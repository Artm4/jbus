<?php
namespace JBus\Upload;

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