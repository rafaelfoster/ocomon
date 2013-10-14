<?session_start();
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

 	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	print "<BR><B>".TRANS('OCO_REPORT_FULL','Relatório de Ocorrências')."</B><BR>";


	print "<FORM method='POST' name='form1' action='mostra_resultado_relatorio_total.php'>";
	print "<TABLE border='0'  align='center' width='100%'>";
        ?>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Número inicial:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="numero_inicial" class='data'></TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Número final:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="numero_final" class='data'></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Problema:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?
                print "<SELECT name='problema' class='select'>";
                print "<option value=-1 selected>-  Selecione um problema -</option>";
                $query = "SELECT * from problemas order by problema";
                $resultado = mysql_query($query);
                while ($row = mysql_fetch_array($resultado))
                {
			print "<option value='".$row['prob_id']."'>".$row['problema']."</option>";
		}
                ?>
                </SELECT>
                </TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Setor responsável:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?
                print "<SELECT name='sistema' class='select'>";
                print "<option value=-1 selected>-  Selecione a área -</option>";
                $query = "SELECT * from sistemas order by  sistema";
                $resultado = mysql_query($query);
                while ($row = mysql_fetch_array($resultado))
                {
			print "<option value='".$row['sis_id']."'>".$row['sistema']."</option>";
		}
		?>
		</SELECT>
		</TD>
	</TR>
	<TR>
		<TD width="20%" align="left" bgcolor="<?print TD_COLOR?>" valign="top">Descrição:</TD>
		<TD colspan='3' width="80%" align="left" bgcolor="<?print BODY_COLOR?>"><TEXTAREA class='textarea' name="descricao"></textarea></TD>
	</TR>
	<TR>
		<TD width="20%" align="left" bgcolor="<?print TD_COLOR?>">Equipamento:</TD>
		<TD colspan='3' width="30%" align="left" bgcolor="<?print BODY_COLOR?>"><INPUT type="text" name="equipamento" class='text'></TD>
	</TR>
		<tr>
		<TD width="20%" align="left" bgcolor="<?print TD_COLOR?>">Contato:</TD>
		<TD width="30%" align="left" bgcolor="<?print BODY_COLOR?>"><INPUT type="text" name="contato" class='text'></TD>
		<TD width="20%" align="left" bgcolor="<?print TD_COLOR?>">Status:</TD>
		<TD width="30%" align="left" bgcolor="<?print BODY_COLOR?>">
		<?
		print "<SELECT name='status' class='select'>";
		print "<option value='Em aberto' selected>Em aberto</option>";
		$query = "SELECT * from status order by status";
		$resultado = mysql_query($query);
                while ($row = mysql_fetch_array($resultado))
                {
			print "<option value='".$row['stat_id']."'>".$row['status']."</option>";
		}
		?>
		</SELECT>
		</TD>
	</tr>
		<TR>
		<TD width="20%" align="left" bgcolor="<?print TD_COLOR?>">Local:</TD>
		<TD width="30%" align="left" bgcolor="<?print BODY_COLOR?>">
                <?
                print "<SELECT name='local' class='select'>";
                print "<option value=-1 selected>-  Selecione um local -</option>";
                $query = "SELECT * from localizacao order by local";
                $resultado = mysql_query($query);
                while ($row = mysql_fetch_array($resultado))
                {
			print "<option value='".$row['loc_id']."'>".$row['local']."</option>";
		}
		?>
		</SELECT>
		</TD>
		<TD width="20%" align="left" bgcolor="<?print TD_COLOR?>">Operador:</TD>
		<TD width="30%" align="left" bgcolor="<?print BODY_COLOR?>">
		<?
		print "<SELECT name='operador' class='select'>";
		print "<option value=-1 selected>-  Selecione um operador -</option>";
		$query = "SELECT * from usuarios order by nome";
		$resultado = mysql_query($query);
                while ($row = mysql_fetch_array($resultado))
                {
			print "<option value='".$row['user_id']."'>".$row['nome']."</option>";
		}
		?>
		</SELECT>
		</TD>
	</TR>
	<TR>
		<TD width="20%" align="left" bgcolor="<?print TD_COLOR?>">Data abertura (inicial):</TD>
		<TD width="30%" align="left" bgcolor="<?print BODY_COLOR?>">
		<?
			print "<INPUT type='text' name='data_inicial' class='data'>";
		?>
		</TD>
		<TD width="20%" align="left" bgcolor="<?print TD_COLOR?>">Data abertura (final):</TD>
		<TD width="30%" align="left" bgcolor="<?print BODY_COLOR?>">
		<?
			print "<INPUT type='text' name='data_final' class='data'>";
		?>
		</TD>
	</TR>
	<TR>
		<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Relatório ordernado por:</TD>
		<TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
		<SELECT name='ordem' class='select'>";
		<option value='numero' selected>Número</option>";
		<option value='problema'>Problema</option>";
		<option value='sistema'>Sistema</option>";
		<option value='Equipamento'>Equipamento</option>";
		<option value='contato'>Contato</option>";
		<option value='status'>Status</option>
		<option value='local'>Local</option>
		<option value='operador'>Operador</option>
		<option value='data_cres'>Data de abertura (crescente)</option>
		<option value='data_decres'>Data de abertura (decrescente)</option>
		</SELECT>
		</TD>
		<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Relatório para:</TD>
		<TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
		<SELECT name='relatorio' class='select'>";
		<option value='impressao' selected>Impressão</option>";
		<option value='intranet'>Intranet</option>
		</SELECT>
		</TD>
	</TR>

        <TR>
                <BR>
                <TD colspan='2' align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="    Ok    " name="ok" onclick="ok=sim">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD colspan='2' align="center" width="50%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar"></TD>
        </TR>

</TABLE>
</FORM>


</BODY>
</HTML>

