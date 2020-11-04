<?php
class ControllerAccountUploadimage extends Controller {
	public function index() {
		if(isset($_FILES["upload"])){

	      	$filename=date("h:i:sa").$_FILES["upload"]["name"];
			$file_public_addr = DIR_UPLOAD.$filename.$ext;

			$success=move_uploaded_file($_FILES["upload"]["tmp_name"], $file_public_addr);
			if( $success){
				$json["uploaded"]=true;
				$json["url"]=$localfileaddr;
        		json_encode($json);
			}
			if(!$success){
				$json["uploaded"]=false;
				$json["error"]=array("message"=>"Error Uploaded");
	        	json_encode($json);
			}
		}
		

	}

}