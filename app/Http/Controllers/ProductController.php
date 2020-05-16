<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Cart;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //
    public function save_product(Request $request){
       
        if($request->product_category == 'Select category'){
            // warning message display
            Session::put('message1', 'Please select the category');

            return redirect::to('/add-product');
        } else {

            // if there is an image run this else
            $this->validate($request, [
                'products_image' => 'image|nullable|max:1999'
            ]);

            if ($request->hasFile('product_image')) {

                // 1 : Get file name with extension
                $filenameWithExt = $request->file('product_image')->getClientOriginalName();
                
                // 2 : Get just file name
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                
                // 3 : Get just the extension
                $extension = $request->file('product_image')->getClientOriginalExtension();

                // 4 : file name to save to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;

                // 5 : PATH to public
                $path = $request->file('product_image')->storeAs('public/cover_images', $fileNameToStore);
            } else {
                $fileNameToStore = 'noimage.jpg';
            }

            if(!$request->product_status){
                $status = 0;
            } else {
                $status = $request->product_status;
            }

            $data = array();
            // collect data from forms 
            $data['product_name'] = $request->product_name;
            $data['product_price'] = $request->product_price;
            $data['product_category'] = $request->product_category;
            $data['product_image'] = $fileNameToStore;
            $data['status'] = $status;

            // insert Data to DB
            DB::table('tbl_products')
                ->insert($data);

            // success message display
            Session::put('message', 'The product is added successfully');

            return redirect::to('/add-product');
        }
    }

    // make the filter functional
    public function select_product_by_category($category_name){
        // print("The selected cart is ".$category_name);

        $all_products = DB::table('tbl_products')
                    ->where('product_category', $category_name)
					->where('status', 1)
                    ->get();
                    
        $manage_products = view('client.shop')
                    ->with('all_products', $all_products);

        return view('layouts.app')
                ->with('client.shop', $manage_products);

    }

    public function unactivate_product($id){
        // print('The selected id product is '.$id);

        // sets status to 0, deactivate and redirect
        $data = array();
        $data['status'] = 0;

        DB::table('tbl_products')
            // select a particular item from row and update
            ->where('id', $id)
            ->update($data);

            Session::put('message', 'Product unactivated successfully');

        return redirect::to('/products');
    }

    public function activate_product($id){
        // print('The selected id product is '.$id);

        // sets status to 0, deactivate and redirect
        $data = array();
        $data['status'] = 1;

        DB::table('tbl_products')
            // select a particular item from row and update
            ->where('id', $id)
            ->update($data);

            Session::put('message', 'Product activated successfully');

        return redirect::to('/products');
    }

    public function delete_product($id){

        // delete from cover_image folder too
        $select_image = DB::table('tbl_products')
                        ->where('id', $id)
                        ->first();

        if($select_image->product_image != 'noimage'){
           Storage::delete('public/cover_images/'.$select_image->product_image);
        }

        DB::table('tbl_products')
            ->where('id', $id)
            ->delete();

         // success message display
         Session::put('message', 'The Product is deleted successfully');

         return redirect::to('/products');

    }

    public function edit_product($id){

        $select_product = DB::table('tbl_products')
                            ->where('id', $id)
                            ->first();

        // phase DB to newly router
        $manage_product = view('admin.edit_product')
                            ->with('select_product', $select_product);
                        
        return view('layouts.appadmin')
                ->with('admin.edit_product', $manage_product);
    }

    public function update_product(Request $request){

         // if there is an image run this else
         $this->validate($request, [
            'products_image' => 'image|nullable|max:1999'
        ]);

        if ($request->hasFile('product_image')) {

            // 1 : Get file name with extension
            $filenameWithExt = $request->file('product_image')->getClientOriginalName();
            
            // 2 : Get just file name
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            
            // 3 : Get just the extension
            $extension = $request->file('product_image')->getClientOriginalExtension();

            // 4 : file name to save to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            // 5 : PATH to public
            $path = $request->file('product_image')->storeAs('public/cover_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }


        $data = array();
        // collect data from forms 
        $data['product_name'] = $request->product_name;
        $data['product_price'] = $request->product_price;
        $data['product_category'] = $request->product_category;
        
        $data['status'] = $request->product_status;

        // if we select image when updating, modifying
        if($request->hasFile('product_image')){
            $select_image_name = DB::table('tbl_products')
                                    ->where('id', $request->product_id)
                                    ->first();

            // Replace and delete old image 
            $data['product_image'] = $fileNameToStore;

            if($select_image_name->product_image != 'noimage'){
                Storage::delete('public/cover_images/'.$select_image_name->product_image);
            }
        }

        // UPDATE Data to DB
        DB::table('tbl_products')
            ->where('id', $request->product_id)
            ->update($data);

        // success message display
        Session::put('message', 'The product is updated successfully');

        return redirect::to('/products');
    
    }

    public function addToCart($id){
        $product = DB::table('tbl_products')
                    ->where('id', $id)
                    ->first();

        // if we have a cart in our Session, get it or null
        $oldCart = Session::has('cart')?Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);
        Session::put('cart', $cart);

        // dd(Session::get('cart'));
        return redirect::to('/');
    }

    public function cart(){
        // return view('client.cart');
        // if session doesn't have cart
        if(!Session::has('cart')){
            return view('client.cart');
        }

        // if we have a cart in our Session, get it or null
        $oldCart = Session::has('cart')?Session::get('cart'):null;
        $cart = new Cart($oldCart);
        // return with different items
        return view('client.cart', ['products' => $cart->items]);
    }

    public function updateQty(Request $request){
        // print($request->id.'has qty'.$request->quantity);
        // if we have a cart in our Session, get it or null
        $oldCart = Session::has('cart')?Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->updateQty($request->id, $request->quantity);
        Session::put('cart', $cart);

        // dd(Session::get('cart'));
        return redirect::to('/cart');
    }

    public function removeItem($product_id){

        // if we have a cart in our Session, get it or null
        $oldCart = Session::has('cart')?Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->removeItem($product_id);

        // if we have atleast one item in cart
        if(count($cart->items) > 0){
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }

        // dd(Session::get('cart'));
        return redirect::to('/cart');
    }
}
