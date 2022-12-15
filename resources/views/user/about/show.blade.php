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
                    <h1 class="h1-title">درباره ما</h1>
                </div>
            </div>
        </div>
    </section>
    
    <div class="main-banner-breadcrum">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="banner-breadcrum">
                        <ul>
                            <li><a href="{{url('/')}}">خانه</a></li>
                            <li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
                            <li><a href="{{route('user.about.show')}}">درباره ما</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="main-course-detail-in">
        <div class="container">
            <div class="row justify-content-center" dir="rtl">

                <div class="col-xl-8 col-lg-7">
                    <div class="course-detail-box">
                        <h2 class="h2-title">درباره ما</h2>
                        <div class="course-detail-user-box">
                            <div class="row align-items-center">
                                <div class="col-xxl-5 col-xl-12 col-lg-12">
                                    <div class="course-detail-instructor-date-box">

                                    </div>
                                </div>
                                <div class="col-xxl-7 col-xl-12 col-lg-12">

                                </div>
                            </div>
                        </div>
                        <div class="course-detail-img wow fadeInUp  animated" data-wow-delay=".4s"
                         style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;">
                            <img src="{{url($item->pic)}}" alt="course">
                        </div>
                        {!! $item->text!!}
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
