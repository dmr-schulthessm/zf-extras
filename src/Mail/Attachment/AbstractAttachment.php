<?php

namespace ZfExtra\Mail\Attachment;

use Zend\Mime\Mime;
use Zend\Mime\Part;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
abstract class AbstractAttachment implements AttachmentInterface
{

    /**
     *
     * @var string
     */
    protected $encoding = Mime::ENCODING_BASE64;

    /**
     *
     * @var string
     */
    protected $type = Mime::TYPE_OCTETSTREAM;

    /**
     *
     * @var string
     */
    protected $charset = 'UTF-8';

    /**
     *
     * @var string
     */
    protected $filename;

    /**
     *
     * @var string
     */
    protected $disposition = Mime::DISPOSITION_ATTACHMENT;

    /**
     *
     * @var string
     */
    protected $content;

    /**
     * Constructor.
     * 
     * @param array $params
     */
    public function __construct(array $params)
    {
        if (isset($params['encoding'])) {
            $this->setEncoding($params['encoding']);
        }

        if (isset($params['type'])) {
            $this->setType($params['type']);
        }

        if (isset($params['charset'])) {
            $this->setCharset($params['charset']);
        }

        if (isset($params['filename'])) {
            $this->setFilename($params['filename']);
        }

        if (isset($params['disposition'])) {
            $this->setDisposition($params['disposition']);
        }

        if (isset($params['content'])) {
            $this->setContent($params['content']);
        }
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * 
     * @return string
     */
    public function getDisposition()
    {
        return $this->disposition;
    }

    /**
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 
     * @param string $encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * 
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * 
     * @param string $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * 
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * 
     * @param string $disposition
     */
    public function setDisposition($disposition)
    {
        $this->disposition = $disposition;
    }

    /**
     * @param string $content
     */
    abstract public function setContent($content);

    /**
     * 
     * @return Part
     */
    public function asMimePart()
    {
        $part = new Part($this->getContent());
        $part->disposition = $this->getDisposition();
        $part->encoding = $this->getEncoding();
        $part->type = $this->getType();
        $part->charset = $this->getCharset();
        $part->filename = $this->filename;
//        $part->filename = "=?utf-8?B?" . base64_encode($this->filename) . "?=";
        return $part;
    }

}
