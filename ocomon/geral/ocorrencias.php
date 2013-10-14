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

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

	print "<BR><B>".TRANS('ADM_OCO')."</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";


	$queryTotal = "SELECT * from ocorrencias";
        $resultadoTotal = mysql_query($queryTotal);
        $linhasTotal = mysql_num_rows($resultadoTotal);

	$qry_page = "SELECT conf_page_size AS page FROM config";
	$qry_page_exec = mysql_query($qry_page) or die (TRANS('ERR_QUERY'));
	$rowConf = mysql_fetch_array($qry_page_exec);
	$PAGE_SIZE = $rowConf['page'];

	/*------------------------------------------------------------------------------
	@$min = PRIMEIRO REGISTRO A SER EXIBIDO
	@$max = QUANTIDADE DE REGISTROS POR PÁGINA
	@$top = NÚMERO DO ÚLTIMO REGISTRO EXIBIDO DA PÁGINA
	@$base = NÚMERO DO PRIMEIRO REGISTRO EXIBIDO DA PÁGINA
	--------------------------------------------------------------------------------*/

        $min = 0;
        $maxAux = 0;
        $minAux = 0;

	$query = $QRY["ocorrencias_full_ini"]." order by numero ";

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

	//print $query;

	$resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);
        $cor=TD_COLOR;
        $cor1=TD_COLOR;

	print "<table border='0' cellspacing='1' summary='' width='100%'>";
        if ($linhas == 0)
        {
                print "<TR class='header'><td class='line'><B>".TRANS('MSG_NO_RECORDS')."</B></TD></TR>";
                print "</table>";
                exit;
        }
        if ($linhas>1){
				//print "<table border='0' cellspacing='1' summary=''>";
				print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."'>";

				$min++;

				print "<tr>";
				print "<TD witdh='700' align='left'><B>".TRANS('THERE_IS_ARE')." <font color=red>".$linhasTotal."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".
					"".TRANS('mostrado')." <font color=red>".$base."</font> ".TRANS('ate')." <font color=red>".$top."</font>. </B></TD>";
				print "<TD colspan='2' width='300' align='right' ><input  type='submit' class='button' name='voltaInicio' value='<<' ".
					"title='".TRANS('VIEW_THE')." ".$max." ".TRANS('FIRST_RECORDS').".'> <input  type='submit' class='button'  name='voltaUm' value='<' ".
					"title='".TRANS('VIEW_THE')." ".$max." ".TRANS('PREVIOUSLY_RECORDS').".'> <input  type='submit' class='button'  name='avancaUm' value='>' ".
					"title='".TRANS('VIEW_THE_NEXT')." ".$max." ".TRANS('RECORDS').".'> <input  type='submit' class='button'  name='avancaFim' value='>>' ".
					"title='".TRANS('VIEW_THE_LAST')." ".$max." ".TRANS('RECORDS').".'> <input  type='submit' class='button'  name='avancaTodos' value='".TRANS('ALL')."' ".
					"title='".TRANS('VIEW_ALL')." ".$linhasTotal." ".TRANS('RECORDS').".'></td>";
				print "</tr>";
				$min--;
				print "<input type='hidden' value='".$min."' name='min'>";
				print "<input type='hidden' value='".$max."' name='max'>";
				print "<input type='hidden' value='".$maxAux."' name='maxAux'>";
				print "<input type='hidden' value='".$base."' name='top'>";
				print "<input type='hidden' value='".$top."' name='top'>";
				print "</form>";
				print "</table>";

		}
		else {
                	print "<TR class='header'><td class='line'><B>".TRANS('THEREIS')." 1 ".TRANS('OCO').".</B></TD></TR>";
                }
        //print "</TD>";

        //print "<td class='line'>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
        print "<TR class='header'><td class='line'>".TRANS('COL_NUMBER')."</TD><td class='line'>".TRANS('COL_PROB')."</TD><td class='line'>".TRANS('COL_LOCAL')."</TD><td class='line'>".TRANS('COL_OPERATOR')."</TD>
                <td class='line'>".TRANS('COL_OPEN')."</TD><td class='line'>".TRANS('COL_STATUS')."</TD><td class='line'>".TRANS('COL_EDIT')."</TD><td class='line'>".TRANS('COL_DEL')."</TD></TR>";
        $i=0;
        $j=2;
        while ($row= mysql_fetch_array($resultado))
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

                print "<td class='line'><a href='mostra_consulta.php?numero=".$row['numero']."'>".$row['numero']."</a></TD>";
                print "<td class='line'>".$row['problema']."</TD>";
                print "<td class='line'>".$row['setor']."</TD>";
                print "<td class='line'>".$row['nome']."</TD>";
                print "<td class='line'>".formatDate($row['data_abertura'])."</TD>";
                print "<td class='line'>".$row['chamado_status']."</TD>";


		print "<td class='line'><a onClick=\"redirect('altera_dados_ocorrencia.php?numero=".$row['numero']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></TD>";
		print "<td class='line'><a onClick=\"confirma('".TRANS('ENSURE_DEL')."?','excluir_ocorrencia.php?numero=".$row['numero']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
		print "</TR>";
		$i++;
        }
print "</table>";
print "</BODY>";
print "</HTML>";
