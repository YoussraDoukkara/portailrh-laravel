<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class LeaveAbsenceRequestRejectedNotification extends Notification
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
            ->subject('Refus de la demande de congé')
            ->greeting('Bonjour, ' . $this->user->first_name . ' ' . $this->user->last_name . ' !')
            ->line('Nous regrettons de vous informer que votre demande de congé a été refusée. Après avoir soigneusement examiné votre demande et pris en compte les besoins de l\'entreprise, nous avons pris la décision de ne pas accorder cette période d\'absence.')
            ->line('Nous comprenons que cela peut être décevant pour vous, mais nous tenons à vous rappeler que nous avons des contraintes opérationnelles et des impératifs de service à respecter. Votre présence pendant cette période est nécessaire pour assurer la continuité des activités de l\'entreprise.')
            ->line('Nous vous encourageons à discuter avec votre responsable direct pour trouver des solutions alternatives, telles que la reprogrammation de vos congés à une date ultérieure qui serait plus compatible avec les besoins de l\'entreprise.')
            ->line('Si vous avez des questions supplémentaires ou des préoccupations, n\'hésitez pas à nous contacter. Nous sommes là pour vous aider et trouver des solutions qui conviennent à tous.')
            ->line('Nous vous remercions de votre compréhension et de votre coopération.');
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
