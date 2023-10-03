<?php

namespace App\Transformers;

use App\Models\MealAttribute;
use League\Fractal\TransformerAbstract;

class MealAttributeTransformer extends TransformerAbstract
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
    public function transform(MealAttribute $attr)
    {
        $array= [
            //
            'id'=>$attr->id,
            'type'=>$attr->type,
            'name'=>$attr->name,
            'price'=>$attr->price ?  $attr->price : $attr->offer_price,
            
        ];

        if($this->type=="dashboard"){
            unset($array['name']);
          $array['name_ar']=$attr->translate('ar')->name;
          $array['name_en']=$attr->translate('en')->name;
        }

        return $array;

    }
}
