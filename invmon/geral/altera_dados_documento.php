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
       // $query = "select * from materiais where mat_cod='$mat_cod'";
		$query = "SELECT mat.* , marc.* 
								FROM materiais AS mat
								LEFT  JOIN marcas_comp as marc ON mat.mat_modelo_equip = marc.marc_cod where mat.mat_cod='$mat_cod' ";        
		$resultado = mysql_query($query);
		$row = mysql_fetch_array($resultado);

?>
<BR>
<B>Alterar Registro:</B>
<BR>

<FORM method="POST" action="<?php $PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="left" width="40%" bgcolor=<?php print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Código:</TD>
                 <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><?php print $row['mat_cod'];?></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Material:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class='text' name="mat_nome" id="idMaterial" value="<?php print $row['mat_nome'];?>" maxlength="100" size="100"></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Quantidade:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class='text'  name="mat_qtd" id="idQtd" value="<?php print $row['mat_qtd'];?>" maxlength="100" size="100"></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Caixa:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class='text'  name="mat_caixa" id="idCaixa" value="<?php print $row['mat_caixa'];?>" maxlength="100" size="100"></TD>
        </TR>
        <TR>
                	<TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Modelo Associado:</TD>
	                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
						<?php 
							print "<SELECT name='modelo' class='text' size=1>";
							print "<option value= '-1'>Selecione o modelo</option>";
							$query = "SELECT * from marcas_comp order by marc_nome";
							$exec_marc = mysql_query($query);
							while ($row_marc = mysql_fetch_array($exec_marc))
	                				{
								print "<option value=".$row_marc['marc_cod']."";
								if ($row_marc['marc_cod'] == $row['mat_modelo_equip']) {
									print " selected";
								}
								print " >".$row_marc['marc_nome']." </option>";
							}
							print "</select>";
						?>
             	    </TD>		
		</TR>
		
		
		<TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Data:</TD>
                 <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><?php print $row['mat_data'];?></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Obs:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class='text'  name="mat_obs" id="idObs" value="<?php print $row['mat_obs'];?>" maxlength="100" size="100"></TD>
        </TR>

        <TR>
                <TD align="center" width="20%" bgcolor=<?php print BODY_COLOR?>><input type="submit" value="  Ok  " name="Liberar">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="80%" bgcolor=<?php print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar"></TD>
        </TR>
        <TR>
                <TD colspan='2' align="center" width="100%" bgcolor=<?php print BODY_COLOR?>><a href='javascript:history.back()'>Voltar</a></TD>
        </TR>

        <?php 
                if ($rodou == "sim")
                {
                        $erro = "não";

                        if ($erro == "não")
                        {

                          $data = datam($hoje);
                         $query = "UPDATE materiais SET mat_nome='".noHtml($mat_nome)."', mat_qtd='$mat_qtd', mat_caixa='$mat_caixa', mat_obs='".noHtml($mat_obs)."', mat_modelo_equip=$modelo WHERE (mat_cod=$mat_cod)";
                         $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "Um erro ocorreu ao tentar alterar o Registro.";
                                }
                                else
                                {
                                        $aviso = "Registro Alterado com sucesso no sistema.";
                                }
                        }
                        print "<script>mensagem('$aviso'); redirect('materiais.php')</script>";
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
