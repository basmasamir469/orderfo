<?php

namespace App\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\addresses\AddressRequest;
use App\Http\Requests\addresses\StoreAddressRequest;
use App\Http\Requests\addresses\UpdateAddressRequest;
use App\Models\Address;
use App\Models\Governorate;
use App\Transformers\AddressTransformer;
use App\Transformers\GovernorateTransformer;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $addresses=auth()->user()->addresses;
        $addresses = fractal()
        ->collection($addresses)
        ->transformWith(new AddressTransformer())
        ->toArray();
        return $this->dataResponse(['saved_places'=>$addresses],'saved_places',200);
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
    public function store(StoreAddressRequest $request)
    {
        //
        $data=$request->validated();

        if($data['type'] == 2  || $data['type'] == 1){
             
            $address=Address::where('type',$data['type'])->first();

            if($address){
                return $this->dataResponse(null,__('already existed'),422);
            }
    
        }
        $address=Address::create([
           'user_id'=>auth()->user()->id,
           'name'=>$data['name'],
           'street'=>$data['street'],
           'type'=>$data['type'],
           'latitude'=>$data['latitude'],
           'longitude'=>$data['longitude'],
           'building'=>$data['building'],
           'area_id'=>$data['area_id'],
           'floor' =>$data['floor'],
           'additional_directions'=>$data['additional_directions']
        ]);

        return $this->dataResponse(null,__('stored successfully'),200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $address=Address::findOrFail($id);
        return $this->dataResponse(fractal($address,new AddressTransformer('show'))->toArray(),__('address details'),200);
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
    public function update(Request $request, string $id)
    {
        $data=$request->validated();
        $address=Address::findOrFail($id);
        $address->update(
            [
               'name'       => $data['name']      ?? $address->name,
               'street'     => $data['street']    ?? $address->street,
               'latitude'   => $data['latitude']  ?? $address->latitude,
               'longitude'  => $data['longitude'] ?? $address->longitude,
               'building'   => $data['building']  ?? $address->building,
               'area_id'    => $data['area_id']   ?? $address->area_id,
               'floor'      => $data['floor']     ?? $address->floor,
               'additional_directions' => $data['additional_directions'] ?? $address->additional_directions
            ]
        );
    
        return $this->dataResponse(null,__('updated successfully'),200);     
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $address = Address::findOrFail($id);

        if($address->delete()){

            return $this->dataResponse(null,__('deleted successfully'),200);
        }
        return $this->dataResponse(null,__('failed to delete'),422);
    }

    public function areas(Request $request)
    {
        $skip = $request->skip ? $request->skip : 0;
        $take = $request->take ? $request->take : 10;

        $governorates = Governorate::query()
            ->search()
            ->skip($skip)
            ->take($take)
            ->get();

        $governorates = fractal()
        ->collection($governorates)
        ->transformWith(new GovernorateTransformer())
        ->includeAreas()
        ->toArray();
                
        return $this->dataResponse(['governorates'=>$governorates], 'all areas', 200);        
    }
}
