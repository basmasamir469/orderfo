<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Resturant;
use App\Models\Review;
use App\Models\Slider;
use App\Transformers\CategoryTransformer;
use App\Transformers\ResturantTransformer;
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
            ->search($resturants)
            ->filter($resturants)
            ->leftJoin('reviews', 'resturants.id', '=', 'reviews.resturant_id')
            ->groupBy('resturants.id')
            ->select('resturants.*', DB::raw('ROUND((AVG(reviews.order_packaging) + AVG(reviews.delivery_time) + AVG(reviews.value_of_money)) / 3, 1) as average_rating'))
            ->orderBy('average_rating', 'DESC');

        $count = $restaurants->count();
        $restaurants = $restaurants->skip($skip)->take($take)->get();

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
        $slider=Restaurant::findOrFail($id);
        return $this->dataResponse(['resturant'=>fractal($slider->resturant,new ResturantTransformer('show'))->toArray()], 'resturant details', 200);
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
}
