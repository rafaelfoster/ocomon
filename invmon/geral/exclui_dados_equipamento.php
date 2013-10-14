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
  */

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);
	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);

        if ($s_nivel!=1)
        {
                echo "<META HTTP-EQUIV=REFRESH   CONTENT=\"0;
                        URL=../index.php\">";
        } else {

		 $query = "SELECT c.comp_inv as etiqueta, c.comp_sn as serial, c.comp_nome as nome, 
 			c.comp_nf as nota, inst.inst_nome as instituicao, inst.inst_cod as cod_inst,
 			c.comp_coment as comentario, c.comp_valor as valor, c.comp_data as data_cadastro, 
			c.comp_data_compra as data_compra, c.comp_ccusto as ccusto, c.comp_situac as situacao, 
			c.comp_local as tipo_local, loc.loc_reitoria as reitoria_cod, reit.reit_nome as reitoria,
			c.comp_mb as tipo_mb, c.comp_proc as tipo_proc,
			c.comp_tipo_equip as tipo, c.comp_memo as tipo_memo, c.comp_video as tipo_video,
			c.comp_modelohd as tipo_hd, c.comp_modem as tipo_modem, c.comp_cdrom as tipo_cdrom,
			c.comp_dvd as tipo_dvd, c.comp_grav as tipo_grav, c.comp_resolucao as tipo_resol,
			c.comp_polegada as tipo_pole, c.comp_tipo_imp as tipo_imp,
			equip.tipo_nome as equipamento, c.comp_rede as tipo_rede, c.comp_som as tipo_som,
			t.tipo_imp_nome as impressora, loc.local, 
			
			
			
			proc.mdit_fabricante as fabricante_proc, proc.mdit_desc as processador, proc.mdit_desc_capacidade as clock, proc.mdit_cod as cod_processador,
			hd.mdit_fabricante as fabricante_hd, hd.mdit_desc as hd, hd.mdit_desc_capacidade as hd_capacidade,hd.mdit_cod as cod_hd,
			vid.mdit_fabricante as fabricante_video, vid.mdit_desc as video, vid.mdit_cod as cod_video,
			red.mdit_fabricante as rede_fabricante, red.mdit_desc as rede, red.mdit_cod as cod_rede,
			mod.mdit_fabricante as fabricante_modem, mod.mdit_desc as modem, mod.mdit_cod as cod_modem,
			cd.mdit_fabricante as fabricante_cdrom, cd.mdit_desc as cdrom, cd.mdit_cod as cod_cdrom,
			grav.mdit_fabricante as fabricante_gravador, grav.mdit_desc as gravador, grav.mdit_cod as cod_gravador,
			dvd.mdit_fabricante as fabricante_dvd, dvd.mdit_desc as dvd, dvd.mdit_cod as cod_dvd,
			mb.mdit_fabricante as fabricante_mb, mb.mdit_desc as mb, mb.mdit_cod as cod_mb,
			memo.mdit_desc as memoria, memo.mdit_cod as cod_memoria,
			som.mdit_fabricante as fabricante_som, som.mdit_desc as som, som.mdit_cod as cod_som, 

			fab.fab_nome as fab_nome, fab.fab_cod as fab_cod, fo.forn_cod as fornecedor_cod, 
			fo.forn_nome as fornecedor_nome, model.marc_cod as modelo_cod, model.marc_nome as modelo,
			pol.pole_cod as polegada_cod, pol.pole_nome as polegada_nome, 
			res.resol_cod as resolucao_cod, res.resol_nome as resol_nome,
			sit.situac_cod as situac_cod, sit.situac_nome as situac_nome,
			date_add(c.comp_data_compra, interval tmp.tempo_meses month)as vencimento
		
		FROM ((((((((((((((((((equipamentos as c left join  tipo_imp as t on 
			t.tipo_imp_cod = c.comp_tipo_imp) left join polegada as pol on c.comp_polegada
			 = pol.pole_cod) left join resolucao as res on c.comp_resolucao = res.resol_cod)
			left join fabricantes as fab on fab.fab_cod = c.comp_fab) 
			left join fornecedores as fo on fo.forn_cod = c.comp_fornecedor) 
			left join situacao as sit on sit.situac_cod = c.comp_situac)
			left join tempo_garantia as tmp on tmp.tempo_cod =c.comp_garant_meses)
			
			left join modelos_itens as proc on proc.mdit_cod = c.comp_proc)
			left join modelos_itens as hd on hd.mdit_cod = c.comp_modelohd)
			left join modelos_itens as vid on vid.mdit_cod = c.comp_video)
			left join modelos_itens as red on red.mdit_cod = c.comp_rede)
			left join modelos_itens as mod on mod.mdit_cod = c.comp_modem)
			left join modelos_itens as cd on cd.mdit_cod = c.comp_cdrom)
			left join modelos_itens as grav on grav.mdit_cod = c.comp_grav)
			left join modelos_itens as dvd on dvd.mdit_cod = c.comp_dvd)
			left join modelos_itens as mb on mb.mdit_cod = c.comp_mb)
			left join modelos_itens as memo on memo.mdit_cod = c.comp_memo)
			left join modelos_itens as som on som.mdit_cod = c.comp_som),

			
			localizacao as loc, instituicao as inst, marcas_comp as model, tipo_equip as equip,
			reitorias as reit
            WHERE
 			((c.comp_local = loc.loc_id) and
			(c.comp_inst = inst.inst_cod) and (c.comp_marca = model.marc_cod) and 
			(c.comp_tipo_equip = equip.tipo_cod) and (loc.loc_reitoria = reit.reit_cod) and
			(c.comp_inv = $comp_inv) and (inst.inst_cod = $comp_inst))
			
			";



        $resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);
        $row = mysql_fetch_array($resultado);

		if (!(empty($row['ccusto'])))  
		{
		$CC =  $row['ccusto'];   		
		$query2 = "select * from planejamento.CCUSTO where codigo= $CC";
		$resultado2 = mysql_query($query2);
		$resultado3 = mysql_result($resultado2,0,4);
		//$row2 = mysql_fetch_array($resultado2);
		$centroCusto = "$resultado3";
		}


      //  echo "$query<br>";
      //  echo "$queryMonitor<br>";


