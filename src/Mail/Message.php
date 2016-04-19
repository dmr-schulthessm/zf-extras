<?php

namespace ZfExtra\Mail;

use Exception;
use Zend\Mail\Address;
use Zend\Mail\AddressList;
use Zend\Mail\Headers;
use Zend\Mime\Mime;
use Zend\Mime\Part;
use ZfExtra\Mail\Attachment\AttachmentInterface;

/**
 * 
 * @method Message setTo($emailOrAddress, $name = null)
 * @method Message addTo($emailOrAddress, $name = null)
 * @method AddressList getTo()
 * @method Message setCc($emailOrAddress, $name = null)
 * @method Message addCc($emailOrAddress, $name = null)
 * @method AddressList getCc()
 * @method Message setBcc($emailOrAddress, $name = null)
 * @method Message addBcc($emailOrAddress, $name = null)
 * @method AddressList getBcc()
 * @method Message setReplyTo($emailOrAddress, $name = null)
 * @method Message addReplyTo($emailOrAddress, $name = null)
 * @method AddressList getReplyTo()
 * 
 * @package ZfExtra
 * @category Mail
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Message
{

    /**
     *
     * @var Address
     */
    protected $from;

    /**
     *
     * @var AddressList
     */
    protected $to;

    /**
     *
     * @var AddressList
     */
    protected $cc;

    /**
     *
     * @var AddressList
     */
    protected $bcc;

    /**
     *
     * @var AddressList
     */
    protected $replyTo;

    /**
     *
     * @var Address
     */
    protected $sender;

    /**
     *
     * @var string
     */
    protected $subject;

    /**
     *
     * @var string
     */
    protected $html;

    /**
     *
     * @var string
     */
    protected $text;

    /**
     *
     * @var AttachmentInterface[]
     */
    protected $attachments = [];

    /**
     *
     * @var string
     */
    protected $encoding = Mime::ENCODING_QUOTEDPRINTABLE;

    /**
     *
     * @var string
     */
    protected $charset = 'UTF-8';

    /**
     *
     * @var Headers
     */
    protected $headers;

    /**
     *
     * @var Part
     */
    protected $parts = [];
    
    /**
     * 
     * @param string $from
     * @param string|array|AddressList $to
     * @param string $subject
     * @param string $body
     * @param string $type
     */
    public function __construct($from = null, $to = null, $subject = null, $body = null, $type = Mime::TYPE_HTML)
    {
        $this->to = new AddressList;
        $this->cc = new AddressList;
        $this->bcc = new AddressList;
        $this->replyTo = new AddressList;
        $this->headers = new Headers;

        if (null !== $from) {
            $this->setFrom($from);
        }

        if (null !== $to) {
            $this->setTo($to);
        }

        if (null !== $subject) {
            $this->setSubject($subject);
        }

        if (null !== $body) {
            $this->setBody($body, $type);
        }
    }

    /**
     * 
     * @param string|Address $emailOrAddress
     * @param string $name
     * @return Message
     */
    public function setFrom($emailOrAddress, $name = null)
    {
        if (!$emailOrAddress instanceof Address) {
            $address = new Address($emailOrAddress, $name);
        }
        $this->from = $address;
        return $this;
    }

    /**
     * 
     * @return Address
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * 
     * @param string $subject
     * @return Message
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * 
     * @param string $content
     * @return Message
     */
    public function setBodyHtml($content)
    {
        $this->setBody($content, Mime::TYPE_HTML);
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getBodyHtml()
    {
        return $this->getBody(Mime::TYPE_HTML);
    }

    /**
     * 
     * @param string $content
     * @return Message
     */
    public function setBodyText($content)
    {
        $this->setBody($content, Mime::TYPE_TEXT);
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getBodyText()
    {
        return $this->getBody(Mime::TYPE_TEXT);
    }

    /**
     * 
     * @param string $content
     * @param string $type
     * @return Message
     */
    public function setBody($content, $type = Mime::TYPE_HTML)
    {
        switch ($type) {
            case Mime::TYPE_HTML:
                $this->html = $content;
                break;
            case Mime::TYPE_TEXT:
                $this->text = $content;
                break;
        }
        return $this;
    }

    /**
     * 
     * @param string $type
     * @return string
     */
    public function getBody($type = Mime::TYPE_HTML)
    {
        switch ($type) {
            case Mime::TYPE_HTML:
                return $this->html;
            case Mime::TYPE_TEXT:
                return $this->text;
        }
    }

    public function getContentType()
    {
        if ($this->isMultipart()) {
            throw Exception(__METHOD__ . ' is only for non-multipart messages.');
        }
        
        if ($this->html) {
            return Mime::TYPE_HTML;
        }

        if ($this->text) {
            return Mime::TYPE_TEXT;
        }
        
        return Mime::TYPE_TEXT;
    }

    /**
     * Returns text or html content.
     * 
     * @return string
     * @throws Exception
     */
    public function getFilledBody()
    {
        if ($this->isMultipart()) {
            throw Exception(__METHOD__ . ' is only for non-multipart messages.');
        }
        return $this->getBody($this->getContentType());
    }

    /**
     * 
     * @param AttachmentInterface[] $attachments
     * @return Message
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * 
     * @param AttachmentInterface $attachment
     * @return Message
     */
    public function addAttachment(AttachmentInterface $attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * 
     * @return AttachmentInterface[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }
    
    /**
     * 
     * @param Part[] $parts
     */
    public function setParts($parts)
    {
        $this->parts = $parts;
    }
    
    /**
     * 
     * @param Part $part
     */
    public function addPart(Part $part)
    {
        $this->parts[] = $part;
    }
    
    /**
     * 
     * @return Part[]
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * 
     * @param string|Address $emailOrAddress
     * @param string $name
     * @return Message
     */
    public function setSender($emailOrAddress, $name = null)
    {
        if (!$emailOrAddress instanceof Address) {
            $address = new Address($emailOrAddress, $name);
        }
        $this->sender = $address;
        return $this;
    }

    /**
     * 
     * @return Address
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * 
     * @param string $charset
     * @return Message
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * 
     * @param string $encoding
     * @return Message
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * 
     * @param Headers $headers
     * @return Message
     */
    public function setHeaders(Headers $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * 
     * @return Headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * 
     * @return boolean
     */
    public function isMultipart()
    {
        if (count($this->attachments) > 0) {
            return true;
        }
        
        if (count($this->parts) > 0) {
            return true;
        }

        return ($this->text && $this->html);
    }

    /**
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        $allowedProps = array('to', 'cc', 'bcc', 'replyTo');
        $calledMethod = substr($name, 0, 3);
        $prop = lcfirst(substr($name, 3));
        if (in_array($prop, $allowedProps)) {
            $method = $calledMethod . 'Addresses';
            return call_user_func_array([$this, $method], array_merge([$prop], $arguments));
        } else {
            throw new Exception('Called unknown method: ' . $name);
        }
    }

    /**
     * @param string $prop
     * @param string|array|Address|AddressList $emailOrAddress
     * @param string $name
     */
    protected function setAddresses($prop, $emailOrAddress, $name = null)
    {
        $this->$prop = new AddressList;
        $this->addAddresses($prop, $emailOrAddress, $name);
    }

    /**
     * @param string $prop
     * @return AddressList
     */
    protected function getAddresses($prop)
    {
        return $this->$prop;
    }

    /**
     * 
     * @param string $prop
     * @param string|array|Address|AddressList $emailOrAddress
     * @param string $name
     * @return Message
     */
    protected function addAddresses($prop, $emailOrAddress, $name = null)
    {
        $list = $this->$prop;
        if (is_string($emailOrAddress)) {
            $list->add($emailOrAddress, $name);
        } else if (is_array($emailOrAddress)) {
            foreach ($emailOrAddress as $key => $value) {
                if (is_numeric($key) || is_int($key)) {
                    $list->add($value);
                } else if (is_string($key)) {
                    $list->add($key, $value);
                }
            }
        } else if ($emailOrAddress instanceof AddressList) {
            $list->merge($emailOrAddress);
        } else if ($emailOrAddress instanceof Address) {
            $list->add($emailOrAddress);
        }
        return $this;
    }

}
