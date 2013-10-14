<?php /*                        Copyright 2005 Flávio Ribeiro

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
*/	session_start();

  	include ("../../includes/include_geral.inc.php");
  	include ("../../includes/functions/browser_detection.php");

	$browser = browser_detection('full');
	$_SESSION['s_browser'] = $browser[0];

	GLOBAL $conec;
	$conec = new conexao;
	$conec->conecta('MYSQL');


	if (AUTH_TYPE == "LDAP") {
		$conec->conLDAP(LDAP_HOST, LDAP_DOMAIN, LDAP_DN, LDAP_PASSWORD);
		$conecSec = new conexao; //Para testar no LDAP Labin
		$conecSec->conLDAP(LDAP_HOST, LDAP_DOMAIN_SEC, LDAP_DN, LDAP_PASSWORD);

		if ((senha_ldap($_POST['login'],$_POST['password'],'usuarios')=="ok") && ($conec->userLDAP($_POST['login'],$_POST['password']) || $conecSec->userLDAP($_POST['login'],$_POST['password'])))
		{
		        $s_usuario=$_POST['login'];
		        $s_senha=$_POST['password'];

			$queryOK = "SELECT u.*, n.*,s.* FROM usuarios u left join sistemas as s on u.AREA = s.sis_id ".
							"left join nivel as n on n.nivel_cod =u.nivel WHERE u.login = '".$_POST['login']."'";

			$resultadoOK = mysql_query($queryOK) or die('IMPOSSÍVEL ACESSAR A BASE DE DADOS DE USUÁRIOS: LOGIN.PHP');
			$row = mysql_fetch_array($resultadoOK);
			$s_nivel = $row['nivel'];

			if ($s_nivel<4){ //Verifica se não está desabilitado
				$s_logado=1;
			}

			$s_nivel_desc = $row['nivel_nome'];
			$s_area = $row['AREA'];
			$s_uid = $row['user_id'];
			$s_area_admin =  $row['user_admin'];
			$s_screen = $row['sis_screen'];

			/*VERIFICA EM QUAIS ÁREAS O USUÁRIO ESTÁ CADASTRADO*/
			$qryUa = "SELECT * FROM usuarios_areas where uarea_uid=".$s_uid.""; //and uarea_sid=".$s_area."
			$execUa = mysql_query($qryUa) or die('IMPOSSÍVEL ACESSAR A BASE DE USUÁRIOS 02: LOGIN.PHP');
			$uAreas = "".$s_area.",";
			while ($rowUa = mysql_fetch_array($execUa)){
				$uAreas.=$rowUa['uarea_sid'].",";
			}
			$uAreas = substr($uAreas,0,-1);
			$s_uareas = $uAreas;

			/*CHECA QUAIS OS MÓDULOS PODEM SER ACESSADOS PELAS ÁREAS QUE O USUÁRIO PERTENCE*/
			$qry = "SELECT * FROM permissoes where perm_area in (".$uAreas.")";
			$exec = mysql_query($qry) or die('IMPOSSÍVEL ACESSAR A BASE DE PERMISSÕES: LOGIN.PHP');

			while($row_perm = mysql_fetch_array($exec)){
				$s_permissoes[]=$row_perm['perm_modulo'];
			}
			$s_ocomon = 0;
			$s_invmon = 0;
			for ($i=0;$i<count($s_permissoes); $i++){
				if($s_permissoes[$i] == 1) $s_ocomon = 1;
				if($s_permissoes[$i] == 2) $s_invmon = 1;
			}

			$sqlPrefs = "SELECT * FROM uprefs WHERE upref_uid = ".$s_uid."";
			$execPrefs = mysql_query($sqlPrefs);
			$rowPref = mysql_fetch_array($execPrefs);			
			
			
			$sqlFormatBar = "SELECT * FROM config";
			$execFormatBar = mysql_query($sqlFormatBar) or die ('NÃO FOI POSSÍVEL ACESSAR A TABELA DE CONFIGURAÇÕES DO SISTEMA!');
			$rowFormatBar = mysql_fetch_array($execFormatBar);
			if (strpos($rowFormatBar['conf_formatBar'],'%oco%')) {
				$formatBarOco = 1;
			} else {
				$formatBarOco = 0;
			}
			if (strpos($rowFormatBar['conf_formatBar'],'%mural%')) {
				$formatBarMural = 1;
			} else {
				$formatBarMural = 0;
			}



			$_SESSION['s_logado'] = $s_logado;
			$_SESSION['s_usuario'] = $s_usuario;
			$_SESSION['s_uid'] = $s_uid;
			$_SESSION['s_senha'] = $s_senha;
			$_SESSION['s_nivel'] = $s_nivel;
			$_SESSION['s_nivel_desc'] = $s_nivel_desc;
			$_SESSION['s_area'] = $s_area;
			$_SESSION['s_uareas'] = $s_uareas;
			$_SESSION['s_permissoes'] = $s_permissoes;
			$_SESSION['s_area_admin'] = $s_area_admin;
			$_SESSION['s_ocomon'] = $s_ocomon;
			$_SESSION['s_invmon'] = $s_invmon;
			$_SESSION['s_allow_change_theme'] = $rowFormatBar['conf_allow_change_theme'];
			$_SESSION['s_screen'] = $s_screen;			


			$_SESSION['s_formatBarOco'] = $formatBarOco;
			$_SESSION['s_formatBarMural'] = $formatBarMural;

			if (!empty($rowPref['upref_lang'])){
				$_SESSION['s_language'] = $rowPref['upref_lang'];			
			} else {
				$_SESSION['s_language'] = $rowFormatBar['conf_language'];
			}

			$_SESSION['s_date_format'] = $rowFormatBar['conf_date_format'];

			$_SESSION['s_paging_full'] = 0;

			$_SESSION['s_page_size'] = $rowFormatBar['conf_page_size'];

			$_SESSION['s_allow_reopen'] = $rowFormatBar['conf_allow_reopen'];

			$_SESSION['s_allow_date_edit'] = $rowFormatBar['conf_allow_date_edit'];
			
			$_SESSION['s_ocomon_site'] = $rowFormatBar['conf_ocomon_site'];

			$sqlStyles = "SELECT * FROM temas t, uthemes u  WHERE u.uth_uid = ".$_SESSION['s_uid']." and t.tm_id = u.uth_thid";
			$execStyles = mysql_query($sqlStyles) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DO TEMA!<BR>'.$sqlStyles);
			$rowSty = mysql_fetch_array($execStyles);
			$regs = mysql_num_rows($execStyles);
			if ($regs==0){ //SE NÃO ENCONTROU TEMA ESPECÍFICO PARA O USUÁRIO
				unset($rowSty);
				$sqlStyles = "SELECT * FROM styles";
				$execStyles = mysql_query($sqlStyles);
				$rowSty = mysql_fetch_array($execStyles);
			}

			$_SESSION['s_colorDestaca'] = $rowSty['tm_color_destaca'];
			$_SESSION['s_colorMarca'] = $rowSty['tm_color_marca'];

			print "<script>redirect('../../index.php?".session_id()."');</script>";
		} else {

			print "<script>redirect('../../index.php?usu=".$_POST['login']."&inv=1');</script>";
			$conec->desconLDAP();
			$conecSec->desconLDAP();
			exit;
		}
		$conec->desconLDAP();
		$conecSec->desconLDAP();

	} else {

		if (senha_system($_POST['login'],$_POST['password'],'usuarios')=="ok")
		{

		        $s_usuario=$_POST['login'];
		        $s_senha=$_POST['password'];

			$queryOK = "SELECT u.*, n.*,s.* FROM usuarios u left join sistemas as s on u.AREA = s.sis_id ".
							"left join nivel as n on n.nivel_cod =u.nivel WHERE u.login = '".$_POST['login']."'";

			$resultadoOK = mysql_query($queryOK) or die('IMPOSSÍVEL ACESSAR A BASE DE DADOS DE USUÁRIOS: LOGIN.PHP');
			$row = mysql_fetch_array($resultadoOK);
			$s_nivel = $row['nivel'];

			if ($s_nivel<4){ //Verifica se não está desabilitado
				$s_logado=1;
			}

			$s_nivel_desc = $row['nivel_nome'];
			$s_area = $row['AREA'];
			$s_uid = $row['user_id'];
			$s_area_admin =  $row['user_admin'];
			$s_screen = $row['sis_screen'];


			/*VERIFICA EM QUAIS ÁREAS O USUÁRIO ESTÁ CADASTRADO*/
			$qryUa = "SELECT * FROM usuarios_areas where uarea_uid=".$s_uid.""; //and uarea_sid=".$s_area."
			$execUa = mysql_query($qryUa) or die('IMPOSSÍVEL ACESSAR A BASE DE USUÁRIOS 02: LOGIN.PHP');
			$uAreas = "".$s_area.",";
			while ($rowUa = mysql_fetch_array($execUa)){
				$uAreas.=$rowUa['uarea_sid'].",";
			}
			$uAreas = substr($uAreas,0,-1);
			$s_uareas = $uAreas;

			/*CHECA QUAIS OS MÓDULOS PODEM SER ACESSADOS PELAS ÁREAS QUE O USUÁRIO PERTENCE*/
			$qry = "SELECT * FROM permissoes where perm_area in (".$uAreas.")";
			$exec = mysql_query($qry) or die('IMPOSSÍVEL ACESSAR A BASE DE PERMISSÕES: LOGIN.PHP');


			while($row_perm = mysql_fetch_array($exec)){
				$s_permissoes[]=$row_perm['perm_modulo'];
			}
			$s_ocomon = 0;
			$s_invmon = 0;
			for ($i=0;$i<count($s_permissoes); $i++){
				if($s_permissoes[$i] == 1) $s_ocomon = 1;
				if($s_permissoes[$i] == 2) $s_invmon = 1;
			}

			
			$sqlPrefs = "SELECT * FROM uprefs WHERE upref_uid = ".$s_uid."";
			$execPrefs = mysql_query($sqlPrefs);
			$rowPref = mysql_fetch_array($execPrefs);
			
			
			$sqlFormatBar = "SELECT * FROM config"; //INFO FROM GENERAL CONF
			$execFormatBar = mysql_query($sqlFormatBar) or die ('NÃO FOI POSSÍVEL ACESSAR A TABELA DE CONFIGURAÇÕES DO SISTEMA!');
			$rowFormatBar = mysql_fetch_array($execFormatBar);
			if (strpos($rowFormatBar['conf_formatBar'],'%oco%')) {
				$formatBarOco = 1;
			} else {
				$formatBarOco = 0;
			}
			if (strpos($rowFormatBar['conf_formatBar'],'%mural%')) {
				$formatBarMural = 1;
			} else {
				$formatBarMural = 0;
			}

			$_SESSION['s_logado'] = $s_logado;
			$_SESSION['s_usuario'] = $s_usuario;
			$_SESSION['s_uid'] = $s_uid;
			$_SESSION['s_senha'] = $s_senha;
			$_SESSION['s_nivel'] = $s_nivel;
			$_SESSION['s_nivel_desc'] = $s_nivel_desc;
			$_SESSION['s_area'] = $s_area;
			$_SESSION['s_uareas'] = $s_uareas;
			$_SESSION['s_permissoes'] = $s_permissoes;
			$_SESSION['s_area_admin'] = $s_area_admin;
			$_SESSION['s_ocomon'] = $s_ocomon;
			$_SESSION['s_invmon'] = $s_invmon;
			$_SESSION['s_allow_change_theme'] = $rowFormatBar['conf_allow_change_theme'];
			$_SESSION['s_screen'] = $s_screen;
			

			$_SESSION['s_formatBarOco'] = $formatBarOco;
			$_SESSION['s_formatBarMural'] = $formatBarMural;

			if (!empty($rowPref['upref_lang'])){
				$_SESSION['s_language'] = $rowPref['upref_lang'];			
			} else {
				$_SESSION['s_language'] = $rowFormatBar['conf_language'];
			}

			$_SESSION['s_date_format'] = $rowFormatBar['conf_date_format'];

			$_SESSION['s_paging_full'] = 0;

			$_SESSION['s_page_size'] = $rowFormatBar['conf_page_size'];

			$_SESSION['s_allow_reopen'] = $rowFormatBar['conf_allow_reopen'];

			$_SESSION['s_allow_date_edit'] = $rowFormatBar['conf_allow_date_edit'];
			
			$_SESSION['s_ocomon_site'] = $rowFormatBar['conf_ocomon_site'];

			$sqlStyles = "SELECT * FROM temas t, uthemes u  WHERE u.uth_uid = ".$_SESSION['s_uid']." and t.tm_id = u.uth_thid";
			$execStyles = mysql_query($sqlStyles) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DO TEMA!<BR>'.$sqlStyles);
			$rowSty = mysql_fetch_array($execStyles);
			$regs = mysql_num_rows($execStyles);
			if ($regs==0){ //SE NÃO ENCONTROU TEMA ESPECÍFICO PARA O USUÁRIO
				unset($rowSty);
				$sqlStyles = "SELECT * FROM styles";
				$execStyles = mysql_query($sqlStyles);
				$rowSty = mysql_fetch_array($execStyles);
			}


			$_SESSION['s_colorDestaca'] = $rowSty['tm_color_destaca'];
			$_SESSION['s_colorMarca'] = $rowSty['tm_color_marca'];
			$_SESSION['s_colorLinPar'] = $rowSty['tm_color_lin_par'];
			$_SESSION['s_colorLinImpar'] = $rowSty['tm_color_lin_impar'];



			###############################################
			## TRECHO PARA VERIFICAÇÃO DAS GARANTIAS ##

			#DEFINIR QUERY PARA CHECAGEM SOBRE OS BENS QUE ESTIVEREM PRESTES A FICAREM SEM GARANTIA;

			if ($rowFormatBar['conf_days_bf']!=0) {

				$qryWarranty = "SELECT e.estoq_cod, e.estoq_sn, e.estoq_partnumber, e.estoq_nf, ".
				"\n\ti.item_nome AS tipo, model.mdit_fabricante as fabricante, model.mdit_desc as modelo, ".
				"\n\tmodel.mdit_desc_capacidade as capacidade, model.mdit_sufixo as sufixo, ".

				"\n\tf.forn_nome as fornecedor, l.local as local,".

				"\n\tew.ew_sent_first_alert as first_alert, ew.ew_sent_last_alert as last_alert,".

				"\n\tdate_add(date_format(e.estoq_data_compra, '%Y-%m-%d') , INTERVAL t.tempo_meses MONTH) AS vencimento ".

				"\nFROM  ".
				"\n\testoque e  ".
				"\n\tleft join email_warranty ew on e.estoq_cod = ew.ew_piece_id ".

				"\n\tleft join fornecedores f on f.forn_cod = e.estoq_vendor ".

				"\n\tleft join localizacao l on l.loc_id = e.estoq_local,  ".

				"\n\ttempo_garantia t, modelos_itens model, itens i ".
				"\nWHERE  ".

				"\n\tdate_add(date_format(e.estoq_data_compra, '%Y-%m-%d'), INTERVAL t.tempo_meses MONTH) >= ".
				"\n\tdate_add(date_format(curdate(), '%Y-%m-%d'), INTERVAL 0 DAY) ".

				"\n\tAND ".

				"\n\tdate_add(date_format(e.estoq_data_compra, '%Y-%m-%d'), INTERVAL t.tempo_meses MONTH) <= ".
				"\n\tdate_add(date_format(curdate(), '%Y-%m-%d'), INTERVAL ".$rowFormatBar['conf_days_bf']." DAY) ".


				"\n\tAND e.estoq_warranty = t.tempo_cod AND e.estoq_tipo = i.item_cod ".
				"\n\tAND e.estoq_desc = model.mdit_cod ".

				"\n\t AND ((ew.ew_sent_first_alert is null OR ew.ew_sent_first_alert=0))".


				"\nORDER BY vencimento, modelo";
				$execWarranty = mysql_query($qryWarranty) or die (dump($qryWarranty));
				//$achou = mysql_num_rows($execWarranty);



				$event = 'mail-about-warranty';
				$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
				$execmsg = mysql_query($qrymsg) or die(TRANS('MSG_ERR_MSCONFIG'));
				$rowmsg = mysql_fetch_array($execmsg);

				$sqlMailArea = "select * from sistemas where sis_id = ".$rowFormatBar['conf_wrty_area']."";
				$execMailArea = mysql_query($sqlMailArea);
				$rowMailArea = mysql_fetch_array($execMailArea);

				while ($rowWrt = mysql_fetch_array($execWarranty)){


					$VARS = array();
					$VARS['%serial%'] = $rowWrt['estoq_sn'];
					$VARS['%partnumber%'] = $rowWrt['estoq_partnumber'];
					$VARS['%tipo%'] = $rowWrt['tipo'];
					$VARS['%modelo%'] = $rowWrt['fabricante']."&nbsp;".$rowWrt['modelo']."&nbsp;".$rowWrt['capacidade']."&nbsp;".$rowWrt['sufixo'];
					$VARS['%vencimento%'] = $rowWrt['vencimento'];
					$VARS['%notafiscal%'] = $rowWrt['estoq_nf'];
					$VARS['%fornecedor%'] = $rowWrt['fornecedor'];
					$VARS['%local%'] = $rowWrt['local'];

					send_mail($event, $rowMailArea['sis_email'], $rowFormatBar, $rowmsg, $VARS);

					$findMailSent = "SELECT * FROM email_warranty ".
						"\n\tWHERE ew_piece_id = '".$rowWrt['estoq_cod']."' ".
						" ";
					$execFindMailSent = mysql_query($findMailSent) or die (dump($findMailSent));
					$found = mysql_num_rows($execFindMailSent);

					if ($found) {
						$updMailSent = "UPDATE email_warranty SET ".
							"\n\tew_piece_id= '".$rowWrt['estoq_cod']."', ".
							"\n\tew_sent_first_alert=1, ".
							"\n\tew_sent_last_alert=0".
							" ";
						$execUpdMailSent = mysql_query($updMailSent) or die (dump($updMailSent));
					} else {
						$insMailSent = "INSERT INTO email_warranty ".
							"\n\t(ew_piece_id,ew_sent_first_alert,ew_sent_last_alert) ".
							"\n\tvalues ('".$rowWrt['estoq_cod']."',1,0 ) ".
							" ";
						$execInsMailSent = mysql_query($insMailSent) or die (dump($insMailSent));

					}
				}
			}


			###########################################################################

			print "<script>redirect('../../index.php?".session_id()."');</script>";
			//print "<script>redirect('../../index.php');</script>";

		}
		else
		{
				print "<script>redirect('../../index.php?usu=".$_POST['login']."&inv=1');</script>";
				exit;
		}
	}



?>
