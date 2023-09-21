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
    private $type;

    public function __construct($type=false){

      $this->type = $type;

    }
    protected array $defaultIncludes = [
        //
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
        $array=[
            //
            'id'=>$slider->id,
            'text'=>$slider->text,
            'image'=>$slider->image,
            'resturant_id'=>$slider->resturant?->id,
            'resturant_name'=>$slider->resturant?->name

        ];
        if($this->type=='all_sliders')
           {
            $array['resturant_images']=$slider->resturant?->images->map(function($image){
                return[
                  'url'=>$image->getUrl()
                ];
            });
           }
        return $array;
    }

}
