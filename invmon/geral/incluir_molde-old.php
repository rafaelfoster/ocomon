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
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);


	$hoje = date("Y-m-d H:i:s");
	$hojeLog = date("d-m-Y H:i:s");
	$nulo = null;
		
	$habilita = "disabled";

		

	print "<BR>";
	print "<B>".$TRANS["head_inc_mold_equip"].":"; 
	print "<BR><a href='mostra_modelos_cadastrados.php'>Lista os modelos de configuração já cadastrados</a>";

print "<FORM name='form1' method='POST' action='$PHP_SELF'>";
?>
<TABLE border="0" colspace="3" width="100%" bgcolor=<?php print BODY_COLOR?>>
       
		<tr> <td colspan="4"></td> <b> <?php print $TRANS["dados_gerais"];?>:</b></td></tr>

		<tr>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><a title="Campo obrigatório - Defina o tipo de equipamento desse modelo"><?php print $TRANS["cx_tipo"]?>:</a></b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                 <SELECT class='select' name='comp_tipo_equip' size=1 > <!--onchange='document.form1.submit()' -->
                
				
				<?php 
					
					print "<option value=-1 selected>".$TRANS["cmb_selec_equip"].": </option>";
                $query = "SELECT * from tipo_equip  order by tipo_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
              	$i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                ?>
                </SELECT>
                </TD>

				<TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><a title="Campo obrigatório - Selecione o nome do fabricante do equipamento"><?php print $TRANS["cx_fab"]?>:*</a> </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_fab' size=1>";
                
							
				print "<option value=-1>".$TRANS["cmb_selec_fab"].": </option>";
                $query = "SELECT * from fabricantes  order by fab_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
              	$i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);
					   echo "\"";
					   if (mysql_result($resultado,$i,0)==$row['fab_cod']) {
					     echo "selected";  
					   }
					   ?>>
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                ?>
                </SELECT>
                </TD>
 		</tr>
		
		
		
		<TR>

                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><a title="Campo obrigatório - Preencha com o número da etiqueta que foi colada ao equipamento"><?php print $TRANS["cx_etiqueta"]?>:</a></b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text"name="comp_inv"  value="<?php $comp_inv?>" disabled></TD>

                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_sn"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text"name="comp_sn" disabled></TD>
       </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><a title="Campo obrigatório - Selecione o modelo do equipamento que está cadastrando"><?php print $TRANS["cx_modelo"]?>*:</a></b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_marca' size=1>";


                print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "SELECT * from marcas_comp order by marc_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                ?>
                </SELECT>
				</td>						
					
			
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><a title="Campo Obrigatório - Selecione o setor onde este equipamento está localizado"><?php print $TRANS["cx_local"]?>:</a></b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_local' size=1 disabled>";
                print "<option value=-1 selected>".$TRANS["cmb_selec_local"]."</option>";
                $query = "SELECT * from localizacao  order by local";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                ?>
                </SELECT>
                </TD>			
		</tr>
        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><a title="Campo Obrigatório - Selecione a situação do equipamento"><?php print $TRANS["cx_situacao"]?>:</a></b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_situac' size=1 disabled>";


                print "<option value=-1 selected>".$TRANS["cmb_selec_situacao"]."</option>";
				$query = "SELECT * from situacao order by situac_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                ?>
                </SELECT>
                </TD>
		</tr>			
 
 
 
   <!--  --------------------------------------------------------------------------------------- -->

	
	<TR>
		<td colspan="4"></td>
    </TR>		
	<tr> <td colspan="3"><b><?php print $TRANS["dados_config"];?>:</b></td><td class='line'><input type="button" class="button" value="<?php print $TRANS["bt_componente"]?>" Onclick="return popup_alerta('incluir_item.php?popup=true')"></td></tr>
	<TR>
		<td colspan="4"></td>
    </TR>		


   
   <!--  --------------------------------------------------------------------------------------- --> 
   
        <tr>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_nome"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text" name="comp_nome" maxlength="15" size="15"></TD>
         
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_mb"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select'  name='comp_mb' size=1>";
                
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 10 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
                
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				?>
					
		                
				</SELECT>
                </TD>
		 </tr>
	   
	    <tr>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_proc"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_proc' size=1>";
                
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
                
				$query = "select * from modelos_itens where mdit_tipo = 11 order by mdit_fabricante, mdit_desc, mdit_desc_capacidade";
				$commit = mysql_query($query);
				$sufixo = "MHZ";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
                print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				?>
											                                
				</SELECT>
                </TD>


                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_memo"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_memo' size=1>";
                
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 7 order by mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "MB";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
                print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				?>
								                                
				</SELECT>
                </TD>
			</tr>

        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_video"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_video' size=1>";
                
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 2 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
                print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				?>
								                                
				</SELECT>
                </TD>

                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_som"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_som' size=1>";
                
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 4 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
                print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				?>
							                                
				</SELECT>
                </TD>
		</tr>
        
		<TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_rede"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_rede' size=1>";
                
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 3 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
                print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				?>
								                                
				</SELECT>
                </TD>


                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_modem"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_modem' size=1>";
                
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 6 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
                ?>
								                                
				</SELECT>
                </TD>
		</tr>


        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_hd"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_modelohd' size=1>";
                
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 1 order by mdit_fabricante, mdit_desc_capacidade";
				$commit = mysql_query($query);
				$sufixo = "GB";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
                print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				?>
							                                
				</SELECT>
                </TD>

                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_grav"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_grav' size=1>";
                
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 9 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
                print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				?>
								                                
				</SELECT>
                </TD>
        </tr>
		
		<TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_cdrom"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_cdrom' size=1>";
                
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 5 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
                print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				?>
								                                
				</SELECT>
                </TD>



                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_dvd"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_dvd' size=1>";
                
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				$query = "select * from modelos_itens where mdit_tipo = 8 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value=".$rowA['mdit_cod'].">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade'].$sufixo."</option>";
				
				} // while
                print "<option value=-1 selected>".$TRANS["cmb_selec_modelo"]."</option>";
				?>
							                                
				</SELECT>
                </TD>
			</tr>


	<TR>
		<td colspan="4"></td>
    </TR>		
	<tr> 
		<td colspan="4"><b><?php print $TRANS["dados_extra"];?>:</b></td>
	</tr>
	
	<TR>
		<td colspan="4"></td>
    </TR>		
	
	
        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_impressora"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_tipo_imp' size=1>";
                
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_imp"].": </option>";
                $query = "SELECT * from tipo_imp  order by tipo_imp_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                print "<option value=-1 selected>".$TRANS["cmb_selec_imp"].": </option>";
				?>
							                                
				</SELECT>
                </TD>
        


                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_monitor"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_polegada' size=1>";
                
				
				print "<option value =-1 selected>".$TRANS["cmb_selec_monitor"].": </option>";
                $query = "SELECT * from polegada  order by pole_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                print "<option value =-1 selected>".$TRANS["cmb_selec_monitor"].": </option>";
				?>
									                                
				</SELECT>
                </TD>
              </tr>
			  <tr>  
				
				<TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_scanner"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_resolucao' size=1>";
                
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_scanner"].": </option>";
                $query = "SELECT * from resolucao  order by resol_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                print "<option value=-1 selected>".$TRANS["cmb_selec_scanner"].": </option>";
				?>
								                                
				</SELECT>
                </TD>
 			</tr>		
		
       
	<TR>
		<td colspan="4"></td>
    </TR>		
	<tr> <td colspan="4"><b> <?php print $TRANS["dados_contab"];?>:</b></td></tr>
	<TR>
		<td colspan="4"></td>
    </TR>		

         


        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><a title="Campo Obrigatório - Selecione a Unidade proprietária desse equipamento"><?php print $TRANS["cx_inst"]?>:</a></b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_inst' size=1>";
                
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_inst"]." </option>";
                $query = "SELECT * from instituicao  order by inst_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                
				?>
                </SELECT>
                </TD>

                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_cc"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_ccusto' size=1 disabled>";
				
				print "<option value = -1 selected>".$TRANS["cmb_selec_cc"]." </option>";
                $query = "SELECT * from planejamento.CCUSTO where ano='2003' order by descricao";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,3)."......:".mysql_result($resultado,$i,4);?>
                       </option>
                       <?php 
                       $i++;
                }
                
				?>
                </SELECT>
                </TD>
		</tr>
		 
		 
		 

        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_fornecedor"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_fornecedor' size=1 disabled>";
                
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_fornecedor"]."</option>";
                $query = "SELECT * from fornecedores  order by forn_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                ?>
                </SELECT>
                </TD>

                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_nf"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text"name="comp_nf" disabled></TD>
   	</tr>


        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_valor"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text"name="comp_valor" disabled></TD>

                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_data_compra"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text"name="comp_data_compra" disabled></TD>
        </tr>

		
