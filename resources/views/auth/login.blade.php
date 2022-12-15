@extends('user.master')
@section('content')
    <style>
        .flex-shrink-0 .floating-form-group > .floating-label { z-index: 99; }
    </style>

    <form method="POST" action="{{route('login')}}">
        <main class="flex-shrink-0">
            <div class="container text-center mt-4">
                <div class="icon icon-100 text-white mb-4 text-center">
                    <img src="{{ url($setting->logo_site) }}" alt="{{ $setting->title }}" style="width: 100px;border-radius: 50px;">
                </div>
                <h4 class="mb-4">{{ $setting->title }}</h4>
            </div>
            
            <div class="container">
                <div class="login-box">
                    <div class="form-group floating-form-group">
                        <input type="text" name="user_name" id="user_name" class="form-control floating-input text-end" required autofocus>
                        <label class="floating-label">نام کاربری خود را وارد کنید</label>
                        <h6 class="text-danger text-center p-1">{{$error ?? ''}}</h6>
                    </div>
                    
                    <div class="form-group floating-form-group">
                        <input type="password" name="password" id="password" class="form-control floating-input text-end" required>
                        <label class="floating-label">رمزعبور را وارد کنید</label>
                        <h6 class="text-danger text-center p-1">{{$error ?? ''}}</h6>
                        @if ($errors->count())
                            <h6 class="text-danger text-center p-1">نام کاربری یا رمزعبور اشتباه است</h6>
                        @endif
                    </div>
                    <div class="form-group my-4 text-secondary">
                        با کلیک روی دکمه زیر قوانین را مطالعه میکنم
                        <br>
                        <a href="#" data-toggle="modal" data-target="#modal" class="link">قوانین و مقررات</a>
                    </div>
                    <button type="submit" class="btn col-12 btn-block btn-info mt-2">ورود</button>
                </div>
            </div>
        </main>
        @csrf
    </form>

    <div class="modal" id="modal">
        <div class="modal-dialog modal-dialog-scrollable pt-4">
            <div class="modal-content" style="border-radius: 30px;">
                <div class="modal-body">
                    <h4 class="mb-3">{{$about->title_rule}}</h4>
                    {!! $about->text_rule !!}
                    <button data-dismiss="modal" class="btn btn-success col-12 btn-block mt-3">قوانین را قبول دارم </button>
                </div>
            </div>
        </div>
    </div>

    {{-- <script>
        $(function(){
            $("input[name='mobile']").on('input', function (e) {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });
        });
    </script> --}}
@endsection

