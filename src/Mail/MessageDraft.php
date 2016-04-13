<?php

namespace ZfExtra\Mail;

use ZfExtra\Support\ArrayToClassPropertiesTrait;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
class MessageDraft
{

    use ArrayToClassPropertiesTrait;

    /**
     *
     * @var array
     */
    protected $to = [];

    /**
     *
     * @var array
     */
    protected $cc = [];

    /**
     *
     * @var array
     */
    protected $bcc = [];

    /**
     *
     * @var string
     */
    protected $subject;

    /**
     *
     * @var string
     */
    protected $from;

    /**
     *
     * @var string
     */
    protected $type;

    /**
     *
     * @var string
     */
    protected $body;

    public function __construct(array $config)
    {
        $this->arrayToClassProperties($config);
    }

    public function getTo()
    {
        return $this->to;
    }

    public function getCc()
    {
        return $this->cc;
    }

    public function getBcc()
    {
        return $this->bcc;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    public function setCc($cc)
    {
        $this->cc = $cc;
        return $this;
    }

    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

}
