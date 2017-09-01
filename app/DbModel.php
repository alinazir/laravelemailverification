<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class DbModel extends Model
{
     public function Registered($data)
     {
         return DB::table('signup')->insert($data);
     }

     public function VerifyEmail($token)
     {
         return  DB::table('signup')->where('verify_token','=',$token)->get();
     }
     public function Autherized($token)
     {
            return DB::table('signup')->where('verify_token','=',$token)->update(['status'=>1,'verify_token'=>null]);
     }

     public function Login($email,$password)
     {
        return  DB::table('signup')->where([['email','=',$email],['pwd','=',$password]])->get();
     }
}
