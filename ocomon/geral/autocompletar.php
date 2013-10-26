<?php

	include ("../../includes/include_geral_III.inc.php");
	session_start();

	if ( !isset($_REQUEST['term']) )
	    exit;

	$search = $_GET['search'];

	switch ($search) {

		case "contatos":
			$query = "nome, email";
			$table = "usuarios";
			$field = "nome";
		break;

		case "computers":
			$query = "comp_inv, comp_sn, comp_nome";
			$table = "equipamentos";
			$field = "comp_inv";
		break;

		case "ramais":
			$query = "fone, nome";
			$table = "usuarios";
			$field = "fone";
		break;

		case "ocorrencias":
			$query = "prob_id, nome";
			$table = "usuarios";
			$field = "fone";
		break;
	}


	$result = mysql_query("SELECT " . $query ." FROM " . $table . "  WHERE " . $field . " like '" . mysql_real_escape_string($_REQUEST['term']) ."%' ") or die( mysql_error() );

	list($campo1,$campo2) = explode(",",$query);

	$campo1 = trim($campo1);
	$campo2 = trim($campo2);

	while ( $row = mysql_fetch_array($result) )
	{
		$data[] = array(
			'label' => $row[$campo1]. ', (' .$row[$campo2]. ') ',
			'value' => $row[$campo1]
		);


	}

	echo json_encode($data);
	flush();
?>
