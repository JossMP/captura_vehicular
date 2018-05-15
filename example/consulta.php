<?php
	header('Content-Type: text/json');

	require ("../src/autoload.php");

	$Requisitoria = new \Pit\Pit();
	
	$nro = ( isset($_REQUEST["nro"]))? $_REQUEST["nro"] : false;
	echo json_encode( $Requisitoria->check( $nro ) );
?>
