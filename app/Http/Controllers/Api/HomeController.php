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
            ->transformWith(new SliderTransformer())
            ->toArray();

        return $this->dataResponse(['sliders' => $sliders, 'categories' => $categories], 'all sliders', 200);
    }

    public function offers(){

        $sliders = Slider::latest()->get();

        $sliders = fractal()
            ->collection($sliders)
            ->transformWith(new SliderTransformer('all_sliders'))
            ->toArray();

        return $this->dataResponse(['sliders'=>$sliders], 'all sliders', 200);
    }

}
