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

	$_SESSION['s_page_home'] = $_SERVER['PHP_SELF'];

	$imgsPath = "../../includes/imgs/";

	$hoje = date("Y-m-d H:i:s");
	$valign = " VALIGN = TOP ";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);

?>
<HTML>
<head>
<META HTTP-EQUIV='Refresh' CONTENT="120; URL=abertura_user.php?action=listall">
<script type="text/javascript">
	function popup(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
		return false
	}
</script>
</head>
<?

	$dt = new dateOpers; //Criado o objeto $dt
	$dta = new dateOpers;

	$cor  = TD_COLOR;
    	$cor1 = TD_COLOR;
    	$cor3 = BODY_COLOR;

	$percLimit = 20; //Tolerância em percentual
	$imgSlaR = 'sla1.png';
	$imgSlaS = 'checked.png';

	print "<br>";

        //OCORRÊNCIAS VINCULADAS AO OPERADOR
        //PAINEL 1 É O PAINEL SUPERIOR DA TELA DE ABERTURA
        $query = $QRY["ocorrencias_full_ini"]." where o.aberto_por = ".$_SESSION['s_uid']." and s.stat_painel not in(3) order by numero";
	$resultado_oco = mysql_query($query);
        $linhas = mysql_num_rows($resultado_oco);

	if ($linhas == 0)
        {
                echo mensagem("Não foi encontrada nenhuma ocorrência ativa aberta por você.");
        }
        else
        {//OCORRENCIAS ABERTAS PELO USUÁRIO
		if ($linhas>1)
		{
			print "<TR class='header'><td class='line'>Foram encontradas ".$linhas." ocorrências <font color=red><b>ativas</b></font> abertas por você.</TD></TR>";
				//print "</TD>";
		}
		else
		{
			print "<TR class='header'><td class='line'>Foi encontrada ".$linhas." ocorrência <font color=red><b>ativa</b></font> aberta por você.</TD></TR>";
		}
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='".$cor."'>";
		print "<TR class='header'>";
		print "	<td class='line'>Chamado</TD>";
		print "	<td class='line'>Problema</TD>";
		print "	<td class='line'>Contato<BR>Ramal</TD>";
		print " <td class='line'>Local<br>Descrição</TD>";
		print " <td class='line'>Área</TD>";
		print "	<td class='line'>Status</TD>";
		print "	<td class='line'>Tempo<br>válido</TD>";
		print "	<td class='line'>RESP.</TD>";
		print "	<td class='line'>SOLUC.</TD>";
		print "</TR>";
	}
        $i=0;
        $j=2;

        while ($rowAT = mysql_fetch_array($resultado_oco))
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
		//print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";
		print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";

			$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']." or dep_filho=".$rowAT['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
			$regSub = mysql_num_rows($execSubCall);
			if ($regSub > 0) {
				#É CHAMADO PAI?
				$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']."";
				$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
				$regSub = mysql_num_rows($execSubCall);
				$comDeps = false;
				while ($rowSubPai = mysql_fetch_array($execSubCall)){
					$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
					$execStatus = mysql_query($sqlStatus) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE STATUS DOS CHAMADOS FILHOS<br>'.$sqlStatus);
					$regStatus = mysql_num_rows($execStatus);
					if ($regStatus > 0) {
						$comDeps = true;
					}
				}
				if ($comDeps) {
					$imgSub = "<img src='".ICONS_PATH."view_tree_red.png' width='16' height='16' title='Chamado com vínculos pendentes'>";
				} else
					$imgSub =  "<img src='".ICONS_PATH."view_tree_green.png' width='16' height='16' title='Chamado com vínculos mas sem pendências'>";
			} else
				$imgSub = "";

		print "<TD class='line' ".$valign."><a href='mostra_consulta.php?numero=".$rowAT['numero']."'>".$rowAT['numero']."</a>".$imgSub."</TD>";
		print "<TD  class='line' ".$valign.">".$rowAT['problema']."</TD>";
		print "<TD  class='line' ".$valign."><b>".$rowAT['contato']."</b><br>".$rowAT['telefone']."</TD>";
		print "<TD  class='line' ".$valign."><b>".$rowAT['setor']."</b><br>";
		$texto = trim($rowAT['descricao']);
		if (strlen($texto)>200){
			$texto = substr($texto,0,195)." ..... ";
		};
		print $texto;
            	print "</TD>";
            	print "<TD class='line'  ".$valign.">".$rowAT['area']."</TD>";
            	print "<TD class='line'  ".$valign.">".$rowAT['chamado_status']."</TD>";

			// if (array_key_exists($rowAT['cod_area'],$H_horarios)){  //verifica se o código da área possui carga horária definida no arquivo config.inc.php
					//$areaChamado = $rowAT['cod_area']; //Recebe o valor da área de atendimento do chamado
			// } else $areaChamado = 1; //Carga horária default definida no arquivo config.inc.php
			$areaChamado = "";
			$areaChamado=testaArea($areaChamado,$rowAT['area_cod'],$H_horarios);

			$data = $rowAT['data_abertura'];

			$diff = date_diff($data,$hoje);
			$sep = explode ("dias",$diff);
			if ($sep[0]>20) { //Se o chamado estiver aberto a mais de 20 dias o tempo é mostrado em dias para não ficar muito pesado.
				$diff = $sep[0]." dias";
				$segundos = ($sep[0]*86400);
			} else {
				$dta->setData1($data);
				$dta->setData2($hoje);

				$dta->tempo_valido($dta->data1,$dta->data2,$H_horarios[$areaChamado][0],$H_horarios[$areaChamado][1],$H_horarios[$areaChamado][2],$H_horarios[$areaChamado][3],"H");
				$diff = $dta->tValido;
				$diff2 = $dta->diff["hValido"];
				$segundos = $dta->diff["sValido"]; //segundos válidos
			}
		print "<TD  class='line' ".$valign.">".$diff."</TD>";

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

			print "<TD class='line'  $valign align='center'><a onClick=\"javascript:popup('sla_popup.php?sla=r')\"><img height='14' width='14' src='".$imgsPath."".$imgSlaR."'></a></TD>";
			print "<TD  class='line' $valign align='center'><a onClick=\"javascript:popup('sla_popup.php?sla=s')\"><img height='14' width='14' src='".$imgsPath."".$imgSlaS."'></a></TD>";

			print "</TR>";
			$i++;
        }
        print "</TABLE>";
        print "<HR>";

	if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'listall') {
        //TODAS OCORRÊNCIAS VINCULADAS AO OPERADOR

        	$query = $QRY["ocorrencias_full_ini"]." where o.aberto_por = ".$_SESSION['s_uid']." and s.stat_painel in(3) order by numero DESC ";

		$qrytmp = $query;
		$exectmp = mysql_query($qrytmp) or die ('NÃO FOI POSSÍVEL RODAR A QUERY TEMPORÁRIA!'.$qrytmp);
		$linhasTotal = mysql_num_rows($exectmp);


		/*------------------------------------------------------------------------------
		@$min = PRIMEIRO REGISTRO A SER EXIBIDO
		@$max = QUANTIDADE DE REGISTROS POR PÁGINA
		@$top = NÚMERO DO ÚLTIMO REGISTRO EXIBIDO DA PÁGINA
		@$base = NÚMERO DO PRIMEIRO REGISTRO EXIBIDO DA PÁGINA
		--------------------------------------------------------------------------------*/

		$min = 0;
		$maxAux = 0;
		$minAux = 0;
		$page = 5;

		if (!isset($_POST['min']))  {
			$min =0;
		} else $min = $_POST['min'];

		if (!isset($_POST['max']))  {
			$max =$page;
			if ($max > $linhasTotal) {
				$maxAux = $max;
				$max = $linhasTotal;
			}
		} else {
			$max = $_POST['max'] ;//$linhasTotal;
			$maxAux = $_POST['max'];
			if ($max > $linhasTotal) {
				$maxAux = $max;
				$max = $linhasTotal;
			}
		}

		if (!isset($_POST['top'])) {
			if ($max < $linhasTotal) {
				$top = $max;
			} else
				$top = $linhasTotal;
		} else
			$top = $_POST['top'];

		if (!isset($_POST['base'])) {
			$base = $min+1;
		} else
			$base = $_POST['base'];

		if (isset($_POST['avancaUm'])) {
			$minAux = $min;
			$min += $max;
			if ($min >=($linhasTotal)) {
				$min = $minAux;
			}
			$top += $max;
			if ($top >$linhasTotal) {
				$base = $min+1;
				$top = $linhasTotal;
			} else {
				if ($base < (($top - $max))) {
					$base += $max;
				} else {
					$base-=$max;
				}
			}
		} else
		if (isset($_POST['avancaFim'])) {
			$minAux = $min;
			$min=$linhasTotal - $page;
			if ($min <=0) {
				$min = $minAux;
			}
			$top = $linhasTotal;
			$base = ($linhasTotal - $page)+1;
		} else
		if (isset($_POST['avancaTodos'])) {
			$max=$linhasTotal;
			$min=0;
			$top = $linhasTotal;
			$base = $linhasTotal - $max;
		} else
		if (isset($_POST['voltaUm']) ) {
			if (($_POST['max']==$linhasTotal) && ($_POST['min']==0)) {$max=$_POST['maxAux']; $min=$linhasTotal;}
				 //Está exibindo todos os registros na tela!

			$min-=$_POST['max'];
			if ($min<0) {$min=0;};

			if (($top - $base) < $max) {
				$top = $base -1;
			} else $top-=$max;
			$base-=$max;
		} else
		if (isset($_POST['voltaInicio']) ) {
			$min=0;
			$max = $page;
			$top = $max;
			$base = 1;
		}

		if ($top > $linhasTotal) {
			$top = $linhasTotal;
		} else
		if ($top < $max) {
			$top = $max;
		}
		if ($base < 1) {
			$base = 1;
		}


		$query.=" LIMIT ".$min.", ".$max."";

		$resultado_oco = mysql_query($query) or die ('ERRO: '.$query);
		$linhas = mysql_num_rows($resultado_oco);

		if ($linhas == 0) {
			print mensagem("Não existe nenhuma ocorrência inativa aberta por você no sistema.");
		}
		else {
			if ($linhas == 1) {
				print "<TR class='header'><td class='line'>Existe ".$linhas." ocorrência <font color=red><b>inativa</b></font> aberta por você no sistema.</TD></TR>";
			} else {
				print "<FORM name='form1' method='POST' action='".$_SERVER['PHP_SELF']."?action=listall'>";
				$min++;

				print "<tr>";
				print "<TD witdh='700' align=left><B>Existem <font color=red>".$linhasTotal."</font> ocorrências <font color=red>".
					"<b>inativas</b></font> abertas por você. Mostradas as mais recentes de <font color=red>".$base."</font> a ".
					"<font color=red>".$top."</font>. </B></TD>";
				print "<TD colspan='2' width='300' align='right' ><input  type='submit' class='button' name='voltaInicio' value='<<' ".
					"title='Visualiza os ".$max." primeiros registros.'> <input  type='submit' class='button'  name='voltaUm' value='<' ".
					"title='Visualiza os ".$max." registros anteriores.'> <input  type='submit' class='button'  name='avancaUm' value='>' ".
					"title='Visualiza os próximos ".$max." registros.'> <input  type='submit' class='button'  name='avancaFim' value='>>' ".
					"title='Visualiza os últimos ".$max." registros.'> <input  type='submit' class='button'  name='avancaTodos' value='Todas' ".
					"title='Visualiza todos os ".$linhasTotal." registros.'></td>";
				print "</tr>";
				$min--;
				print "<input type='hidden' value='".$min."' name='min'>";
				print "<input type='hidden' value='".$max."' name='max'>";
				print "<input type='hidden' value='".$maxAux."' name='maxAux'>";
				print "<input type='hidden' value='".$base."' name='base'>";
				print "<input type='hidden' value='".$top."' name='top'>";

// 				print "<input type='hidden' value='".$minClose."' name='minClose'>";
// 				print "<input type='hidden' value='".$maxClose."' name='maxClose'>";
// 				print "<input type='hidden' value='".$maxAuxClose."' name='maxAux'>";
// 				print "<input type='hidden' value='".$baseClose."' name='baseClose'>";
// 				print "<input type='hidden' value='".$topClose."' name='topClose'>";

				print "</form>";
				print "</table>";
			}

			print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='".$cor."'>";
			print "<TR class='header'>";
			print "	<td class='line'>Chamado</TD>";
			print "	<td class='line'>Problema</TD>";
			print "	<td class='line'>Contato<BR>Ramal</TD>";
			print " <td class='line'>Local<br>Descrição</TD>";
			print "	<td class='line'>Status</TD>";
			print "</TR>";
		}
		$i=0;
		$j=2;
		while ($rowAT = mysql_fetch_array($resultado_oco))
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
			//print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."');\" onMouseOut=\"libera('linhax".$j."');\"  onMouseDown=\"marca('linhax".$j."');\">";
			print "<tr class=".$trClass." id='linhaz".$j."' onMouseOver=\"destaca('linhaz".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhaz".$j."');\"  onMouseDown=\"marca('linhaz".$j."','".$_SESSION['s_colorMarca']."');\">";

			$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']." or dep_filho=".$rowAT['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
			$regSub = mysql_num_rows($execSubCall);
			if ($regSub > 0) {
				#É CHAMADO PAI?
				$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']."";
				$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
				$regSub = mysql_num_rows($execSubCall);
				$comDeps = false;
				while ($rowSubPai = mysql_fetch_array($execSubCall)){
					$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
					$execStatus = mysql_query($sqlStatus) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE STATUS DOS CHAMADOS FILHOS<br>'.$sqlStatus);
					$regStatus = mysql_num_rows($execStatus);
					if ($regStatus > 0) {
						$comDeps = true;
					}
				}
				if ($comDeps) {
					$imgSub = "<img src='".ICONS_PATH."view_tree_red.png' width='16' height='16' title='Chamado com vínculos pendentes'>";
				} else
					$imgSub =  "<img src='".ICONS_PATH."view_tree_green.png' width='16' height='16' title='Chamado com vínculos mas sem pendências'>";
			} else
				$imgSub = "";

			print "<TD  class='line' ".$valign."><a href='mostra_consulta.php?numero=".$rowAT['numero']."'>".$rowAT['numero']."</a>".$imgSub."</TD>";
			print "<TD  class='line' ".$valign.">".$rowAT['problema']."</TD>";
			print "<TD  class='line' ".$valign."><b>".$rowAT['contato']."</b><br>".$rowAT['telefone']."</TD>";
			print "<TD  class='line' ".$valign."><b>".$rowAT['setor']."</b><br>";
			$texto = trim($rowAT['descricao']);
			if (strlen($texto)>200){
				$texto = substr($texto,0,195)." ..... ";
			};
			print $texto;
			print "</TD>";
			print "<TD class='line'  ".$valign.">".$rowAT['chamado_status']."</TD>";
			print "</TR>";
			$i++;
		}
		print "</TABLE>";
		print "<HR>";
	//####################################################################
        //TODAS OCORRÊNCIAS CONCLUÍDAS PELO OPERADOR

        	$queryClose = $QRY["ocorrencias_full_ini"]." where o.operador = ".$_SESSION['s_uid']." and s.stat_painel in(3) order by numero DESC ";

		$qryCloseTmp = $queryClose;
		$execCloseTmp = mysql_query($qryCloseTmp) or die ('NÃO FOI POSSÍVEL RODAR A QUERY TEMPORÁRIA!'.$qryCloseTmp);
		$linhasCloseTotal = mysql_num_rows($execCloseTmp);

		/*------------------------------------------------------------------------------
		@$min = PRIMEIRO REGISTRO A SER EXIBIDO
		@$max = QUANTIDADE DE REGISTROS POR PÁGINA
		@$top = NÚMERO DO ÚLTIMO REGISTRO EXIBIDO DA PÁGINA
		@$base = NÚMERO DO PRIMEIRO REGISTRO EXIBIDO DA PÁGINA
		--------------------------------------------------------------------------------*/

		$minClose = 0;
		$maxAuxClose = 0;
		$minAuxClose = 0;
		$page = 5;

		if (!isset($_POST['minClose']))  {
			$minClose =0;
		} else $minClose = $_POST['minClose'];

		if (!isset($_POST['maxClose']))  {
			$maxClose =$page;
			if ($maxClose > $linhasCloseTotal) {
				$maxAuxClose = $maxClose;
				$maxClose = $linhasCloseTotal;
			}
		} else {
			$maxClose = $_POST['maxClose'] ;//$linhasCloseTotal;
			$maxAuxClose = $_POST['maxClose'];
			if ($maxClose > $linhasCloseTotal) {
				$maxAuxClose = $maxClose;
				$maxClose = $linhasCloseTotal;
			}
		}

		if (!isset($_POST['topClose'])) {
			if ($maxClose < $linhasCloseTotal) {
				$topClose = $maxClose;
			} else
				$topClose = $linhasCloseTotal;
		} else
			$topClose = $_POST['topClose'];

		if (!isset($_POST['baseClose'])) {
			$baseClose = $minClose+1;
		} else
			$baseClose = $_POST['baseClose'];

		if (isset($_POST['avancaUmClose'])) {
			$minAuxClose = $minClose;
			$minClose += $maxClose;
			if ($minClose >=($linhasCloseTotal)) {
				$minClose = $minAuxClose;
			}
			$topClose += $maxClose;
			if ($topClose >$linhasCloseTotal) {
				$baseClose = $minClose+1;
				$topClose = $linhasCloseTotal;
			} else {
				if ($baseClose < (($topClose - $maxClose))) {
					$baseClose += $maxClose;
				} else {
					$baseClose-=$maxClose;
				}
			}
		} else
		if (isset($_POST['avancaFim'])) {
			$minAuxClose = $minClose;
			$minClose=$linhasCloseTotal - $page;
			if ($minClose <=0) {
				$minClose = $minAuxClose;
			}
			$topClose = $linhasCloseTotal;
			$baseClose = ($linhasCloseTotal - $page)+1;
		} else
		if (isset($_POST['avancaTodosClose'])) {
			$maxClose=$linhasCloseTotal;
			$minClose=0;
			$topClose = $linhasCloseTotal;
			$baseClose = $linhasCloseTotal - $maxClose;
		} else
		if (isset($_POST['voltaUmClose']) ) {
			if (($_POST['maxClose']==$linhasCloseTotal) && ($_POST['minClose']==0)) {$maxClose=$_POST['maxAux']; $minClose=$linhasCloseTotal;}
				 //Está exibindo todos os registros na tela!

			$minClose-=$_POST['maxClose'];
			if ($minClose<0) {$minClose=0;};

			if (($topClose - $baseClose) < $maxClose) {
				$topClose = $baseClose -1;
			} else $topClose-=$maxClose;
			$baseClose-=$maxClose;
		} else
		if (isset($_POST['voltaInicioClose']) ) {
			$minClose=0;
			$maxClose = $page;
			$topClose = $maxClose;
			$baseClose = 1;
		}

		if ($topClose > $linhasCloseTotal) {
			$topClose = $linhasCloseTotal;
		} else
		if ($topClose < $maxClose) {
			$topClose = $maxClose;
		}
		if ($baseClose < 1) {
			$baseClose = 1;
		}


		$queryClose.=" LIMIT ".$minClose.", ".$maxClose."";

		$resultado_ocoClose = mysql_query($queryClose) or die ('ERRO: '.$queryClose);
		$linhasClose = mysql_num_rows($resultado_ocoClose);

		if ($linhasClose == 0) {
			print mensagem("".TRANS('MSG_NO_EXIST_CLOSE_CALLS','Não existem ocorrências concluídas por você no sistema')."!");
		}
		else {
			if ($linhasClose == 1) {
					print "<TR class='header'><td class='line'>Existe ".$linhasClose." ocorrência <font color='red'><b>concluída por você</b></font> no sistema.</TD></TR>";
			} else {
				print "<FORM name='form1' method='POST' action='".$_SERVER['PHP_SELF']."?action=listall'>";
				$minClose++;

				print "<tr>";
				print "<TD witdh='700' align=left><B>Existem <font color=red>".$linhasCloseTotal." ocorrências ".
					"concluídas por você</font>. Mostradas as mais recentes de <font color=red>".$baseClose."</font> a ".
					"<font color=red>".$topClose."</font>. </B></TD>";
				print "<TD colspan='2' width='300' align='right' ><input  type='submit' class='button' name='voltaInicioClose' value='<<' ".
					"title='Visualiza os ".$maxClose." primeiros registros.'> <input  type='submit' class='button'  name='voltaUmClose' value='<' ".
					"title='Visualiza os ".$maxClose." registros anteriores.'> <input  type='submit' class='button'  name='avancaUmClose' value='>' ".
					"title='Visualiza os próximos ".$maxClose." registros.'> <input  type='submit' class='button'  name='avancaFim' value='>>' ".
					"title='Visualiza os últimos ".$maxClose." registros.'> <input  type='submit' class='button'  name='avancaTodosClose' value='Todas' ".
					"title='Visualiza todos os ".$linhasCloseTotal." registros.'></td>";
				print "</tr>";
				$minClose--;
				print "<input type='hidden' value='".$minClose."' name='minClose'>";
				print "<input type='hidden' value='".$maxClose."' name='maxClose'>";
				print "<input type='hidden' value='".$maxAuxClose."' name='maxAux'>";
				print "<input type='hidden' value='".$baseClose."' name='baseClose'>";
				print "<input type='hidden' value='".$topClose."' name='topClose'>";
				print "</form>";
				print "</table>";
			}

			print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='".$cor."'>";
			print "<TR class='header'>";
			print "	<td class='line'>Chamado</TD>";
			print "	<td class='line'>Problema</TD>";
			print "	<td class='line'>Contato<BR>Ramal</TD>";
			print " <td class='line'>Local<br>Descrição</TD>";
			print "	<td class='line'>Status</TD>";
			print "</TR>";
        	}
		$i=0;
		$j=2;
		while ($rowAT = mysql_fetch_array($resultado_ocoClose))
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
			//print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."');\" onMouseOut=\"libera('linhax".$j."');\"  onMouseDown=\"marca('linhax".$j."');\">";
			print "<tr class=".$trClass." id='linhazx".$j."' onMouseOver=\"destaca('linhazx".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhazx".$j."');\"  onMouseDown=\"marca('linhazx".$j."','".$_SESSION['s_colorMarca']."');\">";

			$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']." or dep_filho=".$rowAT['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
			$regSub = mysql_num_rows($execSubCall);
			if ($regSub > 0) {
				#É CHAMADO PAI?
				$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']."";
				$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
				$regSub = mysql_num_rows($execSubCall);
				$comDeps = false;
				while ($rowSubPai = mysql_fetch_array($execSubCall)){
					$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
					$execStatus = mysql_query($sqlStatus) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE STATUS DOS CHAMADOS FILHOS<br>'.$sqlStatus);
					$regStatus = mysql_num_rows($execStatus);
					if ($regStatus > 0) {
						$comDeps = true;
					}
				}
				if ($comDeps) {
					$imgSub = "<img src='".ICONS_PATH."view_tree_red.png' width='16' height='16' title='Chamado com vínculos pendentes'>";
				} else
					$imgSub =  "<img src='".ICONS_PATH."view_tree_green.png' width='16' height='16' title='Chamado com vínculos mas sem pendências'>";
			} else
				$imgSub = "";

			print "<TD  class='line' ".$valign."><a href='mostra_consulta.php?numero=".$rowAT['numero']."'>".$rowAT['numero']."</a>".$imgSub."</TD>";
			print "<TD  class='line' ".$valign.">".$rowAT['problema']."</TD>";
			print "<TD  class='line' ".$valign."><b>".$rowAT['contato']."</b><br>".$rowAT['telefone']."</TD>";
			print "<TD  class='line' ".$valign."><b>".$rowAT['setor']."</b><br>";
			$texto = trim($rowAT['descricao']);
			if (strlen($texto)>200){
				$texto = substr($texto,0,195)." ..... ";
			};
			print $texto;
			print "</TD>";
			print "<TD class='line'  ".$valign.">".$rowAT['chamado_status']."</TD>";
			print "</TR>";
			$i++;
		}
		print "</TABLE>";
		print "<HR>";
	}
	print "</body>";
	print "</html>";

?>