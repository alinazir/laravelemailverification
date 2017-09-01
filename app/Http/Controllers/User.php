<?php

namespace App\Http\Controllers;
use Mail;
use App\Mail\VerfiyUser;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Validation;
use App\DbModel;


class User extends Controller
{

      public function index()
      {
       return view('Signup');
      }
      public function Registered(Request $request)
      {
           Validator::make($request->all(),['uname'=>'required','email'=>'required|email','city'=>'required','pwd'=>'required'])->validate();
          $token=Str::random(40);
           $data=[
                     'name'=>$request->uname,
                     'email'=>$request->email,
                     'city'=>$request->city,
                     'pwd'=>$request->pwd,
                     'status'=>0,
                     'verify_token'=>$token,
                 ];
            $db=new DbModel( );
            $res=$db->Registered($data);
            if ($res==1) {

              $this->SendEmail($token);
              return redirect('/Verify');
            }
            else {
              return redirect('/')->with('message','Some error is occured while regisration, try later');
            }
      }

      private function SendEmail($token)
      {
           Mail::send(new VerfiyUser($token));
      }
      public function Verify()
      {
         return view('Verify');
      }
       public function Autherized($token)
       {
          $db=new DbModel();
          $person=$db->VerifyEmail($token);
          //dd($db);
        if (count($person)>0) {
            $db->Autherized($token);
            return   redirect()->route('loginpage')->with('message','you are successfully verified');
          }
          else {
            return view('NotFoundError');
          }




        //  return 'DB '.$db->VerifyEmail($token).'  Email: '.$token;
        //  return  view('Verified');
       }
      public function loginview($value='')
      {
          return view('Login');
      }
      public function login(Request $req)
      {
              $validator=Validator::make($req->all(),$this->rules(),$this->messages())->validate();

            /*  if ($validator->fails()) {
                return redirect('/')->withErrors($validator)->withInput();
                //return 'fails validation';
              }
              */
              $db=new DbModel();
              $email=$req->email;
              $pwd=$req->pwd;
              $detail=$db->Login($email, $pwd);
            //  dd($detail);
             if ($detail->count()>0) {
                foreach ($detail as $data) {
                   if ($data->status==0) {
                     //return redirect()->route('verifyFirst');
                     return redirect('/LoginUser')->with('message', 'Please Verify Your Account First');
                   }
                   else {
                     return view('welcome')->with('email', $email);
                   }
                }

              }
              else {
                 return redirect('/LoginUser')->with('message', 'User name or password is incorrect');
              }



      }
 public function AccountVerifyError()
 {
    return view('AccountVerify');
 }
      public static  function rules(){
        return [
                'email'=>'required | email',
                'pwd'=>'required',

        ];
      }
      public static function messages(){
        return [
                'required'=>':attribute is empty',
                'email'=>':attribute isn\'t valid'
        ];
      }



}
