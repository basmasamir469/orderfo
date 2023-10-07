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
        $data=$request->validated();
        DB::beginTransaction();

        $meal=Meal::create([
            'en'=>['name'=>$data['name_en'],'description'=>$data['description_en']],
            'ar'=>['name'=>$data['name_ar'],'description'=>$data['description_ar']],
            'type'=>$data['meal_type'],
            'resturant_id'=>$data['resturant_id'],
        ]);
        foreach($data['sizes'] as $key => $value){
            $meal->meal_attributes()->create([
                'en'=>['name'=>$data['sizes'][$key]['name_en']],
                'ar'=>['name'=>$data['sizes'][$key]['name_ar']],
                'type'=>0,
                'price'=>$data['sizes'][$key]['price'],
                'offer_price'=>$data['sizes'][$key]['offer_price'],
               ]);
       
        }

        foreach($data['extras'] as $key => $value){
            $meal->meal_attributes()->create([
                'en'=>['name'=>$data['extras'][$key]['name_en']],
                'ar'=>['name'=>$data['extras'][$key]['name_ar']],
                'type'=>1,
                'price'=>$data['extras'][$key]['price'],
                'offer_price'=>$data['extras'][$key]['offer_price'],
               ]);
       
        }

        foreach($data['options'] as $key => $value){
            $meal->meal_attributes()->create([
                'en'=>['name'=>$data['options'][$key]['name_en']],
                'ar'=>['name'=>$data['options'][$key]['name_ar']],
                'type'=>2,
                'price'=>$data['options'][$key]['price']?$data['options'][$key]['price']:0.00,
                'offer_price'=>$data['options'][$key]['offer_price'],
               ]);
       
        }
        // $meal->addMedia($data['image'])->toMediaCollection('meals-images');
        foreach($data['images'] as $image){
            $meal->addMedia($image)->toMediaCollection('meals-images');
        }
        DB::commit();

        return $this->dataResponse(null,__('stored successfully'),200);    


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $meal=Meal::findOrFail($id);

        return $this->dataResponse(fractal($meal,new MealTransformer('dashboard'))->parseIncludes('meal_attributes')->toArray(),__('meal details'),200);
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
        $data=$request->validated();
        DB::beginTransaction();
        $meal=Meal::findOrFail($id);
        $meal=$meal->update([
            'en'=>['name'=>$data['name_en'],'description'=>$data['description_en']],
            'ar'=>['name'=>$data['name_ar'],'description'=>$data['description_ar']],
            'type'=>$data['meal_type'],
            'resturant_id'=>$data['resturant_id'],
        ]);
        $meal->meal_attributes()->update([
        'en'=>['name'=>$data['attribute_name_en']],
        'ar'=>['name'=>$data['attribute_name_ar']],   
         'type'=>$data['attr_type'],
         'price'=>$data['price'],
         'offer_price'=>$data['offer_price'],
        ]);

        $meal->clearMediaCollection('meals-images');
        
        foreach($data['images'] as $image){
            $meal->addMedia($image)->toMediaCollection('meals-images');
        }
        DB::commit();
    
        return $this->dataResponse(null,__('updated successfully'),200);    

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
