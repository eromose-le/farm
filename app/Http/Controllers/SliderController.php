<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    //

    public function save_slider(Request $request){

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
            $data['description1'] = $request->description1;
            $data['description2'] = $request->description2;
            $data['slider_image'] = $fileNameToStore;
            $data['status'] = $request->product_status;

            // insert Data to DB
            DB::table('tbl_sliders')
                ->insert($data);

            // success message display
            Session::put('message', 'The slider is added successfully');

            return redirect::to('/add-slider');
    }

    public function unactivate_slider($id){
        // print('The selected id product is '.$id);

        // sets dtatus to 0, deactivate and redirect
        $data = array();
        $data['status'] = 0;

        DB::table('tbl_sliders')
            // select a particular item from row and update
            ->where('id', $id)
            ->update($data);

            Session::put('message', 'Slider unactivated successfully');

        return redirect::to('/sliders');
    }

    public function activate_slider($id){
        // print('The selected id product is '.$id);

        // sets dtatus to 0, deactivate and redirect
        $data = array();
        $data['status'] = 1;

        DB::table('tbl_sliders')
            // select a particular item from row and update
            ->where('id', $id)
            ->update($data);

            Session::put('message', 'Slider activated successfully');

        return redirect::to('/sliders');
    }

    public function delete_slider($id){

        // delete from cover_image folder too
        $select_image = DB::table('tbl_sliders')
                        ->where('id', $id)
                        ->first();

        if($select_image->slider_image != 'noimage'){
           Storage::delete('public/cover_images/'.$select_image->slider_image);
        }

        DB::table('tbl_sliders')
            ->where('id', $id)
            ->delete();

         // success message display
         Session::put('message', 'The Slider is deleted successfully');

         return redirect::to('/sliders');

    }

    public function edit_slider($id){

        $select_slider = DB::table('tbl_sliders')
                            ->where('id', $id)
                            ->first();

        // phase DB to newly router
        $manage_slider = view('admin.edit_slider')
                            ->with('select_slider', $select_slider);
                        
        return view('layouts.appadmin')
                ->with('admin.edit_slider', $manage_slider);
    }

    public function update_slider(Request $request){

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
        $data['description1'] = $request->description1;
        $data['description2'] = $request->description2;
        
        $data['status'] = $request->product_status;

        // if we select image when updating, modifying
        if($request->hasFile('product_image')){
            $select_image_name = DB::table('tbl_sliders')
                                    ->where('id', $request->slider_id)
                                    ->first();

            // Replace and delete old image 
            $data['slider_image'] = $fileNameToStore;

            if($select_image_name->slider_image != 'noimage'){
                Storage::delete('public/cover_images/'.$select_image_name->slider_image);
            }
        }

        // UPDATE Data to DB
        DB::table('tbl_sliders')
            ->where('id', $request->slider_id)
            ->update($data);

        // success message display
        Session::put('message', 'The slider is updated successfully');

        return redirect::to('/sliders');
    
    }
}