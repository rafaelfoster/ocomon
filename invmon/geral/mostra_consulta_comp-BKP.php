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

	$hoje = date("d-m-Y H:i:s");
	$hojeDia = date("y-m-d");
	$hoje_termo = date("d/m/Y H:i:s");
	$logo = LOGO_PATH.'/logo_lasalle.gif';


	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);
	if ($_SESSION['s_nivel']==1)
	{
		$administrador = true;
	} else
		$administrador = false;

	if (!isset($_REQUEST['header'])) {
		$header= $TRANS["relat_personalisado"];
	} else
		$header = $_REQUEST['header'];

	$qry = "SELECT conf_page_size AS page FROM config";
	$qry_exec = mysql_query($qry) or die ('É NECESSÁRIO ATUALIZAR A TABELA CONF - VERSAO 1.50');
	$rowConf = mysql_fetch_array($qry_exec);
	$PAGE_SIZE = $rowConf['page'];


	//Verifica se a coluna já está ordenada e seta para ser ordenada em ordem inversa!!
	$az = "";
	$mostra = "";
	$ordenado = "";

	$ICON_ORDER['etiqueta'] = "";
	$ICON_ORDER['instituicao'] = "";
	$ICON_ORDER['tipo'] = "";
	$ICON_ORDER['modelo'] = "";
	$ICON_ORDER['local'] = "";
	$ICON_ORDER['situacao'] = "";

	if (isset($_REQUEST['ordenado'])){
		$ordenado = $_REQUEST['ordenado'];
	} else
		$ICON_ORDER['etiqueta'] = "<img src='../../includes/css/OrderAsc.png' width='16' height='16' align='absmiddle'>";

	if (isset($_REQUEST['coluna']) ) {
	 	if (isset($_REQUEST['ordenado']))
	 	if ($_REQUEST['coluna'] == $_REQUEST['ordenado']) {
			$az = " desc";
			$ordenado = "";
			$mostra = " ".$TRANS["za"];

			$ICON_ORDER['etiqueta'] = "";
			$ICON_ORDER['instituicao'] = "";
			$ICON_ORDER['tipo'] = "";
			$ICON_ORDER['modelo'] = "";
			$ICON_ORDER['local'] = "";
			$ICON_ORDER['situacao'] = "";

			$ICON_ORDER[$_REQUEST['coluna']] = "<img src='../../includes/css/OrderDesc.png' width='16' height='16' align='absmiddle'>";
	 	} else {
			$ordenado = $_REQUEST['coluna'];
			$az = " asc";
			$mostra = " ".$TRANS["az"];

			$ICON_ORDER['etiqueta'] = "";
			$ICON_ORDER['instituicao'] = "";
			$ICON_ORDER['tipo'] = "";
			$ICON_ORDER['modelo'] = "";
			$ICON_ORDER['local'] = "";
			$ICON_ORDER['situacao'] = "";

			$ICON_ORDER[$_REQUEST['coluna']] = "<img src='../../includes/css/OrderAsc.png' width='16' height='16' align='absmiddle'>";
	 	}
	}





	//Para não precisar escrever na tela todos os critérios de ordenação eu defino aqui o que deve aparecer!!
	$traduz = array("etiqueta".$az.""=>$TRANS["col_etiqueta"].$mostra,
		"fab_nome".$az.",modelo".$az."" => $TRANS["col_modelo"].$mostra,
		"fab_nome".$az.",modelo".$az.",etiqueta".$az.""=> $TRANS["col_modelo"].$mostra,
		"modelo".$az.",etiqueta".$az.""=> $TRANS["col_modelo"].$mostra,
		"instituicao".$az.",etiqueta".$az."" =>$TRANS["col_instituicao"].$mostra,
		"equipamento".$az.",modelo".$az."" =>$TRANS["col_tipo"].$mostra,
		"local".$az."" =>$TRANS["col_local"].$mostra,
		"equipamento".$az.",fab_nome".$az.",modelo".$az.",etiqueta".$az."" => $TRANS["col_tipo"].$mostra,
		"equipamento".$az.",fab_nome".$az.",modelo".$az.",local".$az.",etiqueta".$az.""=> $TRANS["col_tipo"].$mostra,
		"equipamento".$az.",modelo".$az.",local".$az.",etiqueta".$az."" => $TRANS["col_tipo"].$mostra,
		"fab_nome".$az.",modelo".$az.",local".$az.",etiqueta".$az.""=> $TRANS["col_fabricante"].$mostra,
		"local".$az.",etiqueta".$az.""=> $TRANS["col_local"].$mostra,
		"local".$az.",equipamento".$az.",fab_nome".$az.",modelo".$az.",etiqueta".$az.""=>$TRANS["col_local"].$mostra,
		"serial".$az.""=> $TRANS["col_sn"].$mostra,
		"nota".$az.""=> $TRANS["col_nf"].$mostra,
		"situac_nome".$az.",etiqueta".$az.""=> $TRANS["col_situacao"].$mostra,
		"situac_nome".$az.""=> $TRANS["col_situacao"].$mostra,
		"tipo,localização".$az."" => $TRANS["col_tipo"].$mostra);

	if (isset($_REQUEST['visualiza'])) {
		if ($_REQUEST['visualiza']!='impressora' && $_REQUEST['visualiza']!='texto' && $_REQUEST['visualiza']!='relatorio' &&
			$_REQUEST['visualiza']!='mantenedora1' && $_REQUEST['visualiza']!='config' && $_REQUEST['visualiza']!='termo' &&
			$_REQUEST['visualiza']!='transito') {

		}
	} else {
		print "<body class='relatorio'>";
	}

	################################################################
	//Código para definir o array de unidades como sendo array de uma única posição

	$comp_inst ="";
	if (isset($_GET['comp_inst'])) {
		$comp_inst = $_GET['comp_inst'];
	} else
	if (isset($_POST['comp_inst'])){
		$comp_inst = $_POST['comp_inst'];
	}

	if (!isset($_POST['saida']) && !empty($comp_inst))
	{
		$saida="";
		if (is_array($comp_inst)) {
			for ($i=0; $i<count($comp_inst); $i++){
				$saida.= "$comp_inst[$i],";
			}
		} else
			$saida=$comp_inst;

		if (strlen($saida)>0) {
			$saida = substr($saida,0,-1);
		}
		$comp_inst = $saida;
	}
	################################################################
	$comp_inv ="";
	if (isset($_GET['comp_inv'])) {
		$comp_inv = $_GET['comp_inv'];
	} else
	if (isset($_POST['comp_inv'])){
		$comp_inv = $_POST['comp_inv'];
	}

	/**
	*@min = Variável referente o primeiro parametro do "limit" na montagem da clausula SLQ
	*@maxAux = Variável auxiliar para a montagem dos botoes de navegacao.
	*@minAux = Variável auxiliar para a montagem dos botões de navegacao.
	*
	*/

	$min = 0;
	$maxAux = 0;
	$minAux = 0;
	//$page = 50;

	$msgInst = "";
	$checked = "";
	$comp_inv_flag = false;
	$comp_sn_flag = false;
	$comp_marca_flag = false;
	$comp_mb_flag = false;
	$comp_proc_flag = false;
	$comp_memo_flag = false;
	$comp_video_flag = false;
	$comp_som_flag = false;
	$comp_rede_flag = false;
	$comp_modem_flag = false;
	$comp_modelohd_flag = false;
	$comp_cdrom_flag = false;
	$comp_dvd_flag = false;
	$comp_grav_flag = false;
	$comp_local_flag = false;
	$comp_reitoria_flag = false;
	$comp_nome_flag = false;
	$comp_fornecedor_flag = false;
	$comp_nf_flag = false;
	$comp_inst_flag = false;
	$comp_tipo_equip_flag = false;
	$comp_fab_flag = false;
	$comp_tipo_imp_flag = false;
	$comp_polegada_flag = false;
	$comp_resolucao_flag = false;
	$comp_ccusto_flag = false;
	$comp_situac_flag = false;
	$comp_data_flag = false;
	$comp_data_compra_flag = false;
	$garantia_flag = false;
	$soft_flag = false;
	$comp_assist_flag = false;
	$comp_memo_notnull = false;
	$comp_memo_null = false;
	$tmpData = array();


	if (isset($_GET['encadeado'])) {
		$checked = "checked";
	}

 	$query = $QRY["full_detail_ini"];	// ../includes/queries/

        if (isset($_REQUEST['negado']))
	{
		$negado = $_REQUEST['negado'];
	} else
		$negado = false;


	if (empty($logico)) {
		$logico = " and ";
	}

	if (empty($sinal)) {
		$sinal = "=";
		$neg = "";
	}

	if (!empty($comp_inv)) {
		$comp_inv_flag = true;
		$query.= "$logico (c.comp_inv in (".$comp_inv.")) ";
	}

        if (isset($_REQUEST['comp_sn']))
	{
		if ($_REQUEST['comp_sn'] != '') {
			$comp_sn_flag = true;
			$comp_sn = strtoupper($_REQUEST['comp_sn']);
			$query.= "$logico (UPPER(c.comp_sn) = '".$comp_sn."') ";
		}
	}  else
		$comp_sn = "";

        if (isset($_REQUEST['comp_marca'])) {
		if (($_REQUEST['comp_marca'] != -1) && ($_REQUEST['comp_marca'] != '')) {
			$comp_marca_flag = true;
			$query.= " ".$logico." (c.comp_marca = ".$_REQUEST['comp_marca'].") ";
			$sinal_marca = "=";
		}
	}

	if (isset($_REQUEST['comp_mb'])) {
		if (($_REQUEST['comp_mb'] != -1) && ($_REQUEST['comp_mb'] != '')) {
			$comp_mb_flag = true;
			$query.= " ".$logico." (c.comp_mb = ".$_REQUEST['comp_mb'].") ";
		}
	}

	if (isset($_REQUEST['comp_proc'])) {
		if (($_REQUEST['comp_proc'] !=-1) && ($_REQUEST['comp_proc'] !='')) {
			$comp_proc_flag = true;
			$query.=" ".$logico." (c.comp_proc = ".$_REQUEST['comp_proc'].") ";
		}
	}


	if (isset($_REQUEST['comp_memo'])) {
		if (($_REQUEST['comp_memo'] != -1) && ($_REQUEST['comp_memo'] !='')) {
			if ($_REQUEST['comp_memo']==-2) {
				$comp_memo_notnull = true;
				$query.=" ".$logico." (c.comp_memo is not null)";
			} else
			if ($_REQUEST['comp_memo']==-3) {
				$comp_memo_null = true;
				$query.=" ".$logico." (c.comp_memo is null)";
			} else {
				$comp_memo_flag = true;
				$query.=" ".$logico." (c.comp_memo = ".$_REQUEST['comp_memo'].") ";
			}
		}
	}


	if (isset($_REQUEST['comp_video'])) {
		if (($_REQUEST['comp_video'] != -1) && ($_REQUEST['comp_video'] !='')) {
			$comp_video_flag = true;
			$query.= " ".$logico." (c.comp_video = ".$_REQUEST['comp_video'].") ";
		}
	}

	if (isset($_REQUEST['comp_som'])) {
		if (($_REQUEST['comp_som'] != -1) && ($_REQUEST['comp_som']!= '')) {
			$comp_som_flag = true;
			$query.= " ".$logico." (c.comp_som = ".$_REQUEST['comp_som'].") ";
		}
	}

	if (isset($_REQUEST['comp_rede'])) {
		if (($_REQUEST['comp_rede'] != -1) && ($_REQUEST['comp_rede'] !='')) {
			$comp_rede_flag = true;
			$query.= " ".$logico." (c.comp_rede = ".$_REQUEST['comp_rede'].") ";
		}
	}

	if (isset($_REQUEST['comp_modem'])) {
		if (($_REQUEST['comp_modem'] != -1) && ($_REQUEST['comp_modem'] !='')) {
			$comp_modem_flag = true;
			if ($_REQUEST['comp_modem'] ==-2) {$query.= "and (c.comp_modem is null or c.comp_modem = 0)";} else
			if ($_REQUEST['comp_modem'] ==-3) {$query.= "and (c.comp_modem is not null and c.comp_modem != 0)";} else
				$query.= " ".$logico." (c.comp_modem = ".$_REQUEST['comp_modem'].") ";
		}
        }

	if (isset($_REQUEST['comp_modelohd'])) {
		if (($_REQUEST['comp_modelohd'] != -1)&& ($_REQUEST['comp_modelohd']!='')) {
			$comp_modelohd_flag = true;
			$query.= " ".$logico." (c.comp_modelohd = ".$_REQUEST['comp_modelohd'].") ";
		}
        }

	if (isset($_REQUEST['comp_cdrom'])) {
		if (($_REQUEST['comp_cdrom'] != -1) && ($_REQUEST['comp_cdrom']!='')) {
			$comp_cdrom_flag = true;
			if ($_REQUEST['comp_cdrom'] ==-2) {$query.= "and (c.comp_cdrom is null or c.comp_cdrom = 0)";} else
			if ($_REQUEST['comp_cdrom'] ==-3) {$query.= "and (c.comp_cdrom is not null and c.comp_cdrom != 0)";} else
				$query.= " ".$logico." (c.comp_cdrom = ".$_REQUEST['comp_cdrom'].") ";
		}
	}

	if (isset($_REQUEST['comp_dvd'])) {
		if (($_REQUEST['comp_dvd'] != -1) && ($_REQUEST['comp_dvd']!='')) {
			$comp_dvd_flag = true;
			$query.= "$logico (c.comp_dvd = ".$_REQUEST['comp_dvd'].") ";
		}
        }

	if (isset($_REQUEST['comp_grav'])) {
		if (($_REQUEST['comp_grav'] != -1) && ($_REQUEST['comp_grav']!='')) {
			$comp_grav_flag = true;
			if ($_REQUEST['comp_grav'] ==-2) {$query.= "and (c.comp_grav is null or c.comp_grav = 0)";} else
			if ($_REQUEST['comp_grav'] ==-3) {$query.= "and (c.comp_grav is not null and c.comp_grav != 0)";} else
				$query.= " ".$logico." (c.comp_grav = ".$_REQUEST['comp_grav'].") ";
		}
	}


	if (isset($_REQUEST['comp_local'])) {
		if (($_REQUEST['comp_local'] != -1) && ($_REQUEST['comp_local']!='')) {
			$comp_local_flag = true;
			if ($negado== "comp_local") {
				$query.= "$logico (c.comp_local <> ".$_REQUEST['comp_local'].") ";
			} else
				$query.= "$logico (c.comp_local ".$sinal." ".$_REQUEST['comp_local'].") ";
		}
        }

	if (isset($_REQUEST['comp_reitoria'])) {// OBS: não existe o campo comp_reitoria, apenas usei esse nome para padronizar!
		if (($_REQUEST['comp_reitoria'] != -1) && ($_REQUEST['comp_reitoria']!='')) {
			$comp_reitoria_flag = true;
			$query.= "$logico (c.comp_reitoria = ".$_REQUEST['comp_reitoria'].") ";
		}
        }


	if (isset($_REQUEST['comp_nome'])) {
		if (!empty($_REQUEST['comp_nome'])) {
			$comp_nome_flag = true;
			$query.= "$logico (c.comp_nome = ".$_REQUEST['comp_nome'].") ";
		}
        }

	if (isset($_REQUEST['comp_fornecedor'])) {
		if (($_REQUEST['comp_fornecedor'] != -1) && ($_REQUEST['comp_fornecedor']!='')) {
			$comp_fornecedor_flag = true;
			$query.= "$logico (c.comp_fornecedor = ".$_REQUEST['comp_fornecedor'].") ";
		}
        }

	if (isset($_REQUEST['comp_nf'])) {
		if (!empty($_REQUEST['comp_nf'])) {
			$comp_nf_flag = true;
			$query.= "$logico (c.comp_nf = ".$_REQUEST['comp_nf'].") ";
		}
        }

        if (($comp_inst!= -1) and ($comp_inst!='')) {
		$comp_inst_flag = true;
		if ($negado== "comp_inst") {
			$query.= "$logico (c.comp_inst not in (".$comp_inst."))";
		} else
			$query.= "$logico (c.comp_inst in (".$comp_inst."))";
			if ($comp_inst ==1) {$logo = LOGO_PATH.'/logo_unilasalle.gif';} else
			if ($comp_inst ==2) {$logo = LOGO_PATH.'/logo_colegio.gif';}
	}


	if (isset($_REQUEST['comp_tipo_equip'])) {
		if (($_REQUEST['comp_tipo_equip'] != -1) && ($_REQUEST['comp_tipo_equip']!='')) {
			$comp_tipo_equip_flag = true;
			if ($negado== "comp_tipo_equip") {
				$query.= "$logico (c.comp_tipo_equip <> ".$_REQUEST['comp_tipo_equip'].") ";
			} else
				$query.= "$logico (c.comp_tipo_equip ".$sinal." ".$_REQUEST['comp_tipo_equip'].") ";
		}
        }

	if (isset($_REQUEST['comp_fab'])) {
		if (($_REQUEST['comp_fab'] != -1) && ($_REQUEST['comp_fab']!='')) {
			$comp_fab_flag = true;
			$query.= "$logico (c.comp_fab = ".$_REQUEST['comp_fab'].") ";
		}
        }

	if (isset($_REQUEST['comp_tipo_imp'])) {
		if (($_REQUEST['comp_tipo_imp'] != -1) && ($_REQUEST['comp_tipo_imp']!='')) {
			$comp_tipo_imp_flag = true;
			$query.= "$logico (c.comp_tipo_imp = ".$_REQUEST['comp_tipo_imp'].") ";
		}
        }

	if (isset($_REQUEST['comp_polegada'])) {
		if (($_REQUEST['comp_polegada'] != -1) && ($_REQUEST['comp_polegada']!='')) {
			$comp_polegada_flag = true;
			$query.= "$logico (c.comp_polegada = ".$_REQUEST['comp_polegada'].") ";
		}
        }

	if (isset($_REQUEST['comp_resolucao'])) {
		if (($_REQUEST['comp_resolucao'] != -1) && ($_REQUEST['comp_resolucao']!='')) {
			$comp_resolucao_flag = true;
			$query.= "$logico (c.comp_resolucao = ".$_REQUEST['comp_resolucao'].") ";
		}
        }
	if (isset($_REQUEST['comp_ccusto'])) {
		if (($_REQUEST['comp_ccusto'] != -1) && ($_REQUEST['comp_ccusto']!='')) {
			$comp_ccusto_flag = true;
			$query.= "$logico (c.comp_ccusto = ".$_REQUEST['comp_ccusto'].") ";
		}
        }

	if (isset($_REQUEST['comp_situac'])) {
		if (($_REQUEST['comp_situac'] != -1) && ($_REQUEST['comp_situac']!='')) {
			$comp_situac_flag = true;
			if ($negado== "comp_situac") {
				$query.= "$logico (c.comp_situac <> ".$_REQUEST['comp_situac'].") ";
			} else
				$query.= "$logico (c.comp_situac ".$sinal." ".$_REQUEST['comp_situac'].") ";
		}
        }

	if (isset($_REQUEST['comp_data'])) { //CADASTRO
		if ( ($_REQUEST['comp_data']!='')) {
			$comp_data_flag = true;
			$comp_data = $_REQUEST['comp_data'];

/*			if (strpos($_REQUEST['comp_data'],"-")) {
				$comp_data = substr(datam2($_REQUEST['comp_data']),0,10);
			}*/
			if (strpos($_REQUEST['comp_data']," ")) {
				$tmpData = explode(" ", $_REQUEST['comp_data']);
				$comp_data = $tmpData[0];
			}

			//$comp_data = substr(datam($comp_data),0,10);

			//$query.= "$logico (c.comp_data like ('".$comp_data."%'))";
			$query.= "$logico (c.comp_data like ('".$comp_data."%'))";
		}
        }

	if (isset($_REQUEST['comp_data_compra'])) { //CADASTRO
		if ( ($_REQUEST['comp_data_compra']!='')) {
			$comp_data_compra_flag = true;
			$comp_data_compra = $_REQUEST['comp_data_compra'];

			//$comp_data_compra = substr(datam($comp_data_compra),0,10);
			if (strpos($_REQUEST['comp_data_compra']," ")) {
				$tmpData = explode(" ", $_REQUEST['comp_data_compra']);
				$comp_data_compra = $tmpData[0];
			}


			$query.= "$logico (c.comp_data_compra like ('".$comp_data_compra."%'))";
		}
        }

	if (isset($_REQUEST['garantia'])) {
		if (($_REQUEST['garantia'] == 1) && ($_REQUEST['garantia']==2)) {
			$garantia_flag = true;
			if ($_REQUEST['garantia'] == 1){
				$consulta= $TRANS["crit_exib_em_garantia"];
				$query.="and (date_add(c.comp_data_compra, interval tmp.tempo_meses month) >=now())";
			} else {
				$consulta= $TRANS["crit_exib_fora_garantia"];
				$query.="and (date_add(c.comp_data_compra, interval tmp.tempo_meses month) <now() or comp_garant_meses is null)";
			}
		}
        }

	if (isset($_REQUEST['software'])) {
		if (($_REQUEST['software'] != -1) && ($_REQUEST['software']!='')) {
			$soft_flag = true;
			$query.= "$logico (soft.soft_cod = ".$_REQUEST['software'].") ";
		}
        }

	if (isset($_REQUEST['comp_assist'])) {
		if (($_REQUEST['comp_assist'] != -1) && ($_REQUEST['comp_assist']!='')) {
			$comp_assist_flag = true;
			if ($_REQUEST['comp_assist'] == -2) {
				$query.= "and (c.comp_assist is null)";
			} else
				$query.= "and (c.comp_assist ".$sinal." ".$_REQUEST['comp_assist'].")";
		}
        }

        //$query.=")";

		if (!isset($_REQUEST['ordena'])) {
			$ordena = "etiqueta";
		} else {
			$aux = explode(",",$_REQUEST['ordena']);
			$ordena= "";
			for ($i=0;$i<count($aux);$i++){
				$ordena.=$aux[$i].$az.",";
			}
			$ordena = substr($ordena,0,-1);
		}
		$query.= $QRY["full_detail_fim"];
		$query.= "  order by ".$ordena."";

		$traduzOrdena = strtr("$ordena", $traduz);


