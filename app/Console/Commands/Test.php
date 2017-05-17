<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the web app tests';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	Artisan::call('dusk', ['--group=currentdate']);
    }
}
