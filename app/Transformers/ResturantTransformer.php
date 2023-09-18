<?php

namespace App\Transformers;

use App\Models\Resturant;
use App\Models\Review;
use League\Fractal\TransformerAbstract;

class ResturantTransformer extends TransformerAbstract
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
    public function transform(Resturant $resturant)
    {
        return [
            //
            'id'=>$resturant->id,
            'name'=>$resturant->name,
            'to_time'=>$resturant->to_time,
            'from_time'=>$resturant->from_time,
            'latitude'=>$resturant->latitude,
            'longitude'=>$resturant->longitude,
            'minimum_cost'=>$resturant->minimum_cost,
            'delivery_fee'=>$resturant->delivery_fee,
            'delivery_time'=>$resturant->delivery_time,
            'description'=>$resturant->description,
            'vat'=>$resturant->vat,
            'category_id'=>$resturant->category_id,
            'address'=>$resturant->address,
            'offers'=>count($resturant->sliders)? 1 : 0,
            'logo'=>$resturant->logo,
            'images'=>$resturant->images,
            'reviews'=>$resturant->rate,
            'count_reviews'=>count($resturant->reviews)
        ];
    }

    // public function includeReviews(Resturant $resturant)
    // {
    //     $reviews = $resturant->reviews;

    //     return $this->collection($reviews, new ReviewTransformer()); 
    // }

}
