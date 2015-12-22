<?php

namespace ZfExtra\Mail\Attachment;

use ZfExtra\Mail\Attachment\AbstractAttachment;

/**
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class FileAttachment extends AbstractAttachment
{
    
    /**
     * Constructor.
     * 
     * @param array $params
     */
    public function __construct($file, array $params = array())
    {
        parent::__construct($params);

        $this->setContent($file);
        $this->setFilename(basename($file));
    }

    /**
     * 
     * @return string
     */
    public function getType()
    {
        if (null == $this->type) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $this->type = finfo_file($finfo, $this->content);
        }
        return $this->type;
    }

    /**
     * 
     * @param string $file
     */
    public function setContent($file)
    {
        if (is_file($file) && is_readable($file)) {
            $this->content = fopen($file, 'r');
        }
    }

}
