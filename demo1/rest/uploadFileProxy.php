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
session_start();
error_reporting(~E_NOTICE);
require("tests/cLOG.php");

function trace($txt, $isArray=false){
	//creating a text file that we can log to
	// this is helpful on a remote server if you don't
	//have access to the log files
	//
	$log = new cLOG("tests/upload.txt", false);
	//$log->clear();
	if($isArray){
		$log->printr($txt);
	}else{
		$log->write($txt);
	}

	//echo "$txt<br>";
}

trace("---------------------------------------------------------");

//
//
//	EDIT ME: According to your local directory structure.
// 	NOTE: Folders must have write permissions
//
$upload_path = "tests/uploads/"; 	// where image will be uploaded, relative to this file
$download_path = "tests/uploads/";	// same folder as above, but relative to the HTML file

//
// 	NOTE: maintain this path for JSON services
//
require("tests/resources/JSON.php");
$json = new Services_JSON();

//
// 	Determine if this is a Flash upload, or an HTML upload
//
//

//		First combine relavant postVars
$postdata = array();
$htmldata = array();
$data = "";
trace("POSTDATA: " . count($_FILES) . " FILES");
trace("$_FILES: " . print_r($_FILES,1));
foreach ($_POST as $nm => $val) {
	$data .= $nm ."=" . $val . ",";	// string for flash
	$postdata[$nm] = $val;			// array for html
}

trace($postdata, true);

foreach ($_FILES as $nm => $val) {
	trace("   file: ".$nm ."=" . $val);
}

foreach ($_GET as $nm => $val) {
	trace($nm ."=" . $val);
}
$_SESSION['files']=$_FILES;
$fieldName = "flashUploadFiles";//Filedata";
$data=$json->encode($_FILES);
if( isset($_FILES[$fieldName]) || isset($_FILES['uploadedfileFlash'])){	
		echo($data);		
		return;
}elseif( isset($_FILES['uploadedfile0']) ){	

}elseif( isset($_POST['uploadedfiles']) ){
  trace("HTML5 multi file input... CAN'T ACCESS THIS OBJECT! (POST[uploadedfiles])");
  trace(count($_POST['uploadedfiles'])." ");


}elseif( isset($_FILES['uploadedfiles']) ){	
	print $data;
	return $data;

}elseif( isset($_FILES['uploadedfile']) ){	
	print $data;
	return $data;
}else{
	$data = array("ERROR" => "Improper data sent - no files found");
	$data=$json->encode($_FILES);
}
?>

<textarea style="width:600px; height:150px;"><?php print $data; ?></textarea>
