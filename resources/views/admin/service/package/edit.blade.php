@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            {{ Form::model($item,array('route' => array('admin.service.package.update', $item->id), 'method' => 'POST', 'files' => true)) }}
                            <div class="row">
                                {{-- <div class="col-sm-12">
                                    <div class="form-group">
                                        {{ Form::label('service', '* خدمت') }}
                                        <select class="form-control select2" name="service[]" multiple>
                                            @foreach($items as $item)
                                                <option value="{{$item->id}}" {{in_array($item->id,old('service',$service))?'selected':''}}>{{$item->title}}({{$item->category?$item->category->title:'_'}})</option>
                                            @endforeach
                                        </select>
                                       {{ Form::select('service[]' , Illuminate\Support\Arr::pluck($items,'title','id') ,$service , array('class' => 'form-control select2','multiple')) }}
                                    </div>
                                </div> --}}
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('user_id', 'کارمند') }}
                                        <h6 class="rounded" style="padding: 12px;border: 1px solid #ced4da;">{{$item->user()->first_name.' '.$item->user()->last_name}}</h6>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="form-group">
                                        <div class="col-12 font-weight-bold mb-3">
                                            فعالیت خارج از شهر
                                        </div>
                                        <label class="switch-wrap switch-success ml-2">
                                            <input name="location_work" type="checkbox" {{$item->location_work=='خارج از شهر'?'checked':''}}>
                                            <div class="switch"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="form-group">
                                        {{ Form::label('work_type', ' نوع سپردن فعالیت') }}
                                        <select class="form-control" name="work_type" id="work_type">
                                            <option value="پیمانکاری" @if($item->work_type=='پیمانکاری') selected @endif>پیمانکاری</option>
                                            <option value="ساعتی" @if($item->work_type=='ساعتی') selected @endif>ساعتی</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('title', ' عنوان فعالیت') }}
                                        <select class="form-control" name="title" id="title">
                                            @foreach ($jobs as $job)
                                                <option value="{{$job->id}}" @if ($job->id==$item->title) selected @endif>{{$job->title}}</option>
                                            @endforeach
                                        </select>
                                        {{-- {{ Form::text('title',null, array('class' => 'form-control', 'required' => 'required')) }} --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('started_at', '* تاریخ انجام کار '.my_jdate($item->date,'d F Y')) }}
                                        {{ Form::text('started_at',my_jdate($item->date,'d/m/Y'), array('class' => 'form-control date_p')) }}
                                        <img class="inline-left-logo" src="https://img.icons8.com/external-icematte-lafs/40/000000/external-Calendar-it-icematte-lafs.png">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {{ Form::label('custom', 'مشتری') }}
                                        <select class="form-control" name="custom" id="custom">
                                            @foreach ($customs as $key => $custom)
                                                <option value="{{$custom->id}}" @if ($key==0) selected @endif>{{$custom->first_name.' '.$custom->last_name.' - '.$custom->text}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg col-md-6 d-none">
                                    <div class="form-group">
                                        {{ Form::label('slug', '* نامک') }}
                                        {{ Form::text('slug',null, array('class' => 'form-control')) }}
                                    </div>
                                </div>
{{--                                <div class="col-sm-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        {{ Form::label('limited', '* محدودیت (هر بار برای چند روز)') }}--}}
{{--                                        {{ Form::number('limited',null, array('class' => 'form-control text-left')) }}--}}
{{--                                    </div>--}}
                                {{--</div>--}}
                                {{-- <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('sort_by', 'ترتیب نمایش') }}
                                        {{ Form::number('sort_by',null, array('class' => 'form-control text-left')) }}
                                    </div>
                                </div> --}}
                                {{-- <div class="col-sm-3">
                                    <div class="form-group">
                                        {{ Form::label('custom', 'پکیج ویژه') }}
                                        <input type="checkbox" name="custom" class="form-control" {{$item->custom==1?'checked':''}}>
                                    </div>
                                </div> --}}
{{--                                <div class="col-sm-3">--}}
{{--                                    <div class="form-group">--}}
{{--                                        {{ Form::label('custom_service_count', 'تعداد سرویس های دلخواه') }}--}}
{{--                                        {{ Form::number('custom_service_count',null, array('class' => 'form-control text-left')) }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {{ Form::label('price', '* هزینه') }}
                                        {{ Form::number('price',null, array('class' => 'form-control','onkeyup'=>'number_price(this.value)')) }}
                                        <span id="price_span" class="span_p"><span id="pp_price"></span> تومان </span>
                                    </div>
                                </div>
                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('home_view', 'نمایش در صفحه اصلی') }}
                                        <select class="form-control" name="home_view" id="home_view">
                                            <option value="show">نمایش</option>
                                            <option value="hide" selected>عدم نمایش</option>
                                        </select>
                                    </div>
                                </div> --}}
{{--                                <div class="col-sm-12">--}}
{{--                                    <div class="form-group">--}}
{{--                                        {{ Form::label('home_text', 'توضیحات صفحه اصلی') }}--}}
{{--                                        {{ Form::text('home_text',null, array('class' => 'form-control')) }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                {{-- <div class="col-md-4">
                                    <label for="exampleInputFile">تصویر کارت</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="pic_card" accept=".jpeg,.jpg,.png">
                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                                        </div>
                                    </div>
                                    @if($item->pic_card!=null)
                                        <img src="{{url($item->pic_card)}}" class="mt-2" height="100">
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputFile">تصویر دوم</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="photo" accept=".jpeg,.jpg,.png">
                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                                        </div>
                                    </div>
                                    @if($item->photo)
                                        <img src="{{url($item->photo->path)}}" class="mt-2" height="100">
                                    @endif
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        {{ Form::label('text', '* توضیحات') }}
                                        {{ Form::textarea('text',null, array('class' => 'form-control textarea','onkeyup'=>'number_price(this.value)')) }}
                                    </div>
                                </div> --}}
{{--                                <div class="col-sm-6 mb-2">--}}
{{--                                    <label for="exampleInputFile"> فایل pdf(حداکثر 30 مگابایت)</label>--}}
{{--                                    <div class="input-group">--}}
{{--                                        <div class="custom-file">--}}
{{--                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="file" accept=".pdf">--}}
{{--                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    @if($item->file)--}}
{{--                                        <a href="{{url($item->file->path)}}" class="mt-2" download>دانلود فایل</a>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-6 mb-2">--}}
{{--                                    <label for="exampleInputFile">ویدئو mp4(حداکثر 50 مگابایت)</label>--}}
{{--                                    <div class="input-group">--}}
{{--                                        <div class="custom-file">--}}
{{--                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="video" accept=".mp4">--}}
{{--                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    @if($item->video)--}}
{{--                                        <a href="{{url($item->video->path)}}" class="mt-2" target="_blank">نمایش ویدئو</a>--}}
{{--                                    @endif--}}
{{--                                </div>--}}

                                <div class="col-lg-4">
                                    {{-- <label for="exampleInputFile"> فایل pdf(حداکثر 30 مگابایت)</label> --}}
                                    <label for="exampleInputFile"> فایل (حداکثر 30 مگابایت)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="file">
                                            <label class="custom-file-label" dir="ltr" for="exampleInputFile">انتخاب فایل</label>
                                        </div>
                                    </div>
                                    <p class="m-0">برای اطمینان از سلامت فایل ارسالی ,توصیه میشود از فایل زیپ استفاده کنید</p>
                                    @if($item->file)
                                        <a href="{{url($item->file->path)}}" class="mt-2" download>دانلود فایل پیوست شده</a>
                                    @endif
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        {{ Form::label('text', '* توضیحات') }}
                                        {{ Form::textarea('text',null, array('class' => 'form-control textarea','onkeyup'=>'number_price(this.value)')) }}
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
                        <!-- /.card-body -->
                    </div><!-- /.card -->
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
    <script src="{{ asset('editor/laravel-ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('editor/laravel-ckeditor/adapters/jquery.js') }}"></script>
    <script>
        var textareaOptions = {
            filebrowserImageBrowseUrl: '{{ url('filemanager?type=Images') }}',
            filebrowserImageUploadUrl: '{{ url('filemanager/upload?type=Images&_token=') }}',
            filebrowserBrowseUrl: '{{ url('filemanager?type=Files') }}',
            filebrowserUploadUrl: '{{ url('filemanager/upload?type=Files&_token=') }}',
            language: 'fa'
        };
        $('.textarea').ckeditor(textareaOptions);
        slug('#title', '#slug');

        function number_price(a){
            $('#pp_price').text(a);
            $('#pp_price_1').text(a);
            $('#pp_price').text(function (e, n) {
                var lir1= n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return lir1;
            })
        }
        $(document).ready(function () {
            var a=$('#price').val();
            $('#pp_price').text(a);
            $('#pp_price_1').text(a);
            $('#pp_price').text(function (e, n) {
                var lir1= n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return lir1;
            })
        });
    </script>
@endsection

