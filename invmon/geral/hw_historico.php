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

	$cab = new headers;
	$cab->set_title(TRANS('TTL_INVMON'));
	$auth = new auth;

	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

        $cor  = TD_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;


	$query = "select u.nome, i.item_nome, m.*, h.hwa_data, ins.inst_nome  from usuarios u, itens i, modelos_itens m, hw_alter h, instituicao ins
				where i.item_cod = m.mdit_tipo  and m.mdit_cod = h.hwa_item and h.hwa_user = u.user_id and hwa_inst = ins.inst_cod
				and hwa_inv=".$_GET['inv']." and hwa_inst=".$_GET['inst']."
				order by h.hwa_data, i.item_nome";

		//echo($query);
	$resultado = mysql_query($query) or die(TRANS('MSG_ERR_RESCUE_INFO_HW').'<BR>'.$query);
        $linhas = mysql_num_rows($resultado);

        if ($linhas == 0)
        {
                print "<script>mensagem('".TRANS('TXT_NOT_REG_ALTER_HW')."'); window.close();</script>";
                exit;
        } else {
		print "<table border='0' cellspacing='1' summary=''";
		print "<TR>";
		print "<TD width='600' align='left' ><B>".TRANS('FOUND')." $linhas ".TRANS('TXT_ALTER_REG_OF_HW_FOR_EQUIP')."</B></TD>";
		print "<TD width='200' align='left' ><B></b></td>";
		print "<TD width='224' align='left' ><B></b></td>";
		print "</tr>";
		print "</table>";

		$qryInst = "SELECT * FROM instituicao WHERE inst_cod = '".$_GET['inst']."'";
		$execInst = mysql_query($qryInst) or die(TRANS('MSG_ERR_RESCUE_INFO_INSTIT').'<BR>'.$qryInst);
		$rowInst = mysql_fetch_array($execInst);

		print "<br><table border='0' cellspacing='1' summary=''";
		print "<tr><td class='line'><b>Etiqueta:\t</b>".$_GET['inv']."</TD></tr>";
		print "<tr><td class='line'><b>Unidade:\t</b>".$rowInst['inst_nome']."</TD></tr>";
		print "</table><br>";

		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%'>";
		print "<TR class='header'><td class='line'>".TRANS('COL_COMPONENT')."</TD><td class='line'>".TRANS('COL_DESC')."</TD><td class='line'>".TRANS('COL_MODIF_IN')."</td><td class='line'>".TRANS('COL_MODIF_FOR')."</td></tr>";
		$j=0;
		while ($row = mysql_fetch_array($resultado)) {

			if ($j % 2){
				$trClass = "lin_par";
			} else {
				$trClass = "lin_impar";
			}
			$j++;
			print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";

			print "<td class='line'>".$row['item_nome']."</TD>";
			print "<td class='line'>".$row['mdit_fabricante']."&nbsp;".$row['mdit_desc']."&nbsp;".$row['mdit_desc_capacidade']."&nbsp;".$row['mdit_sufixo']."</td>";
			print "<td class='line'>".datab($row['hwa_data'])."</td>";
			print "<td class='line'>".$row['nome']."</td>";


			print "</tr>";
		}
			print "<tr><td colspan='4'>&nbsp;</td></tr>";
			print "<tr><td colspan='4' align='center'><input type='button' class='minibutton' value='".TRANS('LINK_CLOSE')."' onClick=\"javascript:self.close()\"</td></tr>";

		print "</TABLE>";
	}

?>
</BODY>
</HTML>
