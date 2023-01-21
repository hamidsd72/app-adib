@extends('user.master')
@section('content')
    <section class="col-12 mt-5 p-3">
        <div class="card res_table" style="background: transparent;">
            <div class="card-header">
                <h3 class="card-title float-right mt-2">درخواست ها </h3>
                <a href="#" href="javascript:void(0);" data-toggle="modal" data-target="#ModalTicket" class="float-left btn btn-info">
                    ادامه
                    <i class="fa fa-plus"></i>
                </a>
            </div>
            <div class="row mb-0">
                @if($item->answered == "yes")
                    <div class="col p-0"></div>
                @endif
                <div class="col-8 p-0 radius20 bg-white card-body res_table_in m-2 p-3 redu20">
                    <div class="card-body res_table_in py-0 redu20">
                        <p class="mb-2">
                            وضعیت : 
                            @if($item->answered == "no")
                                @if($item->reply>0)
                                    <span class="reply_email_ok text-dark">پاسخ داده شده</span>
                                @else
                                    <span class="reply_email_no">در انتظار پاسخ</span>
                                @endif
                            @else
                                <span class="reply_email_no">پاسخ درخواست</span>
                            @endif
                            {{-- <span class="mx-4 text-dark">{{$item->category}}</span> --}}
                        </p>
                        <h4>{{$item->subject}}
                            @if ($item->date){{' از '.explode(",",$item->date)[0].' تا '.explode(",",$item->date)[1]}}@endif
                        </h4>
                        <p class="py-2 m-0">{{$item->text}}</p>
                        @if ($item->attach)
                            <a class="text-primary" href="/{{ $item->attach }}" target="_blank">
                                <i class="fa fa-paperclip"></i>
                                مشاهده فایل پیوست شده
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col p-0"></div>
            </div>
            @foreach($items as $sub_item)
                <div class="row mb-0">
                    @if($sub_item->answered == "yes")
                        <div class="col p-0"></div>
                    @endif
                    <div class="col-8 p-0 radius20 bg-white card-body res_table_in m-2 p-3 redu20">
                        <div class="card-body res_table_in py-0 redu20">
                            <p class="mb-2">
                                وضعیت : 
                                @if($sub_item->answered == "no")
                                    @if($sub_item->reply>0)
                                        <span class="reply_email_ok text-dark">پاسخ داده شده</span>
                                    @else
                                        <span class="reply_email_no">در انتظار پاسخ</span>
                                    @endif
                                @else
                                    <span class="reply_email_no">پیام از واحد {{$sub_item->category}}</span>
                                @endif
                            </p>
                            <h4>{{$item->subject}}
                                @if ($item->date){{' از '.explode(",",$item->date)[0].' تا '.explode(",",$item->date)[1]}}@endif
                            </h4>
                            <p class="py-2 m-0">{{$sub_item->text}}</p>
                            @if ($sub_item->attach)
                                <a class="text-primary" target="_blank" href="/{{ $sub_item->attach }}" >
                                    <i class="fa fa-paperclip"></i>
                                    مشاهده فایل پیوست شده
                                </a>
                            @endif
                        </div>
                    </div>
                    @unless($sub_item->answered == "yes")
                        <div class="col p-0"></div>
                    @endunless
                </div>
            @endforeach
        </div>
        <div class="pag_ul">
            {{ $items->links() }}
        </div>

        <div class="modal fade" id="ModalTicket" role="dialog">
            <div class="modal-dialog">

                <div class="modal-content redu20"> 
                    <div class="modal-header">
                        <h4 class="modal-title">{{$item->subject}}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="content mt-0">
                                <form method="post" action="{{route('user.contact.post')}}" enctype="multipart/form-data">
                                    @csrf
                                <fieldset>
                                    <input type="hidden" name="belongs_to_item" value="{{$item->id}}" id="contactbelongs_to_itemField">
                                    {{-- <input type="hidden" name="category" value="{{$serviceCat->id??''}}" id="category_to_itemField"> --}}
                                    <input type="hidden" name="subject" value="{{$item->subject}}" id="contactEmailField">
                                    @if ($item->subject=='درخواست مرخصی' || $item->subject=='درخواست ثبت گزارش کار')
                                        <div class="col-12" id="perDate">
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <div class="form-field form-text">
                                                        <label class="contactMessageTextarea color-theme" for="date">از تاریخ</label>
                                                        <div class="row mb-0">
                                                            <div class="col-10">
                                                                <input type="text" name="date" class="col-12 round-small mb-0 date_p" id="date">
                                                            </div>
                                                            <div class="col-2">
                                                                <img class="float-left" src="https://img.icons8.com/external-icematte-lafs/28/000000/external-Calendar-it-icematte-lafs.png">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-field form-text">
                                                        <label class="contactMessageTextarea color-theme" for="date">تا تاریخ</label>
                                                        <div class="row mb-0">
                                                            <div class="col-10">
                                                                <input type="text" name="date2" class="col-12 round-small mb-0 date_p" id="date">
                                                            </div>
                                                            <div class="col-2">
                                                                <img class="float-left" src="https://img.icons8.com/external-icematte-lafs/28/000000/external-Calendar-it-icematte-lafs.png">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($item->subject=='درخواست مرخصی')
                                            <div id="lorem-box">
                                                <div class="form-field form-text">
                                                    <label class="contactMessageTextarea color-theme" for="lorem2">زمان مرخصی</label>
                                                    <select id="lorem2" name="lorem2" id="lorem2" class="form-control mb-3">
                                                        <option value=" - مرخصی روزانه " selected>روزانه</option>
                                                        <option value=" - مرخصی ساعتی ">ساعتی</option>
                                                        <option value=" - مرخصی استعلاجی ">استعلاجی</option>
                                                    </select>
                                                </div>
                                                <div class="form-field form-text">
                                                    <label class="contactMessageTextarea color-theme" for="lorem3">نوع مرخصی</label>
                                                    <select id="lorem3" name="lorem3" id="lorem3" class="form-control mb-3">
                                                        <option value=" با حقوق " selected>با حقوق</option>
                                                        <option value=" بدون حقوق ">بدون حقوق</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="form-field form-text">
                                        <label class="contactMessageTextarea color-theme" for="contactMessageTextarea">متن:<span>(required)</span></label>
                                        <textarea name="text" class="round-small mb-2" id="contactMessageTextarea"></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label class="contactMessageTextarea color-theme" for="contactMessageTextarea">الحاق فایل:</label>
                                        <input type="file" name="attach" id="attach" class="form-control">
                                    </div>
                                    <div class="form-button">
                                        <input type="submit" class="btn btn-info col-12 mt-2" value="ارسال پیام" data-formid="contactForm">
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
    </section>



@endsection
