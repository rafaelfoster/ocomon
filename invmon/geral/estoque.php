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
	include ("../../includes/classes/paging.class.php");
	print "<link rel='stylesheet' href='../../includes/css/calendar.css.php' media='screen'></LINK>";
	print "<html><head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script></head>";

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	print "<BODY bgcolor='".BODY_COLOR."' onLoad=\"";
		if (isset($_GET['cod'])) {
			print "ajaxFunction('idDivSelItemModel', 'showSelItemModels.php', 'idLoad', 'cod=idCodEstoque');";
		} else {
			print "ajaxFunction('idDivSelItemModel', 'showSelItemModels.php', 'idLoad');";
		}
	print "\">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);


	$fecha = "";
	if (isset($_REQUEST['popup'])) {
		$fecha = "javascript:window.close();";
	} else {
		$fecha = "javascript:history.back();";
	}

	//$readOnlyDateField = "readonly";
	$readOnlyDateField = "";

	print "<BR><B>".TRANS('ADM_WARE')."</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";


	$query = "SELECT ".
				"e.estoq_cod, e.estoq_tipo, e.estoq_desc, e.estoq_sn, e.estoq_comentario, e.estoq_tag_inv, e.estoq_tag_inst, ".
				"e.estoq_nf, e.estoq_warranty, e.estoq_value, e.estoq_data_compra, e.estoq_partnumber,  ".
				"i.item_nome,  ".
				"f.forn_nome, f.forn_cod, ".
				"t.tempo_meses, t.tempo_cod, ".
				"c.descricao as ccusto, c.codigo,  ".
				"m.mdit_fabricante as fabricante, m.mdit_desc as modelo, m.mdit_desc_capacidade as capacidade, m.mdit_sufixo as sufixo, ".
				"l.local, l.loc_id, ".
				"inst.inst_nome, ".
				"s.situac_nome, s.situac_cod, ".
				"eqp.eqp_equip_inv, eqp.eqp_equip_inst, ".
				"instEquip.inst_nome as instEquipamento ".
			"FROM ".
				"estoque e ".
				"left join instituicao as inst on inst.inst_cod = e.estoq_tag_inst ".
				"left join equipXpieces as eqp on eqp.eqp_piece_id = e.estoq_cod ".
				"left join instituicao as instEquip on instEquip.inst_cod = eqp.eqp_equip_inst ".
				"left join fornecedores as f on f.forn_cod = e.estoq_vendor ".
				"left join tempo_garantia as t on t.tempo_cod = e.estoq_warranty ".
				"left join CCUSTO as c on c.codigo = e.estoq_ccusto ".
				"left join situacao as s on s.situac_cod = e.estoq_situac, ".
				"itens i, modelos_itens m, localizacao l ".
			"WHERE ".
				"e.estoq_tipo = i.item_cod ".
				"and e.estoq_tipo = m.mdit_tipo ".
				"and e.estoq_desc = m.mdit_cod ".
				"and e.estoq_local = l.loc_id ";


		if (isset($_GET['cod'])) {
			$query.= " AND e.estoq_cod = ".$_GET['cod']." ";

			print "<input type='hidden' name='cod_estoque' id='idCodEstoque' value='".$_GET['cod']."'>";
		} else
		if (isset($_POST['cod'])) {
			$query.= " AND e.estoq_cod = ".$_POST['cod']." ";
		}

		$filtro = ""; //Variável que irá retornar qual é o filtro que está sendo aplicado na consulta.

		if (isset($_POST['estoque_tipo'])  && $_POST['estoque_tipo']!=-1) {
			$query.= " AND e.estoq_tipo = ".$_POST['estoque_tipo']." ";
		}
		if (isset($_POST['estoque_sn']) && !empty($_POST['estoque_sn'])) {

			$query.= " AND lower(e.estoq_sn) = lower('".$_POST['estoque_sn']."') ";
		}
		if (isset($_POST['estoque_partnumber'])  && !empty($_POST['estoque_partnumber'])) {
			$query.= " AND lower(e.estoq_partnumber) = lower('".$_POST['estoque_partnumber']."') ";
		}
		if (isset($_POST['estoque_local'])  && $_POST['estoque_local']!=-1) {
			$query.= " AND e.estoq_local = ".$_POST['estoque_local']." ";
		}
		if (isset($_POST['estoque_tag']) && !empty($_POST['estoque_tag'])) {
			$query.= " AND e.estoq_tag_inv = ".$_POST['estoque_tag']." ";
		}

		if (isset($_POST['estoque_unidade'])) {
			if ($_POST['estoque_unidade'] !='null')
				$query.= " AND e.estoq_tag_inst = ".$_POST['estoque_unidade']." ";
		}

		$query .=" ORDER BY i.item_nome, e.estoq_desc";

