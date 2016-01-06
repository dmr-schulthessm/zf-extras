<?php

namespace ZfExtra\Mail\Attachment;

use Zend\Mime\Part;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
interface AttachmentInterface
{
    /**
     * 
     * @param string $content
     */
    public function setContent($content);
    
    /**
     * @return Part
     */
    public function asMimePart();
}
