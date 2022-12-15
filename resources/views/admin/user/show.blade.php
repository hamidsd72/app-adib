@extends('layouts.admin')
@section('css')
@endsection
@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-9 m-auto">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-circle" src="{{$item->photo? url($item->photo->path) :'https://img.icons8.com/ultraviolet/100/000000/test-account.png'}}" alt="User profile picture">
                        </div>
                        <h3 class="profile-username text-center">@item($item->name)</h3>
                        <p class="text-muted text-center">@item($item->company__name)</p>
                        <div class="container-fluid">
                            <hr>
                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>نام نماینده شرکت</strong>
                                    <p class="text-muted"> @item($item->company__representative_name?$item->company__representative_name:'ثبت نشده')</p>
                                </div>
                                <div class="col-sm-6">
                                    <strong>شماره تماس نماینده شرکت</strong>
                                    <p class="text-muted"> @item($item->company__representative_phone?$item->company__representative_phone:'ثبت نشده')</p>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>شماره تماس شرکت</strong>
                                    <p class="text-muted"> @item($item->company__phone?$item->company__phone:'ثبت نشده')</p>
                                </div>
                                <div class="col-sm-6">
                                    <strong>شماره تماس مدیر شرکت</strong>
                                    <p class="text-muted"> @item($item->company__manager_phone?$item->company__manager_phone:'ثبت نشده')</p>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>ایمیل </strong>
                                    <p class="text-muted"> @item($item->email?$item->email:'ثبت نشده')</p>
                                </div>
                                <div class="col-sm-6">
                                    <strong>وبسایت شرکت </strong>
                                    <p class="text-muted"> @item($item->company__site?$item->company__site:'ثبت نشده')</p>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>شماره تلگرام شرکت </strong>
                                    <p class="text-muted"> @item($item->company__telegram?$item->company__telegram:'ثبت نشده')</p>
                                </div>
                                <div class="col-sm-6">
                                    <strong>شماره فکس شرکت </strong>
                                    <p class="text-muted"> @item($item->company__fax?$item->company__fax:'ثبت نشده')</p>
                                </div>
                            </div>
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <strong>ادرس شرکت</strong>
                                    <p class="text-muted">{{$item->company__address?$item->company__address:' ثبت نشده'}}</p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div><!-- /.card -->
            </div>
        </div>
    </div>
</section>

@endsection
@section('js')

@endsection