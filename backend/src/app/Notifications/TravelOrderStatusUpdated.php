<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TravelOrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    private $order;
    private $status;

    public function __construct($order)
    {
        $this->order = $order;
        $this->status = $order->status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                ->subject('Atualização no status da sua solicitação de viagem')
                ->greeting('Olá, ' . $notifiable->name . '!')
                ->line("O status do seu pedido de viagem foi atualizado para: **{$this->status}**.")
                ->action('Ver Detalhes', url('/travel-orders/' . $this->order->id))
                ->line('Obrigado por utilizar nossa plataforma!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'destino' => $this->order->destino,
            'status' => $this->status,
            'message' => "O status do seu pedido de viagem nº {$this->order->id} com destino {$this->order->destino}, foi atualizado para {$this->status}.",
        ];
    }
}
