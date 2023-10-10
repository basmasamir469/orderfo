<?php

namespace App\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Resturant;
use App\Transformers\MealTransformer;
use Illuminate\Http\Request;

class MealController extends Controller
{
    //
    public function index(Request $request)
    {
        $skip = $request->skip? $request->skip : 0;
        $take = $request->skip? $request->take : 10;

        $meals= Resturant::findOrFail($request->resturant_id)
                ->meals()
                ->filter()
                ->skip($skip)->take($take)
                ->get();

        $count=count($meals);

        $meals = fractal()
        ->collection($meals)
        ->transformWith(new MealTransformer())
        ->toArray();

        return $this->dataResponse(['meals'=>$meals,'count'=>$count],'meals of resturants',200);
    }

    public function show($id)
    {
       $meal=Meal::findOrFail($id);

       return $this->dataResponse(['meal'=>fractal($meal,new MealTransformer())->parseIncludes('meal_attributes')->toArray()],'meal details',200);
    }


}
