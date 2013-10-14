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
		$status='Em garantia';
		$statusColor='green';
		if ($dias!=1) $s=' dias';  else
		$s=' dia';
		$expira= $dias.$s;
	}
	else
	{
		$status='Garantia vencida';
		$statusColor='red';
		$expira = 'Expirado';
	}

	print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%'>";

	print "<tr><td class='line'>&nbsp;</TD></tr>";
	print "<tr><td width='100%' align='left'><b>Controle de garantias do fabricante.</b></td></tr>";

	print "<td class='line'>";
	print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%' >";
	$i=0;
	$j=2;
	print "</TABLE>";

	if ($linhas == 0) {
		print "<fieldset>".
			"<table><p align='center'>Este equipamento <b>não</b> está cadastrado quando ao seu período de garantia! ".
						"<br>É necessário que o equipamento possua a data de compra e o tempo de garantia cadastrados ".
						"no sistema!<br><br>".
			//"<a href='javascript:self.close()' class='likebutton'>Fechar</a></p>".
			"</table>".
			"</fieldset>";
	} else {
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%' bgcolor='".$cor3."'>";

 		print "<TR>";
			print "<TD bgcolor='".$cor1."'><b>Etiqueta</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>Garantia</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>Tipo</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>Fornecedor</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>Contato</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>Vencimento</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>Tempo restante</b></TD>";
			print "<TD bgcolor='".$cor1."'><b>Status</b></TD>";
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
		print "<tr><td colspan='8' align='center'><input type='button' class='minibutton' value='Fechar' onClick=\"javascript:self.close()\"</td></tr>";
		print "</table>";


print "</body>";
print "</html>";
?>