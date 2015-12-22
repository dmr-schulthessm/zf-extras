<?php

namespace ZfExtra\Mail\Attachment;

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
}
