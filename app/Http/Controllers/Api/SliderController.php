<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\sliders\SliderRequest;
use App\Models\Slider;
use App\Transformers\SliderTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
    	$this->middleware(['auth:sanctum','role:admin'])->except(['index']);
    }

    public function index()
    {
        //
        $sliders = Slider::get();

        $fractal=new Manager();

        return $this->dataResponse(['sliders'=>$fractal->createData(new Collection($sliders,new SliderTransformer))->toArray()], 'all sliders', 200);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SliderRequest $request)
    {
        //
        $data = $request->validated();

        $slider = Slider::create([

            'en'=>['text'=>$data['text_en']],
            'ar'=>['text'=>$data['text_ar']],
            'resturant_id'=>$data['resturant_id']
        ]);

        try{

        $slider->addMedia($data['image'])

        ->toMediaCollection('sliders-images');
        }
        catch(\Exception $e){

            return $this->dataResponse(null,__('failed to store'),500);
        }

        if($slider){

        return $this->dataResponse(fractal($slider,new SliderTransformer)->toArray(),__('stored successfully'),200);
        }
        return $this->dataResponse(null,__('failed to store'),500);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SliderRequest $request, string $id)
    {
        //
        $data = $request->validated();

        $slider = Slider::findOrFail($id);

        $updated = $slider->update([
           'en'=>['text'=>$data['text_en']],
           'ar'=>['text'=>$data['text_ar']],
           'resturant_id'=>$data['resturant_id']
        ]);

        try{

        $slider->clearMediaCollection('sliders-images');

        $slider->addMedia($data['image'])

        ->toMediaCollection('sliders-images');
        }
        catch(\Exception $e){
            
            return $this->dataResponse(null,__('failed to update'),500);
        }

           if($updated)
           {
            return $this->dataResponse(fractal($slider->fresh(),new SliderTransformer)->toArray(),__('updated successfully'),200);
           }
            return $this->dataResponse(null,__('failed to update'),500);



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $slider = Slider::findOrFail($id);

        if($slider->delete() && $slider->clearMediaCollection('sliders-images')){

            return $this->dataResponse(null,__('deleted successfully'),200);
        }
        return $this->dataResponse(null,__('failed to delete'),500);

    }
}
