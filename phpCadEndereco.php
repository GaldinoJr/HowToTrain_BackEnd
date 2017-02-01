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
		$endereco = utf8_encode($_POST['json']);
		$endereco = json_decode($endereco);
		$query  = $endereco->query;

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
			$query = "SELECT * FROM tb_endereco WHERE cd_cidade = " . $ultimo_cd_inserido; // Pega o ultimo registro da tabela, que é o mais atual
			$result = mysqli_query($connect,$query);
			$return = array();

			if (mysqli_num_rows($result) > 0) 
			{
				while ($row = $result->fetch_assoc())
				{	
					$row_array["cd_endereco"] = $row["cd_endereco"];
					$row_array["nr_cep"] = $row["nr_cep"];
					$row_array["ds_endereco"] = $row["ds_endereco"];
					$row_array["ds_bairro"] = $row["ds_bairro"];
					$row_array["cd_cidade"] = $row["cd_cidade"];
					
					array_push($return,$row_array);

				}
			}
		}
		
		echo(json_encode($return));
		mysqli_close($connect);
	}
	
	else if(strcmp('CONSULTAR-json', $_POST['method']) == 0)
	{
		$endereco = utf8_encode($_POST['json']);
		$endereco = json_decode($endereco);
		$query  = $endereco->query;

		$result = mysqli_query($connect,$query);
		$return = array();

		if (mysqli_num_rows($result) > 0) 
		{
			while ($row = $result->fetch_assoc())
			{
				$row_array["cd_endereco"] = $row["cd_endereco"];
				$row_array["nr_cep"] = $row["nr_cep"];
				$row_array["ds_endereco"] = $row["ds_endereco"];
				$row_array["ds_bairro"] = $row["ds_bairro"];
				$row_array["cd_cidade"] = $row["cd_cidade"];

				 array_push($return,$row_array);
			}
		}

		echo(json_encode($return));
		mysqli_close($connect);
	}
?>