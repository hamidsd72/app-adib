@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                {{-- <img class="profile-user-img img-circle" src="{{$item->photo? url($item->photo->path) :asset('admin/img/user.png')}}" alt="{{$item->id}}"> --}}
                                <img class="profile-user-img img-circle" src="{{$item->photo? url($item->photo->path) :'https://img.icons8.com/ultraviolet/100/000000/test-account.png'}}" alt="{{$item->id}}">
                            </div>

                            <h3 class="profile-username text-center">@item($item->name)</h3>
                            <hr>
                            {{ Form::model($item,array('route' => array('admin.profile.update', $item->id), 'method' => 'POST', 'files' => true)) }}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('name', '* نام کامل') }}
                                            {{ Form::text('name',null, array('class' => 'form-control')) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('email', '* ایمیل') }}
                                            {{ Form::email('email',null, array('class' => 'form-control text-left' , $item->email&&$item->email_status=='active'?'readonly':'')) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('company__name', 'نام شرکت') }}
                                            {{ Form::text('company__name',null, array('class' => 'form-control')) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('company__manager_phone', 'شماره تماس مدیر شرکت') }}
                                            {{ Form::text('company__manager_phone',null, array('class' => 'form-control text-left')) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('company__phone', 'شماره تماس شرکت') }}
                                            {{ Form::text('company__phone',null, array('class' => 'form-control text-left')) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('company__fax', 'شماره فکس شرکت') }}
                                            {{ Form::text('company__fax',null, array('class' => 'form-control text-left')) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('company__telegram', 'شماره تلگرام شرکت') }}
                                            {{ Form::text('company__telegram',null, array('class' => 'form-control text-left')) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('company__site', 'وبسایت شرکت') }}
                                            {{ Form::url('company__site',null, array('class' => 'form-control text-left')) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('company__address', 'آدرس شرکت') }}
                                            {{ Form::text('company__address',null, array('class' => 'form-control text-left')) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('company__representative_name', 'نام نماینده شرکت') }}
                                            {{ Form::text('company__representative_name',null, array('class' => 'form-control')) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('company__representative_phone', 'شماره تماس نماینده شرکت') }}
                                            {{ Form::text('company__representative_phone',null, array('class' => 'form-control text-left')) }}
                                        </div>
                                    </div>
                                    {{-- referred_to
                                        suspended
                                        draft_permission
                                        mohr
                                        emza
                                        profile --}}
            
                                    <div class="col-lg-6 mb-3 mb-lg-0">
                                        <label for="exampleInputFile">تغییر تصویر پروفایل</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="exampleInputFile" name="photo" accept=".jpeg,.jpg,.png">
                                                <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="col">
                                        {{ Form::button('ویرایش', array('type' => 'submit', 'class' => 'btn btn-success col-12')) }}
                                    </div>
                                    <div class="col">
                                        <a href="{{ URL::previous() }}" class="btn btn-secondary col-12">بازگشت</a>
                                    </div>
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('js')
<script>
    $('.date_p').persianDatepicker({
        observer: true,
        format: 'YYYY/MM/DD',
        altField: '.observer-example-alt',
        initialValue:false,
    });
        $(document).ready(function () {
            $('select[name=state_id]').on('change', function () {
                $.get("{{url('/')}}/city-ajax/" + $(this).val(), function (data, status) {
                    $('select[name=city_id]').empty();
                    $.each(data, function (key, value) {
                        $('select[name=city_id]').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $('select[name=city_id]').trigger('change');
                });
            });
        });
</script>
@endsection