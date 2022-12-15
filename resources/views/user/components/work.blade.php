<div class="card product-card-small mb-0" style="box-shadow: none;">
    <div class="card-body pt-0">
        <div class="p-2 ps-3 border redu30">
            <div class="row mb-0">
                <div class="col-lg">
                    <p class="m-0 fs-6">{{$work->title }}</p>
                    <p class="m-0"><span class="text-dark">ارجاع دهنده : {{ $work->referrer?$work->referrer->name:'نامشخص' }}</span></p>
                    <p class="small vm m-0">نام شرکت : {{ $work->company?$work->company->company__name:'نامشخص' }}</p>
                </div>
                <div class="col-lg-4">
                    <div class="row my-0">
                        <div class="col-6 col-lg-12 my-1 text-center">
                            @if($workTimesheet_doing)
                                <form action="{{route('user.work-stop')}}" method="post" class="m-0">
                                    <button type="submit" class="btn btn-danger py-0">اتمام کار<i class="fa fa-refresh fa-spin mx-1"></i></button>
                                    <input type="hidden" value="{{$work->id}}" name="id">
                                    {{ csrf_field() }}
                                </form>
                            @elseif($workTimesheet_finished)
                                <form action="{{route('user.timesheet-store')}}" method="post" class="m-0">
                                    <button type="submit" class="btn btn-success py-0">ادامه کار</button>
                                    <input type="hidden" value="{{$work->id}}" name="type_id">
                                    <input type="hidden" value="work" name="type">
                                    {{ csrf_field() }}
                                </form>
                            @else
                                <form action="{{route('user.timesheet-store')}}" method="post" class="m-0">
                                    <button type="submit" class="btn btn-primary py-0">{{ $workTimesheet_paused?'ادامه کار':'شروع کن' }}</button>
                                    <input type="hidden" value="{{$work->id}}" name="type_id">
                                    <input type="hidden" value="work" name="type">
                                    {{ csrf_field() }}
                                </form>
                            @endif
                        </div>
                        <div class="col-6 col-lg-12 my-1 text-center">
                            <a href="{{route('user.package',$work->id)}}" class="btn btn-warning py-0">ویرایش کار</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn mt-2 m-lg-0" data-bs-toggle="collapse" data-bs-target="#demo{{$work->id}}"><i class="far fa-clone"></i></button>
            <div id="demo{{$work->id}}" class="collapse">{!! nl2br($work->description) !!}</div>
        </div>
    </div>
</div>