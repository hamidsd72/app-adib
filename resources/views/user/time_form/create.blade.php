@extends('user.master')
@section('content')

  <form action="{{route('user.time-login-create-post')}}" method="post" class="mt-5" enctype="multipart/form-data">

    <div class="container">
      <div class="card product-card-large w-100">
        <div class="card-header bg-warning">
            <h6 class="lh-base">فرم {{$title}}</h6>
        </div>
              
        <div class="card-body border-top border-color">                        

          <div class="mb-2 ">
            <label class="form-label">نام درخواست کننده :</label>
            <input type="text" class="form-control" value="{{auth()->user()->name}}    « {{auth()->user()->role()->description}} »" disabled/>
          </div>

          <div class="mb-2 {{ $errors->has('date_fa') ? ' has-error' : '' }}">
            <label for="date_fa" class="form-label">تاریخ * :</label>
            <input id="date_fa" type="text" class="form-control date_p" name="date_fa" required/>
            @if ($errors->has('date_fa'))
              <span class="help-block"><strong>{{$errors->first('date_fa')}}</strong></span>
            @endif
          </div>

          <div class="row mb-2">
            <div class="col {{ $errors->has('time_login') ? ' has-error' : '' }}">
              <label for="time_login" class="form-label"> ساعت ورود <br> روی ساعت کلیک کنید </label>
              <input id="time_login" type="time" class="form-control" value="09:30" name="time_login"/>
              @if ($errors->has('time_login'))
                <span class="help-block"><strong>{{$errors->first('time_login')}}</strong></span>
              @endif
            </div>
  
            <div class="col {{ $errors->has('time_exit') ? ' has-error' : '' }}">
              <label for="time_exit" class="form-label"> ساعت خروج <br> روی ساعت کلیک کنید</label>
              <input id="time_exit" type="time" class="form-control" value="11:30" name="time_exit"/>
              @if ($errors->has('time_exit'))
                <span class="help-block"><strong>{{$errors->first('time_exit')}}</strong></span>
              @endif
            </div>
          </div>

          <div class="mb-2 {{ $errors->has('info') ? ' has-error' : '' }}">
            <label for="info" class="form-label">علت را انتخاب کنید *</label>
            <div class="flex">
              <button type="button" class="mb-2 btn btn-primary" id="فراموشی" onclick="setInfoInput('فراموشی')">فراموشی</button>
              <button type="button" class="mb-2 btn btn-info" id="ماموریت" onclick="setInfoInput('ماموریت')">ماموریت</button>
              <button type="button" class="mb-2 btn btn-info" id="دورکاری" onclick="setInfoInput('دورکاری')">دورکاری</button>
              <button type="button" class="mb-2 btn btn-info" id="جلسه" onclick="setInfoInput('جلسه')">جلسه</button>
              <button type="button" class="mb-2 btn btn-info" id="ادواری" onclick="setInfoInput('ادواری')">ادواری</button>
            </div>
            <input type="hidden" name="info" id="inputInfo" value="فراموشی">
            {{-- <select id="info" class="form-control select" name="info">
              <option value="forget">فراموشی</option>
              <option value="mission">ماموریت از طرف شرکت</option>
              <option value="home_work">کار در منزل</option>
              <option value="meeting">جلسه</option>
            </select> --}}
            @if ($errors->has('info'))
              <span class="help-block"><strong>{{$errors->first('info')}}</strong></span>
            @endif
          </div>

          <div class="{{ $errors->has('text') ? ' has-error' : '' }}">
            <label for="text" class="form-label">شرح * :</label>
            <textarea rows="3" class="form-control" name="text" required>{{old('text')}}</textarea>
            @if ($errors->has('text'))
              <span class="help-block"><strong>{{$errors->first('text')}}</strong></span>
            @endif
          </div>

        </div>

        {{csrf_field()}}

        <div class="card-footer border-top border-color">
          <div class="row mb-0">
              <div class="col">
                  <button type="submit" class="btn btn-success">{{$title}}</button>
              </div>
              <div class="col-auto">
                  <a href="{{ URL::previous() }}" class="btn btn-secondary">برگشت</a>
              </div>
          </div>
        </div>

      </div>
    </div>
  </form>
  
  <script>
    var old = 'فراموشی';
    function setInfoInput($data) {
      document.getElementById('inputInfo').value=$data;
      if (old) {
        document.getElementById(old).classList.remove('btn-primary');
        document.getElementById(old).classList.add('btn-info');
      }
      document.getElementById($data).classList.remove('btn-info');
      document.getElementById($data).classList.add('btn-primary');
      old = $data;
    }
  </script>
@endsection
