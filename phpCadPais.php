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
		$pais = utf8_encode($_POST['json']);
		$pais = json_decode($pais);
		$query  = $pais->query;

		$result = mysqli_query($connect,$query);
		$return = array();
		if (!$result) 
		{
			 echo 'Error01';
			 mysqli_close($connect);
		} 
		else 
		{
			echo $connect->insert_id; // Funзгo que vai devolver o ъltimo ID inserido
			$ultimo_cd_inserido = $connect->insert_id;
			$query = "SELECT * FROM tb_pais WHERE cd_pais = " . $ultimo_cd_inserido; // Pega o ultimo registro da tabela, que й o mais atual
			$result = mysqli_query($connect,$query);
			$return = array();

			if (mysqli_num_rows($result) > 0) 
			{
				while ($row = $result->fetch_assoc())
				{
					$row_array["cd_pais"] = $row["cd_pais"];
					$row_array["ds_pais"] = $row["ds_pais"];
					
					array_push($return,$row_array);

				}
			}
		}
		
		echo(json_encode($return));
		mysqli_close($connect);
	}
	
	else if(strcmp('CONSULTAR-json', $_POST['method']) == 0)
	{
		$pais = utf8_encode($_POST['json']);
		$pais = json_decode($pais);
		$query  = $pais->query;

		$result = mysqli_query($connect,$query);
		$return = array();

		if (mysqli_num_rows($result) > 0) 
		{
			while ($row = $result->fetch_assoc())
			{
				$row_array["cd_pais"] = $row["cd_pais"];
				$row_array["ds_pais"] = $row["ds_pais"];

				 array_push($return,$row_array);
			}
		}

		echo(json_encode($return));
		mysqli_close($connect);
	}
?>