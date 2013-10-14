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

        $query = "SELECT * FROM ocorrencias WHERE numero='".$_GET['numero']."'";
        $resultado = mysql_query($query);

        $query2 = "SELECT * FROM assentamentos WHERE ocorrencia='".$_GET['numero']."'";
        $resultado2 = mysql_query($query2);
        $linhas=mysql_numrows($resultado2);


print "<HTML>";
print "<BODY>";
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

	if (isset($_GET['numero']) && $_SESSION['s_nivel'] ==1) {

		$query = "DELETE FROM ocorrencias WHERE numero=".$_GET['numero']."";
		$resultado = mysql_query($query) or die ('ERRO NA TENTATIVA DE EXCLUIR O REGISTRO!<BR>'.$query);

		//$qryAssent = "SELECT * FROM assentamentos WHERE numero = ".$_GET['numero']."";
		$query2 = "DELETE FROM assentamentos WHERE ocorrencia = ".$_GET['numero']."";
		$resultado2 = mysql_query($query2) or die ('ERRO NA TENTATIVA DE EXCLUIR O REGISTRO!<BR>'.$query2);

		$query3 = "DELETE FROM tempo_status WHERE ts_ocorrencia = ".$_GET['numero']."";
		$resultado3 = mysql_query($query3) or die ('ERRO NA TENTATIVA DE EXCLUIR O REGISTRO!<BR>'.$query3);


		if (($resultado == 0) || ($resultado2==0) || ($resultado3==0))
		{
			$aviso = "Um erro ocorreu ao tentar excluir a ocorrência do sistema.";
		}
		else
		{
			$aviso = "OK. Ocorrência excluida com sucesso.";
		}

		print "<script>mensagem('".$aviso."'); redirect('ocorrencias.php');</script>";
	} else {
		//print "<script>mensagem('".$aviso."'); redirect('ocorrencias.php');</script>";
	}
print "</body>";
print "</html>";
?>