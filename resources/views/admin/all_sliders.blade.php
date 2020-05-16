@extends('layouts.appadmin')

@section('content')

<div class="main-panel">
        <div class="content-wrapper">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Sliders</h4>

              <?php
                $increment = 1;
                $all_sliders = DB::table('tbl_sliders')
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
                            <th>Description one</th>
                            <th>Description two</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                      </thead>
                    
                      <tbody>
                        {{-- loop the body of the table dynamically --}}
                        @foreach ($all_sliders as $slider)
                        
                          <tr>
                            <td>{{$increment}}</td>
                            <td><img src="/storage/cover_images/{{$slider->slider_image}}" alt=""></td>
                            <td>{{$slider->description1}}</td>
                            <td>{{$slider->description2}}</td>
                            @if($slider->status == 1)
                              <td>
                                <label class="badge badge-success">Activated</label>
                              </td>
                            @else
                              <td>
                                <label class="badge badge-danger">Unactivated</label>
                              </td>
                            @endif

                            <td>
                              <button class="btn btn-outline-primary"><a style="text-decoration:none;" href="{{URL::to('/edit_slider/'.$slider->id)}}">Update</a></button>
                              <button class="btn btn-outline-danger"><a style="text-decoration:none;" href="{{URL::to('/delete_slider/'.$slider->id)}}">Delete</a></button>
                              @if($slider->status == 1)
                                <button class="btn btn-outline-warning"><a style="text-decoration:none;" href="{{URL::to('/unactivate_slider/'.$slider->id)}}">Unactivate</a></button>
                              @else
                                  <button class="btn btn-outline-success"><a style="text-decoration:none;" href="{{URL::to('/activate_slider/'.$slider->id)}}">Activate</a></button>
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