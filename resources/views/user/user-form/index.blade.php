@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <section class="content">
        <div class="card res_table">
            <div class="card-body p-0">
                @foreach($items as $item)
                    <div class="border-bottom p-4">
                        <div class="small">
                            @if ($item->form_type=='قرارداد عمومی')
                                وبینار
                                @if ($item->visa_type)
                                    حقوقی
                                    {{' ویزای '.$item->visa_type}}
                                @else
                                    ویزا
                                @endif
                            @else
                                {{$item->form_type}}
                            @endif
                        </div>
                        <div class="my-2 small">{{$item->first_name,' ',$item->last_name }}</div>
                        <div class="small">{{$item->status ?? 'درحال بررسی' }}</div>
                        <a href="{{route('user.forms.edit',$item->id)}}" class="col-12 btn btn-info mt-3" >مشاهده فرم</a>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="pag_ul">
            {{ $items->appends(request()->query())->links() }}
        </div>
    </section>

@endsection
@section('js')
@endsection
