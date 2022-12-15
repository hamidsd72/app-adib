@extends('layouts.admin')
@section('css')
<style>
    .user-profile-box-border {
        border: 1px solid gray;
        border-radius: 4px;
        padding: 0px;
    }
    @media only screen and (max-width: 640px) {
        .small-box h3 {
            font-size: 16px !important;
        }
    }
    .small-box > .small-box-footer {
        font-size: 12px !important;
    }
    .small-box {
        border-radius: 20px;
    }
    .small-box>.small-box-footer {
        border-radius: 20px;
        margin: 0px 12px;
    }
    .user-profile-box-border {
        border: none;
        text-align: center;
    }
    .row .small-box .inner h3 , .row .small-box .inner p {
        color: white !important;
    }
    @media only screen and (max-width: 640px) {
        .row .small-box .inner h3 , .row .small-box .inner p {
            margin: 0px;
        }
    }
</style>

@endsection
@section('content')
<section class="content">
    <div class="user-profile-box-border">
        <img src="{{auth()->user()->profile? is_file(auth()->user()->profile) ? url(auth()->user()->profile) : 'https://support.adib-it.com/'.auth()->user()->profile :
             'https://img.icons8.com/ultraviolet/100/000000/test-account.png'}}" class="profile-user-img img-circle" alt="User Image">
        <div class="fw-bold"> @item($item->name)</div>
    </div>
    <p class="text-muted text-center mb-2">@item($item->education)</p>
    <div class="container-fluid pb-3">
        {{-- <div class="row">
            <div class="col-sm-6">
                <strong><i class="fa fa-calendar-alt ml-1"></i> تاریخ ثبت</strong>
                <p class="text-muted">
                    {{my_jdate($item->create,'d F Y')}}
                </p>
            </div>
            <div class="col-sm-6">
                <strong><i class="fa fa-mobile ml-1"></i> موبایل</strong>
                <p class="text-muted">
                    @if($item->mobile!=null) @item($item->mobile) @else ثبت نشده @endif
                    @if($item->mobile_status=='pending')
                        <span class="right badge badge-danger">تایید نشده</span>
                    @elseif($item->mobile_status=='active')
                            <span class="right badge badge-success">تایید شده</span>
                    @endif
                </p>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-lg-6">
                <div class="small-box bg-dark">
                    <div class="inner">
                        <h3>
                            {{-- @if($item->mobile!=null) @item($item->mobile) @else ثبت نشده @endif --}}
                            {{$item->email?$item->email:$item->mobile}}
                            {{-- @if($item->mobile_status=='pending')
                                <span class="right badge badge-danger">تایید نشده</span>
                            @elseif($item->mobile_status=='active')
                                    <span class="right badge badge-success">تایید شده</span>
                            @endif --}}
                        </h3>
                        <p><strong><i class="fa fa-calendar-alt ml-1"></i> تاریخ ثبت -  </strong>{{my_jdate($item->create,'d F Y')}}</p>
                    </div>
                    <div class="icon"><i class="fa fa-user text-secondary"></i></div>
                    <a href="{{route('admin.profile.edit')}}" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        {{-- <h3>{{App\Model\ServicePackage::where('user_id', auth()->user()->id)->where('status', 'active')->where('type', 'sample')->count()}} مورد</h3> --}}
                        <p>محاسبه فعالیت های من</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="{{route('user.job-report.show',auth()->user()->id)}}" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        {{-- <h3>{{App\Model\ServicePackage::where('user_id', auth()->user()->id)->where('status', 'active')->where('type', 'sample')->count()}} مورد</h3> --}}
                        <p>فعالیت های من</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="{{route('user.packages')}}" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        {{-- <h3>{{App\Model\Notification::where('user_id',auth()->user()->id)->where('status',"pending")->count()}} جدید</h3> --}}
                        <p>پیام های من</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-comment-o"></i>
                    </div>
                    <a href="{{route('user.notification.index')}}" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        {{-- <h3>{{App\Model\Contact::where('user_id', auth()->user()->id )->count()}} تیکت</h3> --}}
                        <p>تیکت های من</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-comment-o"></i>
                    </div>
                    <a href="{{route('user.tickets')}}" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
@section('js')
    
@endsection