<!DOCTYPE HTML>
<html lang="en" dir="rtl">
@include('includes.head')
<style>
    body { max-width: 540px; } 
    .select2-container .select2-selection--single {height: 38px;}
    .select2-container--default .select2-selection--single .select2-selection__rendered {line-height: 36px;}
    .select2-container--open .select2-dropdown--below {z-index: 9999 !important;}
    .select2-container {width: 100% !important;}
    .accordion-button::after { margin: unset; position: absolute; right: 16px; width: 1rem; height: 1rem; background-size: 1rem; }
    .flash_message { position: absolute; top: 3%; z-index: 9; width: 100%; padding: 0px 5%; }
    .spinner-grow { width: 1rem; height: 1rem; animation: 1s linear infinite spinner-grow; }
</style>
<body class="theme-light body-scroll d-flex flex-column h-100 menu-overlay m-auto" data-highlight="highlight-red" data-gradient="body-default">
    <div id="page" >
        
        @if (auth()->user())
            @include('includes.header')
            @include('includes.bottomNavigationBar')
            <div class="flash_message">
                @if (session()->has('message'))
                    <div class="text-center py-3 alert alert-{{session()->get('status') }}" role="alert">
                        {!! session()->get('message') !!}
                    </div>
                @elseif (session()->has('flash_message'))
                    <div class="text-center py-3 alert alert-success" role="alert">
                        {!! session()->get('flash_message') !!}
                    </div>
                @elseif (session()->has('err_message'))
                    <div class="text-center py-3 alert alert-danger" role="alert">
                        {!! session()->get('err_message') !!}
                    </div>
                @endif
                <div id="offline_start_job_alert"></div>
            </div>
        @endif
        
        {{-- در پایان توسعه باز شود --}}
        {{-- <div class="container-fluid h-100 loader-display">
            <div class="row h-100">
                <div class="align-self-center col">
                    <div class="logo-loading">
                        <div class="icon icon-100 text-white mb-4"><img src="{{ url($setting->icon_site) }}" alt="{{$setting->title}}"></div>
                        <div class="fs-6">{{$setting->title}}</div>
                        <div class="loader-ellipsis">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- کد بالا در پایان توسعه باز شود --}}

        <div class="page-content header-clear-medium" style="padding-top: 0px !important;">
            <main class="flex-shrink-0 pt-4">
                @if ($runningJob??'')
                    <div class="runningJob bg-danger">
                        {{-- <form action="{{route('user.work-stop')}}" method="post" class="m-0"> --}}
                        <form action="{{route('user.timesheet-stop')}}" method="post" class="m-0">

                            <button @if($runningJob->work()->first('type')->type=='outwork') type="button" data-bs-toggle="modal" data-bs-target="#finishWorkTime"
                                 @else type="submit" @endif class="btn bg-danger text-light py-1" id="runningJobStoped">
                                 <div class="row m-0">
                                     <div id="runningJobTimer" class="col-auto  p-0text-light"></div>
                                     <div class="col-auto p-0">اتمام کار<i class="fa fa-refresh fa-spin ms-1"></i></div>
                                 </div>
                            </button>

                            {{-- <input type="hidden" value="{{$runningJob->type_id}}" name="id"> --}}
                            <input type="hidden" value="{{$runningJob->type_id}}" name="type_id">
                            <input type="hidden" value="{{$runningJob->type}}" name="type">
                            {{ csrf_field() }}
                        </form>
                    </div>

                    {{-- فرم کارهای ادواری --}}
                    <div class="modal fade" id="finishWorkTime">
                        <div class="modal-dialog">
                          <div class="modal-content my-5">

                            <div class="modal-header">
                              <h5 class="modal-title">گزارش فعالیت های انجام شده</h5>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('user.timesheet-stop')}}" method="post" class="m-0">
                                    {{ csrf_field() }}
                                    <div>
                                        <input type="hidden" value="{{$runningJob->type_id}}" name="type_id">
                                        <input type="hidden" value="{{$runningJob->type}}" name="type">

                                        <div class="border mb-3">
                                            <div class="card bg-light text-end redu10 mb-0">
                                                <div class="card-header">
                                                    <h6>subject <small class="text-template-primary-light">subject</small></h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="custom-control custom-switch">
                                                        <label class="custom-control-label" for="UpdateAntivirusDate" id="LabUpdateAntivirusDate">UpdateAntivirusDate</label>
                                                        <input type="checkbox" class="custom-control-input switch-secondary" onclick="setInputWork('UpdateAntivirusDate')" id="UpdateAntivirusDate">
                                                        <input type="hidden" value="deactive" name="UpdateAntivirusDate" id="InpUpdateAntivirusDate">
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <label class="custom-control-label" for="DeleteTempFile" id="LabDeleteTempFile">DeleteTempFile</label>
                                                        <input type="checkbox" class="custom-control-input switch-secondary" onclick="setInputWork('DeleteTempFile')" id="DeleteTempFile">
                                                        <input type="hidden" value="deactive" name="DeleteTempFile" id="InpDeleteTempFile">
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <label class="custom-control-label" for="RunScanDiskOrCheckDisk" id="LabRunScanDiskOrCheckDisk">RunScanDiskOrCheckDisk</label>
                                                        <input type="checkbox" class="custom-control-input switch-secondary" onclick="setInputWork('RunScanDiskOrCheckDisk')" id="RunScanDiskOrCheckDisk">
                                                        <input type="hidden" value="deactive" name="RunScanDiskOrCheckDisk" id="InpRunScanDiskOrCheckDisk">
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <label class="custom-control-label" for="RunAcompleteVirussystemScan" id="LabRunAcompleteVirussystemScan">RunAcompleteVirussystemScan</label>
                                                        <input type="checkbox" class="custom-control-input switch-secondary" onclick="setInputWork('RunAcompleteVirussystemScan')" id="RunAcompleteVirussystemScan">
                                                        <input type="hidden" value="deactive" name="RunAcompleteVirussystemScan" id="InpRunAcompleteVirussystemScan">
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <label class="custom-control-label" for="SureForSavingImportantDataOnServer" id="LabSureForSavingImportantDataOnServer">SureForSavingImportantDataOnServer</label>
                                                        <input type="checkbox" class="custom-control-input switch-secondary" onclick="setInputWork('SureForSavingImportantDataOnServer')" id="SureForSavingImportantDataOnServer">
                                                        <input type="hidden" value="deactive" name="SureForSavingImportantDataOnServer" id="InpSureForSavingImportantDataOnServer">
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <label class="custom-control-label" for="CheckCompleteShutdown" id="LabCheckCompleteShutdown">CheckCompleteShutdown</label>
                                                        <input type="checkbox" class="custom-control-input switch-secondary" onclick="setInputWork('CheckCompleteShutdown')" id="CheckCompleteShutdown">
                                                        <input type="hidden" value="deactive" name="CheckCompleteShutdown" id="InpCheckCompleteShutdown">
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <label class="custom-control-label" for="CheckAndReMoveUnnecessaryStartup" id="LabCheckAndReMoveUnnecessaryStartup">CheckAndReMoveUnnecessaryStartup</label>
                                                        <input type="checkbox" class="custom-control-input switch-secondary" onclick="setInputWork('CheckAndReMoveUnnecessaryStartup')" id="CheckAndReMoveUnnecessaryStartup">
                                                        <input type="hidden" value="deactive" name="CheckAndReMoveUnnecessaryStartup" id="InpCheckAndReMoveUnnecessaryStartup">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border mb-3">
                                            <div class="card bg-light text-end redu10 mb-0">
                                                <div class="card-header">
                                                    <h6>subject <small class="text-template-primary-light">subject</small></h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="custom-control custom-switch">
                                                        <label class="custom-control-label" for="CheckingForLeaseDuration" id="LabCheckingForLeaseDuration">CheckingForLeaseDuration</label>
                                                        <input type="checkbox" class="custom-control-input switch-secondary" onclick="setInputWork('CheckingForLeaseDuration')" id="CheckingForLeaseDuration">
                                                        <input type="hidden" value="deactive" name="CheckingForLeaseDuration" id="InpCheckingForLeaseDuration">
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <label class="custom-control-label" for="UpdatingAntivirus" id="LabUpdatingAntivirus">UpdatingAntivirus</label>
                                                        <input type="checkbox" class="custom-control-input switch-secondary" onclick="setInputWork('UpdatingAntivirus')" id="UpdatingAntivirus">
                                                        <input type="hidden" value="deactive" name="UpdatingAntivirus" id="InpUpdatingAntivirus">
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <label class="custom-control-label" for="RunAndCompleteVirusSystemScan" id="LabRunAndCompleteVirusSystemScan">RunAndCompleteVirusSystemScan</label>
                                                        <input type="checkbox" class="custom-control-input switch-secondary" onclick="setInputWork('RunAndCompleteVirusSystemScan')" id="RunAndCompleteVirusSystemScan">
                                                        <input type="hidden" value="deactive" name="RunAndCompleteVirusSystemScan" id="InpRunAndCompleteVirusSystemScan">
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <label class="custom-control-label" for="PhysicalChecksOfSystemHardware" id="LabPhysicalChecksOfSystemHardware">PhysicalChecksOfSystemHardware</label>
                                                        <input type="checkbox" class="custom-control-input switch-secondary" onclick="setInputWork('PhysicalChecksOfSystemHardware')" id="PhysicalChecksOfSystemHardware">
                                                        <input type="hidden" value="deactive" name="PhysicalChecksOfSystemHardware" id="InpPhysicalChecksOfSystemHardware">
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <label class="custom-control-label" for="ForMonthlyCheckUPS" id="LabForMonthlyCheckUPS">ForMonthlyCheckUPS</label>
                                                        <input type="checkbox" class="custom-control-input switch-secondary" onclick="setInputWork('ForMonthlyCheckUPS')" id="ForMonthlyCheckUPS">
                                                        <input type="hidden" value="deactive" name="ForMonthlyCheckUPS" id="InpForMonthlyCheckUPS">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border mb-3">
                                            <div class="card bg-light text-end redu10 mb-0">
                                                <div class="card-header">
                                                    <h6>subject <small class="text-template-primary-light">subject</small></h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="ServerName">ServerName</label>
                                                        <input type="text" class="form-control text-end" name="ServerName" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="BackupType">BackupType</label>
                                                        <input type="text" class="form-control text-end" name="BackupType" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="BackupStatus">BackupStatus</label>
                                                        <input type="text" class="form-control text-end" name="BackupStatus" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="LastBackupDate">LastBackupDate</label>
                                                        <input type="text" class="form-control text-end" name="LastBackupDate" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="HardRemainingSpace">HardRemainingSpace</label>
                                                        <input type="text" class="form-control text-end" name="HardRemainingSpace" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="description">comment / any change</label>
                                                        <textarea name="description" class="form-control text-end" rows="8" required></textarea>
                                                    </div>
                                                    {{-- <div class="form-group">
                                                        <label for="report"></label>
                                                        <textarea name="report" class="form-control" rows="8" required></textarea>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>

                                        
                                    </div>
                                    <button type="button" class="btn btn-secondary mt-4 float-end" data-bs-dismiss="modal">فعالیت هنوز ادامه دارد</button>
                                    <button type="submit" class="btn btn-primary mt-4">ثبت گزارش</button>
                                </form>
                            </div>

                          </div>
                        </div>
                    </div>

                @endif
                @yield('content')
            </main>
        </div>

    </div>   
    @include('includes.js')
</body>
  