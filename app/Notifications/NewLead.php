<?php

namespace App\Notifications;

use App\User;
use App\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewLead extends Notification
{
    use Queueable;

	//protected $user_info;	
	//protected $lead_info;
	private $subscription;
	
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($letter)
    {
        //
		//$this->user = $user_info;
		//$this->lead = $lead_info;
		
		$this->subscription = $letter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
/*     public function toMail($notifiable)
    {
        return (new MailMessage)
					->subject('New Lead Added')
					->line("New User Added: ".$this->user->fname." ".$this->user->lname)
					->line("With Lead/Business Name: ".$this->lead->businessName)		
                    ->action('Notification Action', url('http://localhost:8000/lead/leadview'))
                    ->line('Thank you for using our application!');
    } */
	
/*  	public function toDatabase(){
		//
		return [
			'id' => $this->lead->id,
			'businessName' => $this->lead->businessName,
			'businessNature' => $this->lead->businessNature,
			'description' => $this->lead->description,
			'fb_link' => $this->lead->fb_link,
			'tw_link' => $this->lead->tw_link,
			'in_link' => $this->lead->in_link,
			'li_link' => $this->lead->li_link,
			'web_link' => $this->lead->web_link,
			'created_at' => $this->lead->created_at 		
		];
	} */

 	public function toDatabase($notifiable){
		//
		return [
			'letter' => $this->subscription,
		
		];
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
