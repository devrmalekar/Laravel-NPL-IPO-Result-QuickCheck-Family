<?php

namespace App\Http\Controllers;

use App\People;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request as HttpRequest;
use Goutte\Client;
use Illuminate\Support\Facades\DB;


class HomeController_bizpatti extends Controller
{

    /**
     * Home page : check your IPO result
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  index(){
        try{
            $client = new Client();
            $crawler = $client->request('GET', 'http://bizpati.com/ipo/results');
            $company_id=$crawler->filter("select>option")->extract('value'); // get the select option values as an array
            $company_name = $crawler->filter("select>option")->extract(array('_text')); // get the select option text as an array
            $company_list = array_combine($company_id, $company_name); // combine abvoe two array in a single array as key=>value, i.e value=>text
            return view('welcome', ['company_list'=>$company_list]);
        } catch (ConnectException $connectException){
            return view('error',["msg"=>"Failed to connect server http://bizpati.com/ipo/results. Please try again after few minutes later."]);
        } catch (\Exception $exp){
            return view('error',["msg"=>"Some error occured. Please try again."]);
        }


    }

    public function getIPOResults(HttpRequest $request){
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


        foreach($peoples as $count=>$detail){
            set_time_limit(1000);
            $fullname=(strlen(trim($detail['mname']))==0)?trim($detail['fname']) ." ".trim($detail['lname']) : trim($detail['fname']) ." ".trim($detail['mname'])." ".trim($detail['lname']);
            try{
                $crawler = $client->request('POST', 'http://bizpati.com/ipo/results',[
                    'ipo_search[ipo_company_id]'=>$cid,
                    'ipo_search[fullname]'=>trim($fullname),
                    'ipo_search[application_id]'=>trim($detail['BOID']),
                    'ipo_search[applied_share]'=>'',
                    'submit'=>'submit'
                ]);
            } catch (ConnectException $connectException){
                return view('error',["msg"=>"Failed to connect server http://bizpati.com/ipo/results. Please try again after few minutes later."]);
            } catch (\Exception $exp){
                return view('error',["msg"=>"Some error occured. Please try again.\n".print_r($exp->getMessage())]);
            }

            $th_elements  = $crawler->filter('table[id=example]>thead>tr:first-child>th')->extract(array('_text'));
            $td_elements = $crawler->filter('table[id=example]>tbody>tr:first-child>td')->extract(array('_text'));
            $ipo_result=array();

            if(count($td_elements)==0){
                $ipo_result["Full Name"]=trim($fullname);
                $ipo_result["BOID No."]=trim($detail['BOID']);
                $ipo_result["Share Applied"]="N/A";
                $ipo_result["Share Alloted"]="N/A";
            } else{
                $ipo_result=array_combine($th_elements, $td_elements);
            }
            $ipo_results[$index++]=$ipo_result;

        }

        foreach ($ipo_results as $key => $row) {
            $vc_array_name[$key] = $row['Share Alloted'];
        }
        array_multisort($vc_array_name, SORT_ASC, $ipo_results);

        return view('ipoResults',[
            'ipoResults'=>$ipo_results, 'cname'=>$request->cname
        ]);
    }

    public function getPeopleList(){
        $peoples = People::select("*",
            DB::raw("CONCAT(bank_dmat_detail.dmat_id, bank_dmat_detail.client_id) AS BOID, acc_num, bank_name "))
            ->join('bank_dmat_detail', function($join){
                $join->on('people.id','=','bank_dmat_detail.pid');
            })
            ->get()->toArray();

        return view('peopleList', [
            'peoples'=>$peoples, 'tr_class'=>['active','success','danger','warning','info']
        ]);
    }
}
