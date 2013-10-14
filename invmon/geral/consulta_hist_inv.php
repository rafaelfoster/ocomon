<?php 
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
	$cab->set_title(TRANS('TTL_INVMON'));

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

?>


<script type="text/javascript">
<!--

	function valida(){
		var ok = validaForm('idComp_inv','INTEIRO','<?php print TRANS('OCO_FIELD_TAG');?>',1);
		return ok;
	}

//-->
</script>

<?php 
	print "<br><B>".TRANS('SUBTTL_CONS_HIST_TAG').":</B><BR><br>";

	print "<FORM name='consulta' method='POST' action='mostra_historico.php' onSubmit='return valida()'>";
	print "<TABLE border='0'  width='40%' bgcolor='".BODY_COLOR."'>";
	print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_UNIT').":</font></font></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<SELECT class='select' name='comp_inst'>";
		$query = "SELECT * from instituicao  order by inst_nome";
		$resultado = mysql_query($query);
		$linhas = mysql_numrows($resultado);
		while ($row = mysql_fetch_array($resultado))
		{
			print "<option value='".$row['inst_cod']."'>".$row['inst_nome']."</option>";
		}
		print "</SELECT>";
		print "</TD>";
	print "</tr>";


	print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_TAG').":</font></font></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>".
				"<INPUT type='text' class='text' name='comp_inv' id='idComp_inv'>".
			"</TD>";
	print "</TR>";
	NL(2);
	print "<TR>";
		if (isset($_GET['from_menu'])) print "<input type='hidden' name='from_menu' value='1'>";
		print "<TD align='center' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('MNL_CON')."' name='ok'>";
		print "</TD>";
		print "<TD align='center' bgcolor='".BODY_COLOR."'>".
			"<INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' onClick='javascript:history.back()'></TD>";
	print "</TR>";
	print "</table>";
	print "</form>";

	print "</body>";
	print "</html>";
?>