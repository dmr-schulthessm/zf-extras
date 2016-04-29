<?php

namespace ZfExtra\Mail;

use Zend\Mail\Message as ZendMessage;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
interface PluginInterface
{

    public function preSend(ZendMessage $message);

    public function postSend(ZendMessage $message, $result);
}