########################################################################################################



?>


<TABLE border="0"  align="left" width="100%" bgcolor=<?print BODY_COLOR?>>
        
		
		<TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Tipo de equipamento:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["equipamento"]?></TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Fabricante:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["fab_nome"]?></TD>
       </TR>
        
		<TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Código de Inventário:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["etiqueta"]?></TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Número de Série:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["serial"]?></TD>
       </TR>		
		<TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Modelo:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["modelo"]?></TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Local:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["local"]?></TD>
       </TR>				

	
	<tr><td colspan='4'></td></tr>
	<TR>
	 <td colspan='4'><b> Dados complementares - (Esses campos só estarão preenchidos para equipamentos do tipo COMPUTADOR) </b></td>
	</TR>
	<tr><td colspan='4'></td></tr>
		
		<TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Nome do computadr:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["nome"]?></TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>MB:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["mb"]?></TD>
       </TR>				

		<TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Processador:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["processador"].$row["clock"]." MHZ"?></TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Memória:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["memoria"]." MB"?></TD>
       </TR>				


		<TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Placa de vídeo:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["fabricante_video"].$row["video"]?></TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Placa de som:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["fabricante_som"].$row["som"]?></TD>
       </TR>	

		<TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>HD:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["fabricante_hd"]." ".$row["hd"]." ".$row["hd_capacidade"]." GB"?></TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Unidade CD-ROM:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["fabricante_cdrom"].$row["cdrom"]?></TD>
       </TR>				


		<TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Unidade Gravadora de CD:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["fabricante_gravador"].$row["gravador"]?></TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Unidade de DVD:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["fabricante_dvd"].$row["dvd"]?></TD>
       </TR>				

	<tr><td colspan='4'></td></tr>
	<tr> 
		<td colspan='4'><b> Dados complementares - (Algum desses campos só estará preenchido se o equipamento for IMPRESSORA ou MONITOR ou SCANNER) </b></td>
	</TR>
	<tr><td colspan='4'></td></tr>

		<TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Tipo de Impressora:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["impressora"]?></TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Monitor:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["polegada_nome"]?></TD>
		</tr>
		<tr>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Scanner:</b></TD>
                <TD colspan='3' width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["resolucao_nome"]?></TD>
        
       </TR>				

	<tr><td colspan='4'></td></tr>			
	<tr> 
		<td colspan='4' <b> Dados complementares - CONTÁBEIS: </b></td>
	</TR>
	<tr><td colspan='4'></td></tr>		
		<TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Unidade:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["instituicao"]?></TD>
               
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Centro de Custo:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $centroCusto?></TD>
		</tr>

		<TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Fornecedor:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["fornecedor_nome"]?></TD>
               
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Nota Fiscal:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["nota"]?></TD>
		</tr>
		
		<TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Valor:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["valor"]?></TD>
               
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Data da Compra:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["data_compra"]?></TD>
		</tr>
		

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Data do cadastro:</b></TD>
                <TD colspan='3' width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print datab($hoje);?></TD>
        </TR>
		
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Comentário:</b></TD>
                <TD colspan='3' width="80%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row["comentario"]?></TD>
        </TR>		



		
                        <?
                     
                                $sql = "DELETE FROM historico WHERE hist_inv = '$comp_inv' and hist_inst= '$comp_inst'";
								$resultadoSQL = mysql_query($sql);
								
								$query = "DELETE FROM equipamentos WHERE comp_inst='$comp_inst' and comp_inv='$comp_inv'";
                                $resultado = mysql_query($query);

                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao excluir registro do sistema.";

                                }
                                else
                                {
                                        $aviso = "OK. Registro excluido com sucesso.";
										
										$texto = "Excluído: Etiqueta= $comp_inv, Unidade= $comp_inst";
										geraLog(LOG_PATH.'invmon.txt',$hojeLog,$s_usuario,'exclui_dados_computador.php',$texto);	                                   
								}
                                $origem = "mostra_computadores.php";
                                session_register("aviso");
                                session_register("origem");
                               // echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
             }


        ?>
		<script language="javascript">
		<!--
			mensagem('<?print $aviso;?>');
			history.back();
			
		//-->
		</script>

</TABLE>


</body>
</html>