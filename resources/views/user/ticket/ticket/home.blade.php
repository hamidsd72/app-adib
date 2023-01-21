@extends('user.master')
@if(auth()->user()->role_id==9 || auth()->user()->role_id==2)
    @section('styles_meta')
        <meta http-equiv="refresh" content="300" >
    @endsection
@endif
@section('content')

    <div class="container mt-5">
        <div class="row m-0">
            <div class="col fs-6 my-auto">
                {{$title}}<span> : {{ $data->count() }}</span>
            </div>
            <div class="col-auto">
                <a href="{{ url('/ticket') }}" class="btn btn-primary">همه تیکت ها</a>
            </div>
        </div>

        <div class="accordion my-3" id="accordionExample">
            <div class="accordion-item"  style="border-radius: 30px;">
                <h2 class="accordion-header" id="headingSearch">
                <button class="accordion-button collapsed btn btn-info p-1 pt-2 ps-5 " style="border-radius: 30px;"
                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseSearch"
                    aria-expanded="false" aria-controls="collapseSearch">جستجوی پیشرفته</button>
                </h2>
                <div id="collapseSearch" class="accordion-collapse collapse" aria-labelledby="headingSearch" data-bs-parent="#accordionExample">
                <div class="accordion-body">

                    {{-- جستجوی پیشرفته --}}
                    <form action="{{ route('user.ticket-search') }}" method="POST">
                        <div class="form-group">
                            <label for="name">انتخاب شرکت</label>
                            <select name="name" id="name" class="form-control select2">
                                @foreach($companies as $key => $company)
                                    <option value="{{ $company->id }}">{{ $company->company__name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group my-4">
                            <label for="number"> یا - شماره تیکت</label>
                            <input type="number" name="number" id="number" class="form-control" value="{{ old('number') }}" required/>
                            {{ csrf_field() }}
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> جستجو شود</button>
                    </form>

                </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body px-0 pt-0">
                <table class="table datatable-responsive table-togglable">
                    <div class="card-header bg-warning redu20">
                        <thead class="bg-warning"> 
                            <tr>
                                {{-- @if(isset($invoices)) --}}
                                    <th class="text-center" data-toggle="true">شرکت</th>
                                    {{-- <th class="text-center" data-hide="phone">اولویت</th> --}}
                                    <th class="text-center" data-hide="phone">عنوان</th>
                                    <th class="text-center" data-hide="phone">پاسخ دهنده</th>
                                    <th class="text-center" data-hide="phone">وضعیت</th>
                                {{-- @else
                                    <th class="text-center" data-hide="phone">شرکت</th>
                                    <th class="text-center" data-hide="phone">اولویت</th>
                                    <th class="text-center" data-hide="phone">تاریخ ثبت</th>
                                    <th class="text-center" data-hide="phone">وضعیت</th>
                                @endif --}}
                            </tr>
                        </thead>
                    </div>
                    <tbody>
                        @foreach($data as $data)
                            <tr style="background: {{ count($data->comments->where('confirmation',0)) ? 'antiquewhite' : '' }}">
                                <td>
                                    <a href="#" onclick="openModal( '{{$data->ticket__priority}}' , '{{$data->role()->description}}' , 
                                    '{{my_jdate($data->updated_at,'d F Y').' '.$data->updated_at->format('H:i')}}' , '{{$data->referred()?$data->referred()->name:'______'}}' , '{{$data->id}}' )" 
                                    class="btn btn-primary p-0 px-1">{{$data->user->company__name}}.. 
                                    @if ($data->seen__id==0)
                                        <div class="spinner-grow text-danger" role="status"><span class="sr-only"></span></div>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn @if($data->ticket__priority=='زیاد') btn-danger @elseif($data->ticket__priority=='متوسط') btn-warning @else btn-success @endif p-0 px-1">
                                        {{$data->ticket__title}}
                                    </a>
                                </td>
                                <td>
                                    {{$data->comments->count() ? $data->comments->last()->user->count() ? $data->comments->last()->user->name : '' : 'کاربر'}}
                                    <span class="text-danger fw-bold">
                                        {{$data->contract_true=='no'?'(بدون قرارداد)':''}}
                                    </span>
                                </td>
                                <td class="my-auto">
                                    <div id="ticketStatus{{$data->id}}" >
                                        <a href="#" class="pb-1 text-info border-info border-bottom" onclick="translateStatus('{{$data->id}}','{{$data->status}}')">
                                            {{$data->ticket__status}}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- نمایش جزییات آیتم ها --}}
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content mt-5 redu20">
            
                <div class="modal-header bg-warning" style="border-radius: 20px 20px 0px 0px;"><h4 class="modal-title" id="titleItem"></h4></div>
            
                <div class="modal-body">
                    <p class="fs-6 mb-3" id="roleNameItem"></p>
                    <p class="fs-6 mb-3" id="referredItem"></p>
                    <p class="fs-6 mb-3 text-dark" id="updatedAtItem"></p>
                    <input type="hidden" name="idItem" id="idItem" value="">
                    <button type="button" class="btn btn-primary mt-3" onclick="showItem()">بررسی تیکت</button>
                    <button type="button" class="btn btn-secondary mt-3 float-end" data-bs-dismiss="modal">بستن</button>
                </div>
            
            </div>
        </div>
    </div>

    <button id="openModal" type="button" style="display: none" data-bs-toggle="modal" data-bs-target="#myModal">Open</button>

    <script>
        function openModal( priority , roleName , updatedAt , referred , id ) {
            document.getElementById('openModal').click();
            document.getElementById("titleItem").innerHTML = ` اولویت : ${priority}`;
            document.getElementById("roleNameItem").innerHTML = ` بخش ${roleName}`;
            document.getElementById("referredItem").innerHTML = ` ارجاع به ${referred}`;
            document.getElementById("updatedAtItem").innerHTML = ` آخرین بروزرسانی ${updatedAt}`;
            document.getElementById("idItem").value = id;
        }
        function showItem() {
            let id  = document.getElementById("idItem").value;
            window.open( `/ticket/${id}` , '_blank');
        }
        function translateStatus( id , data ) {
            document.getElementById(`ticketStatus${id}`).innerHTML = data;
        }
    </script>
@endsection
