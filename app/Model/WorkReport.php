<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WorkReport extends Model {

    protected $table = 'adib_it_work_reports';

    protected $fillable = [
        "timesheet_id",
        "description",
        "UpdateAntivirusDate",
        "DeleteTempFile",
        "RunScanDiskOrCheckDisk",
        "RunAcompleteVirussystemScan",
        'SureForSavingImportantDataOnServer',
        "CheckCompleteShutdown",
        "CheckAndReMoveUnnecessaryStartup",
        "CheckingForLeaseDuration",
        "UpdatingAntivirus",
        "RunAndCompleteVirusSystemScan",
        "PhysicalChecksOfSystemHardware",
        "ForMonthlyCheckUPS",
        "ServerName",
        "BackupType",
        'BackupStatus',
        "LastBackupDate",
        "HardRemainingSpace",
        "report",
    ];

    public function timesheet() {
        return $this->belongsTo('App\Model\WorkTimesheet' ,'id' ,'timesheet_id')->first();
    }

}

