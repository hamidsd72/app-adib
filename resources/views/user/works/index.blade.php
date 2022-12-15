@extends('user.master')
@section('content')

    <div class="container mt-5 text-center">
        @if ($items->count())
            <p class="text-secondary my-2"> 
                <span class="text-dark">{{auth()->user()->works()->count()}} کار</span> برای انجام دادن
            </p>
            <a class="fs-6" href="{{route('user.work-create')}}"> + ایجاد کار جدید </a>
        @endif
        <div class="row mt-3">
            @foreach($items as $work)
                @php
                    $workTimesheet_doing=\App\Model\WorkTimesheet::WorkTimeSheetByStatus('work',$work->id,'doing');
                    $workTimesheet_finished=\App\Model\WorkTimesheet::WorkTimeSheetByStatus('work',$work->id,'finished');
                    $workTimesheet_paused=\App\Model\WorkTimesheet::WorkTimeSheetByStatus('work',$work->id,'paused');
                @endphp
                <div class="col-lg-6">
                    <div class="card product-card-large w-100 mb-4">
                        <div class="card-header">
                            <div class="row mb-0">
                                <div class="col-auto">
                                    <h6 class="lh-base">{{$work->title }}</h6>
                                </div>
                                <div class="col-auto">
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body border-top border-color">                        
                            <div class="row mb-0">
                                <div class="col-auto ml-auto">
                                    
                                    <p class="small text-secondary m-0">ارجاع دهنده : {{ $work->referrer?$work->referrer->name:'نامشخص' }}</p>
                                    <span class="text-secondary">
                                        نام شرکت : {{ $work->company?$work->company->company__name:'نامشخص' }}
                                    </span>

                                </div>
                            </div>
                            <button class="btn mt-2 m-lg-0" data-bs-toggle="collapse" data-bs-target="#demo{{$work->id}}"><i class="far fa-clone"></i></button>
                            <div id="demo{{$work->id}}" class="collapse">{!! nl2br($work->description) !!}</div>
                        </div>

                        <div class="card-footer border-top border-color">
                            <div class="row mb-0">
                                <div class="col my-auto">
                                    @if($workTimesheet_doing)
                                        {{-- <form action="{{route('user.work-stop')}}" method="post" class="m-0">
                                            <button type="submit" class="btn btn-danger py-0">اتمام <i class="fa fa-refresh fa-spin mx-1"></i></button>
                                            <input type="hidden" value="{{$work->id}}" name="id">
                                            {{ csrf_field() }}
                                        </form> --}}
                                        <button onclick="document.getElementById('runningJobStoped').click();" class="btn btn-danger py-0">اتمام کار<i class="fa fa-refresh fa-spin mx-1"></i></button>
                                    @elseif($workTimesheet_finished)
                                        <form action="{{route('user.timesheet-store')}}" method="post" class="m-0">
                                            <button type="submit" class="btn btn-success py-0">ادامه </button>
                                            <input type="hidden" value="{{$work->id}}" name="type_id">
                                            <input type="hidden" value="work" name="type">
                                            {{ csrf_field() }}
                                        </form>
                                    @else
                                        <form action="{{route('user.timesheet-store')}}" method="post" class="m-0">
                                            <button type="submit" class="btn btn-primary py-0">{{ $workTimesheet_paused?'ادامه':'شروع' }}</button>
                                            <input type="hidden" value="{{$work->id}}" name="type_id">
                                            <input type="hidden" value="work" name="type">
                                            {{ csrf_field() }}
                                        </form>
                                    @endif
                                </div>
                                <div class="col">
                                    <a href="{{route('user.work-edit',$work->id)}}" class="btn btn-warning py-0">ویرایش</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $items->links() }}
    </div>
    
@endsection

