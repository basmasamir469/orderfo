<?php

namespace App\Transformers;

use App\Models\Meal;
use App\Models\MealAttribute;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
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
        'meal_attributes'
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Meal $meal)
    {
        $size_attributes=$meal->meal_attributes()->where('type',0)->get();
        $array= [
            //
            'id'=>$meal->id,
            'name'=>$meal->name,
            'description'=>$meal->description,
            'image'=>$meal->getFirstMediaUrl('meals-images'),
            'price'=>$size_attributes->map(function($attr){
                return [
                    ($attr->offer_price != null || $attr->offer_price != 0.00) ? $attr->offer_price : $attr->price
                ];
            }),
            'type'=>$meal->type,

        ];

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

    public function includeMealAttributes(Meal $meal)
    {
       $meal_attributes = $meal->meal_attributes;
       return $this->collection($meal_attributes, new MealAttributeTransformer($this->type ?? '')); 
    }

}
