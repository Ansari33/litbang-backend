<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Notifiable;

class StatusNotif extends Notification
{
    use Queueable,Notifiable;

    private $e_mail = [];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
            ->from($notifiable->e_mail['email_pengirim'],$notifiable->e_mail['nama_pengirim'])
            #->bcc($notifiable->e_mail['cc'])
            ->line('Usulan Inovasi Anda '.$notifiable->e_mail['status'])
            ->action('Usulan Inovasi', env('WEB_URL').'forum-inovasi')
            ->line('Thank you for using our application!');
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

    public function routeNotificationForMail($notification)
    {
        return $this->e_mail['penerima'];
        // Return email address only...
        return $this->email_address;

        // Return email address and name...
        return [$this->email_address => $this->name];
    }

    public function setEmail($data){
        $this->e_mail = $data;
    }
}