##################################################################################
	$qtdTotal = $query;
	$resultadoTotal = mysql_query($qtdTotal) or die ('ERRO NA SEGUINTE QUERY:<br>'.$qtdTotal);
	$linhasTotal = mysql_num_rows($resultadoTotal); //Aqui armazedo a quantidade total de registros
##################################################################################

		if ( (!isset($_REQUEST['visualiza'])) || ($_REQUEST['visualiza']=='tela')) { //condição para montar na tela os botões de navegação

			/*------------------------------------------------------------------------------
			@$min = PRIMEIRO REGISTRO A SER EXIBIDO
			@$max = QUANTIDADE DE REGISTROS POR PÁGINA
			@$top = NÚMERO DO ÚLTIMO REGISTRO EXIBIDO DA PÁGINA
			@$base = NÚMERO DO PRIMEIRO REGISTRO EXIBIDO DA PÁGINA
			--------------------------------------------------------------------------------*/

// 			$min = 0;
// 			$maxAux = 0;
// 			$minAux = 0;
// 			$page = 50;

			if (!isset($_POST['min']))  {
				$min =0;
			} else $min = $_POST['min'];

			if (!isset($_POST['max']))  {
				$max =$PAGE_SIZE;
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
				$min=$linhasTotal - $PAGE_SIZE;
				if ($min <=0) {
					$min = $minAux;
				}
				$top = $linhasTotal;
				$base = ($linhasTotal - $PAGE_SIZE)+1;
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
				//$max=$_POST['maxAux'];
				$max = $PAGE_SIZE;
				$top = $max;
				$base = 1;
			}

			$query.=" LIMIT ".$min.", ".$max."";

			if ($top > $linhasTotal) {
				$top = $linhasTotal;
			} else
			if ($top < $max) {
				$top = $max;
			}
			if ($base < 1) {
				$base = 1;
			}
		}


	$resultado = mysql_query($query) or die ('ERRO NA SEGUINTE QUERY: <BR>'.$query);
	$resultadoAux = mysql_query($query);
        $linhas = mysql_num_rows($resultado);

        $row = mysql_fetch_array($resultadoAux);

