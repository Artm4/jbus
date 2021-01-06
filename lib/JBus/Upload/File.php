<?php
namespace JBus\Upload;

class File extends \SplFileInfo
{
    /**
     * File mimetype (e.g. "image/png")
     * @var string
     */
    protected $mimetype;   
    protected $width;
    protected $height;
    protected $dimensions;
    protected $originalName='';
    
    protected function calculateDimensions()
    {
        if(!$this->dimensions)
        {
            $this->dimensions=getimagesize($this->getPathname());
            list($this->width, $this->height) = $this->dimensions;
        }                
    }    
    public function setOriginalName($name)
    {
        $this->originalName=$name;
        return $this;
    }    
    public function getOriginalName()
    {
        return $this->originalName;
    }    
    public function getWidth()
    {
        $this->calculateDimensions();
        return $this->width;
    }    
    public function getHeight()
    {
        $this->calculateDimensions();
        return $this->height;
    }
    /**
     * Get mimetype
     * @return string
     */
    public function getMimetype()
    {
        if (!isset($this->mimeType)) {
            $finfo = new \finfo(FILEINFO_MIME);
            $mimetype = $finfo->file($this->getPathname());
            $mimetypeParts = preg_split('/\s*[;,]\s*/', $mimetype);
            $this->mimetype = strtolower($mimetypeParts[0]);
            unset($finfo);
        }
    
        return $this->mimetype;
    }
}