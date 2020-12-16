<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NewReqMail;
use Illuminate\Support\Facades\Mail;
use App\Requerimiento;
use App\Resolutor;

class MailController extends Controller
{
	public function send()
	{
        $objDemo = new \stdClass();
        $objDemo->demo_one = 'Demo One Value';
        $objDemo->demo_two = 'Demo Two Value';
        $objDemo->sender = 'SenderUserName';
        $objDemo->receiver = 'ReceiverUserName';

 
        Mail::to("dtapia1025@gmail.com")->send(new NewReqMail($objDemo));
	}
}
