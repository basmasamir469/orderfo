<?php

namespace App\Transformers;

use App\Models\Resturant;
use App\Models\Review;
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
            //
            // 'rate'=>round($review->avg('order_packaging')+$review->avg('delivery_time')+$review->avg('value_of_money'),1),
            // 'count'=>Resturant::find($review->resturant->id)->withCount('reviews')
        ];
    }
}