<!--
#################################################################################
-->		
		


        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_tipo_garantia"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_tipo_garant' size=1 disabled>";
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_tipo"]."</option>";
                $query = "SELECT * from tipo_garantia  order by tipo_garant_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                print "<option value=-1>".$TRANS["cmb_selec_tipo"]."</option>";
				?>
								                                
				</SELECT>
                </TD>



                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_tempo_garantia"]?>: </b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select' name='comp_garant_meses' size=1 disabled>";
                
				
				print "<option value=-1 selected>".$TRANS["cmb_selec_tempo"]."</option>";
                $query = "SELECT * from tempo_garantia  order by tempo_meses";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1).' meses';?>
                       </option>
                       <?php 
                       $i++;
                }
                print "<option value=-1>".$TRANS["cmb_selec_tempo"]."</option>";
				?>
									                                
				</SELECT>
                </TD>
			</tr>


<!--
#################################################################################
-->		

		
		<tr>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_coment"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text"name="comp_coment" maxlength="200" size="100"></TD>
        </TR>



        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b><?php print $TRANS["cx_data_cadastro"]?>:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>><?php print datab($hoje);?></TD>
        </TR>

			

        <TR>
                <TD colspan="2"  align="right" bgcolor=<?php print BODY_COLOR?>><input type="submit"  value="<?php print $TRANS["bt_cadastrar"]?>" name="ok" title="Cadastrar as informações fornecidas." disabled >
                      <!--  <input type="hidden" name="rodou" value="sim"> -->
                </TD>
                <TD colspan="2" align="right" bgcolor=<?php print BODY_COLOR?>><INPUT type="reset" value="<?php print $TRANS["bt_cancelar"]?>" onClick="javascript:history.back()"></TD>
        </TR>

