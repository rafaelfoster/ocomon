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
	$hoje = date("Y-m-d H:i:s");

	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);
?>
<BR>
<B><?php print $TRANS["head_inc_doc"]?>:</B>
<BR>

<FORM method="POST" action="<?php _SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="left" width="50%" bgcolor=<?php print BODY_COLOR?>>
        <TR>
                <TD width="30%" align="left" bgcolor=<?php print TD_COLOR?>><?php print $TRANS["cx_doc"]?>:</TD>
                <TD width="70%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text" name="mat_nome" id="idMaterial"></TD>
        </TR>
		<tr>	  
		  <TD width="30%" align="left" bgcolor=<?php print TD_COLOR?>><?php print $TRANS["cx_qtd"]?>:</TD>
                <TD width="70%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text" name="mat_qtd" id="idQtd"></TD>
        </TR>
        <TR>
                <TD width="30%" align="left" bgcolor=<?php print TD_COLOR?>><?php print $TRANS["cx_caixa"]?>:</TD>
                <TD width="70%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text" name="mat_caixa" id="idCaixa"></TD>
        </TR>
		
	<?php // TRADUZIR//?>	
		<tr>
                	<TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Associado ao modelo:</TD>
	                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
						<?php 
							print "<SELECT name='modelo' class='text' size=1 id='idModelo'>";
	        		        print "<option value= '-1'>Selecione o modelo</option>";
			
							$sql="select * from marcas_comp order by marc_nome";
							$commit = mysql_query($sql);
							$i=0;
							while($row = mysql_fetch_array($commit)){
								print "<option value=".$row['marc_cod'].">".$row["marc_nome"]."</option>";
								$i++;
							} // while
							print "</select>";
						//FIM DO TRECHO PARA TRADUZIR
						?>
             	    </TD>		
		</tr>	
		
		
		<tr>
                <TD width="30%" align="left" bgcolor=<?php print TD_COLOR?>><?php print $TRANS["cx_coment"]?>:</TD>
                <TD width="70%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text" name="mat_obs" id="idObs"></TD>
        </TR>

        <TR>
                <BR>
                <TD align="right" width="20%" bgcolor=<?php print BODY_COLOR?>><input type="submit" value="<?php print $TRANS["bt_cadastrar"]?>" name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="80%" bgcolor=<?php print BODY_COLOR?>><INPUT type="reset" value="<?php print $TRANS["bt_cancelar"]?>" onClick="javascript:redirect('abertura.php')"></TD>
        </TR>

        <?php 
                if ($rodou == "sim")
                {
                        $erro="não";

                        if (empty($mat_nome) or ($mat_caixa==""))
                        {
                                $aviso = $TRANS["alerta_dados_incompletos"];
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM materiais WHERE mat_nome='$mat_nome'";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        if ($linhas >=1)
                        {
                                $aviso = $TRANS["alerta_ja_cadastrado"];
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM materiais";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);
                        $num=0;
                        if ($linhas>0)
                                $num = mysql_result($resultado,$linhas-1,0);
                        $num++;

                        if ($erro == "não")
                        {
                                $data=$hoje;
                                $query = "INSERT INTO materiais (mat_nome, mat_qtd, mat_caixa, mat_data, mat_obs, mat_modelo_equip) values ('".noHtml($mat_nome)."','$mat_qtd',".noHtml($mat_caixa).",'$data','".noHtml($mat_obs)."', $modelo)";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = $TRANS["alerta_erro_incluir"]."!";
                                }
                                else
                                {
                                        $aviso = $TRANS["alerta_sucesso_incluir"]."!";
                                }
                        }
                        $origem = "incluir_material.php";
                        session_register("aviso");
                        session_register("origem");
                        //echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                ?>
						<script language="javascript">
						<!--
							mensagem('<?php print $aviso;?>');
							history.back();
						//-->
						</script>
				<?php 				
				
				
				}
        ?>


</TABLE>
</FORM>

</body>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idMaterial','','Documento',1);
		if (ok) var ok = validaForm('idQtd','INTEIRO','Quantidade',1);
		if (ok) var ok = validaForm('idCaixa','INTEIRO','Caixa',1);
		//if (ok) var ok = validaForm('idModelo','COMBO','Modelo',1);
		if (ok) var ok = validaForm('idObs','','Comentário',1);
		
		return ok;
	}		
-->	
</script>
</html>
