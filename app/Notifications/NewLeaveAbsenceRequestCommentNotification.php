<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class NewLeaveAbsenceRequestCommentNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $leaveAbsenceRequestComment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $leaveAbsenceRequestComment)
    {
        $this->user = $user;
        $this->leaveAbsenceRequestComment = $leaveAbsenceRequestComment;
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
        $message = new MailMessage;

        $message->subject('Nouveau commentaire sur la demande de congé')
        ->greeting('Bonjour, ' . $this->user->first_name . ' ' . $this->user->last_name . ' !')
        ->line('Nous tenons à vous informer qu\'un nouveau commentaire a été ajouté à votre demande de congé. Nous souhaitons vous tenir informé(e) des dernières informations et des discussions en cours concernant votre demande.')
        ->line('Commentaire :')
        ->line($this->leaveAbsenceRequestComment->body)
        ->line('Merci de votre compréhension et de votre collaboration.');

        foreach ($this->leaveAbsenceRequestComment->attachments as $attachment) {
            $message->attach($attachment->getPath());
        }

        return $message;
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
