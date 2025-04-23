<?php

namespace SmartCms\Reviews\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use SmartCms\Reviews\Admin\Resources\ProductReviewResource;
use SmartCms\Reviews\Models\ProductReview;

class NewReviewNotification extends Notification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(public ProductReview $review) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $via = [];
        $notificationSettings = $notifiable->notifications ?? [];
        if (isset($notificationSettings['mail']) && isset($notificationSettings['mail']['new_review']) && $notificationSettings['mail']['new_review']) {
            $via[] = 'mail';
        }
        if (isset($notificationSettings['telegram']) && isset($notificationSettings['telegram']['new_review']) && $notificationSettings['telegram']['new_review'] && $notifiable->telegram_id) {
            $via[] = 'telegram';
        }

        return $via;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->line('🔔 New review for product '.$this->review->product->name.'!')
            ->line("Review: {$this->review->review}")
            ->line("Rating: {$this->review->rating}");

        return $message->action('View in admin panel', ProductReviewResource::getUrl('index'))->line('Thank you for using our application!');
    }

    public function toTelegram(object $notifiable): TelegramMessage
    {

        return TelegramMessage::create()
            ->to($notifiable->telegram_id)
            ->content("🔔 New review for product {$this->review->product->name}!\n\nReview: {$this->review->comment}\nRating: {$this->review->rating}")
            ->button('View in admin panel', ProductReviewResource::getUrl('index'));
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
