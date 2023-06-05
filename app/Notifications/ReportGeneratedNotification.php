<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ReportGeneratedNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $filePath;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $filePath)
    {
        $this->user = $user;
        $this->filePath = $filePath;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $today = Carbon::now()->locale('fr')->format('d-m-Y');
        $subject = 'Rapport généré le ' . $today;
    
        return (new MailMessage)
            ->subject($subject)
            ->greeting('Bonjour, ' . $this->user->first_name . ' ' . $this->user->last_name . ' !')
            ->line('Le rapport a été généré.')
            ->attach($this->filePath);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
