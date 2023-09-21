<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Transformers\ResturantTransformer;
use App\Transformers\SliderTransformer;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    //
    public function allSliders(){

        $sliders = Slider::latest()->get();

        $sliders = fractal()
            ->collection($sliders)
            ->transformWith(new SliderTransformer('all_sliders'))
            ->toArray();

        return $this->dataResponse(['sliders'=>$sliders], 'all sliders', 200);
    }

    public function showResturant($id)
        {
            //
            $slider=Slider::findOrFail($id);
            $resturant=$slider->resturant;
            return $this->dataResponse(['resturant'=>fractal($slider->resturant,new ResturantTransformer('show'))->toArray()], 'resturant details', 200);
    
            
        }

}
