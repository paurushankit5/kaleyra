<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

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
        $users  = User::where('id','!=', \Auth::user()->id)->paginate(100);
        $array =    array('users'=> $users);
        return view('home', $array);
    }

    public function makeacall(Request $request){
        $user = User::findOrFail($request->id);
        $this->voice(\Auth::user()->mobile,$user->mobile);
        echo 1;
    }
    protected function voice($caller, $receiver){
        //$caller = "917531855396"; //Your SMS Gateway Center Account Username
        //$receiver = "7545829633";  //Your SMS Gateway Center Account Password
        $smsgatewaycenter_com_url = "https://api-voice.kaleyra.com/v1/?"; //SMS Gateway Center API URL
        $parameters = 'api_key='.env('VOICE_API_KEY');
        $parameters.= '&method='."dial.click2call";
        $parameters.= '&caller='.urlencode($caller);
        $parameters.= '&receiver='.urlencode($receiver);
        //$parameters.= '&msg='.urlencode($sendmessage);
        $api_url =  $smsgatewaycenter_com_url.$parameters;
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);            
        return($curl_scraped_page);
    }

    protected function sms($caller, $receiver){
        //$caller = "917531855396"; //Your SMS Gateway Center Account Username
        //$receiver = "7545829633";  //Your SMS Gateway Center Account Password
        $smsgatewaycenter_com_url = "https://api-voice.kaleyra.com/v1/?"; //SMS Gateway Center API URL
        $parameters = 'api_key='.env('VOICE_API_KEY');
        $parameters.= '&method='."dial.click2call";
        $parameters.= '&caller='.urlencode($caller);
        $parameters.= '&receiver='.urlencode($receiver);
        //$parameters.= '&msg='.urlencode($sendmessage);
        $api_url =  $smsgatewaycenter_com_url.$parameters;
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);            
        return($curl_scraped_page);
    }
}
