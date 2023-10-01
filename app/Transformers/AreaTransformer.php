<?php

namespace App\Transformers;

use App\Models\Area;
use League\Fractal\TransformerAbstract;

class AreaTransformer extends TransformerAbstract
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
    public function transform(Area $area)
    {
        $array = [
            //
            'id'=>$area->id,
            'name'=>$area->name,
        ];

        if($this->type =="dashboard"){
            
            unset($array['name']);
            $array['name_en']=$area->translate('en')->name;
            $array['name_ar']=$area->translate('ar')->name;
        }
        return $array;
            
    }
}
