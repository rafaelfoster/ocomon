<?php 
session_start();
  	include ("../../includes/config.inc.php");
  	include ("../../includes/classes/conecta.class.php");

	$conec = new conexao;
	$conec->conecta('MYSQL');

	//Secure the user data by escaping characters
	// and shortening the input string
	function clean($input, $maxlength) {
		$input = substr($input, 0, $maxlength);
		$input = EscapeShellCmd($input);
		return ($input);
	}

	$file = "";
	$file = clean($_GET['file'], 4);

	if (empty($file))
	exit;

	if(isset($_GET['cod']))
	{
		// if id is set then get the file with the id from database

		$query = "SELECT img_nome, img_tipo, img_size, img_bin FROM imagens WHERE  img_cod=".$_GET['cod']."";
		$result = mysql_query($query) or die("ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DA IMAGEM");
		//$data = @ mysql_fetch_array($result);

		//list($name, $type, $size, $content) = mysql_fetch_array($result);

		list($img_nome, $img_tipo, $img_size, $img_bin) =mysql_fetch_array($result);

		header("Content-length: ".$img_size."");
		header("Content-type: ".$img_tipo."");
		header("Content-Disposition: attachment; filename=".$img_nome."");
		echo $img_bin;

		exit;
	}
?>