<?php

namespace App\Console;

use App\Models\Purchase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
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
            $events = Purchase::getRememberTickets();

            foreach ($events as $event) {
                $email = $event->email;
                Mail::send('email.remember-event', ['event' => $event], function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Dia evento');
                });
            }
            $opinions = Purchase::sendOpinion();
            // dd($events);
            foreach ($opinions as $opinion) {
                $token = Crypt::encryptString(json_encode([
                    'name' => $opinion->name,
                    'email' => $opinion->email,
                    'id' => $opinion->id,
                ]));

                
                $email = $opinion->email;
                Mail::send('email.opinion', ['opinion'=> $opinion, 'token'=>$token], function ($message) use ($email) {
                    $message->to($email)
                        -> subject('Valoracion');
                });
            }
        })->everyMinute();

       
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
