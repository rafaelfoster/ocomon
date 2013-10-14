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


	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	if (!isset($_GET['COD'])) {
		print TRANS('MSG_ERROR_PARAM','ERRO: Falta parâmentro de entrada')."!";
		exit;
	} else {

		print "<BR><B>".TRANS('TTL_LIST_FILES','Arquivos anexados à ocorrência número').":&nbsp;".$_GET['COD']."</B><BR>";
		print "<TABLE border='0'>";

		$qryTela = "select * from imagens where img_oco = ".$_GET['COD']."";
		$execTela = mysql_query($qryTela) or die ("NÃO FOI POSSÍVEL RECUPERAR AS INFORMAÇÕES DOS ARQUIVOS ANEXOS!");
		//$rowTela = mysql_fetch_array($execTela);
		$isTela = mysql_num_rows($execTela);
		$cont = 0;
		while ($rowTela = mysql_fetch_array($execTela)) {
		//if ($isTela !=0) {
			$cont++;
			print "<tr>";
			$size = round($rowTela['img_size']/1024,1);
			print "<TD  bgcolor='".TD_COLOR."' >Anexo ".$cont."&nbsp;[".$rowTela['img_tipo']."]<br>(".$size."k):</td>";

			//if(eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $rowTela["img_tipo"])) {
			if(isImage($rowTela["img_tipo"])) {
				$viewImage = "&nbsp;<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?".
					"file=".$_GET['COD']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\" ".
					"title='View the file'><img src='../../includes/icons/kghostview.png' width='16px' height='16px' border='0'></a>";
			} else {
				$viewImage = "";
			}
			print "<td colspan='5' ><a onClick=\"redirect('../../includes/functions/download.php?".
					"file=".$_GET['COD']."&cod=".$rowTela['img_cod']."')\" title='Download the file'>".
					"<img src='../../includes/icons/attach2.png' width='16px' height='16px' border='0'>".
					"".$rowTela['img_nome']."</a>".$viewImage."</TD>";
			print "</tr>";
		}
		print "</table>";
	}

	print "</body>";
	print "</html>";
?>