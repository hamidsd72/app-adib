@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header bg-zard">
                    <div class="float-left">
                        <a href="#" data-toggle="modal" data-target="#addReport" class="btn btn-info">
                            اضافه کردن گزارش
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                    <div class="float-right">
                        <button class="btn btn-light px-3" onclick="ExportToExcel('xlsx')"><i class="fa fa-file-excel-o"></i></button>
                        <button class="btn btn-light px-3 mx-2" onclick="generatePDF()"><i class="fa fa-file-pdf-o"></i></button>
                    </div>
                </div>
                <div class="card-body box-profile">
                    <table id="example2" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th>تاریخ اجرای فعالیت</th>
                            <th>زمان اجرا</th>
                            <th>نمایش مکان</th>
                            <th>گزارش فعالیت</th>
                            <th>هزینه ی گزارش شده</th>
                            <th>ضمیمه گزارش فعالیت</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($items)>0)
                            @foreach($items as $item)
                                <tr >
                                    <td>{{my_jdate($item->created_at,'d F Y')}}</td>
                                    <td>{{$item->time.' دقیقه '}}</td>
                                    <td>
                                        @if ($item->location)
                                            <a target="_blank" href="{{'https://www.google.com/maps/@'.$item->location}}">نمایش مکان از روی نقشه</a>
                                            {{-- <a target="_blank" href="{{route('admin.job-report-show-map',$item->id)}}">نمایش مکان از روی نقشه</a> --}}
                                        @else ______ @endif
                                    </td>
                                    <td class="col-5">
                                        <a href="#" data-toggle="tooltip" data-placement="left" title="{{$item->description}}">
                                            {{substr($item->description,0,200).'...'}}
                                        </a>
                                    </td>
                                    <td>{{$item->price.' تومان '}}</td>
                                    <td>
                                        @if ($item->attach)
                                            <a href="{{url($item->attach)}}" download>دانلود فابل ضمیمه شده</a>
                                        @else ______ @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="6" class="text-center">موردی یافت نشد</td></tr>
                        @endif
                    </table>
                </div>
            </div>
            <div class="pag_ul">
                {{ $items->links() }}
            </div>
        </div>
    </section>

    <div class="modal fade mt-5" id="addReport" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content redu20"> 
                <div class="modal-header">
                    <h4 class="modal-title">لطفا بازه زمانی را انتخاب کنید</h4>
                </div>
                <div class="modal-body">
                    <div class="content mt-0">
                        <form method="post" action="{{route('admin.job-report-add-report')}}" enctype="multipart/form-data">
                            @csrf
                            <fieldset>
                                <div class="row mb-0">
                                    <input type="hidden" name="job_id" value="{{$id}}">
                                    <input type="hidden" name="user_id" value="{{$item->user_id}}">
                                    <div class="form-field form-text">
                                        <label class="contactMessageTextarea color-theme" for="date">زمان را وارد کنید (دقیقه)</label>
                                        <input type="number" name="time" class="round-small mb-0" id="time" required>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                    <div class="form-button col-lg-6">
                                        <input type="submit" class="btn btn-info col-12 mt-3" value="ثبت" data-formid="contactForm">
                                    </div>
                                    <div class="form-button col-lg-6">
                                        <button type="button" class="btn btn-secondary  col-12 mt-3" data-dismiss="modal">بستن</button>
                                    </div>
                                </div>
                            </fieldset> 
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection
@section('js')
@endsection
