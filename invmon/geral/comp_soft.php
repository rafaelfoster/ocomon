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
  */session_start();

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;

	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);


	$hojeLog = date ("d-m-Y H:i:s");


	if ( isset($_GET['acao']) ) {
		if ($_GET['acao'] == "del"){
			$sql = "delete from hw_sw where hws_sw_cod=".$_GET['id']." and hws_hw_cod = ".$_GET['comp_inv']." and hws_hw_inst = ".$_GET['comp_inst']."";
			$commit = mysql_query($sql);
			if ($commit==0) {
			    $aviso = "ERRO NA EXCLUSÃO DO REGISTRO!";
			} else
				$aviso = "Arquivo excluído com sucesso!";

		} else
		if ($_GET['acao']=="reseta") {
			$sql = "delete from hw_sw where hws_hw_cod =".$_GET['comp_inv']." and hws_hw_inst =".$_GET['comp_inst']."";
			$commit = mysql_query($sql);
			if ($commit==0) {
			    $aviso = "ERRO NA EXCLUSÃO DO REGISTRO!";
			} else
				$aviso = "Arquivo excluído com sucesso!";

		} else
		if ($_GET['acao']=="carrega") {
			$erro = false;
			$sqlA = "select * from sw_padrao order by swp_sw_cod ";
			$commitA = mysql_query($sqlA);
			while ($rowA = mysql_fetch_array($commitA)){

				$sqlTemp = "select * from hw_sw where hws_sw_cod = ".$rowA['swp_sw_cod']." and hws_hw_cod =".$_GET['comp_inv']." and ".
							"hws_hw_inst =".$_GET['comp_inst']."";
				$commitTemp = mysql_query($sqlTemp);
				$regs = mysql_num_rows ($commitTemp);
				if ($regs ==0) {

					$sqlB = "insert into hw_sw (hws_sw_cod, hws_hw_cod, hws_hw_inst) values (".$rowA["swp_sw_cod"].", ".$_GET['comp_inv'].", ".$_GET['comp_inst'].")";
					$commitB = mysql_query($sqlB);
					if ($commitB==0) {
					    $erro = true;
					}
				}
			}
			if (!$erro) {
			    $aviso = "Dados cadastrados com sucesso!";
			} else {
				$aviso = "Ocorreram problemas na tentativa de cadastrar os softwares!";
			}
		} else

		if ($_GET['acao'] == "alt") {
		 	$aviso = "Opção não disponível!";
		 //--
		}

		print "<script>mensagem('".$aviso."'); redirect('comp_soft.php?comp_inv=".$_GET['comp_inv']."&comp_inst=".$_GET['comp_inst']."');</script>";
		//print $sqlB;

		//unset($_GET['acao']);
	}
	else
	{

		$sql = "select s.*, l.*, c.*, f.*, h.* from softwares as s, licencas as l, categorias as c,
				fabricantes as f, hw_sw as h
				where s.soft_tipo_lic = l.lic_cod and s.soft_cat = c.cat_cod and s.soft_fab = f.fab_cod
				and h.hws_sw_cod = s.soft_cod and h.hws_hw_cod = ".$_GET['comp_inv']." and h.hws_hw_inst =".$_GET['comp_inst']." ".
				"order by f.fab_nome, s.soft_desc";
		$commit = mysql_query($sql);
		//$rowA = mysql_fetch_array($commit);
		$linhas = mysql_num_rows($commit);

		if ($linhas == 0)
		{
			print "<b><p align=center>Nenhum software cadastrado para esse equipamento!</b><br><input type='button' value='Adicionar' class='minibutton' onClick= \"javascript:popup_alerta('incluir_hws.php?popup=true&comp_inv=".$_GET['comp_inv']."&comp_inst=".$_GET['comp_inst']."')\"></p>";
			print "<p align=center><input type='button' value='Carregar' class='minibutton' onClick=\"javascript:confirmaAcao('Tem certeza que deseja carregar a configuração padrão?','".$_SERVER['PHP_SELF']."', 'acao=carrega&comp_inv=".$_GET['comp_inv']."&comp_inst=".$_GET['comp_inst']."&popup=true')\"></p>";
			exit;
		} else

		if ($linhas>0) {
			print "<br>";
			print "<table class=corpo>";
			print "<tr>";
			print "<TD width='350' align='left'><B>Equipamento ".$_GET['comp_inv'].": <font color='red'>".$linhas."</font> software(s).</B></TD>";
			print "<TD width='200' align='left'><input type='button' value='Adicionar' class='minibutton' onClick= \"javascript:popup_alerta('incluir_hws.php?popup=true&comp_inv=".$_GET['comp_inv']."&comp_inst=".$_GET['comp_inst']."')\"></td>";
			print "<TD width='200' align='left'><input type='button' value='Carregar' class='minibutton' onClick=\"javascript:confirmaAcao('Tem certeza que deseja carregar a configuração padrão?','".$_SERVER['PHP_SELF']."', 'acao=carrega&comp_inv=".$_GET['comp_inv']."&comp_inst=".$_GET['comp_inst']."&popup=true')\"></TD>";

			print "<TD width='200' align='left'><input type='button' value='Resetar' class='minibutton' onClick=\"javascript:confirmaAcao('Tem certeza que deseja excluir todos softwares dessa configuração?','".$_SERVER['PHP_SELF']."', 'acao=reseta&comp_inv=".$_GET['comp_inv']."&comp_inst=".$_GET['comp_inst']."&popup=true')\"></TD>";
			print "</tr>";
			print "</table><br>";
		}

		print "<table class=corpo2 >";
		print "<TR class='header'><td class='line'><b>Fabricante</TD><td class='line'><b>Software</TD><td class='line'><b>Versao</TD><td class='line'><b>Categoria</TD><td class='line'><b>Licença</TD><td class='line'><b>Excluir</TD>";

		$j=2;
		while ($row = mysql_fetch_array($commit))
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

			print "<TR class='".$trClass."'>";
			print "<td class='line'>".$row['fab_nome']."</TD>";
			print "<td class='line'>".$row['soft_desc']."</TD>";
			print "<td class='line'>".$row['soft_versao']."</TD>";
			print "<td class='line'>".$row['cat_desc']."</TD>";
			print "<td class='line'>".$row['lic_desc']."</TD>";
			print "<td class='line'><a onClick=\"javascript:confirmaAcao('Deletar ".$row['fab_nome']." ".$row['soft_desc']."?','".$_SERVER['PHP_SELF']."', 'acao=del&id=".$row['soft_cod']."&comp_inv=".$_GET['comp_inv']."&comp_inst=".$_GET['comp_inst']."&popup=true')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></TD>";
			print "</TR>";
		}

		print "<tr><td colspan='6'>&nbsp;</td></tr>";
		print "<tr><td colspan='6' align='center'><input type='button' class='minibutton' value='Fechar' onClick=\"javascript:self.close()\"</td></tr>";
		print "</TABLE>";
	}
	$cab->set_foot();

?>