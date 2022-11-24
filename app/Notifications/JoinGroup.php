<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class JoinGroup extends Notification
{
    use Queueable;

    private $group;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($group)
    {
        $this->group = $group;
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
            ->subject('Sei nel gruppo ' . $this->group->name )
            ->markdown('emails.join_group', [ 'group' => $this->group ]);
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toDatabase($notifiable)
    {
        return [
            'group' => $this->group
        ];
    }
}
