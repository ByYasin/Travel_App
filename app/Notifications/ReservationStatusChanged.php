<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reservation;
    protected $action;
    protected $message;

    /**

     *
     * @param Reservation $reservation
     * @param string $action
     * @param string $message
     * @return void
     */
    public function __construct(Reservation $reservation, string $action, string $message)
    {
        $this->reservation = $reservation;
        $this->action = $action;
        $this->message = $message;
    }

    /**

     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**

     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $tour = $this->reservation->tour;
        $url = config('app.url') . '/reservations/' . $this->reservation->id;
        
        return (new MailMessage)
            ->subject('Rezervasyon Durumu Güncellendi')
            ->greeting('Merhaba ' . $notifiable->name . '!')
            ->line($this->message)
            ->line('Tur: ' . $tour->title)
            ->line('Tarih: ' . $this->reservation->reservation_date->format('d.m.Y'))
            ->line('Katılımcı Sayısı: ' . $this->reservation->participant_count)
            ->line('Toplam Fiyat: ' . $this->reservation->total_price . ' TL')
            ->action('Rezervasyonu Görüntüle', $url)
            ->line('Teşekkür ederiz!');
    }

    /**

     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'reservation_id' => $this->reservation->id,
            'tour_id' => $this->reservation->tour_id,
            'tour_title' => $this->reservation->tour->title,
            'status' => $this->reservation->status,
            'action' => $this->action,
            'message' => $this->message
        ];
    }
}