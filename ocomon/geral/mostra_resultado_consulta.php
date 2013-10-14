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

	$cor=TD_COLOR;
	$cor1=TD_COLOR;
	$percLimit = 20; //Tolerância em percentual
	$imgSlaR = 'sla1.png';
	$imgSlaS = 'checked.png';

	$dtS = new dateOpers; //objeto para Solução
	$dtR = new dateOpers; //objeto para Resposta

print "<HTML>";
print "<head>";
?>
<script type="text/javascript">
	function popup(pagina)	{ //Exibe uma janela popUP
      		x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
		return false
     	}

	function popup_alerta(pagina)	{ //Exibe uma janela popUP
      		x = window.open(pagina,'_blank','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false
     	}
</script>
<?
print "</head>";
print "<BODY>";

	$auth = new auth;
	if (isset($_GET['popup'])) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	print "<BR><B>Consulta de Ocorrências</B><BR>";

        if (isset($_POST['submit']))
        {
		$query_ini = $QRY["ocorrencias_full_ini"];
                $query = "";

                if (!empty($_POST['numero_inicial']) )
                        $query.=" and o.numero>='".$_POST['numero_inicial']."' ";

                if (!empty($_POST['numero_final']))
                        $query.=" and o.numero<='".$_POST['numero_final']."' ";

                if ($_POST['problema'] != -1)
                {
                        if (!empty($_POST['problema']) and $_POST['problema'] != -1)
                        {
                                $query.=" and o.problema=".$_POST['problema']." ";
                        }
                }

                if (!empty($_POST['descricao']))
                {
                        $query.=" and o.descricao LIKE '%".$_POST['descricao']."%' ";
                }

		if ($_POST['instituicao'] != -1)
                {
                        if (!empty($_POST['instituicao']) and $_POST['instituicao'] != -1)
                        {
                                $query.=" and o.instituicao=".$_POST['instituicao']." ";
                        }
                }
                if (!empty($_POST['equipamento']))
                {
                        $query.=" and o.equipamento in (".$_POST['equipamento'].") ";
                }

                if (!empty($_POST['sistema']) and $_POST['sistema'] != -1)
                {
                        $query.=" and o.sistema=".$_POST['sistema']." ";
                }
                if (!empty($_POST['contato']))
                {
                        $query.=" and o.contato LIKE '%".$_POST['contato']."%' ";
                }
                if (!empty($_POST['telefone']))
                {
                        $query.=" and o.telefone='".$_POST['telefone']."' ";
                }
                if (!empty($_POST['local']) and $_POST['local'] != -1)
                {
                        $query.=" and o.local=".$_POST['local']." ";
                }
                if (!empty($_POST['operador']) and $_POST['operador'] != -1)
                {
			if(isset($_POST['opAbertura']))
				$query.=" and o.aberto_por = ".$_POST['operador']." "; else
				$query.=" and o.operador = ".$_POST['operador']." ";
                }

                //####################################################################

                if ($_POST['tipo_data']=="abertura")
                {
                        if (!empty($_POST['data_inicial']) )
                        {
                                $data_inicial = str_replace("-","/",$_POST['data_inicial']);
				$data_inicial = substr(datam($data_inicial),0,10);
                                $data_inicial.=" 00:00:01";
                                $query.=" and o.data_abertura>='".$data_inicial."' ";
                        }

                        if (!empty($_POST['data_final']))
                        {
                                $data_final = str_replace("-","/",$_POST['data_final']);
				$data_final = substr(datam($data_final),0,10);
                                $data_final.=" 23:59:59";
                                $query.=" and o.data_abertura<='".$data_final."'";
                        }
                }
                else
                {
                        if (!empty($_POST['data_inicial']) )
                        {
                                $data_inicial = str_replace("-","/",$_POST['data_inicial']);
				$data_inicial = substr(datam($data_inicial),0,10);
                                $data_inicial.=" 00:00:01";
                                $query.=" and o.data_fechamento>='".$data_inicial."' ";
                        }

                        if (!empty($_POST['data_final']))
                        {
                                $data_final = str_replace("-","/",$_POST['data_final']);
				$data_final = substr(datam($data_final),0,10);
                                $data_final.=" 23:59:59";
                                $query.=" and o.data_fechamento<='".$data_final."'";
                        }
                }
                //###########################################################################

                if ($_POST['status'] == "Em aberto")
                {
                        $query.=" and o.status not in (4,12,18)";
                }
                else
                if ($_POST['status'] !=15) {
                        $query.=" and o.status=".$_POST['status']." ";
                }

                if ($_POST['ordem'] == "data")
                        if ($_POST['tipo_data'] == "abertura")
                                $query.="  ORDER BY data_abertura";
                        else
                                $query.="  ORDER BY data_fechamento";
                else
                        $query.=" ORDER BY ".$_POST['ordem']."";

		if (strlen($query)>0) {
			$query_ini.=" WHERE o.numero = o.numero ".$query;
		}

		$resultado = mysql_query($query_ini) or die('ERRO NA TENTATIVA DE RODAR A CONSULTA! '.$query_ini);
		$linhas = mysql_numrows($resultado);
		if ($linhas==0)
		{
			$aviso = "Nenhuma ocorrência localizada.";
			print "<script>alert('Nenhuma ocorrência localizada!'); history.back();</script>";
		}

                print "<table>";
                if ($linhas>1) {
                        print "<TR><td class='line'><B>Foram encontradas ".$linhas." ocorrências.</B></TD></TR>";
		}
                else
                	print "<TR><td class='line'><B>Foi encontrada somente 1 ocorrência.</B></TD></TR>";

		print "</table>";

		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' >" ;

		if (isset($_POST['saida']) && $_POST['saida'] == 1) {

			$valign = " valign='top' ";
			print "<TR class='header'>";
			print "<TD ".$valign.">Número</TD>";
			print "<TD ".$valign.">Problema</TD>";
			print "<TD ".$valign.">Contato<BR>Operador</TD>";
			print "<TD ".$valign.">Local</TD>";
			print "<TD ".$valign.">Data de ".$_POST['tipo_data']."</TD>";
			print "<TD ".$valign.">Status</TD>";
			print "<TD ".$valign.">RESP.</TD>";
			print "<TD ".$valign.">SOLUC.</TD>";
			print "</TR>";

			$i=0;
			$j=2;
			$calcula = false;
			while ($row = mysql_fetch_array($resultado))
			{
				$i++;
				if ($j % 2)
				{
					$color =  BODY_COLOR;
					$trClass= "lin_par";
				}
				else
				{
					$color = "white";
					$trClass = "lin_impar";
				}

				if (($row['status_cod'] == 1)) { $calcula = true;} else $calcula = false;
				$j++;

				$texto = trim( $row['descricao']);
				$limite = 250;
				if (strlen($texto)>$limite){
					$texto = substr($texto,0,($limite-3))."...";
				};

				if ($calcula) {

					// if (array_key_exists($row['cod_area'],$H_horarios)){  //verifica se o código da área possui carga horária definida no arquivo config.inc.php
						// $areaChamado = $row['cod_area']; //Recebe o valor da área de atendimento do chamado
					// } else $areaChamado = 1; //Carga horária default definida no arquivo config.inc.php
					$areaChamado = "";
					$areaChamado=testaArea($areaChamado,$row['area_cod'],$H_horarios);

				//------------------------------------------------
					$dtR->setData1($row['data_abertura']);
					if ($row['data_atendimento'] =="") {
						$dtR->setData2(date("Y-m-d H:i:s")) ;
					} else {
						$dtR->setData2($row['data_atendimento']) ;
					}
					$dtR->tempo_valido($dtR->data1,$dtR->data2,$H_horarios[$areaChamado][0],$H_horarios[$areaChamado][1],$H_horarios[$areaChamado][2],$H_horarios[$areaChamado][3],"H");
					$diffR = $dtR->tValido;
					$diff2R = $dtR->diff["hValido"];
					$segundosR = $dtR->diff["sValido"]; //segundos válidos
					//------------------------------------------------

					$diff = date_diff($row['data_abertura'],date("Y-m-d H:i:s"));
					$sep = explode ("dias",$diff);
					if ($sep[0]>20) { //Se o chamado estiver aberto a mais de 20 dias o tempo é mostrado em dias para não ficar muito pesado.
						$diff = $sep[0]." dias";
						$segundosS = ($sep[0]*86400);
					}  else {
						$dtS->setData1($row['data_abertura']);
						if ($row['data_fechamento'] =="") {
							$dtS->setData2(date("Y-m-d H:i:s")) ;
						} else {
							$dtS->setData2($row['data_fechamento']) ;
						}
						$dtS->tempo_valido($dtS->data1,$dtS->data2,$H_horarios[$areaChamado][0],$H_horarios[$areaChamado][1],$H_horarios[$areaChamado][2],$H_horarios[$areaChamado][3],"H");
						$diffS = $dtS->tValido;
						$diff2S = $dtS->diff["hValido"];
						$segundosS = $dtS->diff["sValido"]; //segundos válidos
					}

					//------------------------------------
					if ($row['data_atendimento'] ==""){//Controle das bolinhas de SLA de Resposta
						if ($segundosR<=($row['sla_resposta_tempo']*60)){
							$imgSlaR = 'sla1.png';
						} else if ($segundosR  <=(($row['sla_resposta_tempo']*60) + (($row['sla_resposta_tempo']*60) *$percLimit/100)) ){
							$imgSlaR = 'sla2.png';
						} else {
							$imgSlaR = 'sla3.png';
						}
					} else
						$imgSlaR = 'checked.png';
					//-----------------------------------------

					$sla_tempo = $row['sla_solucao_tempo'];
					if (($sla_tempo !="") && ($row['data_fechamento']=="")) { //Controle das bolinhas de SLA de solução
						if ($segundosS <= ($row['sla_solucao_tempo']*60)){
							$imgSlaS = 'sla1.png';
						} else if ($segundosS  <=(($row['sla_solucao_tempo']*60) + (($row['sla_solucao_tempo']*60) *$percLimit/100)) ){
							$imgSlaS = 'sla2.png';
						} else
							$imgSlaS = 'sla3.png';
					} else
						$imgSlaS = 'checked.png';
					//-----------------------------------------------------
				} else {
					$imgSlaR = 'checked.png';
					$imgSlaS = 'checked.png';
				}
				print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";

				$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." or dep_filho=".$row['numero']."";
				$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
				$regSub = mysql_num_rows($execSubCall);
				if ($regSub > 0) {
					#É CHAMADO PAI?
					$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']."";
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

				print "<TD ".$valign."><a onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\"><font color='blue'>".$row['numero']."</font></a>".$imgSub."</TD>";
				print "<TD ".$valign.">".$row['problema']."</TD>";
				print "<TD ".$valign."><b>".$row['contato']."</b><br>".$row['nome']."</TD>";
				print "<TD ".$valign."><b>".$row['setor']."</b><br>".$texto."</TD>";
				if ($_POST['tipo_data'] == "abertura")
					print "<TD ".$valign.">".$row['data_abertura']."</TD>"; else
					print "<TD ".$valign.">".$row['data_fechamento']."</TD>";

				print "<TD ".$valign."><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=".$row['numero']."')\">".$row['chamado_status']."</a></TD>";
				print "<TD ".$valign." align='center'><a onClick=\"javascript:popup('sla_popup.php?sla=r')\"><img height='14' width='14' src='../../includes/imgs/imgs/".$imgSlaR."'></a></TD>";
				print "<TD ".$valign." align='center'><a onClick=\"javascript:popup('sla_popup.php?sla=s')\"><img height='14' width='14' src='../../includes/imgs/".$imgSlaS."'></a></TD>";
				print "</TR>";

			} //while

		} // if $_POST['saida'] == 1
		else
		if (isset($_POST['saida']) && $_POST['saida'] == 2) { //Show detailed output
			while ($row = mysql_fetch_array($resultado)) {
				print "<tr><td><b>Número:</b></td><td>".$row['numero']."</td><td><b>Área:</b></td><td>".$row['area']."</td><td><b>Problema:</b></td><td>".$row['problema']."</td></tr>";
				print "<tr><td><b>Descrição:</b></td><td colspan='5'>".nl2br($row['descricao'])."</td></tr>";
				print "<tr><td><b>Unidade:</b></td><td>".$row['unidade']."</td><td><b>Etiqueta:</b></td><td colspan='3'>".$row['etiqueta']."</td></tr>";
				print "<tr><td><b>Local:</b></td><td>".$row['setor']."</td><td><b>Contato:</b></td><td>".$row['contato']."</td><td><b>Ramal:</b></td><td>".$row['telefone']."</td></tr>";
				print "<tr><td><b>Data de abertura:</b></td><td>".$row['data_abertura']."</td><td><b>Data de fechamento:</b></td><td colspan='3'>".$row['data_fechamento']."</td></tr>";
				print "<tr><td colspan='6'><b><u>Assentamentos</u>:</b></td></tr>";

				$sql = "SELECT *  FROM assentamentos a, usuarios u where a.ocorrencia = ".$row['numero']." AND u.user_id = a.responsavel";
				$exec = mysql_query($sql);
				while ($rowA = mysql_fetch_array($exec)){
					print "<tr><td><b>Por ".$rowA['nome']."</b> em ".datam($rowA['data']).":</td><td colspan='5'>".$rowA['assentamento']."</td></tr>";
				}
				print "<tr><td><b>Status Atual:</b></td><td colspan='6'><b>".$row['chamado_status']."</td></tr>";
				print "<tr><td colspan='6'><hr /></td></tr>";
			}
		}
                print "</TABLE>";
        }
print "</body>";
print "</html>";
?>