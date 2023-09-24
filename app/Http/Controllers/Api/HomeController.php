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

class HomeController extends Controller
{
    public function home()
    {

        $sliders = Slider::latest()->get();

        $categories = Category::get();

        $categories = fractal()
            ->collection($categories)
            ->transformWith(new CategoryTransformer())
            ->toArray();

        $sliders = fractal()
            ->collection($sliders)
            ->transformWith(new SliderTransformer('show'))
            ->toArray();

        return $this->dataResponse(['sliders' => $sliders, 'categories' => $categories], 'all sliders', 200);
    }

    public function resturants(Request $request)
    {

        $resturants = Resturant::query();
        $resturants = $resturants->search($resturants)->filter($resturants);
        $resturants = $resturants->leftJoin('reviews', 'resturants.id', '=', 'reviews.resturant_id')
            ->groupBy('resturants.id')
            ->select('resturants.*', DB::raw('ROUND((AVG(reviews.order_packaging) + AVG(reviews.delivery_time) + AVG(reviews.value_of_money)) / 3, 1) as average_rating'))
            ->orderBy('average_rating', 'DESC')
            ->latest()
            // ->skip(0)
            // ->take(2)
            ->get();

        $resturants = fractal()
            ->collection($resturants)
            ->transformWith(new ResturantTransformer())
            ->toArray();


        return $this->dataResponse(['resturants' => $resturants], 'all resturants', 200);
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
