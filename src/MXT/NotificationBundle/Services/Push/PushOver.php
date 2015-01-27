<?php
/*
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\NotificationBundle\Services\Push\PushOver
 *
 */
namespace MXT\NotificationBundle\Services\Push;

use Sly\PushOver\Model\Push;
use Sly\PushOver\PushManager;

class PushOver
{
    private $userKey, $apiKey;
    private $pushManger;
    private $push;

    public function __construct($userKey, $apiKey)
    {
        $this->userKey = $userKey;
        $this->apiKey = $apiKey;

        $this->pushManger = new PushManager($userKey, $apiKey);
        $this->push = new Push();
    }

    public function getPush()
    {
        return $this->push;
    }

    public function setPush(Push $push)
    {
        $this->push = $push;
    }

    public function send()
    {
        return $this->pushManger->push($this->push);
    }
}