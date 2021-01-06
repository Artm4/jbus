<?php
// summary
//		Test file to handle image uploads (remove the image size check to upload non-images)
//
//		This file handles both Flash and HTML uploads
//
//		NOTE: This is obviously a PHP file, and thus you need PHP running for this to work
//		NOTE: Directories must have write permissions
//		NOTE: This code uses the GD library (to get image sizes), that sometimes is not pre-installed in a
//				standard PHP build.
//
error_reporting(~E_NOTICE);

class UploadResult
{
    protected $fileSource;
    protected $fileDestination;
    protected $error='';
    protected $succeed=false;
        
    function __construct(File $file)
    {
        $this->file=$file;
    }    
    public function getFile()
    {
        return $this->file;
    }        
    public function hasError()
    {
        return $this->error!='';
    }    
    public function setError($error)
    {
        $this->error=$error;
    }    
    public function setSucceed()
    {
        $this->succeed=true;
    }    
    public function setFailed()
    {
        $this->succeed=false;
    }
    public function isSuccessfull()
    {
        return $this->succeed;
    }
}

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

class UploadFile
{
    protected $fileMeta;
    protected $tmpFileList=array();
    protected $total=0;
    protected $uploadResultList=array();
    
    function __construct($files)
    {
        $this->fileMeta=$files;        
        $this->loadTmpFileMeta();
    }    
    /**
     * Get mimetype
     * @return File[] 
     */
    public function getTmpFileList()
    {
        return $this->tmpFileList;
    }
    /**
     * Get upload result
     * @return UploadResult[]
     */
    public function getUploadResult()
    {
        return $this->uploadResultList;
    }
    public function move($uploadPath)
    {
        foreach($this->tmpFileList as $file)
        {
            //could use $file->getBasename()
            $destinationFileName=$uploadPath . '/' . md5(uniqid('',true));
            $this->uploadResultList[]=$this->moveFile($file,$destinationFileName);
        }
    }    
    public function getTotal()
    {
        return $this->total;
    }    
    public function moveFile(File $file,$destinationFileName)
    {       
        $moved = move_uploaded_file($file->getPathname(),  $destinationFileName);        
        if($moved)
        {
            $fileUploaded=new File($destinationFileName);
            $fileUploaded->setOriginalName($file->getOriginalName());
            $result=new UploadResult($fileUploaded);
            $result->setSucceed();
        }
        else
        {
            $result=new UploadResult($file);
            $result->setFailed();
        }
        return $result;
    }    
    protected function loadTmpFileMeta()
    {
        if(isset($this->fileMeta['uploadedfiles']['name']) )
        {
            $this->total=count($this->fileMeta['uploadedfiles']['name']);
            for($i=0;$i<$this->total;$i++)
            {
                $file=new File($this->fileMeta['uploadedfiles']['tmp_name'][$i]);
                $file->setOriginalName($this->fileMeta['uploadedfiles']['name'][$i]);
                $this->tmpFileList[]=$file;
            }
        }
        elseif( isset($this->fileMeta['uploadedfile']) )
        {
            $this->total=1;
            $file=new File($this->fileMeta['uploadedfile']['tmp_name']);
            $file->setOriginalName($this->fileMeta['uploadedfile']['name']);
            $this->tmpFileList[]=$file;
        }
    }    
}
