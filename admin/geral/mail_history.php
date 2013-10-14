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

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

    	print "<BR><B>".TRANS('TTL_MAIL_HIST').":</b><BR><br>";

		print "<form name='form1' action='".$_SERVER['PHP_SELF']."' method='post'>"; //onSubmit='return valida()'
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='70%'>";



		$query = "SELECT * FROM mail_hist m, usuarios u WHERE m.mhist_technician=u.user_id ";
		if (isset($_GET['cod'])) {
			$query .= "AND m.mhistl_cod=".$_GET['cod']." ";
		}
		$query.="ORDER BY m.mhist_date";
		$resultado = mysql_query($query) or die (TRANS('ERR_QUERY'));

	if ((empty($_GET['action'])) and empty($_POST['submit'])){


		if (mysql_numrows($resultado) == 0)
		{
			//echo mensagem(TRANS('ALERT_CONFIG_EMPTY'));
		}
		else
		{
				$linhas = mysql_numrows($resultado);

				print "<TABLE border='0' cellpadding='1' cellspacing='0' width='100%'>";
				print "<tr class='header'>";
				print "<td class='line'>".TRANS('MHIST_SUBJECT')."</td><td class='line'>".TRANS('MHIST_LISTS')."</td>".
					"<td class='line'>".TRANS('MHIST_BODY')."</td>".
					//"<td class='line'>".TRANS('MHIST_ADDRESS')."</td>".
					//"<td class='line'>".TRANS('MHIST_ADDRESS_CC')."</td>".
					"<td class='line'>".TRANS('MHIST_DATE')."</td>".
					"</td><td class='line'>".TRANS('MHIST_TECHNICIAN')."</td>";
				print "</tr>";

				$j = 2;
				while ($row = mysql_fetch_array($resultado)) {
					if ($j % 2) {
							$trClass = "lin_par";
					}
					else {
							$trClass = "lin_impar";
					}
					$j++;

					$limite = 50;
					$shortBody = trim($row['mhist_body']);
					if (strlen($shortBody)>$limite) {
						$shortBody = substr($shortBody,0,($limite-4))."...";
					}

/*					print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
					print "<td class='line'>".$row['mhist_subject']."</td><td class='line'>".NVL($row['mhist_listname'])."</td>".
						"<td class='line'><IMG ID='imgidBody".$j."' SRC='../../includes/icons/open.png' width='9' height='9' ".
										"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('idBody".$j."');\">&nbsp;".$shortBody."</td>".
						"<td class='line'>".$row['mhist_date']."</td><td class='line'>".$row['nome']."</td>";
					print "</tr>";*/

					print "<tr class=".$trClass." id='imglinhax".$j."' onMouseOver=\"destaca('imglinhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('imglinhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('imglinhax".$j."','".$_SESSION['s_colorMarca']."');\" onClick=\"invertView('linhax".$j."');\" STYLE=\"{cursor: pointer;}\">";
					print "<td class='line'>".$row['mhist_subject']."</td><td class='line'>".NVL($row['mhist_listname'])."</td>".
						"<td class='line'>".$shortBody."</td>".
						"<td class='line'>".$row['mhist_date']."</td><td class='line'>".$row['nome']."</td>";
					print "</tr>";


					print "<tr><td colspan='6' ><div id='linhax".$j."' style='{display:none}'>"; //style='{display:none}'
					print "<TABLE border='0' cellpadding='2' cellspacing='0' width='90%'>";

						//print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
						print "<tr><td class='line'><b>".TRANS('MAIL_FIELD_TO').":</b> ".$row['mhist_address']."</td></tr>";
						print "<tr><td class='line'><b>".TRANS('MAIL_FIELD_CC').":</b> ".$row['mhist_address_cc']."</td></tr>";
						print "<tr><td class='textarea'>".$row['mhist_body']."</td></tr>";
						NL();

					print "</table></div></td></tr>";



				}

				print "</table>";
		}

	}



	print "</table>";
	print "</form>";


print "</body>";
print "</html>";

?>