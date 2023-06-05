<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class LeaveAbsenceRequestApprovedNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $leaveAbsenceRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $leaveAbsenceRequest)
    {
        $this->user = $user;
        $this->leaveAbsenceRequest = $leaveAbsenceRequest;
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
        return (new MailMessage)
            ->subject('Approbation de la demande de congé accordée')
            ->greeting('Bonjour, ' . $this->user->first_name . ' ' . $this->user->last_name . ' !')
            ->line('Nous avons le plaisir de vous informer que votre demande de congé a été approuvée. Votre période d\'absence a été accordée conformément à vos souhaits et aux politiques de l\'entreprise.')
            ->line('Dates du congé approuvées : ' . $this->leaveAbsenceRequest->starts_at . ' - ' . $this->leaveAbsenceRequest->ends_at . '')
            ->line('Nous vous encourageons à bien profiter de votre congé et à vous reposer pleinement. Assurez-vous de bien organiser votre travail et de vous assurer que toutes les tâches essentielles sont gérées avant votre départ.')
            ->line('Si vous avez des questions ou des préoccupations supplémentaires, n\'hésitez pas à nous contacter. Nous serons ravis de vous aider.')
            ->line('Merci et profitez de votre temps libre !');
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
