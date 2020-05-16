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
                    <h4 class="card-title">Add slider</h4>
                    {{-- <form class="cmxform" id="commentForm" method="get" action="#"> --}}
                    {!! Form::open(['action' => 'SliderController@save_slider', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
                    <fieldset>
                        <div class="form-group">
                            <label for="cname">Description one</label>
                            <input id="cname" class="form-control" name="description1" minlength="2" type="text" required>
                        </div>
                        <div class="form-group">
                            <label for="cname">Description two</label>
                            <input id="cname" class="form-control" name="description2" minlength="2" type="text" required>
                        </div>

                        <div class="form-group">
                            <label for="cname">Slider image</label>
                            {{-- <input id="cname" class="form-control" name="product_image" type="file" required> --}}
                            {{Form::file('product_image', ['class' => 'form-control'])}}
                        </div>

                        <div class="form-group">
                            <label for="cname">Status</label>
                            <input id="cname" name="product_status" type="checkbox" value="1">
                        </div>

                        {{-- <input class="btn btn-primary" type="submit" value="Submit"> --}}
                        {{Form::submit('Add slider', ['class' => 'btn btn-primary'])}}
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