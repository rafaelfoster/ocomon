<?php 
# Inlcuir comentários e informações sobre o sistema
#
#################################################################################
#                                  CHANGELOG                                   #
################################################################################
#  incluir um changelog
################################################################################

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);

	$hoje = date("Y-m-d H:i:s");

    $cor1 = TD_COLOR;

?>
<BR>
<B><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Consulta personalizada (visualização normal ou como relatório): </B></font></font>
<BR>

<FORM method="POST" action=mostra_consulta_comp.php>
<TABLE border="0"  align="left" width="100%"  bgcolor=<?php print BODY_COLOR?>>
        
	<tr><td colspan="4"></td></tr>
		<tr>
		<td colspan="4"><b>Dados complementares - GERAIS:</b></td><td class='line'></td><td class='line'></td><td class='line'></td>
        </tr>
	<tr><td colspan="4"></td></tr>
		
        <tr>
				<TD align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Tipo de equipamento: </b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_tipo_equip' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
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
				<TD align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Fabricante: </font></font></b></TD>
                <TD align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_fab' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
                $query = "SELECT * from fabricantes  order by fab_nome";
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
		<tr>

                <TD align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Etiqueta:</b></TD>
                <TD align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_inv" maxlength="200" size="15"></TD>

                <TD align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Número de Série:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_sn" maxlength="30" size="30"></TD>

		</tr>    

     	<tr>
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Modelo:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_marca' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
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
                </TD>
			
			
			
			
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Localização:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_local' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
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
                <TD align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Situação:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_situac' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
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

		<tr><td colspan="4"></td></tr>
		<tr><td colspan="4"><b>Dados complementares - COMPUTADORES:</b></td></tr>
		<tr><td colspan="4"></td></tr>


   
   <!--  --------------------------------------------------------------------------------------- --> 
   
        <tr>
                <TD align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Nome do computador:</b></TD>
                <TD align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_nome" maxlength="15" size="15"></TD>
         
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>MB:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_mb' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
				$query = "select * from modelos_itens where mdit_tipo = 10 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
                </SELECT>
                </TD>
        
		

        </tr>
       
		
		
		
	
	   
	   
	   
	    <tr>

                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b>Processador:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_proc' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
				$query = "select * from modelos_itens where mdit_tipo = 11 order by mdit_fabricante,mdit_desc,mdit_desc_capacidade";
				$commit = mysql_query($query);
				$sufixo = "MHZ";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
                </SELECT>
                </TD>


                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b>Memória RAM:</b><input type='radio' name='comparaMemo' value='igual' checked='checked'>=<input type='radio' name='comparaMemo' value='menor'><<input type='radio' name='comparaMemo' value='maior'>></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_memo' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
				$query = "select * from modelos_itens where mdit_tipo = 7 order by mdit_fabricante, mdit_desc, mdit_desc_capacidade";
				$commit = mysql_query($query);
				$sufixo = "MB";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
                
				<option value=-2>Não nulo</option>
				<option value=-3>Nulo</option>
				</SELECT>
                </TD>
			   </tr>






        <TR>
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b>Placa de vídeo:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_video' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
				$query = "select * from modelos_itens where mdit_tipo = 2 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
                </SELECT>
                </TD>




                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Placa de som:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_som' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
				$query = "select * from modelos_itens where mdit_tipo = 4 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
                </SELECT>
                </TD>
		</tr>
        
		

		
		
		
		<TR>
                <TD align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Placa de rede:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_rede' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
				$query = "select * from modelos_itens where mdit_tipo = 3 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
                </SELECT>
                </TD>


                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Placa fax/modem:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2' name='comp_modem' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
				$query = "select * from modelos_itens where mdit_tipo = 6 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
						<option value=-2>Não possui</option>
						<option value=-3>Possui qualquer</option>				                                										                                                
                
				</SELECT>
                </TD>
        
		</tr>


        <TR>
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Modelo do HD:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2' name='comp_modelohd' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
				$query = "select * from modelos_itens where mdit_tipo = 1 order by mdit_fabricante, mdit_desc_capacidade";
				$commit = mysql_query($query);
				$sufixo = "GB";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while

                ?>
                </SELECT>
                </TD>

                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Unidade Gravador de CD:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2' name='comp_grav' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
				$query = "select * from modelos_itens where mdit_tipo = 9 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
						<option value=-2>Não possui</option>
						<option value=-3>Possui qualquer</option>				                                										                                                

				</SELECT>
                </TD>

            </tr>
        
        <TR>
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Unidade de CDROM:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2' name='comp_cdrom' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
				$query = "select * from modelos_itens where mdit_tipo = 5 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
						<option value=-2>Não possui</option>
						<option value=-3>Possui qualquer</option>				                                										                                                
				</SELECT>
                </TD>



                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Unidade de DVD:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2' name='comp_dvd' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
				$query = "select * from modelos_itens where mdit_tipo = 8 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade'].$sufixo."</option>";
				} // while
                ?>
                </SELECT>
                </TD>
            
	</tr>

        <TR>
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b>Com o software:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2' name='software' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
				$query = "select * from softwares s, fabricantes f where s.soft_fab = f.fab_cod order by f.fab_nome, s.soft_desc";
				$commit = mysql_query($query);
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['soft_cod'].">".$row['fab_nome']." ".$row['soft_desc']." ".$row['soft_versao']."</option>";
				} // while
                ?>
						
				</SELECT>
                </TD>
	</tr>
	


	<tr><td colspan="4"></td></tr>
	<tr> <td colspan="4" ><b>Dados complementares - IMPRESSORAS/ MONITORES/ SCANNERS:</b></td></tr>
	<tr><td colspan="4"></td></tr>

	
        <TR>
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Tipo de impressora: </b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_tipo_imp' size=1>";
                print "<option value=-1 selected>--------Todas-------- </option>";
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
                ?>
                </SELECT>
                </TD>
        


                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Monitor:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_polegada' size=1>";
                print "<option value =-1 selected>--------Todos--------</option>";
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
                ?>
                </SELECT>
                </TD>
			</tr>                
				
			<tr>	
				<TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Scanner:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_resolucao' size=1>";
                print "<option value=-1 selected>--------Todos--------</option>";
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
                ?>
                </SELECT>
                </TD>
 	
		</tr>	
		
	
	
		


	<tr><td colspan="4"></td></tr>
	<tr> <td colspan="4"><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Dados complementares - CONTÁBEIS:</font></font> </b></td></tr>
	<tr><td colspan="4"></td></tr>


        <TR>
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt><a title='É possível selecionar mais de uma Unidade utilizando a tecla CTRL!'>Unidade:</a></b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT name='comp_inst[]' size=1 multiple='yes'>";
                print "<option value=-1 title='Utiliza a tecla CTRL e as teclas direcionais para seleção múltipla!'>--------Todas--------</option>";
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
        
				<?php 
				
				
				?>
				

                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Centro de Custo:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_ccusto' size=1>";
                print "<option value = -1 selected>---------------Todos----------------- </option>";
                $query = "SELECT * from planejamento.CCUSTO  order by descricao";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,4);?>
                       </option>
                       <?php 
                       $i++;
                }
                ?>
                </SELECT>
                </TD>
        
		
	</tr>
		 
		 
		 

        <TR>
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Fornecedor:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='comp_fornecedor' size=1>";
                print "<option value=-1 selected>---------------------Todos---------------------</option>";
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

                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Nota Fiscal:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_nf" maxlength="30" size="30"></TD>

        </tr>



        <TR>
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Valor R$:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_valor" maxlength="30" size="30"></TD>

                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Data da Compra:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_data_compra" maxlength="30" size="30"></TD>


        </tr>

		<tr>
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Comentário:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_coment" maxlength="200" size="100"></TD>
        </TR>
		
        <tr>
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Data do cadastro:</b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text2" name="comp_data" maxlength="15" size="15"></TD>
        
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><a title="Selecione o equipamento quanto ao seu status de garantia."><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Garantia:</a></b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='garantia' size=1>";
                print "<option value='-1' selected>Todas</option>";
                print "<option value='1'>Em Garantia</option>";  
                print "<option value='2'>Fora da garantia</option>";  
             	print"</selected>";
			 	?>
				</td>
		
		
		
        </TR>
		
        <TR>
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><a title="Escolha por qual campo deseja ordenar a consulta"><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Ordenar por:</a></b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='ordena' size=1>";
                print "<option value='etiqueta' selected>Etiqueta</option>";
                print "<option value='instituicao,etiqueta'>Unidade</option>";  
                print "<option value='equipamento,modelo'>Tipo</option>";                          
                print "<option value='fab_nome,modelo'>Modelo</option>";       
                print "<option value='local'>Localização</option>";                      
             	print"</selected>";
			 	?>
                </TD>
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><a title="Escolha como será o formato de saída da sua consulta"><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>Formato de saída:</a></b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT class='select2'name='visualiza' size=1>";
                print "<option value='tela' selected>Normal</option>";
                print "<option value='impressora'>Relatório 5 linhas</option>";  
                print "<option value='relatorio'>Relatório 1 linha</option>";  
				print "<option value='mantenedora1'>Mantenedora 1 linha</option>";
				print "<option value='texto'>Texto com delimitador</option>";
				print "<option value='config'>Configuração</option>";
				print "<option value='termo'>Termo de compromisso</option>";
				print "<option value='transito'>Formulário de trânsito</option>";
             	print"</selected>";
			 	?>
				</td>
			</tr>
        

		<tr>
                <TD  align="left" bgcolor=<?php print TD_COLOR?>><b><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt><a title='Digite aqui o texto que será exibido como cabeçalho se a saída for no formato de relatório.'>Cabeçalho (se for saída=relatório):</a></b></TD>
                <TD  align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text2" name="header" maxlength="200" size="100"></TD>
        </TR>






        <TR>
                <BR>
                <TD colspan="2" align="right"  bgcolor=<?php print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD colspan="2" align="right"  bgcolor=<?php print BODY_COLOR?>><INPUT type="reset" value="Cancelar" onClick="javascript:history.back()"></TD>
        </TR>


</TABLE>
</FORM>

</body>
</html>
