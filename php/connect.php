<?php

	$link = '';

	function connect(){
		$con=mysqli_connect('localhost', 'root', '', 'cmf');
		return $con;
	}

	function clean($input){
		$con=connect();
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		//	$input = addslashes($input);
		$input = mysqli_real_escape_string($con, $input);
		mysqli_close($con);
		return $input;
	}

	function encyptMd5($pass){
		$cifrado=md5("$pass");
		return $cifrado;
	}

	function saveError($txt){
		$fecha=date("Y-m-d_h:m");
		$content = "Log: $fecha"."\r\n".$txt;
		$fechaU=date("U");

		$dir="log";
		if (!file_exists($dir) && !is_dir($dir)) {
			mkdir($dir);         
		} 
		$fp = fopen("log/Log_$fechaU.txt","wb");
		fwrite($fp,$content);
		fclose($fp);
	}

	function checkInput($array){
		foreach($array as $key => $value){
			if(empty($value)){
				$array[$key]="null";
			}
			else{
				$value=clean($value);
				$array[$key]="'$value'";
			}
		}
	}

	function ejec($sql){
		global $link;

		if ( ($link == '') ) $link = connect();
		$txt="";
		if (mysqli_connect_errno()) {
			$txt="Connect failed: %s\n". mysqli_connect_error();
			exit();
		}
		$reg=mysqli_query($link, $sql);
		if (!$reg) {
			$txt .="Errormessage (query): %s\n". mysqli_error($link);
		}

		if($txt!=""){
			saveError($txt);
		}
		//mysqli_close($link); 
		return $reg;
	}

	function queryToXml($query, $parentName, $childName){
		$xml = new SimpleXMLElement('<'.$parentName.'/>');
		$arrayP=queryToArray($query);
		foreach($arrayP as $array) {
			$reg=$xml->addChild($childName);
			foreach($array as $key => $value) {
				$reg->addChild(strtolower($key), $value);
			}
		}
		//Header('Content-type: text/xml');     	
		return $xml;

	}

	function queryToArray($query){
		$array=array();
		while($row=mysqli_fetch_array($query, MYSQL_ASSOC)){
			$array[]=$row;
		}
		return $array;
	}

	function queryToJSON($query){
		$array=queryToArray($query);
		//header("Content-type:application/json");
		return json_encode($array);   
	}

?>