</TABLE>
</FORM>


<script type="text/javascript">
<!--

	function desabilita(v)
	{
		document.form1.ok.disabled=v;
	}
 
	function Habilitar(){
		//var inventario = document.form1.comp_inv.value;
		var ind_tipo_equip = document.form1.comp_tipo_equip.selectedIndex;
		var sel_tipo_equip = document.form1.comp_tipo_equip.options[ind_tipo_equip].value;
		var ind_comp_marca = document.form1.comp_marca.selectedIndex;
		var sel_comp_marca = document.form1.comp_marca.options[ind_comp_marca].value;
		var ind_fab = document.form1.comp_fab.selectedIndex;
		var sel_fab = document.form1.comp_fab.options[ind_fab].value;
		//var ind_local = document.form1.comp_local.selectedIndex;
		//var sel_local = document.form1.comp_local.options[ind_local].value;
		//var ind_sit = document.form1.comp_situac.selectedIndex;
		//var sel_sit = document.form1.comp_situac.options[ind_sit].value;
		//var ind_inst = document.form1.comp_inst.selectedIndex;
		//var sel_inst = document.form1.comp_inst.options[ind_inst].value;
			
			
			if ((sel_tipo_equip==-1)||(sel_comp_marca==-1)||(sel_fab==-1))
			{
				desabilita(true);
			
			} else {
				desabilita(false);

			}
		
	}
	window.setInterval("Habilitar()",100);

	
	function monta_modelos(){

		;
	}
	
	
