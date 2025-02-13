<?php

namespace App\Notifications;

use Domain\Core\Entity\TravelOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TravelOrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    private TravelOrder $travelOrder;

    public function __construct(TravelOrder $travelOrder)
    {
        $this->travelOrder = $travelOrder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting("Olá {$this->travelOrder->getUser()->getName()}!")
            ->subject("O status do seu pedido de viagem foi alterado!")
            ->line("O pedido seu pedido de viagem #{$this->travelOrder->getOrderId()->value()}"
                . " foi {$this->travelOrder->getStatus()->getDescription()}."
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
