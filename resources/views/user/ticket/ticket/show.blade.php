@extends('user.master')
@section('content')

   <style> img.img-user { width: 100px; height: 100px; border-radius: 50%; }</style>

   <div class="container mt-5 pt-5">
      <div class="card">
         <div class="card-header bg-warning" style="border-radius: 15px 15px 0px 0px;">{{ $data->ticket__type=='invoices'?'وضعیت فاکتور':'وضعیت تیکت'}} :</div>
         <div class="card-body">

            <p class="fs-6">{{$data->ticket__title}}</p>
            
            <div class="redu10 bg-info p-2 text-dark">
               <i class="fa fa-clock-o"></i> زمان ایجاد : {{my_jdate($data->created_at,'d F Y').' '.$data->created_at->format('H:i')}} <br>
               <i class="fa fa-clock-o"></i> آخرین بروزرسانی : {{my_jdate($data->updated_at,'d F Y').' '.$data->updated_at->format('H:i')}} <br>
               <i class="fa fa-bar-chart"></i> {{ $data->ticket__type=='invoices'?'وضعیت فاکتور':'وضعیت تیکت'}} : {{$data->ticket__status}} <br>
               <i class="fa fa-user"></i> بخش :{{$data->role()->description}}
            </div>

            @if($data->user->contracts->count())
               <div class="bg-warning redu10 my-3 p-2">
                  @foreach($data->user->contracts as $contract)
                     <div class="py-1 border-bottom">
                        @php $date = Carbon\Carbon::now()->diffInDays(Carbon\Carbon::parse($contract->expire), false);@endphp
                        زمان باقی مانده از قرارداد : 
                        @if($date<=0)
                           <span class="text-dark">{{ abs($date) }} روز گذشته و منقضی شده</span>
                        @else
                           <span class="text-success">{{ $date }} روز از قرارداد مانده</span>
                        @endif
                        <div>
                           وضعیت :
                           @if($contract->active == 1)
                              <span class="bg-success text-light redu20 px-1">فعال</span>
                           @elseif($contract->active == 2)
                              <span class="bg-danger text-light redu20 px-1">غیرفعال</span>
                           @endif
                           <span class="bg-light redu20 px-1">{{ $contract->type }}</span>
                        </div>
                     </div>
                  @endforeach
               </div>
            @endif

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 my-3">
               @php
                  $workTimesheet_doing=\App\Model\WorkTimesheet::WorkTimeSheetByStatus('ticket',$data->id,'doing');
                  $workTimesheet_finished=\App\Model\WorkTimesheet::WorkTimeSheetByStatus('ticket',$data->id,'finished');
                  $workTimesheet_paused=\App\Model\WorkTimesheet::WorkTimeSheetByStatus('ticket',$data->id,'paused');
               @endphp
               @if($data->ticket__status != 'closed' and $data->ticket__status != 'unpaid')
                  <div class="d-flex">

                     @if (Auth::user()->id == 111 or Auth::user()->id == 177 or Auth::user()->role_id == 8)
                        <a href="{{url('ticket_closed', $data->id)}}" class="btn btn-danger mx-1" onclick="return confirm('تیکت بسته شود ؟')">
                           <i class="fa fa-power-off"></i><div class="d-none d-md-block">بستن تیکت</div>
                        </a>
                     @endif

                     <a href="{{url('ticket_doing', $data->id)}}" class="btn btn-info mx-2">
                        <i class="fa fa-rocket"></i><div class="d-none d-md-block">در حال پیگیری</div>
                     </a>

                     @if(auth()->user()->role_id==1 or auth()->user()->role_id==9 or auth()->user()->id==10000099)
                        <a href="javascript:void(0)" class="btn btn-dark" data-toggle="modal" data-target="#reference">
                           <i class="fa fa-reply-all"></i><div class="d-none d-md-block">ارجاع تیکت</div>
                        </a>
                     @endif

                     @if(auth()->user()->role_id==1 or auth()->user()->role_id==9)
                        <a href="javascript:void(0)" class="btn btn-dark mx-2" data-toggle="modal" data-target="#reference_move">
                           <i class="fa fa-reply-all"></i><div class="d-none d-md-block">انتقال تیکت</div>
                        </a>
                     @endif
                  </div>
               @endif
               
               <div class="d-flex my-3">
                  @if($data->ticket__type == 'invoices')
                     <a href="{{url('invoice_confirm', $data->id)}}" class="btn btn-warning mx-1"><i class="fa fa-check"></i> تایید فاکتور</a>
                  @endif

                  @if($workTimesheet_doing)
                     <div class="col-auto">
                        <button" class="btn btn-dark"><i class="far fa-hourglass mx-1"></i>در حال انجام </button>
                     </div>
                  @elseif($workTimesheet_finished)
                     <form action="{{url('timesheet-store')}}" method="post" id="selectProject-form">
                        <button type="submit" class="btn btn-primary">
                           <i class="far fa-play-circle mx-1"></i>از سرگیری مجدد</button>
                        <input type="hidden" value="{{$data->id}}" name="type_id">
                        <input type="hidden" value="ticket" name="type">
                        {{ csrf_field() }}
                     </form>
                  @else
                     <form action="{{url('timesheet-store')}}" method="post" id="selectProject-form">
                        <button type="submit" class="btn btn-success">
                           <i class="far fa-play-circle mx-1"></i>{{ $workTimesheet_paused?'ادامه کار':'شروع کن' }}</button>
                        <input type="hidden" value="{{$data->id}}" name="type_id">
                        <input type="hidden" value="ticket" name="type">
                        {{ csrf_field() }}
                     </form>
                  @endif

               </div>

               @if($data && $data->user)
                  <a target="_blank" href="{{$data->user->company__site}}" class="btn btn-secondary">
                     <i class="fa fa-web"></i>{{ $data->user->company__site }}</a>
               @endif

            </div>

            <form action="{{url('comment_store')}}" method="post" enctype="multipart/form-data" class="shadow redu20 my-4 p-2">
               <input type="hidden" name="ticket__id" value="{{$data->id}}">
               <input type="hidden" name="ticket__status" value="answered">

               <div class="form-group{{ $errors->has('comment__content') ? ' has-error' : '' }}">
                  <label for="comment__content" class="form-label">پاسخ جدید :</label>
                  <textarea id="comment__content" class="form-control" name="comment__content" rows="8">{{old('comment__content')}}</textarea>
                  @if ($errors->has('comment__content'))
                     <span class="help-block"><strong>{{$errors->first('comment__content')}}</strong></span>
                  @endif
               </div>

               <div class="form-group{{ $errors->has('hour') ? ' has-error' : '' }}">
                  <label for="hour" class="form-label mt-2">مجموع دقایق کاری :</label>
                  <input id="hour" type="number" class="form-control" name="hour" placeholder="زمان کار را به عدد لاتین وارد کنید" required/>
                  @if ($errors->has('hour'))
                     <span class="help-block"><strong>{{$errors->first('hour')}}</strong></span>
                  @endif
               </div>

               <div class="form-group{{ $errors->has('comment__attachment') ? ' has-error' : '' }}">
                  <label for="comment__attachment" class="form-label">پیوست :</label>
                  <p class="ticket__type mb-2">پسوندهای مجاز: .jpg, .gif, .jpeg, .png, .txt, .pdf,.zip, .rar</p>
                  <input id="comment__attachment" type="file" name="comment__attachment[]" class="form-control" multiple/>
                  @if ($errors->has('comment__attachment'))
                     <span class="help-block"><strong>{{$errors->first('comment__attachment')}}</strong></span>
                  @endif
               </div>
               
               {{csrf_field()}}
               <button type="submit" class="btn btn-{{ auth()->user()->role_id==4 && !$workTimesheet_doing?'dark':'success' }} col-12 mt-3 " {{ auth()->user()->role_id==4 && !$workTimesheet_doing?'disabled':'' }}>
                  <i class="fa fa-check mx-1"></i>ارسال
               </button>
            </form>

            <div class="ticket-message">
               @foreach($data->comments->reverse()->sortByDesc('updated_at') as $comment)

                  @if(isset($comment->user))
                     @if($comment->user->role()->id == 5)
                        <div class="card bg-light redu10 p-1 p-lg-3">

                           <div class="card-header border-bottom">
                              <i class="fa fa-comments mx-1" aria-hidden="true"></i>
                              <span class="float-end">#{{ $comment->id }}</span>
                              {{isset($comment->user)?$comment->user->name:''}}
                              {{isset($comment->user)?'شرکت : ':$comment->user->company__name}}
                           </div>

                           <div class="card-body border-bottom">
                              <div class="p-1 py-3 p-lg-3">{!! html_entity_decode(nl2br($comment->comment__content)) !!}</div>
                              @if(isset($comment->libraries))
                                 <p class="text-center">تعداد فایل های پیوست : {{$comment->libraries->count()}}</p>
                                 @foreach($comment->libraries as $library)
                                    <div class="comment_attach"><i class="fa fa-paperclip"></i>
                                       <a href="https://support.adib-it.com/{{$library->file__path}}" target="_blank">مشاهده فایل پیوست</a>
                                    </div>
                                 @endforeach
                              @endif
                           </div>

                           <div class="card-footer">
                              <i class="fa fa-clock-o mx-1"></i>
                              {{my_jdate($comment->created_at,'d F Y').' - '.$comment->created_at->format('H:i')}}
                           </div>

                        </div>
                     @else
                        <div class="card bg-light redu10 p-1 p-lg-3">

                           <div class="card-header border-bottom bg-secondary text-light">
                              <i class="fa fa-comments" aria-hidden="true"></i>
                              <span class="float-end">#{{ $comment->id }}</span>ادیب ( پشتیبان )
                              {!! $comment->confirmation==1?'':"<span class='bg-warning redu10 p-1 p-lg-2'>در انتظار تایید</span>" !!}
                              @if($comment->confirmation==0 && auth()->user()->role()->id==1)
                                    <a href="#" onclick="activeComments('{{$comment->id}}')" class="btn btn-success">تایید</a>
                                    <button data-id="{{$comment->id}}" data-text="{{$comment->comment__content}}" class='btn btn-primary ms-2'>ویرایش</button>
                              @endif
                           </div>

                           <div class="card-body border-bottom">
                              <div class="d-flex">
                                 <img class="img-user" src="{{url( $comment->user->profile ?
                                    is_file($comment->user->profile) ? url($comment->user->profile) : 'https://https://support.adib-it.com/'.$comment->user->profile
                                     : 'https://img.icons8.com/ultraviolet/100/000000/test-account.png' )}}" alt="{{$comment->user->name}}">
                                 <div class="ms-3 fs-6 my-auto">
                                    {{$comment->user->name}} <br>
                                    <small>{{my_jdate($comment->created_at,'d F Y').' - '.$comment->created_at->format('H:i')}}</small>
                                 </div>
                              </div>
                              <div class="p-1 py-3 p-lg-3">{!! html_entity_decode(nl2br($comment->comment__content)) !!}</div>
                              @if(isset($comment->libraries))
                                 <p class="text-center">تعداد فایل های پیوست : {{$comment->libraries->count()}}</p>
                                 @foreach($comment->libraries as $library)
                                    <div class="comment_attach"><i class="fa fa-paperclip"></i>
                                       <a href="https://support.adib-it.com/{{$library->file__path}}" target="_blank">مشاهده فایل پیوست</a>
                                    </div>
                                 @endforeach
                              @endif
                           </div>

                           <div class="card-footer">
                              <div class="d-flex">
                                 <img src="{{ url($setting->icon_site)}}" alt="{{ $setting->title }}" width="100px">
                                 <div class="ms-3">
                                    <h6 class="mt-2 text-dark">{{$comment->user->name}}</h6>
                                    <small class="fw-bold text-dark">{{$comment->user->role()->description}}</small>
                                 </div>
                              </div>
                           </div>

                        </div>
                     @endif
                  @endif

               @endforeach

               @if($data->ticket__type != 'invoices')

                  @if(isset($data->user))

                     @if($data->send__id == 0)

                        <div class="card bg-light redu10 p-1 p-lg-3">

                           <div class="card-header border-bottom">
                              <i class="fa fa-comments" aria-hidden="true"></i>
                              <span class="float-end">#{{ $data->id }}</span>
                              {{$data->user->name}} (شرکت : {{$data->user->company__name}} )
                           </div>

                           <div class="card-body border-bottom">
                              <div class="p-1 py-3 p-lg-3">{!! html_entity_decode(nl2br($data->ticket__content)) !!}</div>
                              @if(isset($data->libraries))
                                 <p class="text-center">تعداد فایل های پیوست : {{$data->libraries->count()}}</p>
                                 @foreach($data->libraries as $library)
                                    <div class="comment_attach">
                                       <i class="fa fa-paperclip mx-1"></i>
                                       <a href="https://support.adib-it.com/{{$library->file__path}}" target="_blank">مشاهده فایل پیوست</a>
                                    </div>
                                 @endforeach
                              @endif
                           </div>

                           <div class="card-footer">
                              <i class="fa fa-clock-o" aria-hidden="true"></i>
                              {{my_jdate($data->created_at,'d F Y').' '.$data->created_at->format('H:i')}}
                           </div>
                        </div>

                     @elseif($data->send__id == 1)

                        <div class="card bg-light redu10 p-1 p-lg-3">

                           <div class="card-header border-bottom bg-dark text-light">
                              <i class="fa fa-comments mx-1"></i>
                              <span class="float-end">#{{ $data->id }}</span> ادیب ( پشتیبان )
                           </div>
                           
                           <div class="card-body border-bottom">
                              <div class="p-1 py-3 p-lg-3">{!! html_entity_decode(nl2br($data->ticket__content)) !!}</div>
                              @if(isset($data->libraries))
                                 <p class="text-center">تعداد فایل های پیوست : {{$data->libraries->count()}}</p>
                                 @foreach($data->libraries as $library)
                                    <div class="comment_attach">
                                       <i class="fa fa-paperclip mx-1"></i>
                                       <a href="https://support.adib-it.com/{{$library->file__path}}" target="_blank">مشاهده فایل پیوست</a>
                                    </div>
                                 @endforeach
                              @endif
                           </div>

                           <div class="card-footer">
                              <i class="fa fa-clock-o mx-1"></i>
                              {{my_jdate($data->created_at,'d F Y').' '.$data->created_at->format('H:i')}}
                           </div>
                        </div>

                     @endif

                  @else
                     <div class="card bg-light redu10 p-1 p-lg-3">

                        <div class="card-header border-bottom bg-dark text-light">
                           <i class="fa fa-comments mx-1"></i>
                           <span class="float-end">#{{ $data->id }}</span> ادیب ( پشتیبان )
                        </div>

                        <div class="card-body border-bottom">
                           <div class="p-1 py-3 p-lg-3">{!! html_entity_decode(nl2br($data->ticket__content)) !!}</div>
                           @if(isset($data->libraries))
                              <p class="text-center">تعداد فایل های پیوست : {{$data->libraries->count()}}</p>
                              @foreach($data->libraries as $library)
                                 <div class="comment_attach">
                                    <i class="fa fa-paperclip mx-1"></i>
                                    <a href="https://support.adib-it.com/{{$library->file__path}}" target="_blank">مشاهده فایل پیوست</a></div>
                              @endforeach
                           @endif
                        </div>
                        
                        <div class="card-footer">
                           <i class="fa fa-clock-o mx-1"></i>
                           {{my_jdate($data->created_at,'d F Y').' '.$data->created_at->format('H:i')}}
                        </div>

                     </div>
                  @endif

               @endif
            </div>
         
         </div>
      </div>
   </div>

   <!-- Modal -->
   <div id="reference" class="modal">
      <div class="modal-dialog">
         <div class="modal-content mt-5">
            <form action="{{url('reference', $data->id)}}">

               <div class="modal-header">
                  <button type="button" class="close pull-left" data-bs-dismiss="modal">&times;</button>
                  <h4 class="modal-title">ارجاع تیکت به کاربر دیگر</h4>
               </div>
               
               <div class="modal-body">
                  <div class="form-group{{ $errors->has('role__id') ? ' has-error' : '' }}">
                     <label for="user__id" class="form-label">برای کاربر :</label>
                     <select id="user__id" name="user__id" class="select" data-placeholder="کاربر مربوطه را انتخاب کنید">
                        <option value="">کاربر مربوطه را انتخاب کنید</option>
                        @if(isset($merged_users1500))
                           @foreach($merged_users1500 as $merged_users)
                              <option value="{{ $merged_users->id }}">{{ $merged_users->name }} {{$merged_users->role()->description}}</option>
                           @endforeach
                        @endif
                     </select>

                     @if ($errors->has('role__id'))
                        <span class="help-block"><strong>{{$errors->first('role__id')}}</strong></span>
                     @endif
                  </div>

                  <button type="button" class="btn btn-secondary float-end mt-3" data-bs-dismiss="modal">بستن</button>
                  <button type="submit" class="btn btn-success mt-3">ارجاع</button>
               </div>
            </form>
         </div>
      </div>
   </div>

   <!-- Modal -->
   <div id="reference_move" class="modal">
      <div class="modal-dialog mt-5">
         <div class="modal-content">
            <form action="{{url('reference-move', $data->id)}}">

               <div class="modal-header">
                  <button type="button" class="close pull-left" data-bs-dismiss="modal">&times;</button>
                  <h4 class="modal-title">انتقال تیکت به بخش دیگر</h4>
               </div>
               
               <div class="modal-body">
                  <div class="form-group{{ $errors->has('role__id') ? ' has-error' : '' }}">
                     <label for="user__id" class="form-label">برای بخش :</label>
                     <select id="user__id" name="role__id" class="select" data-placeholder="بخش مربوطه را انتخاب کنید">
                        <option value="">بخش مربوطه را انتخاب کنید</option>
                        @if(isset($roles))
                           @foreach($roles as $role)
                              <option value="{{ $role->id }}">{{ $role->description }}</option>
                           @endforeach
                        @endif
                     </select>
                     @if ($errors->has('role__id'))
                        <span class="help-block"><strong>{{$errors->first('role__id')}}</strong></span>
                     @endif
                  </div>

                  <button type="button" class="btn btn-secondary float-end mt-3" data-bs-dismiss="modal">بستن</button>
                  <button type="submit" class="btn btn-success mt-3">انتقال</button>
               </div>
               
            </form>
         </div>
      </div>
   </div>

   <!-- Modal -->
   <div id="hasDone" class="modal">
      <div class="modal-dialog">
         <div class="modal-content">
            <form action="{{url('ticket_finished')}}" method="POST" >

               <div class="modal-header">
                  <button type="button" class="close pull-left" data-bs-dismiss="modal">&times;</button>
                  <h4 class="modal-title">کارشناس گرامی لطفا اطلاعات را وارد کنید</h4>
               </div>
               
               <div class="modal-body">
                  <input type="hidden" name="ticket_id" value="{{$data->id}}"/>
                  <input type="hidden" name="company_id" value="{{$data->user__id}}"/>
                  <input type="hidden" name="user_id" value="{{auth()->user()->id}}"/>
                  
                  <div class="form-group{{ $errors->has('hour') ? ' has-error' : '' }}">
                     <label for="hour" class="form-label">مجموع دقایق کاری برای این تیکت :</label>
                     <input id="hour" type="number" class="form-control" name="hour" value="{{old('hour')}}"/>
                     @if ($errors->has('hour'))
                        <span class="help-block"><strong>{{$errors->first('hour')}}</strong></span>
                     @endif
                  </div>
                  {{csrf_field()}}
               </div>

               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                  <button type="submit" class="btn btn-success">انجام شد</button>
               </div>
            
            </form>
         </div>
      </div>
   </div>

   {{-- edit comment --}}
   <div id="editComment" class="modal">
      <div class="modal-dialog">
         <div class="modal-content">
            <form action="{{url('comment-update')}}" method="post" id="editComment-form">

               <div class="modal-body">
                  <textarea id="comment__text" class="form-control" name="comment__text" rows="10"></textarea>
                  {{ csrf_field() }}
               </div>

               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                  <button type="submit" class="btn btn-success">ویرایش و تایید</button>
               </div>
               
            </form>
         </div>
      </div>
   </div>

   <a href="#" id="clickToOpenModalActiveComment" class="d-none" data-bs-toggle="modal" data-bs-target="#active_comment">تایید</a>
   <!-- active comment -->
   <div id="active_comment" class="modal" role="dialog">
      <div class="modal-dialog mt-5">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close float-end" data-bs-dismiss="modal">&times;</button>
               <h4 class="modal-title text-success">تائید پاسخ کاربر</h4>
            </div>

            <form action="{{url('p-comment-confirm')}}" method="post" id="active_comment_form">
               {{ csrf_field() }}

               <div class="modal-body">
                  <div class="form-group{{ $errors->has('role__id') ? ' has-error' : '' }}">
                     <label for="user__id" class="form-label">نوع تائید :</label>
                     <select id="status" name="status" class="select text-success" data-placeholder="نوع تائید">
                        <option value="answered">تایید و اتمام کار</option>
                        <option value="waiting_answered">تایید و ادامه کار</option>
                        {{--<option value="">تائید و منتظر پاسخ</option>--}}
                     </select>
                  </div>

                  <button type="button" class="btn btn-secondary mt-4 float-end" data-bs-dismiss="modal">بستن</button>
                  <button type="submit" class="btn btn-success mt-4" >تایید</button>
               </div>
            </form>
            
         </div>
      </div>
   </div>

   <script>
      $(function(){
          $("input[id='hour']").on('input', function (e) {
              $(this).val($(this).val().replace(/[^0-9]/g, ''));
          });
      });
  </script>
   <script>
      function activeComments($id) {
         document.getElementById("active_comment_form").action = `p-comment-confirm/${$id}`;
         document.getElementById('clickToOpenModalActiveComment').click();
      }
   </script>

@endsection
@section('scripts')
   <script>
      $('.edit-comment').click(function () {
         let text = $(this).data('text');
         let id = $(this).data('id');
         $('#comment__text').html(text);
         $('#editComment').modal('show');
         $('#editComment-form').attr('action', `comment-update/${id}`);
      });
      $('.confirm-comment').click(function () {
            let id = $(this).data('id');
            $.ajax({
               url: `comment-confirm/${id}`,
               method: 'GET',
               success: function (data) {
                  if (data == 'true') {
                        $('.confirmation-status').hide();
                        $('.confirm-comment').hide();
                        $('.confirm-edit').hide();
                  }
               }
            })
      })
   </script>
@endsection

