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
                    <h4 class="card-title">Add category</h4>
                    {{-- <form class="cmxform" id="commentForm" method="get" action="#"> --}}
                    {!! Form::open(['action' => 'CategoryController@save_category', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
                    <fieldset>
                        <div class="form-group">
                        <label for="cname">Category name</label>
                        <input id="cname" class="form-control" name="category_name" minlength="2" type="text" required>
                        </div>

                        {{-- <input class="btn btn-primary" type="submit" value="Submit"> --}}
                        {{Form::submit('Add product', ['class' => 'btn btn-primary'])}}

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