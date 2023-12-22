<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActiveAccount extends Mailable
{
    use Queueable, SerializesModels;
    private $string;
    private $email;
    private $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $string)
    {
        //
        $this->email = $email;
        $this->string = $string;
        $this->url = url('active/'.$email.'/'.$string);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // dd($this->url);
        return $this->from('kq909981@gmail.com','EShopper')->subject('Kích hoạt tài khoản')->markdown('emails.account.active',['url'=>$this->url]);
    }
}
