<?php session_start();
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
  */

	if (!isset($_SESSION['s_logado']) || $_SESSION['s_logado'] == 0)
	{
	        print "<script>window.open('../../index.php','_parent','')</script>";
		exit;
	}

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	include ("../../includes/classes/paging.class.php");
	$PAGE = new paging;
	$PAGE->setRegPerPage(10);


	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];
	//$_SESSION['s_page_ocomon'] = basename($_SERVER['PHP_SELF']);

	$imgsPath = "../../includes/imgs/";


	//$hoje = date("Y-m-d H:i:s");
	$valign = " VALIGN = TOP ";

	if ($_SESSION['s_nivel']>2){
			print "<script>window.open('../../index.php','_parent','')</script>";
	}

	print "<html>";
	print "<head>";

	?>
	<script type="text/javascript">

		function popup(pagina)	{ //Exibe uma janela popUP
			x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
			x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
			return false
		}
		window.setInterval("redirect('abertura.php')",120000);
	</script>

	<?php 
	print "</head>";
	$auth = new auth;
	if (isset($_GET['popup'])) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2,'helpOabertura.php');

	//DIV PARA MOSTRAR OS HINTS
	print "<div id='bubble_tooltip'>";
		print "<div class='bubble_top'><span></span></div>";
		print "<div class='bubble_middle'><span id='bubble_tooltip_content'></span></div>";
		print "<div class='bubble_bottom'></div>";
	print "</div>";

	print "<div id='idLoad' class='loading' style='{display:none}'><img src='../../includes/imgs/loading.gif'></div>";


	$dt = new dateOpers; //Criado o objeto $dt
	$dta = new dateOpers;

	$cor  = TD_COLOR;
	$cor1 = TD_COLOR;
	$cor3 = BODY_COLOR;

        $percLimit = 20; //Tolerância em percentual
        $imgSlaR = 'sla1.png';
        $imgSlaS = 'checked.png';

	//Todas as áreas que o usuário percente
	$uareas = $_SESSION['s_area'];
	if ($_SESSION['s_uareas']) {
		$uareas.=",".$_SESSION['s_uareas'];
	}



	$query = "SELECT a.*, u.*, ar.* from usuarios u, avisos a left join sistemas ar on a.area = ar.sis_id where (a.area in (".$uareas.") or a.area=-1) and a.origem=u.user_id and upper(a.status) = 'ALTA'";
	$resultado = mysql_query($query) or die (TRANS('ERR_QUERY').$query);
        $linhas = mysql_num_rows($resultado);
        if ($linhas>0)
        {
        	print "<BR>";
		print "<B><font color='red'>".TRANS('OCO_URGENT_NOTICES','Aviso(s) Urgente(s)').":</font></B>";
		print "<TABLE border='0' cellpadding='0' cellspacing='0' align='center' width='100%' >";
		print "<TR>";
		print "<TD>";
		//STYLE='{border-bottom:  thin solid #999999; }'
		print "<TABLE class='header_centro' border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='".$cor."'>";
		print "<TR class='header'>";
		print "<TD>".TRANS('OCO_DATE','Data')."</TD><TD>".TRANS('OCO_NOTICE','Aviso')."</TD><TD>".TRANS('OCO_RESP','Responsável')."</TD><TD>".TRANS('OCO_TOAREA','Para área')."</TD>";
		$j=2;
		while ($resposta = mysql_fetch_array($resultado))
		{
			if ($j % 2) {
				$trClass = "lin_par";
			} else	{
				$trClass = "lin_impar";
			}
			$j++;

			print "<TR class='".$trClass."'>";
			print "<TD  class='line'>".formatDate($resposta['data'])."</TD>";
			print "<TD class='line'>".nl2br($resposta['avisos'])."</TD>";
			print "<TD class='line'>".$resposta['login']."</TD>";
			if (isIn($resposta['sis_id'],$uareas))
				$area_aviso = $resposta['sistema']; else
				$area_aviso = "".TRANS('OCO_ALL_AREAS','TODAS')."";
			print "<TD class='line'>".$area_aviso."</TD>";
			print "</TR>";
        	}
		print "</TR>";
		print "</TABLE>";
		print "</TD>";

		print "</TR>";
		print "</TABLE>";
        }
        print "</TR>";
        print "</TABLE>";

        print "<TABLE border='0' cellpadding='0' cellspacing='0' align='center' width='100%' bgcolor='".$cor3."'>";
        print "<TR width=100%>";
        print "&nbsp;";
        print "</TR>";

        print "<TD class='line' >";

        $query = "SELECT aviso_id FROM avisos WHERE upper(status) = 'NORMAL' and area in (".$uareas.")"; //area=".$_SESSION['s_area'].")
        $resultado = mysql_query($query) or die (TRANS('ERR_QUERY').$query);
        $linhas = mysql_num_rows($resultado);
        if ($linhas==1)
                print "<TR><TD  class='line' bgcolor='".$cor1."'><B>".TRANS('THEREIS','Existe')."&nbsp;".$linhas."&nbsp;".TRANS('OCO_HIDDEN_NOTICE','aviso não exibido nessa tela!  Verifique no mural').".</B></TD></TR><BR>";
        if ($linhas>1)
                print "<TR><TD  class='line' bgcolor='".$cor1."'><B>".TRANS('THEREARE','Existem')."&nbsp;".$linhas."&nbsp;".TRANS('OCO_HIDDEN_NOTICES','avisos não exibidos nessa tela!  Verifique no mural').".</B></TD></TR><BR>";


        $query = "SELECT empr_id FROM emprestimos WHERE (data_devol <= '".date("Y-m-d H:i:s")."' AND responsavel='".$_SESSION['s_uid']."')";
        $resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);
        if ($linhas==1)
                print "<TR><TD class='line' >".TRANS('FOUND_ONE','Foi encontrado')."&nbsp;".$linhas."&nbsp;".TRANS('OCO_LENDING_ONE','empréstimo pendente para este usuário').".</TD></TR><BR>";
        if ($linhas>1)
                print "<TR><TD class='line' ><b>".TRANS('FOUND','Foram encontrados')."&nbsp;".$linhas."&nbsp;".TRANS('OCO_LENDING','empréstimos pendentes para este usuário').".</b></TD></TR><tr><td class='line'>&nbsp;</td></tr>";







        #######################################################
        #######################################################
        ## OCORRENCIAS AGENDADAS ###

	$query = $QRY["ocorrencias_full_ini"]." WHERE o.oco_scheduled=1 and o.sistema in (".$uareas.") ORDER BY numero";
	$resultado_oco = mysql_query($query) or die (TRANS('ERR_QUERY').$query);
        $linhas = mysql_num_rows($resultado_oco);

	if ($linhas == 0) {
        	echo mensagem("".TRANS('OCO_NOT_SCHEDULED_CALLS','Não existem ocorrências agendadas no sistema'));
        }
        else {//
		if ($linhas>=1) {
			//VARIÁVEIS DE SESSÃO PARA O COLLAPSE DOS CHAMADOS AGENDADOS


			if (!isset($_SESSION['CHAVE2'])) {
				$_SESSION['CHAVE2'] = "{display:none}";
				$_SESSION['ICON_CHAVE2']="open.png";
			} else
			if (isset($_GET['CHAVE2'])) {
				if ($_GET['CHAVE2'] == "") {
					$_SESSION['CHAVE2'] = "{display:none}";
					$_SESSION['ICON_CHAVE2']="open.png";
				} else {
					$_SESSION['CHAVE2'] = "";
					$_SESSION['ICON_CHAVE2']="close.png";
				}
			}

  			if (!isset($_SESSION['ICON_CHAVE2'])) {
  				$_SESSION['ICON_CHAVE2']="open.png";
  			}


			print "<tr><TD><IMG ID='imgAgendados' SRC='../../includes/icons/".$_SESSION['ICON_CHAVE2']."' width='9' height='9' ".
				"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('Agendados') ; ajaxFunction('idDivSessionAgendados', 'updateCollapseSession.php', 'idLoad', 'CHAVE2=idChave2');".
				"\">&nbsp;<b>".TRANS('THEREARE','Existem')."&nbsp;<font color='red'>".$linhas."&nbsp;".TRANS('OCO_OCORRENCIAS')."&nbsp;".
				"".TRANS('OCO_SCHEDULED')."&nbsp; ".TRANS('OCO_IN_THE_SYSTEM')."</b></td></tr>";

			if (isset($_SESSION['CHAVE2'])){
				print "<input type='hidden' name='chave2' id='idChave2' value='".$_SESSION['CHAVE2']."'>";
			}
			print "<div id='idDivSessionAgendados' style='{display:none;}'></div>";


		} else {
			print "<TR><td class='line' ><b>".TRANS('THERE_IS_ARE')."&nbsp;".$linhas."&nbsp;".TRANS('OCO_OCORRENCIA','ocorrência')."&nbsp;<font color='red'>".TRANS('OCO_SCHEDULED','agendada')."&nbsp; ".TRANS('OCO_IN_THE_SYSTEM')."</font>.<b></TD></TR>";
		}

		print "<tr><td colspan='4'></td></tr>";
		$style_chave2 = "";
		if (isset($_SESSION['CHAVE2'])){
			$style_chave2 = $_SESSION['CHAVE2'];
		}
		print "<tr><td colspan='4'><div id='Agendados' style='".$style_chave2."'>"; //style='{display:none}'	//style='{padding-left:5px;}'

		print "<TABLE class='header_centro'  border-top: thin solid #999999;}' border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='".$cor."'>";
		print "<TR class='header'>";
			print "<TD  class='line' >".TRANS('OCO_NUMBER_BRIEF')."</TD><TD class='line' >".TRANS('OCO_PROB')."</TD>".
				"<TD  class='line' >".TRANS('OCO_CONTACT')."<BR>".TRANS('OCO_PHONE')."</TD>".
				"<TD  class='line'  WIDTH='250'>".TRANS('OCO_LOCAL')."</TD>".
				"<TD  class='line' >".TRANS('OCO_STATUS')."</TD>".
				"<TD  class='line' ><a title='".TRANS('HNT_REMAIN_TIME')."'>".TRANS('OCO_REMAIN_TIME')."</a></TD>";
		print "</TR>";
        }
        $i=0;
        $j=2;
        while ($rowAT = mysql_fetch_array($resultado_oco))
        {
        	if ($j % 2) {
			$trClass = "lin_par";
            	} else {
			$trClass = "lin_impar";
            	}
            	$j++;
		print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";

		$qryImg = "select * from imagens where img_oco = ".$rowAT['numero']."";
		$execImg = mysql_query($qryImg) or die (TRANS('ERR_QUERY'));
		$rowTela = mysql_fetch_array($execImg);
		$regImg = mysql_num_rows($execImg);
		if ($regImg!=0) {
			$linkImg = "<a onClick=\"javascript:popup_wide('listFiles.php?COD=".$rowAT['numero']."')\"><img src='../../includes/icons/attach2.png'></a>";
		} else $linkImg = "";

		$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']." or dep_filho=".$rowAT['numero']."";
		$execSubCall = mysql_query($sqlSubCall) or die (TRANS('ERR_QUERY').'<br>'.$sqlSubCall);
		$regSub = mysql_num_rows($execSubCall);
		if ($regSub > 0) {
			#É CHAMADO PAI?
			$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die (TRANS('ERR_QUERY').$sqlSubCall);
			$regSub = mysql_num_rows($execSubCall);
			$comDeps = false;
			while ($rowSubPai = mysql_fetch_array($execSubCall)){
				$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
				$execStatus = mysql_query($sqlStatus) or die (TRANS('ERR_QUERY').$sqlStatus);
				$regStatus = mysql_num_rows($execStatus);
				if ($regStatus > 0) {
					$comDeps = true;
				}
			}
			if ($comDeps) {
				$imgSub = "<a onClick=\"javascript:popup('../../includes/help/help_depends.php')\"><img src='".ICONS_PATH."view_tree_red.png' width='16' height='16' title='Chamado com vínculos pendentes'></a>";
			} else
				$imgSub =  "<a onClick=\"javascript:popup('../../includes/help/help_depends.php')\"><img src='".ICONS_PATH."view_tree_green.png' width='16' height='16' title='Chamado com vínculos mas sem pendências'></a>";
		} else
			$imgSub = "";

		print "<TD  class='line'  ".$valign."><a href='mostra_consulta.php?numero=".$rowAT['numero']."'>".$rowAT['numero']."</a>".$imgSub."</TD>";
		print "<TD  class='line'  ".$valign.">".$linkImg."&nbsp;".$rowAT['problema']."</TD>";
		print "<TD  class='line'  ".$valign."><b>".$rowAT['contato']."</b><br>".$rowAT['telefone']."</TD>";
		print "<TD  class='line'  ".$valign."><b>".$rowAT['setor']."</b><br>";
		$texto = trim($rowAT['descricao']);
		if (strlen($texto)>200){
			$texto = substr($texto,0,195)." ..... ";
		};
	        print "<a class='no' href='mostra_consulta.php?numero=".$rowAT['numero']."'>".$texto."</a>";
	        print "</TD>";

            	print "<TD  class='line'  ".$valign.">".$rowAT['chamado_status']."</TD>";

		// if (array_key_exists($rowAT['cod_area'],$H_horarios)){  //verifica se o código da área possui carga horária definida no arquivo config.inc.php
				//$areaChamado = $rowAT['cod_area']; //Recebe o valor da área de atendimento do chamado
		// } else $areaChamado = 1; //Carga horária default definida no arquivo config.inc.php
		$areaChamado = "";
		$areaChamado=testaArea($areaChamado,$rowAT['area_cod'],$H_horarios);

		$data = $rowAT['data_abertura'];

		$diff = date_diff($data,date("Y-m-d H:i:s"));
		$sep = explode ("dias",$diff);

		if ($sep[0]>5) { //Se a programação for maior do que 5 dias, o tempo é mostrado em dias para não ficar muito pesado.
			$diff = $sep[0]." dias";
			$segundos = ($sep[0]*86400);
		} else {
			$dta->setData1($data);
			$dta->setData2(date("Y-m-d H:i:s"));

			$dta->tempo_valido($dta->data1,$dta->data2,$H_horarios[$areaChamado][0],$H_horarios[$areaChamado][1],$H_horarios[$areaChamado][2],$H_horarios[$areaChamado][3],"H");
			$diff = $dta->tValido;
			$diff2 = $dta->diff["hValido"];
			$segundos = $dta->diff["sValido"]; //segundos válidos
		}
       		print "<TD  class='line'  ".$valign.">".$diff."&nbsp;<img height='16' width='16' src='".ICONS_PATH."sla.png' title='".TRANS('HNT_REMAIN_TIME')."'></TD>";

		## O CHAMADO ENTRA P/ FILA NORMAL DE ATENDIMENTO
		if ($rowAT['data_abertura'] <= date("Y-m-d H:i:s")){
			$error = "";

			if (!isset($rowAT['date_first_queued']) ){ //OR empty($rowAT['date_first_queued'])
				
				$qryUpdSchedule = "UPDATE ocorrencias SET oco_scheduled=0, `status`=1, date_first_queued='".$rowAT['data_abertura']."' WHERE numero = ".$rowAT['numero']."";
			
			} else {
				//$qryUpdSchedule = "UPDATE ocorrencias SET oco_scheduled=0, `status`=1, date_first_queued='".$rowAT['data_abertura']."' WHERE numero = ".$rowAT['numero']."";
				$qryUpdSchedule = "UPDATE ocorrencias SET oco_scheduled=0, `status`=1 WHERE numero = ".$rowAT['numero']."";
			}
			
			$execUpdSchedule = mysql_query($qryUpdSchedule) or die ($qryUpdSchedule);			


			$qryConfig = "SELECT * FROM config";
			$execConfig = mysql_query($qryConfig);
			$row_config = mysql_fetch_array($execConfig);

			##TRATANDO O STATUS ANTERIOR
			//Verifica se o status 'atual' já foi gravado na tabela 'tempo_status' , em caso positivo, atualizo o tempo, senão devo gravar ele pela primeira vez.
			//$sql_ts_anterior = "select * from tempo_status where ts_ocorrencia = ".$rowAT['numero']." and ts_status = ".$row_config['conf_schedule_status']." ";
			$sql_ts_anterior = "select * from tempo_status where ts_ocorrencia = ".$rowAT['numero']." and ts_status = ".$rowAT['status_cod']." ";
			$exec_sql = mysql_query($sql_ts_anterior);

			if ($exec_sql == 0) $error= " erro 1";

			$achou = mysql_num_rows($exec_sql);
			if ($achou >0){ //esse status já esteve setado em outro momento
				$row_ts = mysql_fetch_array($exec_sql);

				$areaT = "";
				$areaT=testaArea($areaT,$rowAT['area_cod'],$H_horarios);

				$dt = new dateOpers;
				$dt->setData1($row_ts['ts_data']);
				//$dt->setData2(date("Y-m-d H:i:s"));  //SUBSTITUÍ date("Y-m-d H:i:s") POR $rowAT['data_abertura']
				$dt->setData2($rowAT['data_abertura']);
				$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$areaT][0],$H_horarios[$areaT][1],$H_horarios[$areaT][2],$H_horarios[$areaT][3],"H");
				$segundos = $dt->diff["sValido"]; //segundos válidos

				//$sql_upd = "update tempo_status set ts_tempo = (ts_tempo+".$segundos.") , ts_data ='".date("Y-m-d H:i:s")."' where ts_ocorrencia = ".$rowAT['numero']." and
						//ts_status = ".$row_config['conf_schedule_status']." ";
				$sql_upd = "update tempo_status set ts_tempo = (ts_tempo+".$segundos.") , ts_data ='".$rowAT['data_abertura']."' where ts_ocorrencia = ".$rowAT['numero']." and
						ts_status = ".$rowAT['status_cod']." ";

				$exec_upd = mysql_query($sql_upd);
				if ($exec_upd ==0) $error.= " erro 2";

			} else {
				//$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$rowAT['numero'].", ".$row_config['conf_schedule_status'].", 0, '".date("Y-m-d H:i:s")."' )";
				$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$rowAT['numero'].", ".$rowAT['status_cod'].", 0, '".$rowAT['data_abertura']."' )";
				$exec_ins = mysql_query ($sql_ins);
				if ($exec_ins == 0) $error.= " erro 3 ";
			}
			##TRATANDO O NOVO STATUS
			//verifica se o status 'novo' já está gravado na tabela 'tempo_status', se estiver eu devo atualizar a data de início. Senão estiver gravado então devo gravar pela primeira vez
			$sql_ts_novo = "select * from tempo_status where ts_ocorrencia = ".$rowAT['numero']." and ts_status = 1 ";
			$exec_sql = mysql_query($sql_ts_novo);
			if ($exec_sql == 0) $error.= " erro 4";

			$achou_novo = mysql_num_rows($exec_sql);
			if ($achou_novo > 0) { //status já existe na tabela tempo_status
				$sql_upd = "update tempo_status set ts_data = '".$rowAT['data_abertura']."' where ts_ocorrencia = ".$rowAT['numero']." and ts_status = 1 ";
				$exec_upd = mysql_query($sql_upd);
				if ($exec_upd == 0) $error.= " erro 5";
			} else {//status novo na tabela tempo_status
				$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$rowAT['numero'].", 1, 0, '".$rowAT['data_abertura']."' )";
				$exec_ins = mysql_query($sql_ins);
				if ($exec_ins == 0) $error.= " erro 6 ";
			}

			print "<script type=\"text/javascript\">redirect('abertura.php');</script>";
		}

		print "</TR>";
	        $i++;
        } //while
        print "</TABLE>";
	print "</div>";
        print "</td>";



        #######################################################
        #######################################################




        print "<TABLE border='0' cellpadding='0' cellspacing='0' align='center' width='100%' bgcolor='".$cor3."'>";
        print "<TR width=100%>";
        print "</TR>";

        print "<tr><TD>&nbsp</td></tr>";



        //OCORRÊNCIAS VINCULADAS AO OPERADOR
        //PAINEL 1 É O PAINEL SUPERIOR DA TELA DE ABERTURA

	$query = $QRY["ocorrencias_full_ini"]." WHERE s.stat_painel in (1) and o.operador = ".$_SESSION['s_uid']." ".
				"and o.oco_scheduled=0 ORDER BY numero";
	$resultado_oco = mysql_query($query) or die (TRANS('ERR_QUERY').$query);
        $linhas = mysql_num_rows($resultado_oco);

	if ($linhas == 0) {
        	echo mensagem("".TRANS('OCO_NOT_PENDING_TO_USER','Não existem ocorrências pendentes para o usuário')."&nbsp;".$_SESSION['s_usuario'].".");
        }
        else {//OCORRENCIAS VINCULADAS AO OPERADOR
		if ($linhas>1) {
			//VARIÁVEIS DE SESSÃO PARA O COLLAPSE DOS CHAMADOS VINCULADOS AO OPERADOR LOGADO

 			if (!isset($_SESSION['ICON_CHAVE'])) {
 				$_SESSION['ICON_CHAVE']="close.png";
 			}
//
 			if (!isset($_SESSION['CHAVE'])) {
 				$_SESSION['CHAVE'] = "";
 			} //else
// 			if (isset($_GET['CHAVE'])) {
// 				if ($_GET['CHAVE'] == "{display:none}") {
// 					$_SESSION['CHAVE'] = "";
// 					$_SESSION['ICON_CHAVE']="close.png";
// 				} else {
// 					$_SESSION['CHAVE'] = "{display:none}";
// 					$_SESSION['ICON_CHAVE']="open.png";
// 				}
// 			}

// 			print "<tr><TD><IMG ID='imgVinculados' SRC='../../includes/icons/".$_SESSION['ICON_CHAVE']."' width='9' height='9' ".
// 				"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('Vinculados') ; redirect('".$_SERVER['PHP_SELF']."?".
// 				"CHAVE=".$_SESSION['CHAVE']."')\">&nbsp;<b>".TRANS('THEREARE','Existem')."&nbsp;<font color='red'>".$linhas."&nbsp;".TRANS('OCO_OCORRENCIAS')."&nbsp;".
// 				"".TRANS('OCO_PENDING')."</font>&nbsp;".TRANS('OCO_TO_USER','para o usuário')."&nbsp;".$_SESSION['s_usuario'].".</b></td></tr>";

 			print "<tr><TD><IMG ID='imgVinculados' SRC='../../includes/icons/".$_SESSION['ICON_CHAVE']."' width='9' height='9' ".
 				"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('Vinculados') ; ajaxFunction('idDivSessionPendentes', 'updateCollapseSession.php', 'idLoad', 'CHAVE=idChave');".
 				"\">&nbsp;<b>".TRANS('THEREARE','Existem')."&nbsp;<font color='red'>".$linhas."&nbsp;".TRANS('OCO_OCORRENCIAS')."&nbsp;".
 				"".TRANS('OCO_PENDING')."</font>&nbsp;".TRANS('OCO_TO_USER','para o usuário')."&nbsp;".$_SESSION['s_usuario'].".</b></td></tr>";

			print "<input type='hidden' name='chave' id='idChave' value='".$_SESSION['CHAVE']."'>";
			print "<div id='idDivSessionPendentes' style='{display:none;}'></div>";


		} else {
			print "<TR><td class='line' ><b>".TRANS('THERE_IS_ARE')."&nbsp;".$linhas."&nbsp;".TRANS('OCO_OCORRENCIA','ocorrência')."&nbsp;<font color='red'>".TRANS('OCO_PENDING_ONE','pendente')."</font>&nbsp;".TRANS('OCO_TO_USER','')."&nbsp;".$_SESSION['s_usuario'].".<b></TD></TR>";
		}
		//print "<TD  class='line' >";

		
		$style_chave = "";
		if (isset($_SESSION['CHAVE'])){
			$style_chave = $_SESSION['CHAVE'];
		}		
		print "<tr><td colspan='4'></td></tr>";
		print "<tr><td colspan='4'><div id='Vinculados' style='".$style_chave."'>"; //style='{display:none}'	//style='{padding-left:5px;}'

		print "<TABLE class='header_centro'  border-top: thin solid #999999;}' border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='".$cor."'>";
		print "<TR class='header'>";
			print "<TD  class='line' >".TRANS('OCO_NUMBER_BRIEF')."</TD><TD class='line' >".TRANS('OCO_PROB','Problema')."</TD>".
				"<TD  class='line' >".TRANS('OCO_CONTACT')."<BR>".TRANS('OCO_PHONE','Ramal')."</TD>".
				"<TD  class='line'  WIDTH='250'>".TRANS('OCO_LOCAL')."</TD>".
				"<TD  class='line' >".TRANS('OCO_STATUS')."</TD>".
				"<TD  class='line' ><a title='".TRANS('HNT_VALID_TIME')."'>".TRANS('OCO_VALID_TIME')."</a></TD>".
				"<TD  class='line' ><a title='".TRANS('HNT_RESPONSE_TIME')."'>".TRANS('OCO_RESPONSE')."</a></TD>".
				"<TD class='line' ><a title='".TRANS('HNT_SOLUTION_TIME')."'>".TRANS('OCO_SOLUC','SOLUC.')."</a></TD>";
		print "</TR>";
        }
        $i=0;
        $j=2;
        while ($rowAT = mysql_fetch_array($resultado_oco))
        {
        	if ($j % 2) {
			$trClass = "lin_par";
            	} else {
			$trClass = "lin_impar";
            	}
            	$j++;
		print "<tr class=".$trClass." id='linhaxx".$j."' onMouseOver=\"destaca('linhaxx".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhaxx".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhaxx".$j."','".$_SESSION['s_colorMarca']."');\">";

		$qryImg = "select * from imagens where img_oco = ".$rowAT['numero']."";
		$execImg = mysql_query($qryImg) or die (TRANS('ERR_QUERY'));
		$rowTela = mysql_fetch_array($execImg);
		$regImg = mysql_num_rows($execImg);
		if ($regImg!=0) {
			//$linkImg = "<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$rowAT['numero']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\"><img src='../../includes/icons/attach2.png'></a>";
			$linkImg = "<a onClick=\"javascript:popup_wide('listFiles.php?COD=".$rowAT['numero']."')\"><img src='../../includes/icons/attach2.png'></a>";
		} else $linkImg = "";

		$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']." or dep_filho=".$rowAT['numero']."";
		$execSubCall = mysql_query($sqlSubCall) or die (TRANS('ERR_QUERY').'<br>'.$sqlSubCall);
		$regSub = mysql_num_rows($execSubCall);
		if ($regSub > 0) {
			#É CHAMADO PAI?
			$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die (TRANS('ERR_QUERY').$sqlSubCall);
			$regSub = mysql_num_rows($execSubCall);
			$comDeps = false;
			while ($rowSubPai = mysql_fetch_array($execSubCall)){
				$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
				$execStatus = mysql_query($sqlStatus) or die (TRANS('ERR_QUERY').$sqlStatus);
				$regStatus = mysql_num_rows($execStatus);
				if ($regStatus > 0) {
					$comDeps = true;
				}
			}
			if ($comDeps) {
				$imgSub = "<a onClick=\"javascript:popup('../../includes/help/help_depends.php')\"><img src='".ICONS_PATH."view_tree_red.png' width='16' height='16' title='Chamado com vínculos pendentes'></a>";
			} else
				$imgSub =  "<a onClick=\"javascript:popup('../../includes/help/help_depends.php')\"><img src='".ICONS_PATH."view_tree_green.png' width='16' height='16' title='Chamado com vínculos mas sem pendências'></a>";
		} else
			$imgSub = "";

		print "<TD  class='line'  ".$valign."><a href='mostra_consulta.php?numero=".$rowAT['numero']."'>".$rowAT['numero']."</a>".$imgSub."</TD>";
		print "<TD  class='line'  ".$valign.">".$linkImg."&nbsp;".$rowAT['problema']."</TD>";
		print "<TD  class='line'  ".$valign."><b>".$rowAT['contato']."</b><br>".$rowAT['telefone']."</TD>";
		print "<TD  class='line'  ".$valign."><b>".$rowAT['setor']."</b><br>";
		$texto = trim($rowAT['descricao']);
		if (strlen($texto)>200){
			$texto = substr($texto,0,195)." ..... ";
		};
		//print $texto;
	        print "<a class='no' href='mostra_consulta.php?numero=".$rowAT['numero']."'>".$texto."</a>";
	        print "</TD>";
            	print "<TD  class='line'  ".$valign.">".$rowAT['chamado_status']."</TD>";

		// if (array_key_exists($rowAT['cod_area'],$H_horarios)){  //verifica se o código da área possui carga horária definida no arquivo config.inc.php
				//$areaChamado = $rowAT['cod_area']; //Recebe o valor da área de atendimento do chamado
		// } else $areaChamado = 1; //Carga horária default definida no arquivo config.inc.php
		$areaChamado = "";
		$areaChamado=testaArea($areaChamado,$rowAT['area_cod'],$H_horarios);

		$data = $rowAT['data_abertura'];

		$diff = date_diff($data,date("Y-m-d H:i:s"));
		$sep = explode ("dias",$diff);
		if ($sep[0]>20) { //Se o chamado estiver aberto a mais de 20 dias o tempo é mostrado em dias para não ficar muito pesado.
			$diff = $sep[0]." dias";
			$segundos = ($sep[0]*86400);
		} else {
			$dta->setData1($data);
			$dta->setData2(date("Y-m-d H:i:s"));

			$dta->tempo_valido($dta->data1,$dta->data2,$H_horarios[$areaChamado][0],$H_horarios[$areaChamado][1],$H_horarios[$areaChamado][2],$H_horarios[$areaChamado][3],"H");
			$diff = $dta->tValido;
			$diff2 = $dta->diff["hValido"];
			$segundos = $dta->diff["sValido"]; //segundos válidos
		}
       		print "<TD  class='line'  ".$valign.">".$diff."</TD>";

		if ($rowAT['data_atendimento'] ==""){//Controle das bolinhas de SLA de Resposta
			if ($segundos<=($rowAT['sla_resposta_tempo']*60)){
				$imgSlaR = 'sla1.png';
			} else if ($segundos  <=(($rowAT['sla_resposta_tempo']*60) + (($rowAT['sla_resposta_tempo']*60) *$percLimit/100)) ){
					$imgSlaR = 'sla2.png';
			} else {
				$imgSlaR = 'sla3.png';
			}
		} else
			$imgSlaR = 'checked.png';

		$sla_tempo = $rowAT['sla_solucao_tempo'];
		if ($sla_tempo !="") { //Controle das bolinhas de SLA de solução
			if ($segundos <= ($rowAT['sla_solucao_tempo']*60)){
				$imgSlaS = 'sla1.png';
			} else if ($segundos  <=(($rowAT['sla_solucao_tempo']*60) + (($rowAT['sla_solucao_tempo']*60) *$percLimit/100)) ){
				$imgSlaS = 'sla2.png';
			} else
				$imgSlaS = 'sla3.png';
		} else
			$imgSlaS = 'checked.png';
		//-----------------------------------------------------

		print "<TD  class='line'  ".$valign." align='center'><a onClick=\"javascript:popup('../../includes/help/sla_popup.php?sla=r')\"><img height='14' width='14' src='".$imgsPath."".$imgSlaR."'></a></TD>";
		print "<TD  class='line'  ".$valign." align='center'><a onClick=\"javascript:popup('../../includes/help/sla_popup.php?sla=s')\"><img height='14' width='14' src='".$imgsPath."".$imgSlaS."'></a></TD>";

		print "</TR>";
	        $i++;
        } //while
        print "</TABLE>";
	print "</div>";
        print "</td>";

	//----------------------------------------------------------------------------------------------------
        print "<TABLE border='0' cellpadding='0' cellspacing='0' align='center' width='100%' bgcolor='".$cor3."'>";
        print "<TR width=100%>";
        print "</TR>";

        print "<tr><TD>&nbsp</td></tr>";

		//---------------------------- OCORRÊNCIAS AGUARDANDO ATENDIMENTO ----------------------------------//
		//******** Ocorrências aguardando atendimento => status=1 *********
		//PAINEL 2 É O PAINEL PRINCIPAL DA TELA DE ABERTURA, ONDE FICAM TODOS OS CHAMADOS PENDENTES DE ATENDIMENTO

	$ICON_ORDER_AREA = "";
	$ICON_ORDER_PROB = "";
	$ICON_ORDER_DATA = "";
	$ICON_ORDER_LOCAL = "";
	$ICON_ORDER_CONTATO = "";
        if (!isset($_SESSION['ORDERBY'])) {
        	$_SESSION['ORDERBY'] = "area, numero";
        	$_SESSION['TEXTO_ORDER'] = "área e número do chamado (padrão)";

		$ICON_ORDER_AREA = "<img src='../../includes/css/OrderAsc.png' width='16' height='16' align='absmiddle'>";
		$ICON_ORDER_DATA = "";
		$ICON_ORDER_PROB = "";
		$ICON_ORDER_LOCAL = "";
		$ICON_ORDER_CONTATO = "";
        } //else

        if (isset($_GET['ORDERBY'])) {
        	if ($_GET['ORDERBY'] == "PROB"){
        		if ($_SESSION['ORDERBY'] == "area, problema, numero") {
        			$_SESSION['ORDERBY'] = "area, problema desc, numero";
        			$_SESSION['TEXTO_ORDER'] = "área, problema Z-a e número";
        		} else {
	        		$_SESSION['ORDERBY'] = "area, problema, numero";
	        		$_SESSION['TEXTO_ORDER'] = "área, problema e número";
	        	}
        	} else
        	if ($_GET['ORDERBY'] == "DATA"){
        		if ($_SESSION['ORDERBY'] == "data_abertura") {
        			$_SESSION['ORDERBY'] = "data_abertura desc";
        			$_SESSION['TEXTO_ORDER'] = "data de abertura Z-a";
        		} else {
	        		$_SESSION['ORDERBY'] = "data_abertura";
	        		$_SESSION['TEXTO_ORDER'] = "data de abertura";
	        	}
        	} else
        	if ($_GET['ORDERBY'] == "AREA"){
        		if ($_SESSION['ORDERBY'] == "area, numero") {
        			$_SESSION['ORDERBY'] = "area desc, numero";
        			$_SESSION['TEXTO_ORDER'] = "área Z-a e número";
        		} else {
	        		$_SESSION['ORDERBY'] = "area, numero";
	        		$_SESSION['TEXTO_ORDER'] = "área e número do chamado (padrão)";
	        	}
        	} else
        	if ($_GET['ORDERBY'] == "LOCAL"){
        		if ($_SESSION['ORDERBY'] == "setor") {
        			$_SESSION['ORDERBY'] = "setor desc";
        			$_SESSION['TEXTO_ORDER'] = "Localização Z-a";
        		} else {
	        		$_SESSION['ORDERBY'] = "setor";
	        		$_SESSION['TEXTO_ORDER'] = "Localização";
	        	}
        	} else
        	if ($_GET['ORDERBY'] == "CONTATO"){
        		if ($_SESSION['ORDERBY'] == "contato") {
        			$_SESSION['ORDERBY'] = "contato desc";
        			$_SESSION['TEXTO_ORDER'] = "Contato Z-a";
        		} else {
	        		$_SESSION['ORDERBY'] = "contato";
	        		$_SESSION['TEXTO_ORDER'] = "contato";
	        	}
        	}
        }

	if (isset($_SESSION['ORDERBY'])) {
		if ($_SESSION['ORDERBY'] == "data_abertura desc") {
			$ICON_ORDER_DATA = "<img src='../../includes/css/OrderDesc.png' width='16' height='16' align='absmiddle'>";
			$ICON_ORDER_AREA = "";
			$ICON_ORDER_PROB = "";
			$ICON_ORDER_LOCAL = "";
			$ICON_ORDER_CONTATO = "";
		}
		else
		if ($_SESSION['ORDERBY'] == "data_abertura") {
			$ICON_ORDER_DATA = "<img src='../../includes/css/OrderAsc.png' width='16' height='16' align='absmiddle'>";
			$ICON_ORDER_AREA = "";
			$ICON_ORDER_PROB = "";
			$ICON_ORDER_LOCAL = "";
			$ICON_ORDER_CONTATO = "";
		}
		else
		if ($_SESSION['ORDERBY'] == "area desc, numero") {
			$ICON_ORDER_AREA = "<img src='../../includes/css/OrderDesc.png' width='16' height='16' align='absmiddle'>";
			$ICON_ORDER_DATA = "";
			$ICON_ORDER_PROB = "";
			$ICON_ORDER_LOCAL = "";
			$ICON_ORDER_CONTATO = "";
		}
		else
		if ($_SESSION['ORDERBY'] == "area, numero") {
			$ICON_ORDER_AREA = "<img src='../../includes/css/OrderAsc.png' width='16' height='16' align='absmiddle'>";
			$ICON_ORDER_DATA = "";
			$ICON_ORDER_PROB = "";
			$ICON_ORDER_LOCAL = "";
			$ICON_ORDER_CONTATO = "";
		}
		else
		if ($_SESSION['ORDERBY'] == "area, problema desc, numero") {
			$ICON_ORDER_PROB = "<img src='../../includes/css/OrderDesc.png' width='16' height='16' align='absmiddle'>";
			$ICON_ORDER_DATA = "";
			$ICON_ORDER_AREA = "";
			$ICON_ORDER_LOCAL = "";
			$ICON_ORDER_CONTATO = "";
		}
		else
		if ($_SESSION['ORDERBY'] == "area, problema, numero") {
			$ICON_ORDER_PROB = "<img src='../../includes/css/OrderAsc.png' width='16' height='16' align='absmiddle'>";
			$ICON_ORDER_DATA = "";
			$ICON_ORDER_AREA = "";
			$ICON_ORDER_LOCAL = "";
			$ICON_ORDER_CONTATO = "";
		}
		else
		if ($_SESSION['ORDERBY'] == "setor desc") {
			$ICON_ORDER_LOCAL = "<img src='../../includes/css/OrderDesc.png' width='16' height='16' align='absmiddle'>";
			$ICON_ORDER_DATA = "";
			$ICON_ORDER_AREA = "";
			$ICON_ORDER_PROB = "";
			$ICON_ORDER_CONTATO = "";
		}
		else
		if ($_SESSION['ORDERBY'] == "setor") {
			$ICON_ORDER_LOCAL = "<img src='../../includes/css/OrderAsc.png' width='16' height='16' align='absmiddle'>";
			$ICON_ORDER_DATA = "";
			$ICON_ORDER_AREA = "";
			$ICON_ORDER_PROB = "";
			$ICON_ORDER_CONTATO = "";
		} else
		if ($_SESSION['ORDERBY'] == "contato desc") {
			$ICON_ORDER_CONTATO = "<img src='../../includes/css/OrderDesc.png' width='16' height='16' align='absmiddle'>";
			$ICON_ORDER_DATA = "";
			$ICON_ORDER_AREA = "";
			$ICON_ORDER_PROB = "";
			$ICON_ORDER_LOCAL = "";
		}
		else
		if ($_SESSION['ORDERBY'] == "contato") {
			$ICON_ORDER_CONTATO = "<img src='../../includes/css/OrderAsc.png' width='16' height='16' align='absmiddle'>";
			$ICON_ORDER_DATA = "";
			$ICON_ORDER_AREA = "";
			$ICON_ORDER_PROB = "";
			$ICON_ORDER_LOCAL = "";
		}
	}

        $query = $QRY["ocorrencias_full_ini"]." WHERE s.stat_painel in (2) and o.sistema in (".$uareas.") ".
        			" and o.oco_scheduled=0 ORDER BY ".$_SESSION['ORDERBY']."";

	$resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);

	if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);

	if (isset($_GET['FULL'])){
		$_SESSION['s_paging_full'] = $_GET['FULL'];
	}

	$PAGE->setSQL($query,$_SESSION['s_paging_full']);
	$PAGE->execSQL();

