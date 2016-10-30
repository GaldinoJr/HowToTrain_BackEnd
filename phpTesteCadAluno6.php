<?php
	$host = "mysql.hostinger.com.br";
	$bd = "u713684323_htt";
	$user = "u713684323_htt";
	$senha = "Htt@1234";
	
	
	$connect = mysqli_connect($host,$user,$senha,$bd);
	if (mysqli_connect_errno())
	{
		die("Failed to connect to MySQL: " . mysqli_connect_error());
	}
	
	$query = "SET @ultimo_id_inserido = -1; call pr_estado_inclui_altera('bb', @ultimo_id_inserido);";

	
	$result = mysqli_multi_query($connect,$query);
	

		
	echo(json_encode($return));
	mysqli_close($connect);

	
?>