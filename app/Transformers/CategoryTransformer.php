<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
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
    public function transform(Category $category)
    {
        $array=[
            //
            'id'=>$category->id,
            'name'=>$category->name,
            'logo'=>$category->logo
        ];

        if($this->type=="dashboard"){
            unset($array['name']);
            $array['name_en']=$category->translate('en')->name;
            $array['name_ar']=$category->translate('ar')->name;
        }
        return $array;

    }
}
