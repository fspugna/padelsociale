<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Models\News;

class NewsPublished extends Notification
{
    use Queueable;

    private $news;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($news)
    {
        $this->news = $news;                
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
        return (new MailMessage)
            ->subject($this->news->title)
            ->markdown('emails.news_published', [ 'news' => $this->news ]);
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable     
     */
    public function toDatabase($notifiable)
    {        
        return [
            'news' => $this->news            
        ];
    }
}
