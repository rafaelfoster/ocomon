<?
 /*                        Copyright 2005 Flávio Ribeiro

         This file is part of OCOMON.

         OCOMON is free software; you can redistribute it and/or modify
         it under the terms of the GNU General Public License as published by
         the Free Software Foundation; either version 2 of the License, or
         (at your option) any later version.

         OCOMON is distributed in the hope that it will be useful,
         but WITHOUT ANY WARRANTY; without even the implied warranty of
         MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
         GNU General Public License for more details.

         You should have received a copy of the GNU General Public License
         along with Foobar; if not, write to the Free Software
         Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  */session_start();

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	$auth = new auth;
	if (isset($_GET['popup'])) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);


        $aviso = "";
        $texto = "";

	$sql = "SELECT * FROM ocorrencias WHERE equipamento = ".$_GET['comp_inv']." AND instituicao = ".$_GET['comp_inst']." ";
	$exec = mysql_query($sql) or die ('NÃO FOI POSSÍVEL RECUPERAR AS INFORMAÇOES DO REGISTRO!<BR> '.$sql);

	$regs = mysql_num_rows($exec);
	if ($regs >0) {
		$aviso = "Não é possível excluir esse equipamento pois existem ocorrências vinculadas a ele no sistema!";

	} else {

		$query = "DELETE FROM equipamentos WHERE comp_inst=".$_GET['comp_inst']." and comp_inv=".$_GET['comp_inv']."";
		$resultado = mysql_query($query) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇOES DO REGISTRO!<BR>'.$query);

		$sql = "DELETE FROM historico WHERE hist_inv = ".$_GET['comp_inv']." and hist_inst= ".$_GET['comp_inst']."";
		$resultadoSQL = mysql_query($sql);



		if ($resultado == 0)
		{
				$aviso = "".$TRANS["alerta_erro_excluir"]."!";
		} else
		{
			$aviso = "".$TRANS["alerta_sucesso_excluir"]."!";
			$texto = " Excluído: Etiqueta= ".$_GET['comp_inv'].", Unidade= ".$_GET['comp_inst']."";
			geraLog(LOG_PATH.'invmon.txt',date("d-m-Y h:i:s"),$_SESSION['s_usuario'],'exclui_dados_computador.php',$texto);
		}

	}

	print "<script language='javascript'>mensagem('".$aviso." ".$texto."'); history.back(); </script>";

?>
