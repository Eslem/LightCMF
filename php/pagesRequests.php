<?php
	function save(){
		$name=$_POST['name'];
		$imgCode=$_POST['img'];
		$html=$_POST['html'];

		//-Image Thumbnail

		$uri =  substr($imgCode,strpos($imgCode,",")+1);

		// create a filename for the new image
		//$file = md5(uniqid()) . '.png';
		$file ="../pages/thumbnails/".$name. '.png';

		// decode the image data and save it to file
		file_put_contents($file, base64_decode($uri));
		
		
		file_put_contents("../pages/".$name. '.html', $html);
	}

	function remove(){
		$name=$_POST['name'];
		unlink("../pages/$name.html");
	}

	$wth=$_POST['wth'];

	if($wth=='save'){
		save();
	}else if($wth=='remove'){
		remove();
	}
?>
