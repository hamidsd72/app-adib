<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Withdrawal extends Notification {
    use Queueable;

    public $description;
    public $UpdateAntivirusDate;
    public $DeleteTempFile;
    public $RunScanDiskOrCheckDisk;
    public $RunAcompleteVirussystemScan;
    public $SureForSavingImportantDataOnServer;
    public $CheckCompleteShutdown;
    public $CheckAndReMoveUnnecessaryStartup;
    public $CheckingForLeaseDuration;
    public $UpdatingAntivirus;
    public $RunAndCompleteVirusSystemScan;
    public $PhysicalChecksOfSystemHardware;
    public $ForMonthlyCheckUPS;
    public $ServerName;
    public $BackupType;
    public $BackupStatus;
    public $LastBackupDate;
    public $HardRemainingSpace;

    public function __construct( $description ,$UpdateAntivirusDate ,$DeleteTempFile ,$RunScanDiskOrCheckDisk ,$RunAcompleteVirussystemScan
     ,$SureForSavingImportantDataOnServer ,$CheckCompleteShutdown ,$CheckAndReMoveUnnecessaryStartup ,$CheckingForLeaseDuration ,$UpdatingAntivirus
      ,$RunAndCompleteVirusSystemScan ,$PhysicalChecksOfSystemHardware ,$ForMonthlyCheckUPS ,$ServerName ,$BackupType ,$BackupStatus ,$LastBackupDate ,$HardRemainingSpace )
    {
        $this->description = $description;
        $this->UpdateAntivirusDate = $UpdateAntivirusDate;
        $this->DeleteTempFile = $DeleteTempFile;
        $this->RunScanDiskOrCheckDisk = $RunScanDiskOrCheckDisk;
        $this->RunAcompleteVirussystemScan = $RunAcompleteVirussystemScan;
        $this->SureForSavingImportantDataOnServer = $SureForSavingImportantDataOnServer;
        $this->CheckCompleteShutdown = $CheckCompleteShutdown;
        $this->CheckAndReMoveUnnecessaryStartup = $CheckAndReMoveUnnecessaryStartup;
        $this->CheckingForLeaseDuration = $CheckingForLeaseDuration;
        $this->UpdatingAntivirus = $UpdatingAntivirus;
        $this->RunAndCompleteVirusSystemScan = $RunAndCompleteVirusSystemScan;
        $this->PhysicalChecksOfSystemHardware = $PhysicalChecksOfSystemHardware;
        $this->ForMonthlyCheckUPS = $ForMonthlyCheckUPS;
        $this->ServerName = $ServerName;
        $this->BackupType = $BackupType;
        $this->BackupStatus = $BackupStatus;
        $this->LastBackupDate = $LastBackupDate;
        $this->HardRemainingSpace = $HardRemainingSpace;
    }

    public function via($notifiable) {
        return ['mail'];
    }

    public function toMail($notifiable) {
        return (new MailMessage)
                    ->subject('activity report for you')
                    ->line('UpdateAntivirusDate : '.$this->UpdateAntivirusDate)
                    ->line('DeleteTempFile : '.$this->DeleteTempFile)
                    ->line('RunScanDiskOrCheckDisk : '.$this->RunScanDiskOrCheckDisk)
                    ->line('RunAcompleteVirussystemScan : '.$this->RunAcompleteVirussystemScan)
                    ->line('SureForSavingImportantDataOnServer : '.$this->SureForSavingImportantDataOnServer)
                    ->line('CheckCompleteShutdown : '.$this->CheckCompleteShutdown)
                    ->line('CheckAndReMoveUnnecessaryStartup : '.$this->CheckAndReMoveUnnecessaryStartup)
                    ->line('CheckingForLeaseDuration : '.$this->CheckingForLeaseDuration)
                    ->line('UpdatingAntivirus : '.$this->UpdatingAntivirus)
                    ->line('RunAndCompleteVirusSystemScan : '.$this->RunAndCompleteVirusSystemScan)
                    ->line('PhysicalChecksOfSystemHardware : '.$this->PhysicalChecksOfSystemHardware)
                    ->line('ForMonthlyCheckUPS : '.$this->ForMonthlyCheckUPS)
                    ->line('ServerName : '.$this->ServerName)
                    ->line('BackupType : '.$this->BackupType)
                    ->line('BackupStatus : '.$this->BackupStatus)
                    ->line('LastBackupDate : '.$this->LastBackupDate)
                    ->line('HardRemainingSpace : '.$this->HardRemainingSpace)
                    ->line('description : '.$this->description)
                    ->action('view report', url('/user/reports/notifications'));
    }

    public function toArray($notifiable) {
        return [
            //
        ];
    }
}
