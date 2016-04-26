<?php

namespace ZfExtra\Mail;

use Zend\Mail\Message;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
interface PluginInterface
{

    public function preSend(Message $message);

    public function postSend();
}
