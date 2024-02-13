<?php

namespace App\Console;

use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use SplSubject;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $events = Ticket::getRememberTickets();

            foreach ($events as $event) {
                $email = $event->email;
                Mail::send('email.remember-event', ['event' => $event], function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Dia evento');
                });
            }
            $opinions = Ticket::sendOpinion();
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
        })->daily();

       
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
