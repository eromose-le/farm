@extends('layouts.appadmin')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row grid-margin">
            <div class="col-lg-12">
                <div class="card">
                <div class="card-body">
                        <?php
                            $message = Session::get('message');
                        ?>
                        @if($message)
                            <p class="alert alert-success">
                                <?php
                                    echo $message;
                                    Session::put('message', null);
                                ?>
                            </p>
                        @endif
                    <h4 class="card-title">Edit category</h4>
                    {{-- <form class="cmxform" id="commentForm" method="get" action="#"> --}}
                    {!! Form::open(['action' => 'CategoryController@update_category', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
                    <fieldset>
                        <div class="form-group">
                        <label for="cname">Category name</label>
                        <input id="cname" class="form-control" name="category_name" minlength="2" type="text" value="{{$select_category->category_name}}" required>
                        <input id="cname" class="form-control" name="category_id" minlength="2" type="hidden" value="{{$select_category->id}}" required>
                        </div>

                        {{-- <input class="btn btn-primary" type="submit" value="Submit"> --}}
                        {{Form::submit('Update product', ['class' => 'btn btn-primary'])}}

                    </fieldset>
                    {{Form::close()}}
                    {{-- </form> --}}
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection