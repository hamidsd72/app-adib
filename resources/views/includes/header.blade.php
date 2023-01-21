<style>.menu-overlay .main-menu .menu-container .nav-pills .nav-item .nav-link { text-align: right !important; }</style>

<header class="header" style="min-height: 60px !important;">
    <div class="row mb-0 pt-1">
        <div class="col-auto px-0">
        </div>
        <div class="text-left col">
            <a class="navbar-brand" href="#">
                <div class="icon icon-44 text-white" style="height: 40px;width: 40px;">
                    <img src="{{ $setting->icon_site?url($setting->icon_site):'' }}" alt="{{ $setting->title }}" style="width: 100%;">
                </div>
            </a>
        </div>
        <div class="ml-auto col-auto">
            <div class="d-flex">
                <div id="notify" class="dropdown">
                    <div class="badge-top-container pt-2 me-2" role="button" id="dropdownNotification" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <img src="https://img.icons8.com/ultraviolet/26/appointment-reminders.png" alt="notification">
                        {{auth()->user()->unreadNotify->count()}}
                    </div>
                    <div class="dropdown-menu dropdown-menu-right">
                        @if(auth()->user()->unreadNotify->count())
                            <a href="javascript:void(0)" data-url="{{route('user.notification.read.all')}}" class="all_read_not">
                                <div class="d-flex dropdown-item text-center">
                                    <div class="notification-details flex-grow-1">
                                        <p class="text-dark py-2 m-0">خالی کردن نوتیفیکیشن</p>
                                    </div>
                                </div>
                            </a>
                            @foreach(auth()->user()->unreadNotify as $key=>$notification)
                                {{-- <a href="{{url('/').substr(json_decode($notification->data)->url,27,100).'?id_not='.$notification->key}}" target="_blank"> --}}
                                <a href="{{json_decode($notification->data)->url.'?id_not='.$notification->key}}" target="_blank">
                                    <div class="dropdown-item d-flex mt-2">
                                        <div class="notification-details flex-grow-1">
                                            <div class="d-flex">
                                                <img src="https://img.icons8.com/ultraviolet/16/alarm.png" alt="notification">
                                                <p class="m-0 ms-2 text-end">{{g2j($notification->created_at,'Y/m/d H:i')}}</p>
                                            </div>
                                            <p class="m-0 text-start text-dark">
                                                {{json_decode($notification->data)->title}}
                                                <br>
                                                {{json_decode($notification->data)->name}}
                                            </p>
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
                    </div>
                </div>
                <!-- Notificaiton End -->
                <button class="menu-btn btn btn-link-default" type="button">
                    <img src="https://img.icons8.com/ultraviolet/28/000000/line-width.png"/>
                </button>
            </div>
        </div>
    </div>
</header>

<div class="main-menu">
    <div class="menu-container">
        <div class="icon icon-100 position-relative">
            <figure class="background">
                <img src="{{ $setting->icon_site?url($setting->icon_site):'' }}" alt="{{ $setting->title }}" style="width: 100%;">
            </figure>
        </div>
        <ul class="nav nav-pills flex-column ">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('user.index') }}">
                    <i class="me-1 fa fa-home"></i>
                    صفحه اصلی
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.profile.show') }}">
                    <i class="me-1 fa fa-user"></i>
                    پروفایل
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.tickets') }}">
                    <i class="me-1 fa fa-edit"></i>
                    درخواست ها
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.packages') }}">
                    <i class="me-1 fa fa-check-square"></i>
                    فعالیت ها
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.notification.index') }}">
                    <i class="me-1 fa fa-envelope-open"></i>
                    پیام ها
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.contact.show') }}">
                    <i class="mx-1 fa fa-info"></i>
                    درباره ما
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ env('LEARN_CHANNEL_URl') }}" target="_blank">
                    <i class="fa fa-desktop"></i>
                    چنل آموزش ها
                </a>
            </li>
            
        </ul>
        <a class="text-danger my-2 d-block" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="me-1 fa fa-sign-out"></i>
            خروج از حساب
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        <button class="btn btn-secondary sqaure-btn close text-white"><svg xmlns='http://www.w3.org/2000/svg' class="icon-size-24" viewBox='0 0 512 512'>
                <title>ionicons-v5-l</title>
                <line x1='368' y1='368' x2='144' y2='144' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px' />
                <line x1='368' y1='144' x2='144' y2='368' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px' />
            </svg></button>
    </div>
</div>


