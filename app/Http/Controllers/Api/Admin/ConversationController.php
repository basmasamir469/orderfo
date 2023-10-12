<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Token;
use App\Transformers\ConversationTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\Conversions\Conversion;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $skip = $request->skip? $request->skip : 0;
        $take = $request->skip? $request->take : 10;
        $conversations = $request->user()->conversations()
                                         ->search()
                                         ->skip($skip)
                                         ->take($take)
                                         ->orderBy('updated_at','desc')
                                         ->get();

        $count =count($request->user()->conversations);
        $conversations = fractal()
        ->collection($conversations)
        ->transformWith(new ConversationTransformer())
        ->toArray();   
        return $this->dataResponse([
            'conversations' => $conversations,
            'count' => $count
        ], 'your conversations', 200);
                               
    }
    
    public function show($id)
    {
        $conversation = Conversation::findOrFail($id);

        return $this->dataResponse(fractal($conversation,new ConversationTransformer())->parseIncludes('messages')->toArray(), 'conversation messages', 200);
        
    }

    public function sendMessage(Request $request)
    {   
        DB::beginTransaction();
        $conversation = Conversation::where(['user_id'=>$request->user_id,'resturant_id' =>$request->user()->id])->first()?? 
                        Conversation::create([
                           'user_id'=>$request->user_id,
                           'resturant_id'=>$request->user()->id ]);

       $message= $conversation->messages()->create([
               'text'=>$request->text,
               'sender_type'=>'resturant',
               'user_id'=>$request->user_id,
               'resturant_id'=>$request->user()->id
            ]);

       $conversation->update([
           'updated_at'=>$message->created_at
           ]);

        DB::commit();
        

        if($request->image)
        {
                $message->addMedia($request->image)
                        ->toMediaCollection('messages-images');
        }
        $tokens = Token::where('user_id',$request->user_id)->pluck('token')->toArray();
        $data=[
          'silent'=>true
        ];
        $this->notifyByFirebase($tokens,$data,"silent");
        return $this->dataResponse(null,'message sent successfully',200);
    }
}
