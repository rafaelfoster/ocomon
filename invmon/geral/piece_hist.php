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

        $hoje = date("Y-m-d H:i:s");

        $cor  = TD_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;


	if (isset($_POST['from_menu'])){
		$BT_TEXT = "Voltar";
		$GETOUT = "javascript:history.back()";
	} else {
		$BT_TEXT = TRANS('LINK_CLOSE');
		$GETOUT = "javascript:self.close()";
	}

 	$query = "SELECT ".
 			"*, t.nome as tecnico ".
		"FROM ".

			"hist_pieces h ".
			"left join instituicao inst on inst.inst_cod = h.hp_comp_inst ".

			"left join usuarios t on t.user_id = h.hp_technician, ".

			"estoque e, itens i, modelos_itens m, localizacao l, usuarios u ".
		"WHERE ".
			"h.hp_piece_id = e.estoq_cod and ".
			"e.estoq_tipo = i.item_cod and ".
			"m.mdit_cod = e.estoq_desc and ".
			"m.mdit_tipo = i.item_cod and ".
			"h.hp_piece_local = l.loc_id and ".
			"h.hp_uid = u.user_id and ".
			"h.hp_piece_id = ".$_GET['piece_id']." ".
		"ORDER BY h.hp_date DESC";

	//print $query;
	$resultado = mysql_query($query) or die ($query);
	$resultado2 = mysql_query($query) or die ($query);
	$linhas = mysql_num_rows($resultado);
	$rowA = mysql_fetch_array($resultado);


        if ($linhas == 0)
	{
		echo mensagem(TRANS('TXT_NOT_FOUND_EQUIP_CAD_SYSTEM'));
		exit;
	} else
        if ($linhas>1){
                $texto = TRANS('TXT_PLACES');//Perfumaria: Apenas imprime o nome da coluna no plural se existirem mais de um local no histórico.
		print "<table border='0' cellspacing='1' summary=''";
		print "<TR>";
		print "<TD colspan='3' align='left' ><B>".TRANS('FOUND')." ".$linhas." ".TRANS('TXT_REG_OF_LOCALIZATION_FOR_EQUIP')."</B></TD>";
		print "</tr>";
		print "</table>";
		print "<tr><p><TD bgcolor='".$cor1."'><b>".TRANS('COL_TYPE').":\t</b>".$rowA['item_nome']."</TD></tr><br>";
		print "<tr><TD bgcolor='".$cor1."'><b>".TRANS('COL_MODEL').":\t</b>".$rowA['mdit_fabricante']."&nbsp;".$rowA['mdit_desc']."&nbsp;".$rowA['mdit_desc_capacidade']."&nbsp;".$rowA['mdit_sufixo']."</TD></tr><br>";
		print "<tr><TD bgcolor='".$cor1."'><b>".TRANS('COL_SN').":</b>\t".$rowA['estoq_sn']."</TD></tr><br></p>";

	}
	else {
		$texto = TRANS('OCO_LOCAL');
		print "<TR><TD bgcolor='".$cor1."'><B>".TRANS('TXT_FOUND_ONLY_ONE_REG_OF_LOCALIZATION_FOR_EQUIP')."</B></TD></TR>";
		print "<tr><p><TD bgcolor='".$cor1."'><b>".TRANS('COL_TYPE').":\t</b>".$rowA['item_nome']."</TD></tr><br>";
		print "<tr><TD bgcolor='".$cor1."'><b>".TRANS('COL_MODEL').":\t</b>".$rowA['mdit_fabricante']."&nbsp;".$rowA['mdit_desc']."&nbsp;".$rowA['mdit_desc_capacidade']."&nbsp;".$rowA['mdit_sufixo']."</TD></tr><br>";
		print "<tr><TD bgcolor='".$cor1."'><b>".TRANS('COL_SN').":</b>\t".$rowA['estoq_sn']."</TD></tr><br></p>";
	}
	print "</TD>";

        print "<td class='line'>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%'>";
        print "<TR class='header'><td class='line'><b>".$texto."</TD><TD class='line'>".TRANS('ASSOC_EQUIP_PIECES')."</TD>".
        		"<TD class='line'>".TRANS('COL_MODIF_IN')."</TD><td class='line'><b>".TRANS('COL_MODIF_FOR')."</TD>".
        		"<td class='line'>".TRANS('TECHNICIAN')."</td> ";
        $i=0;
        $j=2;

//	while ($row = mysql_fetch_array($resultado2)) {
		//if ($i==0) {
			//$atualmente = "<font ".$alerta.">[".TRANS('TXT_CURRENT_PLACE')."]</font>";  //Perfumaria: Serve para identificar para o usuário o local atual onde o equipamento está!!!
		//} else
			//$atualmente = '';

//		print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
//		print "<td class='line'><a ".$alerta." onClick= \"javascript: window.opener.location.href='mostra_consulta_comp.php?comp_local=".$row['local_cod']."&comp_tipo_equip=".$row['tipo']."&ordena=fab_nome,modelo,local,etiqueta'\">".$row['locais']." </a><b><font color='green'>".$atualmente."</font></b></td>";
//		print "<td class='line'><font ".$alerta.">".$row['DIA']."/".$row['MES']."/".$row['ANO']."</font></td>";

  //              print "</TR>";
	//}

	$j=2;
	while ($row = mysql_fetch_array($resultado2))
	{
		if ($j % 2)
		{
			$trClass = "lin_par";
		}
		else
		{
			$trClass = "lin_impar";
		}
		$j++;
		print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
		print "<td class='line'>".$row['local']."</td>";
		print "<td class='line'>".NVL($row['inst_nome']."&nbsp;".$row['hp_comp_inv'])."</td>";
		print "<td class='line'>".NVL(formatDate($row['hp_date']))."</td>";
		print "<td class='line'>".NVL($row['nome'])."</td>";
		print "<td class='line'>".NVL($row['tecnico'])."</td>";

		print "</TR>";
	}



		print "<tr><td colspan='4'>&nbsp;</td></tr>";
		print "<tr><td colspan='4' align='center'><input type='button' class='minibutton' value='".$BT_TEXT."' onClick=\"".$GETOUT."\"</td></tr>";

	print "</TABLE>";

print "</BODY>";
print "</HTML>";
?>