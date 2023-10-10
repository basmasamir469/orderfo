<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Meal;
use App\Transformers\CartTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        DB::beginTransaction();
        $meal = Meal::findOrFail($request->meal_id);
        // check if auth user has already cart or create new one 
        $user_cart = (auth()->user()->cart) ? auth()->user()->cart : auth()->user()->cart()->create(['total_price'=>0.00]) ;
        
        // check if meals in cart belong to the same resturant
        if($user_cart->meals()->first()?->resturant_id != $meal->resturant_id)
        {
            return $this->dataResponse(null,__('cannot added to cart ,meals must belong to one resturant '),422);
        }

        // add meal to cart
        $user_cart->meals()->attach($meal->id,[
            'quantity' => $request->quantity?? 1 ,
            'special_instructions'=>$request->special_instructions,
            'size'=>$request->size_id,
            'extras'=>$request->extra_id,
            'option'=>$request->option_id,
        ]);

        DB::commit();

        return $this->dataResponse(null,__('added to cart'),200);
    }


    public function myCart()
    {
        $cart = auth()->user()->cart;
        $cart_price  = 0;
        foreach($cart->meals->get() as $cart_meal)
        {
            $meal = Meal::findOrFail($cart_meal->meal_id);
            $size_attr = $meal->meal_attributes()->where('id',$cart_meal->pivot->size)->first();
            $size_price = (is_null($size_attr?->offer_price)) ? $size_attr?->price : $size_attr?->offer_price;
            $extras_attr = $meal->meal_attributes()->where('id',$cart_meal->pivot->extras)->first();
            $extra_price = (is_null($extras_attr?->offer_price)) ? $extras_attr?->price : $extras_attr?->offer_price;

            $cart_price += ($cart_meal->pivot->quantity * ($size_price + $extra_price));
        }

        $cart->update([
            'total_price'=> $cart_price 
        ]);

        return $this->dataResponse( ['my-cart'=> fractal( $cart , new CartTransformer )->toArray()] ,'mycart details',200);
    }

    public function clearCart()
    {
       if(auth()->user()->cart->meals()->detach() && auth()->user()->cart()->update(['total_price' =>0.00]))
       {

        return $this->dataResponse( null ,__('cleared successfully'),200);

       }
    }
}
