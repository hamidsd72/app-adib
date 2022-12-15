@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header bg-zard">
                    <div class="float-right">
                        <button class="btn btn-light px-3" onclick="ExportToExcel('xlsx')"><i class="fa fa-file-excel-o"></i></button>
                        <button class="btn btn-light px-3 mx-2" onclick="generatePDF()"><i class="fa fa-file-pdf-o"></i></button>
                    </div>
                </div>
                <div class="card-body box-profile">
                    <table id="example2" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th>عنوان</th>
                            <th>هزینه های گزارش شده</th>
                            <th>تاریخ</th>
                            <th>زمان اجرا</th>
                            <th>جزئیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($items)>0)
                            @foreach($items as $item)
                                <tr >
                                    <td>{{$item->packageName()?$item->packageName()->title:'________'}}</td>
                                    <td>{{$item->job()->sum('price').' تومان '}}</td>
                                    <td>{{my_jdate($item->created_at,'d F Y')}}</td>
                                    <td>{{$item->jobTime()>0?$item->jobTime().' دقیقه ':'__________'}}</td>
                                    <td><a href="{{route('admin.job-report.edit',$item->id)}}" class="badge bg-primary">نمایش جزئیات</a></td>
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

@endsection
@section('js')
@endsection
