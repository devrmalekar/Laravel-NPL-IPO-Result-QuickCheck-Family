<?php

namespace App\Http\Controllers;

use App\BankDmatDetail;
use App\People;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request as HttpRequest;
use Goutte\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator ;

class HomeController extends Controller
{

    /**
     * Home page : check your IPO result
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  index(){
        try{
            $client = new Client();
            $crawler = $client->request('GET', 'http://merolagani.com/IpoResult.aspx');
            $company_id=$crawler->filter("#ctl00_ContentPlaceHolder1_ddlIpoCompanyFilter>option")->extract('value'); // get the select option values as an array
            $company_name = $crawler->filter("#ctl00_ContentPlaceHolder1_ddlIpoCompanyFilter>option")->extract(array('_text')); // get the select option text as an array
            $company_list = array_combine($company_id, $company_name); // combine abvoe two array in a single array as key=>value, i.e value=>text
            return view('welcome', ['company_list'=>$company_list]);
        } catch (ConnectException $connectException){
            return view('error',["msg"=>"Failed to connect server http://bizpati.com/ipo/results. Please try again after few minutes later."]);
        } catch (\Exception $exp){
            return view('error',["msg"=>"Some error occured. Please try again."]);
        }


    }

    /**
     * get all ipo results of xyz company
     * @param HttpRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIPOResults(HttpRequest $request){
        try{
            /**
             * validate data
             */
            $request->validate([
                'cid' => 'required',
                'cname'=>'required'
            ]);

            $cid= $request->cid;

            /**
             * select query to get people info and join with bank detail
             */
            $peoples = People::select("people.fname","people.mname","people.lname",
                DB::raw("CONCAT(TRIM(bank_dmat_detail.dmat_id), TRIM(bank_dmat_detail.client_id)) AS BOID"))
                ->where('bank_dmat_detail.dmat_id', '<>', 0)
                ->join('bank_dmat_detail', function($join){
                    $join->on('people.id','=','bank_dmat_detail.pid');
                })
                ->get()->toArray();

            $client = new Client();

            $ipo_results= array();
            $index=0;
            $th_elements=["index","Name", "DEMAT No.", "Applied Kitta", "Alloted Kitta"];

            foreach($peoples as $count=>$detail) {
                set_time_limit(1000);
                $fullname = (strlen(trim($detail['mname'])) == 0) ? trim($detail['fname']) . " " . trim($detail['lname']) : trim($detail['fname']) . " " . trim($detail['mname']) . " " . trim($detail['lname']);
                try {
                    /*
                     * Get hidden form field
                     * __EventTarget, __EventValidation, __EventState
                     */
                    $crawler = $client->request('GET', 'http://merolagani.com/IpoResult.aspx');
                    $__EVENTVALIDATION = $crawler->filter("#__EVENTVALIDATION")->extract('value');
                    $__VIEWSTATE = $crawler->filter("#__VIEWSTATE")->extract('value');
                    $__VIEWSTATEGENERATOR = $crawler->filter("#__VIEWSTATEGENERATOR")->extract('value');
                    $form_field = [
                        '__EVENTARGUMENT' => '',
                        '__EVENTTARGET' => 'ctl00$ContentPlaceHolder1$lbtnSearch',
                        '__EVENTVALIDATION' => $__EVENTVALIDATION[0],
                        '__VIEWSTATE' => $__VIEWSTATE[0],
                        '__VIEWSTATEGENERATOR' => $__VIEWSTATEGENERATOR[0],
                        'ctl00$ASCompany$hdnAutoSuggest' => '0',
                        'ctl00$ASCompany$txtAutoSuggest' => '',
                        'ctl00$AutoSuggest1$hdnAutoSuggest' => '0',
                        'ctl00$AutoSuggest1$txtAutoSuggest' => '',
                        'ctl00$ContentPlaceHolder1$ddlIpoCompanyFilter' => $cid,
                        'ctl00$ContentPlaceHolder1$txtApplicationNo' => trim($detail['BOID']),
                        'ctl00$ContentPlaceHolder1$txtFirstName' => '',
                        'ctl00$ContentPlaceHolder1$txtLastName' => '',
                        'ctl00$txtNews' => '',
                    ];
                    /**
                     * Post form to merolagani.com to get IPO REsult
                     */
                    $crawler = $client->request('POST', 'http://merolagani.com/IpoResult.aspx', $form_field);
                } catch (ConnectException $connectException) {
                    return view('error', ["msg" => "Failed to connect server http://bizpati.com/ipo/results. Please try again after few minutes later."]);
                } catch (\Exception $exp) {
                    return view('error', ["msg" => "Some error occured. Please try again.\n" . print_r($exp->getMessage())]);
                }
                //$th_elements  = $crawler->filter('#ctl00_ContentPlaceHolder1_divData th')->extract(array('_text'));
                /**
                 * if found result fill td_elements as an array
                 */
                $td_elements = $crawler->filter('#ctl00_ContentPlaceHolder1_divData td')->extract(array('_text'));
                $ipo_result=array();

                if (count($td_elements) == 0) {
                    $ipo_result["Name"] = trim($fullname);
                    $ipo_result["DEMAT No."] = trim($detail['BOID']);
                    $ipo_result["Applied Kitta"] = "N/A";
                    $ipo_result["Alloted Kitta"] = "N/A";
                } else {
                    /***
                     * combine table data with table head
                     */
                    $ipo_result = array_combine($th_elements, $td_elements);
                }
                /**
                 * Add results of individuals to others
                 */
                $ipo_results[$index++] = $ipo_result;
            }

