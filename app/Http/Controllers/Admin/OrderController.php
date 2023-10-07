<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::whereNot('order_status',Order::CANCELLED)
                      ->filter()
                      ->paginate(10);

        $orders = fractal()
        ->collection($orders)
        ->transformWith(new OrderTransformer())
        ->toArray();
        
        return $this->dataResponse(['orders'=>$orders], 'all orders', 200);

    }

    public function show($id)
    {

        $order=Order::findOrFail($id);

        return $this->dataResponse(fractal($order,new OrderTransformer('show'))->toArray(),__('order details'),200);
    } 

    public function acceptOrder($id)
    {
        $order = Order::findOrFail($id);

        if($order->order_status == Order::PENDING)
        {
            $order->update([

                'order_status' => Order::APPROVED
      
              ]);

            return $this->dataResponse(null,__('order is cooking now'),200);  
        }
            return $this->dataResponse(null,__('can not accept order'),422);  


    }

    public function rejectOrder($id)
    {
        $order = Order::findOrFail($id);

        if($order->order_status == Order::PENDING || $order->order_status == Order::APPROVED)
        {
            $order->update([

                'order_status' => Order::CANCELLED
      
              ]);

            return $this->dataResponse(null,__('order is rejected '),200);    
        }

    }

    public function outForDelivery($id)
    {
        $order = Order::findOrFail($id);

        if($order->order_status == Order::APPROVED)
        {

            $order->update([

                'order_status' => Order::OUTFORDELIVERY
      
              ]); 
              
            return $this->dataResponse(null,__('order is in the way!'),200);

        }

    }

}
