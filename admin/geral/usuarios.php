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
	include ("../../includes/classes/paging.class.php");

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	$hoje = date("d-m-Y H:i:s");
	$hoje2 = date("d/m/Y");

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);

	if (isset($_POST['search'])){
		$search = $_POST['search'];
	} else
		$search = "";

	$query = "SELECT u.*, n.*,s.* from usuarios u left join sistemas as s on u.AREA = s.sis_id ".
					"left join nivel as n on n.nivel_cod =u.nivel ";
        if (isset($_GET['login'])) {
			$query.=" WHERE u.user_id = ".$_GET['login']."";
		} else
		if (isset($_GET['nivel'])) {
			$query.= "WHERE n.nivel_cod = ".$_GET['nivel']."";
		} else
		if (isset($_POST['search'])) {
			$query.= " WHERE (lower(u.login) like lower(('%".noHtml($_POST['search'])."%'))) OR ".
						"(lower(u.nome) like lower(('%".noHtml($_POST['search'])."%')))  ";
		}
		$query.=" ORDER BY u.nome";
		$resultado = mysql_query($query);
		$registros = mysql_num_rows($resultado);

		if (isset($_GET['LIMIT']))
			$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));


		if (isset($_GET['n_desc'])) {
			$n_descricao = $_GET['n_desc'];
		} else
			$n_descricao = TRANS('ALL','TODOS');

	print "<FORM name='form_usuarios' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";

	print "<TABLE border='0' ".$cellStyle." align='center' width='100%' >";

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		$PAGE->execSQL();

		print "<BR>";
		print "<B>".TRANS('TTL_USERS').":&nbsp;<font color='red'>".$n_descricao."</font></b>";
		print "<BR>";
		print "<tr>";
		print "<TD bgcolor='".BODY_COLOR."'><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>".TRANS('NEW')."</a>".
				"&nbsp;|&nbsp;<a href='usuarios.php?action=stat'>".TRANS('BRIEF')."</a></TD>";
		if (isset($_GET['n_desc'])) {
			print "<td class='line'>&nbsp;|&nbsp;</td><td class='line'><a href='".$_SERVER['PHP_SELF']."'>".TRANS('SHOW_ALL')."</a></td>";
		}
		print "</tr>";

		print "<tr>".//<td>".TRANS('FIELD_SEARCH')."</td>".
				"<td colspan='4'><input type='text' class='text' name='search' id='idSearch' value='".$search."'>&nbsp;".
				"<input type='submit' name='BT_SEARCH' class='button' value='".TRANS('BT_FILTER')."'>".
			"</td></tr>";
		//print "<BR>";
		if (isset($_POST['search'])) {
			print "<script>foco('idSearch');</script>";
		}

		if ($registros == 0)
        	{
			print "<tr><td>";
			print mensagem(TRANS('MSG_NO_RECORDS'));
			print "</tr></td>";
		}
		else
        	{
			$cor1=TD_COLOR;
			print "<tr><td colspan='8' class='line'>";
			print "<B>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD></tr>";
			//print "".TRANS('THERE_IS_ARE')."&nbsp;<b>".$registros."</b>&nbsp;".TRANS('USER_S_IN_SYSTEM').".<br>";
			print "<TR class='header'><td class='line'>".TRANS('OPT_NAME','Nome')."</TD>".
					"<td class='line'>".TRANS('OPT_LOGIN_NAME','Login')."</TD><td class='line'>".TRANS('OCO_FIELD_AREA','Área')."</TD>".
					"<td class='line'>".TRANS('OCO_FIELD_AREA_ADMIN','Área admin')."</TD>".
					"<td class='line'>".TRANS('OCO_FIELD_SUBSCRIBE_DATE','Data de inclusão')."</TD><td class='line'>".TRANS('OCO_FIELD_HIRE_DATE','Data de admissão')."</TD>".
					"<td class='line'>".TRANS('OCO_FIELD_EMAIL','E-mail')."</TD><td class='line'>".TRANS('OCO_FIELD_PHONE','Telefone')."</TD>".
					"<td class='line'>".TRANS('OCO_FIELD_LEVEL','Nível')."</TD><td class='line'>".TRANS('OCO_FIELD_ALTER','Alterar')."</TD>".
					"<td class='line'>".TRANS('OCO_FIELD_EXCLUDE','Excluir')."</TD></TR>";
			$i=0;
			$j=2;
			while ($row=mysql_fetch_array($PAGE->RESULT_SQL))
			{
				($j % 2)?$trClass = "lin_par":$trClass = "lin_impar";
				$j++;

				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";

				print "<td class='line'>".$row['nome']."</TD>";
				print "<td class='line'>".$row['login']."</TD>";
				print "<td class='line'>".$row['sistema']."</TD>";
				print "<td class='line'>".transbool($row['user_admin'])."</TD>";
				print "<td class='line'>".datab($row['data_inc'])."</TD>";
				print "<td class='line'>".datab($row['data_admis'])."</TD>";
				print "<td class='line'>".$row['email']."</TD>";
				print "<td class='line'>".NVL($row['fone'])."</TD>";
				print "<td class='line'><a href='usuarios.php?nivel=".$row['nivel_cod']."&n_desc=".$row['nivel_nome']."'>".$row['nivel_nome']."</a></TD>";
				print "<td class='line'><a onClick=\"redirect('usuarios.php?action=alter&cellStyle=true&login=".$row['user_id']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				print "<td class='line'><a onClick=\"javascript:confirmaAcao('".TRANS('ENSURE_DEL')." ".$row['nome']."?','usuarios.php','action=excluir&login=".$row['user_id']."');\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></TD>";

				print "</TR>";

			}
			print "<tr><td colspan='8'>";
			$PAGE->showOutputPages();
			print "</td></tr>";
		}
	} else
	if ((isset($_GET['action']) && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR>";
		print "<B>".TRANS('CADASTRE_USERS').":</B>";
		print "<BR>";

		//print "<FORM name='incluir' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		//print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
		print "<TR>";
       		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_LOGIN').":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='login' id='idLogin'></TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_LEVEL').":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<SELECT class='select' name='categoria' id='idCategoria'>";
			print "<option value=-1 selected>".TRANS('SEL_LEVEL')."</option>";
			$query = "SELECT * from nivel order by nivel_nome";
			$resultado = mysql_query($query);
			$registros = mysql_num_rows($resultado);
			$i=0;
			while ($rownivel = mysql_fetch_array($resultado)){
				print "<option value='".$rownivel['nivel_cod']."'>".$rownivel['nivel_nome']."</option>";
			}
			print "</select>";
			print "</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_NAME').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='nome' id='idNome'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_PASS').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='password' class='text' name='password' id='idSenha'></TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_SUBSCRIBE_DATE').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='data_inc' id='idDataInc' value='".formatDate(date('Y-m-d'), " ")."'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_HIRE_DATE').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='data_admis' id='idDataAdmis' value='".formatDate(date('Y-m-d'), " ")."'></TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_EMAIL').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='email' id='idEmail'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_PHONE').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='telefone' id='idTelefone'></TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_PRIMARY_AREA').":</TD>";
			print "<TD colspan='3' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='area' size=1 id='idArea'>";
			print "<option value=-1 selected>".TRANS('SEL_WORK_AREA').":</option>";
			$query = "SELECT * from sistemas where sis_status not in (0) order by sistema";
			$resultado = mysql_query($query);
			$registros = mysql_num_rows($resultado);
			while ($rowarea = mysql_fetch_array($resultado)) {
				print "<option value='".$rowarea['sis_id']."'>".$rowarea['sistema']."</option>";
			}
		print "</SELECT>";
		print "<input type='checkbox' name='areaadmin' value=1>".TRANS('COL_AREA_ADMIN')."";

			print "</TD>";
		print "</TR>";

			$qry = "select * from sistemas where sis_status not in (0) and sis_atende =1";
			$exec = mysql_query($qry);
			$i=0;
			print "<tr><td colspan='4'>".TRANS('COL_SECUNDARY_AREAS').":</td></tr>";
			while ($rowa=mysql_fetch_array($exec)){
				print "<tr><td colspan='4'>";
				print "<input type='checkbox' name='grupo[".$i."]' value='".$rowa['sis_id']."'>".$rowa['sistema']."";
				print "</td></tr>";
				$i++;
			}
		print "<TR>";
		print "<BR>";
			print "<TD colspan='2' align='center' width='50%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_CAD')."' name='submit'>";
			print "<input type='hidden' name='rodou' value='sim'>";
			print "</TD>";
			print "<TD colspan='2' align='center' width='50%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('BT_CANCEL')."' onClick=\"javascript:history.back()\" name='cancelar'></TD>";
		print "</TR>";
	} else
	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		print "<BR>";
		print "<B>".TRANS('TTL_EDIT_RECORD').":</B>";
		print "<BR>";

		$row = mysql_fetch_array($resultado);
		//print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";
		//print "<TABLE border='0' align='center' width='100%' bgcolor='".BODY_COLOR."'>";
		print "<TR>";

			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_LOGIN').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>".$row['login']."</TD>";

			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_LEVEL').":</TD>";

			$qrynivel = "SELECT * FROM nivel order by nivel_nome";
			$execnivel = mysql_query($qrynivel);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<SELECT class='select' name='categoria' id='idCategoria'>";
			print "<option value=-1>".TRANS('SEL_LEVEL')."</option>";
			while ($rownivel = mysql_fetch_array($execnivel)){
				print "<option value='".$rownivel['nivel_cod']."'";
				if ($rownivel['nivel_cod'] == $row['nivel_cod']){
					print " selected";
				}
				print ">".$rownivel['nivel_nome']."</option>";
			}
			print "</SELECT>";
			print "</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_NAME').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='nome' id='idNome' value='".$row['nome']."'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_PASS').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='password' class='text' name='password' id='idSenha' value='".$row['password']."'></TD>";

			$password2 = md5($row['password']);
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_SUBSCRIBE_DATE').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='data_inc' id='idDataInc' value='".formatDate($row['data_inc'], " ")."'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_HIRE_DATE').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='data_admis' id='idDataAdmis' value='".formatDate($row['data_admis'], " ")."'></TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_EMAIL').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='email' id='idEmail' value='".$row['email']."'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_PHONE').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='telefone' id='idTelefone' value='".$row['fone']."'></TD>";
		print "</TR>";
		print "<tr>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_PRIMARY_AREA').":</TD>";
			$qryarea = "SELECT * FROM sistemas WHERE sis_status not in (0)";
			$execarea = mysql_query($qryarea);
			print "<TD colspan='3' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='area' id='idArea'>";
			print "<option value=-1>Selecione a área</option>";
			while ($rowarea = mysql_fetch_array($execarea)){
				print "<option value='".$rowarea['sis_id']."'";
				if ($rowarea['sis_id'] == $row['sis_id']){
					print " selected";
				}
				print ">".$rowarea['sistema']."</option>";
			}
            		print "</SELECT>";
			if ($row['user_admin']) {
				$check = " checked";
			} else $check = "";
			print "<input type='checkbox' name='areaadmin' value=1 ".$check.">".TRANS('COL_AREA_ADMIN')."";
			print "</TD>";
		print "</TR>";
		print "<tr>";
			print "<TD align='center' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_ALTER')."' name='submit'>";
			print "<input type='hidden' name='login' value='".$_GET['login']."'>";
			print "<input type='hidden' name='password2' value='".$password2."'>";
			print "</TD>";
            print "<TD colspan='3' align='center' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('BT_CANCEL')."' onClick=\"javascript:history.back();\" name='cancelar'></TD>";
        print "</TR>";


			$qry_areas = "select * from usuarios_areas where uarea_uid=".$_GET['login']."";
			$exec_areas = mysql_query($qry_areas);
			$total_areas = 0;
			while ($row_areas = mysql_fetch_array($exec_areas)){
				$uareas[$total_areas]= $row_areas['uarea_sid'];
				$total_areas++;
			}

			$qry = "select * from sistemas order by sistema";
			$exec = mysql_query($qry);

			$checked = array();
			$i = 0;
			print "<tr><td colspan='4'>".TRANS('COL_SECUNDARY_AREAS').":</td></tr>";
			while ($rowa=mysql_fetch_array($exec)){
				print "<tr><td colspan='4'>";
				for ($j=0; $j<$total_areas; $j++){
					//$checked[$i] = "";
					if ($uareas[$j]== $rowa['sis_id']) {
						$checked[$i] = "checked";
					}
				}

				if (isset($checked[$i]) ){
					$checkedValue = $checked[$i];
				} else
					$checkedValue = "";
				print "<input type='checkbox' name='grupo[".$i."]' value='".$rowa['sis_id']."' ".$checkedValue.">".$rowa['sistema']."";
				print "</td></tr>";
				$i++;
			}
	} else

	if ((isset($_GET['action']) && $_GET['action']=="stat") && empty($_POST['submit'])){

		$qryStat = "SELECT count(*) quantidade, n.nivel_nome nivel, n.nivel_cod nivel_cod FROM usuarios u, nivel n
					WHERE u.nivel = n.nivel_cod GROUP by nivel ORDER BY quantidade desc, nome";

		$execStat = mysql_query($qryStat) or die (TRANS('ERR_QUERY').$qryStat);

		$background = '#CDE5FF';

		print "<br><center><b/>".TRANS('TTL_USERS_BRIEF')."</center><br>";
		print "<table class='centro' cellspacing='0' border='1' align='center'>";
		print "<tr bgcolor='".$background."'><td class='line'>".strtoupper(TRANS('COL_LEVEL'))."</td><td class='line'>".strtoupper(TRANS('COL_QTD'))."</td></tr>";
		$TOTAL = 0;
		while ($rowStat = mysql_fetch_array($execStat)) {
			print "<tr><td class='line'><a href='usuarios.php?nivel=".$rowStat['nivel_cod']."&n_desc=".$rowStat['nivel']."'>".$rowStat['nivel']."</a></td><td class='line'>".$rowStat['quantidade']."</td></tr>";
			$TOTAL+=$rowStat['quantidade'];
		}
		print "<tr><td class='line'>".TRANS('TOTAL')."</td><td class='line'>".$TOTAL."</td></tr>";
		print "</table>";



		$qryTmp = "SELECT * FROM utmp_usuarios ORDER BY utmp_nome, utmp_cod";
		$execTmp = mysql_query($qryTmp);
		$registrosTmp = mysql_num_rows($execTmp);
		if ($registrosTmp > 0) {
			print "<br><BR><center><b/>".TRANS('TTL_WAITING_CONFIRMATION')."</center><br>";
			print "<table class='centro' cellspacing='0' border='1' align='center'>";
			print "<tr bgcolor='".$background."'><td class='line'>".TRANS('COL_NAME')."</td><td class='line'>".TRANS('COL_LOGIN')."</td><td class='line'>".TRANS('COL_EMAIL')."</td><td class='line'>".TRANS('COL_CONFIRM')."</td><td class='line'>".TRANS('COL_DEL')."</td></tr>";
			while ($rowtmp = mysql_fetch_array($execTmp)) {
				print "<tr><td class='line'>".$rowtmp['utmp_nome']."</a></td><td class='line'>".$rowtmp['utmp_login']."</td><td class='line'>".$rowtmp['utmp_email']."</td>";
				print "<td class='line'><a onClick=\"javascript:confirmaAcao('".TRANS('ENSURE_CONFIRM')." ".$rowtmp['utmp_nome']."?','usuarios.php','action=addtmp&cod=".$rowtmp['utmp_cod']."');\"><img height='16' width='16' src='".ICONS_PATH."ok.png' title='".TRANS('HNT_CONFIRM')."'></TD>";
				print "<td class='line'><a onClick=\"javascript:confirmaAcao('".TRANS('ENSURE_DEL')." ".$rowtmp['utmp_nome']."?','usuarios.php','action=deltmp&cod=".$rowtmp['utmp_cod']."');\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></TD>";
				print "</tr>";
			}

			print "<tr><td colspan='2'><b>".TRANS('TOTAL')."</b></td><td colspan='3'><b>".$registrosTmp." ".TRANS('RECORDS')."</b></td></tr>";
			print "</table>";

		} else
			print TRANS('MSG_NO_PENDENCES');


	} else


	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$qrydel = "select * from ocorrencias where operador=".$_GET['login']." OR aberto_por = ".$_GET['login']." ";
		$execdel = mysql_query($qrydel) or die (TRANS('ERR_QUERY'));

		$regs = mysql_num_rows($execdel);

		if ($regs!=0){
			print "<script>mensagem('".TRANS('MSG_CANT_DEL').": ocorrencias ".TRANS('LINKED_TABLE')."!');
			redirect('".$_SERVER['PHP_SELF']."');</script>";
			exit;
		}
		else {
			$qrydel = "DELETE FROM usuarios WHERE user_id=".$_GET['login']."";
			$execdel = mysql_query($qrydel) or die ('Erro na exlusão do registro'.$qrydel);
			print "<script>mensagem('".TRANS('OK_DEL')."');".
					"redirect ('".$_SERVER['PHP_SELF']."');</script>";
		}

	} else

	if ( isset($_GET['action']) && $_GET['action'] == "deltmp"){
		$qrydel = "DELETE FROM utmp_usuarios where utmp_cod = ".$_GET['cod']."";
		$execdel = mysql_query($qrydel) or die (TRANS('ERR_DEL'));

		print "<script>mensagem('".TRANS('OK_DEL')."');".
					"redirect ('usuarios.php?action=stat');</script>";

	} else

	if (isset($_GET['action']) && $_GET['action'] == "addtmp"){
		//print "<script>mensagem('FUNÇÃO DE CONFIRMAÇÃO AINDA NÃO IMPLEMENTADA!'); redirect('usuarios.php?action=stat');</script>";
		$qryadd = "SELECT utmp_rand FROM utmp_usuarios WHERE utmp_cod = ".$_GET['cod']."";
		$execadd = mysql_query($qryadd) or die (TRANS('ERR_QUERY'));
		$rowadd = mysql_fetch_array($execadd);
		print "<script>redirect('../../ocomon/geral/confirma.php?rand=".$rowadd['utmp_rand']."&fromAdmin=true');</script>";


	} else

	if ($_POST['submit'] == TRANS('BT_CAD')){

		$erro=false;
		$pass = md5($_POST['password']);

		$qrytesta = "SELECT * FROM sistemas where sis_id = ".$_POST['area']."";
		$execteste = mysql_query($qrytesta);
		$rowtesta = mysql_fetch_array($execteste);

		if ($_POST['categoria'] == 3 and $rowtesta['sis_atende']) {
			$aviso = TRANS('MSG_ONLY_OPEN_USERS');
			$erro = true;
		} else
		if ($_POST['categoria'] != 3 and !$rowtesta['sis_atende']) {
			$aviso = TRANS('MSG_ATTEND_OPERATORS'); //"Usuários operadores não podem pertencer à áreas que não prestam atendimento!";
			$erro = true;
		}


		$qryins= "SELECT login FROM usuarios WHERE login = '".$_POST['login']."'";
		$execins = mysql_query($qryins) or die(TRANS('ERR_QUERY').$qryins);
		$regs = mysql_num_rows($execins);
		if ($regs > 0){
			$aviso = TRANS('MSG_RECORD_EXISTS');
			$erro = true;
		}

		if (!$erro) {
			$data_inc = datam($_POST['data_inc']);
			$data_admis = datam($_POST['data_admis']);
/*			if ($_POST['areaadmin']){

			}*/
			if (isset($_POST['areaadmin'])) {
				$areaadmin = $_POST['areaadmin'];
			} else
				$areaadmin = 0;

			$qryins = "INSERT INTO usuarios (login, nome, password, data_inc, data_admis, email, fone, nivel, AREA, user_admin) ".
					"values ('".noHtml($_POST['login'])."','".noHtml($_POST['nome'])."','".$pass."','".$data_inc."'".
					",'".$data_admis."','".$_POST['email']."','".$_POST['telefone']."', ".$_POST['categoria'].", "
					.$_POST['area'].", '".$areaadmin."')";
			$execins = mysql_query($qryins) or die (TRANS('ERR_INSERT').$qryins);
			$uid = mysql_insert_id();

			$qrycountarea = "SELECT count(*) tAreas from sistemas";
			$execcountarea = mysql_query($qrycountarea) or die (TRANS('ERR_QUERY').$qrycountarea);
			$rowcountarea = mysql_fetch_array($execcountarea);
			for ($j=0; $j<$rowcountarea['tAreas']; $j++){
				if (!empty($_POST['grupo'][$j])){
					$qry_areas = "insert into usuarios_areas (uarea_uid,uarea_sid) values (".$uid.",".$_POST['grupo'][$j].")";
					$exec_qryareas = mysql_query($qry_areas) or die (TRANS('ERR_QUERY'));
				}
				//$error.=$qry_areas." | ";
			}
			$aviso = TRANS('OK_INSERT');
		}
		print "<script>mensagem('".$aviso."'); redirect('usuarios.php');</script>";
	} else
	if ($_POST['submit'] == TRANS('BT_ALTER')){
		$erro = false;

		if (!$erro) {

			if (isset($_POST['areaadmin'])){
				$areaadmin = $_POST['areaadmin'];
			} else
				$areaadmin = "";
			$data_inc = converte_dma_para_amd($_POST['data_inc']);
			$data_admis = converte_dma_para_amd($_POST['data_admis']);
			$pass = md5($_POST['password']);
			if ($pass == $_POST['password2'])
					$query2 = "UPDATE usuarios SET nome='".noHtml($_POST['nome'])."', data_inc='".$data_inc."', ".
						"data_admis='".$data_admis."', email='".$_POST['email']."', fone='".$_POST['telefone']."',".
						"nivel=".$_POST['categoria'].", AREA=".$_POST['area'].", user_admin='".$areaadmin."' "."
						WHERE user_id=".$_POST['login']."";
			else
					$query2 = "UPDATE usuarios SET nome='".noHtml($_POST['nome'])."', password='".$pass."', ".
						"data_inc='".$data_inc."', data_admis='".$data_admis."', email='".$_POST['email']."', ".
						"fone='".$_POST['telefone']."', nivel=".$_POST['categoria'].", AREA=".$_POST['area'].", ".
						" user_admin='".$_POST['areaadmin']."' WHERE user_id=".$_POST['login']."";
			$resultado2 = mysql_query($query2) or die ('Erro - '.$query2);

			/*     ----------------------------------------------------------------------------------------  */

			$qry = "delete from usuarios_areas where uarea_uid=".$_POST['login']."";
			$exec = mysql_query($qry) or die(TRANS('ERR_QUERY').$qry);

			$qrycountarea = "SELECT count(*) tAreas from sistemas";
			$execcountarea = mysql_query($qrycountarea) or die (TRANS('ERR_QUERY').$qrycountarea);
			$rowcountarea = mysql_fetch_array($execcountarea);
			for ($j=0; $j<$rowcountarea['tAreas']; $j++){
				if (!empty($_POST['grupo'][$j])){
					$qry_areas = "insert into usuarios_areas (uarea_uid,uarea_sid) values (".$_POST['login'].",".$_POST['grupo'][$j].")";
					$exec_qry = mysql_query($qry_areas) or die(TRANS('ERR_QUERY'));
				}
				//$error.=$qry_areas." | ";
			 }

			/*-----------------------------------------------------------------------------------------------*/

			$aviso = TRANS('OK_EDIT');
		}
		print "<script>mensagem('".$aviso."'); redirect('usuarios.php');</script>";
	}
	print "</table>";
	print "</form>";
?>
<script type="text/javascript">
<!--
	function valida(){

		var ok = validaForm('idLogin','ALFAFULL','Login',1)
		if (ok) var ok = validaForm('idCategoria','COMBO','Categoria',1);
		if (ok) var ok = validaForm('idNome','','Nome',1);
		if (ok) var ok = validaForm('idSenha','ALFAFULL','Senha',1);
		if (ok) var ok = validaForm('idDataInc','DATA','Data Inscrição',1);
		if (ok) var ok = validaForm('idDataAdmis','DATA','Data Admissão',1);
		if (ok) var ok = validaForm('idEmail','EMAIL','Email',1);
		if (ok) var ok = validaForm('idTelefone','FONE','Telefone',1);
		if (ok) var ok = validaForm('idArea','COMBO','Área',1);

		return ok;
	}
-->
</script>
<?php 


print "</body>";
print "</html>";


?>
