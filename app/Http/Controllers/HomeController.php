<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\ArbSetting;
use App\Mail\EmailDemo;
use App\Models\Exchange;
use Illuminate\Support\Facades\Mail;
use App\Models\Sound;
use App\Models\CoinjarSetting;
use Session;
use DB;
use Illuminate\Support\ServiceProvider;
use DateTime;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    public function index()
    {
        $pair = DB::table('coinjar_soundstate')
        ->select('*')
        ->where('id', auth()->user()->id)
        ->first();

        Session::put('exchange', '1');
        Session::put('timestamp_sell',"");
        Session::put('timestamp_buy',"");
        $exchange = Exchange::find(auth()->user()->id);
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.exchange.coinjar.com/orders/all?cursor=0");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Authorization: Token token=".$pair->secret_key
        ));

        $response = curl_exec($ch);
        curl_close($ch);


        // // coinjar.com
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://data.exchange.coinjar.com/products/'.$pair->selectedpair.'/book?level=2',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'GET',
        //     CURLOPT_HTTPHEADER => array(
        //         'Authorization: Bearer '.$pair->secret_key
        //     ),
        // ));

        // $response = curl_exec($curl);

        // // curl_close($curl);
        // $response = json_decode($response, true);
        // $exchange_buyer = array_slice($response['bids'], 0, 10);
        // $exchange_seller = array_slice($response['asks'], 0, 10);

        // $min=$exchange_buyer[0][0]; $max=0;
        
        // for( $i=0; $i<10; $i++ ){    
        //     if ($min > $exchange_buyer[$i][0] && $exchange->exchange3==1)
        //     {
        //         $min = $exchange_buyer[$i][0];
        //     }
        //     if ($max<$exchange_seller[$i][0]&&$exchange->exchange3==1)
        //     {
        //         $max = $exchange_seller[$i][0];
        //     }
        // }
        
        // return view('home')->with('exchange_buyer', $exchange_buyer)->with('exchange_seller', $exchange_seller)->with('exchange', $exchange)->with('pair', $pair);

        $data = DB::table('exchanges')
        ->select('*')
        ->where('user_id', auth()->user()->id)
        ->first();

        $response = json_decode($response, true);
        $exchange_buyer = array();
        $exchange_seller = array();

        for( $i=0; $i<count($response); $i++ ) {
            if(count($exchange_buyer) < 10 && $response[$i]["side"] == "buy") {
                array_push($exchange_buyer, $response[$i]);
            } else if(count($exchange_seller) < 10 && $response[$i]["side"] == "sell") {
                array_push($exchange_seller, $response[$i]);
            }
            else if(count($exchange_buyer)> 10 && count($exchange_seller) > 10) {
                continue;
            }
        }


        $origin_timestamp = strtotime($data->updated_at);

        // $current_date = date_create();
        // var_dump(strtotime($current_date->format("c")));
        $last_trade_date = new DateTime($exchange_seller[0]["timestamp"]);
        // var_dump(strtotime($last_trade_date->format("c")));
        // exit;

        DB::update('update exchanges set updated_at = current_timestamp where user_id = '.auth()->user()->id);

        return view('home')->with('exchange_buyer', $exchange_buyer)->with('exchange_seller', $exchange_seller)->with('exchange', $exchange)->with('pair', $pair);
    }

    public function getOrders(Request $request) {

        $pair = DB::table('coinjar_soundstate')
        ->select('*')
        ->where('id', auth()->user()->id)
        ->first();

        $exchange = Exchange::find(auth()->user()->id);
        $lastprice_sell = "";
        $lastprice_buy = "";
        // coinjar.com
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.exchange.coinjar.com/orders/all?cursor=0");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Authorization: Token token=".$pair->secret_key
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, true);
        $exchange_buyer = array();
        $exchange_seller = array();

        $data = DB::table('exchanges')
        ->select('*')
        ->where('user_id', auth()->user()->id)
        ->first();

        $origin_timestamp = strtotime($data->updated_at);

        $lasttimestamp_sell =  Session::get('timestamp_sell');
        $lasttimestamp_buy = Session::get('timestamp_buyr');

        for( $i=0; $i<count($response); $i++ ) {
            $last_trade_date = new DateTime($response[0]["timestamp"]);
            if(count($exchange_buyer) < 10 && $response[0]["side"] == "buy") {
                if($origin_timestamp < strtotime($last_trade_date->format("c"))){
                    $lastprice_buy = "1";
                    $lasttimestamp_buy = $response[$i]["timestamp"];
                } else {
                    $lastprice_buy = "0";
                }
                array_push($exchange_buyer, $response[$i]);
            } else if(count($exchange_seller) < 10 && $response[$i]["side"] == "sell") {
                if($origin_timestamp < strtotime($last_trade_date->format("c"))){
                    $lastprice_sell = "1";
                    $lasttimestamp_sell = $response[$i]["timestamp"];
                } else {
                    $lastprice_sell = "0";
                }
                array_push($exchange_seller, $response[$i]);
            }
            else if(count($exchange_buyer)> 10 && count($exchange_seller) > 10) {
                continue;
            }
        }

        // $current_date = date_create();
        // var_dump(strtotime($current_date->format("c")));
        $last_trade_date = new DateTime($exchange_seller[0]["timestamp"]);
        // var_dump(strtotime($last_trade_date->format("c")));
        // exit;

        $exchange = Exchange::find(auth()->user()->id);
        
        return view('ajax-dom')->with('exchange_buyer', $exchange_buyer)->with('exchange_seller', $exchange_seller)->with('exchange', $exchange)->with('lastprice_sell', $lastprice_sell)->with('lastprice_buy', $lastprice_buy)->with('data', $pair)->with('lasttimestamp_sell',$lasttimestamp_sell)->with('lasttimestamp_buy',$lasttimestamp_buy);
        exit;

        DB::update('update exchanges set updated_at = current_timestamp where user_id = '.auth()->user()->id);

        return view('home')->with('exchange_buyer', $exchange_buyer)->with('exchange_seller', $exchange_seller)->with('exchange', $exchange)->with('pair', $pair);

       if(strcmp(Session::get('exchange'), $response[0]['tid'])!==0){
            if($response[0]['taker_side'] == "sell"){
                $lastprice_sell='1';
                Session::put('timestamp_sell', $response[0]['timestamp']);
                
            }else{
                $lastprice_buy='1';
                Session::put('timestamp_buy', $response[0]['timestamp']);
                
            }
            Session::put('exchange', $response[0]['tid']);
        } else {
            if($response[0]['taker_side'] == "sell"){
                $lastprice_sell='0';
           }else{
                $lastprice_buy='0';
           }
        }
        $lasttimestamp_sell =  Session::get('timestamp_sell');
        $lasttimestamp_buy = Session::get('timestamp_buyr');
        $min=$exchange_buyer[0][0]; 
        $max=0;
        for( $i=0; $i<10; $i++ ){
            
            if ($min>$exchange_buyer[$i][0]&&$exchange->exchange3==1)
            {
                $min = $exchange_buyer[$i][0];
            }
            
            if ($max<$exchange_seller[$i][0]&&$exchange->exchange3==1)
            {
                $max = $exchange_seller[$i][0];
            }
        }
        
        $exchange = Exchange::find(auth()->user()->id);
        
        return view('ajax-dom')->with('exchange_buyer', $exchange_buyer)->with('exchange_seller', $exchange_seller)->with('exchange', $exchange)->with('lastprice_sell', $lastprice_sell)->with('lastprice_buy', $lastprice_buy)->with('data', $data)->with('lasttimestamp_sell',$lasttimestamp_sell)->with('lasttimestamp_buy',$lasttimestamp_buy);
    }
    // change user info 
    public function changeSetting()
    {
        return view('changeForm');    
    }

    // save user info
    public function saveSetting(Request $request)
    {
        if($request['password_confirmation'] != $request['password'])
        {
            return view('changeForm')->with('message', "password invalid!");
        }
        
        $user = User::find(auth()->user()->id);
        $user->password = Hash::make($request['password']);
        return view('changeForm')->with('messages', "Successfly saved!");
    }

    public function getData(Request $request)
    {

        $data = DB::table('coinjar_soundstate')
        ->select('*')
        ->where('id', auth()->user()->id)
        ->first();

        $exchange = Exchange::find(auth()->user()->id);
        $lastprice_sell = "";
        $lastprice_buy = "";
        // coinjar.com
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://data.exchange.coinjar.com/products/'.$data->selectedpair.'/book?level=2',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$data->apitoken
            ),
        ));

        $response = curl_exec($curl);
       
        curl_close($curl);
        $response = json_decode($response, true);
        $exchange_buyer = array_slice($response['bids'], 0, 10);
        $exchange_seller = array_slice($response['asks'], 0, 10);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://data.exchange.coinjar.com/products/'.$data->selectedpair.'/trades?limit=1',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$data->apitoken
            ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);

       if(strcmp(Session::get('exchange'), $response[0]['tid'])!==0){
            if($response[0]['taker_side'] == "sell"){
                $lastprice_sell='1';
                Session::put('timestamp_sell', $response[0]['timestamp']);
                
            }else{
                $lastprice_buy='1';
                Session::put('timestamp_buy', $response[0]['timestamp']);
                
            }
            Session::put('exchange', $response[0]['tid']);
        }else{
            if($response[0]['taker_side'] == "sell"){
                $lastprice_sell='0';
           }else{
                $lastprice_buy='0';
           }
        }
        $lasttimestamp_sell =  Session::get('timestamp_sell');
        $lasttimestamp_buy = Session::get('timestamp_buyr');
        $min=$exchange_buyer[0][0]; 
        $max=0;
        for( $i=0; $i<10; $i++ ){
            
            if ($min>$exchange_buyer[$i][0]&&$exchange->exchange3==1)
            {
                $min = $exchange_buyer[$i][0];
            }
            
            if ($max<$exchange_seller[$i][0]&&$exchange->exchange3==1)
            {
                $max = $exchange_seller[$i][0];
            }
        }
        
        $exchange = Exchange::find(auth()->user()->id);
        
        return view('ajax-dom')->with('exchange_buyer', $exchange_buyer)->with('exchange_seller', $exchange_seller)->with('exchange', $exchange)->with('lastprice_sell', $lastprice_sell)->with('lastprice_buy', $lastprice_buy)->with('data', $data)->with('lasttimestamp_sell',$lasttimestamp_sell)->with('lasttimestamp_buy',$lasttimestamp_buy);
    }

    public function setpair(Request $request){
        DB::update('update coinjar_soundstate set selectedpair = "'.$request['selected'].'" where userid = '.auth()->user()->id);
        return;
    } 

    public function setbuyeraudio(Request $request){
        if ( $request->hasFile('file') ) {
            foreach($request->file as $file) {
                $completeFileName = $file->getClientOriginalName();
                $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();     
                $location = public_path('uploads/buyer/');
                $file->move($location,"buyer_".$completeFileName);
            }    
            DB::update('update coinjar_soundstate set buyer_sound = "buyer_'.$completeFileName.'" where userid = '.auth()->user()->id);    
        }
        return;
    }

    public function setselleraudio(Request $request){
        if ( $request->hasFile('file') ) {
            foreach($request->file as $file) {
                $completeFileName = $file->getClientOriginalName();
                $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();     
                $location = public_path('uploads/seller/');
                $file->move($location, "seller_".$completeFileName);
            } 
            DB::update('update coinjar_soundstate set seller_sound = "seller_'.$completeFileName.'" where userid = '.auth()->user()->id);       
        }

        response()->json("completeFileName");
    }

    public function sellercheckstate(Request $request){
        if($request->seller_check_state == "on"){
            DB::update('update coinjar_soundstate set seller_sound_state = 1 where userid = '.auth()->user()->id);
        }else{
            DB::update('update coinjar_soundstate set seller_sound_state = 0 where userid = '.auth()->user()->id);
        }
        return;
    }

    public function buyercheckstate(Request $request){
        if($request->buyer_check_state == "on"){
            DB::update('update coinjar_soundstate set buyer_sound_state = 1 where userid = '.auth()->user()->id);
        }else{
            DB::update('update coinjar_soundstate set buyer_sound_state = 0 where userid = '.auth()->user()->id);
        }
        return;
    }

    public function settoken(Request $request){
        DB::update('update coinjar_soundstate set api_key = "'.$request['api'].'", secret_key = "'.$request['secret'].'" where userid = '.auth()->user()->id);
    }
}

