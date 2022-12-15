@extends('layouts.admin')
@section('css')
<style>
    
</style>
@endsection
@section('content')

    <div class="container">
        <div class="col-md-12">
        {{--<button data-target="#createPackage" data-toggle="modal" class="btn btn-green pull-left btn-sm">ایجاد پکیج</button>--}}
        </div>

        <div class="card bg-light p-3">
            <form action="{{route('admin.workhour-fetch')}}" method="get" class="row mb-0" enctype="multipart/form-data">
    
                <div class="form-group col-lg-3 col-md {{ $errors->has('to_date') ? ' has-error' : '' }}">
                    <label for="from_date" class="form-label">از تاریخ :</label>
                    <input id="from_date" type="text" class="form-control date_p" name="from_date"/>
                    @if ($errors->has('from_date'))
                        <span class="help-block"><strong>{{$errors->first('from_date')}}</strong></span>
                    @endif
                </div>
    
                <div class="form-group col-lg-3 col-md {{ $errors->has('to_date') ? ' has-error' : '' }}">
                    <label for="to_date" class="form-label">تا تاریخ :</label>
                    <input id="to_date" type="text" class="form-control date_p" name="to_date"/>
                    @if ($errors->has('to_date'))
                        <span class="help-block"><strong>{{$errors->first('to_date')}}</strong></span>
                    @endif
                </div>
    
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> مشاهده</button>
                </div>
    
            </form>
        </div>

        @foreach($users as $key => $user)
            <div class="card">
                <div class="card-header">
                    <h6 class="float-right">{{ $user->name }}</h6>
                    <span class="text-dark float-left">ساعت شروع به کار :
                        <?php
                            $today = \Carbon\Carbon::now();
                            $date = $today->format('Y-m-d');
                                //           dd($user,$date);
                            if (count($user->startHour)) {
                                //            dd($user->startHour->where('startDate', $date));
                                if (count($user->startHour->where('startDate', $date))) {
                                    echo $user->startHour->where('startDate', $date)->first()->startTime;
                                } else {
                                    echo 'بدون ورود';
                                }
                            }
                        ?>
                    </span>
                    {{--<a href="{{ url('panel/phase_create',$data->id) }}" class="btn btn-green pull-left btn-sm">ثبت فاز</a>--}}
                    {{--<a href="javascript:void(0)" data-id="{{$data->id}}" data-title="{{ $data->title }}" class="btn btn-green pull-left btn-sm edit-package ml-2">ویرایش پکیج</a>--}}
                    {{--<a href="{{ url('panel/package-destroy',$data->id) }}" data-id="{{$data->id}}" data-title="{{ $data->title }}" class="btn btn-danger pull-left btn-sm edit-package ml-2">حذف پکیج</a>--}}
                </div>
                <div class="card-footer">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>کار</th>
                                <th>شروع کار</th>
                                <th>پایان کار</th>
                                <th>مجموع ساعات (مفید)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $today_minutes=0; @endphp
                            @foreach($user->workTimesheet as $item)
                                {{--@if($key==4)--}}
                                {{--@dd($user->workTimesheet,$item->circle)--}}
                                {{--@endif--}}
                                {{--                                                    @php--}}
                                {{--                                                        $minutes=$item->getPassedMinutes($item);--}}
                                {{--                                                        $today_minutes+=$minutes;--}}
                                {{--                                                        $hour=intval($minutes/60);--}}
                                {{--                                                        $minute=$minutes-$hour*60;--}}
                                {{--                                                        $workHour=$hour.' ساعت و '.$minute.' دقیقه ';--}}
                                {{--                                                    @endphp--}}
                                @php
                                    $st=Carbon\Carbon::parse($item->startDate.' '.$item->startTime);
                                    $et=Carbon\Carbon::parse($item->startDate.' '.$item->endTime?$item->endTime:$time);
                                    $minutes=0;
                                @endphp
                                @if(count($item->circle))
                                    @php
                                        foreach ($item->circle as $index=>$row){
                                            // if its first circle
                                            $paused_at=Carbon\Carbon::parse($row->paused_at);
                                            if (count($item->circle)==1){
                                                $minutes+=$st->diffInMinutes($paused_at);
                                                $resumed_at=Carbon\Carbon::parse($row->resumed_at);
                                                $minutes+=$et->diffInMinutes($resumed_at);
                                            } else {
                                                if ($index==0){
                                                    $minutes+=$paused_at->diffInMinutes($st);
                                                } else {
                                                    if ($index==count($item->circle)-1){
                                                        $resumed_at=Carbon\Carbon::parse($item->circle[$index-1]->resumed_at);
                                                        $minutes+=$paused_at->diffInMinutes($resumed_at);
                                                        $resumed_at=Carbon\Carbon::parse($row->resumed_at);
                                                        $minutes+=$et->diffInMinutes($resumed_at);
                                                    } else {
                                                        $resumed_at=Carbon\Carbon::parse($item->circle[$index-1]->resumed_at);
                                                        $minutes+=$paused_at->diffInMinutes($resumed_at);
                                                        /* dd($item->circle[$index-1]->resumed_at.'-'.$paused_at);*/
                                                    }
                                                }
                                            }
                                        }
                                    @endphp
                                @else
                                    @php $minutes+=$et->diffInMinutes($st); @endphp
                                @endif
                                @php
                                    $today_minutes+=$minutes;
                                    $hour=intval($minutes/60);
                                    $minute=$minutes-$hour*60;
                                    $workHour=$hour.' ساعت و '.$minute.' دقیقه ';
                                @endphp
                                <tr>
                                    <td onclick="window.open('{{url( $item->getRoute($item->type,$item->type_id))}}', '_blank')">
                                        <span class="table-status table-answered pull-right">{{ $item->getTypeColumns($item->type,$item->type_id)['type'] }}</span>
                                        {{ $item->getTypeColumns($item->type,$item->type_id)['title'] }}
                                    </td>
                                    <td>{{$item->startTime}}</td>
                                    <td style="position:relative;">
                                        {{$item->endTime}}
                                        @if(!$item->endTime)
                                            <img src="{{asset('assets/icons8-spinning-circle.gif')}}" style="width: 28px;height: 28px;border-radius: 50%;" alt="spinning-circle">
                                        @endif
                                    </td>
                                    <td>{{ $workHour }}</td>
                                </tr>
                            @endforeach
                            @php
                                $hour=intval($today_minutes/60);
                                $minute=$today_minutes-$hour*60;
                                $workHours=$hour.' ساعت و '.$minute.' دقیقه ';
                            @endphp
                            <tr>
                                <td colspan="4">مجموع ساعات کاری امروز : {{ $workHours }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

    </div>
@endsection