<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Models\Tournament;
use App\Models\Zone;
use App\Models\Category;
use App\Models\CategoryType;


class JoinBracket extends Notification
{
    use Queueable;

    private $bracket;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($bracket)
    {
        $this->bracket = $bracket;
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
        $tournament = Tournament::where('id', '=', $this->bracket->id_tournament)->first();
        $zone = Zone::where('id', '=', $this->bracket->id_zone)->first();
        $category = Category::where('id', '=', $this->bracket->id_category)->first();
        $category_type = CategoryType::where('id', '=', $this->bracket->id_category_type)->first();


        return (new MailMessage)
            ->subject('Sei stato inserito nel tabellone' )
            ->markdown('emails.join_bracket', [ 'bracket' => $this->bracket, 
                                                'zone' => $zone, 
                                                'tournament' => $tournament, 
                                                'category' => $category,
                                                'category_type' => $category_type ]);
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable     
     */
    public function toDatabase($notifiable)
    {        
        return [
            'bracket' => $this->bracket
        ];
    }
}
