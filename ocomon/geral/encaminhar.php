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

	include ("../../includes/classes/lock.class.php");

	//print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

	print "<html>";
	print "<body>";
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$hoje = date("Y-m-d H:i:s");
        $hoje2 = date("d/m/Y");

	$qry_config = "SELECT * FROM config ";
	$exec_config = mysql_query($qry_config) or die ("ERRO AO TENTAR ACESSAR A TABELA CONFIG! CERTIFIQUE-SE DE QUE A TABELA EXISTE!");;
	$row_config = mysql_fetch_array($exec_config);

	$LOCK = new lock();

	if (isset($_GET['numero'])) {
		if (isset($_GET['FORCE_EDIT']) && $_GET['FORCE_EDIT'] == 1)
			$FORCE_EDIT = 1; else $FORCE_EDIT = 0;
		$LOCK->setLock($_GET['numero'], $_SESSION['s_uid'], $FORCE_EDIT);
	}

	if (!isset($_POST['submit'])) {

		$query = "select o.*, u.* from ocorrencias as o, usuarios as u where o.operador = u.user_id and numero=".$_GET['numero']."";
		$resultado = mysql_query($query);
		$row = mysql_fetch_array($resultado);
		$linhas = mysql_numrows($resultado);

		$data_atend = $row['data_atendimento']; //Data de atendimento!!!

		$query2 = "select a.*, u.* from assentamentos a, usuarios u where a.responsavel=u.user_id and ocorrencia=".$_GET['numero']."";
		$resultado2 = mysql_query($query2);
		$linhas2 = mysql_num_rows($resultado2);

		if ($_SESSION['s_nivel'] == 1) $linkEdita = "<br><b><a href='altera_dados_ocorrencia.php?numero=".$_GET['numero']."'>Editar ocorrência como admin:</a></b><br>"; else
			$linkEdita = "<br><b>Editar ocorrência:</b><br>";

		print $linkEdita;


		print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' ENCTYPE='multipart/form-data' onSubmit='return valida()'>";
		print "<input type='hidden' name='MAX_FILE_SIZE' value='".$row_config['conf_upld_size']."' />";

		print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
        	print "<TR>";
                	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Número:</TD>";
                	print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."' ><input class='disable' value='".$row['numero']."' disabled></TD>";

			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Status:</TD>";
			print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'>";

				if ($row['status'] == 4){$stat_flag="";} else $stat_flag =" where stat_id<>4 ";

				print "<SELECT class='select' name='status' id='idStatus' size='1'>";
	        		        print "<option value= '-1'>Selecione o status</option>";
	                		$query_stat = "SELECT * from status ".$stat_flag." order by status";
			                $exec_stat = mysql_query($query_stat);
					while ($row_stat = mysql_fetch_array($exec_stat))
					{
						print "<option value=".$row_stat['stat_id']."";
							if ($row_stat['stat_id'] == $row['status']) {
								print " selected";
							}
						print " >".$row_stat['status']." </option>";
					}
				print "</select>";
			print "</TD>";
		print "</TR>";

        	print "<TR>";
            		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Problema:</TD>";
	                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";

				print "<SELECT class='select' name='problema' id='idProblema' size='1'>";
	        		        print "<option value= '-1'>Selecione o problema</option>";
	                		$query = "SELECT * from problemas order by problema";
			                $exec_prob = mysql_query($query);
					while ($row_prob = mysql_fetch_array($exec_prob))
					{
						print "<option value=".$row_prob['prob_id']."";
							if ($row_prob['prob_id'] == $row['problema']) {
								print " selected";
							}
						print " >".$row_prob['problema']." </option>";
					}
				print "</select>";
			print "</TD>";

                	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Área responsável:</TD>";
	                print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'>";
				print "<SELECT class='select' name='sistema' id='idArea'>";
	        		        print "<option value= '-1'>Selecione a área</option>";
	                		$query = "SELECT * from sistemas order by sistema";
			                $exec_sis = mysql_query($query);
			                while ($row_sis = mysql_fetch_array($exec_sis))
               				{
						print "<option value=".$row_sis['sis_id']."";
							if ($row_sis['sis_id'] == $row['sistema']) {
								print " selected";
							}
						print " >".$row_sis['sistema']." </option>";
            		    		}
				print "</select>";
				print "</TD>";

        	print "</TR>";
	        print "<TR>";
			print "<TD width='20%' align='left' valign='top' bgcolor='".TD_COLOR."'>Descrição:</TD>";
                	print "<TD colspan='2' width='80%' align='left' bgcolor='".BODY_COLOR."' class='wide' valign='top'><b>".nl2br($row['descricao'])."</b></TD>";

        		print "<td colspan='3'>&nbsp;</td>";
        	print "</TR>";
	        print "<TR>";
			print "<TD width='20%' align='left' valign='top' bgcolor='".TD_COLOR."'>Unidade:</TD>";
			print "<TD  width='30%' align='left' bgcolor='".BODY_COLOR."'>";

			$instituicao = $row['instituicao'];

			if ($instituicao != null)
			{
				$query = "SELECT * FROM instituicao WHERE inst_cod=".$instituicao."";
			}
			else
			{
				$query = "SELECT * FROM instituicao WHERE inst_cod is null";
			}
			$resultado3 = mysql_query($query);
			$nomeinst = "";
			if (mysql_numrows($resultado3) > 0)
			{
				$nomeinst=mysql_result($resultado3,0,1);
			}
			print "<select  class='select' name='institui' size='1'>";
			$query_todas="select * from instituicao order by inst_cod";
			$result_todas=mysql_query($query_todas);

			if ($nomeinst=="")
			{
				print "<option value='' selected> </option>";
			}

			while($row_todas=mysql_fetch_array($result_todas))
			{
				if ($row_todas['inst_cod']==$instituicao)
				{
					$s='selected ';
				}
				else
				{
					$s='';
				}
					print "<option value='".$row_todas['inst_cod']."' $s>".$row_todas['inst_nome']."</option>";
			} // while
			print "</select>";
	                print "</TD>";


	                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Etiqueta do equipamento:</TD>";
        	        print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'>".
        	        		"<INPUT type='text'  class='text' name='etiq' id='idEtiqueta' value ='".$row['equipamento']."'' size='15'>".
        	        	"</TD>";
        	print "</TR>";
        	print "<TR>";
                	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Contato:</TD>";
	                print "<TD  width='30%' align='left' bgcolor='".BODY_COLOR."'><input class='disable' value='".$row['contato']."' disabled></TD>";
    	            	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Ramal:</TD>";
        	        print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'><input class='disable' value='".$row['telefone']."' disabled></TD>";
	        print "</TR>";
    	    	print "<TR>";
                	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Local:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";

				print "<SELECT  class='select' name='local' id='idLocal' size=1>";
	        		        print "<option value= '-1'>Selecione o setor</option>";
	                		$query = "SELECT * from localizacao order by local";
			                $exec_loc = mysql_query($query);
					while ($row_loc = mysql_fetch_array($exec_loc))
					{
						print "<option value=".$row_loc['loc_id']."";
							if ($row_loc['loc_id'] == $row['local']) {
								print " selected";
							}
						print " >".$row_loc['local']." </option>";
					}
					print "</select>";
			print "</TD>";
            	    	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Operador:</TD>";
                	print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'>";

				print "<SELECT class='select' name='operador'>";
                    	    	$query = "SELECT u.*, a.* from usuarios u, sistemas a where u.AREA = a.sis_id and a.sis_atende='1' and u.nivel not in (3,4,5) order by login";
                        	$exec_oper = mysql_query($query);
        	                while ($row_oper = mysql_fetch_array($exec_oper))
            	            	{
					print "<option value=".$row_oper['user_id']." ";
					if ($row_oper['user_id']== $_SESSION['s_uid'])
						print " selected";
					print ">".$row_oper['nome']."</option>";
				}
                	        print "</SELECT>";
                    	print "</TD>";
	        print "</TR>";

		$antes = $row['status'];
		if ($row['status'] == 4) //Encerrado
		{
			$antes = 4;
			print "<TR>";
                	print "<TD align='left' bgcolor='".TD_COLOR."'>Data de abertura:</TD>";
                    	print "<TD align='left' bgcolor='".BODY_COLOR."'><input class='disable' value='".formatDate($row['data_abertura'])."' disabled></TD>";
			print "<TD align='left' bgcolor='".TD_COLOR."'>Data de encerramento:</TD>";
                    	print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'><input class='disable' value='".formatDate($row['data_fechamento'])."' disabled></TD>";
          		print "</TR>";
		}
        		else //chamado não encerrado
		{
			print "<TR>";
			print "<TD align='left' bgcolor='".TD_COLOR."'>Data de abertura:</TD>";
			print "<TD colspan='5' align='left' bgcolor='".BODY_COLOR."'><input class='disable' value='".formatDate($row['data_abertura'])."' disabled></TD>";
			print "</TR>";
		}

		print "<TR>";
                	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Assentamento:</TD>";
                	print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			if (!$_SESSION['s_formatBarOco']) {
				print "<TEXTAREA class='textarea' name='assentamento' id='idAssentamento'>".
						"Ocorrência encaminhada/alterada por ".$_SESSION['s_usuario']."</textarea>";
			} else
				print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
			?>
			<script type="text/javascript">
				var bar = '<?print $_SESSION['s_formatBarOco'];?>'
				if (bar ==1) {
					var oFCKeditor = new FCKeditor( 'assentamento' ) ;
					oFCKeditor.BasePath = '../../includes/fckeditor/';
					oFCKeditor.Value = '<?print "Ocorrência encaminhada/alterada por ".$_SESSION['s_usuario']."";?>';
					oFCKeditor.ToolbarSet = 'ocomon';
					oFCKeditor.Width = '570px';
					oFCKeditor.Height = '100px';
					oFCKeditor.Create() ;
				}
			</script>
			<?
			print "</TD>";
        	print "</TR>";

			$qryTela = "select * from imagens where img_oco = ".$row['numero']."";
			$execTela = mysql_query($qryTela) or die ("NÃO FOI POSSÍVEL RECUPERAR AS INFORMAÇÕES DA TABELA DE IMAGENS!");
			//$rowTela = mysql_fetch_array($execTela);
			$isTela = mysql_num_rows($execTela);
			$cont = 0;
			while ($rowTela = mysql_fetch_array($execTela)) {
			//if ($isTela !=0) {
				$cont++;
				print "<tr>";
				$size = round($rowTela['img_size']/1024,1);
				print "<TD  bgcolor='".TD_COLOR."' >Anexo ".$cont."&nbsp;[".$rowTela['img_tipo']."]<br>(".$size."k):</td>";

				//if(eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $rowTela["img_tipo"])) {
				if(isImage($rowTela["img_tipo"])) {
					$viewImage = "&nbsp;<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?".
						"file=".$row['numero']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\" ".
						"title='View the file'><img src='../../includes/icons/kghostview.png' width='16px' height='16px' border='0'></a>";
				} else {
					$viewImage = "";
				}
				print "<td colspan='5' ><a onClick=\"redirect('../../includes/functions/download.php?".
						"file=".$row['numero']."&cod=".$rowTela['img_cod']."')\" title='Download the file'>".
						"<img src='../../includes/icons/attach2.png' width='16px' height='16px' border='0'>".
						"".$rowTela['img_nome']."</a>".$viewImage."</TD>";
				print "</tr>";
			}


			print "<tr>";
				print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Anexar imagem:</TD>";
				print "<TD colspan='5' align='left' bgcolor=".BODY_COLOR."><INPUT type='file' class='text' name='img' id='idImg'></TD>"; //class='text'
			print "</tr>";


			$qrymail = "SELECT u.*, a.*,o.* from usuarios u, sistemas a, ocorrencias o where ".
						"u.AREA = a.sis_id and o.aberto_por = u.user_id and o.numero = ".$_GET['numero']."";
			$execmail = mysql_query($qrymail);
			$rowmail = mysql_fetch_array($execmail);
			if ($rowmail['sis_atende']==0){
				$habilita = "";
			} else $habilita = "disabled";

			print "<tr><td bgcolor='".TD_COLOR."'>Enviar e-mail para:</td>".
					"<td colspan='2'><input type='checkbox' value='ok' name='mailAR' title='Envia email para a área selecionada para esse chamado'>Área Responsável&nbsp;&nbsp;".
									"<input type='checkbox' value='ok' name='mailOP' title='Envia e-mail para o operador selecionado no chamado'>Operador&nbsp;&nbsp;".
									"<input type='checkbox' value='ok' name='mailUS' title='teste' ".$habilita."><a title='Essa opção só fica habilitada para chamados abertos pelo próprio usuário'>Usuário</a></td>".
					"</tr>";

			//print "<tr><td colspan='3'>&nbsp;</td></tr>";
			print "<tr><td colspan='3' align='center'>";
			if ($data_atend =="") {
				print "<input type='checkbox' value='ok' name='resposta' checked title='Desmarque essa opção se esse assentamento não corresponder a uma primeira resposta do chamado'>1.ª Resposta";
			}
			//print "</td><td colspan='3'></td></tr>";


		if ($linhas2 > 0) { //ASSENTAMENTOS DO CHAMADO
			print "<tr><td colspan='6'><IMG ID='imgAssentamento' SRC='../../includes/icons/open.png' width='9' height='9' ".
					"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('Assentamento')\">&nbsp;<b>Existe(m) <font color='red'>".$linhas2."</font>".
					" assentamento(s) para essa ocorrência.</b></td></tr>";

			//style='{padding-left:5px;}'
			print "<tr><td colspan='6' ><div id='Assentamento' style='{display:none}'>"; //style='{display:none}'
			print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
			$i = 0;
			while ($rowAssentamento = mysql_fetch_array($resultado2)){
				$printCont = $i+1;
				print "<TR>";
				print "<TD width='20%' ' bgcolor='".TD_COLOR."' valign='top'>".
						"Assentamento ".$printCont." de ".$linhas2." por ".$rowAssentamento['nome']." em ".
						"".formatDate($rowAssentamento['data'])."".
					"</TD>";
				print "<TD colspan='5' align='left' bgcolor='".BODY_COLOR."' valign='top'>".nl2br($rowAssentamento['assentamento'])."</TD>";
				print "</TR>";
				$i++;
			}
			print "</table></div></td></tr>";
		}



		print "<tr>";
		print "<TD colspan='3' align='center' width='50%' bgcolor='".BODY_COLOR."'>";
			print "<input type='hidden' name='data_gravada' value='".date("Y-m-d H:i:s")."'>";
			print "<input type='submit' class='button' value='  Ok  ' name='submit'>";
			print "<input type='hidden' name='numero' value='".$_GET['numero']."'>";
			print "<input type='hidden' name='antes' value='".$antes."'>";
			print "<input type='hidden' name='data_atend' value='".$data_atend."'>";
			print "<input type='hidden' name='abertopor' value='".$rowmail['user_id']."'>";
                print "</TD>";
                print "<TD colspan='3' align='center' width='25%' bgcolor='".BODY_COLOR."'>";
			print "<INPUT type='reset' class='button' value='Cancelar' onClick='javascript:history.back()' name='cancelar'>";
		print "</TD>";

		print "</TR>";
	} else
	if (isset($_POST['submit'])) {
		$depois = $_POST['status'];
		$erro= false;
		if (!$erro)  {
			$sqlPost = "select o.*, u.* from ocorrencias as o, usuarios as u where o.operador = u.user_id and numero=".$_POST['numero']."";
			$resultadoPost = mysql_query($sqlPost);
			$row = mysql_fetch_array($resultadoPost);



			$gravaImg = false;
			if (isset($_FILES['img']) and $_FILES['img']['name']!="") {
				$qryConf = "SELECT * FROM config";
				$execConf = mysql_query($qryConf) or die ("NÃO FOI POSSÍVEL ACESSAR AS INFORMAÇÕES DE CONFIGURAÇÃO, A TABELA CONF FOI CRIADA?");
				$rowConf = mysql_fetch_array($execConf);
				$arrayConf = array();
				$arrayConf = montaArray($execConf,$rowConf);
				$upld = upload('img',$arrayConf, $row_config['conf_upld_file_types']);

				if ($upld =="OK") {
					$gravaImg = true;
				} else {
					$upld.="<br><a align='center' <a onClick=\"javascript:history.back();\"><img src='".ICONS_PATH."/back.png' width='16px' height='16px'>&nbsp;Voltar</a>"; //onClick=\"exibeEscondeImg('idAlerta');\"
					print "</table>";
					print "<div class='alerta' id='idAlerta'><table bgcolor='#999999'><tr><td colspan='2' bgcolor='yellow'>".$upld."</td></tr></table></div>";
					//print "<script>javascript:history.back();</script>";
					exit;
				}
			}

			//$data = datam($hoje2);
			$responsavel = $_SESSION['s_uid'];

			$aviso = "";
			$queryA = "";
			$queryA = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel)".
					" values (".$_POST['numero'].",";

			if ($_SESSION['s_formatBarOco']) {
				$queryA.= " '".$_POST['assentamento']."',";
			} else {
				$queryA.= " '".noHtml($_POST['assentamento'])."',";
			}

			$queryA.=" '".date('Y-m-d H:i:s')."', ".$responsavel.")";

			if ($gravaImg) {
				//INSERÇÃO DO ARQUIVO NO BANCO
				$fileinput=$_FILES['img']['tmp_name'];

				$tamanho = getimagesize($fileinput);
				$tamanho2 = filesize($fileinput);

				if(chop($fileinput)!=""){
					// $fileinput should point to a temp file on the server
					// which contains the uploaded image. so we will prepare
					// the file for upload with addslashes and form an sql
					// statement to do the load into the database.
					$image = addslashes(fread(fopen($fileinput,"r"), 1000000));
					$SQL = "Insert Into imagens (img_nome, img_oco, img_tipo, img_bin, img_largura, img_altura, img_size) values ".
							"('".noSpace($_FILES['img']['name'])."',".$_POST['numero'].", '".$_FILES['img']['type']."', ".
							"'".$image."', '".$tamanho[0]."', '".$tamanho[1]."', '".$tamanho2."')";
					// now we can delete the temp file
					unlink($fileinput);
				} /*else {
					echo "NENHUMA IMAGEM FOI SELECIONADA!";
					exit;
				}*/
				$exec = mysql_query($SQL);// or die ("NÃO FOI POSSÍVEL GRAVAR O ARQUIVO NO BANCO DE DADOS! ".$SQL);
				if ($exec == 0) $aviso.= "NÃO FOI POSSÍVEL ANEXAR O ARQUIVO!<br>";
			}

			$sqlMailLogado = "select * from usuarios where login = '".$_SESSION['s_usuario']."'";
			$execMailLogado = mysql_query($sqlMailLogado) or die('ERRO AO TESTAR RECUPERAR AS INFORMAÇÕES DO USUÁRIO!');
			$rowMailLogado = mysql_fetch_array($execMailLogado);

			$qryLocal = "select * from localizacao where loc_id=".$_POST['local']."";
			$execLocal = mysql_query($qryLocal);
			$rowLocal = mysql_fetch_array($execLocal);

			$qryfull = $QRY["ocorrencias_full_ini"]." WHERE o.numero = ".$_POST['numero']."";
			$execfull = mysql_query($qryfull) or die('ERRO, NÃO FOI POSSÍVEL RECUPERAR AS VARIÁVEIS DE AMBIENTE!'.$qryfull);
			$rowfull = mysql_fetch_array($execfull);

			$VARS = array();
			$VARS['%numero%'] = $rowfull['numero'];
			$VARS['%usuario%'] = $rowfull['contato'];
			$VARS['%contato%'] = $rowfull['contato'];
			$VARS['%descricao%'] = $rowfull['descricao'];
			$VARS['%setor%'] = $rowfull['setor'];
			$VARS['%ramal%'] = $rowfull['telefone'];
			$VARS['%assentamento%'] = $_POST['assentamento'];
			$VARS['%site%'] = "<a href='".$row_config['conf_ocomon_site']."'>".$row_config['conf_ocomon_site']."</a>";
			$VARS['%area%'] = $rowfull['area'];
			$VARS['%operador%'] = $rowfull['nome'];
			$VARS['%editor%'] = $rowMailLogado['nome'];
			$VARS['%problema%'] = $rowfull['problema'];
			$VARS['%versao%'] = VERSAO;

			$qryconf = "SELECT * FROM mailconfig";
			$execconf = mysql_query($qryconf) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE ENVIO DE E-MAIL!');
			$rowconf = mysql_fetch_array($execconf);

			if (isset($_POST['mailOP']) ){
				$event = 'edita-para-operador';
				$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
				$execmsg = mysql_query($qrymsg) or die('ERRO NO MSGCONFIG');
				$rowmsg = mysql_fetch_array($execmsg);

				$sqlMailOper = "select * from usuarios where user_id =".$_POST['operador']."";
				$execMailOper = mysql_query($sqlMailOper);
				$rowMailOper = mysql_fetch_array($execMailOper);

				$VARS['%operador%'] = $rowMailOper['nome'];
				send_mail($event, $rowMailOper['email'], $rowconf, $rowmsg, $VARS);
			}
			if (isset($_POST['mailAR'])){
				$event = 'edita-para-area';
				$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
				$execmsg = mysql_query($qrymsg) or die('ERRO NO MSGCONFIG');
				$rowmsg = mysql_fetch_array($execmsg);

				$sqlMailArea = "select * from sistemas where sis_id = ".$_POST['sistema']."";
				$execMailArea = mysql_query($sqlMailArea);
				$rowMailArea = mysql_fetch_array($execMailArea);

				send_mail($event, $rowMailArea['sis_email'], $rowconf, $rowmsg, $VARS);
			}
			if (isset($_POST['mailUS'])){
				$event = 'edita-para-usuario';
				$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
				$execmsg = mysql_query($qrymsg) or die('ERRO NO MSGCONFIG');
				$rowmsg = mysql_fetch_array($execmsg);


				$sqlMailUs = "select * from usuarios where user_id = ".$_POST['abertopor']."";
				$execMailUs = mysql_query($sqlMailUs) or die('NÃO FOI POSSÍVEL ACESSAR A BASE DE USUÁRIOS PARA O ENVIO DE EMAIL!');
				$rowMailUs = mysql_fetch_array($execMailUs);

				$qryresposta = "select u.*, a.* from usuarios u, sistemas a where u.AREA = a.sis_id and u.user_id = ".$_SESSION['s_uid']."";
				$execresposta = mysql_query($qryresposta) or die ('NÃO FOI POSSÍVEL IDENTIFICAR O EMAIL PARA RESPOSTA!');
				$rowresposta = mysql_fetch_array($execresposta);

				send_mail($event, $rowMailUs['email'], $rowconf, $rowmsg, $VARS);
			}


			$resultado3 = mysql_query($queryA) or die('NÃO FOI POSSÍVEL GRAVAR AS INFORMAÇÕES DE EDIÇÃO DO CHAMADO!<br>'.$queryA);

			if ($_POST['antes'] != $depois) //Status alterado!!
			{   //$status!=1 and
				if (($_POST['data_atend']==null) and ($_POST['status']!=4) and (isset($_POST['resposta'])) ) //para verificar se já foi setada a data do inicio do atendimento. //Se eu incluir um assentamento seto a data de atendimento
				{
					$query = "UPDATE ocorrencias SET operador=".$_POST['operador'].", problema = ".$_POST['problema'].", instituicao='".$_POST['institui']."', equipamento = '".$_POST['etiq']."', sistema = '".$_POST['sistema']."', local=".$_POST['local'].", data_fechamento=NULL, status=".$_POST['status'].", data_atendimento='".date('Y-m-d H:i:s')."' WHERE numero=".$_POST['numero']."";
					$resultado4 = mysql_query($query);
				}  else
				{
					$query = "UPDATE ocorrencias SET operador=".$_POST['operador'].", problema = ".$_POST['problema']." , instituicao='".$_POST['institui']."', equipamento = '".$_POST['etiq']."', sistema = '".$_POST['sistema']."', local=".$_POST['local'].", data_fechamento=NULL, status=".$_POST['status']." WHERE numero=".$_POST['numero']."";
					$resultado4 = mysql_query($query);
				}
			} else
			{
				if (($_POST['data_atend']==null) and ($_POST['status']!=4) and (isset($_POST['resposta']) )) //para verificar se já foi setada a data do inicio do atendimento. //Se eu incluir um assentamento seto a data de atendimento
				{
					$query = "UPDATE ocorrencias SET operador=".$_POST['operador'].", problema = ".$_POST['problema'].", instituicao='".$_POST['institui']."', equipamento = '".$_POST['etiq']."', sistema = '".$_POST['sistema']."', local=".$_POST['local'].", data_fechamento=NULL, status=".$_POST['status'].", data_atendimento='".date('Y-m-d H:i:s')."' WHERE numero=".$_POST['numero']."";
					$resultado4 = mysql_query($query);
				} else {
					$query = "UPDATE ocorrencias SET operador=".$_POST['operador'].", problema = ".$_POST['problema'].", instituicao='".$_POST['institui']."', equipamento = '".$_POST['etiq']."', sistema = '".$_POST['sistema']."', local=".$_POST['local'].", status=".$_POST['status']." WHERE numero=".$_POST['numero']."";
					$resultado4 = mysql_query($query);
				}
			}

			if (($resultado3==0) OR ($resultado4 == 0))
			{
				$aviso = "ERRO DE ACESSO. Um erro ocorreu ao tentar alterar ocorrência no sistema. - $query";
			}
			else
			{
				$sqlDoc1 = "select * from doc_time where doc_oco = ".$_POST['numero']." and doc_user=".$_SESSION['s_uid']."";
				$execDoc1 = mysql_query($sqlDoc1) or die('ERRO<br>'.$sqlDoc1);
				$regDoc1 = mysql_num_rows($execDoc1);
				$rowDoc1 = mysql_fetch_array($execDoc1);
				if ($regDoc1 >0) {
					$sqlDoc  = "update doc_time set doc_edit=doc_edit+".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s"))." where doc_id = ".$rowDoc1['doc_id']."";
					$execDoc =mysql_query($sqlDoc) or die ('ERRO NA TENTATIVA DE ATUALIZAR O TEMPO DE DOCUMENTAÇÃO DO CHAMADO!<br>').$sqlDoc;
				} else {
					$sqlDoc = "insert into doc_time (doc_oco, doc_open, doc_edit, doc_close, doc_user) values (".$_POST['numero'].", 0, ".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s"))." , 0, ".$_SESSION['s_uid'].")";
					$execDoc = mysql_query($sqlDoc) or die ('ERRO NA TENTATIVA DE ATUALIZAR O TEMPO DE DOCUMENTAÇÃO DO CHAMADO!!<br>').$sqlDoc;
				}

				##ROTINAS PARA GRAVAR O TEMPO DO CHAMADO EM CADA STATUS
				if ($_POST['status'] != $row['status']) { //O status foi alterado
				##TRATANDO O STATUS ANTERIOR
				//Verifica se o status 'atual' já foi gravado na tabela 'tempo_status' , em caso positivo, atualizo o tempo, senão devo gravar ele pela primeira vez.
					$sql_ts_anterior = "select * from tempo_status where ts_ocorrencia = ".$row['numero']." and ts_status = ".$row['status']." ";
					$exec_sql = mysql_query($sql_ts_anterior);

					if ($exec_sql == 0) $error= " erro 1";

					$achou = mysql_num_rows($exec_sql);
					if ($achou >0){ //esse status já esteve setado em outro momento
						$row_ts = mysql_fetch_array($exec_sql);

						// if (array_key_exists($row['sistema'],$H_horarios)){  //verifica se o código da área possui carga horária definida no arquivo config.inc.php
							// $areaT = $row['sistema']; //Recebe o valor da área de atendimento do chamado
						// } else $areaT = 1; //Carga horária default definida no arquivo config.inc.php

						$areaT = "";
						$areaT=testaArea($areaT,$row['sistema'],$H_horarios);

						$dt = new dateOpers;
						$dt->setData1($row_ts['ts_data']);
						$dt->setData2(date("Y-m-d H:i:s"));
						$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$areaT][0],$H_horarios[$areaT][1],$H_horarios[$areaT][2],$H_horarios[$areaT][3],"H");
						$segundos = $dt->diff["sValido"]; //segundos válidos

						$sql_upd = "update tempo_status set ts_tempo = (ts_tempo+".$segundos.") , ts_data ='".date("Y-m-d H:i:s")."' where ts_ocorrencia = ".$row['numero']." and
								ts_status = ".$_POST['status']." ";
						$exec_upd = mysql_query($sql_upd);
						if ($exec_upd ==0) $error.= " erro 2";

					} else {
						$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$row['numero'].", ".$_POST['status'].", 0, '".date("Y-m-d H:i:s")."' )";
						$exec_ins = mysql_query ($sql_ins);
						if ($exec_ins == 0) $error.= " erro 3 ";
					}
					##TRATANDO O NOVO STATUS
					//verifica se o status 'novo' já está gravado na tabela 'tempo_status', se estiver eu devo atualizar a data de início. Senão estiver gravado então devo gravar pela primeira vez
					$sql_ts_novo = "select * from tempo_status where ts_ocorrencia = ".$row['numero']." and ts_status = ".$_POST['status']." ";
					$exec_sql = mysql_query($sql_ts_novo);
					if ($exec_sql == 0) $error.= " erro 4";

					$achou_novo = mysql_num_rows($exec_sql);
					if ($achou_novo > 0) { //status já existe na tabela tempo_status
						$sql_upd = "update tempo_status set ts_data = '".date('Y-m-d H:i:s')."' where ts_ocorrencia = ".$row['numero']." and ts_status = ".$_POST['status']." ";
						$exec_upd = mysql_query($sql_upd);
						if ($exec_upd == 0) $error.= " erro 5";
					} else {//status novo na tabela tempo_status
						$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$row['numero'].", ".$_POST['status'].", 0, '".date("Y-m-d H:i:s")."' )";
						$exec_ins = mysql_query($sql_ins);
						if ($exec_ins == 0) $error.= " erro 6 ";
					}
				}

				$aviso = "";
				$aviso = "Ocorrência alterada com sucesso! ";

			}
		} //fecha if erro=nao

		$LOCK->unlock($_POST['numero']);
		print "<script>mensagem('".$aviso."'); redirect('mostra_consulta.php?numero=".$_POST['numero']."');</script>";

	}//fecha if submit

	//$LOCK->unlock();

?>
<script type="text/javascript">
<!--

	function valida(){
		var ok = validaForm('idStatus','COMBO','Status',1);
		if (ok) var ok = validaForm('idProblema','COMBO','Problema',1);
		if (ok) var ok = validaForm('idArea','COMBO','Área',1);

		if (ok) var ok = validaForm('idEtiqueta','INTEIROFULL','Etiqueta',0);
		if (ok) var ok = validaForm('idLocal','COMBO','Local',1);
		if (ok) var ok = validaForm('idAssentamento','','Assentamento',1);

		return ok;
	}

	function invertView(id) {
		var element = document.getElementById(id);
		var elementImg = document.getElementById('img'+id);
		var address = '../../includes/icons/';

		if (element.style.display=='none'){
			element.style.display='';
			elementImg.src = address+'close.png';
		} else {
			element.style.display='none';
			elementImg.src = address+'open.png';
		}
	}


-->
</script>
<?
print "</TABLE>";
print "</FORM>";
print "</body>";
print "</html>";
?>