            /**
             * Sort array as per alloted kitta, highest to lowest
             */
            foreach ($ipo_results as $key => $row) {
                $vc_array_name[$key] = $row['Alloted Kitta'];
            }
            array_multisort($vc_array_name, SORT_ASC, $ipo_results);

            return view('ipoResults',[
                'ipoResults'=>$ipo_results, 'cname'=>$request->cname
            ]);
        } catch (\Exception $exception){
            return view('error', ["msg" => "Some error occured. Please try again.\n" . print_r($exception->getMessage())]);
        }
    }

    /**
     * List all people in our database with their bank detail, thus using bank_id as a primary key
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPeopleList(){
        try{
            $peoples = People::select("*",
                DB::raw("CONCAT(bank_dmat_detail.dmat_id, bank_dmat_detail.client_id) AS BOID, acc_num, bank_name, bank_dmat_detail.id as bid "))
                ->join('bank_dmat_detail', function($join){
                    $join->on('people.id','=','bank_dmat_detail.pid');
                })
                ->get()->toArray();

            return view('peopleList', [
                'peoples'=>$peoples, 'tr_class'=>['active','success','danger','warning','info']
            ]);
        } catch (\Exception $exception){
            return view('error', ["msg" => "Some error occured. Please try again.\n" . print_r($exception->getMessage())]);
        }
    }

    /**
     * insert and update existing people and bank table
     * @param HttpRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function crud_people(HttpRequest $request){
        try{
            $rules = [
                'fname' => 'required',
                'lname'=>'required',
                'bname' => 'required',
                'cnum' => 'required|unique:people,citizenship_no',
                'dos' => 'required|date_format:"Y-m-d"',
                'accnum'=>'required|unique:bank_dmat_detail,acc_num',
                'did' => 'required',
                'cid'=>'required|unique:bank_dmat_detail,client_id'
            ];

            $message=[
                'fname.required'=>'First name is required field',
                'lname.required'=>'Last name is required field',
                'bname.required'=>'Bank name is required field',
                'cnum.required'=>'Citizenship Number is required field',
                'dos.required'=>'Date of Issue is required field',
                'accnum.required'=>'Account Number is required field',
                'did.required'=>'DMAT Id  is required field',
                'cid.required'=>'Client Id is required field',
                'cnum.unique'=>'Citizenship Number already exists in our database.',
                'dos.date_format'=>'Incorrect Date format. Date format must be YYYY-MM-DD.',
                'accnum.unique'=>'Account Number already exists in our database.',
                'cid.unique'=>'Client Id already exists in our database.',
            ];

            if($request->task=='update') {
                $rules['cnum'] = $rules['cnum'].','.$request->pid;
                $rules['accnum'] = $rules['accnum'].','.$request->bid;
                $rules['cid'] = $rules['cid'].','.$request->bid;
            }


            $request->validate($rules,$message);

            $dos = ($request->dos)?trim($request->dos):null;
            try{
                if($request->task=='insert'){
                    $people = new People([
                        'fname'=>trim($request->fname),
                        'mname'=>trim($request->mname),
                        'lname'=>trim($request->lname),
                        'addr'=>trim($request->addr),
                        'father_name'=>trim($request->faname),
                        'gfather_name'=>trim($request->gname),
                        'citizenship_no'=>trim($request->cnum),
                        'date_of_issue'=>$dos
                    ]);
                    $people->save();

                    $bank_detail= new BankDmatDetail([
                        'bank_name'=>trim($request->bname),
                        'acc_num'=>trim($request->accnum),
                        'dmat_id'=>trim($request->did),
                        'client_id'=>trim($request->cid),
                        'pid'=>$people->id
                    ]);
                    $bank_detail->save();
                    return redirect('/people/'.encrypt($bank_detail->id));
                } else {
                    $people=People::find($request->pid);
                    $people->fname=trim($request->fname);
                    $people->mname=trim($request->mname);
                    $people->lname=trim($request->lname);
                    $people->addr=trim($request->addr);
                    $people->father_name=trim($request->faname);
                    $people->gfather_name=trim($request->gname);
                    $people->citizenship_no=trim($request->cnum);
                    $people->date_of_issue=$dos;

                    $people->save();

                    $bank=BankDmatDetail::find($request->pid);
                    $bank->bank_name=trim($request->bname);
                    $bank->acc_num=trim($request->accnum);
                    $bank->dmat_id=trim($request->did);
                    $bank->client_id=trim($request->cid);
                    $bank->pid=$people->id;

                    $bank->save();
                    return redirect('/people/'.encrypt($bank->id));
                }

            } catch(\Illuminate\Database\QueryException $queryException){
                return view('error', ["msg" => "Some error occured. Please try again.\n" . print_r($queryException->errorInfo[1])]);
            } catch (\Exception $exception){
                return view('error', ["msg" => "Some error occured. Please try again.\n" . print_r($exception->getMessage())]);
            }
        } catch (\Exception $exception){
            return view('error', ["msg" => "Some error occured. Please try again.\n" . print_r($exception->getMessage())]);
        }
    }

    /***
     * list detail of an individual using bank id
     * @param $bid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function saved_people($bid){
        try{
            $bid=decrypt($bid);
            $peoples = People::select("*",
                DB::raw("CONCAT(bank_dmat_detail.dmat_id, bank_dmat_detail.client_id) AS BOID, acc_num, bank_name, bank_dmat_detail.id as bid"))
                ->where('bank_dmat_detail.id','=',$bid)
                ->join('bank_dmat_detail', function($join){
                    $join->on('people.id','=','bank_dmat_detail.pid');
                })
                //->where('people.id','=',decrypt($id))
                ->get()->toArray();

            return view('peopleList', [
                'peoples'=>$peoples, 'tr_class'=>['success','','','','']
            ]);
        } catch (\Exception $exception){
            return view('error', ["msg" => "Some error occured. Please try again.\n" . print_r($exception->getMessage())]);
        }
    }

    /**
     * get the update form
     * @param $bid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateForm($bid){
        try{
            $bid=decrypt($bid);
            $peoples = People::select("*",
                DB::raw("CONCAT(bank_dmat_detail.dmat_id, bank_dmat_detail.client_id) AS BOID, acc_num, bank_name,bank_dmat_detail.id as bid"))
                ->where('bank_dmat_detail.id','=',$bid)
                ->join('bank_dmat_detail', function($join){
                    $join->on('people.id','=','bank_dmat_detail.pid');
                })
                ->get()->toArray();

            return view('peopleForm',[
                'peoples'=>$peoples[0],'task'=>'update'
            ]);
        } catch (\Exception $exception){
            return view('error', ["msg" => "Some error occured. Please try again.\n" . print_r($exception->getMessage())]);
        }
    }
}
