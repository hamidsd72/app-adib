@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card res_table">
                        <div class="card-header bg-zard">
                            <h3 class="card-title float-right">{{$title2}}</h3>
                        </div>
                        <div class="card-body res_table_in">
                            <table id="example2" class="table table-bordered table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>کاربر</th>
                                    <th>زمان کارکرد فعالیت</th>
                                    <th>محاسبه حقوق ساعتی</th>
                                    <th>جمع کل</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($items)>0)
                                    @foreach($items as $item)
                                        <tr >
                                            <td>{{$item->first_name.' '.$item->last_name}}</td>
                                            {{-- فعالیت ها --}}
                                            <td>
                                                <div class="d-none">{{$sum=0}}
                                                    @foreach($item->referrer_code as $job)
                                                        @if ($job->job()->work_type=='ساعتی')
                                                            {{$sum += intval( ( $job->job()->price * $item->reagent_id->where('job_id',$job->job_id)->sum('time') ) / 60 )}}
                                                        @elseif ($job->job()->work_type=='پیمانکاری')
                                                            {{$sum += $job->job()->price}}
                                                        @endif
                                                    @endforeach
                                                </div>
                                                {{number_format($sum).' تومان '}}
                                            </td>
                                            {{-- حقوق ساعتی --}}
                                            <td>{{intval($item->referrer_id / 60).':'.($item->referrer_id % 60).' زمان '.number_format( intval(($item->reagent_code  * $item->referrer_id) / 60) ).' تومان '}}</td>
                                            <td>{{number_format($sum + intval(($item->reagent_code  * $item->referrer_id) / 60) ).' تومان '}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">موردی یافت نشد</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('js')
@endsection