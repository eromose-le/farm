<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    //

    public function save_category(Request $request){

        $data = array();
        $data['category_name'] = $request->category_name;

        // pick data from form and insert to table
        DB::table('tbl_category')
            ->insert($data);

        // success message display
        Session::put('message', 'The category is added successfully');

        return redirect::to('/add-category');

    }

    public function delete_category($id){

        DB::table('tbl_category')
            ->where('id', $id)
            ->delete();

         // success message display
         Session::put('message', 'The category is deleted successfully');

         return redirect::to('/categories');

    }

    public function edit_category($id){

        $select_category = DB::table('tbl_category')
                            ->where('id', $id)
                            ->first();

        $manage_category = view('admin.edit_category')
                            ->with('select_category', $select_category);
                        
        return view('layouts.appadmin')
                ->with('admin.edit_category', $manage_category);
    }

    public function update_category(Request $request){

        $data = array();
        $data['category_name'] = $request->category_name;

        DB::table('tbl_category')
            ->where('id', $request->category_id)
            ->update($data);

        // success message display
        Session::put('message', 'The category is updated successfully');

        return redirect::to('/categories');
    }
}