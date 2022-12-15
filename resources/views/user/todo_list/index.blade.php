@extends('user.master')

@section('content')
  <div class="container">

    <div class="panel panel-default">
      <div class="panel-sidebar panel-heading">{{$title}}</div>
      <div class="panel-body">
        <a href="{{route('user.todo-list.create')}}" class="btn btn-labeled-left">افزودن</a>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs mt-5" role="tablist">
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#befor">جامانده
              <span class="badge badge-danger">{{count($item_before)}}</span>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="#today">امروز
              <span class="badge badge-danger">{{count($item_today)}}</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#today_1">فردا
              <span class="badge badge-danger">{{count($item_today_1)}}</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#today_pas">پس فردا
              <span class="badge badge-danger">{{count($item_today_2)}}</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#month">یک ماه آینده
              <span class="badge badge-danger">{{count($item_month)}}</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#month_after">پس از یک ماه
              <span class="badge badge-danger">{{count($item_month_after)}}</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#stop">متوقف
              <span class="badge badge-danger">{{count($item_stop)}}</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#end">اتمام
              <span class="badge badge-danger">{{count($item_end)}}</span>
            </a>
          </li>
          {{--                    <li class="nav-item">--}}
          {{--                        <a class="nav-link" data-toggle="tab" href="#all">همه--}}
          {{--                            <span class="badge badge-danger">{{count($items)}}</span>--}}
          {{--                        </a>--}}
          {{--                    </li>--}}
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div id="befor" class="container tab-pane">
            <div class="row">
              @if(count($cats))
                <ul class="nav nav-tabs mt-5" role="tablist">
                  @foreach($cats as $i=>$cat)
                    <li class="nav-item {{$i==0?' active':''}}">
                      <a class="nav-link" href="#befor_{{$cat->id}}">{{$cat->title}}
                        <span class="badge badge-danger">{{count($item_before->where('cat_id',$cat->id))}}</span>
                      </a>
                    </li>
                  @endforeach
                </ul>
                <div class="tab-content">
                  @foreach($cats as $i=>$cat)
                        @php
                            $key=0;
                        @endphp
                    <div id="befor_{{$cat->id}}" class="container tab-pane {{$i==0?' active in':''}}">
                      <table class="table datatable-responsive20 table-togglable">
                        <thead>
                        <tr>
                          <th data-hide="phone">#</th>
                          <th data-hide="phone">عنوان</th>
                          <th data-toggle="true">دسته</th>
                          <th data-toggle="true">شرکت</th>
                          <th data-toggle="true">مسئول</th>
                          <th data-toggle="true">اولویت</th>
                          <th data-toggle="true">درصد پیشرفت</th>
                          <th data-toggle="true">یادآوری</th>
                          <th data-hide="phone">عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($item_before->where('cat_id',$cat->id) as $data)
                          <tr>
                            <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$key+1}}</td>
                            <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                              {{$data->title}}
                              {!! $data->status_set($data->status) !!}
                            </td>
                            <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$data->cat?$data->cat->title:'__'}}</td>
                            <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                              {{$data->company_user?$data->company_user->company__name:'__'}}
                              {{$data->company_contract?'('.$data->company_contract->type.')':'__'}}
                            </td>
                            <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                              @if($data->type_ref=='one')
                                {{$data->user_ref?$data->user_ref->name:'__'}}
                              @else
                                {{$data->group_ref?$data->group_ref->title:'__'}}
                                {{$data->group_ref_user ? '('.$data->group_ref_user->name.')':'__'}}
                              @endif
                            </td>
                            <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                              {!! $data->priority_set($data->priority) !!}
                            </td>
                            <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                              <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="{{$data->percent}}"
                                     aria-valuemin="0" aria-valuemax="100" style="width:{{$data->percent}}%">
                                  {{$data->percent}}%
                                </div>
                              </div>
                            </td>
                            <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                              {{$data->type_reminder=='date'?$data->reminder_fa:$data->reminder_day($data->reminder).' '.$data->reminder_set($data->type_reminder)}}
                            </td>
                            <td>
                              @if($edit)
                                <div class="form-group d-flex">
                                  <a href="{{route('user.todo-list.edit', $data->id)}}" class="text-info mr-2"
                                     title="ویرایش"><i
                                            class="nav-icon i-File-Edit"></i></a>
                                </div>
                              @endif
                            </td>
                          </tr>
                          @php
                              $key++;
                          @endphp
                        @endforeach
                        </tbody>
                      </table>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
          </div>
          <div id="today" class="container tab-pane active in">
              @if(count($cats))
                  <ul class="nav nav-tabs mt-5" role="tablist">
                      @foreach($cats as $i=>$cat)
                          <li class="nav-item {{$i==0?' active':''}}">
                              <a class="nav-link" href="#today_{{$cat->id}}">{{$cat->title}}
                                  <span class="badge badge-danger">{{count($item_today->where('cat_id',$cat->id))}}</span>
                              </a>
                          </li>
                      @endforeach
                  </ul>
                  <div class="tab-content">
                      @foreach($cats as $i=>$cat)
                          @php
                              $key=0;
                          @endphp
                          <div id="today_{{$cat->id}}" class="container tab-pane {{$i==0?' active in':''}}">
                              <table class="table datatable-responsive20 table-togglable">
                                  <thead>
                                  <tr>
                                      <th data-hide="phone">#</th>
                                      <th data-hide="phone">عنوان</th>
                                      <th data-toggle="true">دسته</th>
                                      <th data-toggle="true">شرکت</th>
                                      <th data-toggle="true">مسئول</th>
                                      <th data-toggle="true">اولویت</th>
                                      <th data-toggle="true">درصد پیشرفت</th>
                                      <th data-toggle="true">یادآوری</th>
                                      <th data-hide="phone">عملیات</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  @foreach($item_today->where('cat_id',$cat->id) as $data)
                                      <tr>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$key+1}}</td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->title}}
                                              {!! $data->status_set($data->status) !!}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$data->cat?$data->cat->title:'__'}}</td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->company_user?$data->company_user->company__name:'__'}}
                                              {{$data->company_contract?'('.$data->company_contract->type.')':'__'}}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              @if($data->type_ref=='one')
                                                  {{$data->user_ref?$data->user_ref->name:'__'}}
                                              @else
                                                  {{$data->group_ref?$data->group_ref->title:'__'}}
                                                  {{$data->group_ref_user ? '('.$data->group_ref_user->name.')':'__'}}
                                              @endif
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {!! $data->priority_set($data->priority) !!}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              <div class="progress">
                                                  <div class="progress-bar" role="progressbar" aria-valuenow="{{$data->percent}}"
                                                       aria-valuemin="0" aria-valuemax="100" style="width:{{$data->percent}}%">
                                                      {{$data->percent}}%
                                                  </div>
                                              </div>
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->type_reminder=='date'?$data->reminder_fa:$data->reminder_day($data->reminder).' '.$data->reminder_set($data->type_reminder)}}
                                          </td>
                                          <td>
                                              @if($edit)
                                                  <div class="form-group d-flex">
                                                      <a href="{{route('user.todo-list.edit', $data->id)}}" class="text-info mr-2"
                                                         title="ویرایش"><i
                                                                  class="nav-icon i-File-Edit"></i></a>
                                                  </div>
                                              @endif
                                          </td>
                                      </tr>
                                      @php
                                          $key++;
                                      @endphp
                                  @endforeach
                                  </tbody>
                              </table>
                          </div>
                      @endforeach
                  </div>
              @endif
          </div>
          <div id="today_1" class="container tab-pane">
              @if(count($cats))
                  <ul class="nav nav-tabs mt-5" role="tablist">
                      @foreach($cats as $i=>$cat)
                          <li class="nav-item {{$i==0?' active':''}}">
                              <a class="nav-link" href="#today_1_{{$cat->id}}">{{$cat->title}}
                                  <span class="badge badge-danger">{{count($item_today_1->where('cat_id',$cat->id))}}</span>
                              </a>
                          </li>
                      @endforeach
                  </ul>
                  <div class="tab-content">
                      @foreach($cats as $i=>$cat)
                          @php
                              $key=0;
                          @endphp
                          <div id="today_1_{{$cat->id}}" class="container tab-pane {{$i==0?' active in':''}}">
                              <table class="table datatable-responsive20 table-togglable">
                                  <thead>
                                  <tr>
                                      <th data-hide="phone">#</th>
                                      <th data-hide="phone">عنوان</th>
                                      <th data-toggle="true">دسته</th>
                                      <th data-toggle="true">شرکت</th>
                                      <th data-toggle="true">مسئول</th>
                                      <th data-toggle="true">اولویت</th>
                                      <th data-toggle="true">درصد پیشرفت</th>
                                      <th data-toggle="true">یادآوری</th>
                                      <th data-hide="phone">عملیات</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  @foreach($item_today_1->where('cat_id',$cat->id) as $data)
                                      <tr>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$key+1}}</td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->title}}
                                              {!! $data->status_set($data->status) !!}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$data->cat?$data->cat->title:'__'}}</td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->company_user?$data->company_user->company__name:'__'}}
                                              {{$data->company_contract?'('.$data->company_contract->type.')':'__'}}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              @if($data->type_ref=='one')
                                                  {{$data->user_ref?$data->user_ref->name:'__'}}
                                              @else
                                                  {{$data->group_ref?$data->group_ref->title:'__'}}
                                                  {{$data->group_ref_user ? '('.$data->group_ref_user->name.')':'__'}}
                                              @endif
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {!! $data->priority_set($data->priority) !!}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              <div class="progress">
                                                  <div class="progress-bar" role="progressbar" aria-valuenow="{{$data->percent}}"
                                                       aria-valuemin="0" aria-valuemax="100" style="width:{{$data->percent}}%">
                                                      {{$data->percent}}%
                                                  </div>
                                              </div>
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->type_reminder=='date'?$data->reminder_fa:$data->reminder_day($data->reminder).' '.$data->reminder_set($data->type_reminder)}}
                                          </td>
                                          <td>
                                              @if($edit)
                                                  <div class="form-group d-flex">
                                                      <a href="{{route('user.todo-list.edit', $data->id)}}" class="text-info mr-2"
                                                         title="ویرایش"><i
                                                                  class="nav-icon i-File-Edit"></i></a>
                                                  </div>
                                              @endif
                                          </td>
                                      </tr>
                                      @php
                                          $key++;
                                      @endphp
                                  @endforeach
                                  </tbody>
                              </table>
                          </div>
                      @endforeach
                  </div>
              @endif
          </div>
          <div id="today_pas" class="container tab-pane">
              @if(count($cats))
                  <ul class="nav nav-tabs mt-5" role="tablist">
                      @foreach($cats as $i=>$cat)
                          <li class="nav-item {{$i==0?' active':''}}">
                              <a class="nav-link" href="#today_2_{{$cat->id}}">{{$cat->title}}
                                  <span class="badge badge-danger">{{count($item_today_2->where('cat_id',$cat->id))}}</span>
                              </a>
                          </li>
                      @endforeach
                  </ul>
                  <div class="tab-content">
                      @foreach($cats as $i=>$cat)
                          <div id="today_2_{{$cat->id}}" class="container tab-pane {{$i==0?' active in':''}}">
                              <table class="table datatable-responsive20 table-togglable">
                                  <thead>
                                  <tr>
                                      <th data-hide="phone">#</th>
                                      <th data-hide="phone">عنوان</th>
                                      <th data-toggle="true">دسته</th>
                                      <th data-toggle="true">شرکت</th>
                                      <th data-toggle="true">مسئول</th>
                                      <th data-toggle="true">اولویت</th>
                                      <th data-toggle="true">درصد پیشرفت</th>
                                      <th data-toggle="true">یادآوری</th>
                                      <th data-hide="phone">عملیات</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  @foreach($item_today_2->where('cat_id',$cat->id) as $data)
                                      <tr>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$key+1}}</td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->title}}
                                              {!! $data->status_set($data->status) !!}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$data->cat?$data->cat->title:'__'}}</td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->company_user?$data->company_user->company__name:'__'}}
                                              {{$data->company_contract?'('.$data->company_contract->type.')':'__'}}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              @if($data->type_ref=='one')
                                                  {{$data->user_ref?$data->user_ref->name:'__'}}
                                              @else
                                                  {{$data->group_ref?$data->group_ref->title:'__'}}
                                                  {{$data->group_ref_user ? '('.$data->group_ref_user->name.')':'__'}}
                                              @endif
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {!! $data->priority_set($data->priority) !!}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              <div class="progress">
                                                  <div class="progress-bar" role="progressbar" aria-valuenow="{{$data->percent}}"
                                                       aria-valuemin="0" aria-valuemax="100" style="width:{{$data->percent}}%">
                                                      {{$data->percent}}%
                                                  </div>
                                              </div>
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->type_reminder=='date'?$data->reminder_fa:$data->reminder_day($data->reminder).' '.$data->reminder_set($data->type_reminder)}}
                                          </td>
                                          <td>
                                              @if($edit)
                                                  <div class="form-group d-flex">
                                                      <a href="{{route('user.todo-list.edit', $data->id)}}" class="text-info mr-2"
                                                         title="ویرایش"><i
                                                                  class="nav-icon i-File-Edit"></i></a>
                                                  </div>
                                              @endif
                                          </td>
                                      </tr>
                                  @endforeach
                                  </tbody>
                              </table>
                          </div>
                      @endforeach
                  </div>
              @endif
          </div>
          <div id="month" class="container tab-pane">
              @if(count($cats))
                  <ul class="nav nav-tabs mt-5" role="tablist">
                      @foreach($cats as $i=>$cat)
                          <li class="nav-item {{$i==0?' active':''}}">
                              <a class="nav-link" href="#month_{{$cat->id}}">{{$cat->title}}
                                  <span class="badge badge-danger">{{count($item_month->where('cat_id',$cat->id))}}</span>
                              </a>
                          </li>
                      @endforeach
                  </ul>
                  <div class="tab-content">
                      @foreach($cats as $i=>$cat)
                          @php
                              $key=0;
                          @endphp
                          <div id="month_{{$cat->id}}" class="container tab-pane {{$i==0?' active in':''}}">
                              <table class="table datatable-responsive20 table-togglable">
                                  <thead>
                                  <tr>
                                      <th data-hide="phone">#</th>
                                      <th data-hide="phone">عنوان</th>
                                      <th data-toggle="true">دسته</th>
                                      <th data-toggle="true">شرکت</th>
                                      <th data-toggle="true">مسئول</th>
                                      <th data-toggle="true">اولویت</th>
                                      <th data-toggle="true">درصد پیشرفت</th>
                                      <th data-toggle="true">یادآوری</th>
                                      <th data-hide="phone">عملیات</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  @foreach($item_month->where('cat_id',$cat->id) as $data)
                                      <tr>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$key+1}}</td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->title}}
                                              {!! $data->status_set($data->status) !!}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$data->cat?$data->cat->title:'__'}}</td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->company_user?$data->company_user->company__name:'__'}}
                                              {{$data->company_contract?'('.$data->company_contract->type.')':'__'}}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              @if($data->type_ref=='one')
                                                  {{$data->user_ref?$data->user_ref->name:'__'}}
                                              @else
                                                  {{$data->group_ref?$data->group_ref->title:'__'}}
                                                  {{$data->group_ref_user ? '('.$data->group_ref_user->name.')':'__'}}
                                              @endif
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {!! $data->priority_set($data->priority) !!}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              <div class="progress">
                                                  <div class="progress-bar" role="progressbar" aria-valuenow="{{$data->percent}}"
                                                       aria-valuemin="0" aria-valuemax="100" style="width:{{$data->percent}}%">
                                                      {{$data->percent}}%
                                                  </div>
                                              </div>
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->type_reminder=='date'?$data->reminder_fa:$data->reminder_day($data->reminder).' '.$data->reminder_set($data->type_reminder)}}
                                          </td>
                                          <td>
                                              @if($edit)
                                                  <div class="form-group d-flex">
                                                      <a href="{{route('user.todo-list.edit', $data->id)}}" class="text-info mr-2"
                                                         title="ویرایش"><i
                                                                  class="nav-icon i-File-Edit"></i></a>
                                                  </div>
                                              @endif
                                          </td>
                                      </tr>
                                      @php
                                          $key++;
                                      @endphp
                                  @endforeach
                                  </tbody>
                              </table>
                          </div>
                      @endforeach
                  </div>
              @endif
          </div>
          <div id="month_after" class="container tab-pane">
              @if(count($cats))
                  <ul class="nav nav-tabs mt-5" role="tablist">
                      @foreach($cats as $i=>$cat)
                          <li class="nav-item {{$i==0?' active':''}}">
                              <a class="nav-link" href="#month_after_{{$cat->id}}">{{$cat->title}}
                                  <span class="badge badge-danger">{{count($item_month_after->where('cat_id',$cat->id))}}</span>
                              </a>
                          </li>
                      @endforeach
                  </ul>
                  <div class="tab-content">
                      @foreach($cats as $i=>$cat)
                          @php
                              $key=0;
                          @endphp
                          <div id="month_after_{{$cat->id}}" class="container tab-pane {{$i==0?' active in':''}}">
                              <table class="table datatable-responsive20 table-togglable">
                                  <thead>
                                  <tr>
                                      <th data-hide="phone">#</th>
                                      <th data-hide="phone">عنوان</th>
                                      <th data-toggle="true">دسته</th>
                                      <th data-toggle="true">شرکت</th>
                                      <th data-toggle="true">مسئول</th>
                                      <th data-toggle="true">اولویت</th>
                                      <th data-toggle="true">درصد پیشرفت</th>
                                      <th data-toggle="true">یادآوری</th>
                                      <th data-hide="phone">عملیات</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  @foreach($item_month_after->where('cat_id',$cat->id) as $data)
                                      <tr>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$key+1}}</td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->title}}
                                              {!! $data->status_set($data->status) !!}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$data->cat?$data->cat->title:'__'}}</td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->company_user?$data->company_user->company__name:'__'}}
                                              {{$data->company_contract?'('.$data->company_contract->type.')':'__'}}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              @if($data->type_ref=='one')
                                                  {{$data->user_ref?$data->user_ref->name:'__'}}
                                              @else
                                                  {{$data->group_ref?$data->group_ref->title:'__'}}
                                                  {{$data->group_ref_user ? '('.$data->group_ref_user->name.')':'__'}}
                                              @endif
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {!! $data->priority_set($data->priority) !!}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              <div class="progress">
                                                  <div class="progress-bar" role="progressbar" aria-valuenow="{{$data->percent}}"
                                                       aria-valuemin="0" aria-valuemax="100" style="width:{{$data->percent}}%">
                                                      {{$data->percent}}%
                                                  </div>
                                              </div>
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->type_reminder=='date'?$data->reminder_fa:$data->reminder_day($data->reminder).' '.$data->reminder_set($data->type_reminder)}}
                                          </td>
                                          <td>
                                              @if($edit)
                                                  <div class="form-group d-flex">
                                                      <a href="{{route('user.todo-list.edit', $data->id)}}" class="text-info mr-2"
                                                         title="ویرایش"><i
                                                                  class="nav-icon i-File-Edit"></i></a>
                                                  </div>
                                              @endif
                                          </td>
                                      </tr>
                                      @php
                                          $key++;
                                      @endphp
                                  @endforeach
                                  </tbody>
                              </table>
                          </div>
                      @endforeach
                  </div>
              @endif
          </div>
          <div id="stop" class="container tab-pane">
              @if(count($cats))
                  <ul class="nav nav-tabs mt-5" role="tablist">
                      @foreach($cats as $i=>$cat)
                          <li class="nav-item {{$i==0?' active':''}}">
                              <a class="nav-link" href="#stop_{{$cat->id}}">{{$cat->title}}
                                  <span class="badge badge-danger">{{count($item_stop->where('cat_id',$cat->id))}}</span>
                              </a>
                          </li>
                      @endforeach
                  </ul>
                  <div class="tab-content">
                      @foreach($cats as $i=>$cat)
                          @php
                              $key=0;
                          @endphp
                          <div id="stop_{{$cat->id}}" class="container tab-pane {{$i==0?' active in':''}}">
                              <table class="table datatable-responsive20 table-togglable">
                                  <thead>
                                  <tr>
                                      <th data-hide="phone">#</th>
                                      <th data-hide="phone">عنوان</th>
                                      <th data-toggle="true">دسته</th>
                                      <th data-toggle="true">شرکت</th>
                                      <th data-toggle="true">مسئول</th>
                                      <th data-toggle="true">اولویت</th>
                                      <th data-toggle="true">درصد پیشرفت</th>
                                      <th data-toggle="true">یادآوری</th>
                                      <th data-hide="phone">عملیات</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  @foreach($item_stop->where('cat_id',$cat->id) as $data)
                                      <tr>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$key+1}}</td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->title}}
                                              {!! $data->status_set($data->status) !!}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$data->cat?$data->cat->title:'__'}}</td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->company_user?$data->company_user->company__name:'__'}}
                                              {{$data->company_contract?'('.$data->company_contract->type.')':'__'}}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              @if($data->type_ref=='one')
                                                  {{$data->user_ref?$data->user_ref->name:'__'}}
                                              @else
                                                  {{$data->group_ref?$data->group_ref->title:'__'}}
                                                  {{$data->group_ref_user ? '('.$data->group_ref_user->name.')':'__'}}
                                              @endif
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {!! $data->priority_set($data->priority) !!}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              <div class="progress">
                                                  <div class="progress-bar" role="progressbar" aria-valuenow="{{$data->percent}}"
                                                       aria-valuemin="0" aria-valuemax="100" style="width:{{$data->percent}}%">
                                                      {{$data->percent}}%
                                                  </div>
                                              </div>
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->type_reminder=='date'?$data->reminder_fa:$data->reminder_day($data->reminder).' '.$data->reminder_set($data->type_reminder)}}
                                          </td>
                                          <td>
                                              @if($edit)
                                                  <div class="form-group d-flex">
                                                      <a href="{{route('user.todo-list.edit', $data->id)}}" class="text-info mr-2"
                                                         title="ویرایش"><i
                                                                  class="nav-icon i-File-Edit"></i></a>
                                                  </div>
                                              @endif
                                          </td>
                                      </tr>
                                      @php
                                          $key++;
                                      @endphp
                                  @endforeach
                                  </tbody>
                              </table>
                          </div>
                      @endforeach
                  </div>
              @endif
          </div>
          <div id="end" class="container tab-pane">
              @if(count($cats))
                  <ul class="nav nav-tabs mt-5" role="tablist">
                      @foreach($cats as $i=>$cat)
                          <li class="nav-item {{$i==0?' active':''}}">
                              <a class="nav-link" href="#end_{{$cat->id}}">{{$cat->title}}
                                  <span class="badge badge-danger">{{count($item_end->where('cat_id',$cat->id))}}</span>
                              </a>
                          </li>
                      @endforeach
                  </ul>
                  <div class="tab-content">
                      @foreach($cats as $i=>$cat)
                          @php
                              $key=0;
                          @endphp
                          <div id="end_{{$cat->id}}" class="container tab-pane {{$i==0?' active in':''}}">
                              <table class="table datatable-responsive20 table-togglable">
                                  <thead>
                                  <tr>
                                      <th data-hide="phone">#</th>
                                      <th data-hide="phone">عنوان</th>
                                      <th data-toggle="true">دسته</th>
                                      <th data-toggle="true">شرکت</th>
                                      <th data-toggle="true">مسئول</th>
                                      <th data-toggle="true">اولویت</th>
                                      <th data-toggle="true">درصد پیشرفت</th>
                                      <th data-toggle="true">یادآوری</th>
                                      <th data-hide="phone">عملیات</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  @foreach($item_end->where('cat_id',$cat->id) as $data)
                                      <tr>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$key+1}}</td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->title}}
                                              {!! $data->status_set($data->status) !!}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$data->cat?$data->cat->title:'__'}}</td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->company_user?$data->company_user->company__name:'__'}}
                                              {{$data->company_contract?'('.$data->company_contract->type.')':'__'}}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              @if($data->type_ref=='one')
                                                  {{$data->user_ref?$data->user_ref->name:'__'}}
                                              @else
                                                  {{$data->group_ref?$data->group_ref->title:'__'}}
                                                  {{$data->group_ref_user ? '('.$data->group_ref_user->name.')':'__'}}
                                              @endif
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {!! $data->priority_set($data->priority) !!}
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              <div class="progress">
                                                  <div class="progress-bar" role="progressbar" aria-valuenow="{{$data->percent}}"
                                                       aria-valuemin="0" aria-valuemax="100" style="width:{{$data->percent}}%">
                                                      {{$data->percent}}%
                                                  </div>
                                              </div>
                                          </td>
                                          <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">
                                              {{$data->type_reminder=='date'?$data->reminder_fa:$data->reminder_day($data->reminder).' '.$data->reminder_set($data->type_reminder)}}
                                          </td>
                                          <td>
                                              @if($edit)
                                                  <div class="form-group d-flex">
                                                      <a href="{{route('user.todo-list.edit', $data->id)}}" class="text-info mr-2"
                                                         title="ویرایش"><i
                                                                  class="nav-icon i-File-Edit"></i></a>
                                                  </div>
                                              @endif
                                          </td>
                                      </tr>
                                      @php
                                          $key++;
                                      @endphp
                                  @endforeach
                                  </tbody>
                              </table>
                          </div>
                      @endforeach
                  </div>
              @endif
          </div>
          {{--                    <div id="all" class="container tab-pane">--}}
          {{--                        <table class="table datatable-responsive20 table-togglable">--}}
          {{--                            <thead>--}}
          {{--                            <tr>--}}
          {{--                                <th data-hide="phone">#</th>--}}
          {{--                                <th data-hide="phone">عنوان</th>--}}
          {{--                                <th data-toggle="true">دسته</th>--}}
          {{--                                <th data-toggle="true">شرکت</th>--}}
          {{--                                <th data-toggle="true">مسئول</th>--}}
          {{--                                <th data-toggle="true">اولویت</th>--}}
          {{--                                <th data-toggle="true">درصد پیشرفت</th>--}}
          {{--                                <th data-hide="phone">یادآوری</th>--}}
          {{--                                <th data-hide="phone">عملیات</th>--}}
          {{--                            </tr>--}}
          {{--                            </thead>--}}
          {{--                            <tbody>--}}
          {{--                            @foreach($items as $key => $data)--}}
          {{--                                <tr>--}}
          {{--                                    <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$key+1}}</td>--}}
          {{--                                    <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">--}}
          {{--                                        {{$data->title}}--}}
          {{--                                        {!! $data->status_set($data->status) !!}--}}
          {{--                                    </td>--}}
          {{--                                    <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">{{$data->cat?$data->cat->title:'__'}}</td>--}}
          {{--                                    <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">--}}
          {{--                                        {{$data->company_user?$data->company_user->company__name:'__'}}--}}
          {{--                                        {{$data->company_contract?'('.$data->company_contract->type.')':'__'}}--}}
          {{--                                    </td>--}}
          {{--                                    <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">--}}
          {{--                                        @if($data->type_ref=='one')--}}
          {{--                                            {{$data->user_ref?$data->user_ref->name:'__'}}--}}
          {{--                                        @else--}}
          {{--                                            {{$data->group_ref?$data->group_ref->title:'__'}}--}}
          {{--                                            {{$data->group_ref_user ? '('.$data->group_ref_user->name.')':'__'}}--}}
          {{--                                        @endif--}}
          {{--                                    </td>--}}
          {{--                                    <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">--}}
          {{--                                        {!! $data->priority_set($data->priority) !!}--}}
          {{--                                    </td>--}}
          {{--                                    <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">--}}
          {{--                                        <div class="progress">--}}
          {{--                                            <div class="progress-bar" role="progressbar" aria-valuenow="{{$data->percent}}"--}}
          {{--                                                 aria-valuemin="0" aria-valuemax="100" style="width:{{$data->percent}}%">--}}
          {{--                                                {{$data->percent}}%--}}
          {{--                                            </div>--}}
          {{--                                        </div>--}}
          {{--                                    </td>--}}
          {{--                                    <td onclick="document.location = '{{route('user.todo-list.show', $data->id)}}';">--}}
          {{--                                        {{$data->type_reminder=='date'?$data->reminder_fa:$data->reminder_day($data->reminder).' '.$data->reminder_set($data->type_reminder)}}--}}
          {{--                                    </td>--}}
          {{--                                    <td>--}}
          {{--                                        <div class="form-group d-flex">--}}
          {{--                                            <a href="{{route('user.todo-list.edit', $data->id)}}" class="text-info mr-2" title="ویرایش"><i class="nav-icon i-File-Edit"></i></a>--}}
          {{--
          {{--                                        </div>--}}

          {{--                                    </td>--}}
          {{--                                </tr>--}}
          {{--                            @endforeach--}}
          {{--                            </tbody>--}}
          {{--                        </table>--}}
          {{--                    </div>--}}
        </div>
      </div>
    </div>
  </div>

  <script>
      $("ul.nav-tabs a").click(function (e) {
          e.preventDefault();
          $(this).tab('show');
      });
  </script>
@endsection
