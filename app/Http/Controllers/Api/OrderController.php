<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\reviews\ReviewRequest;
use App\Models\Order;
use App\Models\Promotion;
use App\Transformers\OrderTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function myOrders(Request $request)
    {
        $skip = $request->skip? $request->skip : 0;
        $take = $request->skip? $request->take : 10;

        $orders=auth()->user()->orders()
               ->filter()
               ->skip($skip)
               ->take($take)
               ->get();

        $count=$orders->count();

        $orders = fractal()
        ->collection($orders)
        ->transformWith(new OrderTransformer())
        ->toArray();
        
        return $this->dataResponse(['orders'=>$orders], 'all orders', 200);


    }

    public function orderDetails($id)
    {

        $order=Order::findOrFail($id);

        return $this->dataResponse(fractal($order,new OrderTransformer('show'))->toArray(),__('order details'),200);
    } 

    public function makeOrder(Request $request)
    {
        DB::beginTransaction();

        $cart = $request->user()->cart;

        $resturant = $cart->meals[0]->resturant;

        // if user use promo code discount 
        
        if($request->promo_code)
        {
            $promotion = Promotion::where('code',$request->promo_code)->where('expire_date','>',Carbon::now())->first();

            if(!$promotion)
            {

                return $this->dataResponse(null ,__("promo code is invalid"),200);
            }

            $discount = $promotion->discount * ( ($cart->total_price * $resturant->vat) + ($cart->total_price + $resturant->delivery_fee ) );


        }
        else
        {
            $discount = 0;
        }

        $total_cost = ( ($cart->total_price * $resturant->vat) + ($cart->total_price + $resturant->delivery_fee ) ) - $discount;

        $order = Order::create([

                   'user_id'       => $request->user()->id,
                   'resturant_id'  => $resturant->id,
                   'subtotal'      => $cart->total_price,
                   'total_cost'    => $total_cost,
                   'payment_status'=> 0,
                   'payment_way_id'=> $request->payment_way_id,
                   'address_id'    => $request->address_id,
                   'delivery_fee'  => $resturant->delivery_fee,
                   'delivery_time' => $resturant->delivery_time,
                   'order_status'  => Order::PENDING,
                   'promo_code'    => $request->promo_code,
                   'vat'           => $resturant->vat
                   
                                ]);


        foreach($cart->meals as $meal)
        {
            $size = $meal->meal_attributes->where(['type'=>0,'id'=>$meal->pivot->size['id']])->first();

            $meal_price = $size?->offer_price ? $size?->offer_price : $size?->price;

            $order->meals()->attach($meal->id,['quantity' => $meal->pivot->quantity,

            'meal_price' => $meal_price?? 0.00 ,

            'special_instructions'=>$meal->pivot->special_instructions,

            'size'=>json_encode(['id'=>$meal->pivot->size['id'],'price'=>$meal->pivot->size['price']]),

            'extras'=>json_encode(['id'=>$meal->pivot->extras['id'],'price'=>$meal->pivot->extras['price']]),

            'option'=>json_encode(['id'=>$meal->pivot->option['id']]),
           ]);

        }

        $cart->meals()->detach();

        DB::commit();

        return $this->dataResponse(null,__('Your order is waiting to be approved'),200);
    }

    public function makeReview(ReviewRequest $request,$order_id)
    {
        $data = $request->validated();

        $order = Order::findOrFail($order_id);

      if($order->order_status == Order::DELIVERED)
      {

        $item = [
           'user_id'          => $request->user()->id,
           'comment'          => $request->comment,
           'order_packaging'  => $data['order_packaging'],
           'delivery_time'    => $data['delivery_time'],
           'value_of_money'   => $data['value_of_money'],
           'resturant_id'     => $order->resturant_id
        ];
        $order->reviews()->create($item);

        return $this->dataResponse(null, 'added successfully', 200);

       }

       return $this->dataResponse(null, 'cannot add review order has not been delivered yet', 422);
    }


}