/*	if (($linhasTotal==$linhas) and ($avanca!=$TRANS["bt_todos"])){//Desabilita os botões de navegação!!
		$desabilita = "disabled";
		$botaoCor = "#666666";
	} else {
		$desabilita = "";
		$botaoCor = "#0000CC";
	}*/
	######################################################

		//Titulo da consulta que retorna o critério de pesquisa.
		//$texto ="com: ";
		$texto ="";
		$tam = (strlen($texto));
		$param ="&";
		$tamParam = (strlen($param));

		if ($comp_tipo_equip_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_tipo"]."</b> = ".$row['equipamento']."]"; //Escreve o critério de pesquisa
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_tipo_equip=".$_REQUEST['comp_tipo_equip'].""; 	//Monta a lista de parâmetros para a consulta
		};
		if ($comp_tipo_imp_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_impressora"]."</b> = ".$row['impressora']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_tipo_imp=".$_REQUEST['comp_tipo_imp']."";
		};
		if ($comp_polegada_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_monitor"]."</b> = ".$row['polegada_nome']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_polegada=".$_REQUEST['comp_polegada']."";
		};

		if ($comp_resolucao_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_scanner"]."</b> = ".$row['resol_nome']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_resolucao=".$_REQUEST['comp_resolucao']."";
		};

		if ($comp_inv_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_etiqueta"]."</b> = ".$comp_inv."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_inv=".$comp_inv."";
		};

		if ($comp_sn_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_sn"]."</b> = ".$row['serial']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_sn=".$_REQUEST['comp_sn']."";
		};

		if ($comp_fab_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_fab"]."</b> = ".$row['fab_nome']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_fab=".$_REQUEST['comp_fab']."";
		};


		if ($comp_marca_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_modelo"]."</b> = ".$row['modelo']."]"; //$sinal
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_marca=".$_REQUEST['comp_marca']."";
		};

		if ($comp_mb_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_mb"]."</b> = ".$row['fabricante_mb']." ".$row['mb']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_mb=".$_REQUEST['comp_mb']."";
		};
		if ($comp_proc_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_proc"]."</b> = ".$row['processador']." ".$row['clock']." ".$row['proc_sufixo']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_proc=".$_REQUEST['comp_proc']."";
		};
	  	if ($comp_memo_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_memo"]."</b> = ".$row['memoria']."".$row['memo_sufixo']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_memo=".$_REQUEST['comp_memo']."";
		};
	  	if ($comp_memo_notnull) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_memo"]."</b> = ".$TRANS["crit_exib_nao_nula"]."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_memo=".$_REQUEST['comp_memo']."";
		};
	  	if ($comp_memo_null) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_memo"]."</b> = ".$TRANS["crit_exib_nula"]."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_memo=".$_REQUEST['comp_memo']."";
		};

		if ($comp_video_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_video"]."</b> = ".$row['fabricante_video']." ".$row['video']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_video=".$_REQUEST['comp_video']."";
		};
		if ($comp_som_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_som"]."</b> = ".$row['fabricante_som']." ".$row['som']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_som=".$_REQUEST['comp_som']."";
		};
		if ($comp_cdrom_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if ($_REQUEST['comp_cdrom']==-2) {$texto.="[<b>".$TRANS["cx_cdrom"]."</b> = ".$TRANS["crit_exib_nenhum"]."]";} else
			if ($_REQUEST['comp_cdrom']==-3) {$texto.="[<b>".$TRANS["cx_cdrom"]."</b> = ".$TRANS["crit_exib_qualquer"]."]";} else
			$texto.="[<b>".$TRANS["cx_cdrom"]."</b> = ".$row['fabricante_cdrom']." ".$row['cdrom']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_cdrom=".$_REQUEST['comp_cdrom']."";
		};

		if ($comp_grav_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if ($_REQUEST['comp_grav']==-2) {$texto.="[<b>".$TRANS["cx_grav"]."</b> = ".$TRANS["crit_exib_nenhum"]."]";} else
			if ($_REQUEST['comp_grav']==-3) {$texto.="[<b>".$TRANS["cx_grav"]."</b> = ".$TRANS["crit_exib_qualquer"]."]";} else
			$texto.="[<b>".$TRANS["cx_grav"]."</b> = ".$row['fabricante_gravador']." ".$row['gravador']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_grav=".$_REQUEST['comp_grav']."";
		};

		if ($comp_dvd_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if ($_REQUEST['comp_dvd']==-2) {$texto.="[<b>".$TRANS["cx_dvd"]."</b> = ".$TRANS["crit_exib_nenhum"]."]";} else
			if ($_REQUEST['comp_dvd']==-3) {$texto.="[<b>".$TRANS["cx_dvd"]."</b> = ".$TRANS["crit_exib_qualquer"]."]";} else
			$texto.="[<b>".$TRANS["cx_dvd"]."</b> = ".$row['fabricante_dvd']." ".$row['dvd']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_dvd=".$_REQUEST['comp_dvd']."";
		};


		if ($comp_modem_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if ($_REQUEST['comp_modem']==-2) {$texto.="[<b>".$TRANS["cx_modem"]."</b> = ".$TRANS["crit_exib_nenhum"]."]";} else
			if ($_REQUEST['comp_modem']==-3) {$texto.="[<b>".$TRANS["cx_modem"]."</b> = ".$TRANS["crit_exib_qualquer"]."]";} else
			$texto.="[<b>".$TRANS["cx_modem"]."</b> = ".$row['fabricante_modem']." ".$row['modem']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_modem=".$_REQUEST['comp_modem']."";
		};

		if ($comp_modelohd_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_hd"]."</b> = ".$row['fabricante_hd']." ".$row['hd_capacidade']."".$row['hd_sufixo']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_modelohd=".$_REQUEST['comp_modelohd']."";
		};
		if ($comp_rede_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_rede"]."</b> = ".$row['rede_fabricante']." ".$row['rede']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_rede=".$_REQUEST['comp_rede']."";
		};
		if ($comp_local_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_local"]."</b> = ".$row['local']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_local=".$_REQUEST['comp_local']."";
		};
		if ($comp_reitoria_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_reitoria"]."</b> = ".$row['reitoria']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_reitoria=".$_REQUEST['comp_reitoria']."";
		};

		if ($comp_fornecedor_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_fornecedor"]."</b> = ".$row['fornecedor_nome']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_fornecedor=".$_REQUEST['comp_fornecedor']."";
		};
		if ($comp_nf_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_nf"]."</b> = ".$row['nota']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_nf=".$_REQUEST['comp_nf']."";
		}


		if (($comp_ccusto_flag)|| ((isset($_REQUEST['visualiza']) && $_REQUEST['visualiza']=='termo'))) {
			if (strlen($texto) > $tam) $texto.= ", ";

			$CC =  $row['ccusto'];
			if ($CC =="") $CC = -1;
			$query2 = "select * from ".DB_CCUSTO.".".TB_CCUSTO." where ".CCUSTO_ID."= $CC "; //
			$resultado2 = mysql_query($query2);
			$rowCC= mysql_fetch_array($resultado2);
			$centroCusto = $rowCC[CCUSTO_DESC];
			$custoNum = $rowCC[CCUSTO_COD];
			$texto.="[<b>".$TRANS["cx_cc"]."</b> = ".$centroCusto."]";

			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_ccusto = ".$_REQUEST['comp_ccusto']."";
		}

		if ($comp_inst_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";

			$sqlA ="select inst_nome as inst from instituicao where inst_cod in (".$comp_inst.")";
			$resultadoA = mysql_query($sqlA);
			//$rowA = mysql_fetch_array($resultadoA);
  			//if (($resultadoA = mysql_query($sqlA)) && (mysql_num_rows($resultadoA) > 0) ) {
				while ($rowA = mysql_fetch_array($resultadoA)) {
					$msgInst.= $rowA['inst'].', ';
				}
				$msgInst = substr($msgInst,0,-2);
			//}

			$texto.="[<b>".$TRANS["cx_inst"]."</b> = ".$msgInst."]";
			if (strlen($param) > $tamParam) $param.= "&";

			$p_temp = explode(",",$comp_inst);

			for ($i=0;$i<count($p_temp);$i++){
				$param.="comp_inst%5B%5D=".$p_temp[$i]."&";  //%5B%5D  Caracteres especiais do HTML para entender arrays!!
			}
			$param = substr($param,0,-1);
			//$param.= "comp_inst in ($comp_inst)";
		}

		if ($comp_situac_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_situacao"]."</b> = ".$row['situac_nome']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_situac=".$_REQUEST['comp_situac']."";
		};
		if ($comp_data_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_data_cadastro"]."</b> = ".$comp_data."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_data=".$_REQUEST['comp_data']."";
		};
		if ($comp_data_compra_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_data_compra"]."</b> = ".$comp_data_compra."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_data_compra=".$_REQUEST['comp_data_compra']."";
		};

		if ($garantia_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["crit_exib_em_garantia"]."</b> = ".$consulta."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "garantia=".$_REQUEST['garantia']."";
		};

		if ($soft_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".$TRANS["cx_software"]."</b> = ".$row['software']." ".$row['versao']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "software=".$_REQUEST['software']."";
		};

		if ($comp_assist_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";

			if ($comp_assist==-2) {$texto.="[<b>".$TRANS["cx_assistencia"]."</b> = ".$TRANS["crit_exib_nao_definida"]."]";} else
				$texto.="[<b>".$TRANS["cx_assistencia"]."</b> = ".$row['assistencia']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_assist=".$_REQUEST['comp_assist']."";
		};


		if (strlen($texto)==$tam) {$texto.="[<b>".$TRANS["tipo_geral"]."</b> = Todos]";}; //Se nenhum campo foi selecionado para a consulta então todos os equipamentos são listados!!

 		$lim = (strlen($texto)-7);
		$texto2 = (substr($texto,6,$lim));

		#########################################################
		geraLog(LOG_PATH.'invmon.txt',date("d-m-Y H:i:s"),$_SESSION['s_usuario'],$_SERVER['PHP_SELF'],$texto);
		#########################################################

	if ($linhas == 0)
	{
		//print $query."<br><br><a class='likebutton' onClick=\"javascript:history.back();\">Voltar</a>"; exit;

		print "<script>mensagem('".$TRANS["alerta_nao_encontrado"]."')</script>";
		print "<script>history.back()</script>";
		exit;
	} else
	if ($linhas>1){
		if (isset($_REQUEST['visualiza']) && $_REQUEST['visualiza'] =='impressora') {
			print cabecalho($logo,'<a href=abertura.php>Ocomon</a>',$hoje,$header);
			print "<tr><TD bgcolor='".TD_COLOR."'><i>".$TRANS["crit_exib"].": ".$texto.".</i></td></tr><br><br>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B>".$TRANS["encontrado"]." <font color='red'>".$linhas."</font> ".$TRANS["reg_ord"]." <u>".$traduzOrdena."</u>: </B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='consulta_comp.php'>[ ".$TRANS["novo_relat"]." ]</a>.</B></TD></TR>";
		} else

		if (isset($_REQUEST['visualiza']) && $_REQUEST['visualiza'] =='termo') {
			//print "<BODY bgcolor= 'white'>";
			print "<p align='center'><img src='".LOGO_PATH."/unilasalle-peb.gif'></p>";
			print "<br>";
			print "<p class='centro'><B>CENTRO DE INFORMÁTICA - CINFO / SUPORTE AO USUÁRIO - HELPDESK</B></p>";
			print "<p class='centro'><B>TERMO DE COMPROMISSO PARA HARDWARE</B></p>";

			print "<p class='parag'>Por esse termo acuso o recebimento do(s) equipamento(s) abaixo especificado(s), compromentendo-me
				a mantê-lo(s) sob a minha guarda e responsabilidade, dele(s) fazendo uso adequado, de acordo com a resolução 003/2002 que
				define políticas, normas e procedimentos que disciplinam a utilização de equipamentos, recursos e serviços de
				informática do Unilasalle.</p>";
			print "<br>";
			print "<TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='80%' bgcolor= 'black'>";
			$color = "#A3A352";
			print "<TR><TD bgcolor='".$color."'><b>Etiqueta</TD>".
				"<TD bgcolor='".$color."'><b>Unidade</TD>".
				"<TD bgcolor='".$color."'><b>Tipo</TD>".
				"<TD bgcolor='".$color."'><b>Fabricante</TD>".
				"<TD bgcolor='".$color."'><b>Modelo</TD>".
				"<TD bgcolor='".$color."'><b>N.º Série</TD>".
				"<TD bgcolor='".$color."'><b>Nota Fiscal</TD>".
				"</tr>";
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='transito') {
			print "<p align='center'><img src='".LOGO_PATH."/unilasalle-peb.gif'></p>";
			print "<br>";
			print "<p class='centro'><B>CENTRO DE INFORMÁTICA - CINFO / SUPORTE AO USUÁRIO - HELPDESK</B></p>";
			print "<p class='centro'><B>FORMULÁRIO DE TRÂNSITO DE EQUIPAMENTOS DE INFORMÁTICA</B></p>";
			print "<p class='parag'>Informo que o(s) equipamento(s) abaixo descriminado(s) está(ão)
					autorizado(s) pelo setor responsável a serem transportados para fora da Unidade
					pelo portador citado.</p>";
			print "<br>";
			print "<TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='80%' bgcolor= 'black'>";
			$color = "#A3A352";
			print "<TR><TD bgcolor='".$color."'><b>Etiqueta</TD>".
				"<TD bgcolor='".$color."'><b>Unidade</TD>".
				"<TD bgcolor='".$color."'><b>Tipo</TD>".
				"<TD bgcolor='".$color."'><b>Fabricante</TD>".
				"<TD bgcolor='".$color."'><b>Modelo</TD>".
				"<TD bgcolor='".$color."'><b>N.º Série</TD>".
				"</tr>";
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='config') {
			print cabecalho($logo,'<a href=abertura.php>OcoMon</a>',$hoje,$header);
			print "<tr><TD bgcolor='".TD_COLOR."'><i>".$TRANS["crit_exib"].": ".$texto.".</i></td></tr><br><br>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B>".$TRANS["encontrado"]." <font color='red'>".$linhas."</font> ".$TRANS["reg_ord"]." <u>".$traduzOrdena."</u>: </B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href=consulta_comp.php>[ ".$TRANS["novo_relat"]." ]</a>.</B></TD></TR>";

		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='relatorio') {
			print cabecalho($logo,'<a href=abertura.php>OcoMon</a>',$hoje,$header);
			print "<tr><TD bgcolor='".TD_COLOR."'><i>".$TRANS["crit_exib"].": ".$texto.".</i></td></tr><br><br>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B>".$TRANS["encontrado"]." <font color='red'>".$linhas."</font> ".$TRANS["reg_ord"]." <u>".$traduzOrdena."</u>: </B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='consulta_comp.php'>[ ".$TRANS["novo_relat"]." ]</a>.</B></TD></TR>";
			print "<TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='100%' bgcolor='white'>";
			$color = "#A3A352";
			print "<TR><TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=etiqueta&visualiza=relatorio".$param."&header=".$header."'>".$TRANS["col_etiqueta"]."</a></TD>
				<TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=instituicao,equipamento,fab_nome,modelo,etiqueta&visualiza=relatorio".$param."&header=".$header."'>".$TRANS["col_instituicao"]."</a></TD>
				<TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=equipamento,fab_nome,modelo,etiqueta&visualiza=relatorio".$param."&header=".$header."'>".$TRANS["col_tipo"]."</a></TD>
				<TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=fab_nome,modelo,etiqueta&visualiza=relatorio".$param."&header=".$header."'>".$TRANS["col_modelo"]."</a></TD>
				<TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=serial&visualiza=relatorio".$param."&header=".$header."'>".$TRANS["col_sn"]."</a></TD>
				<TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=nota&visualiza=relatorio".$param."&header=".$header."'>".$TRANS["col_nf"]."</a></TD>
				<TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=situac_nome,etiqueta&visualiza=relatorio".$param."&header=".$header."'>".$TRANS["col_situacao"]."</a></TD>
				<TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=local,equipamento,fab_nome,modelo,etiqueta&visualiza=relatorio".$param."&header=".$header."'>".$TRANS["col_local"]."</a></TD>
				</tr>";
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='mantenedora1') {
			print cabecalho($logo,'<a href=abertura.php>OcoMon</a>',$hoje,"RELATÓRIO DE INVENTÁRIO FÍSICO DE EQUIPAMENTOS DE INFORMÁTICA<br>".$texto."");
			print "<br><br><TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='100%' bgcolor= white>";
			$color = "#A3A352";
			print "<TR><TD bgcolor='".$color."'><b>Etiqueta</TD>".
				"<TD bgcolor='".$color."'><b>Tipo</TD>".
				"<TD bgcolor='".$color."'><b>Fabricante</TD>".
				"<TD bgcolor='".$color."'><b>Modelo</TD>".
				"<TD bgcolor='".$color."'><b>Nº de Série</TD>".
				"<TD bgcolor='".$color."'><b>NF</TD>".
				"<TD bgcolor='".$color."'><b>Situação</TD>".
				"<TD bgcolor='".$color."'><b>Localização</TD>".
				"<TD bgcolor='".$color."'><b>Centro de Custo</TD>".
				"</tr>";
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] == 'texto') {
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='abertura.php'>OcoMon</a> - ".$TRANS["head_relat_txt"].".\t</B></TD></TR><br>";
			print "<tr><TD bgcolor='".TD_COLOR."'><i>".$TRANS["crit_exib"].": ".$texto.".</i></td></tr><br><br>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B>".$TRANS["encontrado"]." <font color=red>".$linhas."</font> ".$TRANS["reg_ord"]." <u>".$TraduzOrdena."</u>: </B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='consulta_comp.php'>[ ".$TRANS["novo_relat"]." ]</a>.</B></TD></TR>";
		} else {  //Visualização normal na tela do sistema!!
			print "<table border='0' cellspacing='1' width='100%'>";
			print "<tr><TD with='70%' align='left'><i>".$TRANS["crit_exib"].": ".$texto.".</i></td>
					<td width='30%' align='left'>
					<form name='checagem' method='post' action=''>
						<input  type='checkbox' class='radio' name='encadeia' id='idEncadeia' value='ok' ".$checked."><a title='".$TRANS["hint_pipe"]."!'>".$TRANS["ck_pipe"]."</a>";
				print "<input  type='checkbox' class='radio' name='ckpopup' value='ok'><a title='Consulta os detalhes do equipamento em uma janela popup!'>popup</a>";
				print "<input  type='checkbox' class='radio' name='negada' value='ok'><a title='".$TRANS["hint_not"]."!'>".$TRANS["ck_not"]."</a>";
			print "	</form></td></tr><br>";

			print "</table>";


			print "<table border='0' cellspacing='1' summary=''>";


			print "<FORM method='post' action='".$_SERVER['PHP_SELF']."'>";
			print "<TR>";
			$min++;
			$stilo = "style='{height:17px; width:30px; background-color:#DDDCC5; color:#5E515B; font-size:11px;}'"; //Estilo dos botões de navegação
			$stilo2 = "style='{height:17px; width:50px; background-color:#DDDCC5; color:#5E515B;font-size:11px;}'";
			//if ($avanca==$TRANS["bt_todos"]) {$top=$linhasTotal;} else$top=$min+($max-1);
			print "<TD width='750' align='left' ><B>".$TRANS["encontrado"]." <font color='red'>".$linhasTotal."</font> ".$TRANS["reg_ord"]." <u>".$traduzOrdena."</u>. ".$TRANS["mostrado"]." <font color='red'>".$min."</font> ".$TRANS['ate']." <font color='red'>".$top."</font>.</B></TD>";
			//print "<TD width='50' align='left' ></td>";


				print "<TD width='30%' align='right'><input  type='submit' class='button' name='voltaInicio' value='<<' ".
					"title='Visualiza os ".$max." primeiros registros.'> <input  type='submit' class='button'  name='voltaUm' value='<' ".
					"title='Visualiza os ".$max." registros anteriores.'> <input  type='submit' class='button'  name='avancaUm' value='>' ".
					"title='Visualiza os próximos ".$max." registros.'> <input  type='submit' class='button'  name='avancaFim' value='>>' ".
					"title='Visualiza os últimos ".$max." registros.'> <input  type='submit' class='button'  name='avancaTodos' value='Todas' ".
					"title='Visualiza todos os ".$linhasTotal." registros.'></td>";



// 			print "<TD width='224' align='left'>
// 					<input type='submit' style=\"{background-color:#EAE6D0; width:30px; height:20px; background-image: url('".ICONS_PATH."2leftarrow.png'); background-repeat:no-repeat; background-position:center center;}\" name='voltaInicio'  value='' title='".$TRANS["hint_bt_volta"]." $max ".$TRANS["hint_bt_reg_prim"].".' $desabilita>
// 					<input type='submit' style=\"{background-color:#EAE6D0; width:30px; height:20px; background-image: url('".ICONS_PATH."1leftarrow.png'); background-repeat:no-repeat; background-position:center center;}\" name='voltaUm' value='' title='".$TRANS["hint_bt_volta"]." $max ".$TRANS["hint_bt_reg_ant"].".' $desabilita>
// 					<input type='submit' style=\"{background-color:#EAE6D0; width:30px; height:20px; background-image: url('".ICONS_PATH."1rightarrow.png'); background-repeat:no-repeat; background-position:center center;}\" name='avancaUm' value='' title='".$TRANS["hint_bt_avanca"]." $max ".$TRANS["hint_bt_regs"].".' $desabilita>
// 					<input type='submit' style=\"{background-color:#EAE6D0; width:30px; height:20px; background-image: url('".ICONS_PATH."2rightarrow.png'); background-repeat:no-repeat; background-position:center center;}\" name='avancaFim' value='' title='".$TRANS["hint_bt_ultimos"]." $max ".$TRANS["hint_bt_regs"].".' $desabilita>";
// 			print "<input type='submit' $stilo2 name='avancaTodos' value='".$TRANS["bt_todos"]."' title='".$TRANS["hint_bt_todos"].". $linhasTotal ".$TRANS["hint_bt_regs"].".' $desabilita></td>";

			print "</tr>";
			$min--;

// 			print "<input type='hidden' value='".$min."' name='min'>";
// 			print "<input type='hidden' value='".$max."' name='max'>";
// 			print "<input type='hidden' value='".$maxAux."' name='maxAux'>";

			print "<input type='hidden' value='".$min."' name='min'>";
			print "<input type='hidden' value='".$max."' name='max'>";
			print "<input type='hidden' value='".$maxAux."' name='maxAux'>";
			print "<input type='hidden' value='".$base."' name='top'>";
			print "<input type='hidden' value='".$top."' name='top'>";
			print "<input type='hidden' value='".$ordena."' name='ordena'>";
			print "<input type='hidden' value='".$comp_inv."' name='comp_inv'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_sn'])."' name='comp_sn'>";
			if (isset($comp_sn))
				print "<input type='hidden' value='".$comp_sn."' name='comp_sn'>";
			if (isset($_REQUEST['comp_marca']))
				print "<input type='hidden' value='".$_REQUEST['comp_marca']."' name='comp_marca'>";
			if (isset($_REQUEST['comp_mb']))
				print "<input type='hidden' value='".$_REQUEST['comp_mb']."' name='comp_mb'>";
			if (isset($_REQUEST['comp_proc']))
				print "<input type='hidden' value='".$_REQUEST['comp_proc']."' name='comp_proc'>";
			if (isset($_REQUEST['comp_memo']))
				print "<input type='hidden' value='".$_REQUEST['comp_memo']."' name='comp_memo'>";
			if (isset($_REQUEST['comp_video']))
				print "<input type='hidden' value='".$_REQUEST['comp_video']."' name='comp_video'>";
			if (isset($_REQUEST['comp_som']))
				print "<input type='hidden' value='".$_REQUEST['comp_som']."' name='comp_som'>";
			if (isset($_REQUEST['comp_rede']))
				print "<input type='hidden' value='".$_REQUEST['comp_rede']."' name='comp_rede'>";
			if (isset($_REQUEST['comp_modem']))
				print "<input type='hidden' value='".$_REQUEST['comp_modem']."' name='comp_modem'>";
			if (isset($_REQUEST['comp_modelohd']))
				print "<input type='hidden' value='".$_REQUEST['comp_modelohd']."' name='comp_modelohd'>";

			if (isset($_REQUEST['comp_cdrom']))
				print "<input type='hidden' value='".$_REQUEST['comp_cdrom']."' name='comp_cdrom'>";
			if (isset($_REQUEST['comp_dvd']))
				print "<input type='hidden' value='".$_REQUEST['comp_dvd']."' name='comp_dvd'>";
			if (isset($_REQUEST['comp_grav']))
				print "<input type='hidden' value='".$_REQUEST['comp_grav']."' name='comp_grav'>";
			if (isset($_REQUEST['comp_local']))
				print "<input type='hidden' value='".$_REQUEST['comp_local']."' name='comp_local'>";
			if (isset($_REQUEST['comp_nome']))
				print "<input type='hidden' value='".$_REQUEST['comp_nome']."' name='comp_nome'>";
			if (isset($_REQUEST['comp_fornecedor']))
				print "<input type='hidden' value='".$_REQUEST['comp_fornecedor']."' name='comp_fornecedor'>";
			if (isset($_REQUEST['comp_nf']))
				print "<input type='hidden' value='".$_REQUEST['comp_nf']."' name='comp_nf'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_inst'])."' name='comp_inst[]'>";
			if (isset($_REQUEST['comp_inst']))
				print "<input type='hidden' value='".$comp_inst."' name='comp_inst[]'>";
			if (isset($_REQUEST['comp_tipo_equip']))
				print "<input type='hidden' value='".$_REQUEST['comp_tipo_equip']."' name='comp_tipo_equip'>";
			if (isset($_REQUEST['comp_fab']))
				print "<input type='hidden' value='".$_REQUEST['comp_fab']."' name='comp_fab'>";
			if (isset($_REQUEST['comp_tipo_imp']))
				print "<input type='hidden' value='".$_REQUEST['comp_tipo_imp']."' name='comp_tipo_imp'>";
			if (isset($_REQUEST['comp_polegada']))
				print "<input type='hidden' value='".$_REQUEST['comp_polegada']."' name='comp_polegada'>";
			if (isset($_REQUEST['comp_resolucao']))
				print "<input type='hidden' value='".$_REQUEST['comp_resolucao']."' name='comp_resolucao'>";
			if (isset($_REQUEST['comp_ccusto']))
				print "<input type='hidden' value='".$_REQUEST['comp_ccusto']."' name='comp_ccusto'>";
			if (isset($_REQUEST['comp_situac']))
				print "<input type='hidden' value='".$_REQUEST['comp_situac']."' name='comp_situac'>";

			if (isset($comp_data))
				print "<input type='hidden' value='".$comp_data."' name='comp_data'>";
			if (isset($comp_data_compra))
				print "<input type='hidden' value='".$comp_data_compra."' name='comp_data_compra'>";

			//print "<input type='hidden' value='".isset($_REQUEST['comp_data'])."' name='comp_data'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_data_compra'])."' name='comp_data_compra'>";
			if (isset($_REQUEST['garantia']))
				print "<input type='hidden' value='".$_REQUEST['garantia']."' name='garantia'>";
			if (isset($_REQUEST['negado']))
				print "<input type='hidden' value='".$_REQUEST['negado']."' name='negado'>";


			print "</form>";
			print "</table>";

		}
	}
	 else //APENAS 1 REGISTRO
	{
		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='impressora') {
			print cabecalho('<a href=abertura.php>OcoMon</a>','',$TRANS["head_relat_personalizado"]);
			print "<tr><TD bgcolor='".TD_COLOR."'><i>".$TRANS["crit_exib"].": ".$texto.".</i></td></tr><br><br>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B>".$TRANS["encontrado_um"]."<font color='red'>1</font>".$TRANS["reg_no_sistema"].":</B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='consulta_comp.php'>[ ".$TRANS["relat_novo"]." ]</a>.</B></TD></TR>";
		} else
		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='termo') {
			//print "<BODY bgcolor= 'white'>";
			//print cabecalho($logo,'TERMO DE COMPROMISSO PARA HARDWARE',$hoje,'CENTRO DE INFORMÁTICA - CINFO');
			print "<p align='center'><img src='".LOGO_PATH."/unilasalle-peb.gif'></p>";
			print "<br>";
			print "<p class='centro'><B>CENTRO DE INFORMÁTICA - CINFO / SUPORTE AO USUÁRIO - HELPDESK</B></p>";
			print "<p class='centro'><B>TERMO DE COMPROMISSO PARA HARDWARE</B></p>";

			print "<p class='parag'>Por esse termo acuso o recebimento do(s) equipamento(s) abaixo especificado(s), compromentendo-me
				a mantê-lo(s) sob a minha guarda e responsabilidade, dele(s) fazendo uso adequado, de acordo com a resolução 003/2002  que
				define políticas, normas e procedimentos que disciplinam a utilização de equipamentos, recursos e serviços de
				informática do Unilasalle.</p>";
			print "<br>";
			print "<TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='80%' bgcolor= 'black'>";
			$color = A3A352;
			print "<TR><TD bgcolor='".$color."'><b>Etiqueta</TD>".
				"<TD bgcolor='".$color."'><b>Unidade</TD>".
				"<TD bgcolor='".$color."'><b>Tipo</TD>".
				"<TD bgcolor='".$color."'><b>Fabricante</TD>".
				"<TD bgcolor='".$color."'><b>Modelo</TD>".
				"<TD bgcolor='".$color."'><b>N.º Série</TD>".
				"<TD bgcolor='".$color."'><b>Nota Fiscal</TD>".
				"</tr>";
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='transito') {
			print "<p align='center'><img src='".LOGO_PATH."/unilasalle-peb.gif'></p>";
			print "<br>";
			print "<p class='centro'><B>CENTRO DE INFORMÁTICA - CINFO / SUPORTE AO USUÁRIO - HELPDESK</B></p>";
			print "<p class='centro'><B>FORMULÁRIO DE TRÂNSITO DE EQUIPAMENTOS DE INFORMÁTICA</B></p>";
			print "<p class='parag'>Informo que o(s) equipamento(s) abaixo descriminado(s) está(ão)
					autorizado(s) pelo setor responsável a serem transportados para fora da Unidade
					pelo portador citado.</p>";

			print "<br>";

			print "<TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='80%' bgcolor= 'black'>";
			$color = A3A352;
			print "<TR><TD bgcolor='".$color."'><b>Etiqueta</TD>".
				"<TD bgcolor='".$color."'><b>Unidade</TD>".
				"<TD bgcolor='".$color."'><b>Tipo</TD>".
				"<TD bgcolor='".$color."'><b>Fabricante</TD>".
				"<TD bgcolor='".$color."'><b>Modelo</TD>".
				"<TD bgcolor='".$color."'><b>N.º Série</TD>".
				"</tr>";
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='texto') {
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='abertura.php'>OcoMon</a> - <u>".$TRANS["head_relat_txt"].".</u>\t</B></TD></TR><br>";
			print "<tr><TD bgcolor='".TD_COLOR."'><i>".$TRANS["crit_exib"].": ".$texto.".</i></td></tr><br><br>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B>".$TRANS["encontrado_um"]." <font color='red'>1</font> ".$TRANS["reg_no_sistema"].": </B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='consulta_comp.php'>[ ".$TRANS["relat_novo"]." ]</a>.</B></TD></TR>";
		} else { //Visualização normal na tela do sistema!!
			print "<table border='0' cellspacing='1' width='100%'>";
			print "<tr><TD with='70%' align='left'><i>".$TRANS["crit_exib"].": $texto.</i></td><td width='30%' align='left'><form name='checagem' method='post' action=''><input type='checkbox' name='encadeia' value='ok' disabled><a title='".$TRANS["hint_pipe"]."'>".$TRANS["ck_pipe"]."</a>";
			print "<input  type='checkbox' class='radio' name='ckpopup' value='ok' disabled><a title='Consulta os detalhes do equipamento em uma janela popup!'>popup</a>";
			print "</form></td></tr><br>";
			print "<TR><td class='line'><B>".$TRANS["encontrado_um"]." <font color='red'>1</font> ".$TRANS["reg_no_sistema"].":</B></TD><td class='line'></td></TR>";
			print "</table>";
		}
	}
		print "</TD>";

		// Se a consulta foi solicitada para a impressora ele monta outra saída tipo relatório
		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='impressora') {
			print "<hr width='80%' align='center'>";
			$i=0;
        		$j=2;
			while ($row = mysql_fetch_array($resultado)) {
				if ($j % 2)
				{
					$color =  'white';//BODY_COLOR;
				}
				else
				{
					$color = 'white';
				}
				$j++;

				//print "<title>InvMon - Relatório</title>";
				print "<TABLE WIDTH='80%' BORDER='0' CELLPADDING='4' CELLSPACING='0' align='center'>";
				print "<link rel=stylesheet type=text/css href='../includes/css/estilos.css'>";
				print "	<COL WIDTH='10%'>";
				print "<COL WIDTH='20%'>";
				print "	<COL WIDTH='10%'>";
				print "	<COL WIDTH='20%'>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT'>".strtoupper($TRANS["col_tipo"]).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT'>".$row['equipamento']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT'>".strtoupper($TRANS["col_fabricante"]).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT'>".$row['fab_nome']."</P>";
				print "			</TH>";
				print "		</TR>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["col_etiqueta"]).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><a href='mostra_consulta_inv.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."'>".$row['etiqueta']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["col_sn"]).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['serial']."</P>";
				print "			</TH>";
				print "		</TR>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["col_modelo"]).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['modelo']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["col_nf"]).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['nota']."</P>";
				print "			</TH>";
				print "		</TR>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["col_situacao"]).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['situac_nome']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["col_local"]).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['local']."</P>";
				print "			</TH>";
				print "		</TR>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["col_instituicao"]).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['instituicao']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><BR>";
				print "				</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><BR>";
				print "				</P>";
				print "			</TH>";
				print "		</TR>";
				print "</TABLE>		";
				print "		<hr width='80%' align='center'>";
                print" <hr width='80%' align='center'>";
                $i++;
		}

		print "<b><a href='abertura.php'>OcoMon</a> - ".$TRANS["menu_title"].". ".$TRANS["data"].": ".$hoje.".</b>";
        	print "</TABLE>";


	} else if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='termo') {


		print "<title>OcoMon - Termo de compromisso HW</title>";
		print "<link rel='stylesheet' type='text/css' href='./css/estilos.css'>";

		while ($row = mysql_fetch_array($resultado)) {
			$color =  'white';//BODY_COLOR;
			print "<TR>";
			print "<TD bgcolor='".$color."'>".$row['etiqueta']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['instituicao']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['equipamento']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['fab_nome']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['modelo']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['serial']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['nota']."</TD>";
			print "</tr>";
			$setor = $row['local'];
		}

		//Linha que mostra o total de registros mostrados
		$cor2='#A8A8A8';

	print "</TABLE><br><br>";
		//print "</fieldset>";
		print "<div id='container'>";
		print "<p class='parag_header'><b>INFORMAÇÕES COMPLEMENTARES:</b></P>";
		print "<p class='parag'>";
		print "<TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='80%' bgcolor='black'";
		print "<tr><td bgcolor='white'>Centro de Custo:</td><td bgcolor='white'>".$custoNum." - ".$centroCusto."</td></tr>";
		print "<tr><td bgcolor='white'>Setor:</td><td bgcolor='white'>".strtoupper($setor)."</td></tr>";
		print "<tr><td bgcolor='white'>Usuário responsável:</td><td bgcolor='white'><input type='text' class='text3' name='responsavel'></td></tr>";
		print "</table>";
		print "</P>";

		print "<p class='parag_header'><b>IMPORTANTE:</b></P>";
		print "<p class='parag'>O suporte para qualquer problema que porventura vier a ocorrer na instalação
		 ou operação do(s) equipamento(s), deverá ser solicitado ao Helpdesk,
		 através do ramal 8618, pois somente através desde procedimento os chamados poderão ser registrados
		  e atendidos.</p>";
		print "<p class='parag'>Em conformidade com o preceituado no art. 1º da Resolução nº 003/2002, é expressamente
                vedada a instalação de <i>softwares</i> sem a necessária licença de uso ou em desrespeito aos direitos autorais.</p>";
		print "<p class='parag'>O UNILASALLE, através do seu Centro de Informática (CINFO), em virtude das suas disposições
                    regimentais e regulamentadoras, adota sistema de controle de instalação de <i>softwares</i> em todos os seus
                    equipamentos, impedindo a instalação destes sem prévia autorização do Setor Competente.</p>";

		print "<br>";
		print "<p class='parag'>Assinatura:__________________________________</P>";
		print "<p class='parag'>Canoas, ".$hoje_termo.".</p>";
		print "<br><br><br><br><br>";
		print "<div id='footer'><B><a href='abertura.php'>OcoMon</a> - Sistema de Operacionalização e Gerência de Suporte técnico.</B></div>";
		print "</div>";

	} else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='transito') {

		print "<title>OcoMon - Termo de compromisso HW</title>";
		print "<link rel='stylesheet' type='text/css' href='./css/estilos.css'>";

		$i=0;
		$j=2;
		while ($row = mysql_fetch_array($resultado)) {
			$color = 'white';//BODY_COLOR;

			print "<TR>";
			print "<TD bgcolor='".$color."'>".$row['etiqueta']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['instituicao']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['equipamento']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['fab_nome']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['modelo']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['serial']."</TD>";
			print "</tr>";
		}
			$cor2="#A8A8A8";

        print "</TABLE>";
		//print "</fieldset>";
		print "<div id='container'>";
		print "<p class='parag_header'><b>INFORMAÇÕES COMPLEMENTARES:</b></P>";
		print "<p class='parag'>";
		print "<TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='80%' bgcolor='black'";
		print "<tr><td bgcolor='white'>Portador:</td><td bgcolor='white'><input type='text' class='text3' name='portador'></td></tr>";
		print "<tr><td bgcolor='white'>Destino:</td><td bgcolor='white'><input type='text' class='text3' name='destino'></td></tr>";
		print "<tr><td bgcolor='white'>Data da saída:</td><td bgcolor='white'>".$hoje_termo."</td></tr>";
		print "<tr><td bgcolor='white'>Motivo:</td><td bgcolor='white'><input type='text' class='text3' name='motivo'></td></tr>";
		print "<tr><td bgcolor='white'>Autorizador por:</td><td bgcolor='white'><input type='text' class='text3' name='responsavel'></td></tr>";
		print "<tr><td bgcolor='white'>Setor responsável:</td><td bgcolor='white'><input type='text' class='text3' name='setor_reponsavel'></td></tr>";

		print "</table>";
		print "</P>";

		print "<p class='parag_header'><b>IMPORTANTE:</b></P>";
		print "<p class='parag'>A constatação de inconformidade dos dados aqui descritos no ato de verificação
				na portaria implica na <b>não</b> autorização de saída dos equipamentos, nesse caso o setor
				responsável deve ser contactado.</p>";

		print "<br>";
		print "<p class='parag'>Assinatura:__________________________________</P>";
		print "<p class='parag'>Canoas, ".$hoje_termo.".</p>";
		print "<br><br><br><br><br>";
		print "<div id='footer'><B><a href=abertura.php>OcoMon</a> - Sistema para Operacionalização e Gerência de suporte técnico.</B></div>";
		print "</div>";
	} else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='config') {
		print" <hr width=80% align=center>";
		$i=0;
		$j=2;
		while ($row = mysql_fetch_array($resultado)) {
			if ($j % 2)
			{
				$color =  'white';//BODY_COLOR;
			}
			else
			{
				$color = 'white';
			}
			$j++;

			print "<TABLE WIDTH='80'% BORDER='0' CELLPADDING='4' CELLSPACING='0' align='center'>";
			print "<link rel='stylesheet' type='text/css' href='./css/estilos.css'>";
			print "	<COL WIDTH='10%'>";
			print "<COL WIDTH='20%'>";
			print "	<COL WIDTH='10%'>";
			print "	<COL WIDTH='20%'>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT'>".strtoupper($TRANS["col_tipo"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT'>".$row['equipamento']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT'>".strtoupper($TRANS["col_fabricante"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT'>".$row['fab_nome']."</P>";
			print "			</TH>";
			print "		</TR>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["col_etiqueta"])."</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><a href='mostra_consulta_inv.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."'>".$row['etiqueta']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["col_sn"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($row['serial'])."</P>";
			print "			</TH>";
			print "		</TR>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["col_modelo"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['modelo']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["col_nf"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['nota']."</P>";
			print "			</TH>";
			print "		</TR>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["col_situacao"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['situac_nome']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["col_local"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['local']."</P>";
			print "			</TH>";
			print "		</TR>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["col_instituicao"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['instituicao']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><BR>";
			print "				</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><BR>";
			print "				</P>";
			print "			</TH>";
			print "		</TR>";

			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["cx_proc"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['fabricante_proc']." ".$row['processador']." ".$row['clock']." ".$row['proc_sufixo']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["cx_mb"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['fabricante_mb']." ".$row['mb']."</P>";
			print "			</TH>";
			print "		</TR>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["cx_video"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['fabricante_video']." ".$row['video']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["cx_memo"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['memoria']."".$row['memo_sufixo']."</P>";
			print "			</TH>";
			print "		</TR>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["cx_rede"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['rede_fabricante']." ".$row['rede']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["cx_som"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['fabricante_som']." ".$row['som']."</P>";
			print "			</TH>";
			print "		</TR>";

			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["cx_hd"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['fabricante_hd']." ".$row['hd_capacidade']."".$row['hd_sufixo']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["cx_cdrom"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['fabricante_cdrom']." ".$row['cdrom']."</P>";
			print "			</TH>";
			print "		</TR>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["cx_modem"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['fabricante_modem']." ".$row['modem']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($TRANS["cx_grav"]).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><FONT FACE='Arial, sans-serif'>".$row['fabricante_gravador']." ".$row['gravador']."</P>";
			print "			</TH>";
			print "		</TR>";

			print "</TABLE>";
			print "<hr width='80%' align='center'>";
			print "<hr width='80%' align='center'>";
			$i++;
		}

		print "<b><a href='abertura.php'>".$TRANS["menu_title"]."</a>. ".$TRANS["data"].": ".$hoje.".</b>";
		print "</TABLE>";

	} else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='relatorio') {

		$color = "#A3A352";
                print "<link rel='stylesheet' type='text/css' href='./css/estilos.css'>";
		$i=0;
		$j=2;
		while ($row = mysql_fetch_array($resultado)) {
			if ($j % 2)
			{
				if (($row['situac_cod']==4)or ($row['situac_cod']==5)) { //Equipamento trocado ou furtado!!
					$color='#FF0000';
					$alerta = "style='{color:white;}'";
				} else {
					$color =  "#C8C8C8";
					$alerta = "";
				}
			}
			else
			{
			// $color = EAEAEA;
				if (($row['situac_cod']==4)or ($row['situac_cod']==5))
				{
					$color='#FF0000';
					$alerta = "style='{color:white;}'";
				}
				else
				{
					$color =  "#EAEAEA";
					$alerta = "";
				}
			}
			$j++;

			print "<TR>";
			print "<TD bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_inv.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."' title='".$TRANS['hint_col_etiqueta']."'>".$row['etiqueta']."</a></TD>";

			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_inst=".$row['instituicao']."&ordena=instituicao,fab_nome,modelo,local,etiqueta&visualiza=relatorio'>".$row['instituicao']."</a></td>";

			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_tipo_equip=".$row['tipo']."&ordena=fab_nome,modelo,local,etiqueta&visualiza=relatorio' title='".$TRANS["hint_col_tipo"]." ".$row['equipamento']."'>".$row['equipamento']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_marca=".$row['modelo_cod']."&ordena=local,etiqueta&visualiza=relatorio' title='".$TRANS['hint_col_modelo']." ".$row['fab_nome']." ".$row['modelo'].".'>".$row['fab_nome']." ".$row['modelo']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_sn=".$row['serial']."&ordena=equipamento,fab_nome,modelo,etiqueta&visualiza=relatorio' title='".$TRANS['hint_col_sn']." ".$row['serial'].".'>".strtoupper($row['serial'])."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_nf=".$row['nota']."&ordena=fab_nome,modelo,local,etiqueta&visualiza=relatorio' title='".$TRANS['hint_col_nf']." ".$row['nota'].".'>".$row['nota']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_situac=".$row['situac_cod']."&ordena=local,etiqueta&visualiza=relatorio' title='".$TRANS['hint_col_situacao']." ".$row['situac_nome'].".'>".$row['situac_nome']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_local=".$row['tipo_local']."&ordena=equipamento,fab_nome,modelo,etiqueta&visualiza=relatorio' title='".$TRANS['hint_col_local']." ".$row['local'].".'>".$row['local']."</a></td>";
			print "</tr>";
			$i++;
		}


		//Linha que mostra o total de registros mostrados
		$cor2='#A8A8A8';
		print "<TR><TD colspan='6' bgcolor='".$cor2."'><b></TD>".
				"<TD bgcolor='".$cor2."'><b>TOTAL</TD>".
				"<TD bgcolor='".$cor2."'><b><font color='red'>".$linhas."</font></TD>".
			"</tr>";

		print "</TABLE><br>";

		print "<table width='90%'>".
				"<tr><td class='line'><b><a href='abertura.php'>".$TRANS["menu_title"]."</a>. ".$TRANS["data"].": $hoje.</b></td>".
				"</tr>".
			"</table>";


	} else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='mantenedora1') {
		print "<link rel='stylesheet' type='text/css' href='./css/estilos.css'>";
		$i=0;
		$j=2;
		$cor2='#A8A8A8';
		while ($row = mysql_fetch_array($resultado)) {
			if ($j % 2)
			{
				$color = '#C8C8C8';//BODY_COLOR;
				$alerta = "style='{color:white;}'";
			}
			else
			{
				$color = '#EAEAEA';
				$alerta = "";
			}
			$j++;

			if (!(empty($row['ccusto'])))
			{
				$CC =  $row['ccusto'];
				$query2 = "select * from ".DB_CCUSTO.".".TB_CCUSTO." where ".CCUSTO_ID."= ".$CC."";
				$resultado2 = mysql_query($query2);

				$row2 = mysql_fetch_array($resultado2);
				$centroCusto = $row2[CCUSTO_COD];
				$custoDesc = $row2[CCUSTO_DESC];
			} else
				$centroCusto = '';

			print "<TR>";
			print "<TD bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_inv.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."' title='".$TRANS['hint_col_etiqueta']."'>".$row['etiqueta']."</a></TD>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_tipo_equip=".$row['tipo']."&ordena=fab_nome,modelo,local,etiqueta&visualiza=mantenedora1' title='".$TRANS["hint_col_tipo"]." ".$row['equipamento']."'>".$row['equipamento']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_fab=".$row['fab_cod']."&ordena=fab_nome,modelo,local,etiqueta&visualiza=mantenedora1' title='".$TRANS['hint_col_fabricante']." ".$row['fab_nome'].".'>".$row['fab_nome']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_marca=".$row['modelo_cod']."&ordena=local,etiqueta&visualiza=mantenedora1' title='".$TRANS['hint_col_modelo']." ".$row['fab_nome']." ".$row['modelo'].".'>".$row['fab_nome']." ".$row['modelo']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_sn=".$row['serial']."&ordena=equipamento,fab_nome,modelo,etiqueta&visualiza=mantenedora1' title='".$TRANS['hint_col_sn']." ".$row['serial'].".'>".strtoupper($row['serial'])."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_nf=".$row['nota']."&ordena=fab_nome,modelo,local,etiqueta&visualiza=mantenedora1' title='".$TRANS['hint_col_nf']." ".$row['nota'].".'>".$row['nota']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_situac=".$row['situac_cod']."&ordena=local,etiqueta&visualiza=mantenedora1' title='".$TRANS['hint_col_situacao']." ".$row['situac_nome'].".'>".$row['situac_nome']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_local=".$row['tipo_local']."&ordena=equipamento,fab_nome,modelo,etiqueta&visualiza=mantenedora1' title='".$TRANS['hint_col_local']." ".$row['local'].".'>".$row['local']."</a></td>";

			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_ccusto=".$row['ccusto']."&ordena=equipamento,fab_nome,modelo,etiqueta&visualiza=mantenedora1' title='".$TRANS['hint_col_ccusto']." ".$custoDesc.".'>".$centroCusto."</a></td>";
			print "</tr>";
			$i++;
		}
		//Linha que mostra o total de registros mostrados
		print "<TR><TD bgcolor='".$cor2."'><b></TD>".
			"<TD bgcolor='".$cor2."'><b></TD>".
			"<TD bgcolor='".$cor2."'><b></TD>".
			"<TD bgcolor='".$cor2."'><b></TD>".
			"<TD bgcolor='".$cor2."'><b></TD>".
			"<TD bgcolor='".$cor2."'><b></TD>".
			"<TD bgcolor='".$cor2."'><b></TD>".
			"<TD bgcolor='".$cor2."'><b>TOTAL</TD>".
			"<TD bgcolor='".$cor2."'><b><font color='red'>".$linhas."</font></TD>".
			"</tr>";

		print "</TABLE><br>";

		print "<table width='90%'><tr><td class='line'><b><a href='abertura.php'>OcoMon</a> - Sistema para Operacionalização e Gerência de suporte técnico. Data: ".$hoje.".</b></td></tr></table>";

	}  else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='texto') {  //Texto separado por tabulação//
		print "<link rel='stylesheet' type='text/css' href='./css/estilos.css'>";
		print "<br><i>(Selecione o texto abaixo e salve em um editor de textos com a extensão CSV para exportar para alguma base de dados).</i><br><br><br>";
		echo" <hr width='100%' align='center'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='white'>";
		print "<b>'".$TRANS["col_etiqueta"]."','".$TRANS["col_instituicao"]."','".$TRANS["col_tipo"]."','".$TRANS["col_fabricante"]."','".$TRANS["col_modelo"]."','".$TRANS["col_sn"]."','".$TRANS["col_fornecedor"]."','".$TRANS["col_nf"]."','".$TRANS["cx_cc"]."','".$TRANS["col_local"]."','".$TRANS["cx_proc"]."','".$TRANS["cx_memo"]."','".$TRANS["cx_hd"]."','".$TRANS["cx_impressora"]."','".$TRANS["cx_monitor"]."','".$TRANS["cx_scanner"]."'</b><br>";

		$i=0;
		$j=2;
		while ($row = mysql_fetch_array($resultado)) {
			if ($j % 2)
			{
				$color =  'white';
			}
			else
			{
				$color = 'white';
			}
			$j++;
			if (!(empty($row['ccusto'])))
			{
				$CC =  $row['ccusto'];
				$query2 = "select * from ".DB_CCUSTO.".".TB_CCUSTO." where ".CCUSTO_ID."= ".$CC."";
				$resultado2 = mysql_query($query2);
				$row3 = mysql_fetch_array($resultado2);
				$resultado3 = $row3[CCUSTO_DESC];
				$centroCusto = $row3[CCUSTO_COD];
			}
			print "".$row['etiqueta'].",".$row['instituicao'].",".$row['equipamento'].",".$row['fab_nome'].",".$row['modelo'].",".$row['serial'].",".$row['fornecedor_nome'].",".$row['nota'].",".$centroCusto.",".$row['local'].",".$row['proc_modelo']." ".$row['proc_clock']." ".$row['proc_sufixo'].",".$row['memoria']."".$row['memo_sufixo'].",".$row['hd_fabricante']." ".$row['hd_capacidade']."".$row['hd_sufixo'].",".$row['impressora'].",".$row['polegada_nome'].",".$row['resol_nome']."<br>";
			$centroCusto ="";
			print "</TR>";
			$i++;
		}
		print "<hr width='100%' align='center'>";
		print "</TABLE>";
	}
	else ####### Mostra Consulta normal na tela principal do sistema!!
	{
		print "<fieldset><legend>".$TRANS["head_equipamentos"]."</legend>";
		print "<TABLE border='0' cellpadding='3' cellspacing='0' align='center' width='100%'>";
		print "<TR class='header'>".
				"<TD valign='middle'><b><a href='mostra_consulta_comp.php?ordena=etiqueta&coluna=etiqueta&ordenado=".$ordenado."&".$param."' title='Ordenar por etiqueta.'>".$TRANS["col_etiqueta"]."</a>".$ICON_ORDER['etiqueta']."</TD>".
				"<td class='line'><b><a href='mostra_consulta_comp.php?ordena=instituicao,etiqueta&coluna=instituicao&ordenado=".$ordenado."&".$param."' title='Ordenar pela Unidade.'>".$TRANS["col_instituicao"]."</a>".$ICON_ORDER['instituicao']."</TD>".
				"<td class='line'><b><a href='mostra_consulta_comp.php?ordena=equipamento,modelo&coluna=tipo&ordenado=".$ordenado."&".$param."' title='Ordenar pelo tipo de equipamento.'>".$TRANS["col_tipo"]."</a>".$ICON_ORDER['tipo']."</TD>".
				"<td class='line'><b><a href='mostra_consulta_comp.php?ordena=fab_nome,modelo&coluna=modelo&ordenado=".$ordenado."&".$param."' title='Ordenar por modelo de equipamento.'>".$TRANS["col_modelo"]."</a>".$ICON_ORDER['modelo']."</TD>".
				"<td class='line'><b><a href='mostra_consulta_comp.php?ordena=local&coluna=local&ordenado=".$ordenado."&".$param."' title='Ordenar por localização.'>".$TRANS["col_local"]."</a>".$ICON_ORDER['local']."</TD>".
				"<td class='line'><b><a href='mostra_consulta_comp.php?ordena=situac_nome&coluna=situacao&ordenado=".$ordenado."&".$param."' title='Ordenar por situação.'>".$TRANS["col_situacao"]."</a>".$ICON_ORDER['situacao']."</TD>";
		if ($_SESSION['s_invmon']==1)
			print "<td class='line'><b>".$TRANS["col_alterar"]."</TD>";
		if ($administrador){
			print "<td class='line'><b>".$TRANS["col_excluir"]."</TD>";
		}
		$i=0;
		$j=2;
		$cont=0;
  		while ($row = mysql_fetch_array($resultado)) {
			$cont++;
			if ($j % 2)
			{
				if (($row['situac_cod']==4)or ($row['situac_cod']==5)) {//Equipamento Trocado ou furtado!!!
					$color="#FF0000";
					$alerta = "style='{color:yellow;}'";
					$trClass = "lin_alerta";
				} else {
					$color =  BODY_COLOR;
					$alerta = "";
					$trClass = "lin_par";
				}
			}
			else
			{
				if (($row['situac_cod']==4)or ($row['situac_cod']==5)) {
					$color='#FF0000';
					$alerta = "style='{color:yellow;}'";
					$trClass = "lin_alerta";
				} else {
					$color = 'white';
					$alerta = "";
					$trClass = "lin_impar";
				}
			}
                	$j++;
			print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";

			print "<td class='line'><a ".$alerta." onClick=\"montaPopup('mostra_consulta_inv.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."')\" title='Exibe os detalhes de cadastro desse equipamento.'>".$row['etiqueta']."</a></TD>";
			print "<td class='line'><a ".$alerta." title='Filtra a saída para equipamentos da Unidade ".$row['instituicao'].".' href=\"javascript:monta_link('?comp_inst%5B%5D=".$row['cod_inst']."&ordena=fab_nome,modelo,local,etiqueta&coluna=instituicao&ordenado=".$ordenado."','".$param."','comp_inst')\">".$row['instituicao']."</a></td>";
			print "<td class='line'><a ".$alerta." title='Filtra a saída para equipamentos do tipo ".$row['equipamento'].".' href=\"javascript:monta_link('?comp_tipo_equip=".$row['tipo']."&ordena=fab_nome,modelo,local,etiqueta&coluna=tipo&ordenado=".$ordenado."','".$param."','comp_tipo_equip')\">".$row['equipamento']."</a></td>";
			print "<td class='line'><a ".$alerta." title='Filtra a saída para equipamentos do modelo ".$row['fab_nome']." ".$row['modelo'].".' href=\"javascript:monta_link('?comp_marca=".$row['modelo_cod']."&ordena=local,etiqueta&coluna=modelo&ordenado=".$ordenado."','".$param."','comp_marca')\">".$row['fab_nome']." ".$row['modelo']."</a></td>";
			print "<td class='line'><a ".$alerta." title='Filtra a saída para equipamentos localizados no setor ".$row['local'].".' href=\"javascript:monta_link('?comp_local=".$row['tipo_local']."&ordena=equipamento,fab_nome,modelo,etiqueta&coluna=local&ordenado=".$ordenado."','".$param."','comp_local')\">".$row['local']."</a></td>";
			print "<td class='line'><a ".$alerta." title='Filtra a saída para equipamentos em situação ".$row['situac_nome'].".' href=\"javascript:monta_link('?comp_situac=".$row['situac_cod']."&ordena=fab_nome,modelo,local,etiqueta&coluna=modelo&ordenado=etiqueta','".$param."','comp_situac')\">".$row['situac_nome']."</a></td>";
			if ($_SESSION['s_invmon']==1)
				print "<td class='line'><a ".$alerta." onClick =\"return redirect('altera_dados_computador.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row["cod_inst"]."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
			if ($administrador){
				print "<td class='line'><a ".$alerta." onClick =\"return confirma('".$TRANS["confirm_exclui"]."?','exclui_equipamento.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
			}
			print "</TR>";
			$i++;
		}
		print "</TABLE>";

		if ($linhas>5) { //Colocar rodapé se a quantidade de registros for maior do que 20 registros.

			print "</fieldset>";
			print "<table border='0' cellpadding='3' cellspacing='0' summary=''>";
			print "<FORM method='post' action='".$_SERVER['PHP_SELF']."'>";

			print "<TR>";
			$min++;
			if (isset($avancaTodos)) {$top=$linhasTotal;} else $top=$min+($max-1);
			print "<TD width='750' align='left' ><B>".$TRANS["encontrado"]." <font color='red'>".$linhasTotal."</font> ".$TRANS["reg_ord"]." <u>".$traduzOrdena."</u>. ".$TRANS["mostrado"]." <font color='red'>".$min."</font> ".$TRANS["ate"]." <font color='red'>".$top."</font>.</B></TD>";
			print "<TD width='50' align='left' ></td>";

			print "<TD width='30%' align='right'><input  type='submit' class='button' name='voltaInicio' value='<<' ".
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
			print "<input type='hidden' value='".$base."' name='top'>";
			print "<input type='hidden' value='".$top."' name='top'>";
			print "<input type='hidden' value='".$ordena."' name='ordena'>";
			print "<input type='hidden' value='".$comp_inv."' name='comp_inv'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_sn'])."' name='comp_sn'>";
			if (isset($comp_sn))
				print "<input type='hidden' value='".$comp_sn."' name='comp_sn'>";
			if (isset($_REQUEST['comp_marca']))
				print "<input type='hidden' value='".$_REQUEST['comp_marca']."' name='comp_marca'>";
			if (isset($_REQUEST['comp_mb']))
				print "<input type='hidden' value='".$_REQUEST['comp_mb']."' name='comp_mb'>";
			if (isset($_REQUEST['comp_proc']))
				print "<input type='hidden' value='".$_REQUEST['comp_proc']."' name='comp_proc'>";
			if (isset($_REQUEST['comp_memo']))
				print "<input type='hidden' value='".$_REQUEST['comp_memo']."' name='comp_memo'>";
			if (isset($_REQUEST['comp_video']))
				print "<input type='hidden' value='".$_REQUEST['comp_video']."' name='comp_video'>";
			if (isset($_REQUEST['comp_som']))
				print "<input type='hidden' value='".$_REQUEST['comp_som']."' name='comp_som'>";
			if (isset($_REQUEST['comp_rede']))
				print "<input type='hidden' value='".$_REQUEST['comp_rede']."' name='comp_rede'>";
			if (isset($_REQUEST['comp_modem']))
				print "<input type='hidden' value='".$_REQUEST['comp_modem']."' name='comp_modem'>";
			if (isset($_REQUEST['comp_modelohd']))
				print "<input type='hidden' value='".$_REQUEST['comp_modelohd']."' name='comp_modelohd'>";

			if (isset($_REQUEST['comp_cdrom']))
				print "<input type='hidden' value='".$_REQUEST['comp_cdrom']."' name='comp_cdrom'>";
			if (isset($_REQUEST['comp_dvd']))
				print "<input type='hidden' value='".$_REQUEST['comp_dvd']."' name='comp_dvd'>";
			if (isset($_REQUEST['comp_grav']))
				print "<input type='hidden' value='".$_REQUEST['comp_grav']."' name='comp_grav'>";
			if (isset($_REQUEST['comp_local']))
				print "<input type='hidden' value='".$_REQUEST['comp_local']."' name='comp_local'>";
			if (isset($_REQUEST['comp_nome']))
				print "<input type='hidden' value='".$_REQUEST['comp_nome']."' name='comp_nome'>";
			if (isset($_REQUEST['comp_fornecedor']))
				print "<input type='hidden' value='".$_REQUEST['comp_fornecedor']."' name='comp_fornecedor'>";
			if (isset($_REQUEST['comp_nf']))
				print "<input type='hidden' value='".$_REQUEST['comp_nf']."' name='comp_nf'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_inst'])."' name='comp_inst[]'>";
			if (isset($_REQUEST['comp_inst']))
				print "<input type='hidden' value='".$comp_inst."' name='comp_inst[]'>";
			if (isset($_REQUEST['comp_tipo_equip']))
				print "<input type='hidden' value='".$_REQUEST['comp_tipo_equip']."' name='comp_tipo_equip'>";
			if (isset($_REQUEST['comp_fab']))
				print "<input type='hidden' value='".$_REQUEST['comp_fab']."' name='comp_fab'>";
			if (isset($_REQUEST['comp_tipo_imp']))
				print "<input type='hidden' value='".$_REQUEST['comp_tipo_imp']."' name='comp_tipo_imp'>";
			if (isset($_REQUEST['comp_polegada']))
				print "<input type='hidden' value='".$_REQUEST['comp_polegada']."' name='comp_polegada'>";
			if (isset($_REQUEST['comp_resolucao']))
				print "<input type='hidden' value='".$_REQUEST['comp_resolucao']."' name='comp_resolucao'>";
			if (isset($_REQUEST['comp_ccusto']))
				print "<input type='hidden' value='".$_REQUEST['comp_ccusto']."' name='comp_ccusto'>";
			if (isset($_REQUEST['comp_situac']))
				print "<input type='hidden' value='".$_REQUEST['comp_situac']."' name='comp_situac'>";

			if (isset($_REQUEST['comp_data']))
				print "<input type='hidden' value='".$comp_data."' name='comp_data'>";
			if (isset($_REQUEST['comp_data_compra']))
				print "<input type='hidden' value='".$comp_data_compra."' name='comp_data_compra'>";

			//print "<input type='hidden' value='".isset($_REQUEST['comp_data'])."' name='comp_data'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_data_compra'])."' name='comp_data_compra'>";
			if (isset($_REQUEST['garantia']))
				print "<input type='hidden' value='".$_REQUEST['garantia']."' name='garantia'>";
			if (isset($_REQUEST['negado']))
				print "<input type='hidden' value='".$_REQUEST['negado']."' name='negado'>";


			print "</form>";
			print "</table>";

		} else {
			print "<TABLE border='0' cellpadding='1' cellspacing='0' align='center' width='100%' bgcolor='".BODY_COLOR."'>";
			print "<TR><TD bgcolor='".TD_COLOR."'><font color='".TD_COLOR."'>&nbsp</font></TD></TR>";
			print "</table>";
			print "</fieldset>";
		}
	}

	?>
	<SCRIPT LANGUAGE="JAVASCRIPT">
	<!--

		desabilitaLinks(<?print $_SESSION['s_invmon'];?>);

		function checar() {
			var checado = false;
			if (document.checagem.encadeia.checked){
				checado = true;
			} else {
				checado = false;
			}
			return checado;
		}
		//window.setInterval("checar()",3000);

		function ckPopup() {
			var popup = false;
			if (document.checagem.ckpopup.checked){
				popup = true;
			} else {
				popup = false;
			}
			return popup;
		}
		//window.setInterval("ckPopup()",3000);


		function montaPopup(pagina)	{ //Exibe uma janela popUP

			if (ckPopup()==false){
				window.location.href=pagina;
			} else {
				x = window.open(pagina,'_blank','dependent=yes,width=650,height=470,scrollbars=yes,statusbar=no,resizable=yes');
				x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
			}
			return false
		}

		function negar() {
			var negado = false;
			if (document.checagem.negada.checked){
				negado = true;
			} else {
				negado = false;
			}
			return negado;
		}
		//window.setInterval("negar()",3000);

		function monta_link(clicado,parametro,negaCampo){

			var encadeado = "encadeado=1";
			if (checar()==false){
				parametro = "";
				encadeado = "";
			} /*else
				if (negar()==false){
					negaCampo = "";
				} else {
					negaCampo = "negado="+negaCampo;
				} */
				//alert (clicado+"&"+negaCampo+"&"+parametro);
				//window.location.href=clicado+"&"+negaCampo+"&"+parametro;

				window.location.href=clicado+"&"+parametro+"&"+encadeado;
		}
		//-->
		</SCRIPT>
		<?
print "</body>";
print "</html>";
?>


