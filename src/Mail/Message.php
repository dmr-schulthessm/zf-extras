<?php

namespace ZfExtra\Mail;

use Exception;
use Zend\Mail\Message as ZendMessage;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part;
use ZfExtra\Mail\Attachment\AttachmentInterface;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Message extends ZendMessage
{

    /**
     * @var MimeMessage
     */
    protected $content;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->content = new MimeMessage;
    }

    /**
     * 
     * @param string $content
     */
    public function setContent($content, $type = Mime::TYPE_HTML)
    {
        $part = new Part($content);
        $part->charset = 'UTF-8';
        $part->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
        $part->type = $type;
        $this->content->addPart($part);
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * 
     * @param string $body
     * @throws Exception
     */
    public function setBody($body)
    {
        throw new Exception('Direct access to ' . __METHOD__ . ' is forbidden. Use "setContent" instead.');
    }
    
    /**
     * 
     * @param Part $part
     */
    public function addPart(Part $part)
    {
        $this->content->addPart($part);
    }

    /**
     * 
     * @param AttachmentInterface $attachment
     */
    public function addAttachment(AttachmentInterface $attachment)
    {
        $this->content->addPart($attachment->asMimePart());
    }

    /**
     * 
     * @param AttachmentInterface[] $attachments
     */
    public function addAttachments(array $attachments)
    {
        foreach ($attachments as $attachment) {
            $this->addAttachment($attachment);
        }
    }
    
    /**
     * Passes content to parent's body.
     * Fix hacks allow to create a good attachment API in this class.
     * 
     * @return void
     */
    public function prepare()
    {
        parent::setBody($this->content);
    }

}
