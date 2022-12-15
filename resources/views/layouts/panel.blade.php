@php
  if(Auth::user()->id == 1000000){
      echo '<p style="float: right; width: 100%; text-align: center; font-size: 60px; color: red;margin-bottom: 0;">دسترسی شما به علت رعایت نکردن قوانین ارسال تیکت مسدود شده است</p>';
  }
  else {
@endphp

        <!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="rtl">
<head>

  <link rel="apple-touch-icon" sizes="57x57" href="{{url('favicon/apple-icon-57x57.png')}}">
  <link rel="apple-touch-icon" sizes="60x60" href="{{url('favicon/apple-icon-60x60.png')}}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{url('favicon/apple-icon-72x72.png')}}">
  <link rel="apple-touch-icon" sizes="76x76" href="{{url('favicon/apple-icon-76x76.png')}}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{url('favicon/apple-icon-114x114.png')}}">
  <link rel="apple-touch-icon" sizes="120x120" href="{{url('favicon/apple-icon-120x120.png')}}">
  <link rel="apple-touch-icon" sizes="144x144" href="{{url('favicon/apple-icon-144x144.png')}}">
  <link rel="apple-touch-icon" sizes="152x152" href="{{url('favicon/apple-icon-152x152.png')}}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{url('favicon/apple-icon-180x180.png')}}">
  <link rel="icon" type="image/png" sizes="192x192" href="{{url('favicon/android-icon-192x192.png')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{url('favicon/favicon-32x32.png')}}">
  <link rel="icon" type="image/png" sizes="96x96" href="{{url('favicon/favicon-96x96.png')}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{url('favicon/favicon-16x16.png')}}">
  <link rel="manifest" href="{{url('favicon/manifest.json')}}">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="{{url('favicon/ms-icon-144x144.png')}}">
  <meta name="theme-color" content="#ffffff">
  <meta name="url" content="{{url('/')}}">
  <meta charset="UTF-8">
  <meta content-type="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{--<title>{{ config('app.name') }}</title>--}}
  <title>{{ $titleSeo }}</title>
  <meta name="description" content="{{$descriptionSeo}}"/>
  <meta name="keywords" content="{{$keywordsSeo}}">

  <meta property="og:title" content="{{$titleSeo}}"/>
  <meta property="og:description" content="{{$descriptionSeo}}"/>
  {{--    <link rel="canonical" href="{{$url_page}}"/>--}}
<!-- Styles -->

{{--  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"--}}
{{--        integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">--}}
  <link rel="stylesheet" href="{{ asset('assets/css/iconsmind.css') }}">
  <link href="{{ asset('assets/css/theme-gull.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ url('assets/css/froala_editor.pkgd.min.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ url('assets/css/froala_style.min.css') }}"/>
  <link type="text/css" href="https://cdn.datatables.net/buttons/1.1.2/css/buttons.dataTables.min.css?v1"
        rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/jquery.timepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/nprogress.css') }}">
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.12/dist/css/bootstrap-select.min.css?v1">
  <link rel="stylesheet" href="{{ asset('assets/css/jquery.growl.css') }}">
  <link href="{{ asset('assets/css/panel.css?v=0.0.1') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/lib.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/perfect-scrollbar.min.css') }}">

  <link rel="shortcut icon" href="https://support.adib-it.com/img/logo.png">
  <script async src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
          integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
          crossorigin="anonymous"></script>
  <script src="{{ asset('assets/js/lib.js') }}"></script>
  <style>
      button.text-danger,
      a.text-danger
      {
          color: darkred!important;
          background: unset!important;
          border: unset!important;
          box-shadow: unset!important;
      }
      button.text-info,
      a.text-info
      {
          color: darkblue!important;
          background: unset!important;
          border: unset!important;
          box-shadow: unset!important;
      }
      button i.nav-icon,
      a i.nav-icon
      {
          font-size: 25px;
      }
      .alert-notify {
          z-index: 99999;
      }
      .select2-container
      {
          width: 100%!important;
      }
      @keyframes pulse {
          0% {
              -moz-box-shadow: 0 0 0 0 rgba(27, 89, 141, 1);
              box-shadow: 0 0 0 0 rgba(27, 89, 141, 1);
          }
          90% {
              -moz-box-shadow: 0 0 35px 20px rgba(27, 89, 141, 0);
              box-shadow: 0 0 35px 20px rgba(27, 89, 141, 0);
          }
          100% {
              -moz-box-shadow: 0 0 0 0 rgba(27, 89, 141, 0);
              box-shadow: 0 0 0 0 rgba(27, 89, 141, 0);
          }
      }

      .wat_sapp {
          position: fixed;
          text-align: center;
          bottom: 6%;
          left: 2%;
          z-index: 999;
          animation-name: text-focus-in;
          animation-duration: 2.2s;
          animation-timing-function: linear;
          animation-delay: 0s;
          animation-iteration-count: infinite;
          animation-direction: alternate;
          animation-fill-mode: backwards;
      }

      .wat_sapp img {
          width: 48%;
          border-radius: 50px !important;
          box-shadow: 0 0 50px 11px #2D3E48;
          margin-bottom: 5px;
          animation: pulse 2s infinite;
      }

      .wat_sapp p {
          animation: pulse2 2s infinite;
          background: #3f81cd;
          padding: 7px 15px;
          color: #fff;
          border-radius: 4px;
          display: none;
      }
  </style>
  @yield('styles_meta')

</head>
<body>
@if(isset($watsap))
  @if(auth()->user()->role_id==5)
    <div class="wat_sapp wat_sapp1">
      {{--    <a target="_blank" rel="noreferrer" href="https://api.whatsapp.com/send?phone=+905317677479">--}}
      <a target="_blank" rel="noreferrer" href="https://api.whatsapp.com/send?phone=+905411010374">
        <img class="social_img" src="{{url('https://adib-it.com/adib/whatss4.png')}}" alt="whatsapp adib">
        <p>ارتباط با واحد سایت</p>
      </a>
    </div>
  @endif
@endif
{{--@if(count($recentWorks))--}}
{{--    <div class="left-sidebar">--}}
{{--        <div class="switcher-icon">--}}
{{--            --}}{{--                <i class="zmdi zmdi-settings zmdi-hc-spin"></i>--}}
{{--            <i class="nav-icon i-Bell1 zmdi-hc-spin"></i>--}}
{{--        </div>--}}
{{--        <div class="left-sidebar-content">--}}

{{--            <p class="mb-0">کار های اخیر</p>--}}
{{--            <hr>--}}

{{--            @foreach($recentWorks as $recentWork)--}}
{{--                @php--}}
{{--                    $workTimesheet_doing=\App\Models\WorkTimesheet::WorkTimeSheetByStatus('work',$recentWork->id,'doing');--}}
{{--                    $workTimesheet_finished=\App\Models\WorkTimesheet::WorkTimeSheetByStatus('work',$recentWork->id,'finished');--}}
{{--                    $workTimesheet_paused=\App\Models\WorkTimesheet::WorkTimeSheetByStatus('work',$recentWork->id,'paused');--}}
{{--                @endphp--}}
{{--                <div class="card" style="">--}}
{{--                    <img class="card-img-top" src="https://www.w3schools.com/bootstrap4/img_avatar1.png" alt="Card image" style="width:100%">--}}
{{--                    <i class="nav-icon i-Bell1 card-img-top"></i>--}}
{{--                    <div class="card-body">--}}
{{--                        <h4 class="card-title">{{ $recentWork->title }}</h4>--}}
{{--                        <p class="card-text">--}}
{{--                            {!! str_limit(nl2br($recentWork->description),100,'...')!!}--}}
{{--                        </p>--}}
{{--                        <a href="#" class="btn btn-primary stretched-link">شروع کن</a>--}}
{{--                        @if($workTimesheet_doing)--}}
{{--                            <form action="{{url('panel/work-stop')}}" method="post" class="selectProject-form w-100">--}}
{{--                                <button type="submit" style="margin-right: 10px;background-image: linear-gradient(230deg, rgb(232, 58, 58), rgb(234, 75, 75));" class="table-status startWorkBtn table-doing"><i--}}
{{--                                            class="far fa-stop-circle" style="margin-left: 5px;"></i>اتمام کار--}}
{{--                                </button>--}}
{{--                                <input type="hidden" value="{{$recentWork->id}}" name="id">--}}
{{--                                {{ csrf_field() }}--}}
{{--                            </form>--}}
{{--                        @endif--}}
{{--                        @if($workTimesheet_finished)--}}
{{--                            <form action="{{url('panel/timesheet-store')}}" method="post" class="selectProject-form w-100">--}}
{{--                                <button type="submit" style="background-image: linear-gradient(230deg, #759bff, #843cf6);" class="table-status table-doing startWorkBtn"><i--}}
{{--                                            class="far fa-play-circle" style="margin-left: 5px;"></i>از سرگیری مجدد</button>--}}
{{--                                <input type="hidden" value="{{$recentWork->id}}" name="type_id">--}}
{{--                                <input type="hidden" value="work" name="type">--}}
{{--                                {{ csrf_field() }}--}}
{{--                            </form>--}}
{{--                        @endif--}}

{{--                        @if(!$workTimesheet_doing && !$workTimesheet_finished)--}}
{{--                            <form action="{{url('panel/timesheet-store')}}" method="post" class="selectProject-form w-100">--}}
{{--                                <button type="submit" style="background-image: linear-gradient(230deg, #759bff, #843cf6);" class="table-status startWorkBtn table-doing"><i--}}
{{--                                            class="far fa-play-circle" style="margin-left: 5px;"></i>--}}
{{--                                    {{ $workTimesheet_paused?'ادامه کار':'شروع کن' }}--}}
{{--                                </button>--}}
{{--                                <input type="hidden" value="{{$recentWork->id}}" name="type_id">--}}
{{--                                <input type="hidden" value="work" name="type">--}}
{{--                                {{ csrf_field() }}--}}
{{--                            </form>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endforeach--}}

{{--        </div>--}}
{{--    </div>--}}
{{--@endif--}}
{{-- Modal Section --}}
@if($workTimesheet)
  @php

    try{

        $remainedTime=gmdate('H:i:s',$workTimesheet->getPassedSeconds());
        $remainedTimeArray=explode(':',$remainedTime);

        $remainedHour=$remainedTimeArray[0];
        $remainedMinute=$remainedTimeArray[1];
        $remainedSecond=$remainedTimeArray[2];

    }catch (Exception $e){

        $currentTime = Carbon\Carbon::now();
        $remainedSeconds=$currentTime->diffInSeconds($workTimesheet->created_at);
        $remainedTime=gmdate('H:i:s',$remainedSeconds);
        $remainedTimeArray=explode(':',$remainedTime);

        $remainedHour=$remainedTimeArray[0];
        $remainedMinute=$remainedTimeArray[1];
        $remainedSecond=$remainedTimeArray[2];

    }

  @endphp
  {{-- pause form --}}
  <form id="workTimesheet-pause" action="{{ route('timesheet-pause') }}" method="post">
    <input type="hidden" value="{{$workTimesheet->type}}" name="type">
    <input type="hidden" value="{{$workTimesheet->type_id}}" name="type_id">
    {{csrf_field()}}
  </form>
  {{-- stop form --}}
  <form id="workTimesheet-stop" action="{{ route('timesheet-stop') }}" method="post">
    <input type="hidden" value="{{$workTimesheet->type}}" name="type">
    <input type="hidden" value="{{$workTimesheet->type_id}}" name="type_id">
    {{csrf_field()}}
  </form>
  <div class="workTimesheetConrainer" dir="ltr">
        <span id="workTimesheet">
        <i class="far fa-hourglass fa-2x workTimesheetIcon"></i>
            {{--<span>کار 92161</span>--}}
            <span class="hou">{{$remainedHour}}</span>
        :
        <span class="min">{{$remainedMinute}}</span>
        :
        <span class="sec">{{$remainedSecond}}</span>
        </span>
    {{-- pause btn --}}
    <span class="t-pause" onclick="$('#workTimesheet-pause').submit()">
            <i class="fas fa-pause"></i>
        </span>
    {{-- stop btn --}}
    <span class="t-stop" onclick="$('#workTimesheet-stop').submit()">
            <i class="fas fa-stop"></i>
        </span>
    <span id="workTimesheetTitle">
            @php $type=$workTimesheet->getTypeColumns($workTimesheet->type,$workTimesheet->type_id) @endphp
            <a target="_blank"
               href="{{ $workTimesheet->getRoute($workTimesheet->type,$workTimesheet->type_id) }}"> <span>{{ $type['type'] }}  {{ str_limit($type['title'],18) }}</span> </a>
        </span>
  </div>
@endif
@if(isset($i) && auth()->user()->role_id==4 && count($tickets) && !$workTimesheet)
  <div class="modal fade" id="projectSelector" tabindex="-1" role="dialog" aria-labelledby="messenger"
       aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-left">
            <i style="margin-right: 5px;font-size: 16px;margin-top: auto;"
               class="fas fa-project-diagram"></i>
            Project Selector
          </h5>
        </div>
        <div class="modal-body">
          <form action="{{url('panel/timesheet-store')}}" method="post" id="selectProject-form">
            <div class="row">
              <div class="col-md-12">
                <h5 style="padding: 14px 0;margin-bottom: 15px;" class="alert alert-warning mb-3">وقت
                  بخیر جناب {{ auth()->user()->name }} کار امروز خود را انتخاب کنید</h5>
              </div>
              <div class="col-md-6">
                <select name="type_id" class="form-control" id="sheetType_id">
                  <option value="">انتخاب شود</option>
                  @foreach($tickets as $ticket)
                    <option data-type="ticket"
                            value="{{$ticket->id}}">{{$ticket->ticket__title}} {{ $ticket->user?'('.$ticket->user->company__name.')':'' }}</option>
                  @endforeach
                  @foreach($user_phases as $phase)
                    <option data-type="phase"
                            value="{{$phase->id}}">{{$phase->phase__name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <select name="type" class="form-control" id="sheetType">
                  <option value="ticket">ticket</option>
                  <option value="phase">phase</option>
                </select>
              </div>
              <div class="col-md-12 text-center" style="margin-top: 20px;">
                <button type="submit" class="btn btn-info">
                  <i class="far fa-play-circle"></i>
                  شروع کار
                </button>
              </div>
            </div>
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>
@endif
<div id="app" class="layout-sidebar-large">
  <div class="main-header">
    <div class="logo">
      <img src="{{asset('img/logo-adib-dark.png')}}" alt="">
    </div>
    <div class="menu-toggle">
      <div></div>
      <div></div>
      <div></div>
    </div>
    <div class="d-flex align-items-center d-none d-sm-block">
      <!-- Mega menu -->
      <div class="dropdown mega-menu d-none d-md-block">
        <a href="#" class="btn text-muted dropdown-toggle mr-3" id="dropdownMegaMenuButton"
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">منوی سریع</a>
        <div class="dropdown-menu text-left" aria-labelledby="dropdownMenuButton">
          <div class="row m-0">
            <div class="col-md-4 p-4">
              <p class="text-primary text--cap border-bottom-primary d-inline-block">Components</p>
              <ul class="links">
                <li><a href="accordion.html">Accordion</a></li>
                <li><a href="alerts.html">Alerts</a></li>
                <li><a href="buttons.html">Buttons</a></li>
                <li><a href="badges.html">Badges</a></li>
                <li><a href="carousel.html">Carousels</a></li>
                <li><a href="lists.html">Lists</a></li>
                <li><a href="popover.html">Popover</a></li>
                <li><a href="tables.html">Tables</a></li>
                <li><a href="datatables.html">Datatables</a></li>
                <li><a href="modals.html">Modals</a></li>
                <li><a href="nouislider.html">Sliders</a></li>
                <li><a href="tabs.html">Tabs</a></li>
              </ul>
            </div>
            <div class="col-md-4 p-4">
              <p class="text-primary text--cap border-bottom-primary d-inline-block">Features</p>
              <div class="menu-icon-grid w-auto p-0">
                <a href="{{url('/')}}"><i class="i-Shop-4"></i>خانه</a>
                <a href="{{url(userSection().'/ticket')}}"><i class="i-Mail"></i>تیکت</a>
              </div>
            </div>
            <div class="col-md-4 p-4 bg-img">
              <h2 class="title">Mega Menu <br> Sidebar</h2>
              <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Asperiores natus laboriosam
                fugit, consequatur.
              </p>
              <p class="mb-4">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Exercitationem
                odio amet eos dolore suscipit placeat.</p>
              <button class="btn btn-lg btn-rounded btn-outline-warning">Learn More</button>
            </div>
          </div>
        </div>
      </div>
      <!-- / Mega menu -->
      <div class="search-bar">
        <input type="text" placeholder="Search">
        <i class="search-icon text-muted i-Magnifi-Glass1"></i>
      </div>
    </div>
    <div class="d-none d-sm-block" style="margin: auto"></div>
    <div class="header-part-right">
      <!-- Full screen toggle -->
      <i class="i-Full-Screen header-icon d-none d-sm-inline-block"
         onclick="openFullscreen(document.documentElement)" data-fullscreen=""></i>
      <!-- Grid menu Dropdown -->
      <div class="dropdown">
        <i class="i-Safe-Box text-muted header-icon" role="button" id="dropdownMenuButton"
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <div class="menu-icon-grid">
            <a href="{{url('/')}}"><i class="i-Bar-Chart"></i>پیشخوان</a>
            <a target="_blank" href="https://adib-it.com"><i class="i-Shop-4"></i>خانه</a>
            <a href="{{url(userSection().'/ticket')}}"><i class="i-Mail"></i>تیکت</a>
          </div>
        </div>
      </div>
      <!-- Notificaiton -->
      <div class="dropdown">
        <div class="badge-top-container" role="button" id="dropdownNotification" data-toggle="dropdown"
             aria-haspopup="true" aria-expanded="false">
          <span class="badge badge-primary">{{count(auth()->user()->unreadNotifications)}}</span>
          <i class="i-Bell text-muted header-icon"></i>
        </div>
        <!-- Notification dropdown -->
        <div class="dropdown-menu dropdown-menu-right notification-dropdown rtl-ps-none ps"
             aria-labelledby="dropdownNotification" data-perfect-scrollbar="" data-suppress-scroll-x="true">
@if(count(auth()->user()->unreadNotifications))
  <a href="javascript:void(0)" data-url="{{route('notification.read.all')}}" class="all_read_not">
            <div class="d-flex dropdown-item text-center">
              <div class="notification-details flex-grow-1">
                <p class="text-small text-muted m-0">× خالی کردن نوتیفیکیشن ×</p>
              </div>
            </div>
  </a>
  @foreach(auth()->user()->unreadNotifications as $key=>$notification)
              <a href="{{$notification->data['url'].'?id_not='.$notification->key}}" target="_blank">
            <div class="dropdown-item d-flex">
                <div class="notification-icon">
                  <i class="nav-icon i-Clock-Forward text-primary mr-1"></i>
                </div>
                <div class="notification-details flex-grow-1">
                  <p class="m-0 d-flex align-items-center">
                    <span>{{$notification->data['title']??''}}</span>
                    <span class="flex-grow-1"></span>
                    <span class="text-small text-muted ml-auto">{{ g2j($notification->created_at,'Y/m/d H:i') }}</span>
                  </p>
                  <p class="text-small text-muted m-0">{{$notification->data['name']}}</p>
                </div>
            </div>
              </a>
            @endforeach
          @else
<div class="d-flex dropdown-item text-center">
  <div class="notification-details flex-grow-1">
    <p class="text-small text-muted m-0"> خالی می باشد!</p>
  </div>
</div>
          @endif

<div class="ps__rail-x" style="left: 0px; bottom: 0px;">
  <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
</div>
<div class="ps__rail-y" style="top: 0px; right: -6px;">
  <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
</div>
</div>
</div>
<!-- Notificaiton End -->
<!-- User avatar dropdown -->
<div class="dropdown">
<div class="user col align-self-end">
<img src="{{auth()->user()->profile?url(auth()->user()->profile):asset('img/1.jpg')}}"
     id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
  <div class="dropdown-header">
    <i class="i-Lock-User mr-1"></i> {{ Auth::user()->name }}
    ({{ Auth::user()->id == 1 ? 'برنامه نویس ارشد' : auth()->user()->role->description }})
  </div>

  @if (Auth::user()->role_id == 1)
    {{--<a class="dropdown-item" href="{{url('panel/profile', auth()->user()->id)}}">--}}
    {{--پروفایل--}}
    {{--</a>--}}
    <a class="dropdown-item" href="{{url('panel/technical-admin-index')}}">
      کارتابل مدیر فنی
      ({{$technical_admin_device_count}})
    </a>
    <a class="dropdown-item" href="{{url('panel/financial-devices')}}">
      کارتابل مالی
      ({{$financial_device_count}})
    </a>
  @endif

  <a class="dropdown-item" href="{{Auth::user()->role_id == 5? url('dashbord/profile', auth()->user()->id) : url('panel/profile', auth()->user()->id)}}">پروفایل</a>
  @if (Auth::user()->role_id != 5 and Auth::user()->role_id != 1 )

    <a class="dropdown-item" href="{{url('panel/leave')}}">فرم درخواست مرخصی</a>
    <a class="dropdown-item" href="{{url('panel/my_leave')}}">درخواست های مرخصی من</a>
    <a class="dropdown-item" href="{{url('panel/help-list')}}">درخواست مساعده</a>
  @endif
  <a class="dropdown-item" href="{{route('time-login-index','all')}}">ساعت ورود/خروج</a>
  <a onclick="event.preventDefault();document.getElementById('logout-form').submit();"
     class="dropdown-item" href="javascript:void(0)">خروج</a>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
  </form>

</div>
</div>
</div>
</div>
</div>
<div class="side-content-wrap dir-rtl">
<div class="sidebar-left rtl-ps-none d-sm-block ps ps--active-y open" data-perfect-scrollbar=""
data-suppress-scroll-x="true">
<ul class="navigation-left">

@if (Auth::user()->role_id == 7)
<li class="nav-item">
  <a class="nav-item-hold" href="{{url('panel/devices')}}">
    <i class="nav-icon i-Bar-Chart"></i>
    <span class="nav-text">لیست دستگاه ها
                   ({{$device_count}})
                  </span>
  </a>
  <div class="triangle"></div>
</li>
<li class="nav-item">
  <a class="nav-item-hold" href="{{url('panel/archived-devices')}}">
    <i class="nav-icon i-Computer-2"></i>
    <span class="nav-text">بایگانی شده ها</span>
  </a>
  <div class="triangle"></div>
</li>
@endif
@if (Auth::user()->role_id == 3)
<li class="nav-item">
  <a class="nav-item-hold" href="{{url('panel/financial-devices')}}">
    <i class="nav-icon i-Bar-Chart"></i>
    <span class="nav-text">کارتابل مالی
                  ({{$financial_device_count}})
              </span>
  </a>
  <div class="triangle"></div>
</li>
@endif
@if (Auth::user()->role_id == 2)
<li class="nav-item">
  <a class="nav-item-hold" href="{{url('panel/technical-devices')}}">
    <i class="nav-icon i-Bar-Chart"></i>
    <span class="nav-text">بخش فنی
                      ({{$technical_device_count}})
                  </span>
  </a>
  <div class="triangle"></div>
</li>
@endif
@if (Auth::user()->role_id == 5)
<li class="nav-item">
  <a class="nav-item-hold" href="{{url('dashbord')}}">
    <i class="nav-icon i-Bar-Chart"></i>
    <span class="nav-text">داشبورد</span>
  </a>
  <div class="triangle"></div>
</li>
<li class="nav-item" data-item="tickets">
  <a class="nav-item-hold" href="javascript:void(0)">
    <i class="nav-icon i-Mail"></i>
    <span class="nav-text">تیکت ها</span>
  </a>
  <div class="triangle"></div>
</li>
<li class="nav-item">
  <a class="nav-item-hold" href="{{url('dashbord/project')}}">
    <i class="nav-icon i-Receipt-4"></i>
    <span class="nav-text">پروژه های من</span>
  </a>
  <div class="triangle"></div>
</li>
<li class="nav-item">
  <a class="nav-item-hold" href="{{url('dashbord/domain')}}">
    <i class="nav-icon i-Internet-2"></i>
    <span class="nav-text">دامنه های من</span>
  </a>
  <div class="triangle"></div>
</li>
<li class="nav-item">
  <a class="nav-item-hold" href="{{url('dashbord/host')}}">
    <i class="nav-icon i-Data-Center"></i>
    <span class="nav-text">هاست های من</span>
  </a>
  <div class="triangle"></div>
</li>
<li class="nav-item">
  <a class="nav-item-hold" href="{{url('dashbord/invoices')}}">
    <i class="nav-icon i-Testimonal"></i>
    <span class="nav-text">فاکتور های من</span>
  </a>
  <div class="triangle"></div>
</li>
@else
<li class="nav-item">
  <a class="nav-item-hold" href="{{url('panel')}}">
    <i class="nav-icon i-Bar-Chart"></i>
    <span class="nav-text">داشبورد</span>
  </a>
  <div class="triangle"></div>
</li>
<li class="nav-item" data-item="tickets">
  <a class="nav-item-hold" href="javascript:void(0)">
    <i class="nav-icon i-Mail"></i>
    <span class="nav-text">تیکت ها</span>
  </a>
  <div class="triangle"></div>
</li>
@if(Auth::user()->role_id != 4 && Auth::user()->role_id != 6)
  <li class="nav-item" data-item="jobs">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-Bell1"></i>
      <span class="nav-text">جاب ها</span>
    </a>
    <div class="triangle"></div>
  </li>
@endif
@if (Auth::user()->role_id == 1)
  <li class="nav-item" data-item="phases">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-Check"></i>
      <span class="nav-text">فاز ها</span>
    </a>
    <div class="triangle"></div>
  </li>
@endif
@if(Auth::user()->role_id != 4 && Auth::user()->role_id != 6)
  <li class="nav-item" data-item="hardware">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-Computer-2"></i>
      <span class="nav-text">سخت افزار</span>
    </a>
    <div class="triangle"></div>
  </li>
  <li class="nav-item" data-item="software">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-Monitor-3"></i>
      <span class="nav-text">نرم افزار</span>
    </a>
    <div class="triangle"></div>
  </li>

  <li class="nav-item" data-item="directorial">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-Building"></i>
      <span class="nav-text">اداری
                  </span>
    </a>
    <div class="triangle"></div>
  </li>
  <li class="nav-item" data-item="settings">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-Gear"></i>
      <span class="nav-text">تنظیمات</span>
    </a>
    <div class="triangle"></div>
  </li>
@endif
@if(Auth::user()->role_id != 6)
  <li class="nav-item" data-item="reports">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-Notepad"></i>
      <span class="nav-text">گزارشات</span>
    </a>
    <div class="triangle"></div>
  </li>
@endif
@if(Auth::user()->role_id != 4 && Auth::user()->role_id != 6)
  <li class="nav-item" data-item="keywords">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-Tag-5"></i>
      <span class="nav-text">کلمات کلیدی</span>
    </a>
    <div class="triangle"></div>
  </li>
@endif
@if (Auth::user()->role_id == 7)
  <li class="nav-item" data-item="companies">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-Len-2"></i>
      <span class="nav-text">شرکت ها</span>
    </a>
    <div class="triangle"></div>
  </li>
  <li class="nav-item" data-item="technicalForms">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-File-Horizontal-Text"></i>
      <span class="nav-text">فرم های فنی</span>
    </a>
    <div class="triangle"></div>
  </li>
@endif

@if (Auth::user()->role_id == 1 || Auth::user()->role_id == 9 || Auth::user()->id == 10000099)
  <li class="nav-item" data-item="leaves_ok">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-File-Edit"></i>
      <span class="nav-text">مرخصی
                      @if(auth()->user()->role_id==1 || Auth::user()->id == 10000099)
          @php
            $edari_count=0;
            $leave_count=\App\Models\Leave::where('status','0')->count();
            $edari_count+=$leave_count;
          @endphp
          @if($edari_count>0)
            <span style="background: darkred;display: inline-block;width: 22px;height: 22px;border-radius: 50%;text-align: center;line-height: 22px;color: #fff">{{ $edari_count }}</span>
          @endif
        @elseif(Auth::user()->role_id == 9)
          @php
            $edari_count=0;
            $leave_count=\App\Models\Leave::whereIN('role__id',[2])->where('status','0')->count();
            $edari_count+=$leave_count;
          @endphp
          @if($edari_count>0)
            <span style="background: darkred;display: inline-block;width: 22px;height: 22px;border-radius: 50%;text-align: center;line-height: 22px;color: #fff">{{ $edari_count }}</span>
          @endif
        @endif
                      </span>
    </a>
    <div class="triangle"></div>
  </li>
@endif
  @if (Auth::user()->role_id == 1)
    <li class="nav-item" data-item="time_form">
      <a class="nav-item-hold" href="javascript:void(0)">
        <i class="nav-icon i-Clock"></i>
        <span class="nav-text">ساعت ورود/خروج

            @php
              $time_count=\App\Models\TimeForm::where('status','pending')->count();
            @endphp
            @if($time_count>0)
              <span style="background: darkred;display: inline-block;width: 22px;height: 22px;border-radius: 50%;text-align: center;line-height: 22px;color: #fff">{{ $time_count }}</span>
            @endif
                      </span>
      </a>
      <div class="triangle"></div>
    </li>
  @endif
  @if (Auth::user()->role_id == 1)
    <li class="nav-item">
      <a class="nav-item-hold" href="{{route('help-index')}}">
        <i class="nav-icon i-Dollar"></i>
        <span class="nav-text">مساعده

            @php
              $time_count=\App\Models\Help::where('status',0)->count();
            @endphp
            @if($time_count>0)
              <span style="background: darkred;display: inline-block;width: 22px;height: 22px;border-radius: 50%;text-align: center;line-height: 22px;color: #fff">{{ $time_count }}</span>
            @endif
                      </span>
      </a>
      <div class="triangle"></div>
    </li>
  @endif
@if(Auth::user()->role_id!=5 && Auth::user()->role_id != 4 && Auth::user()->role_id != 6)
  <li class="nav-item" data-item="pages">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-URL-Window"></i>
      <span class="nav-text">صفحات</span>
    </a>
    <div class="triangle"></div>
  </li>
@endif
@if (Auth::user()->role_id == 1  || Auth::user()->role_id == 3 || Auth::user()->id == 126)
  <li class="nav-item" data-item="companies">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-Len-2"></i>
      <span class="nav-text">شرکت ها</span>
    </a>
    <div class="triangle"></div>
  </li>
@endif
@if (Auth::user()->role_id == 3)
  <li class="nav-item" data-item="factors">
    <a class="nav-item-hold" href="{{url('panel/invoices')}}">
      <i class="nav-icon i-Testimonal"></i>
      <span class="nav-text">فاکتور ها</span>
    </a>
    <div class="triangle"></div>
  </li>
@endif
@if (Auth::user()->role_id == 1)
  <li class="nav-item">
    <a class="nav-item-hold" href="{{url('panel/reports_company')}}">
      <i class="nav-icon i-Bar-Chart"></i>
      <span class="nav-text">ساعت کاری شرکت ها</span>
    </a>
    <div class="triangle"></div>
  </li>
@endif
@if (Auth::user()->role_id == 8 || Auth::user()->id==133)
  <li class="nav-item" data-item="companies">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-Len-2"></i>
      <span class="nav-text">شرکت ها</span>
    </a>
    <div class="triangle"></div>
  </li>
  <li class="nav-item" data-item="drafts">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-File-Clipboard-File--Text"></i>
      <span class="nav-text">پیشنویس ها</span>
    </a>
    <div class="triangle"></div>
  </li>
@endif
@if (Auth::user()->role_id == 6 ||Auth::user()->role_id == 8 || Auth::user()->role_id == 1)
  <li class="nav-item">
    <a class="nav-item-hold" href="{{url('panel/new_company')}}">
      <i class="nav-icon i-Bar-Chart"></i>
      <span class="nav-text">شرکت های من</span>
    </a>
    <div class="triangle"></div>
  </li>
@endif
@if(Auth::user()->draft_permission==1)
  <li class="nav-item" data-item="drafts">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-File-Clipboard-File--Text"></i>
      <span class="nav-text">پیشنویس ها</span>
    </a>
    <div class="triangle"></div>
  </li>

@endif
  @if(Auth::user()->role_id!=5)
  <li class="nav-item" data-item="to_do_list">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-Clock-Forward"></i>
      <span class="nav-text">فعالیت ها</span>
    </a>
    <div class="triangle"></div>
  </li>
  @endif
  @if(Auth::user()->id==106)
  <li class="nav-item" data-item="work_price">
    <a class="nav-item-hold" href="javascript:void(0)">
      <i class="nav-icon i-Dollar"></i>
      <span class="nav-text">دستمزد</span>
    </a>
    <div class="triangle"></div>
  </li>
  @endif
<li class="nav-item">
  <a class="nav-item-hold" href="{{url('panel/works')}}">
    <i class="nav-icon i-Mail-3"></i>
    <span class="nav-text">کار های من</span>
  </a>
  <div class="triangle"></div>
</li>
@endif
{{--                <li class="nav-item" data-item="dashboard"><a class="nav-item-hold" href="#"><i class="nav-icon i-Bar-Chart"></i><span class="nav-text">داشبورد</span></a>--}}
{{--                    <div class="triangle"></div>--}}
{{--                </li>--}}
</ul>
</div>
<div class="sidebar-left-secondary rtl-ps-none ps" data-perfect-scrollbar="" data-suppress-scroll-x="true">
<!-- Submenu Dashboards-->
@if (Auth::user()->role_id == 5)
<ul class="childNav" data-parent="tickets" style="display: none;">
<li class="nav-item"><a href="{{url('dashbord/ticket')}}"><i class="nav-icon i-Mail"></i><span
            class="item-name">لیست تیکت ها</span></a></li>
<li class="nav-item"><a href="{{url('dashbord/ticket/create')}}"><i
            class="nav-icon i-Mail"></i><span class="item-name">ثبت تیکت جدید</span></a></li>
</ul>
@else
@if(Auth::user()->role_id != 5)
<ul class="childNav" data-parent="tickets" style="display: none;">
  <li class="nav-item">
    <a href="{{url('panel/ticket')}}">
      <i class="nav-icon i-Mail"></i>
      <span class="item-name">لیست تیکت ها
                          @if(Auth::user()->role_id != 6 and Auth::user()->id != 145 and Auth::user()->id != 144 and Auth::user()->id != 143 and Auth::user()->role_id != 7)
          @if(Auth::user()->role_id == 1)
            @if($panel_tickets_count_admin!=0)
              <span class="badge-count">{{$panel_tickets_count_admin}}</span>
            @endif
          @else
            @if($panel_tickets_count!=0)
              <span class="badge-count">{{$panel_tickets_count}}</span>
            @endif
          @endif
        @endif
                      </span>
    </a>
  </li>
  @if(Auth::user()->id != 145 and Auth::user()->id != 144 and Auth::user()->id != 143 and Auth::user()->role_id != 7 and Auth::user()->role_id != 6)
    <li class="nav-item"><a href="{{url('panel/ticket-answered')}}"><i
                class="nav-icon i-Mail"></i><span class="item-name">تیکت های پاسخ داده شده</span></a>
    </li>
    <li class="nav-item"><a href="{{url('panel/old_ticket')}}"><i
                class="nav-icon i-Mail"></i><span class="item-name">تیکت های بسته شده</span></a>
    </li>
    <li class="nav-item"><a href="{{route('auto_closed')}}"><i class="nav-icon i-Mail"></i><span
                class="item-name">Auto Closed 48h</span></a></li>
  @endif
  <li class="nav-item"><a href="{{url('panel/ticket/create')}}"><i
              class="nav-icon i-Mail"></i><span class="item-name">تیکت جدید</span></a></li>
  @if(Auth::user()->role_id ==3 ||Auth::user()->role_id ==6 ||Auth::user()->role_id ==8 ||Auth::user()->role_id ==7 ||Auth::user()->role_id ==9 || Auth::user()->role_id ==1)
    <li class="nav-item"><a href="{{url('panel/ticket/create/customer')}}"><i
                class="nav-icon i-Mail"></i><span class="item-name">تیکت جدید(از طرف شرکتها)</span></a></li>
  @endif
</ul>
<ul class="childNav" data-parent="jobs" style="display: none;">
  <li class="nav-item">
    <a href="{{url('panel/jobs-index')}}">
      <i class="nav-icon i-Bell1"></i>
      <span class="item-name">جاب های من
                          <span class="badge-count">{{$panel_jobs_count}}</span>
                      </span>
    </a>
  </li>
  <li class="nav-item"><a href="{{url('panel/jobs-done-index')}}"><i class="nav-icon i-Bell1"></i><span
              class="item-name">انجام شده ها</span></a></li>
  <li class="nav-item"><a href="{{url('panel/all-jobs-index')}}"><i
              class="nav-icon i-Bell1"></i><span class="item-name">همه جاب ها</span></a></li>
</ul>
@if (Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
  <ul class="childNav" data-parent="phases" style="display: none;">
    <li class="nav-item">
      <a href="{{url('panel/user_phase')}}">
        <i class="nav-icon i-Check"></i>
        <span class="item-name">لیست فاز ها
                              @if(Auth::user()->role_id == 1)
            @if($panel_tickets_count_admin!=0)
              <span class="badge-count">{{$panel_tickets_count_admin}}</span>
            @endif
          @else
            @if($panel_tickets_count!=0)
              <span class="badge-count">{{$panel_tickets_count}}</span>
            @endif
          @endif
                      </span>
      </a>
    </li>
    <li class="nav-item"><a href="{{url('panel/packages')}}"><i
                class="nav-icon i-Check"></i><span class="item-name">پکیج ها</span></a></li>
    <li class="nav-item"><a href="{{url('panel/phase_completed_interface')}}"><i
                class="nav-icon i-Check"></i><span
                class="item-name">100% Completed</span></a></li>
    <li class="nav-item"><a href="{{url('panel/phase_create')}}"><i
                class="nav-icon i-Check"></i><span class="item-name">ثبت فاز</span></a></li>
  </ul>
@endif
@endif
<ul class="childNav" data-parent="hardware" style="display: none;">
<li class="nav-item">
  <a href="{{url('panel/devices')}}">
    <i class="nav-icon i-Computer-2"></i>
    <span class="item-name">لیست دستگاه ها
                      <span class="badge-count">{{$device_count}}</span>
                  </span>
  </a>
</li>
<li class="nav-item">
  <a href="{{url('panel/technical-devices')}}">
    <i class="nav-icon i-Computer-2"></i>
    <span class="item-name">بخش فنی
                      <span class="badge-count">{{$technical_device_count}}</span>
                  </span>
  </a>
</li>
<li class="nav-item"><a href="{{url('panel/archived-devices')}}"><i
            class="nav-icon i-Computer-2"></i><span class="item-name">لیست بایگانی شده ها</span></a>
</li>
</ul>
<ul class="childNav" data-parent="software" style="display: none;">
<li class="nav-item"><a href="{{url('panel/projects', 'همه')}}"><i class="nav-icon i-Monitor-3"></i><span
            class="item-name">پروژه ها</span></a></li>
{{--                    <li class="nav-item"><a href="{{url('panel/projects', 'سایت')}}"><i class="nav-icon i-Monitor-3"></i><span class="item-name">پروژه سایت</span></a></li>--}}
{{--                    <li class="nav-item"><a href="{{url('panel/projects', 'سئو')}}"><i class="nav-icon i-Monitor-3"></i><span class="item-name">پروژه سئو</span></a></li>--}}
{{--                    <li class="nav-item"><a href="{{url('panel/projects', 'اینستاگرام')}}"><i class="nav-icon i-Monitor-3"></i><span class="item-name">پروژه اینستاگرام</span></a></li>--}}
{{--                    <li class="nav-item"><a href="{{url('panel/project_done')}}"><i class="nav-icon i-Monitor-3"></i><span class="item-name">پروژه های پایان یافته</span></a></li>--}}
{{--                    <li class="nav-item"><a href="{{url('panel/project_cancel')}}"><i class="nav-icon i-Monitor-3"></i><span class="item-name">پروژه های کنسل شده</span></a></li>--}}
<li class="nav-item"><a href="{{url('panel/project/create')}}"><i
            class="nav-icon i-Monitor-3"></i><span class="item-name">ثبت پروژه</span></a></li>
{{--                    <li class="nav-item"><a href="{{url('panel/domain')}}"><i class="nav-icon i-Monitor-3"></i><span class="item-name">لیست دامنه ها </span></a></li>--}}
{{--                    <li class="nav-item"><a href="{{url('panel/d_active_domain')}}"><i class="nav-icon i-Monitor-3"></i><span class="item-name">لیست دامنه های غیر فعال</span></a></li>--}}
{{--                    <li class="nav-item"><a href="{{url('panel/c_active_domain')}}"><i class="nav-icon i-Monitor-3"></i><span class="item-name">لیست دامنه های لغو شده</span></a></li>--}}
<li class="nav-item"><a href="{{url('panel/host')}}"><i class="nav-icon i-Monitor-3"></i><span
            class="item-name">لیست هاست ها</span></a></li>
{{--                    <li class="nav-item"><a href="{{url('panel/deactivated-host')}}"><i class="nav-icon i-Monitor-3"></i><span class="item-name">هاست های غیرفعال</span></a></li>--}}
{{--                    <li class="nav-item"><a href="{{url('panel/canceled-host')}}"><i class="nav-icon i-Monitor-3"></i><span class="item-name">هاست های لغو شده</span></a></li>--}}
<li class="nav-item"><a href="{{url('panel/host/create')}}"><i
            class="nav-icon i-Monitor-3"></i><span class="item-name">ثبت هاست</span></a></li>
<li class="nav-item"><a href="{{url('panel/domain/create')}}"><i
            class="nav-icon i-Monitor-3"></i><span class="item-name">ثبت دامنه</span></a></li>
</ul>
<ul class="childNav" data-parent="directorial" style="display: none;">
<li class="nav-item"><a href="{{url('panel/visits')}}"><i class="nav-icon i-Building"></i><span
            class="item-name">بازدید ها</span></a></li>
<li class="nav-item"><a href="{{url('panel/visits')}}/1"><i class="nav-icon i-Building"></i><span
            class="item-name">ادواری</span></a></li>
<li class="nav-item"><a href="{{url('panel/visits')}}/2"><i class="nav-icon i-Building"></i><span
            class="item-name">اورژانسی</span></a></li>
{{--                    @if (Auth::user()->role_id == 1 || Auth::user()->id == 1)--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{url('panel/leaves_admin')}}">--}}
{{--                                <i class="nav-icon i-Building"></i>--}}
{{--                                <span class="item-name">درخواست های مرخصی--}}
{{--                                    @if(auth()->user()->role_id==1)--}}
{{--                                        @if($edari_count>0)--}}
{{--                                            <span class="badge-count">{{ $edari_count }}</span>--}}
{{--                                        @endif--}}
{{--                                    @endif--}}
{{--                                </span>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    @endif--}}
<li class="nav-item"><a href="{{url('panel/traffic_in')}}"><i class="nav-icon i-Building"></i><span
            class="item-name">ورودی ها</span></a></li>
<li class="nav-item"><a href="{{url('panel/traffic_out')}}"><i class="nav-icon i-Building"></i><span
            class="item-name">خروجی ها</span></a></li>
<li class="nav-item">
  <a href="{{url('panel/contract')}}">
    <i class="nav-icon i-Building"></i>
    <span class="item-name">لیست قرارداد ها
                      @if(Auth::user()->id==111 || Auth::user()->role_id==1)
        @if($drafts_no_status>0)
          <span class="badge-count">{{ $drafts_no_status }}</span>
        @endif
      @endif
      @if(Auth::user()->role_id==3 || Auth::user()->role_id==1)
        @if($drafts_yes_status>0)
          <span class="badge-count">{{ $drafts_yes_status }}</span>
        @endif
      @endif
                  </span>
  </a>
</li>
<li class="nav-item"><a href="{{url('panel/contractDraft')}}"><i
            class="nav-icon i-Building"></i><span class="item-name">پیش نویس قرارداد</span></a>
</li>
<li class="nav-item"><a href="{{url('panel/activated-contract')}}"><i
            class="nav-icon i-Building"></i><span class="item-name">قرارداد های فعال</span></a>
</li>
<li class="nav-item"><a href="{{url('panel/deactivated-contract')}}"><i
            class="nav-icon i-Building"></i><span class="item-name">قرارداد های غیر فعال</span></a>
</li>
<li class="nav-item"><a href="{{url('panel/contract/create')}}"><i
            class="nav-icon i-Building"></i><span class="item-name">ثبت قرارداد</span></a></li>
<li class="nav-item">
  <a href="{{url('panel/letters')}}">
    <i class="nav-icon i-Building"></i>
    <span class="item-name">لیست نامه ها
                      @if(Auth::user()->id==111 || Auth::user()->role_id==1)
        @if($drafts_no_status>0)
          <span class="badge-count">{{ $drafts_no_status }}</span>
        @endif
      @endif
      @if(Auth::user()->role_id==3 || Auth::user()->role_id==1)
        @if($drafts_yes_status>0)
          <span class="badge-count">{{ $drafts_yes_status }}</span>
        @endif
      @endif
                  </span>
  </a>
</li>
<li class="nav-item"><a href="{{url('panel/letter-create')}}"><i
            class="nav-icon i-Building"></i><span class="item-name">نامه جدید</span></a></li>
<li class="nav-item"><a href="{{url('panel/letter-templates')}}"><i class="nav-icon i-Building"></i><span
            class="item-name">قالب ها</span></a></li>
</ul>
<ul class="childNav" data-parent="settings" style="display: none;">
@if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || auth()->id() == 99999)
  <li class="nav-item"><a href="{{url('panel/users')}}"><i class="nav-icon i-Gear"></i><span
              class="item-name">کاربران</span></a></li>
@endif
@if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 8)
  <li class="nav-item"><a href="{{url('panel/host/space')}}"><i class="nav-icon i-Gear"></i><span
              class="item-name">مدیریت فضا هاست</span></a></li>
  <li class="nav-item"><a href="{{url('panel/host/extinction')}}"><i
              class="nav-icon i-Gear"></i><span
              class="item-name">مدیریت پسوند دامنه هاست</span></a></li>
@endif
{{--                    <li class="nav-item"><a href="{{url('panel/cmn_7')}}"><i class="nav-icon i-Gear"></i><span--}}
{{--                                    class="item-name">مشتریان فعال</span></a></li>--}}
<li class="nav-item"><a href="{{url('panel/cmn_1')}}"><i class="nav-icon i-Gear"></i><span
            class="item-name">مشتریان ثبت شده</span></a></li>
{{--                    <li class="nav-item"><a href="{{url('panel/cmn_2')}}"><i class="nav-icon i-Gear"></i><span--}}
{{--                                    class="item-name">مشتریان نیاز به مذاکره</span></a></li>--}}
{{--                    <li class="nav-item"><a href="{{url('panel/cmn_3')}}"><i class="nav-icon i-Gear"></i><span--}}
{{--                                    class="item-name">مشتریان بعدا تماس بگیرید</span></a></li>--}}
{{--                    <li class="nav-item"><a href="{{url('panel/cmn_4')}}"><i class="nav-icon i-Gear"></i><span--}}
{{--                                    class="item-name">مشتریان درخواست جلسه حضوری</span></a></li>--}}
<li class="nav-item"><a href="{{url('panel/cmn_5')}}"><i class="nav-icon i-Gear"></i><span
            class="item-name">مشتریان نیاز به پیش فاکتور(قرار داد)</span></a></li>
<li class="nav-item"><a href="{{url('panel/cmn_6')}}"><i class="nav-icon i-Gear"></i><span
            class="item-name">مشتریان قرار داد</span></a></li>
</ul>
<ul class="childNav" data-parent="reports" style="display: none;">
@if(Auth::user()->role_id != 4)
  <li class="nav-item"><a href="{{url('panel/reports')}}"><i class="nav-icon i-Notepad"></i><span
              class="item-name">لیست گزارشات</span></a></li>
  <li class="nav-item"><a href="{{url('panel/showInvoices')}}"><i class="nav-icon i-Notepad"></i><span
              class="item-name">پیش فاکتور ها</span></a></li>
  <li class="nav-item"><a href="{{url('panel/companies')}}"><i class="nav-icon i-Notepad"></i><span
              class="item-name">شرکت ها</span></a></li>
@endif
<li class="nav-item"><a href="{{url('panel/workhour-index')}}"><i
            class="nav-icon i-Notepad"></i><span class="item-name">ساعت کاری ها</span></a></li>
</ul>
<ul class="childNav" data-parent="keywords" style="display: none;">
<li class="nav-item"><a href="{{url('panel/keyList')}}"><i class="nav-icon i-Tag-5"></i><span
            class="item-name">کلمات کلیدی</span></a></li>
</ul>
@if (Auth::user()->role_id == 7 || Auth::user()->role_id == 6 || Auth::user()->id==126)
<ul class="childNav" data-parent="companies" style="display: none;">
  <li class="nav-item"><a href="{{url('panel/company')}}"><i class="nav-icon i-Len-2"></i><span
              class="item-name">لیست شرکت ها</span></a></li>
  @if (Auth::user()->role_id == 7 || Auth::user()->role_id == 6)
    <li class="nav-item"><a href="{{url('panel/company/create')}}"><i
                class="nav-icon i-Len-2"></i><span class="item-name">ثبت شرکت</span></a></li>
  @endif
</ul>
@endif
@if (Auth::user()->role_id == 7 || Auth::user()->role_id == 6)
<ul class="childNav" data-parent="technicalForms" style="display: none;">
  <li class="nav-item"><a href="{{url('panel/deliveryFormOfGoods')}}"><i
              class="nav-icon i-File-Horizontal-Text"></i><span class="item-name">فرم تحویل کالا</span></a>
  </li>
</ul>
@endif
@if (Auth::user()->role_id != 5)
<ul class="childNav" data-parent="pages" style="display: none;">
  <li class="nav-item"><a href="https://adib-it.com/"><i class="nav-icon i-URL-Window"></i><span
              class="item-name">صفحه اصلی</span></a></li>
  <li class="nav-item"><a href="https://adibhost.com/support/"><i
              class="nav-icon i-URL-Window"></i><span
              class="item-name">ناحیه کاربری</span></a></li>
  <li class="nav-item"><a href="https://adib-it.com/درباره-ما/"><i
              class="nav-icon i-URL-Window"></i><span class="item-name">درباره ما</span></a>
  </li>
  <li class="nav-item"><a href="https://adib-it.com/تماس-با-ما/"><i
              class="nav-icon i-URL-Window"></i><span class="item-name">تماس با ما</span></a>
  </li>
</ul>
@endif
@if (Auth::user()->role_id == 1 ||Auth::user()->role_id == 9 ||Auth::user()->id == 10000099)
<ul class="childNav" data-parent="leaves_ok" style="display: none;">
  <li class="nav-item"><a href="{{url('panel/leaves_ok')}}"><i class="nav-icon i-File-Edit"></i><span
              class="item-name">مرخصی های تایید شده</span></a></li>
  <li class="nav-item">
    <a href="{{url('panel/leaves_admin')}}">
      <i class="nav-icon i-Building"></i>
      <span class="item-name">درخواست های مرخصی
                          @if(auth()->user()->role_id==1 || auth()->user()->role_id==9 ||Auth::user()->id == 10000099)
          @if($edari_count>0)
            <span class="badge-count">{{ $edari_count }}</span>
          @endif
        @endif
                      </span>
    </a>
  </li>
</ul>
@endif
@if (Auth::user()->role_id == 1)
  <ul class="childNav" data-parent="time_form" style="display: none;">
    <li class="nav-item">
      <a href="{{route('time-login-index','pending')}}">
        <i class="nav-icon i-Clock"></i>
        <span class="item-name">درخواست ها
            @if($time_count>0)
              <span class="badge-count">{{ $time_count }}</span>
            @endif
                      </span>
      </a>
    </li>
    <li class="nav-item"><a href="{{route('time-login-index','active')}}"><i class="nav-icon i-Clock"></i><span
                class="item-name">تایید شده</span></a></li>
    <li class="nav-item"><a href="{{route('time-login-index','cancel')}}"><i class="nav-icon i-Clock"></i><span
                class="item-name">کنسل شده</span></a></li>
    <li class="nav-item"><a href="{{route('time-login-index','all')}}"><i class="nav-icon i-Clock"></i><span
                class="item-name">همه</span></a></li>

  </ul>
@endif
@if (Auth::user()->role_id == 1 || Auth::user()->role_id == 3 || Auth::user()->role_id == 8)
<ul class="childNav" data-parent="companies" style="display: none;">
  <li class="nav-item"><a href="{{url('panel/company')}}"><i class="nav-icon i-Len-2"></i><span
              class="item-name">لیست شرکت ها</span></a></li>
  <li class="nav-item"><a href="{{url('panel/company/create')}}"><i
              class="nav-icon i-Len-2"></i><span class="item-name">ثبت شرکت</span></a></li>
</ul>
@endif
{{--        @if (Auth::user()->role_id == 6)--}}
{{--          <ul class="childNav" data-parent="companies" style="display: none;">--}}
{{--            <li class="nav-item"><a href="{{url('panel/company')}}"><i class="nav-icon i-Len-2"></i><span--}}
{{--                        class="item-name">لیست شرکت ها</span></a></li>--}}
{{--          </ul>--}}
{{--          <ul class="childNav" data-parent="drafts" style="display: none;">--}}
{{--            <li class="nav-item"><a href="{{url('panel/addDraft')}}"><i class="nav-icon i-Len-2"></i><span--}}
{{--                        class="item-name">افزودن پیشنویس قرارداد</span></a></li>--}}
{{--            <li class="nav-item"><a href="{{url('panel/contractDraftOk')}}"><i class="nav-icon i-Len-2"></i><span--}}
{{--                        class="item-name">پیش نویس های تایید شده</span></a></li>--}}
{{--          </ul>--}}
{{--        @endif--}}
@if(Auth::user()->draft_permission==1)
<ul class="childNav" data-parent="drafts" style="display: none;">
  <li class="nav-item"><a href="{{url('panel/addDraft')}}"><i class="nav-icon i-Len-2"></i><span
              class="item-name">افزودن پیشنویس قرارداد</span></a></li>
  <li class="nav-item"><a href="{{url('panel/contractDraftOk')}}"><i class="nav-icon i-Len-2"></i><span
              class="item-name">پیش نویس های تایید شده</span></a></li>
</ul>
@endif
@if(Auth::user()->role_id!=5)
  <ul class="childNav" data-parent="to_do_list" style="display: none;">
    @if(Auth::user()->role_id==1 || Auth::user()->role_id==8 || Auth::user()->id==106)
    <li class="nav-item"><a href="{{route('todo-list-ref.index')}}"><i class="nav-icon i-Network-Window"></i><span
                class="item-name">گروه بندی (فعالیت های گروهی)</span></a></li>
    <li class="nav-item"><a href="{{route('todo-list-category.index')}}"><i class="nav-icon i-Folder-Network"></i><span
                class="item-name">دسته بندی</span></a></li>
    @endif
    <li class="nav-item"><a href="{{route('todo-list.index')}}"><i class="nav-icon i-Clock-Forward"></i><span
                class="item-name">فعالیت</span></a></li>
  </ul>
@endif
@if(Auth::user()->id==106)
  <ul class="childNav" data-parent="work_price" style="display: none;">
    <li class="nav-item"><a href="{{route('work-price-hour.index')}}"><i class="nav-icon i-Dollar-Sign"></i><span
                class="item-name">دستمزد ساعت دورکار</span></a></li>
  </ul>
@endif
@endif
<div class="ps__rail-x" style="left: 0px; bottom: 0px;">
<div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
</div>
<div class="ps__rail-y" style="top: 0px; right: 214px;">
<div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
</div>
</div>
<div class="sidebar-overlay open"></div>
</div>
<div id="sidenav_div" class="main-content-wrap sidenav-open d-flex flex-column w-100">
<div class="container" style="direction: rtl">

<div class="breadcrumb">
<h1 class="mr-2">پیشخوان</h1>
@isset($title)
<ul>
  <li><a href="">{{isset($title)?$title:''}}</a></li>
  {{--<li>Version 1</li>--}}
</ul>
@endisset
</div>

<div class="separator-breadcrumb border-top"></div>

@if(auth()->user()->role_id!=5)
<div class="row">
<!-- ICON BG-->
<div class="col-lg-3 col-md-6 col-sm-6">
  <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
    <div class="card-body text-center"><i class="i-Add-User"></i>
      <div class="content">
        <p class="text-muted mt-2 mb-0 mb-2">کاربران</p>
        <p class="text-primary text-24 line-height-1 mb-2">{{$allUsers}}</p>
      </div>
    </div>
  </div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6">
  <a href="{{ auth()->user()->role_id==1?route('company.active.list'):route('agreement.index') }}"  class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
    <div class="card-body text-center"><i class="i-Financial"></i>
      <div class="content">
        @if(auth()->user()->role_id==5)
        <p class="text-muted mt-2 mb-0 mb-2">قراردادهای فعال</p>
        {{-- <p class="text-primary text-24 line-height-1 mb-2">{{$activated_contracts}}</p> --}}
        <p class="text-primary text-24 line-height-1 mb-2">{{auth()->user()->company()->count()}}</p>
        @else
          <p class="text-muted mt-2 mb-0 mb-2">شرکت های فعال</p>
          <p class="text-primary text-24 line-height-1 mb-2">{{$company_active}}</p>
        @endif
      </div>
    </div>
  </a>
</div>
<div class="col-lg-3 col-md-6 col-sm-6">
  <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
    <div class="card-body text-center"><i class="i-Mail"></i>
      <div class="content">
        <p class="text-muted mt-2 mb-0 mb-2">تیکت های من</p>
        <p class="text-primary text-24 line-height-1 mb-2">{{$my_tickets_count}}</p>
      </div>
    </div>
  </div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6">
  <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
    <div class="card-body text-center"><i class="i-Bell1"></i>
      <div class="content">
        <p class="text-muted mt-2 mb-0 mb-2">جاب های فعال</p>
        <p class="text-primary text-24 line-height-1 mb-2">{{$panel_jobs_count}}</p>
      </div>
    </div>
  </div>
</div>
</div>
@endif

@if(Auth::user()->role_id==5)
<div class="pull-right w-100 hidden-mob" style="margin-right: -15px;">
<div class="container">
  <div class="row">
    <div class="fcon d-inline-block mb-4">
      <div class="col-md-2 pull-right">
        <a href="{{ url('dashbord') }}">
          <div class="prosda animated bounceIn">
            <img src="{{ asset('img/dashboard.png') }}" alt="">
            <h5 class="title">داشبورد</h5>
          </div>
        </a>
      </div>
      <div class="col-md-2 pull-right">
        <a href="{{ url('dashbord/ticket') }}">
          <div class="prosda animated bounceIn">
            <img src="{{ asset('img/comment.png') }}" alt="">
            <h5 class="title">تیکت های من
              @php $ticket_count = \App\Models\Ticket::where(['user__id' => auth()->id(), 'ticket__type' => 'services','ticket__status'=>'answered'])->count(); @endphp
              <span class="badge badge-pill badge-warning">{{$ticket_count}}</span>
            </h5>
          </div>
        </a>
      </div>
      <div class="col-md-2 pull-right">
        <a href="{{ url('dashbord/project') }}">
          <div class="prosda animated bounceIn">
            <img src="{{ asset('img/project.png') }}" alt="">
            <h5 class="title">پروژه های من
              @php $project_count = \App\Models\Project::where('user__id', auth()->id())->count(); @endphp
              <span class="badge badge-pill badge-warning">{{$project_count}}</span>
            </h5>
          </div>
        </a>
      </div>
      <div class="col-md-2 pull-right">
        <a href="{{ url('dashbord/domain') }}">
          <div class="prosda animated bounceIn">
            <img src="{{ asset('img/domain.png') }}" alt="">
            <h5 class="title">دامنه های من
              @php $domain_count = \App\Models\Domain::where(['user__id' => auth()->id()])->count(); @endphp
              <span class="badge badge-pill badge-warning">{{$domain_count}}</span>
            </h5>
          </div>
        </a>
      </div>
      <div class="col-md-2 pull-right">
        <a href="{{ url('dashbord/domain') }}">
          <div class="prosda animated bounceIn">
            <img src="{{ asset('img/host.png') }}" alt="">
            <h5 class="title">هاست های من
              @php $host_count = \App\Models\Host::where(['user__id' => auth()->id()])->count(); @endphp
              <span class="badge badge-pill badge-warning">{{$host_count}}</span>
            </h5>
          </div>
        </a>
      </div>
      <div class="col-md-2 pull-right">
        <a href="{{ url('dashbord/invoices') }}">
          <div class="prosda animated bounceIn">
            <img src="{{ asset('img/invoice.png') }}" alt="">
            <h5 class="title">فاکتور های من
              @php $invoice_count = \App\Models\Ticket::where(['user__id' => auth()->id(), 'ticket__type' => 'invoices'])->count(); @endphp
              <span class="badge badge-pill badge-warning">{{$invoice_count}}</span>
            </h5>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>
</div>
<div class="pull-right w-100 hidden-mob" style="margin-right: -15px;display: none;">
<div class="container">
  <div class="row">
    <div class="fcon">
      <div class="col-md-2 pull-right">
        <a href="https://adibcomputer.com/%D9%86%D8%B5%D8%A8-%D9%88-%D8%B1%D8%A7%D9%87-%D8%A7%D9%86%D8%AF%D8%A7%D8%B2%DB%8C-%D8%B4%D8%A8%DA%A9%D9%87/">
          <div class="prosda animated bounceIn">
            <img src="{{ asset('img/net2.jpg') }}" alt="">
            {{--<img src="https://adib-it.com/cdn/assets/img/server.png" alt="">--}}
            <h5 class="title">نصب و راه اندازی شبکه</h5>
          </div>
        </a>
      </div>
      <div class="col-md-2 pull-right">
        <a href="https://adibcomputer.com/%D8%AE%D8%AF%D9%85%D8%A7%D8%AA-%D8%B4%D8%A8%DA%A9%D9%87/">
          <div class="prosda animated bounceIn">
            <img src="{{ asset('img/net.jpg') }}" alt="">
            {{--<img src="https://adib-it.com/cdn/assets/img/database.png" alt="">--}}
            <h5 class="title">خدمات شبکه</h5>
          </div>
        </a>
      </div>
      <div class="col-md-2 pull-right">
        <a href="https://adibcomputer.com/%D8%AE%D8%AF%D9%85%D8%A7%D8%AA-%D8%A7%D8%AC%D8%B1%D8%A7%DB%8C%DB%8C-%D9%85%D8%B1%D8%A8%D9%88%D8%B7-%D8%A8%D9%87-%D9%BE%D8%B4%D8%AA%DB%8C%D8%A8%D8%A7%D9%86%DB%8C-%D8%B4%D8%A8%DA%A9%D9%87/">
          <div class="prosda animated bounceIn">
            <img src="{{ asset('img/net3.jpg') }}" alt="">
            {{--<img src="https://adib-it.com/cdn/assets/img/cust.png" alt="">--}}
            <h5 class="title">پشتیبانی شبکه</h5>
          </div>
        </a>
      </div>
      <div class="col-md-2 pull-right">
        <a href="https://adibcomputer.com/%D8%AE%D8%AF%D9%85%D8%A7%D8%AA-%D8%B3%DB%8C%D8%B3%DA%A9%D9%88/">
          <div class="prosda animated bounceIn">
            <img src="{{ asset('img/sisco.jpg') }}" alt="">
            {{--<img src="https://adib-it.com/cdn/assets/img/clud.png" alt="">--}}
            <h5 class="title">خدمات سیسکو</h5>
          </div>
        </a>
      </div>
      <div class="col-md-2 pull-right">
        <a href="https://adibcomputer.com/%D9%BE%D8%B4%D8%AA%DB%8C%D8%A8%D8%A7%D9%86%DB%8C-voip/">
          <div class="prosda animated bounceIn">
            <img src="{{ asset('img/voip.jpg') }}" alt="">
            {{--<img src="https://adib-it.com/cdn/assets/img/back-up.png" alt="">--}}
            <h5 class="title">پشتیبانی VOIP</h5>
          </div>
        </a>
      </div>
      <div class="col-md-2 pull-right">
        <a href="https://adibcomputer.com/%D8%AE%D8%AF%D9%85%D8%A7%D8%AA-%D9%85%DB%8C%DA%A9%D8%B1%D9%88%D8%AA%DB%8C%DA%A9/">
          <div class="prosda animated bounceIn">
            <img src="{{ asset('img/micro.jpg') }}" alt="">
            {{--<img src="https://adib-it.com/cdn/assets/img/servers.png" alt="">--}}
            <h5 class="title">خدمات میکروتیک</h5>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>
</div>
@endif

<div class="alert alert-danger text-center hidden"><i class="fa fa-warning"></i> کاربر گرامی، لطفا از ارسال
تیکت
های متعدد خودداری فرمایید، درصورت نیاز به تغییر یا ادامه یک موضوع، در همان تیکت پاسخ جدید ارسال کنید.
</div>

<div class="row">

@yield('content')

<footer class="col-md-12 footer">
<p class="pull-left">Adib-IT Group CRM</p>
<p class="pull-right">Copyright © 2017 | Design & Develop by <a href="https://adib-it.com/"
                                                                target="_blank" rel="follow">Adib IT
    -
    Developer Team</a>, All rights reserved.</p>
</footer>

</div>
{{-- create package --}}
</div>
</div>
</div>
@if (Auth::user()->role_id == 5)

<div class="last-warning" style="display:none;">

<i class="fa fa-warning"></i>
<p>از ارسال تیکت های متعدد خودداری فرمایید، درصورت نیاز به تغییر یا ادامه یک موضوع، در همان تیکت پاسخ جدید ارسال
کنید.</p>
<p>درصورت تکرار دسترسی شما مسدود می شود.</p>

<button class="l-w-close">x</button>

</div>

@endif

@if (session()->has('status') || session()->has('flash_message'))
<div class="alert alert-notify alert-success animated fadeInDown"><i class="fa fa-close pull-left"></i>
{{session('status')??session('flash_message')}}
</div>
@endif
@if (session()->has('danger') || session()->has('err_message'))
<div class="alert alert-notify alert-danger animated fadeInDown"><i class="fa fa-close pull-left"></i>
{{session('danger')??session('err_message')}}
</div>
@endif
{{--<script type="text/javascript">--}}
{{--function googleTranslateElementInit() {--}}
{{--new google.translate.TranslateElement({pageLanguage: 'fa'}, 'google_translate_element');--}}
{{--}--}}
{{--</script>--}}

<script type="text/javascript"
src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<!-- Scripts -->
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
{{--<script src="{{ asset('assets/js/datatables.min.js') }}"></script>--}}

<script type="text/javascript"
src="https://cdn.datatables.net/tabletools/2.2.4/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.flash.min.js"></script>
<script src="{{ asset('assets/js/jszip.min.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/pdfmake.min.js"></script>
{{--<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>--}}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/vfs_fonts.js"></script>
{{--<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>--}}
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.print.min.js"></script>
<script src="{{ asset('assets/js/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/footable.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datepicker.fa.min.js') }}"></script>
<script src="{{ asset('assets/js/pnotify.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.growl.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-notify.js') }}"></script>
<script src="{{ asset('assets/js/printThis.js') }}"></script>
<script src="{{ asset('assets/js/theme-gull.js') }}"></script>
{{--<script src="{{ asset('assets/js/sidbar.js') }}"></script>--}}
<script src="{{ asset('assets/js/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/panel.js') }}"></script>

<script>
$(document).ready(function () {
$('.menu-toggle').click();
})
$(".perfect-scrollbar, [data-perfect-scrollbar]").each(function (index) {
var $el = $(this);
var ps = new PerfectScrollbar(this, {
  suppressScrollX: $el.data("suppress-scroll-x"),
  suppressScrollY: $el.data("suppress-scroll-y")
});
}); // Full screen
</script>
<script>
$(function () {
$('#sales').on('submit', function (e) {
  e.preventDefault();
  $.ajax({
      type: 'POST',
      url: 'https://support.adib-it.com/api/store_sale_info',
      data: $('#sales').serialize(),
      success: function () {
          $('.sale_out, .ffdhj').hide();
          $('.sale_bt_out, .khk').fadeIn(500);
      },
      error: function () {
          $('#sale-eror').show();
      }
  });
});
});
</script>
@if (Auth::user()->role_id == 4 || Auth::user()->role_id == 3 || Auth::user()->role_id == 1)
<input type="hidden" id="user_role" name="id" value="{{Auth::user()->role_id}}"/>
<script>
$(document).ready(function () {
PNotify.desktop.permission();
})

</script>

@if(ticket_send() == 1)
<script>
$(document).ready(function () {
  function has_tick() {
      var id = $('#user_role').val();
      $.get("https://support.adib-it.com/has_ticket/" + id, function (data) {
          if (data == "ok") {
              (new PNotify({
                      title: 'تیکت',
                      type: 'success',
                      text: 'تیکت جدید',
                      desktop: {
                          desktop: true,
                          icon: 'https://support.adib-it.com/img/success.png'
                      }
                  })
              )
          } else {

          }
      });
      setTimeout(has_tick, 180000);
  }

  has_tick();

})
</script>
@endif

@endif
<script>
$('.user').click(function () {
let user_id = $(this).attr('data-id');
let user_name = $(this).attr('data-name');
$('#messageBox').attr('data-id', user_id);
fetchMessage(user_id);
$('.user-title').show('fast');
$('.user-name').text(user_name);
});
$('#messageBox').keypress(function (e) {
if (e.which == 13) {
  let message = $(this).val();
  let user_id = $(this).attr('data-id');
  sendMessage(message, user_id)
  $('#messageBox').val("");
}
});

function sendMessage(message, receiver) {
if (receiver != 0) {
  $.ajax({
      method: 'GET',
      url: '/panel/sendMessage',
      data: {message, receiver},
      success: function (result) {
          $('.row-message').append(
              '<div class="col-12">\n' +
              '<span style="margin-left: auto;">' + message + '</span>\n' +
              '</div>');
          $('.no-message').hide();
          $('#messageBox').val("");
      }
  })
} else {
  alert('برای ارسال پیام یکی از کاربران را انتخاب نمایید');
}
}

function fetchMessage(user_id) {
$('.message-vector').hide();
$('.row-message .col-12').remove();
$('.preloading').slideDown('fast');
$('.no-message').hide();
let me = '{{ Auth::user()->id }}'
$.ajax({
  method: 'GET',
  url: '/panel/fetchMessage',
  data: {user_id},
  success: function (result) {
      $('.row-message .col-12').remove();
      $('.preloading').hide('fast');
      if (jQuery.isEmptyObject(result)) {
          $('.no-message').slideDown('fast');
      }
      result.filter(res => {
          if (res['sender_id'] == me) {
              $('.row-message').prepend(
                  '<div class="col-12">\n' +
                  '<span style="margin-left: auto;">' + res['message'] + '</span>\n' +
                  '</div>');
              $('#messageBox').val("");
          } else {
              $('.row-message').prepend(
                  '<div class="col-12">\n' +
                  '<span style="margin-right: auto;background: #3097d1;color: #fff;">' + res['message'] + '</span>\n' +
                  '</div>');
              $('#messageBox').val("");
          }
      })
  }
})
}

$('#users-open').click(function () {
$('.users').toggleClass('users-open');
})
</script>
{{-- tooltop section --}}
<script>
if ($(".dataTable")[0]) {
$('.dataTable').dataTable();
}
// Select a specified element
if ($("#myTooltip")[0]) {
$('#myTooltip').tooltip();
}
</script>
<script>
$('select').each(function () {
let notselectpicker = $(this).hasClass('not_select');
let selectpicker = $(this).hasClass('select');
let form_control = $(this).hasClass('form-control');
let dataLive = $(this).data('live-search');
if(notselectpicker == false)
{
  if (selectpicker == false) {
      $(this).addClass('select');
  }
  if (form_control == false) {
      $(this).addClass('form-control');
  }
}
// if (typeof dataLive == typeof undefined || dataLive == false){
//     $(this).attr('data-live-search','true');
// }
})
</script>
@if(auth()->id()==99999)
{{--    @php dd(session()->all()) @endphp--}}
@if(session()->has('selectProject'))
<script>
$('#projectSelector').modal('show');
</script>
@endif
@endif
<script>
sheetIds($('#sheetType').val());

function sheetIds(element) {
let target = element;
$('#sheetType_id').find('option').hide();
$('#sheetType_id').find('*[data-type="' + target + '"]').show();
}

$('#sheetType').change(function () {
sheetIds($(this).val());
})
</script>
<script>

let timer = setInterval(function () {
if ($('.sec').length < 1) {
  clearTimer();
}
$('.sec').each(function () {
  let sec = parseInt($(this).html()) + 1;
  if (sec > 59) {
      let min = parseInt($(this).prev().html()) + 1;
      if (min < 60) {
          $(this).prev().html(min < 10 ? '0' + min : min);
          $(this).html('00');
      } else {
          let hou = parseInt($(this).prev().prev().html()) + 1;
          $(this).html('00');
          $(this).prev().html('00');
          $(this).prev().prev().html(hou < 10 ? '0' + hou : hou);
      }
  } else {
      $(this).html(sec < 10 ? '0' + sec : sec);
  }
})
}, 1000);

function clearTimer() {
clearInterval(timer);
}
$('#sidenav_div').hover(function(){
$('.sidebar-left-secondary').removeClass('open')
})
</script>

@yield('scripts')
@stack('script')
{{--<script type="text/javascript" src="{{ asset('assets/js/froallajs.js') }}"></script>--}}
</body>
</html>
<?php
}
?>
