<?php

namespace AdminBundle\Service;



use Mp091689\TurboSms\TurboSms;

class SmsSender
{
    private $sender;

    public function __construct($host, $db_name, $user, $password)
    {
        $this->sender = new TurboSms($host, $db_name, $user, $password);
    }

    public function send()
    {
        // TODO: implement
    }
}