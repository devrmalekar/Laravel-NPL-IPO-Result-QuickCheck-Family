<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');
Route::get('/IpoResult',function (){
    return redirect('/');
});
Route::post('/IpoResult','HomeController@getIPOResults')->name('ipo.getResult');
Route::get('/people/list','HomeController@getPeopleList')->name('ipo.getList');
Route::get('/people/new',function(){
    return view('peopleForm',[
        'task'=>'insert'
    ]);
});
Route::post('/people/new','HomeController@crud_people')->name('people.add');
Route::get('/people/{bid}','HomeController@saved_people')->name('people.save');
Route::get('/people/update/{cnum}','HomeController@UpdateForm');


//Route::post('/IpoResult/bizpati','HomeController_bizpatti@getIPOResults')->name('ipo.getResult');
//Route::get('/bizpati','HomeController_bizpatti@index');