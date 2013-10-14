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
	$cab->set_title(TRANS("html_title"));
	$auth = new auth;

	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

        $hoje = date("Y-m-d H:i:s");

        $cor  = TD_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;

	$base =1;
	$topo = 30;


	if (isset($_POST['from_menu'])){
		$BT_TEXT = "Voltar";
		$GETOUT = "javascript:history.back()";
	} else {
		$BT_TEXT = "Fechar";
		$GETOUT = "javascript:self.close()";
	}


	$queryTotal = "SELECT * from equipamentos";
        $resultadoTotal = mysql_query($queryTotal);
        $linhasTotal = mysql_num_rows($resultadoTotal);

 	$query = "select c.comp_inv as etiqueta, c.comp_inst as instituicao, c.comp_local as tipo_local, ".
			"i.inst_nome as instituicao_nome, c.comp_tipo_equip as tipo, ".
			"t.tipo_nome as equipamento, s.situac_cod as situac_cod, ".
			"l.local as locais, l.loc_id as local_cod, h.hist_data as data, extract(day from hist_data)as DIA, ".
			"extract(month from hist_data)as MES, extract(year from hist_data)as ANO ".

			"from equipamentos as c, instituicao as i, ".
			"localizacao as l, historico as h, tipo_equip as t, situacao as s ".
			"where ".
			"c.comp_inv = h.hist_inv and c.comp_inst=h.hist_inst and h.hist_local=l.loc_id and h.hist_inv= ".$_REQUEST['comp_inv']." ".
			"and h.hist_inst=".$_REQUEST['comp_inst']." and i.inst_cod=h.hist_inst and c.comp_tipo_equip=t.tipo_cod ".
			"and c.comp_situac=s.situac_cod ".
			"order by data desc";

	$resultado = mysql_query($query);
	$resultado2 = mysql_query($query);
	$linhas = mysql_num_rows($resultado);
	$row = mysql_fetch_array($resultado);

        if ($linhas == 0)
	{
		echo mensagem("Não foi encontrado nenhum equipamento cadastrado no sistema.");
		exit;
	} else
        if ($linhas>1){
                $texto = 'Locais';//Perfumaria: Apenas imprime o nome da coluna no plural se existirem mais de um local no histórico.
		print "<table border='0' cellspacing='1' summary=''";
		print "<TR>";
		print "<TD colspan='3' align='left' ><B>Foram econtrados ".$linhas." registros de localização para esse equipamento.</B></TD>";
		print "</tr>";
		print "</table>";
		print "<tr><p><TD bgcolor='".$cor1."'><b>Etiqueta:\t</b>".$row['etiqueta']."</TD></tr><br>";
		print "<tr><TD bgcolor='".$cor1."'><b>Unidade:\t</b>".$row['instituicao_nome']."</TD></tr><br>";
		print "<tr><TD bgcolor='".$cor1."'><b>Tipo de equipamento:</b>\t".$row['equipamento']."</TD></tr><br></p>";

	}
	else {
		$texto = 'Local';
		print "<TR><TD bgcolor='".$cor1."'><B>Foi encontrado somente 1 registro de localização para este equipamento.</B></TD></TR>";
		print "<tr><p><TD bgcolor='".$cor1."'><b>Etiqueta:\t</b>".$row['etiqueta']."</TD></tr><br>";
		print "<tr><TD bgcolor='".$cor1."'><b>Unidade:\t</b>".$row['instituicao_nome']."</TD></tr><br>";
		print "<tr><TD bgcolor='".$cor1."'><b>Tipo de equipamento:</b>\t".$row['equipamento']."</TD></tr><br></p>";
	}
	print "</TD>";

        print "<td class='line'>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%'>";
        print "<TR class='header'><td class='line'><b>".$texto."</TD><td class='line'><b>Data</TD>";
        $i=0;
        $j=2;

	while ($row = mysql_fetch_array($resultado2)) {
		if ($j % 2)
                {
			if ((($row['situac_cod']==4)or ($row['situac_cod']==5))and $i==0) { //Equipamento trocado ou furtado!!
				$color='#FF0000';
				$alerta = "style='{color:white;}'";
				$trClass = "lin_alerta";
			} else {
				$color =  BODY_COLOR;
                		$alerta = "";
				$trClass = "lin_par";
			}
		}
		else
		{
			if ((($row['situac_cod']==4)or ($row['situac_cod']==5))and $i==0) { //Equipamento trocado ou furtado!!
				$color='#FF0000';
				$alerta = "style='{color:white;}'";
				$trClass = "lin_alerta";
			} else {
				$color =  'white';
                		$alerta = "";
				$trClass = "lin_impar";
			}
		}
		$j++;
		if ($i==0) {
			$atualmente = "<font ".$alerta.">[Local Atual]</font>";  //Perfumaria: Serve para identificar para o usuário o local atual onde o equipamento está!!!
		} else
			$atualmente = '';

		print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";
		print "<td class='line'><a ".$alerta." onClick= \"javascript: window.opener.location.href='mostra_consulta_comp.php?comp_local=".$row['local_cod']."&comp_tipo_equip=".$row['tipo']."&ordena=fab_nome,modelo,local,etiqueta'\">".$row['locais']." </a><b><font color='green'>".$atualmente."</font></b></td>";
		print "<td class='line'><font ".$alerta.">".$row['DIA']."/".$row['MES']."/".$row['ANO']."</font></td>";

                print "</TR>";
                $i++;
	}
		print "<tr><td colspan='4'>&nbsp;</td></tr>";
		print "<tr><td colspan='4' align='center'><input type='button' class='minibutton' value='".$BT_TEXT."' onClick=\"".$GETOUT."\"</td></tr>";

	print "</TABLE>";

print "</BODY>";
print "</HTML>";
?>