@extends('user.master')
@section('content')
    <section class="col-12 mt-5 px-2">
        <div class="card res_table" style="background: transparent;">
            <div class="card-header">
                <h3 class="card-title float-right mt-2">درخواست ها</h3>
                <a href="#" href="javascript:void(0);" data-toggle="modal" data-target="#ModalTicket" class="float-left btn btn-info">
                    ارسال درخواست
                    <i class="fa fa-plus"></i>
                </a>
            </div>
            @if(count($items)>0)
                @foreach($items as $item)
                    <div class="radius20 bg-white card-body res_table_in m-2 p-3 redu20">
                        <div class="d-flex">
                            <h6 class="my-2">{{$item->subject}}</h6>
                            <span class="text-dark px-2 small">
                                @if ($item->mobile>0)
                                    {{$item->mobile.' مکالمه '}}
                                @else
                                    در انتظار پاسخ    
                                @endif
                            </span>
                        </div>
                        <a href="{{route('user.show-ticket',$item->id)}}" class="btn btn-primary col-12 mt-3">نمایش همه</a>
                    </div>
                @endforeach
            @else
                <div colspan="3" class="text-center">موردی یافت نشد</div>
            @endif
        </div>
        <div class="pag_ul">
            {{ $items->links() }}
        </div>

        <!-- Modal send ticket -->
        <div class="modal fade" id="ModalTicket" role="dialog">
            <div class="modal-dialog">

                <div class="modal-content redu20"> 
                    <div class="modal-header">
                        <h4 class="modal-title">ارسال درخواست</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="content mt-0">
                            <form method="post" action="{{route('user.contact.post')}}" enctype="multipart/form-data">
                                @csrf
                                <fieldset>
                                    {{-- <div class="form-field form-text">
                                        <label class="contactMessageTextarea color-theme" for="category">واحد مربوطه</label>
                                        <select id="category" name="category" class="form-control mb-4 select2">
                                            @foreach (\App\Model\Role::pluck('name') as $key => $item)
                                                @unless ($item == 'مدیر ارشد' || $item == 'کاربر')
                                                    <option value="{{$item}}" @if($key == 0) selected @endif>{{$item}}</option>
                                                @endunless
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="form-field form-text">
                                        <label class="contactMessageTextarea color-theme" for="subject">نوع درخواست</label>
                                        <select id="subject" name="subject" onchange="changeInput()" class="form-control mb-4 select2">
                                            {{-- <option value="درخواست پاداش" selected>پاداش</option> --}}
                                            <option value="درخواست مساعده" selected>مساعده</option>
                                            <option value="درخواست مرخصی" >مرخصی</option>
                                            <option value="درخواست تنخواه">تنخواه</option>
                                            {{-- <option value="درخواست محاسبه ساعت کار">محاسبه ساعت کار</option> --}}
                                            <option value="درخواست ثبت گزارش کار">ثبت گزارش کار</option>
                                        </select>
                                    </div>
                                    {{-- <div class="form-field form-text">
                                        <label class="contactEmailField color-theme" for="contactEmailField">موضوع:<span>(required)</span></label>
                                        <input type="text" name="subject" class="round-small col-12" id="contactEmailField">
                                    </div> --}}
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
                                    <div class="form-field form-text">
                                        <label class="contactMessageTextarea color-theme" for="text">متن:<span>(required)</span></label>
                                        <textarea name="text" class="round-small mb-0" id="text"></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label class="contactMessageTextarea color-theme" for="attach">الحاق فایل:</label>
                                        <input type="file" name="attach" id="attach" class="form-control">
                                    </div>
                                    <div class="form-button">
                                        <input type="submit" class="btn btn-info col-12" value="ارسال پیام" data-formid="contactForm">
                                    </div>
                                </fieldset> 
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </section>

    <script>
        function changeInput() {
            let req = document.getElementById("subject").value; 
            if (req=='درخواست مرخصی') {
                document.getElementById("perDate").style.display = "block";
                document.getElementById("lorem-box").style.display = "block";
            } else if(req=='درخواست ثبت گزارش کار') {
                document.getElementById("lorem-box").style.display = "none";
                document.getElementById("perDate").style.display = "block";
            } else {
                document.getElementById("perDate").style.display = "none";
                document.getElementById("lorem-box").style.display = "none";
            }
        }
    </script>
@endsection
