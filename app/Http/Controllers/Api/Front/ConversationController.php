<?php

namespace App\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Token;
use App\Transformers\ConversationTransformer;
use App\Transformers\MessageTransformer;
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
        ->whereHas('messages')
        ->search();

        $count = $conversations->count();
        $conversations = $conversations->skip($skip)
            ->take($take)
            ->orderBy('updated_at','desc')
            ->get();

        
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
        $messages = fractal()
        ->collection($conversation->messages)
        ->transformWith(new MessageTransformer())
        ->toArray();   

        return $this->dataResponse($messages, 'conversation messages', 200);
        
    }

    public function sendMessage(Request $request)
    {
        DB::beginTransaction();
        $conversation = Conversation::where(['user_id'=>$request->user()->id,'resturant_id' =>$request->resturant_id])->first() ?? 
            Conversation::create([
                'user_id'=>$request->user()->id,
                'resturant_id'=>$request->resturant_id 
            ]);

       $message = $conversation->messages()->create([
               'text'=>$request->text,
               'sender_type'=>'user',
               'user_id'=>$request->user()->id,
               'resturant_id'=>$request->resturant_id
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
        $tokens = Token::where('resturant_id',$request->resturant_id)->pluck('token')->toArray();
        $data = [
            'message_id' => $message->id
        ];
        $this->notifyByFirebase($tokens,$data,"silent");
        return $this->dataResponse(null,'message sent successfully',200);
    }
}
