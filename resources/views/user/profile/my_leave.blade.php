@extends('user.master')
@section('content')

    <div class="container mt-5">
        <div class="py-2">
            <div class="fs-6">{{$title}}</div>
        </div>
        <div class="card">
            <table class="table datatable-responsive table-togglable">
                <thead>
                    <div class="card-header bg-warning">
                        <div class="row my-1">
                            <div class="col p-0 fw-bold text-center">نوع</div>
                            <div class="col p-0 fw-bold text-center">بازه تاریخ</div>
                            <div class="col p-0 fw-bold text-center">بازه ساعت</div>
                            <div class="col p-0 fw-bold text-center">وضعیت</div>
                            <div class="col p-0 fw-bold text-center">جزییات</div>
                        </div>
                    </div>
                </thead>
                <tbody>
                @foreach($items as $data)
                    <tr>
                        <td>{{$data->type==1?'ساعتی':'روزانه'}}</td>
                        <td>{{my_jdate($data->as_date, 'j F Y')}} <br> {{my_jdate($data->to_date, 'j F Y')}}</td>
                        <td>از {{$data->as_time}} <br> الی {{$data->to_time}}</td>
                        <td >
                            <div class="pt-3">
                                <div class="d-none d-md-block">
                                    @if($data->status == 0)
                                        <span class="bg-warning redu20 p-1 ">در انتظار تایید مدیریت</span>
                                    @elseif($data->status == 1 || $data->status == 3)
                                        <span class="bg-success redu20 p-1 ">با مرخصی موافقت شده</span>
                                    @elseif($data->status == 2)
                                        <span class="bg-danger redu20 p-1  text-white">رد شده توسط مدیریت</span>
                                    @endif
                                </div>
                                <div class="d-md-none">
                                    @if($data->status == 0)
                                        <span class="fs-5 me-3 text-warning"><i class="far fa-question"></i></span>
                                    @elseif($data->status == 1 || $data->status == 3)
                                        <span class="fs-5 me-3 text-success"><i class="fa fa-check"></i></span>
                                    @elseif($data->status == 2)
                                        <span class="fs-5 me-3 text-danger"><i class="fa fa-close "></i></span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="pt-3">
                                <a class="p-1 bg-success redu20 text-white" href="#"
                                 onclick="openModal( '{{$data->title}}' , '{{$data->description}}' , '{{my_jdate($data->created_at, 'Y/m/d').' - '.my_jdate($data->created_at, 'H:i:s')}}' )">نمایش</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
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
                    <p class="mt-3 text-dark" id="createdItem"></p>
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
            document.getElementById("createdItem").innerHTML = created_at;
        }
    </script>

@endsection
