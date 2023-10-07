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
    public function addToCart(Request $request,$meal_id)
    {
        DB::beginTransaction();

       $meal = Meal::findOrFail($meal_id);

    // check if auth user has already cart or create new one 

    if(auth()->user()->cart)
    {
      $user_cart =auth()->user()->cart;
    }
    else{

        $user_cart = Cart::create([

            'user_id' => auth()->user()->id,

            'total_price'=>0.00
         ]);  

    }

    // get meal-attribute price 

    $size_attr = $meal->meal_attributes()->where('id',$request->size_id)->first();

    $size_price = ($size_attr?->offer_price == null || $size_attr?->offer_price == 0.00) ? $size_attr?->price : $size_attr?->offer_price;

    $extras_attr = $meal->meal_attributes()->where('id',$request->extra_id)->first();

    $extra_price = ($extras_attr?->offer_price == null || $extras_attr?->offer_price == 0.00) ? $extras_attr?->price : $extras_attr?->offer_price;

   

    // check if meals in cart belong to the same resturant

    if(count(Cart::find($user_cart->id)->meals) > 0){

        foreach(Cart::find($user_cart->id)->meals as $cart_meal)
        {

             if($cart_meal->resturant_id != $meal->resturant_id)
             {
                  return $this->dataResponse(null,__('cannot added to cart ,meals must belong to one resturant '),422);
             }
        }
    }

    // add meal to cart

    Cart::find($user_cart->id)->meals()->attach($meal->id,['quantity' => $request->quantity?? 1 ,

                                         'meal_price' => 0.00 ,

                                         'special_instructions'=>$request->special_instructions,

                                         'size'=>json_encode(['id'=>$request->size_id,'price'=>$size_price]),

                                         'extras'=>json_encode(['id'=>$request->extra_id,'price'=>$extra_price]),

                                         'option'=>json_encode(['id'=>$request->option_id]),

                                        ]);

//   calculate total price of all meals in the cart 
    $meal_price = 0;

       foreach(Cart::find($user_cart->id)->meals as $cart_meal)
       {
        $meal_price += ($cart_meal->pivot->quantity * ($cart_meal->pivot->size['price'] + $cart_meal->pivot->extras['price']));
       }
    
       Cart::find($user_cart->id)->update([

        'total_price'=> $meal_price 
   
       ]);

       DB::commit();

    return $this->dataResponse(null,__('added to cart'),200);
    }


    public function myCart()
    {

        $cart=auth()->user()->cart;

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
