<?php
	$host = "mysql.hostinger.com.br";
	$bd = "u713684323_htt";
	$user = "u713684323_htt";
	$senha = "Htt@1234";




	//Comentario

	//Outro Teste

	$connect = mysqli_connect($host,$user,$senha,$bd);
	if (mysqli_connect_errno())
	{
		die("Failed to connect to MySQL: " . mysqli_connect_error());
	}
	if(strcmp('SEND-json', $_POST['method']) == 0)
	{
		$academia = utf8_encode($_POST['json']);
		$academia = json_decode($academia);
		$query  = $academia->query;
		$operacao = $academia->operacao;
		$cd_busca = -1;
		$result = mysqli_multi_query($connect,$query);
		if(strcmp($operacao,"salvar") == 0)
		{
			$ids = array();
			$connect->next_result();
			 do
			 {
				 $ids[] = $connect->insert_id;
				 $connect->next_result();
			 } while($connect->more_results());
			$cd_busca = $ids[2]; // Pega o id do endere�o inserido
		}
		else if(strcmp($operacao,"alterar") == 0)
			$cd_busca = $academia->cd_endereco_busca;
		$return = array();
		if (!$result)
		{
			 echo 'Error01'; // N�o inseriu todos os registros
			 mysqli_close($connect);
		}
		else // Inseriu todos os registro?
		{
			if(!is_resource($connect )) // N�o est� conectado?
				$connect = mysqli_connect($host,$user,$senha,$bd);
			if (mysqli_connect_errno())
			{
				die("Falha ao conectar com o servidor: " . mysqli_connect_error());
			}
			$query = "SELECT a.cd_academia, a.cd_registro, " .
					" r.ind_registro, r.ds_nome, " .
					" e.cd_endereco, e.nr_cep, e.ds_endereco, e.ds_bairro, e.cd_cidade" .
					" FROM tb_academia a" .
					" INNER JOIN tb_registro r ON r.cd_registro = a.cd_registro" .
					" INNER JOIN tb_endereco e ON e.cd_endereco = r.cd_endereco" .
					" WHERE r.cd_endereco = " . $cd_busca; // Cd da primeira tabela inserida no script anterior
			$result = mysqli_query($connect,$query);
			$return = array();

			if (!$result)
			{
				 echo 'Erro: 02'; // N�o localizou os registros
				 mysqli_close($connect);
			}
			else // Inseriu todos os registro?
			{
				if (mysqli_num_rows($result) > 0)
				{
					while ($row = $result->fetch_assoc())
					{
						// Academia
						$row_array["cd_academia"] = $row["cd_academia"];
						$row_array["cd_registro"] = $row["cd_registro"];
						// Registro
						$row_array["ind_registro"] = $row["ind_registro"];
						$row_array["ds_nome"] = $row["ds_nome"];
						// Endereco
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
	}
	else if(strcmp('SALVAR-json', $_POST['method']) == 0)
	{
		$academia = utf8_encode($_POST['json']);
		$academia = json_decode($academia);
		$query  = $academia->query;

		$result = mysqli_query($connect,$query);
		$return = array();
		if (!$result)
		{
			 echo 'Error01';
			 mysqli_close($connect);
		}
		else
		{
			echo $connect->insert_id; // Fun��o que vai devolver o �ltimo ID inserido
			$ultimo_cd_inserido = $connect->insert_id;
			$query = "SELECT * FROM tb_endereco WHERE cd_academia = " . $ultimo_cd_inserido; // Pega o ultimo academia da tabela, que � o mais atual
			$result = mysqli_query($connect,$query);
			$return = array();

			if (mysqli_num_rows($result) > 0)
			{
				while ($row = $result->fetch_assoc())
				{
					$row_array["cd_academia"] = $row["cd_academia"];
					$row_array["cd_registro"] = $row["cd_registro"];

					array_push($return,$row_array);

				}
			}
		}

		echo(json_encode($return));
		mysqli_close($connect);
	}

	else if(strcmp('CONSULTAR-json', $_POST['method']) == 0)
	{
		$academia = utf8_encode($_POST['json']);
		$academia = json_decode($academia);
		$query  = $academia->query;

		$result = mysqli_query($connect,$query);
		$return = array();

		if (mysqli_num_rows($result) > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				$row_array["cd_academia"] = $row["cd_academia"];
				$row_array["cd_registro"] = $row["cd_registro"];

				array_push($return,$row_array);
			}
		}

		echo(json_encode($return));
		mysqli_close($connect);
	}
?>
