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
                <!-- Notificaiton -->
               <div class="dropdown">
                   <div class="badge-top-container pt-2 me-3" role="button" id="dropdownNotification" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                       {{auth()->user()->unreadNotifications->count()}}
                       <img src="https://img.icons8.com/external-icematte-lafs/28/000000/external-Messages-it-icematte-lafs.png"/>
                   </div>
                   <!-- Notification dropdown -->
                   <div class="dropdown-menu dropdown-menu-right">
                           @if(auth()->user()->unreadNotifications->count())
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


