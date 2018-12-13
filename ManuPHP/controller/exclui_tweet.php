<?php

	session_start();

	if(!isset($_SESSION['usuario'])){
		header('Location: index.php?erro=1');
	}

	require_once('db.class.php');

	$texto_tweet = $_POST['texto_tweet'];
	$id_usuario = $_SESSION['id_usuario'];

	if($texto_tweet == '' || $id_usuario == ''){
		die();
	}

	$objDb = new db();
	$link = $objDb->conecta_mysql();
	
	$sql = " DELETE id_usuario, id_tweet FROM tweet";

	mysqli_query($link, $sql);

?>