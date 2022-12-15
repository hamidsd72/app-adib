@extends('user.master')
@section('content')

  <div class="container mt-5">
    <div class="py-2">
      <div class="float-start fs-6">{{$title}}</div>
      <div class="text-end">
        <a href="{{route('user.time-login-create')}}" class="bg-primary text-white p-1 pt-2 px-2 redu20"><i class="fa fa-plus px-1"></i></a>
      </div> 
    </div>
    <div class="card">
      <table class="table datatable-responsive3 table-togglable">
        <thead>
          <div class="card-header bg-warning">
            <div class="row my-1">
              <div class="col p-0 fw-bold text-center">نام کاربر</div>
              <div class="col p-0 fw-bold text-center">تاریخ</div>
              <div class="col p-0 fw-bold text-center">زمان ورود</div>
              <div class="col p-0 fw-bold text-center">زمان خروج</div>
              <div class="col p-0 fw-bold text-center">وضعیت</div>
              <div class="col p-0 fw-bold text-center">جزییات</div>
            </div>
          </div>
        </thead>
        <tbody>
          @if ($items->count())
            @foreach($items as $data)
              <tr>
                <td>{{$data->name}}</td>
                <td title="{{$data->date_en}}">{{$data->date_fa}}</td>
                <td>{{ substr($data->time_login,0,5) }}</td>
                <td>{{ substr($data->time_exit,0,5) }}</td>
                <td>
                  @if(auth()->user()->role_id==1)
                    @if($data->status=='pending')
                      <p class="m-0 text-warning">بررسی</p>
                      <a class="bg-success redu20" title="تایید" href="{{route('user.time-login-status',[$data->id,'active'])}}"><i class="fa fa-check text-light ms-1"></i> </a>
                      <a class="bg-danger redu20 ms-2 ms-lg-3" title="کنسل"  href="{{route('user.time-login-status',[$data->id,'cancel'])}}"><i class="fa fa-close text-light mx-1 ms-2"></i> </a>
                    @elseif($data->status=='active')
                      <p class="m-0 text-success">
                        تایید شده
                        ({{$data->user_status?$data->user_status->name:'__'}})
                      </p>
                      <a class="mr-2" title="کنسل"  href="{{route('user.time-login-status',[$data->id,'cancel'])}}"><i class="fa fa-close text-danger"></i></a>
                    @elseif($data->status=='cancel')
                      <p class="m-0 text-danger">
                        کنسل شده
                        ({{$data->user_status?$data->user_status->name:'__'}})
                      </p>
                      <a class="mr-2" title="تایید" href="{{route('user.time-login-status',[$data->id,'active'])}}"><i class="fa fa-check text-success"></i> </a>
                    @endif
                  @else
                    @if($data->status=='pending')
                      <span class="d-none d-md-block text-center bg-warning redu20 p-1">در دست بررسی</span>
                      <span class="d-md-none fs-5 me-3 text-warning"><i class="far fa-question mt-1"></i></span>
                    @elseif($data->status=='active')
                      <span class="d-none d-md-block text-center text-light bg-success redu20 p-1">تایید شده</span>
                      <span class="d-md-none fs-5 me-3 text-success"><i class="fa fa-check mt-1"></i></span>
                    @elseif($data->status=='cancel')
                      <span class="d-none d-md-block text-center text-light bg-danger redu20 p-1">کنسل شده</span>
                      <span class="d-md-none fs-5 me-3 text-danger"><i class="fa fa-close mt-1"></i></span>
                    @endif
                  @endif
                </td>
                <td>
                  <div class="pt-1">
                    <a class="p-1 bg-success redu20 text-white" href="#" onclick="openModal( '{{$data->info}}' , '{{$data->text}}' )">نمایش</a>
                  </div>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
      {{ $items->count()?$items->links():'' }}
    </div>
  </div>

  <div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content mt-5 redu20">
        
            <div class="modal-header bg-warning" style="border-radius: 20px 20px 0px 0px;"><h4 class="modal-title" id="titleItem"></h4></div>
        
            <div class="modal-body">
                <p class="fs-6 my-0" id="descriptionItem"></p>
                <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">بستن</button>
            </div>
        
        </div>
    </div>
  </div>

  <button id="openModal" type="button" style="display: none" data-bs-toggle="modal" data-bs-target="#myModal">Open</button>

  <script>
      function openModal( title , description , created_at ) {
          document.getElementById('openModal').click();
          document.getElementById("titleItem").innerHTML = title;
          document.getElementById("descriptionItem").innerHTML = description;
      }
  </script>

@endsection

