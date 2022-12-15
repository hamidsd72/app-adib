@extends('layouts.admin')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-circle" src="{{$item->photo? url($item->photo->path):'https://img.icons8.com/ultraviolet/100/000000/test-account.png'}}" alt="User profile picture">
                            </div>
                            <hr>
                            {{ Form::model($item,array('route' => array('admin.user.update', $item->id), 'method' => 'POST', 'files' => true)) }}
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {{ Form::label('user_name', '* نام کاربری ') }}
                                        {{ Form::text('user_name',null, array('class' => 'form-control text-left' , $item->user_name?'readonly':'' )) }}
                                    </div>
                                </div>
                                <div class="col-lg-4"> 
                                    <div class="form-group">
                                        {{ Form::label('name', '* نام کامل') }}
                                        {{ Form::text('name',null, array('class' => 'form-control')) }}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {{ Form::label('email', '* ایمیل') }}
                                        {{ Form::email('email',null, array('class' => 'form-control text-left' )) }}
                                    </div>
                                </div>

                                <div class="col-12"><h3 class="text-info mt-3 mb-4">اطلاعات شرکت</h3></div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('company__name', 'نام شرکت') }}
                                        {{ Form::text('company__name',null, array('class' => 'form-control')) }}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('company__site', 'وبسایت شرکت') }}
                                        {{ Form::url('company__site',null, array('class' => 'form-control text-left' , 'style' => 'direction: ltr')) }}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('company__representative_name', 'نام نمایده شرکت') }}
                                        {{ Form::text('company__representative_name',null, array('class' => 'form-control')) }}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('company__representative_phone', 'شماره تماس نماینده شرکت') }}
                                        {{ Form::number('company__representative_phone',null, array('class' => 'form-control text-left')) }}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('company__phone', ' شماره تماس شرکت') }}
                                        {{ Form::number('company__phone',null, array('class' => 'form-control text-left')) }}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('company__manager_phone', ' شماره تماس مدیر شرکت') }}
                                        {{ Form::number('company__manager_phone',null, array('class' => 'form-control text-left')) }}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('company__fax', ' شماره فکس شرکت') }}
                                        {{ Form::text('company__fax',null, array('class' => 'form-control text-left')) }}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('company__telegram', ' شماره تلگرام شرکت') }}
                                        {{ Form::text('company__telegram',null, array('class' => 'form-control text-left')) }}
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('company__address', ' آدرس شرکت') }}
                                        {{ Form::text('company__address',null, array('class' => 'form-control')) }}
                                    </div>
                                </div>
                                
                                <div class="col-12"><h3 class="text-info my-4">اطلاعات بیشتر</h3></div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {{ Form::label('referred_to', ' ارجاع به ...') }}
                                        {{ Form::text('referred_to',null, array('class' => 'form-control')) }}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {{ Form::label('suspended', ' معلق کردن ') }}
                                        {{ Form::number('suspended',null, array('class' => 'form-control text-left')) }}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {{ Form::label('draft_permission', 'پیش نویس مجوز') }}
                                        {{ Form::number('draft_permission',null, array('class' => 'form-control text-left ')) }}
                                    </div>
                                </div>

                                <div class="col-12"><h3 class="text-info mt-4">اطلاعات نمایشی</h3></div>
                               
                                <div class="col-lg-3 col-md-6 mt-4">
                                    <label for="exampleInputFile">تصویر photo (100×100)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="photo" accept=".jpeg,.jpg,.png">
                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                                        </div>
                                    </div>
                                    @if ($item->photo)
                                        <img height="60px" src="{{$item->photo->path? url($item->photo->path):''}}" alt="User">
                                    @endif
                                </div>

                                <div class="col-lg-3 col-md-6 mt-4">
                                    <label for="exampleInputFile">تصویر پروفایل (100×100)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="profile" accept=".jpeg,.jpg,.png">
                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                                        </div>
                                    </div>
                                    @if ($item->profile)<img height="60px" src="{{url($item->profile)}}" alt="User">@endif
                                </div>

                                <div class="col-lg-3 col-md-6 mt-4">
                                    <label for="exampleInputFile">تصویر مهر (100×100)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="mohr" accept=".jpeg,.jpg,.png">
                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                                        </div>
                                    </div>
                                    @if ($item->mohr)<img height="60px" src="{{url($item->mohr)}}" alt="User">@endif
                                </div>

                                <div class="col-lg-3 col-md-6 mt-4">
                                    <label for="exampleInputFile">تصویر امضا (100×100)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="emza" accept=".jpeg,.jpg,.png">
                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                                        </div>
                                    </div>
                                    @if ($item->emza)<img height="60px" src="{{url($item->emza)}}" alt="User">@endif
                                </div>

                                <div class="col-12"><h3 class="text-info my-4">اطلاعات امنیتی</h3></div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('password', 'پسورد') }}
                                        {!! Form::password('password', ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('password_confirmation', 'تکرار پسورد') }}
                                        {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    {{ Form::button('ویرایش', array('type' => 'submit', 'class' => 'btn btn-info col-12')) }}
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
