<?php

namespace ZfExtra\Asset;

class AssetManager
{
    
    /**
     * 
     * @param string $sourceDir
     * @param string $targetDir
     * @return mixed
     */
    public function install($sourceDir, $targetDir)
    {
        if (file_exists($targetDir)) {
            unlink($targetDir);
        }
        return symlink($sourceDir, $targetDir);
    }


}
