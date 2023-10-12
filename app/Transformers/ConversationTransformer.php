<?php

namespace App\Transformers;

use App\Models\Conversation;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ConversationTransformer extends TransformerAbstract
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
        'messages'
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Conversation $conversation)
    {
        $last_message = $conversation->messages()->latest()->first();
        $array= [
            'id' => $conversation->id,
            'sender_name' =>auth()->user()->hasRole('resturant')? $conversation->user->fname : $conversation->resturant->name,
            'sender_logo' =>auth()->user()->hasRole('resturant')? $conversation->user->getFirstMediaUrl('users-images') :$conversation->resturant->getFirstMediaUrl('resturants-logos'),
            'is_muted'=>$conversation->is_muted,
            'last_message_date'=>$conversation->last_message_date,
            'last_message'=> fractal($last_message,new MessageTransformer())->toArray(),
        ];

        return $array;
    }

    public function includeMessages(Conversation $conversation)
    {
       $messages = $conversation->messages;

       return $this->collection($messages, new MessageTransformer()); 
    }

}
