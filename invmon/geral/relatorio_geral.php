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
	

?>

<HTML>
<BODY>

<?


	$col1 = 121; 
	$col2 = 121;
	$col3 = 121;
	$col4 = 121;

 
 
 $query = "SELECT c.comp_inv as etiqueta, c.comp_sn as serial, c.comp_nome as nome, 
 			c.comp_nf as nota, inst.inst_nome as instituicao, inst.inst_cod as cod_inst,
 			c.comp_coment as comentario, c.comp_valor as valor, c.comp_data_compra as
			data_compra, c.comp_ccusto as ccusto, c.comp_situac as situacao,
			equip.tipo_nome as equipamento,
			t.tipo_imp_nome as impressora, loc.local, mb_fabricante as mb_fabricante, 
			mb_modelo as mb_modelo, proc.proc_fabricante as proc_fabricante, 
			proc.proc_modelo as proc_modelo, proc.proc_clock as proc_clock, 
			me.memo_desc as memoria, vid.vid_fabricante as vid_fabricante, 
			vid.vid_modelo as vid_modelo, som.som_fabricante as som_fabricante, 
			som.som_modelo as som_modelo, rede.rede_fabricante as rede_fabricante, 
			rede.rede_modelo as rede_modelo, hd.hd_modelo as hd_modelo, 
			hd.hd_fabricante as hd_fabricante, hd.hd_capacidade as hd_capacidade, 
			mo.mod_fabricante as mod_fabricante, mo.mod_modelo as mod_modelo, 
			cd.cd_fabricante as cd_fabricante, cd.cd_velocidade as cd_velocidade,
			dvd.dvd_fabricante as dvd_fabricante, dvd.dvd_velocidade as dvd_velocidade, 
			gr.grav_fabricante as grav_fabricante, gr.grav_velocidade as grav_velocidade, 
			fab.fab_nome as fab_nome, fo.forn_cod as fornecedor_cod, 
			fo.forn_nome as fornecedor_nome, model.marc_cod as modelo_cod, model.marc_nome as modelo,
			pol.pole_cod as polegada_cod, pol.pole_nome as polegada_nome, 
			res.resol_cod as resolucao_cod, res.resol_nome as resol_nome,
			sit.situac_cod as situac_cod, sit.situac_nome as situac_nome
			   

		FROM (((((((((((((((((computadores as c left join  tipo_imp as t on 
			t.tipo_imp_cod = c.comp_tipo_imp) left join polegada as pol on c.comp_polegada
			 = pol.pole_cod) left join resolucao as res on c.comp_resolucao = res.resol_cod)
			left join mbs as mb on c.comp_mb = mb.mb_cod) left join cdroms as cd on cd.cd_cod =
			c.comp_cdrom) left join dvds as dvd on dvd.dvd_cod = c.comp_dvd) left join fabricantes
			as fab on fab.fab_cod = c.comp_fab) left join fornecedores as fo on fo.forn_cod =
			c.comp_fornecedor) left join gravadores as gr on gr.grav_cod = c.comp_grav) 
			left join hds as hd on hd.hd_cod = c.comp_modelohd) left join memorias as me 
			on me.memo_cod = c.comp_memo) left join modens as mo on mo.mod_cod = 
			c.comp_modem) left join processadores as proc on proc.proc_cod = c.comp_proc) 
			left join rede_placas as rede on rede.rede_cod = c.comp_rede) left join 
			som_placas as som on som.som_cod = c.comp_som) left join vid_placas as vid on
			vid.vid_cod = c.comp_video) left join situacao as sit on sit.situac_cod = c.comp_situac),
			
			localizacao as loc, instituicao as inst, marcas_comp as model, tipo_equip as equip
		WHERE (c.comp_local = loc.loc_id) and
			(c.comp_inst = inst.inst_cod) and (c.comp_marca = model.marc_cod) and 
			(c.comp_tipo_equip = equip.tipo_cod) order by equipamento, modelo, etiqueta
			";


		$resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);
		$row = mysql_fetch_array($resultado);  //***********
              
			  
        if ($linhas == 0)
        {
                echo mensagem("Não foi encontrado nenhum equipamento cadastrado no sistema.");
                exit;
        }
        if ($linhas>1){
                print "<table border='0' cellspacing='1' summary=''";
				print "<TR>";
				print "<td witdh='600' align='left'><P STYLE=page-break-before: always><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt><b>InvMon - Relatório Geral de equipamentos cadastrados - Total de equipamentos: $linhas.</b>\t<a href=relatorios.php>[ Outros relatórios ]</a>.\t<a href=abertura.php>[ Início ]</a></FONT></FONT></P></td>";
				//print "<TD width='200' align='left' ><B><a href=incluir_computador.php>Incluir equipamento</a></b></td>";
				//print "<TD width='224' align='left' ><B><a href=marcas_comp.php>Incluir modelo de equipamento</a></b></td>";
				print "</tr>";
				print "</table>"; 
				print "<hr width=80% align=left>";}
		
		
		else
                print "<TR><TD bgcolor=$cor1><B>Foi encontrado somente 1 equipamento cadastrado no sistema.</B></TD></TR>";
        print "</TD>";

        print "<td class='line'>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='$cor'>";
       //print "<TR><TD bgcolor=$cor1><a href=mostra_computadores.php?ordena=etiqueta><b>Etiqueta</TD><TD bgcolor=$cor1><a href=mostra_computadores.php?ordena=instituicao,etiqueta><b>Unidade</TD><TD bgcolor=$cor1></a><a href=mostra_computadores.php?ordena=equipamento,modelo><b>Tipo</TD><TD bgcolor=$cor1></a><a href=mostra_computadores.php?ordena=fab_nome,modelo><b>Modelo</TD><TD bgcolor=$cor1></a><a href=mostra_computadores.php?ordena=local><b>Localização</TD><TD bgcolor=$cor1></a><b>Alterar</TD><TD bgcolor=$cor1><b>Excluir</TD>";        
        $i=0;
        $j=2;
  if (($resultado = mysql_query($query)) && (mysql_num_rows($resultado) > 0) ) {
  while ($row = mysql_fetch_array($resultado)) {


                if ($j % 2)
                {
                        $color =  white;//BODY_COLOR;
                }
                else
                {
                        $color = white;
                }
                $j++;
                ?>

<?//STYLE="page-break-inside: avoid"




?>

<TABLE WIDTH=80% BORDER=0 CELLPADDING=4 CELLSPACING=0 >
	<link rel=stylesheet type="text/css" href="menu.css">
	<COL WIDTH=10%>
	<COL WIDTH=20%>
	<COL WIDTH=10%>
	<COL WIDTH=20%>
	
	
		<TR VALIGN=TOP>
			<TD WIDTH=10%>
				<P ALIGN=LEFT><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt">TIPO DE EQUIPAMENTO:</FONT></FONT></P>
			</TD>
			<TH WIDTH=10%>
				<P ALIGN=LEFT><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt"><?print $row['equipamento']?></FONT></FONT></P>
			</TH>
			<TD WIDTH=10%>
				<P ALIGN=LEFT><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt">FABRICANTE:</FONT></FONT></P>
			</TD>
			<TH WIDTH=10%>
				<P ALIGN=LEFT><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt"><?print $row['fab_nome']?></FONT></FONT></P>
			</TH>
		</TR>
	
	
	
	
		<TR VALIGN=TOP>
			<TD WIDTH=20%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt">Nº DA ETIQUETA:</FONT></FONT></P>
			</TD>
			<TH WIDTH=20%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt"><?print $row['etiqueta']?></FONT></FONT></P>
			</TH>
			<TD WIDTH=20%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt">Nº DE SÉRIE:</FONT></FONT></P>
			</TD>
			<TH WIDTH=20%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt"><?print $row['serial']?></FONT></FONT></P>
			</TH>
		</TR>
		<TR VALIGN=TOP>
			<TD WIDTH=10%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt">MODELO</FONT></FONT></P>
			</TD>
			<TH WIDTH=10%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt"><?print $row['modelo']?></FONT></FONT></P>
			</TH>
			<TD WIDTH=10%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt">LOCALIZAÇÃO:</FONT></FONT></P>
			</TD>
			<TH WIDTH=10%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt"><?print $row['local']?></FONT></FONT></P>
			</TH>
		</TR>
		<TR VALIGN=TOP>
			<TD WIDTH=20%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt">SITUAÇÃO:</FONT></FONT></P>
			</TD>
			<TH WIDTH=20%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt"><?print $row['situac_nome']?></FONT></FONT></P>
			</TH>
			<TD WIDTH=20%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><BR>
				</P>
			</TD>
			<TH WIDTH=20%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><BR>
				</P>
			</TH>
		</TR>
		<TR VALIGN=TOP>
			<TD WIDTH=20%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt">INSTITUIÇÃO:</FONT></FONT></P>
			</TD>
			<TH WIDTH=20%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><FONT FACE="Arial, sans-serif"><FONT SIZE=2 STYLE="font-size: 9pt"><?print $row['instituicao']?></FONT></FONT></P>
			</TH>
			<TD WIDTH=20%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><BR>
				</P>
			</TD>
			<TH WIDTH=20%>
				<P ALIGN=LEFT STYLE="font-weight: medium"><BR>
				</P>
			</TH>
		</TR>
	
</TABLE>		
		<hr width=80% align=left>
			

                <?
                print "<hr width=80% align=left>";
				
				//print "</TR>";
				
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
