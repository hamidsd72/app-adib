@extends('layouts.panel')
@section('styles')
    <style>
    </style>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">
                {{ $visit->name }}
            </div>
            <div class="panel-body">

                <p>{{$visit->title}}</p>

                <div style="min-height: {{$visit->status==10 ? '240px' : '220px'}};border-top-left-radius: 0;border-bottom-left-radius: 0;background: #908c81 !important;color: #fff !important;" class="col-md-7 alert-default">
                    <div class="col-sm-6"><i class="fa fa-user"></i> نام مشتری
                        : {{ $visit->user_id!=0 ? $visit->user->name : $visit->user_name }}
                    </div>
                    <div class="col-sm-6"><i class="fa fa-user"></i> نام مشتری
                        : {{ $visit->expert ? $visit->expert->name : 'تعیین نشده' }}
                    </div>
                    <div class="col-sm-6"><i class="fa fa-phone"></i> شماره تماس
                        : {{ $visit->user_id!=0 ? $visit->user->company__phone : $visit->user_mobile }}
                    </div>
                    <div class="col-sm-6"><i class="fa fa-clock-o"></i> زمان ایجاد
                        : {{my_jdate($visit->created_at,'Y/m/j H:i')}}
                    </div>
                    <div class="col-sm-6"><i class="fa fa-clock-o"></i> تاریخ بازدید
                        : {{ str_replace(',','/',$visit->visit_date) }}
                    </div>
                    <div class="col-sm-6"><i class="fa fa-clock-o"></i> نوع بازدید
                        : {{ $visit->type ? 'ادواری' : 'اورژانسی'}}
                    </div>
                    <div class="col-sm-6"><i class="fa fa-bar-chart"></i>
                            وضعیت بازدید :
                        @php
                            switch ($visit->status){
                                case 0:
                                    echo '<span class="alert alert-success">فعال</span>';
                                    break;
                                case 10:
                                    echo '<span style="background-color: #eaff9f;color: #444444;" class="alert">بایگانی شده</span>';
                                    break;
                                default:
                                    echo 'نامشخص';
                                    break;
                            }
                        @endphp
                    </div>
                    <div class="col-sm-6"><i class="fa fa-user"></i> کد بازدید :
                        {{ $visit->code }}
                    </div>
                    <div class="col-sm-12"><i class="fas fa-paperclip"></i> فایل (های) پیوست :
                        @if(count($visit->libraries))
                            @foreach($visit->libraries as $key => $file)
                                <a style="background: #fff;border-radius: 20px;padding: 5px 10px 5px 10px;text-decoration: none;font-size: 10px" target="_blank" href="{{ url($file->file__path) }}">
                                    مشاهده پیوست {{ $key+1 }}
                                    <i class="fas fa-cloud-download-alt"></i>
                                </a>
                            @endforeach
                        @else
                            <span style="border-radius: 20px;padding: 1px 10px;background: #fff;" class="text-danger">
                                ندارد
                            <i class="fas fa-not-equal"></i>
                            </span>
                        @endif
                    </div>
                </div>
                <div style="padding: 20px 20px;text-align: justify;min-height: {{$visit->status==10 ? '240px' : '220px'}};border-top-right-radius: 0;border-bottom-right-radius: 0;background: #908c81 !important;color: #fff !important;" class="col-md-5 alert-default">
                <p>توضیحات مربوطه :</p>
                    {{ $visit->description }}
                </div>
                {{-- done Jobs --}}
                @if($visit->expert_id==Auth::user()->id)
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="row" style="margin-top: 20px;background: #b1e0d4;padding: 10px 0px;">
                            <form action="{{url('panel/visit_done_job_store')}}" method="post" enctype="multipart/form-data"
                                  class="send_comment form-horizontal">
                                <input type="hidden" value="{{ $visit->id }}" name="id">
                                {{ csrf_field() }}
                                <div class="col-sm-2 text-left">
                                    <button class="btn btn-submit" type="submit">ثبت</button>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="description" value="{{ old('description') }}" placeholder="توضیحات" required>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="عنوان کار" required>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
                @if(count($visit->doneJob))
                    @foreach($visit->doneJob as $key=>$job)
                        <div class="col-md-12" style="margin-top: 10px;">
                            <span class="badge badge-pill badge-primary">{{$job->title}}</span>
                            <div style="padding: 10px;background: {{ $key/2==0 ? 'rgb(239, 237, 209) none repeat scroll 0% 0%' : '#eee' }};border-radius: 5px;border-right: 4px solid #ccc7c7;position:relative;padding-left: 30px">
                                <span class="badge badge-pill" style="position:absolute;left: 10px;top: 10px;background: rgb(203, 203, 203) none repeat scroll 0% 0%;color: #fff;">{{ $key+1 }}</span>
                                {{ $job->description }}
                            </div>
                        </div>
                    @endforeach
                @endif
                {{-- take an comment --}}
                <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top: 50px;">
                    <div class="row">

                        <form action="{{url('panel/visit_comment_store')}}" method="post" enctype="multipart/form-data"
                              class="send_comment form-horizontal">
                            <fieldset>
                                <input type="hidden" name="visit__id" value="{{$visit->id}}">
                                <input type="hidden" name="visit__status" value="answered">
                                <div class="form-group{{ $errors->has('comment__content') ? ' has-error' : '' }}">
                                    <div class="col-md-12">
                                        <label for="comment__content" class="form-label">پاسخ جدید :</label>
                                        <textarea id="comment__content" class="form-control" name="comment__content"
                                                  rows="10">{{old('comment__content')}}</textarea>
                                        @if ($errors->has('comment__content'))
                                            <span class="help-block"><strong>{{$errors->first('comment__content')}}</strong></span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('comment__attachment') ? ' has-error' : '' }}">
                                    <div class="col-md-12">
                                        <label for="comment__attachment" class="form-label">پیوست :</label>
                                        <p class="ticket__type">پسوندهای مجاز: .jpg, .gif, .jpeg, .png, .txt, .pdf,
                                            .zip, .rar</p>
                                        <input id="comment__attachment" type="file" name="comment__attachment[]"
                                               class="form-control" multiple/>
                                        @if ($errors->has('comment__attachment'))
                                            <span class="help-block"><strong>{{$errors->first('comment__attachment')}}</strong></span>
                                        @endif
                                    </div>
                                </div>

                                {{csrf_field()}}

                                <button type="submit" class="btn btn-labeled-left"><i class="fa fa-check"></i> ارسال
                                </button>

                            </fieldset>
                        </form>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 padding-0">
                    <div class="row">
                        <div class="ticket-message">

                            @foreach($visit->comments as $comment)

                                        <div class="adminticket">
                                            <div class="adminheader"><h5 style="background: #5486FF !important;"><i class="fa fa-comments" aria-hidden="true"></i> <span class="pull-left">#{{ $comment->id }}</span>
                                                    {{$comment->user ? $comment->user->name : 'نامشخص'}}</h5></div>
                                            <div class="adminmsg">
                                                <p>{!! html_entity_decode(nl2br($comment->comment)) !!}</p>
                                                @if(isset($comment->libraries)) <p class="text-center">تعداد فایل های پیوست
                                                    : {{$comment->libraries->count()}}</p>
                                                @foreach($comment->libraries as $library)
                                                    <div class="comment_attach"><i class="fa fa-paperclip"></i> <a
                                                                href="{{url($library->file__path)}}" target="_blank">مشاهده
                                                            فایل پیوست</a></div>
                                                @endforeach
                                                @endif
                                                <hr>
                                            </div>
                                            <div class="ticket-footer clearfix">
                                                <div class="tickets-timestamp"><i class="fa fa-clock-o"
                                                                                  aria-hidden="true"></i>
                                                    @include('partials.comment-jdate')
                                                    ({{$comment->created_at->format('H:i')}})
                                                </div>
                                            </div>
                                        </div>

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
