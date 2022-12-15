@extends('user.master')
@section('content')

    <form action="{{ route('user.work-update',$item->id) }}" method="post" class="mt-5">

        <div class="container">
            <div class="card product-card-large w-100">
                <div class="card-header bg-warning">
                    <h6 class="lh-base">ثبت پیش نویس قرارداد</h6>
                </div>
                
                <div class="card-body border-top border-color">                        
    
                    <div class="form-group-grid {{ $errors->has('title') ? 'has-error' : '' }}">
                        <label for="subject" class="form-label"> عنوان</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title',$item->title) }}" required/>
                        @if ($errors->has('title'))
                            <span class="help-block"><strong>{{$errors->first('title')}}</strong></span>
                        @endif
                    </div>
    
                    <div class="form-group-grid {{ $errors->has('referrer_id') ? 'has-error' : '' }}">
                        <label for="referrer_id" class="form-label"> چه کسی به شما کار سپرده است</label>
                        <select name="referrer_id" id="referrer_id" class="form-control select" required>
                            <option>انتخاب کنید</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}" {{ old('referrer_id',$item->referrer_id)==$user->id?'selected':'' }}>{{$user->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('referrer_id'))
                            <span class="help-block"><strong>{{$errors->first('referrer_id')}}</strong></span>
                        @endif
                    </div>
    
                    <div class="form-group-grid {{ $errors->has('company_id') ? 'has-error' : '' }}">
                        <label for="company_id" class="form-label"> نام شرکت ( بر روی سایت یا اپ کدام شرکت میخواهید کار کنید؟ )</label>
                        <select name="company_id" id="company_id" class="form-control select" required>
                            <option>انتخاب کنید</option>
                            @foreach($companies as $user)
                                <option value="{{$user->id}}" {{ old('company_id',$item->company_id)==$user->id?'selected':'' }}>{{$user->company__name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('company_id'))
                            <span class="help-block"><strong>{{$errors->first('company_id')}}</strong></span>
                        @endif
                    </div>
    
                    <div class="form-group-grid {{ $errors->has('type') ? 'has-error' : '' }}">
                        <label for="type" class="form-label"> نوع کار</label>
                        <select name="type" id="type" class="form-control select" required>
                            <option>انتخاب کنید</option>
                            @foreach($types as $type)
                                <option value="{{$type}}" {{ old('type',$item->type)==$type?'selected':'' }}>{{$type}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('type'))
                            <span class="help-block"><strong>{{$errors->first('type')}}</strong></span>
                        @endif
                    </div>
    
                    <div class="form-group-grid {{ $errors->has('description') ? 'has-error' : '' }}">
                        <label for="time" class="form-label"> توضیحات (باید واضح و قابل پیگیری باشد)</label>
                        <textarea name="description" id="description" class="form-control" cols="30" rows="5" required>{{ old('description',$item->description) }}</textarea>
                        @if ($errors->has('description'))
                            <span class="help-block"><strong>{{$errors->first('description')}}</strong></span>
                        @endif
                    </div>
                    {{csrf_field()}}
    
                </div>
    
                <div class="card-footer border-top border-color">
                    <div class="row mb-0">
                        <div class="col">
                            <button type="submit" class="btn btn-success">ویرایش کار</button>
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
