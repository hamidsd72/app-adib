@extends('layouts.panel')
@section('content')
    <div class="col-md-12 dsd">
        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">{{$title}}</div>
            <div class="panel-body">
                <table class="table datatable-responsive3 table-togglable">
                    <thead>
                    <tr>
                        @if(isset($invoices))
                            <th data-hide="phone">#</th>
                            <th data-toggle="true">عنوان تیکت</th>
                            <th data-hide="phone">نام شرکت</th>
                            <th data-hide="phone">اولویت</th>
                            <th data-hide="phone">آخرین پاسخ دهنده</th>
                            <th data-hide="phone">وضعیت</th>
                        @else
                            <th data-hide="phone">#</th>
                            <th data-toggle="true">عنوان</th>
                            <th data-hide="phone">شرکت</th>
                            <th data-hide="phone">اولویت</th>
                            <th data-hide="phone">تاریخ ثبت</th>
                            <th data-hide="phone">وضعیت</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $data)
                        <tr onclick="document.location = '{{url("panel/ticket", $data->id)}}';">
                            <td>{{$data->id}}</td>
                            <td title="{{$data->ticket__title}}">{{mb_substr($data->ticket__title,0,25, "utf-8")}}
                                ...
                                @if($data->seen__id == 0) <span class="no-read">خوانده نشده</span>
                                @endif</td>
                            <td>{{$data->user->company__name}}</td>
                            <td>
                                @if($data->ticket__priority == 'high')
                                    <span class="table-status table-no-pay">زیاد</span>
                                @elseif($data->ticket__priority == 'normal')
                                    <span class="table-status table-answered">متوسط</span>
                                @elseif($data->ticket__priority == 'low')
                                    <span class="table-status table-closed">کم</span>
                                @endif
                            </td>
                            <td>--</td>
                            <td>
                                @if($data->ticket__status == "pending")
                                    <span class="table-status table-pending">در انتظار پاسخ</span>
                                @elseif($data->ticket__status == "answered")
                                    <span class="table-status table-answered">پاسخ داده شده</span>
                                @elseif($data->ticket__status == "closed")
                                    <span class="table-status table-closed">بسته شده</span>
                                @elseif($data->ticket__status == "doing")
                                    <span class="table-status table-doing">در حال پیگیری</span>
                                @elseif($data->ticket__status == "finished")
                                    <span class="table-status table-finished">به پایان رسیده</span>
                                @elseif($data->ticket__status == "unpaid")
                                    <span class="table-status table-no-pay">پرداخت نشده</span>
                                @elseif($data->ticket__status == "paid")
                                    <span class="table-status table-answered">پرداخت شده</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div style=" direction: ltr; width: 100%; margin-top: 3rem;text-align: center; ">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection