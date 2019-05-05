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

    public function sendsms(Request $request){
        $user = User::findOrFail($request->id);
        $this->sms($user->mobile, $request->msg);
        echo 1;
    }
    protected function sms($mobile, $sendmessage)
    {
        //http://apps.smslane.com/vendorsms/pushsms.aspx?user=Tarun%20Manna&password=tarun_123&msisdn=919002187227&sid=EXAGRO&msg=sms testing &fl=0&gwid=2
        $smsgatewaycenter_com_user = env('SMS_USER'); //Your SMS Gateway Center Account Username
        $smsgatewaycenter_com_password = env('SMS_PASSWORD');  //Your SMS Gateway Center Account Password
        $smsgatewaycenter_com_url = "http://nimbusit.co.in/api/swsendSingle.asp?"; //SMS Gateway Center API URL
        $smsgatewaycenter_com_mask = "GYANII"; //Your Approved Sender Name / Mask
        $parameters = 'username='.$smsgatewaycenter_com_user;
        $parameters.= '&password='.$smsgatewaycenter_com_password;
        $parameters.= '&sendto='.urlencode($mobile);
        $parameters.= '&sender='.urlencode($smsgatewaycenter_com_mask);
        $parameters.= '&message='.urlencode($sendmessage);
        //$parameters.= '&fl=0&gwid=2';
        $api_url =  $smsgatewaycenter_com_url.$parameters;
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);            
        return($curl_scraped_page);
    }

    public function sendFriendRequest($id){
        try{
            $user = User::findOrFail($id);
            \Auth::user()->befriend($user);
            return redirect (route('home'));
        }
        catch (\Exception $e) {
            return redirect (route('home'));
        }
        
    }

    public function askforhelp(){
        $users = User::where('id','!=',\Auth::user()->id)->select('mobile','name')->get();
        if(count($users))
        {
            foreach($users as $user)
            {
                $msg = "Hi $user->name, I am ".\Auth::user()->name.", I need help. Please rescue me from this place.";
                //echo $msg;
                $this->sms($user->mobile, $msg);

            }
            echo "<script>alert('Message sent');</script>";

        }
        return redirect(route('home'));

    }
}
