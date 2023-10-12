<?php

namespace App\Transformers;

use App\Models\Resturant;
use App\Models\Review;
use League\Fractal\TransformerAbstract;

class ResturantTransformer extends TransformerAbstract
{
    private $type;

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
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
        'payment_ways','meals'
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
            'reviews'=>$resturant->rate,
            'count_reviews'=>count($resturant->reviews),
            'is_offered' => $resturant->is_offered,
            'is_favourite' => $resturant->is_favourite,
            'status'=>$resturant->status
        ];


        if ($this->type == 'show') {

            $array['to_time'] = $resturant->to_time;
            $array['from_time'] = $resturant->from_time;
            $array['latitude'] = $resturant->latitude;
            $array['longitude'] = $resturant->longitude;
            $array['minimum_cost'] = $resturant->minimum_cost;
            $array['delivery_fee'] = $resturant->delivery_fee;
            $array['description'] = $resturant->description;
            $array['vat'] = $resturant->vat;
            $array['category_id'] = $resturant->category_id;
            $array['category_name'] = $resturant?->category?->name;
            $array['address'] = $resturant->address;
            $array['resturant_images']=$resturant->images->map(function($image){
                return[
                  'url'=>$image->getUrl()
                ];
            });
        }

        if($this->type=="dashboard"){
            unset($array['name']);
            $array['name_en']=$resturant->translate('en')->name;
            $array['name_ar']=$resturant->translate('ar')->name;
        }

        return $array;

    }

     public function includePaymentWays(Resturant $resturant)
      {
         $payment_ways = $resturant->paymentWays;

         return $this->collection($payment_ways, new PaymentWayTransformer()); 
      }

      public function includeMeals(Resturant $resturant)
      {
         $meals = $resturant->meals;

         return $this->collection($meals, new MealTransformer()); 
      }

}
