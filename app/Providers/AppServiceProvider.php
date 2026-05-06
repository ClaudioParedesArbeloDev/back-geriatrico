<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function ($notifiable, $token) {
        $url = "carlota://reset-password?token=$token&email={$notifiable->email}";

        return (new MailMessage)
            ->subject('Recuperar contraseña')
            ->line('Hacé click abajo para resetear tu contraseña')
            ->action('Resetear contraseña', $url)
            ->line('Si no solicitaste esto, ignorá este mensaje.');
        });
    }
}
