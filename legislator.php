<?php
	header("Access-Control-Allow-Origin: *");
	file_put_contents("vote.js",$_POST['data']);
?>