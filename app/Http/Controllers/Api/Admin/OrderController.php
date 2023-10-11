<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Token;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            DB::beginTransaction();

            $order->update([

                'order_status' => Order::APPROVED
      
              ]);
            
            $notification=$order->user->notifications()->create([
                'en'=>['title'=>'order is accepted','content'=>'your order is cooking now!'],
                'ar'=>['title'=>'تم قبول طلبك','content'=>'يتم تجهيز طلبك الان !'],
                'action'=>'accept',
                'action_id'=>$order->id
            ]);

            DB::commit();

            $tokens = Token::where('user_id',$order->user_id)->pluck('token')->toArray();
            $data=[
                'title'    => $notification->translate('en')->title,
                'body'     => $notification->translate('en')->content,
                'action_id'=> $notification->action_id
            ];

             $this->notifyByFirebase($tokens,$data);

            return $this->dataResponse(null,__('order is cooking now'),200);  
        }
            return $this->dataResponse(null,__('can not accept order'),422);  


    }

    public function rejectOrder($id)
    {
        $order = Order::findOrFail($id);

        if($order->order_status == Order::PENDING || $order->order_status == Order::APPROVED)
        {
            DB::beginTransaction();

            $order->update([

                'order_status' => Order::CANCELLED
      
              ]);
              $notification=$order->user->notifications()->create([
                'en'=>['title'=>'order is cancelled','content'=>'your order is cancelled !'],
                'ar'=>['title'=>'تم الغاء طلبك','content'=>' للاسف تم الغاء طلبك'],
                'action'=>'reject',
                'action_id'=>$order->id
            ]);

            DB::commit();
            $tokens=Token::where('user_id',$order->user_id)->pluck('token')->toArray();
            $data=[
                'title'    => $notification->translate('en')->title,
                'body'     => $notification->translate('en')->content,
                'action_id'=> $notification->action_id
            ];

             $this->notifyByFirebase($tokens,$data);

            return $this->dataResponse(null,__('order is rejected '),200);    
        }

    }

    public function outForDelivery($id)
    {
        $order = Order::findOrFail($id);

        if($order->order_status == Order::APPROVED)
        {
            Db::beginTransaction();
            $order->update([

                'order_status' => Order::OUTFORDELIVERY
      
              ]);
              $notification=$order->user->notifications()->create([
                'en'=>['title'=>'order is out for delivery','content'=>'your order is in the way !'],
                'ar'=>['title'=>'تم توصيل طلبك','content'=>'  طلبك في الطريق !'],
                'action'=>'out',
                'action_id'=>$order->id
            ]);

            DB::commit();
            $tokens=Token::where('user_id',$order->user_id)->pluck('token')->toArray();
           
            $data=[
                'title'    => $notification->translate('en')->title,
                'body'     => $notification->translate('en')->content,
                'action_id'=> $notification->action_id
            ];

             $this->notifyByFirebase($tokens,$data);
 
              
            return $this->dataResponse(null,__('order is in the way!'),200);

        }

    }

}
