<?php
	
	namespace App\Notifications;
	
	use Illuminate\Bus\Queueable;
	use Illuminate\Notifications\Messages\MailMessage;
	use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
	
	class ResetPassword extends ResetPasswordNotification
	{
		use Queueable;
		
		/**
		 * ResetPassword constructor.
		 * @param $token
		 */
		public function __construct($token)
		{
			$this->token = $token;
		}
		
		/**
		 * Get the notification's delivery channels.
		 *
		 * @param  mixed $notifiable
		 * @return array
		 */
		public function via($notifiable)
		{
			return ['mail'];
		}
		
		/**
		 * Get the mail representation of the notification.
		 *
		 * @param  mixed $notifiable
		 * @return \Illuminate\Notifications\Messages\MailMessage
		 */
		public function toMail($notifiable)
		{
			return (new MailMessage)
				->from('support.ity-consulting@gmail.com')
				->subject('Password reset ' . config('app.name'))
				->greeting('Hi')
				->line('The introduction to the notification.')
				->action('Notification Action', url(config('app.url') . route('password.reset', $this->token, false)))
				->line('Thank you for using our application!')
				->salutation('Regards, ' . config('app.name'))
				;
		}
		
		/**
		 * Get the array representation of the notification.
		 *
		 * @param  mixed $notifiable
		 * @return array
		 */
		public function toArray($notifiable)
		{
			return [
				//
			];
		}
	}
