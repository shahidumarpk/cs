<?php

namespace App\Notifications;

use App\Recording;
use App\User;
use App\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RecordingPublished extends Notification
{
    use Queueable;
	
	protected $user;
	public $lead;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user,Lead $lead)
    {
        //
		$this->user = $user;
		$this->lead = $lead;
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
					->line("New User Added: ".$this->user->fname." ".$this->user->lname)
					->line("With Lead of: ".$this->lead->businessName)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('www.laravel.com'))
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
}
