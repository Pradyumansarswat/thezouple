<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Redirect,route,Session,View,Validator,Config,Hash;
use DB;

use App\User;
use App\Contact;
use Mail;

class UserController extends Controller
{
    public function newsubscribers_list()
    {
        $data['news_data'] = DB::table('subscribe')->orderby('subscribe_id','desc')->get();
        $page_title = "Subscribers - Zouple";
       return view('masters.user.newsubscribers',compact('page_title'),$data);
    }
    public function newsubscribersdestory(Request $request,$id)
    {
        DB::table('subscribe')->where('subscribe_id','=',$id)->delete();
        $request->session()->flash('alert-success','Subscribers has been sucessfully deleted.');
        return Redirect::route('newsubscribers');
    }

    /* User List Code */

    public function user_list()
    {

       $data['userlist_data'] = DB::table('users')
            ->where('users.user_role','FRONT')
            ->orderBy('users.id')
            ->get();

       $page_title = "User List - Zouple";
       return view('masters.user.user_list',compact('page_title'),$data);
    }

    public function user_data_delete(Request $request,$id)
    { 
        $check1 = User::where('id',$id)->delete();
        $check2 = DB::table('user_information')->where('user_id',$id)->delete();
       
        return Redirect::route('userlist');
    }
    
    public function userAddressPage(Request $request,$id)
    {
        $data['userAddressData'] = DB::table('user_information')->where('user_id',$id)->get();
        $page_title = "User Address - Zouple";
        return view('masters.user.user_address',compact('page_title'),$data);
    }

    public function change_user_password_update_save(Request $request)
    {
      $id = $request->id;
      $old_pass = Auth::user()->password;
      $old_password = $request->oldpassword;
      if((Hash::check($old_password , $old_pass)))
      {
          
        $input['password']=Hash::make($request['password']);
         $input['email']=$request->email; 
         User::where('id','=',$id)->update($input); 
          
          Auth::logout();
          $request->session()->flash('alert-danger','Password has been sucessfully updated.'); 
          return redirect('masterAdmin');
      }
      else
      {
         
          $request->session()->flash('alert-danger','Old Password does not match !!');
          return Redirect::route('userlist');
          
      }
  }


    public function user_update_save(Request $request)
    {
        $input = $request->all();
        DB::table('users')->where('id',$input['id'])->update($input);
        $request->session()->flash('alert-success','User Status has been sucessfully updated.');
        return Redirect::route('userlist');
    }


    /* User List Code */

    /* Contact Code */

    public function contact_information_list(Request $request)
    {
      $data['contact_data'] = Contact::orderBy('contact_id', 'DESC')->get();
      $page_title = "Contact List - Zouple";
      return view('masters.user.contact_list',compact('page_title'),$data);
    }

    public function contactDelete_format(Request $request,$contact_id)
    {
        Contact::where('contact_id','=',$contact_id)->delete();
        $request->session()->flash('alert-success','Contact has been sucessfully deleted.');
        return Redirect::route('contact_information');
    }

    public function contactReplayPage(Request $request,$contact_id)
    {
        $page_title = "Contact Replay  - Zouple";

        $data['contact_datas'] =Contact::where('contact_id','=',$contact_id)->get();
        return view('masters.user.contact_replay_page',compact('page_title'), $data);
    }

    public function contactSeeMorePage(Request $request,$contact_id)
    {
        $page_title = "Contact See More Page  - Zouple";
        $data['contactSeeMoreDatas'] =Contact::where('contact_id','=',$contact_id)->get();
        return view('masters.user.contact_seemore_page',compact('page_title'), $data);
    }

    public function reloay_store(Request $request)
    {
        $email = $request->email;
        $message = $request->message;
        $messageBody = $message;       
        $subject = "Reply from Zouple - Contact Enquiry";
        $data['msg'] = $messageBody;
        $data['subject'] = $subject;
        $data['email'] = $email;

        // Save reply to database
        $replyData = [
            'email' => $email,
            'description' => $messageBody,
            'date' => date('d/M/Y'),
            '_token' => $request->_token,
        ];
        DB::table('replay_contact')->insert($replyData);

        // Send email with error handling
        try {
            Mail::send([], [], function ($message) use($data) {
                $message->to($data['email'])
                    ->subject($data['subject'])
                    ->setBody($data['msg'], 'text/html');
            });
            $request->session()->flash('alert-success', 'Reply has been sent successfully!');
        } catch (\Exception $e) {
            $request->session()->flash('alert-warning', 'Reply saved but email could not be sent. Please check mail settings.');
        }

        return Redirect::route('contact_information');
    }


    public function mess_replay_list(Request $request)
    {
       $page_title = "Message Replay List - Zouple";

       $data['replay_data'] = DB::table('replay_contact')->orderby('replay_id', 'asc')->get();
       return view('masters.user.mess_list',compact('page_title'), $data);
    }

    public function messageDelete_format(Request $request,$replay_id)
    {
     
        $m = DB::table('replay_contact')->where('replay_id','=',$replay_id)->delete();
        $request->session()->flash('alert-success','Message has been deleted Successfully!!');
        return Redirect::route('mess_replay');
    }

    /* Contact Code */


    /* Message Code Start */

     public function messageSendPage(Request $request)
    {
      $data['messageData'] = DB::table('send_message')->get();
      $page_title = "Message Send List - Zouple";
      return view('masters.user.messageSend',compact('page_title'),$data);
    }

    public function messageSendDeleteFormat(Request $request,$send_message_id)
    {
        DB::table('send_message')->where('send_message_id','=',$send_message_id)->delete();
        $request->session()->flash('alert-success','Message Send has been sucessfully deleted.');
        return Redirect::route('messageSend');
    }

    /* Message Code End */
}
