@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section class="content">
        <div class="col-11 col-lg-8 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        {{-- <img class="profile-user-img img-circle" src="{{$item->photo?url($item->photo->path):asset('admin/img/user.png')}}" alt="{{$item->id}}"> --}}
                        <img class="profile-user-img img-circle" src="{{$item->photo? url($item->photo->path) :'https://img.icons8.com/ultraviolet/100/000000/test-account.png'}}" alt="{{$item->id}}">
                    </div>
                    <h3 class="profile-username text-center">@item($item->first_name) @item($item->last_name)</h3>
                    <hr>
                    {{ Form::model($item, array('route' => array('admin.password.update', $item->id), 'method' => 'POST','class'=>'container-fluid')) }}
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('password', 'رمز عبور جدید') }}
                                    {{ Form::password('password', array('class' => 'form-control')) }}
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('password_confirmation', 'تکرار رمز عبور جدید') }}
                                    {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
                                </div>
                            </div>
                            <div class="col-6">
                                {{ Form::button('ویرایش', array('type' => 'submit', 'class' => 'btn btn-success col-12')) }}
                            </div>
                            <div class="col-6">
                                <a href="{{ URL::previous() }}" class="btn btn-rounded btn-secondary col-12">بازگشت</a>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>

@endsection
@section('js')

@endsection