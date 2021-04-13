<?php

namespace Wikichua\SAP\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetAdminPassword extends Notification
{
    public $token;
    public static $toMailCallback;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage())
            ->subject(__('Reset Password Notification'))
            ->line(__('You are receiving this email because we received a password reset request for your account.'))
            ->action(__('Reset Password'), url(config('app.url').route('admin.password.reset', $this->token, false)))
            ->line(__('If you did not request a password reset, no further action is required.'))
        ;
    }

    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
