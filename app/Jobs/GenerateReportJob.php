<?php

namespace App\Jobs;

use App\Exports\ReportExport;
use App\Models\User;
use App\Notifications\ReportGeneratedNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fileName = 'Rapport ' . Carbon::now()->locale('fr')->format('d-m-Y_H-i-s') . '.xlsx';
        $filePath = 'reports/' . $fileName;

        $date = Carbon::today();

        Excel::store(new ReportExport($date), $filePath);

        $fullPath = storage_path('app/' . $filePath);

        $user = User::where('role', 'admin')->first();

        $user->notify(new ReportGeneratedNotification($user, $fullPath));
    }
}
