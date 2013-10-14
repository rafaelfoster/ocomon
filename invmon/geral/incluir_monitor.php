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
  */

include ("var_sessao.php");
include ("funcoes.inc");
include ("config.inc.php");
include ("logado.php");

$hoje = date("Y-m-d H:i:s");




?>

<HTML>
<BODY bgcolor=<?print BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TD_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>InvMon - Controle de Inventário  -  Usuário: <font color=red>$s_usuario</font></b></TD>";
                        echo menu_usuario();
                        if ($s_usuario=='admin')
                        {
                                echo menu_admin();
                        }
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>

<BR>
<B>Inclusão de monitores</B> (campos marcados com <B>*</B> devem ser preenchidos).
<BR>

<FORM method="POST" action=<?PHP_SELF?>>
<TABLE border="1"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        
		
       <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Unidade*:</b></TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT name='mon_inst' size=1>";
                print "<option value=-1 selected>Unidade: </option>";
                $query = "SELECT * from instituicao  order by inst_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>
                </TD>
        </tr>
        </table>		
		
		
		
		
		
		
		<TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Código de Inventário *:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="mon_inv" maxlength="10" size="39"></TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Número de Série *:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="mon_sn" maxlength="10" size="30"></TD>

        </TABLE>
       </TR>


        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Fabricante *:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT name='mon_fabricante' size=1>";
                print "<option value=-1 selected>Selecione o fabricante ---------------</option>";
                $query = "SELECT * from fabricantes order by fab_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>
                </TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Modelo *:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT name='mon_modelo' size=1>";
                print "<option value=-1 selected>Selecione o modelo -----------------</option>";
                $query = "SELECT * from modelos order by modelo_desc";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>
                </TD>




        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Fornecedor *:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT name='mon_fornecedor' size=1>";
                print "<option value=-1 selected>Selecione o fornecedor ---------------</option>";
                $query = "SELECT * from fornecedores  order by forn_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>
                </TD>

                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Nota Fiscal:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="mon_nf" maxlength="30" size="30"></TD>

        </tr>
        </table>


        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Inventário associado *:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT name='mon_comp_inv' size=1>";
                print "<option value=-1 selected>Código de inventário associado</option>";
                $query = "SELECT comp_inv from computadores  order by comp_inv";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,0);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>
                </TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><b>Localização*:</b></TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT name='mon_local' size=1>";
                print "<option value=-1 selected>Selecione o local</option>";
                $query = "SELECT * from localizacao  order by local";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>
                </TD>
        </table>
        </tr>






        <TR>
        <TABLE  border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <BR>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar"></TD>
        </TABLE>
        </TR>

        <?

                if ($rodou == "sim")
                {
                        $erro="não";

#############################################

                        $query2 = "SELECT m.*, c.* FROM monitores as m, computadores as c 
									WHERE 
										(m.mon_inv='$mon_inv') or ((c.comp_inv = '$mon_inv') and 
											(c.comp_inst = '$mon_inst'))";
                        $resultado2 = mysql_query($query2);
                        $linhas = mysql_numrows($resultado2);
                        if ($linhas > 0)
                        {
                                $aviso = "Este código de inventário já está cadastrado sistema!";
                                $erro = "sim";
                        }
############################################






                        if ( empty($mon_inv) or  ($mon_modelo==-1) or ($mon_fornecedor ==-1) or ($mon_local==-1) or
                             ($mon_fabricante==-1) or $mon_inst ==-1)

                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }


                        if ($erro=="não")
                        {


                                $data = $hoje;

                                        $query = "INSERT INTO monitores (mon_inv, mon_fabricante, mon_modelo, mon_fornecedor,
                                                  mon_sn, mon_nf, mon_comp_inv, mon_local, mon_inst) values ('$mon_inv','$mon_fabricante',
                                                  '$mon_modelo','$mon_fornecedor','$mon_sn','$mon_nf','$mon_comp_inv','$mon_local', '$mon_inst')";
                                        $resultado = mysql_query($query);


                                if ($resultado == 0)
                                {
                                        print $query;

                                        $aviso = "ERRO na inclusão dos dados.";
                                }
                                else
                                {
                                        $numero = mysql_insert_id();                                                 //$numero
                                        $aviso = "OK. Monitor inventariado com sucesso.<BR>Código: <font color=red>$comp_inv</font>";

                                }
                        }
                        $origem = "incluir_monitor.php";
                        session_register("aviso");
                        session_register("origem");
                        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                }

        ?>

</TABLE>
</FORM>

</body>
</html>
