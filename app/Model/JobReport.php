<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WorkReport extends Model
{
    protected $table = 'adib_it_work_reports';

    protected $fillable = [
        "timesheet_id",
        "description",
        "UpdateAntivirusDate",
        "DeleteTempFile",
        "RunScanDisk/CheckDisk",
        "RunAcompleteVirussystemscan",
        'SureForSavingImportantDataOnServer',
        "CheckCompleteShutdown",
        "CheckAndReMoveUnnecessaryStartup",
        "CheckingForLeaseDuration",
        "UpdatingAntivirus",
        "Run&CompleteVirusSystemScan",
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
        return $this->belongsTo('App\Model\WorkTimesheet','timesheet_id')->first();
    }

}

