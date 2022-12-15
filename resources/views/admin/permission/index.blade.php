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
                            <h3 class="card-title float-right">{{$title2}}</h3>
                        </div>
                        <div class="card-body res_table_in">
                            <table id="example2" class="table table-bordered table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>سمت</th>
                                    <th>بخش کاربران
                                        <i class="nav-icon fa fa-group"></i>
                                    </th>
                                    <th>بخش اعلانات
                                        <i class="nav-icon fa fa-smile-o"></i>
                                    </th>
                                    <th>بخش فعالیتها
                                        <i class="nav-icon fa fa-handshake-o"></i>
                                    </th>
                                    <th>بخش گزارشات
                                        <i class="nav-icon fa fa-pie-chart"></i>
                                    </th>
                                    <th>بخش محتوا
                                        <i class="nav-icon fa fa-cogs"></i>
                                    </th>
                                    <th>بخش تنظیمات
                                        <i class="nav-icon fa fa-cog"></i>
                                    </th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>
                                            <p class="small my-0">{{$item->name}}</p>
                                            {{$item->description}}
                                            <div style="dispaly: none">{{ $permission = $permissionList->where('name',$item->id)->first()?$permissionList->where('name',$item->id)->first()->access:'' }}</div>
                                        </td>
                                        {{ Form::open(array('route' => ['admin.permission.store'], 'method' => 'POST', 'files' => true)) }}

                                            <input type="hidden" name="id" value="{{$item->id}}">
                                            <td>
                                                <label class="switch-wrap switch-success ml-2">
                                                    <input name="کاربران" type="checkbox" {{ in_array('کاربران', explode(",", $permission) )?'checked':'' }} >
                                                    <div class="switch"></div>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch-wrap switch-success ml-2">
                                                    <input name="اعلانات" type="checkbox" {{ in_array('اعلانات', explode(",", $permission) )?'checked':'' }}>
                                                    <div class="switch"></div>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch-wrap switch-success ml-2">
                                                    <input name="فعالیتها" type="checkbox" {{ in_array('فعالیتها', explode(",", $permission) )?'checked':'' }}>
                                                    <div class="switch"></div>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch-wrap switch-success ml-2">
                                                    <input name="گزارشات" type="checkbox" {{ in_array('گزارشات', explode(",", $permission) )?'checked':'' }}>
                                                    <div class="switch"></div>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch-wrap switch-success ml-2">
                                                    <input name="محتوا" type="checkbox" {{ in_array('محتوا', explode(",", $permission) )?'checked':'' }}>
                                                    <div class="switch"></div>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch-wrap switch-success ml-2">
                                                    <input name="تنظیمات" type="checkbox" {{ in_array('تنظیمات', explode(",", $permission) )?'checked':'' }}>
                                                    <div class="switch"></div>
                                                </label>
                                            </td>
                                            <td>
                                                <div class="row mb-0">
                                                    {{ Form::button('ثبت دسترسی', array('type' => 'submit', 'class' => 'btn btn-success col-auto mx-1')) }}
                                                
                                                    {{ Form::close() }}
    
                                                    @if ($permissionList->where('name',$item->id)->first())
                                                        {{ Form::open(array('route' => ['admin.permission.destroy',$permissionList->where('name',$item->id)->first()->id], 'method' => 'DELETE', 'files' => true)) }}
                                                            {{ Form::button(' بازنشانی دسترسی ', array('type' => 'submit', 'class' => 'btn btn-secondary col-auto mx-1')) }}
                                                        {{ Form::close() }}
                                                    @endif

                                                </div>
                                            </td>
                                            <div style="display: none">{{$permission=''}}</div>

                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('js')
@endsection