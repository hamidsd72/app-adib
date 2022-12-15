<?php

namespace App\Notification\TodoList;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class TodoListNotification extends Notification
{
    use Queueable;

    public function __construct($item,$title)
    {
        $this->comment = $item;
        $this->title=$title;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->line('The introduction to the notification.')
        ->action('Notification Action', url('/'))
        ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'id' => $this->comment->id,
            'name' => $this->comment->title,
            'url'=> 'https://support.adib-it.com/panel/todo-list/'.$this->comment->id,
        ];
    }

}