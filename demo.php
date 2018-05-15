<?php
	// Modo de Uso
	require_once("src/autoload.php");
	
	$test = new \Pit\Pit();
	
	print_r( $test->check( "B1M140" ) ); // Sin Requisitoria
	
	print_r( $test->check( "SQZ949" ) ); // Con Requisitoria
?>
