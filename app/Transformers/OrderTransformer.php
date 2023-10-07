<?php

namespace App\Transformers;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
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
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Order $order)
    {

        $array = [

            'id' => $order->id,

            'resturant-logo' => $order->resturant->getFirstMediaUrl('resturants-logos'),

            'resturant-name '=> $order->resturant->name,

            'total_price'    => $order->total_cost,

            'order_date'     => $order->created_at,

            'meals_count'    => $order->meals->sum('pivot.quantity'),

            'status'         => $order->status
        ];

        if ($this->type == 'show') {

            $array['resturant-description'] = $order->resturant->description;

            $array['delivery_address'] = [

                'id' => $order->address->id,

                'name' => $order->address->street,

                'floor' => $order->address->floor

            ];

            $array['meals'] = $order->meals->map(function ($meal) {
                return [

                    'id'   => $meal->id,
                    'image' => $meal->getFirstMediaUrl('meals-images'),
                    'name' => $meal->name,
                    'description' => $meal->description,
                    'price'       => $meal->pivot->meal_price
                ];
            });

            $array['subtotal'] = $order->subtotal;

            $array['delivery_fee'] = $order->delivery_fee;
        }

        return $array;
    }
}
