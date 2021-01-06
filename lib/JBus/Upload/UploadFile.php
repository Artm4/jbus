<?php
namespace JBus\Upload;

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