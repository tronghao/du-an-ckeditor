<?php
class ControllerAccountUploadimage extends Controller {
	public function index() {
		if(isset($_FILES['upload']['name']))
		{
			 $file = $_FILES['upload']['tmp_name'];
			 $file_name = $_FILES['upload']['name'];
			 $file_name_array = explode(".", $file_name);
			 $extension = end($file_name_array);
			 $new_image_name = rand() . $file_name_array[0] . '.' . $extension;

			 $allowed_extension = array("jpg", "gif", "png");
			 if(in_array($extension, $allowed_extension))
			 {
			  move_uploaded_file($file, DIR_UPLOAD . $new_image_name);
			  $function_number = $_GET['CKEditorFuncNum'];
			  $url = HTTP_SERVER. "system/upload/" . $new_image_name;
			  $message = '';
			  echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '$url', '$message');</script>";
			 }
		}
	}
}