// 		print "CONEXÃO: ".SQL_USER."@".SQL_SERVER.".".SQL_DB;
// 		dump($_POST, 'VARIÁVEIS DE POST');
// 		dump($_GET,'VARIÁVEIS DE GET');
// 		print $query."<br>";

		$resultado = mysql_query($query) or die( TRANS('MSG_ERR_QRY_CONS')."<br>".$query);
		$registros = mysql_num_rows($resultado);

		if (isset($_GET['LIMIT']))
			$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));

		print "<div id='idLoad' class='loading' style='{display:none}'><img src='../../includes/imgs/loading.gif'></div>";

	if ((!isset($_GET['action'])) && (empty($_POST['submit']) || $_POST['submit'] == TRANS('BT_SEARCH')) ) {

		$PAGE->execSQL();

		print "<TR>".
				"<TD colspan='2' bgcolor='".BODY_COLOR."'>".
				"<input type='button' class='button' id='idBtIncluir' value='".TRANS('LINK_CAD_ITEM_SUPPLY','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\">".
				"</TD>".

				"<TD colspan='3' bgcolor='".BODY_COLOR."'>".
				"<input type='button' class='button' id='idBtSearch' value='".TRANS('LINK_SEARCH_ITEM','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=search&cellStyle=true');\">".
				"</TD>".

			"</TR>";
		if (mysql_num_rows($resultado) == 0){

			print "<tr><td align='center'>";
			echo mensagem(TRANS('MSG_NOT_REG_CAD'));
			print "</td>";
			print "</tr>";

		}else	{
			print "<tr>";

			print "<TD colspan='5' width='400' align='left'><B>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('TXT_ITEM_SUPPLY').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";

			//print "<TD width='200' align='left' ><a href='itens.php?action=incluir&cellStyle=true'>".TRANS('TTL_INCLUDE_COMP')."</a></td>";
			print "<TD align='left' colspan='2'>".
					"<input type='button' class='button' id='idBtIncluir' value='".TRANS('TTL_INCLUDE_COMP_MODEL','',0)."' onClick=\"redirect('itens.php?action=incluir&cellStyle=true');\">".
					"</td>";
			print "</tr>";
			print "<TR class='header'><td class='line'>".TRANS('COL_TYPE')."</TD><td class='line'>".TRANS('COL_MODEL')."</TD>".
					"<td class='line'>".TRANS('COL_SN')."</TD><td class='line'>".TRANS('COL_PARTNUMBER')."</TD>".
					"<td class='line'>".TRANS('COL_LOCAL')."</TD><td class='line'>".TRANS('COL_EQUIP','Equipamento')."</TD>".
					"<td class='line'>".TRANS('COL_EDIT')."</TD>".
					"<td class='line'>".TRANS('COL_DEL')."</TD>";
			$j=2;
			while ($row = mysql_fetch_array($PAGE->RESULT_SQL))
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
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=details&cod=".$row['estoq_cod']."&cellStyle=true')\">".$row['item_nome']."</a></td>";
				print "<td class='line'>".$row['fabricante']."&nbsp;".$row['modelo']."&nbsp;".$row['capacidade']."&nbsp;".$row['sufixo']."</td>";
				print "<td class='line'>".NVL($row['estoq_sn'])."</td>";
				print "<td class='line'>".NVL($row['estoq_partnumber'])."</td>";
				print "<td class='line'>".NVL($row['local'])."</td>";
				print "<td class='line'><a onClick=\"popup('mostra_consulta_inv.php?comp_inv=".$row['eqp_equip_inv']."&comp_inst=".$row['eqp_equip_inst']."')\">".NVL($row['instEquipamento']." - ".$row['eqp_equip_inv'])."</a></td>";
				print "<td class='line'><a onClick=\"popup_alerta('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['estoq_cod']."&cellStyle=true&popup=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('MSG_DEL_REG')."','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['estoq_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";

				print "</TR>";
			}
			print "<tr><td colspan='8'>";
			$PAGE->showOutputPages();
			print "</td></tr>";

		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<BR><B>".TRANS('SUBTTL_INCLUDE_ITEM_SUPPLY')."</B><BR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_TYPE').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";

			$select = "select * from itens order by item_nome";
			$exec = mysql_query($select);

		print "<select class='select' name='estoque_tipo' id='idTipo' onChange=\"ajaxFunction('idDivSelItemModel', 'showSelItemModels.php', 'idLoad', 'tipo=idTipo');\">"; //onChange=\"fillSelectFromArray(this.form.estoque_desc, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));\">";
			print "<option value=-1>".TRANS('SEL_TYPE_ITEM')."</option>";
			while($row = mysql_fetch_array($exec)){
				print "<option value=".$row['item_cod'].">".$row['item_nome']."</option>";
			} // while
		print "</select>";

		print "</TD>";
        	print "</TR>";

       		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_MODEL').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<div id='idDivSelItemModel'></div>";
/*			print "<select class='select' name='estoque_desc' id='idDesc'>";
			print "<option value='".null."' selected>".TRANS('SEL_MODEL')."</option>";
				$select ="select * from itens, modelos_itens where mdit_tipo = item_cod order by ".
					"item_nome, mdit_fabricante, mdit_desc, mdit_desc_capacidade";
				$exec = mysql_query($select);
				while($row = mysql_fetch_array($exec)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade']." ".$row['mdit_sufixo']."</option>";
				} // while
			print "</select>";*/
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_SN').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_sn' id='idSN'></TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_LOCAL').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_local' id='idLocal'>";
			print "<option value=null selected>".TRANS('OCO_SEL_LOCAL')."</option>";
			$select = "select * from localizacao order by local";
			$exec = mysql_query($select);
			while($row = mysql_fetch_array($exec)){
				print "<option value=".$row['loc_id'].">".$row['local']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_UNIT','Unidade').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_unidade' id='idUnidade'>";
			print "<option value=null selected>".TRANS('OCO_SEL_UNIT')."</option>";
			$select = "select * from instituicao order by inst_nome";
			$exec = mysql_query($select);
			while($row = mysql_fetch_array($exec)){
				print "<option value=".$row['inst_cod'].">".$row['inst_nome']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_TAG','Etiqueta').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_tag' id='idTag'></TD>";
        	print "</TR>";

        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_PARTNUMBER','Part-Number').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_partnumber' id='idPartnumber'></TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_VENDOR','Fornecedor').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_vendor' id='idVendor'>";
			print "<option value=null selected>".TRANS('OCO_SEL_VENDOR')."</option>";
			$select = "select * from fornecedores order by forn_nome";
			$exec = mysql_query($select);
			while($row = mysql_fetch_array($exec)){
				print "<option value=".$row['forn_cod'].">".$row['forn_nome']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_NF','Nota Fiscal').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_nf' id='idNf'></TD>";
        	print "</TR>";

        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_VALUE','Valor').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_value' id='idValue'></TD>";
        	print "</TR>";

        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_DATE_BUY').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' ".$readOnlyDateField." name='estoque_date_buy' id='idDatebuy'>".
//				"<a onclick=\"displayCalendar(document.forms[0].estoque_date_buy,'dd-mm-yyyy',this)\">".
//				"<img height='16' width='16' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('SEL_DATE')."'>".
				"</a>".
			"</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_TIME_MONTH').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_warranty' id='idWarranty'>";
			print "<option value=null selected>".TRANS('OCO_SEL_WARRANTY')."</option>";
			$select = "select * from tempo_garantia order by tempo_meses";
			$exec = mysql_query($select);
			while($row = mysql_fetch_array($exec)){
				print "<option value=".$row['tempo_cod'].">".$row['tempo_meses']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_CCUSTO','Centro de custo').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_ccusto' id='idCcusto'>";
			print "<option value=null selected>".TRANS('OCO_SEL_CCUSTO')."</option>";
			$select = "select * from CCUSTO order by descricao";
			$exec = mysql_query($select);
			while($row = mysql_fetch_array($exec)){
				print "<option value=".$row['codigo'].">".$row['descricao']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_SITUAC','Situação').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_situac' id='idSituac'>";
			print "<option value=null selected>".TRANS('OCO_SEL_SITUAC')."</option>";
			$select = "select * from situacao order by situac_nome";
			$exec = mysql_query($select);
			while($row = mysql_fetch_array($exec)){
				print "<option value=".$row['situac_cod'].">".$row['situac_nome']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_COMMENT').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_comentario' id='idComent'></TD>";
        	print "</TR>";


		NL(2);
		print "<TR>";
		print "<td colspan='2'>".TRANS('ASSOC_EQUIP_PIECES','Equipamento associado').":";
		print "</td>";
		print "</tr>";
        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_UNIT','Unidade').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_equip_unidade' id='idEquipUnidade'>";
			print "<option value=-1 selected>".TRANS('OCO_SEL_UNIT')."</option>";
			$select = "select * from instituicao order by inst_nome";
			$exec = mysql_query($select);
			while($rowS = mysql_fetch_array($exec)){
				print "<option value=".$rowS['inst_cod'].">".$rowS['inst_nome']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";
        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_TAG','Etiqueta').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_equip_tag' id='idEquipTag'></TD>";
        	print "</TR>";



		NL(2); //new line
		print "<tr>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('BT_CAD')."' name='submit'>";
		print "</TD>";
		print "<TD align='left'  bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"".$fecha."\"></TD>";

		print "</TR>";

	} else

	if ((isset($_GET['action'])  && ($_GET['action'] == "search") )&& empty($_POST['submit'])) {

		print "<BR><B>".TRANS('SUBTTL_SEARCH_ITEM_SUPPLY').":</B><BR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_TYPE').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";

			$select = "select * from itens order by item_nome";
			$exec = mysql_query($select);

		print "<select class='select' name='estoque_tipo' id='idTipoSearch'>";
			print "<option value=-1>".TRANS('SEL_TYPE_ITEM')."</option>";
			while($row = mysql_fetch_array($exec)){
				print "<option value=".$row['item_cod'].">".$row['item_nome']."</option>";
			} // while
		print "</select>";

		print "</TD>";
        	print "</TR>";


        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_SN').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_sn' id='idSN'></TD>";
        	print "</TR>";
        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_PARTNUMBER','Part-Number').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_partnumber' id='idPartnumber'></TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_LOCAL').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_local' id='idLocal'>";
			print "<option value=-1 selected>".TRANS('OCO_SEL_LOCAL')."</option>";
			$select = "select * from localizacao order by local";
			$exec = mysql_query($select);
			while($row = mysql_fetch_array($exec)){
				print "<option value=".$row['loc_id'].">".$row['local']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_UNIT','Unidade').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_unidade' id='idUnidade'>";
			print "<option value=null selected>".TRANS('OCO_SEL_UNIT')."</option>";
			$select = "select * from instituicao order by inst_nome";
			$exec = mysql_query($select);
			while($row = mysql_fetch_array($exec)){
				print "<option value=".$row['inst_cod'].">".$row['inst_nome']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_TAG','Etiqueta').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_tag' id='idTag'></TD>";
        	print "</TR>";


                NL(2); //new line
		print "<tr>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('BT_SEARCH')."' name='submit'>";
		print "</TD>";

		print "<TD align='left'  bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"".$fecha."\"></TD>";

		print "</TR>";



	} else


	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		//print "<script>ajaxFunction('idDivSelItemModel', 'showSelItemModels.php', 'idLoad', 'tipo=idTipo');</script>";

		$row = mysql_fetch_array($resultado);

		//dump ($row, "ROW - ALTER");
		print "<BR><B>".TRANS('SUBTTL_EDIT_ITEM_SUPPLY')."</B><BR>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_TYPE').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_tipo' id='idTipo' onChange=\"ajaxFunction('idDivSelItemModel', 'showSelItemModels.php', 'idLoad', 'tipo=idTipo', 'cod=idCodEstoque');\">";
			$select = "select * from itens order by item_nome";
			$exec = mysql_query($select);
			while($tipos = mysql_fetch_array($exec)){
				print "<option value =".$tipos['item_cod']."";
				if ($tipos['item_cod']==$row['estoq_tipo'])
					print " selected";
				print ">".$tipos['item_nome']."</option>";
			} // while
			print "</select>";

		print "</TD>";
		print "</tr>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_DESC').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";

			print "<div id='idDivSelItemModel'></div>";
// 			print "<select class=select name='estoque_desc' id='idDesc'>";
// 			$select ="select * from modelos_itens order by mdit_tipo, mdit_fabricante, mdit_desc, mdit_desc_capacidade";
// 			$exec = mysql_query($select);
// 			while($desc = mysql_fetch_array($exec)){
// 				print "<option value=".$desc['mdit_cod']."";
// 				if ($desc['mdit_cod']==$row['estoq_desc'])
// 					print " selected";
// 				print ">".$desc['mdit_fabricante']." ".$desc['mdit_desc']." ".$desc['mdit_desc_capacidade']." ".$desc['mdit_sufixo']."</option>";
// 			} // while
// 			print "</select>";



		print "</TD>";
		print "</tr>";


		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_SN').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<INPUT type='text' class='text' name='estoque_sn' id='idSN' value='".$row['estoq_sn']."'>";
		print "</TD>";
		print "</tr>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_LOCALIZATION').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class=select name='estoque_local' id='idLocal'>";
			//print "<option value=".$row['estoq_local']." selected>".$row['local']."</option>";
			print "<option value=null>".TRANS('OCO_SEL_LOCAL')."</option>";
			$select = "select * from localizacao order by local";
			$exec = mysql_query($select);
			while($locais = mysql_fetch_array($exec)){
				print "<option value =".$locais['loc_id']."";
				if ($locais['loc_id']==$row['loc_id'])
					print " selected";

				print ">".$locais['local']."</option>";
			} // while
			print "</select>";
		print "</TD>";
		print "</tr>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_UNIT','Unidade').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_unidade' id='idUnidade'>";
			print "<option value=null>".TRANS('OCO_SEL_UNIT')."</option>";
			$select = "select * from instituicao order by inst_nome";
			$exec = mysql_query($select);
			while($rowS = mysql_fetch_array($exec)){
				print "<option value=".$rowS['inst_cod']."";
				if ($rowS['inst_cod']==$row['estoq_tag_inst'])
					print " selected";

				print ">".$rowS['inst_nome']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_TAG','Etiqueta').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_tag' id='idTag' value='".$row['estoq_tag_inv']."'></TD>";
        	print "</TR>";

        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_PARTNUMBER','Part-Number').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_partnumber' id='idPartnumber' value='".$row['estoq_partnumber']."'></TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_VENDOR','Fornecedor').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_vendor' id='idVendor'>";
			print "<option value=null>".TRANS('OCO_SEL_VENDOR')."</option>";
			$select = "select * from fornecedores order by forn_nome";
			$exec = mysql_query($select);
			while($rowS = mysql_fetch_array($exec)){
				print "<option value=".$rowS['forn_cod']."";
				if ($rowS['forn_cod']==$row['forn_cod'])
					print " selected";

				print ">".$rowS['forn_nome']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_NF','Nota Fiscal').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_nf' id='idNf' value='".$row['estoq_nf']."'></TD>";
        	print "</TR>";

        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_VALUE','Valor').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_value' id='idValue' value='".valueSeparator($row['estoq_value'],',')."'></TD>";
        	print "</TR>";

        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_DATE_BUY').":</TD>";
		//print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_date_buy' id='idDatebuy' value='".$row['estoq_data_compra']."'></TD>";
			print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' ".$readOnlyDateField." name='estoque_date_buy' id='idDatebuy' value='".formatDate($row['estoq_data_compra'])."'>".
			"<a onclick=\"displayCalendar(document.forms[0].estoque_date_buy,'dd-mm-yyyy',this)\">".
			"<img height='16' width='16' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('SEL_DATE')."'>".
			"</a>".
			"</TD>";

        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_TIME_MONTH').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_warranty' id='idWarranty'>";
			print "<option value=null>".TRANS('OCO_SEL_WARRANTY')."</option>";
			$select = "select * from tempo_garantia order by tempo_meses";
			$exec = mysql_query($select);
			while($rowS = mysql_fetch_array($exec)){
				print "<option value=".$rowS['tempo_cod']."";
				if ($rowS['tempo_cod']==$row['tempo_cod'])
					print " selected";

				print ">".$rowS['tempo_meses']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_CCUSTO','Centro de custo').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_ccusto' id='idCcusto'>";
			print "<option value=null>".TRANS('OCO_SEL_CCUSTO')."</option>";
			$select = "select * from CCUSTO order by descricao";
			$exec = mysql_query($select);
			while($rowS = mysql_fetch_array($exec)){
				print "<option value=".$rowS['codigo']."";
				if ($rowS['codigo']==$row['codigo'])
					print " selected";

				print ">".$rowS['descricao']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_SITUAC','Situação').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_situac' id='idSituac'>";
			print "<option value=null>".TRANS('OCO_SEL_SITUAC')."</option>";
			$select = "select * from situacao order by situac_nome";
			$exec = mysql_query($select);
			while($rowS = mysql_fetch_array($exec)){
				print "<option value=".$rowS['situac_cod']."";
				if ($rowS['situac_cod']==$row['situac_cod'])
					print " selected";

				print ">".$rowS['situac_nome']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";




		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_DESC').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<INPUT type='text' class='text' name='estoque_comentario' id='idComent' value='".$row['estoq_comentario']."' maxlength='250' size='100'>";
		print "</TD>";
		print "</tr>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_TECHNICIAN').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='technician' id='idTechnician'>";
			$select = "SELECT u.*, a.* from usuarios u, sistemas a where u.AREA = a.sis_id and a.sis_atende='1' and u.nivel not in (3,4,5) order by login";
			$exec = mysql_query($select);
			while($rowS = mysql_fetch_array($exec)){
				print "<option value=".$rowS['user_id']."";
				if ($rowS['user_id']==$_SESSION['s_uid'])
					print " selected";

				print ">".$rowS['nome']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

		NL(2);
		print "<TR>";
		print "<td colspan='2'>".TRANS('ASSOC_EQUIP_PIECES','Equipamento associado').":";
		print "</td>";
		print "</tr>";
        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_UNIT','Unidade').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_equip_unidade' id='idEquipUnidade'>";
			print "<option value=-1>".TRANS('OCO_SEL_UNIT')."</option>";
			$select = "select * from instituicao order by inst_nome";
			$exec = mysql_query($select);
			while($rowS = mysql_fetch_array($exec)){
				print "<option value=".$rowS['inst_cod']."";
				if ($rowS['inst_cod']==$row['eqp_equip_inst'])
					print " selected";

				print ">".$rowS['inst_nome']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";
        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_TAG','Etiqueta').":</TD>";
		print "<TD  align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_equip_tag' id='idEquipTag' value='".$row['eqp_equip_inv']."'></TD>";
        	print "</TR>";


		NL(2); //new line colspan =2
		print "<TR>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('BT_ALTER')."' name='submit'>";

		if (isset($_GET['popup'])){
			print "<input type='hidden' name='popup' value='".$_GET['popup']."'>";
		}
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left'  bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"".$fecha."\"></TD>";

		print "</TR>";


	} else

	if ((isset($_GET['action']) && $_GET['action']=="details") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);
		//dump($row,'ROW - DETAILS');

		print "<BR><B>".TRANS('SUBTTL_DETAIL_ITEM_SUPPLY').":</B><BR>";
		NL(2);

		print "<tr><td><b><a href='".$_SERVER['PHP_SELF']."'>".TRANS('SUBTTL_SHOW_ALL')."</b></a></td>".
			"<td>".
				"<table><tr>".
				"<td><B><a onClick= \"javascript: popup_alerta('piece_hist.php?popup=true&piece_id=".$row['estoq_cod']."')\" title='".TRANS('HNT_HISTORY_LOCAL_EQUIP')."'>".TRANS('MNL_CON_HIST')."</a></B></TD>".
				"<td>".NVL('')."</td>".
				"<td><B><a onClick= \"javascript: popup_alerta('consulta_garantia_piece.php?popup=true&piece_id=".$row['estoq_cod']."')\" title='".TRANS('HNT_HISTORY_LOCAL_EQUIP')."'>".TRANS('LINK_GUARANT')."</a></B></TD>".
				//"<td>".NVL('')."</td><td>".TRANS('LINK_GUARANT')."</td>".
					"<td>".NVL('')."</td><td>".
							"<b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['estoq_cod']."&cellStyle=true')\">".
							"".TRANS('COL_EDIT')."</b></a></td>".
				"</tr></table>".
			"</td></tr>";




		NL(2);
		print "<TR>";
                print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_TYPE').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['item_nome']."</td>";
		print "</tr>";


		print "<TR>";
                print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_DESC').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['fabricante']." ".$row['modelo']." ".$row['capacidade']." ".$row['sufixo']."</td>";
		print "</tr>";

		print "<TR>";
                print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_SN').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['estoq_sn']."</td>";
		print "</tr>";

		print "<TR>";
                print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_LOCALIZATION').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['local']."</td>";
		print "</tr>";

        	print "<TR>";
                print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_UNIT','Unidade').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['inst_nome']."</td>";
		print "</tr>";

        	print "<TR>";
               	print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_TAG','Etiqueta').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['estoq_tag_inv']."</td>";
		print "</tr>";

        	print "<TR>";
               	print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_PARTNUMBER','Part-Number').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['estoq_partnumber']."</td>";
		print "</tr>";

        	print "<TR>";
                print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_VENDOR','Fornecedor').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['forn_nome']."</td>";
		print "</tr>";

        	print "<TR>";
               	print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_NF','Nota Fiscal').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['estoq_nf']."</td>";
		print "</tr>";

        	print "<TR>";
               	print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_VALUE','Valor').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".valueSeparator($row['estoq_value'],',')."</td>";
		print "</tr>";

        	print "<TR>";
               	print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_DATE_BUY','Data da compra').":</TD>";
                print "<TD align='left' bgcolor='".BODY_COLOR."'>".formatDate($row['estoq_data_compra'])."</td>";
                //print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['estoq_data_compra']."</td>";
		print "</tr>";

        	print "<TR>";
                print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_TIME_MONTH').":</TD>";
                print "<TD align='left' bgcolor='".BODY_COLOR."'>".$row['tempo_meses']."</td>";
		print "</tr>";


        	print "<TR>";
                print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_CCUSTO','Centro de custo').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['ccusto']."</td>";
		print "</tr>";

        	print "<TR>";
                print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_SITUAC','Situação').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['situac_nome']."</td>";
		print "</tr>";

		print "<TR>";
                print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_DESC').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['estoq_comentario']."</td>";
		print "</tr>";

		NL(2);
		print "<TR>";
		print "<td class='default'  colspan='2'>".TRANS('ASSOC_EQUIP_PIECES','Equipamento associado').":";
		print "</td>";
		print "</tr>";
        	print "<TR>";
                print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_UNIT','Unidade').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['instEquipamento']."</td>";
		print "</tr>";

        	print "<TR>";
               	print "<TD class='default'  width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_TAG','Etiqueta').":</TD>";
                print "<TD  align='left' bgcolor='".BODY_COLOR."'>".$row['eqp_equip_inv']."</td>";
		print "</tr>";

	} else


	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$query = "DELETE FROM estoque WHERE estoq_cod='".$_GET['cod']."'";
		$resultadoDel = mysql_query($query);
		if ($resultadoDel == 0)
		{
			$aviso = TRANS('ERR_DEL');
		}
		else
		{
			$aviso = TRANS('OK_DEL');
		}
		print "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	if ($_POST['submit'] == TRANS('BT_CAD')){

		$erro=false;

		if (empty($_POST['estoque_desc']) or empty($_POST['estoque_local'])or empty($_POST['estoque_tipo']))
		{
			$aviso = TRANS('MSG_EMPTY_DATA');
			$erro = true;
		}

// 		$query = "SELECT * FROM estoque WHERE  ".
// 					" estoq_tag_inst = '".$_POST['estoque_unidade']."' and estoq_tag_inv='".$_POST['estoque_tag']."'   ";
// 		$resultadoCad = mysql_query($query);
// 		$linhas = mysql_numrows($resultadoCad);
//
// 		if ($linhas > 0)
// 		{
// 			$aviso = TRANS('MSG_RECORD_EXISTS');
// 			$erro = true;
// 		}

		if (!$erro)
		{

			$valor = str_replace(",",".",$_POST['estoque_value']);
			if (empty($_POST['estoque_tag'])){
				$estoque_tag = 'null';
			} else
				$estoque_tag = $_POST['estoque_tag'];

			$query = "INSERT INTO estoque (estoq_tipo, estoq_desc, estoq_local, estoq_sn, estoq_tag_inv, estoq_tag_inst, ".
						"estoq_nf, estoq_warranty, estoq_value, estoq_situac, estoq_data_compra, estoq_ccusto, estoq_vendor, ".
						"estoq_partnumber, estoq_comentario ) ".
					"values (".$_POST['estoque_tipo'].", '".$_POST['estoque_desc']."', ".$_POST['estoque_local'].", ".
					"'".noHtml($_POST['estoque_sn'])."', ".$estoque_tag.", ".$_POST['estoque_unidade'].", ".
					"'".noHtml($_POST['estoque_nf'])."', '".$_POST['estoque_warranty']."', '".noHtml($valor)."', ".
					"'".$_POST['estoque_situac']."', '".FDate($_POST['estoque_date_buy'])."', '".$_POST['estoque_ccusto']."', ".
					"'".$_POST['estoque_vendor']."', '".noHtml($_POST['estoque_partnumber'])."', ".
					"'".noHtml($_POST['estoque_comentario'])."')";

			//print $query; exit;

			$resultadoNew = mysql_query($query) or die (TRANS('ERR_INSERT').'<BR>'.$query);
			if ($resultadoNew == 0)
			{
				$aviso = TRANS('ERR_INSERT');
			}
			else
			{
				$aviso = TRANS('OK_INSERT');
			}
			$PIECE_ID = mysql_insert_id();

			if (isset($_POST['estoque_equip_unidade']) && isset($_POST['estoque_equip_tag'])){
				$qryInsertEquip = "INSERT INTO equipXpieces (eqp_piece_id, eqp_equip_inv, eqp_equip_inst) ".
						"values ('".$PIECE_ID."', '".$_POST['estoque_equip_tag']."', '".$_POST['estoque_equip_unidade']."')";
				$execInsertEquip = mysql_query ($qryInsertEquip) or die(TRANS('ERR_EDIT').'<br>'.$qryInsertEquip);

				//ATUALIZA HISTÓRICO
				$qryUpdHistorico = "INSERT INTO hist_pieces (hp_piece_id, hp_piece_local, hp_comp_inv, ".
						"hp_comp_inst, hp_uid, hp_date)
					values ('".$PIECE_ID."', '".$_POST['estoque_local']."', '".$_POST['estoque_equip_tag']."',".
					" '".$_POST['estoque_equip_unidade']."', '".$_SESSION['s_uid']."', '".date("Y-m-d H:i:s")."')";
				$execUpdHist = mysql_query($qryUpdHistorico) or die (TRANS('ERR_INSERT')."<BR>".$qryUpdHistorico);

			} else {
				//ATUALIZA HISTÓRICO
				$qryUpdHistorico = "INSERT INTO hist_pieces (hp_piece_id, hp_piece_local, hp_uid, hp_date)
					values ('".$PIECE_ID."', '".$_POST['estoque_local']."', '".$_SESSION['s_uid']."', '".date("Y-m-d H:i:s")."')";
				$execUpdHist = mysql_query($qryUpdHistorico) or die (TRANS('ERR_INSERT')."<BR>".$qryUpdHistorico);
			}

		}

		print "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	if ($_POST['submit'] == TRANS('BT_ALTER')){
		$row = mysql_fetch_array($resultado);

		$query = "SELECT * FROM estoque WHERE  ".
					" estoq_tag_inst = '".$_POST['estoque_unidade']."' and estoq_tag_inv='".$_POST['estoque_tag']."'   ";
		$resultadoCad = mysql_query($query);
		$linhas = mysql_numrows($resultadoCad);

// 		if ($linhas > 0)
// 		{
// 			$aviso = TRANS('MSG_RECORD_EXISTS');
// 			$erro = true;
// 		} else $erro = false;
		$erro = false;
		if (!$erro) {
			if (($row['loc_id'] != $_POST['estoque_local']) || ($row['eqp_equip_inst'] != $_POST['estoque_equip_unidade'])  || ($row['eqp_equip_inv'] != $_POST['estoque_equip_tag'])){
				$updHist = true;
			} else
				$updHist = false;

			$valor = str_replace(",",".",$_POST['estoque_value']);

			if (empty($_POST['estoque_tag'])){
				$estoque_tag = 'null';
			} else
				$estoque_tag = $_POST['estoque_tag'];


			$query = "UPDATE estoque SET estoq_tipo = ".$_POST['estoque_tipo']." , estoq_desc = '".noHtml($_POST['estoque_desc'])."', ".
						"estoq_sn = '".noHtml($_POST['estoque_sn'])."', estoq_local = ".$_POST['estoque_local'].", ".

						"estoq_tag_inst = ".$_POST['estoque_unidade'].", estoq_tag_inv = ".$estoque_tag.", ".
						"estoq_partnumber = '".noHtml($_POST['estoque_partnumber'])."',  estoq_vendor = '".$_POST['estoque_vendor']."', ".
						"estoq_nf = '".noHtml($_POST['estoque_nf'])."', estoq_value = '".noHtml($valor)."',  ".
						"estoq_data_compra = '".FDate(noHtml($_POST['estoque_date_buy']))."', estoq_warranty = '".$_POST['estoque_warranty']."',  ".
						"estoq_ccusto = '".$_POST['estoque_ccusto']."', estoq_situac = '".$_POST['estoque_situac']."',  ".
						"estoq_comentario = '".noHtml($_POST['estoque_comentario'])."' ".
						"WHERE estoq_cod=".$_POST['cod']."";
			//print $query; exit;

			$resultadoUpd = mysql_query($query) or die (TRANS('ERR_EDIT').'<BR>'.$query);
			if ($resultadoUpd == 0)
			{
				$aviso = TRANS('ERR_EDIT');
			}
			else
			{
				$aviso = TRANS('OK_EDIT');
				$texto = TRANS('WARE_COD') .$row['estoq_cod']. TRANS('CHANGED');
				geraLog(LOG_PATH.'invmon.txt',date('d-m-Y H:i:s'),$_SESSION['s_usuario'],$_SERVER['PHP_SELF'],$texto);
			}

			if (isset($_POST['estoque_equip_unidade']) && isset($_POST['estoque_equip_tag'])){
				$sqlChecaEquip = "SELECT * FROM equipXpieces WHERE eqp_piece_id = ".$_POST['cod']." "; //".$row['estoq_cod']."
				$execChecaEquip = mysql_query($sqlChecaEquip) or die(TRANS('ERR_EDIT').'<br>'.$sqlChecaEquip);
				$achou = mysql_num_rows($execChecaEquip);
				if ($achou) {//update
					$qryUpdEquip = "UPDATE equipXpieces SET eqp_equip_inv = '".noHtml($_POST['estoque_equip_tag'])."', ".
							"eqp_equip_inst = '".$_POST['estoque_equip_unidade']."' ".
							"WHERE eqp_piece_id = ".$_POST['cod']."";
					$execUpdEquip = mysql_query($qryUpdEquip) or die(TRANS('ERR_EDIT').'<br>'.$qryUpdEquip);
				} else { //insert
					$qryInsertEquip = "INSERT INTO equipXpieces (eqp_piece_id, eqp_equip_inv, eqp_equip_inst) ".
							"values ('".$_POST['cod']."', '".$_POST['estoque_equip_tag']."', '".$_POST['estoque_equip_unidade']."')";
					$execInsertEquip = mysql_query ($qryInsertEquip) or die(TRANS('ERR_EDIT').'<br>'.$qryInsertEquip);
				}

				//ATUALIZA HISTÓRICO
				if ($updHist) {
					$qryUpdHistorico = "INSERT INTO hist_pieces (hp_piece_id, hp_piece_local, hp_comp_inv, ".
							"hp_comp_inst, hp_uid, hp_date, hp_technician)
						values ('".$_POST['cod']."', '".$_POST['estoque_local']."', '".$_POST['estoque_equip_tag']."',".
						" '".$_POST['estoque_equip_unidade']."', '".$_SESSION['s_uid']."', '".date("Y-m-d H:i:s")."', '".$_POST['technician']."')";
					$execUpdHist = mysql_query($qryUpdHistorico) or die (TRANS('ERR_INSERT')."<BR>".$qryUpdHistorico);
				}

			} else {
				if ($updHist) {
					//ATUALIZA HISTÓRICO
					$qryUpdHistorico = "INSERT INTO hist_pieces (hp_piece_id, hp_piece_local, hp_uid, hp_date, hp_technician)
						values ('".$_POST['cod']."', '".$_POST['estoque_local']."', '".$_SESSION['s_uid']."', '".date("Y-m-d H:i:s")."', '".$_POST['technician']."')";
					$execUpdHist = mysql_query($qryUpdHistorico) or die (TRANS('ERR_INSERT')."<BR>".$qryUpdHistorico);
				}
			}
		}

		if (isset($_REQUEST['popup'])) {
			$fecha = "javascript:window.close();";
		} else {
			$fecha = "javascript:history.go(-2);";
		}

		echo "<script>mensagem('".$aviso."'); ".$fecha."</script>";

	}

	print "</table>";

?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idTipo','COMBO','<?php print TRANS('COL_TYPE');?>',1);
		if (ok) var ok = validaForm('idDesc','COMBO','<?php print TRANS('COL_MODEL');?>',1);
		if (ok) var ok = validaForm('idLocal','COMBO','<?php print TRANS('OCO_LOCAL');?>',1);
		if (ok) var ok = validaForm('idValue','MOEDASIMP','<?php print TRANS('COL_VALUE');?>',0);

		return ok;
	}

	team = new Array(
	<?php 
	$conta = 0;
	$conta_sub = 0;

	$sql="select * from itens order by item_nome";
	$sql_result=mysql_query($sql);
	echo mysql_error();
	$num=mysql_numrows($sql_result);
	while ($row_A=mysql_fetch_array($sql_result)){
		$conta=$conta+1;
		$cod_item=$row_A["item_cod"];
			echo "new Array(\n";
			$sub_sql="select * from modelos_itens where mdit_tipo='".$cod_item."' order by mdit_tipo, mdit_fabricante, mdit_desc, mdit_desc_capacidade";
			$sub_result=mysql_query($sub_sql);
			$num_sub=mysql_numrows($sub_result);
			if ($num_sub>=1){
				echo "new Array(\"Todos\", -1),\n";
				while ($rowx=mysql_fetch_array($sub_result)){
					$codigo_sub=$rowx["mdit_cod"];
					$sub_nome=$rowx["mdit_fabricante"]." ".$rowx["mdit_desc"]." ".$rowx["mdit_desc_capacidade"]." ".$rowx["mdit_sufixo"];
					$conta_sub=$conta_sub+1;
					if ($conta_sub==$num_sub){
						echo "new Array(\"$sub_nome\", $codigo_sub)\n";
						$conta_sub="";
					}else{
						echo "new Array(\"$sub_nome\", $codigo_sub),\n";
					}
				}
			}else{
				echo "new Array(\"Qualquer\", -1)\n";
			}
		if ($num>$conta){
			echo "),\n";
		}
	}
	echo ")\n";
	echo ");\n";
	?>

	function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
		var i, j;
		var prompt;
		// empty existing items
		for (i = selectCtrl.options.length; i >= 0; i--) {
			selectCtrl.options[i] = null;
		}
		prompt = (itemArray != null) ? goodPrompt : badPrompt;
		if (prompt == null) {
			j = 0;
		}
		else {
			selectCtrl.options[0] = new Option(prompt);
			j = 1;
		}
		if (itemArray != null) {
			// add new items
			for (i = 0; i < itemArray.length; i++) {
				selectCtrl.options[j] = new Option(itemArray[i][0]);
				if (itemArray[i][1] != null) {
					selectCtrl.options[j].value = itemArray[i][1];
				}
				j++;
			}
		// select first item (prompt) for sub list
		selectCtrl.options[0].selected = true;
		}
	}
//-->
</script>
<?php 

print "</body>";
print "</html>";
?>
