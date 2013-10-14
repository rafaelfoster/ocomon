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
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;


		if ($popup) {
			$auth->testa_user_hidden($s_usuario,$s_nivel,$s_nivel_desc,2);
		} else
			$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);


        $query = "select * from marcas_comp where marc_cod='".$_GET['marc_cod']."' ";
        $resultado = mysql_query($query) or die ('NÃO FOI POSSÍVEL RECUPERAR AS INFORMAÇÕES DOS MODELOS'.$query);

	?>
<BR>
<B>Alterar dados do modelo de equipamento</B>
<BR>

<FORM method="POST" action='<?php $PHP_SELF?>' ENCTYPE="multipart/form-data">
<TABLE border="0"  align="center" width="100%">
        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Modelo:</TD>
                <TD width="80%" align="left"><INPUT type="text" class='text' name="marc_nome" value="<?php print mysql_result($resultado,0,1);?>"></TD>
        </TR>
        
        <TR>
                <TD width="20%" align="left" valign="top"  bgcolor=<?php print TD_COLOR?>>Tipo de equipamento:</TD>
                <TD width="80%" align="left"><select name="tipo" class='select'>
        <?php 
			$atual = mysql_result($resultado,0,2);
			$sql = "select * from tipo_equip where tipo_cod=$atual";
			$commit = mysql_query($sql);
			$qtd = mysql_num_rows($commit);
			$nome = mysql_result($commit,0,1);
				print "<option value=$atual selected>$nome</option>";
			
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
		
		print "</select>";
		print "</td>";
		
		print "</tr>";
		print "<tr>";		
                print "<td width='20%' bgcolor='".TD_COLOR."'><b>Anexar imagem</b></td>";
		print "<TD width='80%' align='left'><INPUT type='file' class='text' name='img' id='idImg'></TD>";
		print "</tr>";
		
		$qryTela3 = "select  i.* from imagens i  WHERE i.img_model = '".$_GET['marc_cod']."'  order by i.img_inv ";					
		$execTela3 = mysql_query($qryTela3) or die ("NÃO FOI POSSÍVEL RECUPERAR AS INFORMAÇÕES DA TABELA DE IMAGENS!");
		//$rowTela = mysql_fetch_array($execTela);
		$isTela3 = mysql_num_rows($execTela3);
		$cont = 0;
		while ($rowTela3 = mysql_fetch_array($execTela3)) {
		//if ($isTela !=0) {		
			$cont++;
			print "<tr>";
		
			print "<TD  width='20%' bgcolor='".TD_COLOR."' >Imagem ".$cont." do modelo:</td>";
			print "<td colspan='3'><a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$rowTela3['img_cod']."&cod=".$rowTela3['img_cod']."',".$rowTela3['img_largura'].",".$rowTela3['img_altura'].")\"><img src='../../includes/icons/attach2.png'>".$rowTela3['img_nome']."</a>";
			print "<input type='checkbox' name='delImg[".$cont."]' value='".$rowTela3['img_cod']."'><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></TD>";
			
			print "</tr>";
		}
		
		
		?>
		<TR>
                <TD align="center" width="20%"><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                         <input type="hidden" name="cont" value=<?php print $cont;?>
                </TD>
                <TD align="center" width="80%"><INPUT type="reset" value="Cancelar" name="cancelar" onClick="javascript:window.close();"></TD>
        </TR>

        <?php 
                if ($rodou == "sim")
                {
                        $erro = "não";

                        if ($erro == "não")
                        {
                         
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
                         
			for ($j=1; $j<=$_POST['cont']; $j++) {
				if ($_POST['delImg'][$j]){
					$qryDel = "DELETE FROM imagens WHERE img_cod = ".$_POST['delImg'][$j]."";
					$execDel = mysql_query($qryDel) or die ("NÃO FOI POSSÍVEL EXCLUIR A IMAGEM!");
				}
				
			}
                         
                         
                         $query = "UPDATE marcas_comp SET marc_nome='$marc_nome',marc_tipo=$tipo WHERE marc_cod='$marc_cod'";
                         $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "Um erro ocorreu ao tentar alterar dados da marca.";
                                }
                                else
                                {
                                        
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
									"('".$_FILES['img']['name']."', '".$marc_cod."', '".$_FILES['img']['type']."', '".$image."', ".$tamanho[0].", ".$tamanho[1].")";
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
                                        
                                        $aviso = "Dados da marca alterados com sucesso.";
                                }
                        }
					if ($anexado) {
						$direciona = "altera_dados_marca_comp.php?marc_cod=".$_GET['marc_cod'];
					} else {
						$direciona = "marcas_comp.php";
					}
					print "<script>mensagem('".$aviso."'); window.opener.location.reload(); redirect('".$direciona."');</script>";
                }
        ?>

</TABLE>
</FORM>

</body>
</html>
