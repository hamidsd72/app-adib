@extends('layouts.panel')
@section('content')
<!-- Modal -->
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="{{ route('visit_update',$item->id) }}" method="POST" id="addUser">
                <div class="form-group">
                    <label for="title">عنوان بازدید</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="عنوان بازدید"
                           value="{{ $item->title }}" required>
                </div>
                <div class="form-group">
                    <label for="type">نوع بازدید</label>
                    <select name="type" id="type" class="form-control selectpicker" data-live-search="true"
                            required>
                        <option value="1" {{ $item->type==1 ? 'selected' : '' }}>ادواری</option>
                        <option value="2"  {{ $item->type==2 ? 'selected' : '' }}>اورژانسی</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="user_id">مشتری</label>
                    <select name="user_id" id="user_id" class="form-control selectpicker" data-live-search="true"
                            required>
                        <option value="0">بدون انتخاب</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $item->user_id==$customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                <label for="expert_id">کارشناس مربوطه</label>
                    <select name="expert_id" id="expert_id" class="form-control selectpicker" data-live-search="true" required>
                        <option value="0">بدون انتخاب</option>
                    @foreach($experts as $expert)
                        <option value="{{ $expert->id }}" {{ $item->expert_id==$expert->id ? 'selected' : '' }}>{{ $expert->name }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="user_name">نام مشتری (اختیاری)</label>
                    <input type="text" name="user_name" id="user_name" class="form-control"
                           placeholder="نام مشتری" value="{{ $item->user_name }}">
                </div>
                <div class="form-group">
                    <label for="user_mobile">شماره تماس مشتری (اختیاری)</label>
                    <input type="number" name="user_mobile" id="user_mobile" class="form-control"
                           placeholder="شماره تماس" value="{{ $item->user_mobile }}">
                </div>
                <div class="form-group{{ $errors->has('expire') ? ' has-error' : '' }}">
                    <label for="visit_date">تاریخ بازدید</label>
                    <input id="datepicker" placeholder="تاریخ بازدید" type="text" value="{{ $item->visit_date }}" class="form-control" name="visit_date"/>
                    @if ($errors->has('visit_date'))
                        <span class="help-block"><strong>{{$errors->first('visit_date')}}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="description">توضیحات</label>
                    <textarea name="description" class="form-control" id="description" cols="30" rows="10"
                              placeholder="توضیحات">{{ $item->description }}</textarea>
                </div>
                <div class="form-group">
                    {{ csrf_field() }}
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-info">ثبت و ارجاع</button>
                </div>
            </form>
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
@endsection
@endsection
