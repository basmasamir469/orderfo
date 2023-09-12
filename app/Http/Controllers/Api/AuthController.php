<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckCodeRequest;
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
public function checkCode(CheckCodeRequest $request){
    // type,value,code
  $data = $request->validated();
  $activated = ActivationProcess::where(['type'=>$data['type'],'value'=>$data['value'],'code'=>$data['code']])->first();
  $user = User::where($data['type'],$data['value'])->first();
  if($activated){

    DB::beginTransaction();

    $activated->update(['status'=>1]);

   if($data['type'] == 'email'){

     $user->update([
        'is_active_email'=>1]);
   }else{
     $user->update([
        'is_active_phone'=>1]);
   }
   ActivationProcess::where(['type'=>$data['type'],'value'=>$data['value'],'status'=>0])->first()?->delete();

   DB::commit();

   return $this->dataResponse(null, 'your email is verified successfully', 200);
    }

  return $this->dataResponse(null, 'code is invalid please try again', 422);
    }




public function login(LoginRequest $request){

 $data = $request->validated();
  if(Auth::attempt([$data['type'] => $data['value'], 'password' => $data['password']])){

       $user=$request->user();

       $token= $user->createToken("ORDERFO")->plainTextToken;

       $activated=$data['type']=='email'?$user->is_active_email:$user->is_active_phone;

       if($activated){

        return $this->dataResponse([
            'activation'=> 1 ,
            'token' =>$token ], 'logged in successfully',200);
        
        }
        $code = rand(11111,99999);
        ActivationProcess::create([
            'code'  => $code,
            'status'=> 0 ,
            'type'  => 'email',
            'value' => $data['email']
        ]);
        
            Mail::to($user->email)
             ->bcc("basmaelazony@gmail.com")
             ->send(new VerifyEmail($code));
    
        return $this->dataResponse([
            'activation'=> 0],'failed to login your account is not activated',422);
    }
    return $this->dataResponse(null,'failed to login password && email does not match our record',422);
}

public function forgetPassword(ForgetPasswordRequest $request){

    $data = $request->validated();
    $user = User::where($data['type'],$data['value'])->first();
    $code = '020'.rand(11111111,99999999);

    if($user){

        $reset=DB::table('password_resets')->insert([
          'email'=>$data['value'],
          'token'=>$code,
          'created_at'=>Carbon::now('Africa/Cairo')
        ]);

        if($reset){
            Mail::to($data['value'])
            ->bcc("basmaelazony@gmail.com")
            ->send(new ForgetPassword($code));

            return $this->dataResponse(null, 'we have sent you reset password code!',200);
    
        }

           return $this->dataResponse(null, 'something error is happened please try again!',500);

    }
    return $this->dataResponse(null, 'email not found',401);
}


public function checkResetPasswordCode(SendResetPasswordCodeRequest $request){
    $data = $request->validated();
    // $request->merge(['value'=>DB::table('password_resets')->select('email')->latest()->first()->email]);

    $code = DB::table('password_resets')->where(['email'=>$data['value'],'token'=>$data['code']])->first();

    if($code){
        return $this->dataResponse(null, 'code is valid',200);
    }
       return $this->dataResponse(null, 'code is invalid',422);


}

public function resetPassword(ResetPasswordRequest $request){

$data = $request->validated();
// $request->merge(['value'=>DB::table('password_resets')->select('email')->latest()->first()->email]);
$data['password']=Hash::make($data['password']);
// $type=filter_var($data['value'],FILTER_VALIDATE_EMAIL)?'email':'phone';
$updated = User::where($data['type'],$data['value'])->update([
'password'=>$data['password']
]);
if($updated){

    DB::table('password_resets')->where(['email'=>$data['value']])->delete();

   return $this->dataResponse(null, 'password is updated successfully',200);
}
    return $this->dataResponse(null, 'failed to update please try again',500);

}


public function logout(Request $request){

    if($request->user()->currentAccessToken()->delete()){

    return $this->dataResponse(null, 'logged out successfully',200);

    }

    return $this->dataResponse(null, 'failed to logout',500);
}


public function getProfile(){

    $user = auth()->user();

    return $this->dataResponse(['user'=>fractal($user,new UserTransformer($user))->toArray()], '',200);
}

public function updateProfile(UpdateProfileRequest $request){
    $data=$request->validated();
    if(empty($data['password'])){
        $data=Arr::except($data,['password']);
    }
    else{
        $data['password'] = Hash::make($data['password']);
    }
     if(User::find(auth()->user()->id)->update($data)){

        return $this->dataResponse(null, 'profile updated successfully',200);
     }     
     return $this->dataResponse(null, 'failed to update',500);

}

public function uploadImage(Request $request){
    // $request->user()->clearMediaCollection('users_images');

    $request->user()->media()->delete();

    if($request->image&&$request->user()

    ->addMedia($request->image)

    ->toMediaCollection('users-images')){
        return $this->dataResponse(null, 'image uploaded successfully',200);
    }
    return $this->dataResponse(null, 'something wrong is happened !failed to upload image',500);
}

}
