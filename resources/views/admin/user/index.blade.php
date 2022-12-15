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
                                    <a href="{{route('admin.user.create')}}" class="float-left btn btn-info">
                                        افزودن <i class="fa fa-plus"></i>
                                    </a>
                                <div class="float-right">
                                    <button class="btn btn-light px-3" onclick="ExportToExcel('xlsx')"><i class="fa fa-file-excel-o"></i></button>
                                    <button class="btn btn-light px-3 mx-2" onclick="generatePDF()"><i class="fa fa-file-pdf-o"></i></button>
                                </div>
                            </div>
                            <div class="card-body res_table_in">
                                <table id="example2" class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>نام کامل</th>
                                        <th>نام شرکت</th>
                                        <th>شماره شرکت</th>
                                        <th>شماره مدیر شرکت</th>
                                        <th>شماره نماینده شرکت</th>
                                        @if ( auth()->user()->role_id==1 )
                                            <th>سمت</th>
                                        @endif
                                        <th>عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($items)>0)
                                    @foreach($items as $item)
                                        <tr>
                                            <td>@item($item->name)</td>
                                            <td>@item($item->company__name)</td>
                                            <td>@item($item->company__phone)</td>
                                            <td>@item($item->company__manager_phone)</td>
                                            <td>@item($item->company__representative_phone)</td>
                                            @if ( auth()->user()->role_id==1 )
                                                <td>{{$item->role()->description}}
                                                    <a href="javascript:void(0);" class="badge bg-info mx-1" data-toggle="modal" data-target="#role{{$item->id}}"><i class="fa fa-edit"></i> </a>
                                                </td>
                                            @endif
                                            <td class="text-center">
                                                @if ( auth()->user()->role_id==1 )
                                                    <a href="{{route('admin.user.fastLogin',$item->id)}}" class="badge bg-warning ml-1" title="ورود سریع"><i class="fa fa-arrow-circle-o-left"></i></a>
                                                @endif
                                                <a href="{{route('admin.user.show',$item->id)}}" class="badge bg-info ml-1" title="پروفایل"><i class="fa fa-eye"></i> </a>
                                                <a href="{{route('admin.user.edit',$item->id)}}" class="badge bg-primary ml-1" title="ویرایش"><i class="fa fa-edit"></i> </a>
                                                @if ( auth()->user()->role_id==1  )
                                                    <a href="javascript:void(0);" onclick="del_row('{{$item->id}}')" class="badge bg-danger ml-1" title="حذف"><i class="fa fa-trash"></i></a>
                                                @endif
                                                {{-- @if($item->user_status=='active')
                                                    <a href="javascript:void(0);" onclick="active_row('{{$item->id}}','blocked')" class="badge bg-success ml-1" 
                                                        title="کاربر فعال است مسدود شود؟"><i class="fa fa-check"></i></a>
                                                @endif
                                                @if($item->user_status=='pending')
                                                    <a href="javascript:void(0);" onclick="active_row('{{$item->id}}','active')" class="badge bg-warning ml-1" 
                                                        title="کاربر جدید است فعال شود؟"><i class="fa fa-close"></i></a>
                                                @endif --}}
                                                @if($item->user_status=='blocked')
                                                    <a href="javascript:void(0);" onclick="active_row('{{$item->id}}','active')" class="badge bg-danger ml-1" 
                                                        title="کاربر مسدود است فعال شود؟"><i class="fa fa-close"></i></a>
                                                @endif
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

    @if(count($items)>0)
        @foreach($items as $item)
            <div class="modal" id="role{{$item->id}}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        {{ Form::model($item,array('route' => array('admin.user-role.update'), 'method' => 'POST', 'files' => true)) }}
                            <div class="modal-header">
                                <h4 class="modal-title">تغییر سمت کاربر</h4>
                                <button type="button" class="close" data-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="{{$item->id}}">
                                <div class="form-group">
                                    <label for="role_name" >نوع سمت</label>
                                    <select id="role_name" name="role_name" class="form-control col-lg-6 col-10">
                                        @foreach (\App\Model\Role::all(['id','description']) as $role)
                                            <option value="{{$role->id}}" {{ $item->role_id==$role->id?'selected':''}}>{{$role->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer row">
                                <div class="col">
                                    {{ Form::button('ویرایش', array('type' => 'submit', 'class' => 'btn btn-success col-12 ')) }}
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-danger col-12" data-dismiss="modal">بستن</button>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        @endforeach
    @endif

@endsection
@section('js')
    <script>
        function active_row(id,type) {
            if(type=='blocked')
            {
                var text_user='پنل کاربر مسدود می شود';
            }
            if(type=='active')
            {
                var text_user='پنل کاربر فعال می شود';
            }
            Swal.fire({
                title: text_user ,
                text: 'برای تغییر وضعیت کاربر مطمئن هستید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = '{{url('/')}}/admin/user-active/'+id+'/'+type;
                }
            })
        }
        function del_row(id) {
            Swal.fire({
                text: 'برای حذف مطمئن هستید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = '{{url('/')}}/admin/user-destroy/'+id;
                }
            })
        }
    </script>
@endsection