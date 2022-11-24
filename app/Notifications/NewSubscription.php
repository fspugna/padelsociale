<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Models\Subscription;
use App\Models\Tournament;
use App\Models\Zone;
use App\Models\CategoryType;
use App\Models\Team;
use App\Models\TeamPlayer;

class NewSubscription extends Notification
{
    use Queueable;

    private $subscritpion;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $tournament = Tournament::where('id', '=', $this->subscription->id_tournament)->first();
        $zone = Zone::where('id', '=', $this->subscription->id_zone)->first();
        $category_type = CategoryType::where('id', '=', $this->subscription->id_category_type)->first();
        $team = Team::where('id', '=', $this->subscription->id_team)->first();
        $players = TeamPlayer::where('id_team', '=', $team->id)->get();

        return (new MailMessage)
                ->subject(env('APP_NAME') . ' - Nuova iscrizione' )
                ->markdown('emails.new_subscription', [ 
                                                            'subscription' => $this->subscription ,
                                                            'tournament' => $tournament,
                                                            'zone' => $zone,
                                                            'category_type' => $category_type,
                                                            'players' => $players
                                                      ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {        
        return [
            'subscription' => $this->subscritpion
        ];
    }
}
