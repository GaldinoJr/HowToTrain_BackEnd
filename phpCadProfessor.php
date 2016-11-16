<?php
	//Produção
	// $host = "mysql.hostinger.com.br";
	// $bd = "u713684323_htt";
	// $user = "u713684323_htt";
	// $senha = "Htt@1234";

	//Local
	$host = "localhost";
	$bd = "HowToTrain";
	$user = "root";
	$senha = "nkr5vdyi";


	$connect = mysqli_connect($host,$user,$senha,$bd);
	if (mysqli_connect_errno())
	{
		die("Failed to connect to MySQL: " . mysqli_connect_error());
	}
	if(strcmp('SEND-json', $_POST['method']) == 0)
	{
		$professor = utf8_encode($_POST['json']);
		$professor = json_decode($professor);
		$query  = $professor->query;
		$operacao = $professor->operacao;
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
			$cd_busca = $ids[2]; // Pega o id do endereço inserido
		}
		else if(strcmp($operacao,"alterar") == 0)
			$cd_busca = $professor->cd_endereco_busca;
		$return = array();
		if (!$result)
		{
			 echo 'Error01'; // Não inseriu todos os registros
			 mysqli_close($connect);
		}
		else // Inseriu todos os registro?
		{
			if(!is_resource($connect )) // Não está conectado?
				$connect = mysqli_connect($host,$user,$senha,$bd);
			if (mysqli_connect_errno())
			{
				die("Failed to connect to MySQL: " . mysqli_connect_error());
			}
			$query = "SELECT prof.cd_professor, prof.nr_anos_lecionando, prof.cd_pessoa, " .
				" p.dt_nascimento, p.ind_sexo, p.fg_professor, p.cd_registro," .
				" r.ind_registro, r.ds_nome, " .
				" e.cd_endereco, e.nr_cep, e.ds_endereco, e.ds_bairro, e.cd_cidade" .
				" FROM tb_professor prof" .
				" INNER JOIN tb_pessoa p ON p.cd_pessoa = prof.cd_pessoa" .
				" INNER JOIN tb_registro r ON r.cd_registro = p.cd_registro" .
				" INNER JOIN tb_endereco e ON e.cd_endereco = r.cd_endereco" .
				" WHERE r.cd_endereco = " . $cd_busca; // Cd da primeira tabela inserida no script anterior
			$result = mysqli_query($connect,$query);
			$return = array();

			if (!$result)
			{
				 echo 'Erro: 02'; // Não localizou os registros
				 mysqli_close($connect);
			}
			else // Inseriu todos os registro?
			{
				if (mysqli_num_rows($result) > 0)
				{
					while ($row = $result->fetch_assoc())
					{
						// Professor
						$row_array["cd_professor"] = $row["cd_professor"];
						$row_array["nr_anos_lecionando"] = $row["nr_anos_lecionando"];
						$row_array["cd_pessoa"] = $row["cd_pessoa"];
						// Pessoa
						$row_array["dt_nascimento"] = $row["dt_nascimento"];
						$row_array["ind_sexo"] = $row["ind_sexo"];
						$row_array["fg_professor"] = $row["fg_professor"];
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
		$professor = utf8_encode($_POST['json']);
		$professor = json_decode($professor);
		$query  = $professor->query;

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
			$query = "SELECT * FROM tb_endereco WHERE cd_professor = " . $ultimo_cd_inserido; // Pega o ultimo professor da tabela, que é o mais atual
			$result = mysqli_query($connect,$query);
			$return = array();

			if (mysqli_num_rows($result) > 0)
			{
				while ($row = $result->fetch_assoc())
				{
					$row_array["cd_professor"] = $row["cd_professor"];
					$row_array["nr_anos_lecionando"] = $row["nr_anos_lecionando"];
					$row_array["cd_pessoa"] = $row["cd_pessoa"];

					array_push($return,$row_array);

				}
			}
		}

		echo(json_encode($return));
		mysqli_close($connect);
	}

	else if(strcmp('CONSULTAR-json', $_POST['method']) == 0)
	{
		$professor = utf8_encode($_POST['json']);
		$professor = json_decode($professor);
		$query  = $professor->query;

		$result = mysqli_query($connect,$query);
		$return = array();

		if (mysqli_num_rows($result) > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				$row_array["cd_professor"] = $row["cd_professor"];
				$row_array["nr_anos_lecionando"] = $row["nr_anos_lecionando"];
				$row_array["cd_pessoa"] = $row["cd_pessoa"];

				array_push($return,$row_array);
			}
		}

		echo(json_encode($return));
		mysqli_close($connect);
	}
?>
