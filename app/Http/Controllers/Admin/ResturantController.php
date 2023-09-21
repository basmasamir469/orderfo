<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\resturants\ResturantRequest;
use App\Http\Requests\resturants\UpdateResturantRequest;
use App\Http\Requests\sliders\SliderRequest;
use App\Models\Resturant;
use App\Models\Slider;
use App\Transformers\ResturantTransformer;
use App\Transformers\SliderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class ResturantController extends Controller
{
    private $limit =0;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $resturants = Resturant::with(['reviews'=>function($q){

            return $q->select('resturant_id')
            ->groupBy('resturant_id')
            ->orderByRaw("ROUND(( AVG(reviews.order_packaging) + AVG(reviews.delivery_time) + AVG(reviews.value_of_money)) / 3,1) DESC");
             }])
             ->latest()
            ->skip($this->limit)
            ->take(2)
            ->get();

        $this->limit +=2;

        $resturants = fractal()
        ->collection($resturants)
        ->transformWith(new ResturantTransformer())
        ->toArray();
        
        return $this->dataResponse(['resturants'=>$resturants], 'all resturants', 200);

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
    public function store(ResturantRequest $request)
    {
        //
        $data = $request->validated();

        DB::beginTransaction();

        $resturant = Resturant::create([

            'en'=>['name'=>$data['name_en']],
            'ar'=>['name'=>$data['name_ar']],
            'to_time'=>$data['to_time'],
            'from_time'=>$data['from_time'],
            'latitude'=>$data['latitude'],
            'longitude'=>$data['longitude'],
            'minimum_cost'=>$data['minimum_cost'],
            'delivery_fee'=>$data['delivery_fee'],
            'delivery_time'=>$data['delivery_time'],
            'description'=>$data['description'],
            'vat'=>$data['vat'],
            'category_id'=>$data['category_id'],
            'address'=>$data['address'],
            'status'=>$request->status


        ]);

        try{

             
        $resturant->paymentWays()->attach($data['payment_ways']);


        $resturant->addMedia($data['logo'])

        ->toMediaCollection('resturants-logos');
        
        foreach($data['images'] as $image){
            $resturant->addMedia($image)->toMediaCollection('resturants-images');
        }
        
        DB::commit();

        }
        catch(\Exception $e){

            return $this->dataResponse(null,$e->getMessage(),500);
        }

        if($resturant){

        return $this->dataResponse(fractal($resturant,new ResturantTransformer)->toArray(),__('stored successfully'),200);
        }
        return $this->dataResponse(null,__('failed to store'),500);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $resturant=Resturant::findOrFail($id);

        return $this->dataResponse(fractal($resturant->fresh(),new ResturantTransformer('show'))->includePaymentWays()->toArray(),__('updated successfully'),200);

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
    public function update(UpdateResturantRequest $request, string $id)
    {
        //
        $data = $request->validated();

        $resturant = Resturant::findOrFail($id);

      DB::beginTransaction();

        $updated = $resturant->update([
            'en'=>['name'=>$data['name_en']],
            'ar'=>['name'=>$data['name_ar']],
            'to_time'=>$data['to_time'],
            'from_time'=>$data['from_time'],
            'latitude'=>$data['latitude'],
            'longitude'=>$data['longitude'],
            'minimum_cost'=>$data['minimum_cost'],
            'delivery_fee'=>$data['delivery_fee'],
            'delivery_time'=>$data['delivery_time'],
            'description'=>$data['description'],
            'vat'=>$data['vat'],
            'category_id'=>$data['category_id'],
            'address'=>$data['address'],
            'status'=>$request->status

        ]);

        try{
            $resturant->paymentWays()->sync($data['payment_ways']);
            if(!empty($data['logo'])){

        $resturant->clearMediaCollection('resturants-logos');

        $resturant->addMedia($data['logo'])
            

        ->toMediaCollection('resturants-logos');
            }
            if(!empty($data['images'])){

        $resturant->clearMediaCollection('resturants-images');

        foreach($data['images'] as $image){
            $resturant->addMedia($image)->toMediaCollection('resturants-images');
        }
             }

        DB::commit();

        }
        catch(\Exception $e){
            
            return $this->dataResponse(null,$e->getMessage(),500);
        }

           if($updated)
           {
            return $this->dataResponse(fractal($resturant->fresh(),new ResturantTransformer)->toArray(),__('updated successfully'),200);
           }
            return $this->dataResponse(null,__('failed to update'),500);



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $resturant = Resturant::findOrFail($id);

        if($resturant->delete() && $resturant->clearMediaCollection('resturants-images') && $resturant->clearMediaCollection('resturants-logos')){

            return $this->dataResponse(null,__('deleted successfully'),200);
        }
        return $this->dataResponse(null,__('failed to delete'),500);

    }
}
