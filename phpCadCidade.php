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
	
	if(strcmp('SALVAR-json', $_POST['method']) == 0)
	{
		$cidade = utf8_encode($_POST['json']);
		$cidade = json_decode($cidade);
		$query  = $cidade->query;

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
			$query = "SELECT * FROM tb_cidade WHERE cd_cidade = " . $ultimo_cd_inserido; // Pega o ultimo registro da tabela, que é o mais atual
			$result = mysqli_query($connect,$query);
			$return = array();

			if (mysqli_num_rows($result) > 0) 
			{
				while ($row = $result->fetch_assoc())
				{
					$row_array["cd_cidade"] = $row["cd_cidade"];
					$row_array["ds_cidade"] = $row["ds_cidade"];
					$row_array["cd_estado"] = $row["cd_estado"];
					
					array_push($return,$row_array);

				}
			}
		}
		
		echo(json_encode($return));
		mysqli_close($connect);
	}
	
	else if(strcmp('CONSULTAR-json', $_POST['method']) == 0)
	{
		$cidade = utf8_encode($_POST['json']);
		$cidade = json_decode($cidade);
		$query  = $cidade->query;

		$result = mysqli_query($connect,$query);
		$return = array();

		if (mysqli_num_rows($result) > 0) 
		{
			while ($row = $result->fetch_assoc())
			{
				$row_array["cd_cidade"] = $row["cd_cidade"];
				$row_array["ds_cidade"] = $row["ds_cidade"];
				$row_array["cd_estado"] = $row["cd_estado"];

				 array_push($return,$row_array);
			}
		}

		echo(json_encode($return));
		mysqli_close($connect);
	}
?>