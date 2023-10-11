<?php

namespace App\Transformers;

use App\Models\Notification;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
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
    public function transform(Notification $notification)
    {
        return [

            'title'  => $notification->title,
            'content'=> $notification->content,
            'date'   => Carbon::parse($notification->created_at)->diffForHumans(),
            'order_id' => $notification->action_id

              ];
    }
}
