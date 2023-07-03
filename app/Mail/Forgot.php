<?php
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class Forgot extends Mailable {
 
    use Queueable,
        SerializesModels;
 
    //build the message.
    public function build() {
        return $this->view('forgot');
    }
}