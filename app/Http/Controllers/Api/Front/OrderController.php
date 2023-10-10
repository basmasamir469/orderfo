<?php

namespace App\Http\Controllers\Api\Front;

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

        $orders = auth()->user()->orders()->filter();

        $count = $orders->count();

        $orders = $orders->skip($skip)->take($take)->get();


        $orders = fractal()
        ->collection($orders)
        ->transformWith(new OrderTransformer())
        ->toArray();
        
        return $this->dataResponse($orders, 'all orders', 200);
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
        $total_cost = (($cart->total_price * $resturant->vat)/100) + $cart->total_price + $resturant->delivery_fee ;
        if($request->promo_code)
        {
            $promotion = Promotion::where('code',$request->promo_code)->where('expire_date','>',Carbon::now())->first();

            if(!$promotion)
            {

                return $this->dataResponse(null ,__("promo code is invalid"),200);
            }

            $total_cost -= ($promotion->discount/100) * $total_cost ;
        }


        $order = $request->user()->orders()->create([
            'resturant_id'  => $resturant->id,
            'subtotal'      => $cart->total_price,
            'total_cost'    => $total_cost,
            'payment_status'=> 0,
            'payment_way_id'=> $request->payment_way_id,
            'address_id'    => $request->address_id,
            'delivery_fee'  => $resturant->delivery_fee,
            'delivery_time' => $resturant->delivery_time,
            'order_status'  => Order::PENDING,
            'promo_code'    => $request->promo_code ?? '',
            'vat'           => $resturant->vat
        ]);


        foreach($cart->meals as $meal)
        {
            $size = $meal->meal_attributes->where(['type'=>0,'id'=>$meal->pivot->size])->first();

            $meal_price = $size?->offer_price ? $size?->offer_price : $size?->price;
            $extras_attr = $meal->meal_attributes()->where('id',$meal->pivot->extras)->first();
            $extra_price = (is_null($extras_attr?->offer_price)) ? $extras_attr?->price : $extras_attr?->offer_price;

            $order->meals()->attach($meal->id,['quantity' => $meal->pivot->quantity,

            'meal_price' => $meal_price?? 0.00 ,

            'special_instructions'=>$meal->pivot->special_instructions,

            'size'=>json_encode(['id'=>$meal->pivot->size,'price'=>$meal_price]),

            'extras'=>json_encode(['id'=>$meal->pivot->extras,'price'=>$extra_price]),

            'option'=>json_encode(['id'=>$meal->pivot->option]),
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
