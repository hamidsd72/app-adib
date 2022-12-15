@extends('user.master')
@section('content')

    <main class="flex-shrink-0">
        <div class="container">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide text-center pb-3">
                        <div class="image-circle">
                            <figure class="background"><img src="{{ url($setting->logo_site) }}" alt="{{ $setting->title }}"></figure>
                        </div>
                        <h4 class="mt-0 my-3">{{ $setting->title }}</h4>
                        <div class="text-center">
                            <p class="small-font text-secondary px-lg-4">{{$setting->description}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-center" style="bottom: 0px;width: 100%;">
        <div class="container">
            <div class="row">
                <div class="col">
                    <a href="{{route('login')}}" class="btn btn-block col-12 mx-auto btn-info btn-lg">ورود به حساب</a>
                </div>
                <div class="col">
                    <a href="{{route('user.home-guost-pwa')}}" class="btn btn-block col-12 mx-auto btn-dark btn-lg">نصب اپلیکیشن</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        "use strict"
        $(window).on('load', function() {
            var swiper = new Swiper('.swiper-container', {
                pagination: {
                    el: '.swiper-pagination',
                },
            });
        });
    </script>

@endsection

