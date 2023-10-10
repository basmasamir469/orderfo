<?php

namespace App\Transformers;

use App\Models\Resturant;
use App\Models\Review;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ReviewTransformer extends TransformerAbstract
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
    public function transform(Review $review)
    {
        return [
            'id'=>$review->id,
            'user_name'=>$review->user->fname.' '.$review->user->lname,
            'date'=>Carbon::parse($review->created_at)->format('M d,Y'),
            'comment'=>$review->comment,
            'rate'=> number_format(((float)$review->order_packaging +$review->value_of_money +$review->delivery_time)/3 , 1 , "." ,"")?? 0
        ];
    }
}
