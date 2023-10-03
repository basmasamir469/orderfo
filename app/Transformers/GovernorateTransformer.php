<?php

namespace App\Transformers;

use App\Models\Area;
use App\Models\Governorate;
use League\Fractal\TransformerAbstract;

class GovernorateTransformer extends TransformerAbstract
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
        'areas'
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Governorate $governorate)
    {
            $array=[
                'id'=>$governorate->id,
                'name'=>$governorate->name,
            ];
    
            if($this->type =="dashboard"){
                unset($array['name']);
                $array['name_en']=$governorate->translate('en')->name;
                $array['name_ar']=$governorate->translate('ar')->name;
            }
            return $array;
    
    }

    public function includeAreas(Governorate $governorate)
    {
       $areas = $governorate->areas;

       return $this->collection($areas, new AreaTransformer($this->type ?? '')); 
    }
}
