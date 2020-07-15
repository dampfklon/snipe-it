<?php

namespace App\Notifications;

use App\Helpers\Helper;
use App\Models\Setting;
use App\Models\SnipeModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ExpectedCheckinNotification extends Notification
{
    use Queueable;
    /**
     * @var
     */
    private $params;

    /**
     * Create a new notification instance.
     *
     * @param $params
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $notifyBy = [];
        $item = $this->params['item'];

        $notifyBy[]='mail';
        return $notifyBy;
    }

    public function toSlack($notifiable)
    {

    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $asset
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($params)
    {
        $settings = Setting::getSettings();

        $message = (new MailMessage)->markdown('notifications.markdown.expected-checkin',
            [
                'date' => Helper::getFormattedDateObject($this->params->expected_checkin, 'date', false),
                'asset' => $this->params->present()->name(),
                'serial' => $this->params->serial,
                'asset_tag' => $this->params->asset_tag
            ])
            ->subject(trans('mail.Expected_Checkin_Notification'), $this->params->present()->name());

        return $message;

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
