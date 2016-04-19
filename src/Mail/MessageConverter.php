<?php

namespace ZfExtra\Mail;

use Zend\Mail\Message as ZendMessage;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part;

class MessageConverter
{
    /**
     * 
     * @param Message $message
     * @return ZendMessage
     */
    public static function convert(Message $message)
    {
        $mailMessage = new ZendMessage;
        
        $mailMessage->setSubject($message->getSubject());
        $mailMessage->setFrom($message->getFrom());
        $mailMessage->setTo($message->getTo());
        $mailMessage->setCc($mailMessage->getCc());
        $mailMessage->setBcc($message->getBcc());
        $mailMessage->setReplyTo($message->getReplyTo());
        $mailMessage->getHeaders()->addHeaders($message->getHeaders());
        
        if ($mailMessage->getSender()) {
            $mailMessage->setSender($message->getSender());
        }
        
        if ($message->isMultipart()) {
            $mimePart = new MimeMessage;
            
            if ($message->getBodyHtml()) {
                $part = new Part($message->getBodyHtml());
                $part->charset = $message->getCharset();
                $part->encoding = $message->getEncoding();
                $part->type = Mime::TYPE_HTML;
                $mimePart->addPart($part);
            }
            
            if ($message->getBodyText()) {
                $part = new Part($message->getBodyText());
                $part->charset = $message->getCharset();
                $part->encoding = $message->getEncoding();
                $part->type = Mime::TYPE_TEXT;
                $mimePart->addPart($part);
            }
            
            foreach ($message->getAttachments() as $attachment) {
                $mimePart->addPart($attachment->asMimePart());
            }
            
            foreach ($message->getParts() as $part) {
                $mimePart->addPart($part);
            }
            
            $mailMessage->setBody($mimePart);
        } else {
            $mailMessage->getHeaders()->addHeaderLine('Content-Type', $message->getContentType());
            $mailMessage->setEncoding($message->getEncoding());
            $mailMessage->setBody($message->getFilledBody());
        }
        return $mailMessage;
    }

}
