<?php

namespace App\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckCodeRequest;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendResetPasswordCodeRequest;
use App\Http\Requests\TokenRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Mail\ForgetPassword;
use App\Mail\VerifyEmail;
use App\Models\ActivationProcess;
use App\Models\Token;
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
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    //
public function register(RegisterRequest $request){

    $data = $request->validated();
    $data['password'] = Hash::make($request->password);
    DB::beginTransaction();
    $user = User::create($data);
    $role = Role::where(['name'=>'user','guard_name'=>'api'])->first();
    $user->assignRole($role);

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
public function verifyUser(CheckCodeRequest $request){
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
   ActivationProcess::where(['type'=>$data['type'],'value'=>$data['value'],'status'=>0])->delete();

   DB::commit();

   return $this->dataResponse(null, 'your email is verified successfully', 200);
    }

  return $this->dataResponse(null, 'code is invalid please try again', 422);
    }




public function login(LoginRequest $request){

 $data = $request->validated();
  if(Auth::attempt([$data['type'] => $data['value'], 'password' => $data['password']])){

       $user=$request->user();

       $activated=$data['type']=='email'?$user->is_active_email:$user->is_active_phone;

       if($activated){
            $token= $user->createToken("ORDERFO")->plainTextToken;

            return $this->dataResponse([
                'activation'=> 1 ,
                'token' =>$token ], 'logged in successfully',200);
            
        }
        // send code according to type
        $code = rand(11111,99999);
        ActivationProcess::create([
            'code'  => $code,
            'status'=> 0 ,
            'type'  => $data['type'],
            'value' => $data['value']
        ]);
        if($data['type']=='email'){
            Mail::to($user->email)
            ->bcc("basmaelazony@gmail.com")
            ->send(new VerifyEmail($code));
   
       return $this->dataResponse([
           'activation'=> 0],'failed to login your account is not activated check your email',200);

        }

        // if data type is phone 


    }
    return $this->dataResponse(null,'failed to login password && email does not match our record',422);
}

public function forgetPassword(ForgetPasswordRequest $request){

    $data = $request->validated();
    $user = User::where($data['type'],$data['value'])->first();
    $code = rand(11111111,99999999);

    if($user){

        $reset=DB::table('password_resets')->insert([
          'value'=>$data['value'],
          'token'=>$code,
          'created_at'=>Carbon::now('Africa/Cairo')
        ]);

        if($reset){

            if($data['type']=='email'){
            Mail::to($data['value'])
            ->bcc("basmaelazony@gmail.com")
            ->send(new ForgetPassword($code));

            return $this->dataResponse(null, 'we have sent you reset password code!',200);
            }

            // if data type is phone 



    
        }

           return $this->dataResponse(null, 'something error is happened please try again!',500);

    }
    return $this->dataResponse(null, 'email not found',401);
}


public function checkResetPasswordCode(SendResetPasswordCodeRequest $request){
    $data = $request->validated();
    // $request->merge(['value'=>DB::table('password_resets')->select('email')->latest()->first()->email]);

    $code = DB::table('password_resets')->where(['value'=>$data['value'],'token'=>$data['code']])->first();

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

    DB::table('password_resets')->where(['value'=>$data['value']])->delete();

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
    if(Hash::check($data['password'],auth()->user()->password)){
     if(User::find(auth()->user()->id)->update($data)){

        return $this->dataResponse(null, 'profile updated successfully',200);
     }     
     return $this->dataResponse(null, 'failed to update',500);
    }
    return $this->dataResponse(null, ' failed to update password does not match our record ',422);

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

public function submitToken(TokenRequest $request)
{

  $data  = $request->validated();

//   $request->user()->tokens()->first()->updateOrCreate([
//     'device_id'   => $data['device_id'],
//     'token'       => $data['token'],
//     'device_type' => $data['device_type']
//    ]);

   $token = Token::updateOrCreate(
        ['device_id' => $data['device_id']],
        [
            'user_id'     => $request->user()->id,
            'token'       => $data['token'],
            'device_type' => $data['device_type']
        ]
    );

    return $this->dataResponse(null,__('submitted successfully'),200);

  }

}
