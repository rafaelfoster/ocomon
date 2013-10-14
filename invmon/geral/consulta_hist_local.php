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

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	print "<BR><B>Busca de equipamentos por localização antiga e/ou período de remanejamento:</font></font></B><BR>";

	print "<FORM name='form1' method='POST' action='mostra_consulta_hist_local.php' onSubmit='return valida()'>";
	print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
	NL();
	print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Tipo de equipamento: </b></TD>";
		print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'>";
		print "<SELECT class=select name='comp_tipo_equip'>";
		print "<option value=-1 selected>Todos</option>";
		$query = "SELECT * from tipo_equip  order by tipo_nome";
		$resultado = mysql_query($query);
		$linhas = mysql_numrows($resultado);
		while ($row = mysql_fetch_array($resultado))
		{
			print "<option value='".$row['tipo_cod']."'>".$row['tipo_nome']."</option>";
		}
		print "</SELECT>";
		print "</TD>";
		print "</tr>";

	print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Localização anterior:</b></TD>";
		print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'>";
		print "<SELECT class='select' name='comp_local' id='idComp_local'>";
		print "<option value=-1 selected>Selecione</option>";
		$query = "SELECT * from localizacao  order by local";
		$resultado = mysql_query($query);
		$linhas = mysql_numrows($resultado);
		while ($row = mysql_fetch_array($resultado))
		{
			print "<option value='".$row['loc_id']."'>".$row['local']."</option>";
		}
		print "</SELECT>";
		print "</TD>";
		print "</tr>";

// 	print "<TR>";
// 		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Data inicial:</b></TD>";
// 		print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'>".
// 				"<input type='text' class='text' disabled name='dInicio' size='10'></TD>";
// 	print "</tr>";
// 	print "<TR>";
// 		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Data final:</b></TD>";
// 		print "<TD colspan='3'  align='left' bgcolor='".BODY_COLOR."'>".
// 			"<input type='text' class='text' disabled name='dFinal' size='10'></TD>";
// 	print "</tr>";


	NL(4);
	print "<TR>";
		print "<TD bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='Consultar' name='ok'>";
		print "</TD>";
		print "<TD colspan='3' bgcolor='".BODY_COLOR."'>".
			"<INPUT type='reset' value='Cancelar' class='button' onClick='javascript:history.back()'></TD>";
	print "</TR>";

	print "</table>";
print "</form>";
?>
<script type="text/javascript">
<!--

	function valida(){

		//var ok = validaForm('idComp_inst','COMBO','Unidade',1);
		var ok = validaForm('idComp_local','COMBO','Localização',1);
		//if (ok) var ok = validaForm('idComp_inv','ETIQUETA','Etiqueta',1);

		return ok;

	}

//-->
</script>
<?
print "</body>";
print "</html>";
?>