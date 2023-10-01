<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\areas\AreaRequest;
use App\Models\Area;
use App\Transformers\AreaTransformer;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,$governorate_id)
    {
        //
        $skip = $request->skip ? $request->skip : 0;
        $take = $request->take ? $request->take : 10;

        $areas = Area::query()
            ->search()
            ->filter()
            ->skip($skip)
            ->take($take)
            ->get();

        $areas = fractal()
        ->collection($areas)
        ->transformWith(new AreaTransformer('dashboard'))
        ->toArray();
        
        return $this->dataResponse(['areas'=>$areas], 'all areas', 200);
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
    public function store(AreaRequest $request)
    {
        //
        $data = $request->validated();
        $area = Area::create([

        'en'=>['name'=>$data['name_en']],

        'ar'=>['name'=>$data['name_ar']],

        'governorate_id'=>$data['governorate_id']
        ]);

        if($area){

            return $this->dataResponse(['area'=>fractal($area,new AreaTransformer())->toArray()],'stored successfully',200);
        }
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
    public function update(AreaRequest $request, string $id)
    {
        //
        $data = $request->validated();

        $area = Area::findOrFail($id);

        if($area->update([

        'en'=>['name'=>$data['name_en']],

        'ar'=>['name'=>$data['name_ar']],

        'governorate_id'=>$data['governorate_id']
        ])){

            $this->dataResponse(['area'=>fractal($area,new AreaTransformer)->toArray()],'updated successfully',200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $area = Area::findOrFail($id);

        if($area->delete()){

            return $this->dataResponse(null,__('deleted successfully'),200);
        }
        return $this->dataResponse(null,__('failed to delete'),500);
    }
}
