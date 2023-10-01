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

           if($this->type=="dashboard"){
            unset($array['text']);
            unset($array['resturant_name']);
            $array['text_en']=$slider->translate('en')->text;
            $array['text_ar']=$slider->translate('ar')->text;
            $array['resturant_name_ar']=$slider->resturant?->translate('ar')->name;
            $array['resturant_name_en']=$slider->resturant?->translate('en')->name;
            
        }
        return $array;
    }

}
