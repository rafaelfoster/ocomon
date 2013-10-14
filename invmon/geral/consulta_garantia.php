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
	$cab->set_title(TRANS('TTL_INVMON'));
	$auth = new auth;

	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

        $cor  = BODY_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;

	$query = $QRY["garantia"];
	$query.= " and c.comp_inv=".$_GET['comp_inv']." and c.comp_inst=".$_GET['comp_inst']." ".
				"order by aquisicao";
	$resultado = mysql_query($query);
	$linhas = mysql_num_rows($resultado);
	$row = mysql_fetch_array($resultado);

	$dias = date_diff_dias(date("Y-m-d"),$row['vencimento']);
	if ($dias>=0) {
		$status=TRANS('TXT_IN_GUARANTEE');
		$statusColor='green';
		if ($dias!=1) $s=TRANS('TXT_DAYS');  else
		$s=' dia';
		$expira= $dias.$s;
	}
	else
	{
		$status=TRANS('TXT_VANQUISHED_GUARANTEE');
		$statusColor='red';
		$expira = TRANS('TXT_DIED');
	}

	print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%'>";

	print "<tr><td class='line'>&nbsp;</TD></tr>";
	print "<tr><td width='100%' align='left'><b>".TRANS('SUBTTL_CONTROL_GUARANTEE_FOR_MANUFACTURE')."</b></td></tr>";

	print "<td class='line'>";
	print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%' >";
	$i=0;
	$j=2;
	print "</TABLE>";

	if ($linhas == 0) {
		print "<fieldset>".
			"<table><p align='center'>".TRANS('TXT_GUARANTEE_TEXT_1')." <b>".TRANS('TXT_NO')."</b> ".TRANS('TXT_GUARANTEE_TEXT_2')." ".
						"<br>".TRANS('TXT_GUARANTEE_TEXT_3')."<br><br>".
			//"<a href='javascript:self.close()' class='likebutton'>Fechar</a></p>".
			"</table>".
			"</fieldset>";
	} else {
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%' bgcolor='".$cor3."'>";

 		print "<TR>";
			print "<TD bgcolor='".$cor1."'><b>".TRANS('OCO_FIELD_TAG')."</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>".TRANS('LINK_GUARANT')."</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>".TRANS('COL_TYPE')."</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>".TRANS('COL_VENDOR')."</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>".TRANS('OCO_CONTACT')."</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>".TRANS('TXT_EXPIRATION')."</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>".TRANS('TXT_REMAINING_TIME')."</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>".TRANS('MNL_STATUS')."</b></TD>";
		print "</tr>";

		print "<TR>";
			print "<TD bgcolor='".$cor."'>".$row['inventario']."</TD>";
			print "<TD bgcolor='".$cor."'>".$row['meses']." meses</TD>";
			print "<TD bgcolor='".$cor."'>".$row['garantia']."</TD>";
			print "<TD bgcolor='".$cor."'>".$row['fornecedor']."</TD>";
			print "<TD bgcolor='".$cor."'>".$row['contato']."</TD>";
			print "<TD bgcolor='".$cor."'>".$row['dia']."/".$row['mes']."/".$row['ano']."</TD>";
			print "<TD bgcolor='".$cor."'><font color='".$statusColor."'><b>".$expira."</b></font></TD>";
			print "<TD bgcolor='".$cor."'><font color='".$statusColor."'><b>".$status."</b></font></TD>";
		print "</tr>";
	}
		print "<tr><td colspan='8'>&nbsp;</td></tr>";
		print "<tr><td colspan='8' align='center'><input type='button' class='minibutton' value='".TRANS('LINK_CLOSE')."' onClick=\"javascript:self.close()\"</td></tr>";
		print "</table>";


print "</body>";
print "</html>";
?>