//-->
</script>      

		
		
		
		<?php 

                if ($ok=="Cadastrar")             
                {
                        $erro="não";

#############################################

                        $query2 = "SELECT m.* FROM moldes as m 
									WHERE (m.mold_marca='$comp_marca')";
						
						$resultado2 = mysql_query($query2);
                        $linhas = mysql_numrows($resultado2);
                        if ($linhas > 0)
                        {
                                $aviso = "Este modelo já possui configuração cadastrada no sistema!";
                                $erro = "sim";
                        }
						
############################################

                        if (($comp_marca==-1))
                        {
                                $aviso = "Selecione o modelo associar essa configuração";
                                $erro = "sim";
                        }


                        if ($erro=="não")
                        {


                                $data = $hoje;
								if ($comp_sn == -1) { $comp_sn = "null";} else $comp_sn = "'$comp_sn'";
								if ($comp_mb == -1) { $comp_mb = "null";} else $comp_mb = "'$comp_mb'";
								if ($comp_proc == -1) { $comp_proc = "null";} else $comp_proc = "'$comp_proc'";	
								if ($comp_memo == -1) { $comp_memo = "null";} else $comp_memo = "'$comp_memo'";	
								if ($comp_video == -1) { $comp_video = "null";} else $comp_video = "'$comp_video'";
								if ($comp_som == -1) { $comp_som = "null";} else $comp_som = "'$comp_som'";
								if ($comp_rede == -1) { $comp_rede = "null";} else $comp_rede = "'$comp_rede'";
								if ($comp_modelohd == -1) { $comp_modelohd = "null";} else $comp_modelohd = "'$comp_modelohd'";
								if ($comp_modem == -1) { $comp_modem = "null";} else $comp_modem = "'$comp_modem'";
								if ($comp_cdrom == -1) { $comp_cdrom = "null";} else $comp_cdrom = "'$comp_cdrom'";
								if ($comp_dvd == -1) { $comp_dvd = "null";} else $comp_dvd = "'$comp_dvd'";
								if ($comp_grav == -1) { $comp_grav = "null";} else $comp_grav = "'$comp_grav'";
								if ($comp_nome == -1) { $comp_nome = "null";} else $comp_nome = "'$comp_nome'";
								if ($comp_nf == -1) { $comp_nf = "null";} else $comp_nf = "'$comp_nf'";
								if ($comp_coment == -1) { $comp_coment = "null";} else $comp_coment = "'$comp_coment'";
								if ($comp_ccusto == -1) { $comp_ccusto = "null";} else $comp_ccusto = "'$comp_ccusto'";
								if ($comp_tipo_imp == -1) { $comp_tipo_imp = "null";} else $comp_tipo_imp = "'$comp_tipo_imp'";
								if ($comp_resolucao == -1) { $comp_resolucao = "null";} else $comp_resolucao = "'$comp_resolucao'";
								if ($comp_polegada == -1) { $comp_polegada = "null";} else $comp_polegada = "'$comp_polegada'";						    
								if ($comp_fornecedor == -1) { $comp_fornecedor = "null";} else $comp_fornecedor = "'$comp_fornecedor'";						    
								

                                        $query = "INSERT INTO moldes (mold_inv, mold_sn, mold_marca, mold_mb, mold_proc, mold_memo, mold_video, mold_som,
                                                  mold_rede, mold_modelohd, mold_modem, mold_cdrom, mold_dvd, mold_grav, mold_nome, mold_local,
                                                  mold_fornecedor, mold_nf, mold_coment, mold_data, mold_valor, mold_data_compra, mold_inst,
												  mold_ccusto, mold_tipo_equip, mold_tipo_imp, mold_resolucao, mold_polegada, mold_fab)
												   VALUES ('$comp_inv',$comp_sn,'$comp_marca',$comp_mb,$comp_proc,$comp_memo,$comp_video,$comp_som,
                                                  $comp_rede,$comp_modelohd,$comp_modem,$comp_cdrom,$comp_dvd,$comp_grav,$comp_nome,
                                                  '$comp_local',$comp_fornecedor,$comp_nf,$comp_coment,'$data','$comp_valor','$comp_data_compra',
												  '$comp_inst', $comp_ccusto, '$comp_tipo_equip',$comp_tipo_imp,$comp_resolucao,
												  $comp_polegada,'$comp_fab')";
                                        $resultado = mysql_query($query);
									//	echo "$query";
									//	exit;

                                if ($resultado == 0)
                                {
                                        print $query;

                                        $aviso = "ERRO na inclusão dos dados.";
                                }
                                else
                                {
                                        $numero = mysql_insert_id();                                                 //$numero
                                        $aviso = "OK. Configuração do modelo cadastrada com sucesso.<BR>Código: <font color=red>$comp_inv</font>";

                                }
                        }
					print "<script>mensagem('".$aviso."'); redirect('mostra_modelos_cadastrados.php');</script>";
			}				
                


	$cab->set_foot();	              

 
  ?>      