// 	if (isset($_GET['LIMIT']))
// 		$PAGE->setLimit($_GET['LIMIT']);
// 	$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));
// 	$PAGE->execSQL();

        if ($linhas == 0) {
        	echo mensagem("".TRANS('OCO_NOT_PENDING_IN_SYSTEM','Não existe nenhuma ocorrência pendente no sistema').".");
        	exit;
        } else
        if ($linhas>1)
        	print "<TR><TD><B>".TRANS('THEREARE')."&nbsp;".$linhas."&nbsp;".TRANS('OCO_OCORRENCIAS','ocorrências')."".
        			"&nbsp;<font color='red'>".TRANS('OCO_PENDING','pendentes')."</font>".
        			"&nbsp;".TRANS('OCO_IN_THE_SYSTEM','no sistema').".".
        			"&nbsp;".TRANS('OCO_ORDER_BY','Ordenadas por')."&nbsp;".$_SESSION['TEXTO_ORDER'].".</B></TD></TR>";
        else
	       print "<TR><TD  class='line' ><B>".TRANS('THEREIS','existe')."&nbsp;".TRANS('OCO_ONLY_ONE_CALL','apenas 1 ocorrência')."".
				"&nbsp;<font color='red'>".TRANS('OCO_PENDING_ONE','pendente')."</font>".
				"&nbsp;".TRANS('OCO_IN_THE_SYSTEM','no sistema').".</B></TD></TR>";
        //print "</TD>";

        print "<TD  class='line' >";
        print "<TABLE class='header_centro'  STYLE='{border-top: thin solid #999999;}' border='0' cellpadding='2' cellspacing='0' align='center' width='100%'>";  //cellpadding='2' cellspacing='0'

        print "<TR class='header'>";
        print "<TD  class='line'  nowrap>".TRANS('OCO_NUMBER_BRIEF','N.º')."&nbsp;/&nbsp;<a onClick=\"redirect('".$_SERVER['PHP_SELF']."?&ORDERBY=AREA')\" title='Ordena por Área de atendimento'>".TRANS('OCO_AREA','Área')."".$ICON_ORDER_AREA."</a></TD>".
        	"<TD  class='line' ><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?&ORDERBY=PROB')\" title='Ordena por tipo de problema'>".TRANS('OCO_PROB')."".$ICON_ORDER_PROB."</a></TD>".
        	"<TD  class='line' ><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?&ORDERBY=CONTATO')\" title='Ordena pelo contato'>".TRANS('OCO_CONTACT')."".$ICON_ORDER_CONTATO."</a><BR>".TRANS('OCO_PHONE','Ramal')."</TD>".
        	"<TD  class='line' WIDTH=250><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?&ORDERBY=LOCAL')\" title='Ordena por Localização'>".TRANS('OCO_LOCAL')."".$ICON_ORDER_LOCAL."</a><br>".TRANS('OCO_DESC')."</TD>".
        	"<TD  class='line' nowrap><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?&ORDERBY=DATA')\" title='Order by ".TRANS('HNT_VALID_TIME')."'>".TRANS('OCO_VALID_TIME')."".$ICON_ORDER_DATA."</a></TD>".
        	//<td class='line'>Ação</TD>
        	"<TD  class='line' ><a title='".TRANS('HNT_RESPONSE_TIME')."'>".TRANS('OCO_RESPONSE')."</a></TD>".
        	"<TD  class='line' ><a title='".TRANS('HNT_SOLUTION_TIME')."'>".TRANS('OCO_SOLUC')."</a></TD>";
        print "</TR>";
        $i=0;
        $j=2;
        //while ($row = mysql_fetch_array($resultado))
        while ($row=mysql_fetch_array($PAGE->RESULT_SQL))
        {
		if ($j % 2) {
			$trClass = "lin_par";
		}
		else {
			$trClass = "lin_impar";
		}
		$j++;

		print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linha".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linha".$j."','".$_SESSION['s_colorMarca']."');\">";

		$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." or dep_filho=".$row['numero']."";
		$execSubCall = mysql_query($sqlSubCall) or die (TRANS('ERR_QUERY').'<br>'.$sqlSubCall);
		$regSub = mysql_num_rows($execSubCall);
		if ($regSub > 0) {
			#É CHAMADO PAI?
			$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die (TRANS('ERR_QUERY').'<br>'.$sqlSubCall);
			$regSub = mysql_num_rows($execSubCall);
			$comDeps = false;
			while ($rowSubPai = mysql_fetch_array($execSubCall)){
				$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
				$execStatus = mysql_query($sqlStatus) or die (TRANS('ERR_QUERY').$sqlStatus);
				$regStatus = mysql_num_rows($execStatus);
				if ($regStatus > 0) {
					$comDeps = true;
				}
			}
			if ($comDeps) {
				$imgSub = "<a onClick=\"javascript:popup('../../includes/help/help_depends.php')\"><img src='".ICONS_PATH."view_tree_red.png' width='16' height='16' title='Chamado com restrições para encerramento'></a>";
			} else
				$imgSub =  "<a onClick=\"javascript:popup('../../includes/help/help_depends.php')\"><img src='".ICONS_PATH."view_tree_green.png' width='16' height='16' title='Chamado com vínculos mas sem restrições de encerramento'></a>";
		} else
			$imgSub = "";

		$qryImg = "select * from imagens where img_oco = ".$row['numero']."";
		$execImg = mysql_query($qryImg) or die (TRANS('ERR_QUERY'));
		$rowTela = mysql_fetch_array($execImg);
		$regImg = mysql_num_rows($execImg);
		if ($regImg!=0) {
			//$linkImg = "<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$row['numero']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\"><img src='".ICONS_PATH."attach2.png' width='16' height='16'></a>";
			$linkImg = "<a onClick=\"javascript:popup_wide('listFiles.php?COD=".$row['numero']."')\"><img src='../../includes/icons/attach2.png'></a>";
		} else $linkImg = "";

		print "<TD  class='line'  ".$valign."><b><a href='mostra_consulta.php?numero=".$row['numero']."'>".$row['numero']."</a></b>".$imgSub."<br>".($row['area']==''?'&nbsp;':$row['area'])."</td>";
		print "<TD  class='line'  ".$valign.">".$linkImg."&nbsp;".($row['problema']==''?'&nbsp;':$row['problema'])."</td>";
		print "<TD  class='line'  ".$valign."><b>".$row['contato']."</b><br>".$row['telefone']."</td>";

		$limite = 150;
		$texto = trim($row['descricao']);

		$hnt = " title='Clique aqui para ver os detalhes do chamado!'";
		if ((strlen(toHtml($texto))>$limite) || (strlen($texto) > $limite) ){
			$hnt = "";

			$arrayHNT = explode(chr(10), $texto); //EXTRAINDO AS LINE FEED
			for ($i=0; $i<count($arrayHNT); $i++) {
				$hnt.=trim($arrayHNT[$i]);
			}
			$hnt = noHtml($hnt);
			//$texto = substr($texto,0,($limite-4))."<a href='#' onmousemove=\"showToolTip(event,'".$hnt."', 'bubble_tooltip', 'bubble_tooltip_content'); return false\" onmouseout=\"hideToolTip('bubble_tooltip')\"> ...</a> ";
			$texto = substr($texto,0,($limite-4))."...";
			$hnt = "onmousemove=\"showToolTip(event,'".$hnt."', 'bubble_tooltip', 'bubble_tooltip_content'); return false\" onmouseout=\"hideToolTip('bubble_tooltip')\"";
		};
		print "<TD  class='line'  ".$valign."><b>".$row['setor']."</b><br><a class='no' href='mostra_consulta.php?numero=".$row['numero']."' ".$hnt.">".$texto."</a></td>";

          	$data = dataRED($row['data_abertura']);

		$areaT = "";
		$areaT=testaArea($areaT,$row['area_cod'],$H_horarios);

		$data = $row['data_abertura']; //data de abertura do chamado
            	$dataAtendimento = $row['data_atendimento']; //data da primeira resposta ao chamado

		$diff = date_diff($data,date("Y-m-d H:i:s"));
		$sep = explode ("dias",$diff);
		if ($sep[0]>20) { //Se o chamado estiver aberto a mais de 20 dias o tempo é mostrado em dias para não ficar muito pesado.
			$imgSlaR = 'checked.png';
			$imgSlaS = 'checked.png';
			print "<TD  class='line'  ".$valign."><font color='red'><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=".$row['numero']."')\">".$sep[0]." dias</a></font>".
					"<br>".$row['chamado_status']."</TD>";
		} else {

			$dt->setData1($data);
			$dt->setData2(date("Y-m-d H:i:s"));
			$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$areaT][0],$H_horarios[$areaT][1],$H_horarios[$areaT][2],$H_horarios[$areaT][3],"H");

			$horas = $dt->diff["hValido"];	//horas válidas
			$segundos = $dt->diff["sValido"]; //segundos válidos

			if ($dataAtendimento == ""){//Controle das bolinhas de SLA de Resposta
				if ($segundos<=($row['sla_resposta_tempo']*60)){
					$imgSlaR = 'sla1.png';
				} else if ($segundos  <=(($row['sla_resposta_tempo']*60) + (($row['sla_resposta_tempo']*60) *$percLimit/100)) ){
						$imgSlaR = 'sla2.png';
				} else {
					$imgSlaR = 'sla3.png';
				}
			} else
				$imgSlaR = 'checked.png';

			$sla_tempo = $row['sla_solucao_tempo'];
			if ($sla_tempo !="") { //Controle das bolinhas de SLA de solução
				if ($segundos <= ($row['sla_solucao_tempo']*60)){
					$imgSlaS = 'sla1.png';
				} else if ($segundos  <=(($row['sla_solucao_tempo']*60) + (($row['sla_solucao_tempo']*60) *$percLimit/100)) ){
					$imgSlaS = 'sla2.png';
				} else
					$imgSlaS = 'sla3.png';
			} else
				$imgSlaS = 'checked.png';

			print "<TD  class='line'  ".$valign."><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=".$row['numero']."')\">".$dt->tValido."</a>".
				"<br>".$row['chamado_status']."</TD>";
		}

// 		print "<TD ".$valign." align='center'>";
// 		print "<a href='mostra_consulta.php?numero=".$row['numero']."'><img title='Consultar' width='15' height='15' src='".$imgsPath."consulta.gif' border='0'></a>";
// 		print "</TD>";

		print "<TD  class='line'  ".$valign." align='center'><a onClick=\"javascript:popup('../../includes/help/sla_popup.php?sla=r')\"><img height='14' width='14' src='".$imgsPath."".$imgSlaR."'></a></TD>";
		print "<TD  class='line'  ".$valign." align='center'><a onClick=\"javascript:popup('../../includes/help/sla_popup.php?sla=s')\"><img height='14' width='14' src='".$imgsPath."".$imgSlaS."'></a></TD>";

		echo "</TR>";
            	$i++;
        }//while

        print "</TABLE>";
	print "<tr><td colspan='7'>";
	$PAGE->showOutputPages();
	print "</td></tr>";

print "</body>";
print "</html>";
?>
