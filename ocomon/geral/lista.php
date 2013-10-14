<?php
/*#################################################
#	Este Script, lista todos os chamados que estão vinculados ao Área de Sistemas.
#	Os chamados são separados pelos seguintes status: 
#	- Aguardando Atendimento
#	- E os demais status (Aguardando Atendimento, Indisponível para atendimento, etc).
#		
#	Criação: Alexsandro Corrêa
#	Data: 18/06/2008
#################################################*/

session_start();

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	include ("../../includes/classes/paging.class.php");

	$PAGE = new paging;
	$PAGE->setRegPerPage(10);

	$imgsPath = "../../includes/imgs/";
	$valign = " VALIGN = TOP ";

	print "<html>";
	print "<head>";

	?>
	<script type="text/javascript">

		function popup(pagina)	{ //Exibe uma janela popUP
			x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
			x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
			return false
		}
		window.setInterval("redirect('lista.php')",120000);
	</script>
	<?

	print "</head>";
	$auth = new auth;
	
	//COLOCAR AQUI O TITULO PARA SER EXIBIDO NA TELA

	//DIV PARA MOSTRAR OS HINTS
	print "<div id='bubble_tooltip'>";
		print "<div class='bubble_top'><span></span></div>";
		print "<div class='bubble_middle'><span id='bubble_tooltip_content'></span></div>";
		print "<div class='bubble_bottom'></div>";
	print "</div>";


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

        //MOSTRA TODOS OS CHAMADOS QUE ESTÃO EM ATENDIMENTO PARA ÁREA DE SISTEMAS
        //PAINEL 1 É O PAINEL SUPERIOR DA TELA DE ABERTURA

	//BUSCA OS CHAMADOS EM ATENDIMENTO
	$query = $QRY["ocorrencias_full_ini"]." WHERE s.stat_painel in (1) and a.sis_id ='7'";
	$resultado_oco = mysql_query($query) or die ('OS DADOS NÃO PUDERÃO SER CONSULTADOS!!!'.$query);
        $linhas = mysql_num_rows($resultado_oco);

	if ($linhas == 0) {
        	echo mensagem('Não existem ocorrências pendentes para esta Área');
        }
        else {
		if ($linhas>1) {
			//VARIÁVEIS DE SESSÃO PARA O COLLAPSE DOS CHAMADOS VINCULADOS AO OPERADOR LOGADO
			if (!isset($_SESSION['ICON_CHAVE'])) {
				$_SESSION['ICON_CHAVE']="close.png";
			}

			if (!isset($_SESSION['CHAVE'])) {
				$_SESSION['CHAVE'] = "";
			} else
			if (isset($_GET['CHAVE'])) {
				if ($_GET['CHAVE'] == "{display:none}") {
					$_SESSION['CHAVE'] = "";
					$_SESSION['ICON_CHAVE']="close.png";
				} else {
					$_SESSION['CHAVE'] = "{display:none}";
					$_SESSION['ICON_CHAVE']="open.png";
				}
			}



		} else {
		}
		print "<tr><TD>&nbsp;<b>Ocorrências&nbsp;<font color = 'red'>em atendimento</font> pela Área de Sistemas de Informação&nbsp;.</b></td></tr>";

		print "<tr><td colspan='4'></td></tr>";
		print "<tr><td colspan='4'><div id='Vinculados' style='".$_SESSION['CHAVE']."'>"; 

		print "<TABLE class='header_centro'  border-top: thin solid #999999;}' border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='".$cor."'>";
		print "<TR class='header'>";
			print "<TD  class='line' >Nº</TD><TD class='line' >Problema</TD>".
				"<TD  class='line' >Contato<BR>Ramal</TD>".
				"<TD  class='line'  WIDTH='250'>Local</TD>".
				"<TD  class='line' >Status</TD>".
				"<TD  class='line' ><a title='Nome do Operador'>Operador</a></TD>";
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
		$execImg = mysql_query($qryImg) or die ('OS DADOS NÃO PUDERÃO SER CONSULTADOS!!');
		$rowTela = mysql_fetch_array($execImg);
		$regImg = mysql_num_rows($execImg);
		if ($regImg!=0) {
	
			$linkImg = "<a onClick=\"javascript:popup_wide('listFiles.php?COD=".$rowAT['numero']."')\"><img src='../../includes/icons/attach2.png'></a>";
		} else $linkImg = "";

		$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']." or dep_filho=".$rowAT['numero']."";
		$execSubCall = mysql_query($sqlSubCall) or die (('OS DADOS NÃO PUDERÃO SER CONSULTADOS!!').'<br>'.$sqlSubCall);
		$regSub = mysql_num_rows($execSubCall);
		if ($regSub > 0) {
			#É CHAMADO PAI?
			$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die (('OS DADOS NÃO PUDERÃO SER CONSULTADOS!!').$sqlSubCall);
			$regSub = mysql_num_rows($execSubCall);
			$comDeps = false;
			while ($rowSubPai = mysql_fetch_array($execSubCall)){
				$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
				$execStatus = mysql_query($sqlStatus) or die (('OS DADOS NÃO PUDERÃO SER CONSULTADOS!!').$sqlStatus);
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

		print "<TD  class='line'  ".$valign.">".$rowAT['numero']."</a>".$imgSub."</TD>";
		print "<TD  class='line'  ".$valign.">".$linkImg."&nbsp;".$rowAT['problema']."</TD>";
		print "<TD  class='line'  ".$valign."><b>".$rowAT['contato']."</b><br>".$rowAT['telefone']."</TD>";
		print "<TD  class='line'  ".$valign."><b>".$rowAT['setor']."</b><br>";
		$texto = trim($rowAT['descricao']);
		if (strlen($texto)>200){
			$texto = substr($texto,0,195)." ..... ";
		};
		print "".$texto."</a>";
	        print "</TD>";
            	print "<TD  class='line'  ".$valign.">".$rowAT['chamado_status']."</TD>";
		print "<TD  class='line'  ".$valign.">".$rowAT['nome']."</TD>";
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

		//---------------------------- OCORRÊNCIAS QUE AINDA NÃO FORAM ATENDIDAS ----------------------------------//
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

        $query = $QRY["ocorrencias_full_ini"]." WHERE s.stat_painel in (2) and a.sis_id ='7'";

	$resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);

	if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);

	if (isset($_GET['FULL'])){
		$_SESSION['s_paging_full'] = $_GET['FULL'];
	}

	$PAGE->setSQL($query,$_SESSION['s_paging_full']);
	$PAGE->execSQL();

        if ($linhas == 0) {
        	echo mensagem('Não existe nenhuma ocorrência pendente no sistema');
        	exit;
        } else
        if ($linhas>1)
        	print "<TR><TD><b>Ocorrências&nbsp;<font color ='red'>não atendidas</font> pela a Área de Sistemas de Informação.".
        			"&nbsp;Ordenar por&nbsp;".$_SESSION['TEXTO_ORDER'].".</B></TD></TR>";
        else
	       print "<TR><TD  class='line' ><B>Existe&nbsp;apenas 1 ocorrência".
				"&nbsp;<font color='red'>pendênte</font>".
				"&nbsp;no sistema.</B></TD></TR>";
        //print "</TD>";

        print "<TD  class='line' >";
        print "<TABLE class='header_centro'  STYLE='{border-top: thin solid #999999;}' border='0' cellpadding='2' cellspacing='0' align='center' width='100%'>";  //cellpadding='2' cellspacing='0'

        print "<TR class='header'>";
        print "<TD  class='line'  nowrap>N.º&nbsp;/&nbsp;<a onClick=\"redirect('".$_SERVER['PHP_SELF']."?&ORDERBY=AREA')\" title='Ordena por Área de atendimento'>Área".$ICON_ORDER_AREA."</a></TD>".
        	"<TD  class='line' ><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?&ORDERBY=PROB')\" title='Ordena por tipo de problema'>Problema".$ICON_ORDER_PROB."</a></TD>".
        	"<TD  class='line' ><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?&ORDERBY=CONTATO')\" title='Ordena pelo contato'>Contato".$ICON_ORDER_CONTATO."</a><BR>Ramal</TD>".
        	"<TD  class='line' WIDTH=250><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?&ORDERBY=LOCAL')\" title='Ordena por Localização'>Local".$ICON_ORDER_LOCAL."</a><br>Descrição</TD>".
        	"<TD  class='line' nowrap><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?&ORDERBY=DATA')\" title='Order by Tempo Válido'>Tempo Válido".$ICON_ORDER_DATA."</a></TD>";
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
		$execSubCall = mysql_query($sqlSubCall) or die (('OS DADOS NÃO PUDERÃO SER CONSULTADOS!!').'<br>'.$sqlSubCall);
		$regSub = mysql_num_rows($execSubCall);
		if ($regSub > 0) {
			#É CHAMADO PAI?
			$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die (('OS DADOS NÃO PUDERÃO SER CONSULTADOS!!').'<br>'.$sqlSubCall);
			$regSub = mysql_num_rows($execSubCall);
			$comDeps = false;
			while ($rowSubPai = mysql_fetch_array($execSubCall)){
				$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
				$execStatus = mysql_query($sqlStatus) or die (('OS DADOS NÃO PUDERÃO SER CONSULTADOS!!').$sqlStatus);
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
		$execImg = mysql_query($qryImg) or die ('OS DADOS NÃO PUDERÃO SER CONSULTADOS!!');
		$rowTela = mysql_fetch_array($execImg);
		$regImg = mysql_num_rows($execImg);
		if ($regImg!=0) {
			$linkImg = "<a onClick=\"javascript:popup_wide('listFiles.php?COD=".$row['numero']."')\"><img src='../../includes/icons/attach2.png'></a>";
		} else $linkImg = "";

		print "<TD  class='line'  ".$valign."><b>".$row['numero']."</a></b>".$imgSub."<br>".($row['area']==''?'&nbsp;':$row['area'])."</td>";
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

			$texto = substr($texto,0,($limite-4))."...";
			$hnt = "onmousemove=\"showToolTip(event,'".$hnt."', 'bubble_tooltip', 'bubble_tooltip_content'); return false\" onmouseout=\"hideToolTip('bubble_tooltip')\"";
		};
		print "<TD  class='line'  ".$valign."><b>".$row['setor']."</b><br>".$texto."</a></td>";

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
