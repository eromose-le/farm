@extends('layouts.appadmin')

@section('content')

<div class="main-panel">
        <div class="content-wrapper">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Products</h4>

              <?php
                $increment = 1;
                $all_products = DB::table('tbl_products')
                                    ->get();
              ?>

              <?php
              $message = Session::get('message');
              $message1 = Session::get('message1');
              ?>
              @if($message)
              <p class="alert alert-success">
                  <?php
                      echo $message;
                      Session::put('message', null);
                  ?>
              </p>
              @endif
              @if($message1)
              <p class="alert alert-danger">
                  <?php
                      echo $message1;
                      Session::put('message1', null);
                  ?>
              </p>
              @endif
              
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table id="order-listing" class="table">
                      <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Image</th>
                            <th>Product name</th>
                            <th>Product price</th>
                            <th>Product category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                      </thead>
                     
                      <tbody>
                        {{-- loop the body of the table dynamically --}}
                        @foreach ($all_products as $product)
                        
                          <tr>
                            <td>{{$increment}}</td>
                            <td><img src="/storage/cover_images/{{$product->product_image}}" alt=""></td>
                            <td>{{$product->product_name}}</td>
                            <td>{{$product->product_price}}</td>
                            <td>{{$product->product_category}}</td>
                            @if($product->status == 1)
                              <td>
                                <label class="badge badge-success">Activated</label>
                              </td>
                            @else
                              <td>
                                <label class="badge badge-danger">Unactivated</label>
                              </td>
                            @endif

                            <td>
                              <button class="btn btn-outline-primary"><a href="{{URL::to('/edit_product/'.$product->id)}}">Update</a></button>
                              <button class="btn btn-outline-danger"><a href="{{URL::to('/delete_product/'.$product->id)}}">Delete</a></button>
                              @if($product->status == 1)
                            <button class="btn btn-outline-warning"><a href="{{URL::to('/unactivate_product/'.$product->id)}}">Unactivate</a></button>
                              @else
                                  <button class="btn btn-outline-success"><a href="{{URL::to('/activate_product/'.$product->id)}}">Activate</a></button>
                              @endif
                            </td>      
                            
                          </tr>
                          <?php
                            $increment = $increment +1;
                          ?>
                        
                        {{-- loop through the database category --}}
              
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection