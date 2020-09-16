<?php

namespace App\Jobs;

use App\Imports\SwtsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $path;
    protected $hid;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path,$hid)
    {
        $this->path = $path;
        $this->hid = $hid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::import(new SwtsImport($this->hid), $this->path);
    }
}
