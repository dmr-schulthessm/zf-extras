<?php

namespace ZfExtra\Mail\Attachment;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class StringAttachment extends AbstractAttachment
{

    /**
     * 
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

}
