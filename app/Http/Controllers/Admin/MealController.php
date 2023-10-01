<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\meals\StoreMealRequest;
use App\Models\Meal;
use App\Models\Resturant;
use App\Transformers\MealTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,$resturant_id)
    {
        //
        $skip = $request->skip? $request->skip : 0;
        $take = $request->skip? $request->take : 10;

        $meals= Resturant::findOrFail($resturant_id)
                ->meals
                ->filter()
                ->skip($skip)->take($take)->get();


        $meals = fractal()
        ->collection($meals)
        ->transformWith(new MealTransformer('dashboard'))
        ->toArray();

        return $this->dataResponse(['meals'=>$meals],'meals of resturants',200);

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
    public function store(StoreMealRequest $request)
    {
        //
        $sizes=[];
        $extras=[];
        $options=[];
        $data=$request->validated();
        foreach($data['sizes'] as $key => $size){
            $sizes[]=
            [
                'size_en'=>$data['sizes'][$key]['en'],
                'size_ar'=>$data['sizes'][$key]['ar'],
                'size_price'=>$data['sizes'][$key]['price']
            ];  
        }

        foreach($data['extras'] as $key => $extra){
            $extras[]=
            [
                'extra_en'=>$data['extras'][$key]['en'],
                'extra_ar'=>$data['extras'][$key]['ar'],
                'extra_price'=>$data['extras'][$key]['price']
            ];  
        }

        foreach($data['options'] as $key => $option){
            $options[]=
            [
                'option_en'=>$data['options'][$key]['en'],
                'option_ar'=>$data['options'][$key]['ar'],
            ];  
        }

        DB::beginTransaction();
        $meal=Meal::create([
            'en'=>['name'=>$data['name_en'],'description'=>$data['description_en']],
            'ar'=>['name'=>$data['name_ar'],'description'=>$data['description_ar']],
            'resturant_id'=>$data['resturant_id'],
        ]);
        $meal->meal_attributes()->create([
         'type'=>$data['type'],
         'price'=>$data['price'],
         'offer_price'=>$data['offer_price'],
         'size'=>$sizes,
         'option'=>$options,
         'extras'=>$extras
        ]);
        DB::commit();

        try{

            $meal->addMedia($data['image'])
    
            ->toMediaCollection('meals-images');
            }
            catch(\Exception $e){
    
                return $this->dataResponse(null,__('failed to store'),500);
            }
    
            if($meal){
    
            return $this->dataResponse(fractal($meal,new mealTransformer('show'))->toArray(),__('stored successfully'),200);
            }
            return $this->dataResponse(null,__('failed to store'),500);    


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $meal=Meal::findOrFail($id);

        return $this->dataResponse(fractal($meal,new MealTransformer('show'))->toArray(),__('meal details'),200);
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
    public function update(StoreMealRequest $request, string $id)
    {
        //
        $sizes=[];
        $extras=[];
        $options=[];
        $data=$request->validated();
        foreach($data['sizes'] as $key => $size){
            $sizes[]=
            [
                'size_en'=>$data['sizes'][$key]['en'],
                'size_ar'=>$data['sizes'][$key]['ar'],
                'size_price'=>$data['sizes'][$key]['price']
            ];  
        }

        foreach($data['extras'] as $key => $extra){
            $extras[]=
            [
                'extra_en'=>$data['extras'][$key]['en'],
                'extra_ar'=>$data['extras'][$key]['ar'],
                'extra_price'=>$data['extras'][$key]['price']
            ];  
        }

        foreach($data['options'] as $key => $option){
            $options[]=
            [
                'option_en'=>$data['options'][$key]['en'],
                'option_ar'=>$data['options'][$key]['ar'],
            ];  
        }

        DB::beginTransaction();
        $meal=Meal::findOrFail($id);
        $meal=$meal->update([
            'en'=>['name'=>$data['name_en'],'description'=>$data['description_en']],
            'ar'=>['name'=>$data['name_ar'],'description'=>$data['description_ar']],
            'resturant_id'=>$data['resturant_id'],
        ]);
        $meal->meal_attributes()->update([
         'type'=>$data['type'],
         'price'=>$data['price'],
         'offer_price'=>$data['offer_price'],
         'size'=>$sizes,
         'option'=>$options,
         'extras'=>$extras
        ]);
        DB::commit();

        try{
            $meal->clearMediaCollection('meals-images');
            $meal->addMedia($data['image'])
    
            ->toMediaCollection('meals-images');
            }
            catch(\Exception $e){
    
                return $this->dataResponse(null,__('failed to update'),500);
            }
    
            if($meal){
    
            return $this->dataResponse(fractal($meal,new mealTransformer('show'))->toArray(),__('updated successfully'),200);
            }
            return $this->dataResponse(null,__('failed to update'),500);    

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $meal = Meal::findOrFail($id);

        if($meal->delete() && $meal->meal_attributes()->delete() && $meal->clearMediaCollection('meals-images')){

            return $this->dataResponse(null,__('deleted successfully'),200);
        }
        return $this->dataResponse(null,__('failed to delete'),500);
    }
}
