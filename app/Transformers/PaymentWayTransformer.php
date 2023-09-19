<?php

namespace App\Transformers;

use App\Models\PaymentWay;
use League\Fractal\TransformerAbstract;

class PaymentWayTransformer extends TransformerAbstract
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
    public function transform(PaymentWay $payment_way)
    {
        return [
            //
            'id'=>$payment_way->id,
            'name'=>$payment_way->name
        ];
    }
}
