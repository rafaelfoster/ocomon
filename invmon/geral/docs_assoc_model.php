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

	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);
	$auth = new auth;

	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$hoje = date("Y-m-d");

	$cor  = TD_COLOR;
	$cor1 = TD_COLOR;
	$cor3 = BODY_COLOR;

	$query= "SELECT * from materiais where mat_modelo_equip in (".$_GET['model'].") order by mat_caixa,mat_nome";
	$resultado = mysql_query($query);
	$linhas = mysql_num_rows($resultado);

	print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%'>";

	print "<tr><td class='line'>&nbsp;</TD></tr>";
	print "<tr><td width='100%' align='left'><b>Documentos associados a esse modelo de equipamento.</b></td></tr>";

	print "<td class='line'>";
	print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%'>";
	$i=0;
	$j=2;
	print "</TABLE>";

	if ($linhas == 0) {
		print //"<fieldset>".
			"<table align='center'>".
			"<tr><td align='center'>".mensagem('Não existem documentos associados para esse modelo de equipamento!')."</td></tr>".
			"<tr><td align='center'><input type='button' value='Fechar' class='minibutton' onClick='self.close();'></td></tr>".
			"</table>";
			//"</fieldset>";
	} else {

		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%'>";

		print "<TR>";
			print "<TD bgcolor='".$cor1."'><b>Documento</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>Localização</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>Observaçao</b></TD>";
		print "</tr>";

		$j = 2;
		while ($row = mysql_fetch_array($resultado))  {
			if ($j % 2)
			{
				$color = $cor3;
			}
			else
			{
				$color = $cor;
			}
			$j++;

			print "<TR>";
				print "<TD bgcolor='".$color."'>".$row['mat_nome']."</TD>";
				print "<TD bgcolor='".$color."'>Caixa ".$row['mat_caixa']."</TD>";
				print "<TD bgcolor='".$color."'>".$row['mat_obs']."</TD>";
			print "</tr>";
		}
		print "</table>";
	}

print "</BODY>";
print "</HTML>";
?>