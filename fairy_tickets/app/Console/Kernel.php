<?php

namespace App\Console;

use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $events = Ticket::getRememberTickets();

            foreach($events as $event){

                Mail::send('email.remember-event', ['event'=>$event ], function ($message) {
                    $message->to('prueba@example.com')
                        ->subject('Dia evento');
                });
            }
           
        })->everyMinute();
    

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
