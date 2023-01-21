@extends('user.master')
<style>
    #mamad span:hover img { padding: 16px; transition: 0.4s; }
    .product-card-small .btn:hover { color: darkred !important; }
    .product-card-small button i.teal { color: teal !important; }
    .product-card-small .collapsed i.teal { color: unset !important; }
</style>
@section('content')
    {{-- سرچ باکس --}}
    <div class="container">
        <div class="form-group mb-0 mt-5 pt-2">
            {{-- <form action="{{route('user.user-search.store')}}" method="post"> --}}
            <form action="{{route('user.work-search')}}" method="get">
                @csrf
                <div class="row mb-0">
                    <div class="col">
                        <input type="hidden" name="type" value="package">
                        <input type="text" class="form-control search" name="search" placeholder="جستجو در فعالیت های من">
                    </div>
                    {{-- <div class="col-auto pl-0">
                        <button class="sqaure-btn btn btn-info text-white filter-btn" type="button">
                            <svg xmlns='http://www.w3.org/2000/svg' class="icon-size-24" viewBox='0 0 512 512'>
                                <title>ionicons-v5-i</title>
                                <line x1='368' y1='128' x2='448' y2='128' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px' />
                                <line x1='64' y1='128' x2='304' y2='128' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px' />
                                <line x1='368' y1='384' x2='448' y2='384' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px' />
                                <line x1='64' y1='384' x2='304' y2='384' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px' />
                                <line x1='208' y1='256' x2='448' y2='256' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px' />
                                <line x1='64' y1='256' x2='144' y2='256' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px' />
                                <circle cx='336' cy='128' r='32' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px' />
                                <circle cx='176' cy='256' r='32' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px' />
                                <circle cx='336' cy='384' r='32' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px' />
                            </svg>
                        </button>
                    </div> --}}
                </div>
            </form>
        </div>
    </div>

    <!-- demo slider top -->
    @if ($sliders)
        <div class="my-4 container">
            <div id="demo" class="carousel slide" data-ride="carousel">
                <ul class="carousel-indicators">
                    @for ($i = 0; $i < $sliders->count(); $i++)
                        <li data-target="#demo" data-slide-to="{{$i}}" class="{{$i==0?'active':''}}"></li> 
                    @endfor
                </ul>
                <div class="carousel-inner">
                    @foreach ($sliders as $key => $slider)
                        <div class="carousel-item {{$key==0?'active':''}}">
                            <a href="{{$slider->link}}">
                                @if ($slider->photo)
                                    <img src="{{$slider->photo->path?url($slider->photo->path):''}}" alt="{{$slider->title}}">
                                @endif
                                <div class="carousel-caption p-1 p-lg-2" style="background: #20364bad;right: 2%;width: 96%;bottom: 4% !important;border-radius: 12px">
                                    <a href="{{$slider->link}}" class="px-2 text-white" style="font-size: 16px;">{{$slider->title}}</a>
                                    <div class="float-left">
                                        <div class="tag-images-count text-white px-2">
                                            <span class="vm px-1">{{($key+1).' از '.$sliders->count()}}</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-size-16 vm" viewBox="0 0 512 512">
                                                <title>ionicons-v5-e</title>
                                                <path d="M432,112V96a48.14,48.14,0,0,0-48-48H64A48.14,48.14,0,0,0,16,96V352a48.14,48.14,0,0,0,48,48H80" style="fill:none;stroke:#000;stroke-linejoin:round;stroke-width:32px"></path>
                                                <rect x="96" y="128" width="400" height="336" rx="45.99" ry="45.99" style="fill:none;stroke:#000;stroke-linejoin:round;stroke-width:32px"></rect>
                                                <ellipse cx="372.92" cy="219.64" rx="30.77" ry="30.55" style="fill:none;stroke:#000;stroke-miterlimit:10;stroke-width:32px"></ellipse>
                                                <path d="M342.15,372.17,255,285.78a30.93,30.93,0,0,0-42.18-1.21L96,387.64" style="fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"></path>
                                                <path d="M265.23,464,383.82,346.27a31,31,0,0,1,41.46-1.87L496,402.91" style="fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"></path>
                                            </svg>
                                        </div>
                                    </div>

                                </div>   
                            </a>
                        </div>
                    @endforeach
                </div> 
            </div>
        </div>
    @endif

    {{-- درخواست ها --}}
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <div class="row mb-0">
                    <div class="col">
                        <h6 class="text-dark my-1">
                            <img src="https://img.icons8.com/external-icematte-lafs/28/000000/external-Resources-it-icematte-lafs.png"/>
                            <span class="vm ml-2">ارسال درخواست</span>
                        </h6>
                    </div>
                    {{-- <div class="col-auto">
                        <a class="dropdown-item" href="{{ route('user.tickets') }}">نمایش همه</a>
                    </div> --}}
                </div>
            </div>
            <div class="card-body">
                <div class="row px-2 mb-0" id="mamad">
                    <div class="col-6 col-md-3 px-2 mb-3" onclick="document.getElementById('leave-create-modal').click()">
                        <input type="radio" name="facilitiestype" class="checkbox-boxed" id="garden">
                        <label class="checkbox-lable" for="garden">
                            <span class="image-boxed text-white">
                                <img src="https://img.icons8.com/external-icematte-lafs/62/000000/external-Calendar-it-icematte-lafs.png"/>
                            </span>
                            <span class="pt-2 h6">ثبت مرخصی</span>
                        </label>
                    </div>
                    <div class="col-6 col-md-3 px-2 mb-3" onclick="location.href = '{{route('user.my_leave')}}';">
                        <input type="radio" name="facilitiestype" class="checkbox-boxed" id="sport">
                        <label class="checkbox-lable" for="sport">
                            <span class="image-boxed text-white">
                                <img src="https://img.icons8.com/external-icematte-lafs/62/000000/external-Services-it-icematte-lafs.png"/>
                            </span>
                            <span class="pt-2 h6">مرخصی من</span>
                        </label>
                    </div>
                    <div class="col-6 col-md-3 px-2 mb-3" onclick="document.getElementById('help-create-modal').click()">
                        <input type="radio" name="facilitiestype" class="checkbox-boxed" id="parking">
                        <label class="checkbox-lable" for="parking">
                            <span class="image-boxed text-white">
                                <img src="https://img.icons8.com/external-icematte-lafs/62/000000/external-Cards-it-icematte-lafs.png"/>
                            </span>
                            <span class="pt-2 h6">مساعده</span>
                        </label>
                    </div>
                    <div class="col-6 col-md-3 px-2 mb-3" onclick="location.href = '{{route('user.time-login-index','all')}}';">
                        <input type="radio" name="facilitiestype" class="checkbox-boxed" id="gardeeen">
                        <label class="checkbox-lable" for="gardeeen">
                            <span class="image-boxed text-white">
                                <img src="https://img.icons8.com/external-icematte-lafs/62/000000/external-Monitoring-it-icematte-lafs.png"/>
                            </span>
                            <span class="pt-2 h6">گزارش کار</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- لیست آخرین تیکت ها --}}
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <div class="row mb-0">
                    <div class="col">
                        <h6 class="text-dark my-1">
                            <img src="https://img.icons8.com/ultraviolet/20/000000/edit-property.png"/>
                            <span class="vm ml-2">تیکت های اخیر</span>
                        </h6>
                    </div>
                    <div class="col-auto">
                        <a class="dropdown-item" href="{{ route('user.ticket.index') }}">{{' نمایش همه '.auth()->user()->sorted_tickets()->count()}}</a>
                    </div>
                </div>
            </div>
            @unless (auth()->user()->sorted_tickets()->count())
                <h6 class="text-center mb-2">موردی یافت نشد</h6>
            @endunless
            @foreach(auth()->user()->sorted_tickets()->take(10) as $data)
                <div class="card product-card-small mb-0">
                    <div class="card-body pt-0">
                        <div class="p-2 px-3 border redu30">
                            <div class="row mb-0">
                                <div class="col fs-6">{{$data->user->company__name}}</div>
                                <div class="col-auto">
                                    @if ($data->seen__id==0)
                                        <div class="spinner-grow text-danger" role="status"><span class="sr-only"></span></div>
                                    @endif
                                </div>
                                <div class="col-lg-12">
                                    <div class="my-1">
                                        <a href="{{route('user.ticket.show',$data->id)}}" class="redu-10 p-1 px-2 btn 
                                            @switch($data->ticket__priority)
                                                @case ('high') btn-danger @break;
                                                @case ('normal') btn-warning @break;
                                                @case ('low') @break; btn-success
                                            @endswitch
                                            ">
                                            <p class="p-0 text-light fw-bold">{{$data->ticket__title}} >> </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- لیست آخرین کارها --}}
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <div class="row mb-0">
                    <div class="col">
                        <h6 class="text-dark my-1">
                            <img src="https://img.icons8.com/external-icematte-lafs/28/000000/external-Monitoring-it-icematte-lafs.png"/>
                            <span class="vm ml-2">کارهای اخیر</span>
                            <a class="mx-1" href="{{route('user.work-create')}}"><i class="fa fa-plus"></i></a>

                        </h6>
                    </div>
                    <div class="col-auto">
                        <a class="dropdown-item" href="{{ route('user.works') }}">{{' نمایش همه '.auth()->user()->works()->count()}}</a>
                    </div>
                </div>
            </div>
            @unless (auth()->user()->works()->count())
                <h6 class="text-center mb-2">موردی یافت نشد</h6>
            @endunless
            @foreach(auth()->user()->works()->take(10) as $work)
                @php
                    $workTimesheet_doing=\App\Model\WorkTimesheet::WorkTimeSheetByStatus('work',$work->id,'doing');
                    $workTimesheet_finished=\App\Model\WorkTimesheet::WorkTimeSheetByStatus('work',$work->id,'finished');
                    $workTimesheet_paused=\App\Model\WorkTimesheet::WorkTimeSheetByStatus('work',$work->id,'paused');
                @endphp
                <div class="card product-card-small mb-0">
                    <div class="card-body pt-0">
                        <div class="p-2 ps-3 border redu30">
                            <div class="row mb-0">
                                <div class="col-lg">
                                    <p class="m-0 fs-6">{{$work->title }}</p>
                                    <div class="d-none d-md-block">
                                        <button class="btn collapsed px-0" data-bs-toggle="collapse" data-bs-target="#demo{{$work->id}}"><i class="far teal fa-clone mx-1"></i>نمایش چزییات</button>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row my-0">
                                        <div class="col-6 col-lg-12 my-1 text-center">
                                            @if($workTimesheet_doing)
                                                {{-- <form action="{{route('user.work-stop')}}" method="post" class="m-0">
                                                    <button type="submit" class="btn btn-danger py-0">اتمام کار<i class="fa fa-refresh fa-spin mx-1"></i></button>
                                                    <input type="hidden" value="{{$work->id}}" name="id">
                                                    {{ csrf_field() }}
                                                </form> --}}
                                                <button onclick="document.getElementById('runningJobStoped').click();" class="btn btn-danger py-0">اتمام کار<i class="fa fa-refresh fa-spin mx-1"></i></button>
                                            @elseif($workTimesheet_finished)
                                                <form action="{{route('user.timesheet-store')}}" method="post" class="m-0">
                                                    <button type="submit" class="btn btn-success py-0">ادامه کار</button>
                                                    <input type="hidden" value="{{$work->id}}" name="type_id">
                                                    <input type="hidden" value="work" name="type">
                                                    {{ csrf_field() }}
                                                </form>
                                            @else
                                                <form action="{{route('user.timesheet-store')}}" method="post" class="m-0">
                                                    <button type="submit" class="btn btn-primary py-0">{{ $workTimesheet_paused?'ادامه کار':'شروع کن' }}</button>
                                                    <input type="hidden" value="{{$work->id}}" name="type_id">
                                                    <input type="hidden" value="work" name="type">
                                                    {{ csrf_field() }}
                                                </form>
                                            @endif
                                        </div>
                                        <div class="col-6 col-lg-12 my-1 text-center">
                                            <a href="{{route('user.work-edit',$work->id)}}" class="btn btn-warning py-0">ویرایش کار</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-md-none">
                                <button class="btn collapsed" data-bs-toggle="collapse" data-bs-target="#demo{{$work->id}}"><i class="far fa-clone"></i></button>
                            </div>
                            <div id="demo{{$work->id}}" class="collapse">
                                <p class="m-0"><span class="text-dark">ارجاع دهنده : {{ $work->referrer?$work->referrer->name:'نامشخص' }}</span></p>
                                <p class="small vm my-1">نام شرکت : {{ $work->company?$work->company->company__name:'نامشخص' }}</p>
                                {!! nl2br($work->description) !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- تماس با ما --}}
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h5>
                    <img src="https://img.icons8.com/ultraviolet/18/000000/phone.png"/>
                    تماس با پشتیبانی</h5>
                <p class="text-secondary mb-2">جهت دریافت راهنمایی با کلیک روی دکمه زیر با واحد پشتیبانی تماس بگیرید</p>
                <button onclick="window.open(`tel:{{$setting->support_call}}`);" class="btn btn-success">تماس 
                    <img src="https://img.icons8.com/ultraviolet/20/000000/phone.png"/>
                </button>
            </div>
        </div>
    </div>

    @include('includes.footer')

    <button id="leave-create-modal" class="d-none" data-toggle="modal" data-target="#leave-create">openModal</button>
    <button id="help-create-modal" class="d-none" data-toggle="modal" data-target="#help-create">openModal</button>

    {{-- مرخصی --}}
    <div class="modal fade" id="leave-create" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content redu20"> 
                <div class="modal-header">
                    <h4 class="modal-title">فرم درخواست مرخصی</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{route('user.leave_send')}}" method="post" enctype="multipart/form-data">
                        <fieldset class="content-group">

                            <div class="">
                                <label class="form-label">نام درخواست کننده :</label>
                                <input type="text" class="form-control" value="{{auth()->user()->name}}    « {{auth()->user()->role()->description}} »" disabled/>
                            </div>

                            <div class="row my-2">
                                
                                <div class="col-6 ">
                                    <?php $ndt = date('Y/m/d H:i');?>
                                    <label class="form-label">زمان ثبت درخواست :</label>
                                    <input type="text" class="form-control" value="{{my_jdate($ndt, 'تاریخ : Y/m/d ساعت : H:i')}}" disabled/>
                                </div>
                                
                                <div class="col-6 {{ $errors->has('type') ? ' has-error' : '' }}">
                                    <label for="type" class="form-label">نوع مرخصی :</label>
                                    <select id="type" class="form-control select" name="type">
                                        <option value="2">روزانه</option>
                                        <option value="1">ساعتی</option>
                                    </select>
                                    @if ($errors->has('type'))
                                        <span class="help-block"><strong>{{$errors->first('type')}}</strong></span>
                                    @endif
                                </div>

                            </div>

                            <div class="{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title" class="form-label">بابت :</label>
                                <input id="title" type="text" class="form-control" name="title" value="{{old('title')}}"/>
                                @if ($errors->has('title'))
                                    <span class="help-block"><strong>{{$errors->first('title')}}</strong></span>
                                @endif
                            </div>

                            <div class="row my-2">

                                <div class="col-6 {{ $errors->has('as_date') ? ' has-error' : '' }}">
                                    <label for="as_date" class="form-label">از تاریخ :</label>
                                    <input id="as_date" type="text" class="form-control date_p" name="as_date"  autocomplete="off" readonly/>
                                    @if ($errors->has('as_date'))
                                        <span class="help-block"><strong>{{$errors->first('as_date')}}</strong></span>
                                    @endif
                                </div>

                                <div class="col-6 {{ $errors->has('to_date') ? ' has-error' : '' }}">
                                    <label for="to_date" class="form-label">تا تاریخ :</label>
                                    <input id="to_date" type="text" class="form-control date_p" name="to_date" autocomplete="off" readonly/>
                                    @if ($errors->has('to_date'))
                                        <span class="help-block"><strong>{{$errors->first('to_date')}}</strong></span>
                                    @endif
                                </div>

                                <div class="col-6 {{ $errors->has('as_time') ? ' has-error' : '' }}">
                                    <label for="as_time" class="form-label">از ساعت : (ساعت را وارد کنید)</label>
                                    <input id="as_time" type="time" class="form-control datepickertime" name="as_time" value="10:30"  autocomplete="off"/>
                                    @if ($errors->has('as_time'))
                                        <span class="help-block"><strong>{{$errors->first('as_time')}}</strong></span>
                                    @endif
                                </div>
                                
                                <div class="col-6 {{ $errors->has('to_time') ? ' has-error' : '' }}">
                                    <label for="to_time" class="form-label">تا ساعت : (ساعت را وارد کنید)</label>
                                    <input id="to_time" type="time" class="form-control datepickertime" name="to_time" value="12:30"  autocomplete="off"/>
                                    @if ($errors->has('to_time'))
                                        <span class="help-block"><strong>{{$errors->first('to_time')}}</strong></span>
                                    @endif
                                </div>

                            </div>

                            <div class="{{ $errors->has('description') ? ' has-error' : '' }}">
                                <label for="description" class="form-label">توضیحات :</label>
                                <textarea name="description" id="description" rows="3" class="form-control">{{old('description')}}</textarea>
                                {{-- <input id="description" type="text" class="form-control" name="description" value="{{old('description')}}"/> --}}
                                @if ($errors->has('description'))
                                    <span class="help-block"><strong>{{$errors->first('description')}}</strong></span>
                                @endif
                            </div>

                            {{csrf_field()}}
                        </fieldset>

                        <button type="submit" class="btn btn-success mt-4">ثبت درخواست</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- مساعده --}}
    <div class="modal fade" id="help-create" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content redu20">
                <form action="{{route('user.help_store')}}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h4 class="modal-title">فرم درخواست مساعده</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" >
                        @csrf

                        <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description">توضیحات برای دریافت مساعده</label>
                            <textarea name="description" id="description" rows="5" placeholder="در جه مورد نیاز به مساعده دارید" class="form-control"></textarea>
                            @if ($errors->has('description'))
                                <span class="help-block"><strong>{{$errors->first('description')}}</strong></span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('final_price') ? ' has-error' : '' }}">                        
                            <label for="description">مبلغ مورد نیاز</label>
                            <div class="input-group my-3">
                                <input type="number" placeholder="مبلغ درخواستی را وارد کنید" name="final_price" required="required" class="form-control">

                                <div class="input-group-prepend">
                                    <p class="fs-6 text-light bg-dark p-3 px-4" style="border-radius: 50px 0px 0px 50px;">تومان</p>
                                </div>
                                @if ($errors->has('final_price'))
                                    <span class="help-block"><strong>{{$errors->first('final_price')}}</strong></span>
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success mt-4">ثبت بازدید</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function ConvertNumberToPersion() {
            let persian = { 0: '۰', 1: '۱', 2: '۲', 3: '۳', 4: '۴', 5: '۵', 6: '۶', 7: '۷', 8: '۸', 9: '۹' };
            function traverse(el) {
                if (el.nodeType == 3) {
                    var list = el.data.match(/[0-9]/g);
                    if (list != null && list.length != 0) {
                        for (var i = 0; i < list.length; i++)
                            el.data = el.data.replace(list[i], persian[list[i]]);
                    }
                }
                for (var i = 0; i < el.childNodes.length; i++) {
                    traverse(el.childNodes[i]);
                }
            }
            traverse(document.body);
        }

        ConvertNumberToPersion()
    </script>

@endsection
