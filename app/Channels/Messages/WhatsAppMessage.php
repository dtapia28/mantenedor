<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Channels\Messages;

class WhatsAppMessage
{
  public $content;
  
  public function content($content)
  {
    $this->content = $content;

    return $this;
  }
}


