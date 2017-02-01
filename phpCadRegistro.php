<?php
	$host = "mysql.hostinger.com.br";
	$bd = "u145718072_wl";
	$user = "u145718072_admin";
	$senha = "wlbr1234";
	
	$connect = mysqli_connect($host,$user,$senha,$bd);
	if (mysqli_connect_errno())
	{
		die("Failed to connect to MySQL: " . mysqli_connect_error());
	}
	
	if(strcmp('SALVAR-json', $_POST['method']) == 0)
	{
		$registro = utf8_encode($_POST['json']);
		$registro = json_decode($registro);
		$query  = $registro->query;

		$result = mysqli_query($connect,$query);
		$return = array();
		if (!$result) 
		{
			 echo 'Error01';
			 mysqli_close($connect);
		} 
		else 
		{
			echo $connect->insert_id; // Função que vai devolver o último ID inserido
			$ultimo_cd_inserido = $connect->insert_id;
			$query = "SELECT * FROM tb_endereco WHERE cd_registro = " . $ultimo_cd_inserido; // Pega o ultimo registro da tabela, que é o mais atual
			$result = mysqli_query($connect,$query);
			$return = array();

			if (mysqli_num_rows($result) > 0) 
			{
				while ($row = $result->fetch_assoc())
				{	
					$row_array["cd_registro"] = $row["cd_registro"];
					$row_array["ind_registro"] = $row["ind_registro"];
					$row_array["ds_nome"] = $row["ds_nome"];
					$row_array["cd_endereco"] = $row["cd_endereco"];
					
					array_push($return,$row_array);

				}
			}
		}
		
		echo(json_encode($return));
		mysqli_close($connect);
	}
	
	else if(strcmp('CONSULTAR-json', $_POST['method']) == 0)
	{
		$registro = utf8_encode($_POST['json']);
		$registro = json_decode($registro);
		$query  = $registro->query;

		$result = mysqli_query($connect,$query);
		$return = array();

		if (mysqli_num_rows($result) > 0) 
		{
			while ($row = $result->fetch_assoc())
			{
				$row_array["cd_registro"] = $row["cd_registro"];
				$row_array["ind_registro"] = $row["ind_registro"];
				$row_array["ds_nome"] = $row["ds_nome"];
				$row_array["cd_endereco"] = $row["cd_endereco"];

				 array_push($return,$row_array);
			}
		}

		echo(json_encode($return));
		mysqli_close($connect);
	}
?>