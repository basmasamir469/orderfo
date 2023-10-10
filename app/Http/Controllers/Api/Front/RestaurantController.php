<?php

namespace App\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\reviews\ReviewRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\Resturant;
use App\Models\Review;
use App\Models\Slider;
use App\Transformers\CategoryTransformer;
use App\Transformers\ResturantTransformer;
use App\Transformers\ReviewTransformer;
use App\Transformers\SliderTransformer;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class RestaurantController extends Controller
{

    public function index(Request $request)
    {
        $skip = $request->skip ? $request->skip : 0;
        $take = $request->take ? $request->take : 10;

        $resturants = Resturant::query()
            ->search()
            ->filter()
            ->orderByRate()
            ->skip($skip)
            ->take($take)
            ->get();

        $count = $resturants->count();
        $resturants = fractal()
            ->collection($resturants)
            ->transformWith(new ResturantTransformer())
            ->toArray();


        return $this->dataResponse([
            'resturants' => $resturants,
            'count' => $count,
        ], 'all resturants', 200);
    }

    public function show($id)
    {
        $resturant = Resturant::findOrFail($id);

        return $this->dataResponse(['resturant'=>fractal($resturant,new ResturantTransformer('show'))->parseIncludes('payment_ways')->toArray()], 'resturant details', 200);
    }

    public function addToFav($resturant_id)
    {
        $resturant = Resturant::findOrFail($resturant_id);

        $fav_rest = auth()->user()->favResturants()->toggle($resturant->id);

        if (count($fav_rest['attached']) > 0) {

            return $this->dataResponse(null, _('added successfully'), 200);
        }
        return $this->dataResponse(null, _('removed successfully'), 200);
    }

    public function favResturants()
    {
        $resturants=auth()->user()->favResturants;
        $resturants = fractal()
            ->collection($resturants)
            ->transformWith(new ResturantTransformer())
            ->toArray();

        return $this->dataResponse([
            'resturants' => $resturants,
        ], 'fav resturants', 200);    

    }


    public function reviews(Request $request,$resturant_id){

        $skip = $request->skip ? $request->skip : 0;
        $take = $request->take ? $request->take : 10;

        $resturant = Resturant::findOrFail($resturant_id);
        $reviews = $resturant->reviews->skip($skip)->take($take);
        $count = $resturant->reviews->count();
        $reviews = fractal()
            ->collection($reviews)
            ->transformWith(new ReviewTransformer())
            ->toArray();
      
            return $this->dataResponse(['reviews'=>$reviews,'count'=>$count], 'all reviews', 200);

    }
}
