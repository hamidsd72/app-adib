@extends('layouts.user')

@section('content')

    <section class="main-banner-in">
    <span class="shape-1 animate-this" style="transform: translateX(-17.5782px) translateY(-9.99425px);">
        <img src="{{url('source/asset/user/images/shape-1.png')}}" alt="shape">
    </span>
        <span class="shape-2 animate-this" style="transform: translateX(-17.5782px) translateY(-9.99425px);">
        <img src="{{url('source/asset/user/images/shape-2.png')}}" alt="shape">
    </span>
        <span class="shape-3 animate-this" style="transform: translateX(-17.5782px) translateY(-9.99425px);">
        <img src="{{url('source/asset/user/images/shape-3.png')}}" alt="shape">
    </span>
        <span class="shape-4 animate-this" style="transform: translateX(-17.5782px) translateY(-9.99425px);">
        <img src="{{url('source/asset/user/images/shape-4.png')}}" alt="shape">
    </span>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="h1-title"> {{$ServiceCat->title }}</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="main-course-category" dir="rtl">
        <div class="container">

            <div class="row">
                @foreach($items as $key => $package)
                    <div class="col-lg-4">
                        <div class="course-box" dir="rtl">
                            <div class="course-img">
                                @if($package->photo->path != '' && $package->photo->path != null)
                                    <img style="height: 300px;" src="{{url($package->photo->path)}}" alt="course">
                                @endif
                                {{--<ul>
                                    <li><a href="javascript:void(0);" class="course-tag-orange">Business</a></li>
                                    <li><a href="javascript:void(0);" class="course-tag-blue">Marketing</a></li>
                                </ul>--}}
                            </div>
                            <div class="course-content">
                                <a href="{{route('user.service',[$package->id,$package->slug])}}">
                                    <h3 class="h3-title s-title">{{$package->title}}</h3>
                                </a>
                                <?php
                                $admin = App\User::where('id',1)->first();
                                ?>
                                <div class="course-instructor-review">
                                    <div class="course-instructor-box">
                                        <div class="course-instructor-img">
                                            <img style="width: 50px;" src="{{$admin->photo? url($admin->photo->path) :asset('admin/img/user.png')}}" class="rounded-circle" alt="instructor">
                                        </div>
                                        <a href="{{route('user.service',[$package->id,$package->slug])}}">{{$admin->first_name}}</a>
                                    </div>
                                    <div class="course-review-box">
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <p>5.0 (2k)</p>
                                    </div>
                                </div>
                                <div class="course-line"></div>
                                <div class="course-price-student-box">
                                    <div class="course-price-box">
                                        <span>{{price($package->price)}} تومان</span>
                                    </div>
                                    <div class="course-student-box">
                                        <div class="course-student-icon">
                                            <img src="{{url('source/asset/user/images/student-icon.png')}}" alt="icon">
                                        </div>
                                        <p>600k</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

@endsection
