@extends('user.master')
@section('content')

    <form action="{{ route('user.work-store') }}" method="post" enctype="multipart/form-data" class="mt-5">

        <div class="container">
            <div class="card product-card-large w-100">
                <div class="card-header bg-warning">
                    <h6 class="lh-base">ثبت پیش نویس قرارداد</h6>
                </div>
                
                <div class="card-body border-top border-color">                        
    
                    <div class="mb-2 {{ $errors->has('user__id') ? 'has-error' : '' }}">
                        <label for="subject" class="form-label"> عنوان</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required/>
                        @if ($errors->has('title'))
                            <span class="help-block"><strong>{{$errors->first('title')}}</strong></span>
                        @endif
                    </div>
    
                    <div class="mb-2 {{ $errors->has('user__id') ? 'has-error' : '' }}">
                        <label for="referrer_id" class="form-label"> چه کسی به شما کار سپرده است</label>
                        <select name="referrer_id" id="referrer_id" class="form-control select2" >
                            @foreach($users as $key => $user)
                                <option value="{{$user->id}}" {{ $key==0?'selected':'' }} >{{$user->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('referrer_id'))
                            <span class="help-block"><strong>{{$errors->first('referrer_id')}}</strong></span>
                        @endif
                    </div>
    
                    <div class="mb-2 {{ $errors->has('user__id') ? 'has-error' : '' }}">
                        <label for="company_id" class="form-label"> نام شرکت ( بر روی سایت یا اپ کدام شرکت میخواهید کار کنید؟ )</label>
                        <select name="company_id" id="company_id" class="form-control select2" >
                            @foreach($companies as $index => $user)
                                <option value="{{$user->id}}" {{ $index==0?'selected':'' }} >{{$user->company__name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('company_id'))
                            <span class="help-block"><strong>{{$errors->first('company_id')}}</strong></span>
                        @endif
                    </div>
    
                    <div class="mb-2 {{ $errors->has('user__id') ? 'has-error' : '' }}">
                        <label for="type" class="form-label"> نوع کار</label>
                        <select name="type" id="type" class="form-control select2" >
                            @foreach($types as $type)
                                <option value="{{$type}}" {{ $type=='work'?'selected':'' }}>{{$type}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('type'))
                            <span class="help-block"><strong>{{$errors->first('type')}}</strong></span>
                        @endif
                    </div>
    
                    <div class="{{ $errors->has('user__id') ? 'has-error' : '' }}">
                        <label for="time" class="form-label"> توضیحات (باید واضح و قابل پیگیری باشد)</label>
                        <textarea name="description" id="description" class="form-control" cols="30" rows="4" required>{{ old('description') }}</textarea>
                        @if ($errors->has('description'))
                            <span class="help-block"><strong>{{$errors->first('description')}}</strong></span>
                        @endif
                    </div>
                    {{csrf_field()}}
    
                </div>
    
                <div class="card-footer border-top border-color">
                    <div class="row mb-0">
                        <div class="col">
                            <button type="submit" class="btn btn-success">افزودن کار</button>
                        </div>
                        <div class="col-auto">
                            <a href="{{ URL::previous() }}" class="btn btn-secondary">برگشت</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

@endsection
