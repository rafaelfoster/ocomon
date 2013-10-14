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

	if ($popup) {
		$fecha = "window.close()";
		$caption = "Fechar";
	} else {
		$fecha = "history.back()";
		$caption = "Voltar";
	}

	print "<BR><a href='marcas_comp.php'>Lista modelos cadastrados</a><br>";
<B>Inclusão de modelos de equipamentos:</B>
<BR>

<FORM method="POST" action='<?php _SELF?>'  ENCTYPE="multipart/form-data">
<TABLE border="0"  align="left" width="40%" bgcolor=<?php print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Modelo:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class='text' name="marc_nome"></TD>
        </TR>

        <tr>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Tipo de equipamento:</TD>

                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><select class='select' name="tipo">
				<option value=-1>Selecione o tipo</option>
				<?php 
					$sql="select * from tipo_equip order by tipo_nome";
					$commit = mysql_query($sql);
	                $linhas = mysql_numrows($commit);

					$i=0;
					while($i<$linhas ){
						$resultado_cod = mysql_result($commit,$i,0);
						$resultado_nome = mysql_result($commit,$i,1);
						print "<option value=$resultado_cod>$resultado_nome</option>";
						$i++;
					} // while
				print "</td>";
				print "</select>";
			print "</tr>";

                print "<td width='20%' bgcolor='".TD_COLOR."'><b>Anexar imagem</b></td>";
		print "<TD width='80%' align='left' bgcolor=".BODY_COLOR."><INPUT type='file' class='text' name='img' id='idImg'></TD>";


				?>




        <TR>

                <TD align="center" width="50%" bgcolor=<?php print BODY_COLOR?>><input type="submit"  value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="50%" bgcolor=<?php print BODY_COLOR?>><INPUT type="reset"  value="<?php print $caption;?>" name="cancelar" onClick="javascript:<?php print $fecha;?>;"></TD>
        </TR>

        <?php 
                if ($rodou == "sim")
                {
                        $erro="não";

                        if (empty($marc_nome))
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

			if (isset($_FILES['img']) and $_FILES['img']['name']!="") {
				$qryConf = "SELECT * FROM config";
				$execConf = mysql_query($qryConf) or die ("NÃO FOI POSSÍVEL ACESSAR AS INFORMAÇÕES DE CONFIGURAÇÃO, A TABELA CONF FOI CRIADA?");
				$rowConf = mysql_fetch_array($execConf);
				$arrayConf = array();
				$arrayConf = montaArray($execConf,$rowConf);

				$upld = upload('img',$arrayConf);
				if ($upld =="OK") {
					$gravaImg = true;
				} else {
					$upld.="<br><a align='center' onClick=\"exibeEscondeImg('idAlerta');\"><img src='".ICONS_PATH."/stop.png' width='16px' height='16px'>&nbsp;Fechar</a>";
					print "</table>";
					print "<div class='alerta' id='idAlerta'><table bgcolor='#999999'><tr><td colspan='2' bgcolor='yellow'>".$upld."</td></tr></table></div>";
					exit;
				}
			}



                        $query = "SELECT * FROM marcas_comp WHERE marc_nome='$marc_nome'";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        if ($linhas > 0)
                        {
                                $aviso = "Esse modelo já está cadastrado!";
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM marcas_comp";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);
                        $num=0;
                        if ($linhas>0)
                                $num = mysql_result($resultado,$linhas-1,0);
                        $num++;

                        if ($erro == "não")
                        {
                                $query = "INSERT INTO marcas_comp (marc_nome,marc_tipo) values ('$marc_nome',$tipo)";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao incluir modelo.";
                                }
                                else
                                {
                                        $modelCod = mysql_insert_id();

                                       $anexado = false;
					if ($gravaImg) {
						//INSERÇÃO DA IMAGEM NO BANCO
						$fileinput=$_FILES['img']['tmp_name'];
						$tamanho = getimagesize($fileinput);

						if(chop($fileinput)!=""){
							// $fileinput should point to a temp file on the server
							// which contains the uploaded image. so we will prepare
							// the file for upload with addslashes and form an sql
							// statement to do the load into the database.
							$image = addslashes(fread(fopen($fileinput,"r"), 1000000));
							$SQL = "Insert Into imagens (img_nome, img_model, img_tipo, img_bin, img_largura, img_altura) values ".
									"('".$_FILES['img']['name']."', '".$modelCod."', '".$_FILES['img']['type']."', '".$image."', ".$tamanho[0].", ".$tamanho[1].")";
							// now we can delete the temp file
							unlink($fileinput);
							$anexado = true;
						} /*else {
							echo "NENHUMA IMAGEM FOI SELECIONADA!";
							exit;
						}*/
						$exec = mysql_query($SQL); //or die ("NÃO FOI POSSÍVEL GRAVAR O ARQUIVO NO BANCO DE DADOS! ");
						if ($exec == 0) $aviso.= "NÃO FOI POSSÍVEL ANEXAR A IMAGEM!<br>";

					}



                                       $aviso = "OK. Modelo incluido com sucesso.";
                                }
                        }

			if ($anexado) {
				$direciona = "altera_dados_marca_comp.php?marc_cod=".$modelCod;
			} else {
				$direciona = "marcas_comp.php";
			}

                   print "<script>mensagem('".$aviso."');  window.opener.location.reload(); redirect('".$direciona."');</script>";
                }
        ?>


</TABLE>
</FORM>

</body>
</html>
