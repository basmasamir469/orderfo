<?php

namespace App\Transformers;

use App\Models\Cart;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract
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
    public function transform(Cart $cart)
    {
        $resturant = $cart->meals[0]->resturant;
        
        return [
            'id' => $cart->id,

            'resturant-logo' => $resturant->getFirstMediaUrl('resturants-logos'),

            'resturant-name '=> $resturant->name,

            'resturant-description' => $resturant->description,

            'meals' => $cart->meals->map(function ($meal) {

                $size_attributes=$meal->meal_attributes()->where('type',0)->get();

                return [

                    'id'   => $meal->id,
                    'image' => $meal->getFirstMediaUrl('meals-images'),
                    'name' => $meal->name,
                    'description' => $meal->description,
                    'price'=>$size_attributes->map(function($attr){
                        return [
                            ($attr->offer_price != null || $attr->offer_price != 0.00) ? $attr->offer_price : $attr->price
                        ];
                    }), 
                    'quantity' =>$meal->pivot->quantity       
                ];
            }),

            'subtotal' => $cart->total_price,

            'delivery-fee' => $resturant->delivery_fee,

            'total' => (($cart->total_price  * $resturant->vat)/100) + $cart->total_price + $resturant->delivery_fee 




        ];
    }
}
