<?php

namespace App\Transformers;

use App\Models\Slider;
use League\Fractal\TransformerAbstract;

class SliderTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
        'resturant'
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Slider $slider)
    {
        return [
            //
            'id'=>$slider->id,
            'text'=>$slider->text,
            'image'=>$slider->image,
            // 'resturant'=>$slider->resturant?->name
        ];
    }

     public function includeResturant(Slider $slider)
     {
         $resturant = $slider->resturant;

        return $this->item($resturant, new ResturantTransformer()); 
     }

}
