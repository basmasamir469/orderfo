<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendResetPasswordCodeRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Mail\ForgetPassword;
use App\Mail\VerifyEmail;
use App\Models\ActivationProcess;
use App\Models\User;
use App\Transformers\UserTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    //
public function register(RegisterRequest $request){

    $data = $request->validated();
    $data['password'] = Hash::make($request->password);
    DB::beginTransaction();
    $user = User::create($data);

    $code = rand(11111,99999);
    ActivationProcess::create([
        'code'  => $code,
        'status'=> 0 ,
        'type'  => 'email',
        'value' => $data['email']
    ]);
    
    DB::commit();
    try{
        Mail::to($user->email)
         ->bcc("basmaelazony@gmail.com")
         ->send(new VerifyEmail($code));
    }catch(\Exception $e){
       // return response()->json(['status'=>false,'message'=>$e->getMessage()]);
    }

    return $this->dataResponse(null, 'verification code is sent to your email check it please', 200);
}

// verifyUser  
public function sendVerifyCode(Request $request){
    // type,value,code
   $validator=Validator::make($request->all(),
   [
    'code'=>'required'
   ]
   );  
   if($validator->fails()){
    return response()->json(['message'=>$validator->errors()->first()]);
   }
   $user=User::whereHas('activation_processes',function($query) use($request){
        return $query->where('code',$request->code);
   })->first();
   if($user){
    $user->update([
        'is_active_email'=>1]);
    $user->activation_processes()->delete();
    return response()->json(['status'=>200,'message'=>'your email is verified successfully']);
    }
    return response()->json(['status'=>422,'message'=>'code is invalid please try again']);
    }




public function login(LoginRequest $request){
    $type=$request->type;
    $value=$request->value;
    // $field=filter_var($value,FILTER_VALIDATE_EMAIL)?'email':'phone';
    // $request->merge([$type=>$value]);
  if(Auth::attempt([$type => $value, 'password' => $request->password])){     
       $user=$request->user();
       $token= $user->createToken("ORDAVO")->plainTextToken;
       if($user->is_active_email){
         return response()->json(['status'=>200,'message'=>'logged in successfully','data'=>[
            'activation'=> 1 ,
            'token' =>$token
        ]]);
        
        }
        return response()->json(['status' => 422, 'message' => 'failed to login your account is not activated','data'=>[
            'activation'=> 0 ,
        ]]);
    }
    return response()->json(['status' => 422, 'message' => 'failed to login password && email does not match our record']);
}

public function forgetPassword(ForgetPasswordRequest $request){
    $type=$request->type;
    $value=$request->value;
    $user=User::where($type,$value)->first();
    $code='020'.rand(11111111,99999999);
    if($user){
        $reset=DB::table('password_resets')->insert([
          'email'=>$value,
          'token'=>$code,
          'created_at'=>Carbon::now('Africa/Cairo')
        ]);
        if($reset){
            Mail::to($value)
            ->bcc("basmaelazony@gmail.com")
            ->send(new ForgetPassword($code));

            return response()->json(['status'=>true,'message'=>'we have sent you reset password code!']);
        }
    }
    return response()->json(['status'=>401,'message'=>'email not found']);

}


public function sendResetPasswordCode(SendResetPasswordCodeRequest $request){
    $request->merge(['value'=>DB::table('password_resets')->select('email')->latest()->first()->email]);
    $code=DB::table('password_resets')->where(['email'=>$request->value,'token'=>$request->code])->first();
    if($code){
        return response()->json(['status'=>200,'message'=>'code is valid']);
    }
    return response()->json(['status'=>422,'message'=>'code is invalid']);

}

public function resetPassword(ResetPasswordRequest $request){

$request->merge(['value'=>DB::table('password_resets')->select('email')->latest()->first()->email]);
$request['password']=Hash::make($request->password);
$type=filter_var($request['value'],FILTER_VALIDATE_EMAIL)?'email':'phone';
$updated=User::where($type,$request['value'])->update([
'password'=>$request['password']
]);
if($updated){
    DB::table('password_resets')->where(['email'=>$request->value])->delete();
   return response()->json(['status'=>200,'message'=>'password is updated successfully']);
}
    return response()->json(['status'=>500,'message'=>'failed to update please try again']);

}


public function logout(Request $request){
    if($request->user()->currentAccessToken()->delete()){
        return response()->json(['status'=>200,'message'=>'logged out successfully']); 
    }
    return response()->json(['status'=>500,'message'=>' failed to logout']); 
}


public function getProfile(){
    $user=auth()->user();
    return response()->json(['status'=>200,'user'=>fractal($user,new UserTransformer($user))->toArray()]);
}

public function updateProfile(UpdateProfileRequest $request){
    $input=$request->all();
    if(empty($request['password'])){
        $input=Arr::except($request->all(),['password']);
    }
    else{
        $input['password']=Hash::make($input['password']);
    }
     if(User::find(auth()->user()->id)->update($input)){
        return response()->json(['status'=>200,'message'=>'profile updated successfully']);
     }
     return response()->json(['status'=>500,'message'=>'failed to update']);

}

public function uploadImage(Request $request){
    // $request->user()->clearMediaCollection('users_images');
    $request->user()->media()->delete();
    if($request->image&&$request->user()
    ->addMedia($request->image)
    ->toMediaCollection('users-images')){
        return response()->json(['status'=>200,'message'=>'image uploaded successfully']);
    }
        return response()->json(['status'=>500,'message'=>'something wrong is happened !failed to upload image ']);
}

}
