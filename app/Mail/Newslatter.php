<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Markdown;

class Newslatter extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     * Crie uma nova instância de mensagem.
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     * Construa a mensagem.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'name' => 'Fabiano',
            'email' => 'nikogmail.com',
            'subject' => 'denuncia',
            'url' => 'patdes',
            'message' => 'Mensagem importante'
        ];
        //return $this->markdown('emails.newslatter');
        return $this->view('emails')->with($data);
    }
}
