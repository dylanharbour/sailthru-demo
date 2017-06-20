<?php

namespace App\Notifications;

use App\Channels\SailthruChannel;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeEmailNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    public $sailThruTemplate = '';

    /**
     * @var array
     */
    public $sailThruParameters = [];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'mail',
            SailthruChannel::class
        ];
    }

    /**
     * @param User $user
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(User $user)
    {
        return (new MailMessage)
                    ->subject("Hi {$user->name}, ")
                    ->line('Thanks for signing up to ' . config('app.name'))
                    ->line('Your account is ready, please login using the link below.')
                    ->action('Login', route('login'))
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


    public function toSailthru(User $user)
    {
        $this->sailThruTemplate = 'account activation employer';
        $this->sailThruParameters = [
            'account_activation_link' => 'http://www.brightermonday.com',
            'first_name' => $user->name,
            'alert_link' => 'http://www.brightermonday.com',
            'company_name' => 'Dylan Test Company',
            'employer_title' => 'Dylan Test Employer',
            'employer_name' => 'Dylan Test Employer'
        ];

        return $this;
    }
}