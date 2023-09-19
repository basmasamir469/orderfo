<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resturant;
use App\Transformers\ResturantTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class HomeController extends Controller
{
    public function home(Request $request){

    }



    public function search(Request $request){
        $resturants = Resturant::query();

        $resturants = $resturants->search($resturants);

        $resturants = $resturants->with(['reviews'=>function($q){
           return $q->select('resturant_id')
            ->groupBy('resturant_id')
            ->orderByRaw("ROUND(( AVG(reviews.order_packaging) + AVG(reviews.delivery_time) + AVG(reviews.value_of_money)) / 3,1) desc");
          }])
            ->latest()
            ->skip(0)
            ->take(2)
            ->get();


        $fractal=new Manager();
        
        return $this->dataResponse(['resturants'=>$fractal->createData(new Collection($resturants,new ResturantTransformer))->toArray()], 'all resturants', 200);

    }

    public function filter(Request $request){

        $validator=Validator::make($request->all(),[

         'sort_by'=>'in:a_to_z,fastest_delivery',
         'filter_by'=>'in:deals,online_payment'

        ]);
        if($validator->fails()){

            return $this->dataResponse(null,$validator->errors()->first(),422);
        }
        $resturants = Resturant::query();

        $resturants=$resturants->filter($resturants);

        $resturants=$resturants->with(['reviews'=>function($q){

           return $q->select('resturant_id')
            ->groupBy('resturant_id')
            ->orderByRaw("ROUND(( AVG(reviews.order_packaging) + AVG(reviews.delivery_time) + AVG(reviews.value_of_money)) / 3,1) desc");
          }])
            ->latest()
            ->skip(0)
            ->take(2)
            ->get();


        $fractal=new Manager();
        
        return $this->dataResponse(['resturants'=>$fractal->createData(new Collection($resturants,new ResturantTransformer))->toArray()], 'all resturants', 200);


    }

    public function addToFav($resturant_id){

           $resturant = Resturant::findOrFail($resturant_id);

           $fav_rest = auth()->user()->favResturants()->toggle($resturant->id);

           if(count($fav_rest['attached'])>0){

               return $this->dataResponse(null,_('added successfully'),200);
           }
               return $this->dataResponse(null,_('removed successfully'),200);

           

            
    }
}
