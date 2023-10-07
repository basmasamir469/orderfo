<?php

namespace App\Transformers;

use App\Models\Address;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract
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
    public function transform(Address $address)
    {
        $array= [
            //
            'id'=>$address->id,
            'type'=>$address->type,
            'name'=>$address->name

        ];
        if($this->type == 'show')
        {
            $array['building']=$address->building;
            $array['street']=$address->street;
            $array['area']=$address->area?->name;
            $array['additional_directions']=$address->additional_directions;
            $array['latitude']=$address->latitude;
            $array['longitude']=$address->longitude;
            $array['floor']=$address->floor;
        }
        return $array;
    }
}
