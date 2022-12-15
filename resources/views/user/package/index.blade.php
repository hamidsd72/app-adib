@extends('user.master')
@section('content')

    @if ($runningJob)
        <div class="runningJob">
            <a href="#" class="text-danger" id="clickToOpenModal2"data-toggle="modal" data-target="#ModalTicket2">
                توقف کار
                <i class="fa fa-refresh fa-spin" style="font-size:12px"></i>
            </a>
        </div>
    @endif
    <div class="container mt-5 pt-3">
        @if ($items->count())
            <p class="text-secondary text-center">لیست فعالیت ها <br><span class="text-dark">{{$items->count()}} فعالیت</span> برای انجام دادن </p>
        @endif
        <div class="row">
            @foreach($items as $key => $package)
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card product-card-large w-100 mb-4">
                        <div class="card-header">
                            <div class="row mb-0">
                                <div class="col-auto">
                                    <h6 class="lh-base">{{$package->packageName()?$package->packageName()->title:'________'}}</h6>
                                </div>
                                <div class="col-auto">
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body border-top border-color">
                            {{-- <div class="product-image-large">
                                <div class="background" style="background-image: url({{url($package->pic_card)}});">
                                    <img src="{{url($package->pic_card)}}" alt="" style="display: none;">
                                </div>
                                <div class="tag-images-count text-white bg-dark">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-size-16 vm" viewBox="0 0 512 512">
                                        <title>ionicons-v5-e</title>
                                        <path d="M432,112V96a48.14,48.14,0,0,0-48-48H64A48.14,48.14,0,0,0,16,96V352a48.14,48.14,0,0,0,48,48H80" style="fill:none;stroke:#000;stroke-linejoin:round;stroke-width:32px"></path>
                                        <rect x="96" y="128" width="400" height="336" rx="45.99" ry="45.99" style="fill:none;stroke:#000;stroke-linejoin:round;stroke-width:32px"></rect>
                                        <ellipse cx="372.92" cy="219.64" rx="30.77" ry="30.55" style="fill:none;stroke:#000;stroke-miterlimit:10;stroke-width:32px"></ellipse>
                                        <path d="M342.15,372.17,255,285.78a30.93,30.93,0,0,0-42.18-1.21L96,387.64" style="fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"></path>
                                        <path d="M265.23,464,383.82,346.27a31,31,0,0,1,41.46-1.87L496,402.91" style="fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"></path>
                                    </svg>
                                    <span class="vm">10</span>
                                </div>
                                <button class="small-btn btn btn-danger text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-size-16 vm" viewBox="0 0 512 512">
                                        <title>ionicons-v5-f</title>
                                        <path d="M352.92,80C288,80,256,144,256,144s-32-64-96.92-64C106.32,80,64.54,124.14,64,176.81c-1.1,109.33,86.73,187.08,183,252.42a16,16,0,0,0,18,0c96.26-65.34,184.09-143.09,183-252.42C447.46,124.14,405.68,80,352.92,80Z" style="fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"></path>
                                    </svg>
                                </button>
                            </div> --}}
                        
                            <div class="row mb-0">
                                <div class="col-auto ml-auto">
                                    <p class="small text-secondary m-0">{{' محل فعالیت : '.$package->location_work}}</p>

                                    <span class="text-secondary">
                                        @if ($package->job->count())
                                            {{ ' کل زمان فعالیت '.$package->jobTodayTime().' دقیقه '}}
                                        @else
                                            هنوز شروع نکرده اید
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer border-top border-color">
                            <div class="row mb-0">
                                <div class="col my-auto">
                                    {{my_jdate($package->started_at,'d F Y')}}
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('user.package',$package->slug) }}" class="btn btn-primary p-0 px-3">جزییات</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $items->links() }}
    </div>

    {{-- <div data-bs-parent="#tab-group-listing" class="collapse show mx-3" id="tab-1">
        @foreach($items as $key => $package)
            <div class="card card-style mx-0">
                <div class="card card-style mx-2 mt-2" data-card-height="200" style='box-shadow: none;background-image: url("{{url($package->pic_card)}}")'>
                    <div class="card-top p-3 pe-2 pt-2">
                        <a href="#" data-toast="snackbar-favorites" class="float-end">
                            @if($package->price == 0)
								<span class="bg-danger color-white px-2 py-2 rounded-sm">
									رایگان
								</span>
                            @endif
                        </a>
                    </div>
                </div>
                <a href="{{route('user.package',$package->slug)}}">
                    <div class="content mt-n3">
                        <h2>{{ $package->title }}</h2>
                        <div class="d-flex">
                            <div>
                                <span class="d-block color-green-dark font-700 mt-2">درحال برگزاری</span>
                            </div>
                            <div class="ms-auto">
                                @if ($package->price > 0)
                                    <h6 class="pt-2">{{ $package->price }} تومان <sup class="font-14 font-400 opacity-50"></sup></h6>
                                @endif
                            </div>
                        </div>
                        <div class="divider mt-3 mb-3"></div>
                        <div class="d-flex">
                            <div class="align-self-center">
								<span>
									<i class="fa fa-star font-12 color-yellow-dark pe-1"></i>
									<i class="fa fa-star font-12 color-yellow-dark pe-1"></i>
									<i class="fa fa-star font-12 color-yellow-dark pe-1"></i>
									<i class="fa fa-star font-12 color-yellow-dark pe-1"></i>
									<i class="fa fa-star font-12 color-yellow-dark pe-1"></i>
								</span>
                                <span class="d-block opacity-70 font-11 mt-n2 color-theme"></span>
                            </div>
                            <div class="align-self-center ms-auto">
                                <a href="{{route('user.package',$package->slug)}}" data-toast="snackbar-cart" 
                                    class="btn btn-s bg-blue-dark rounded-sm font-700 text-uppercase">مشاهده</a>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
        @if ($items->count() == 0)<h5 class="text-center">کارگاهی یافت نشد</h5>@endif
    </div> --}}
    <div class="modal fade" id="ModalTicket2" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content redu20"> 
                <div class="modal-header">
                    <h4 class="modal-title">ثبت گزارش از فعالیت انجام شده</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="content mt-0">
                        <form method="POST" action="{{route('user.job_stop')}}" enctype="multipart/form-data">
                            @csrf
                            <fieldset>
                                <input type="hidden" name="job_id" id="job_id">
                                                 
                                <div class="form-field form-text">
                                    <label class="contactMessageTextarea color-theme" for="description">متن:<span>(required)</span></label>
                                    <textarea name="description" class="round-small mb-0" id="description" required></textarea>
                                </div>
                                <div class="form-field form-text">
                                    <label class="contactMessage color-theme" for="price">هزینه ها:<span>(required)</span></label>
                                    <input type="number" name="price" id="price" class="col-12 text-end round-small mb-0">
                                </div>
                                <div class="mb-4">
                                    <label class="contactMessageTextarea color-theme" for="attach">الحاق فایل:</label>
                                    <input type="file" name="attach" id="attach" class="form-control">
                                </div>
                                <div class="form-button">
                                    <input type="submit" class="btn btn-primary col-12" value=" ثبت گزارش و پایان فعالیت" data-formid="contactForm">
                                </div>
                            </fieldset> 
                        </form>
                    </div>
                </div>
            </div>
    
        </div>
    </div>
    
@endsection

