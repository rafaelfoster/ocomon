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
  */

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	$cab = new headers;
	$cab->set_title(HTML_TITLE);

	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);

        $hoje = date("Y-m-d H:i:s");


        $cor  = TD_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;




		$queryA = "SELECT

			mold.mold_marca as padrao,
			mold.mold_inv as etiqueta, mold.mold_sn as serial, mold.mold_nome as nome,
 			mold.mold_nf as nota,

 			mold.mold_coment as comentario, mold.mold_valor as valor, mold.mold_data_compra as
			data_compra, mold.mold_ccusto as ccusto,

			inst.inst_nome as instituicao, inst.inst_cod as cod_inst,

			equip.tipo_nome as equipamento, equip.tipo_cod as equipamento_cod,

			t.tipo_imp_nome as impressora, t.tipo_imp_cod as impressora_cod,

			loc.local as local, loc.loc_id as local_cod,



			proc.mdit_fabricante as fabricante_proc, proc.mdit_desc as processador, proc.mdit_desc_capacidade as clock, proc.mdit_cod as cod_processador,
			hd.mdit_fabricante as fabricante_hd, hd.mdit_desc as hd, hd.mdit_desc_capacidade as hd_capacidade,hd.mdit_cod as cod_hd,
			vid.mdit_fabricante as fabricante_video, vid.mdit_desc as video, vid.mdit_cod as cod_video,
			red.mdit_fabricante as rede_fabricante, red.mdit_desc as rede, red.mdit_cod as cod_rede,
			modm.mdit_fabricante as fabricante_modem, modm.mdit_desc as modem, modm.mdit_cod as cod_modem,
			cd.mdit_fabricante as fabricante_cdrom, cd.mdit_desc as cdrom, cd.mdit_cod as cod_cdrom,
			grav.mdit_fabricante as fabricante_gravador, grav.mdit_desc as gravador, grav.mdit_cod as cod_gravador,
			dvd.mdit_fabricante as fabricante_dvd, dvd.mdit_desc as dvd, dvd.mdit_cod as cod_dvd,
			mb.mdit_fabricante as fabricante_mb, mb.mdit_desc as mb, mb.mdit_cod as cod_mb,
			memo.mdit_desc as memoria, memo.mdit_cod as cod_memoria,
			som.mdit_fabricante as fabricante_som, som.mdit_desc as som, som.mdit_cod as cod_som,


			fab.fab_nome as fab_nome, fab.fab_cod as fab_cod,

			fo.forn_cod as fornecedor_cod, fo.forn_nome as fornecedor_nome,

			model.marc_cod as modelo_cod, model.marc_nome as modelo,

			pol.pole_cod as polegada_cod, pol.pole_nome as polegada_nome,

			res.resol_cod as resolucao_cod, res.resol_nome as resol_nome


		FROM ((((((((((((((((((moldes as mold
			left join  tipo_imp as t on	t.tipo_imp_cod = mold.mold_tipo_imp)
			left join polegada as pol on mold.mold_polegada = pol.pole_cod)
			left join resolucao as res on mold.mold_resolucao = res.resol_cod)
			left join fabricantes as fab on fab.fab_cod = mold.mold_fab)
			left join fornecedores as fo on fo.forn_cod = mold.mold_fornecedor)

			left join modelos_itens as proc on proc.mdit_cod = mold.mold_proc)
			left join modelos_itens as hd on hd.mdit_cod = mold.mold_modelohd)
			left join modelos_itens as vid on vid.mdit_cod = mold.mold_video)
			left join modelos_itens as red on red.mdit_cod = mold.mold_rede)
			left join modelos_itens as modm on modm.mdit_cod = mold.mold_modem)
			left join modelos_itens as cd on cd.mdit_cod = mold.mold_cdrom)
			left join modelos_itens as grav on grav.mdit_cod = mold.mold_grav)
			left join modelos_itens as dvd on dvd.mdit_cod = mold.mold_dvd)
			left join modelos_itens as mb on mb.mdit_cod = mold.mold_mb)
			left join modelos_itens as memo on memo.mdit_cod = mold.mold_memo)
			left join modelos_itens as som on som.mdit_cod = mold.mold_som)

			left join instituicao as inst on inst.inst_cod = mold.mold_inst)
			left join localizacao as loc on loc.loc_id = mold.mold_local),


			marcas_comp as model, tipo_equip as equip
		WHERE

			(mold.mold_tipo_equip = equip.tipo_cod) and
			(mold.mold_marca = model.marc_cod) order by fab_nome";

        //(mold.mold_marca = $comp_marca) and

		$resultadoA = mysql_query($queryA);
        $linhasA = mysql_num_rows($resultadoA);
        $row = mysql_fetch_array($resultadoA);

    /*    if (mysql_num_rows($resultadoA)>0)
        {
                $linhasA = mysql_num_rows($resultadoA)-1;
        }
        else
        {
                $linhasA = mysql_num_rows($resultadoA);
        }

	*/


        if ($linhasA == 0)
        {
          print "<script>mensagem('Não há nenhum modelo de configuração cadastrado no sistema!'); redirect('incluir_molde.php');</script>";
		  exit;

		  print "<br>";
		print "<table border='0' cellspacing='1' summary=''";
				print "<TR>";
				print "<TD width='500' align='left' ><B>Não foi encontrado nenhum modelo de configuração cadastrado no sistema.</B></TD>";
				print "<TD width='300' align='left' ><B><a href=incluir_molde.php>Incluir modelo de configuração</a></b></td>";
				print "<TD width='224' align='left' ><B><a href=marcas_comp.php>Modelos de equipamentos</a></b></td>";
				print "</tr>";
				print "</table>";
        }
        if ($linhasA>1){
          print "<br>";
		print "<table border='0' cellspacing='1' summary=''";
				print "<TR>";
				print "<TD width='500' align='left' ><B>Foram encontrados $linhasA modelos de configurações cadastrados no sistema. </B></TD>";
				print "<TD width='300' align='left' ><B><a href=incluir_molde.php>Incluir modelo de configuração</a></b></td>";
				print "<TD width='224' align='left' ><B><a href=marcas_comp.php>Modelos de equipamentos</a></b></td>";
				print "</tr>";
				print "</table>"; }


		else
	        if ($linhasA==1){

                print "<TR><td class='line'><B>Foi encontrado somente 1 modelo de configuração cadastrado no sistema.</B></TD></TR>";
        }
		print "</TD>";

        print "<td class='line'>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
        print "<TR class='header'><td class='line'><b>Fabricante</TD><td class='line'><b>Modelo</TD><td class='line'><b>Tipo</TD><td class='line'><b>Alterar</TD><td class='line'><b>Excluir</TD>";
        $i=0;
        $j=2;
  if (($resultadoA = mysql_query($queryA)) && (mysql_num_rows($resultadoA) > 0) ) {
  while ($row = mysql_fetch_array($resultadoA)) {


                if ($j % 2)
                {
                        $color =  BODY_COLOR;
						$trClass = "lin_par";
                }
                else
                {
                        $color = white;
						$trClass = "lin_impar";
                }
                $j++;
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
                ?>

                <td class='line'><?php print $row["fab_nome"]?></td>
                <td class='line'><?php  print $row["modelo"]?></td>

				<td class='line'><?php  print $row["equipamento"]?></td>

                <td class='line'>Alterar</TD>
                <td class='line'>Excluir</TD>


                <?php 
                  /*      $problemas = mysql_result($resultado,$i,1);
                        $query = "SELECT * FROM problemas WHERE prob_id='$problemas'";
                        $resultado3 = mysql_query($query);   */
                print "</TR>";
                $i++;

        }
       }
        print "</TABLE>";


        print "<TABLE border='0' cellpadding='0' cellspacing='0' align='center' width='100%' bgcolor='$cor3'>";
        print "<TR width=100%>";
        print "&nbsp;";
        print "</TR>";

        print "<td class='line'>";


?>
</BODY>
</HTML>
