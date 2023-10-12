<?php

namespace App\Transformers;

use App\Models\Message;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class MessageTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Message $message)
    {
        return [
            'id'         =>$message->id,
            'message'    =>$message->text,
            'image'      =>$message->getFirstMediaUrl('messages-images'),
            'time'       =>Carbon::parse($message->created_at)->format('h:i A'),
            'date'       =>$message->date,
            'is_read'    =>$message->is_read,
            'sender_name'=>$message->sender_type  == "user"? $message->user->name : $message->resturant->name,
            'sender_image'=>$message->sender_type == "user"? $message->user->getFirstMediaUrl('users-images') : $message->resturant->getFirstMediaUrl('resturants-logos'),
        ];
    }
}
