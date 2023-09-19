<?php

namespace App\Transformers;

use App\Models\Resturant;
use App\Models\Review;
use League\Fractal\TransformerAbstract;

class ResturantTransformer extends TransformerAbstract
{

    public function __construct($type = false)
    {
        $this->type = $type;
    }

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
        'payment_ways'
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
        $array = [
            'id'=>$resturant->id,
            'name'=>$resturant->name,
            'delivery_time'=>$resturant->delivery_time,
            'logo'=>$resturant->logo,
            'image'=>$resturant->getFirstMediaUrl(),
            'reviews'=>$resturant->rate,
            'count_reviews'=>count($resturant->reviews),
            'is_offered' => 0,
            'is_favourite' => 0,

            // 'from_time'=>$resturant->from_time,
            // 'latitude'=>$resturant->latitude,
            // 'longitude'=>$resturant->longitude,
            // 'minimum_cost'=>$resturant->minimum_cost,
            // 'delivery_fee'=>$resturant->delivery_fee,
            // 'description'=>$resturant->description,
            // 'vat'=>$resturant->vat,
            // 'category_id'=>$resturant->category_id,
            // 'address'=>$resturant->address,
            // 'offers'=>count($resturant->sliders)? 1 : 0,
            
        ];


        if ($this->type == 'show') {
            $array['to_time'] = $resturant->to_time;
        }

        return $array;

    }

     public function includePaymentWays(Resturant $resturant)
      {
         $payment_ways = $resturant->paymentWays;

         return $this->collection($payment_ways, new PaymentWayTransformer()); 
      }

}
