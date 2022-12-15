@extends('layouts.panel')
@section('content')
<!-- Modal -->
<div class="modal fade" id="comments" tabindex="-1" role="dialog" aria-labelledby="comments" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="help_comments" style="text-align: center">
                </h4>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add_user" tabindex="-1" role="dialog" aria-labelledby="add_user" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="search"><i class="fa fa-search"></i> درخواست مساعده</h5>
            </div>
            <div class="modal-body" style="display: flow-root;">
                <form action="{{ route('help_store') }}" method="POST" id="addUser">
                    <div class="form-group">
                        <label for="description">متن مساعده</label>
                        <textarea name="description" class="form-control" id="description" cols="30" rows="10"
                                  placeholder="توضیحات"></textarea>
                    </div>
                    <div class="form-group factor_price">
                        <label for="description" style="width: 100%">مبلغ درخواستی</label>
                        <input style="width: 90%;float: right;border-top-left-radius: 0;border-bottom-left-radius: 0;" type="text" placeholder="قیت نهایی" id="price_format" class="form-control" required>
                        <span style="width: 10%;display: inline-block;float: right;height: 36px;text-align: center;background: #9485ad;color: #fff;line-height: 36px;border-top-left-radius: 20px;border-bottom-left-radius: 20px;font-weight: bold;">تومان</span>
                        <input type="hidden" class="form-control final_price" name="final_price" required>
                    </div>
                    <div class="form-group">
                        {{ csrf_field() }}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بیخیال نمیخوام</button>
                <button type="button" class="btn btn-primary" onclick="$('#addUser').submit();">ثبت بازدید</button>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-sidebar panel-heading">{{$title}}<span
                    class="pull-left">تعداد : {{ $helps->count() }}</span></div>
        <div class="panel-body">
            <button class="btn btn-sm btn-success pull-left" data-toggle="modal" data-target="#add_user"
                    style="margin-right: .5rem"><i class="fa fa-user"></i> افزودن بازدید
            </button>
            <table class="table datatable-responsive22 table-togglable">
                <thead>
                <tr>
                    <th data-toggle="true">متن درخواست</th>
                    <th data-toggle="true">مبلغ درخواستی</th>
                    <th data-hide="phone">وضعیت</th>
                    {{--@if(Auth::user()->role_id==1)--}}
                    {{--<th data-hide="phone">وضعیت</th>--}}
                    {{--@endif--}}
                </tr>
                </thead>
                <tbody>
                @foreach($helps as $help)
                <tr class="text-center">
                    <td onclick="document.location = '{{url("panel/help-show", $help->id)}}';">{{ $help->description }}</td>
                    <td onclick="document.location = '{{url("panel/help-show", $help->id)}}';"><span class="price">{{ $help->price }}</span> تومان</td>
                    <td>
                        {!! help_status($help->status) !!}
                    </td>
                    {{--@if(Auth::user()->role_id==1)--}}
                    {{--<td>--}}
                        {{--<a href="{{ route('helps_edit',$help->id) }}">--}}
                            {{--<i class="fa fa-pencil"></i>--}}
                        {{--</a>--}}
                    {{--</td>--}}
                    {{--@endif--}}
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@section('scripts')
<script>

    $('#accessory_chk').click(function () {
        if($(this).is(':checked')){
            $('#accessory').show();  // checked
        } else{
            $('#accessory').hide();
        }
    })
</script>
<script>
    $('.btn_delivery').click(function () {
        let id=$(this).attr('data-id');
        $('#deliveryForm').attr('action','{{ url('') }}'+'/panel/set-delivery/'+id);
    })
</script>
<script>
    $(document).ready(function () {

        $('#price_format').keyup(function () {
            let price_val=$(this).val();
            $(this).val(function (index, value) {

                return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            });
            let price_split=price_val.replace(/,/g, "");
            $('.final_price').val(price_split);
        })

    });
</script>
<script>
    $(document).ready(function () {

            $('.price').text(function (index, value) {
                $('.price').text(value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            });
    });
</script>
@endsection
@endsection
