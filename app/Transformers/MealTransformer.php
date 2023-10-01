<?php

namespace App\Transformers;

use App\Models\Meal;
use Illuminate\Support\Facades\App;
use League\Fractal\TransformerAbstract;

class MealTransformer extends TransformerAbstract
{
    private $type;

    public function __construct($type = false)
    {
        $this->type = $type;
    }

    /**
     * List of resources to automatically include
     *
     * @var array
     */
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
    public function transform(Meal $meal)
    {
        $array= [
            //
            'id'=>$meal->id,
            'name'=>$meal->name,
            'description'=>$meal->description,
            'image'=>$meal->image,
            'sizes'=>collect($meal->meal_attributes?->size)
                    ->map(function($size){
                        return [
                            'size'=>App::getLocale()=='en'?$size['size_en']:$size['size_ar'],
                            'price'=>$size['size_price']
                        ];
                    }),
            'offer-price'=>$meal->meal_attributes?->offer_price,
            'price'=>$meal->meal_attributes?->price,
            'type'=>$meal->meal_attributes?->type,
            
        ];

        if($this->type=='show'){

            $array['extras']= collect($meal->meal_attributes?->extras)
            ->map(function($extra){
                return [
                    'name'=>App::getLocale()=='en'?$extra['extra_en']:$extra['extra_ar'],
                    'price'=>$extra['extra_price']
                ];
            });

            $array['option']= collect($meal->meal_attributes?->option)
            ->map(function($option){
                return [
                    'option'=>App::getLocale()=='en'?$option['option_en']:$option['option_ar'],
                ];
            });

        }
        if($this->type=="dashboard"){
            unset($array['name']);
            unset($array['description']);
          $array['name_ar']=$meal->translate('ar')->name;
          $array['name_en']=$meal->translate('en')->name;
          $array['description_en']=$meal->translate('en')->description;
          $array['description_ar']=$meal->translate('ar')->description;
        }

        return $array;
    }
}
