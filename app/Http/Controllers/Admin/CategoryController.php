<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\categories\StoreCategoryRequest;
use App\Models\Category;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::get();

        $categories = fractal()
            ->collection($categories)
            ->transformWith(new CategoryTransformer())
            ->toArray();

        return $this->dataResponse($categories, 'all categories', 200);
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
    public function store(StoreCategoryRequest $request)
    {
        //
        $data = $request->validated();

        $category = Category::create([

            'en'=>['name'=>$data['name_en']],
            'ar'=>['name'=>$data['name_ar']]
        ]);

        try{

        $category->addMedia($data['logo'])

        ->toMediaCollection('categories-logos');
        }
        catch(\Exception $e){

            return $this->dataResponse(null,__('failed to store'),500);
        }

        if($category){

        return $this->dataResponse(fractal($category,new CategoryTransformer)->toArray(),__('stored successfully'),200);
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
    public function update(StoreCategoryRequest $request, string $id)
    {
        //
        $data = $request->validated();

        $category = Category::findOrFail($id);

        $updated = $category->update([
           'en'=>['name'=>$data['name_en']],
           'ar'=>['name'=>$data['name_ar']],
        ]);

        try{

        $category->clearMediaCollection('categories-logos');

        $category->addMedia($data['logo'])

        ->toMediaCollection('categories-logos');
        }
        catch(\Exception $e){
            
            return $this->dataResponse(null,__('failed to update'),500);
        }

           if($updated)
           {
            return $this->dataResponse(fractal($category->fresh(),new CategoryTransformer)->toArray(),__('updated successfully'),200);
           }
            return $this->dataResponse(null,__('failed to update'),500);



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $category = Category::findOrFail($id);

        if($category->delete() && $category->clearMediaCollection('categories-logos')){

            return $this->dataResponse(null,__('deleted successfully'),200);
        }
        return $this->dataResponse(null,__('failed to delete'),500);

    }
}
