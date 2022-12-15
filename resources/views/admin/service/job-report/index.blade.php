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
                            {{-- <div class="float-left">
                                <button class="btn btn-info float-left" data-toggle="modal" data-target="#ModalTicket">محاسبه حقوق کارمندان</button>
                            </div> --}}
                            <button type="button" class="btn btn-dark float-left" data-toggle="modal" data-target="#exampleModal">
                                @if(isset($id)) {{$users->where('id',$id)->first()?$users->where('id',$id)->first()->first_name.' '.$users->where('id',$id)->first()->last_name:$id}} @else فیلترکردن بر اساس کاربران @endif
                                <i class="fa fa-search"></i>
                            </button>
                            <div class="float-right">
                                <button class="btn btn-light px-3" onclick="ExportToExcel('xlsx')"><i class="fa fa-file-excel-o"></i></button>
                                <button class="btn btn-light px-3 mx-2" onclick="generatePDF()"><i class="fa fa-file-pdf-o"></i></button>
                            </div>
                        </div>
                        <div class="card-body res_table_in">
                            <table id="example2" class="table table-bordered table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>کارمند</th>
                                    <th>{{$title2}}</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($items)>0)
                                    @foreach($items as $item)
                                        <tr >
                                            <td>{{$item->first_name.' '.$item->last_name}}</td>
                                            <td>{{$item->setJob()->count().' فعالیت دارد '}}</td>
                                            <td class="text-center">
                                                <a href="{{route('admin.job-report.show',$item->id)}}" class="badge bg-primary">بررسی </a>
                                            </td> 
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
                    <div class="pag_ul">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">فیلترکردن بر اساس کاربران</h5>
                </div>
                <div class="modal-body">
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if(isset($id)) {{$users->where('id',$id)->first()?$users->where('id',$id)->first()->first_name.' '.$users->where('id',$id)->first()->last_name:$id}} @else کاربر انتخاب کنید @endif
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <input class="form-control" id="myInput" type="text" placeholder="کاربر را جستحو کنید">
                            @foreach($users as $user)
                                <li style="padding: 6px;"><a class="text-dark" href="{{route('admin.job-report.filter',$user->id)}}" title="انتخاب کاربر">{{$user->first_name.' '.$user->last_name}}</a></li>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade mt-5" id="ModalTicket" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content redu20"> 
                <div class="modal-header">
                    <h4 class="modal-title">لطفا بازه زمانی را انتخاب کنید</h4>
                </div>
                <div class="modal-body">
                    <div class="content mt-0">
                        <form method="post" action="{{route('admin.job-report.store')}}" enctype="multipart/form-data">
                            @csrf
                            <fieldset>
                                <div class="row mb-0">
                                    <div class="form-field form-text col-lg-6">
                                        <label class="contactMessageTextarea color-theme" for="date">از تاریخ</label>
                                        <input type="text" name="date" class="round-small mb-0 date_p" id="date" required>
                                    </div>
                                    <div class="form-field form-text col-lg-6">
                                        <label class="contactMessageTextarea color-theme" for="date">تا تاریخ</label>
                                        <input type="text" name="date2" class="round-small mb-0 date_p" id="date2" required>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                    <div class="form-button col-lg-6">
                                        <button type="button" class="btn btn-secondary  col-12 mt-3" data-dismiss="modal">بستن</button>
                                    </div>
                                    <div class="form-button col-lg-6">
                                        <input type="submit" class="btn btn-info col-12 mt-3" value="محاسبه" data-formid="contactForm">
                                    </div>
                                </div>
                            </fieldset> 
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div> --}}

@endsection
@section('js')
<script>
    $('.date_p').persianDatepicker({
        observer: true,
        format: 'YYYY/MM/DD',
        altField: '.observer-example-alt',
        initialValue:false,
    });
        $(document).ready(function () {
            $('select[name=state_id]').on('change', function () {
                $.get("{{url('/')}}/city-ajax/" + $(this).val(), function (data, status) {
                    $('select[name=city_id]').empty();
                    $.each(data, function (key, value) {
                        $('select[name=city_id]').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $('select[name=city_id]').trigger('change');
                });
            });
        });
</script>
@endsection