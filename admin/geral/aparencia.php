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

	print "<SCRIPT LANGUAGE='Javascript' SRC='../../includes/javascript/ColorPicker2.js'></SCRIPT>";
	print "<SCRIPT LANGUAGE='Javascript' SRC='../../includes/javascript/PopupWindow.js'></SCRIPT>";
	print "<SCRIPT LANGUAGE='Javascript' SRC='../../includes/javascript/AnchorPosition.js'></SCRIPT>";


	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<head>";
	?><script language="javascript"> var cp = new ColorPicker(); // DIV style</script><?php 
	print "</head>";
	print "<BODY bgcolor=".BODY_COLOR." onClick=\"setBGColor('idDestaca'); setBGColor('idMarca'); setBGColor('idLin_par'); ".
				"setBGColor('idLin_impar'); setBGColor('idBody');  setBGColor('idTD'); setBorderWidth('idBorda'); ".
				"setBGColor('idBordaColor'); setBGColor('idTrHeader'); setBGColor('idTopo'); setBGColor('idBarra'); ".
				"setBGColor('idMenu'); setBGColor('idFonteNormal'); setBGColor('idFonteHover'); setBGColor('idFonteDestaque');".
				"setBGColor('idFundoDestaque'); setBGColor('idFontTrHeader'); setBGColor('idHeaderCentro'); setBGColor('idFonteTopo');".
				"\">"; //setBGColor('idTab');

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1, 'helpconfiggeral.php');


    	print "<BR><B>".TRANS('TTL_APPEARANCE','Configurações de aparência do sistema').":</b><BR>";

		$query = "SELECT * FROM styles ";
        	$resultado = mysql_query($query) or die (TRANS('ERR_QUERY'));
		$row = mysql_fetch_array($resultado);


	if ((empty($_GET['action'])) and empty($_POST['submit'])){

		print "<br><TD align='left'>".
				"<input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_EDIT_CONFIG','Editar Configuração',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cellStyle=true');\">".
			"</TD><br><BR>";
		if (mysql_numrows($resultado) == 0)
		{
			echo mensagem(TRANS('ALERT_CONFIG_EMPTY'));
		}
		else
		{
				$cor=TD_COLOR;
				$cor1=TD_COLOR;
				$linhas = mysql_numrows($resultado);
				print "<td>";
				print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
				print "<TR class='header'><td>".TRANS('OPT_DIRETIVA','Diretiva')."</TD><td>".TRANS('OPT_VALOR','Valor')."</TD></TD></tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";


				print "<tr><td colspan='2'><b>".TRANS('OPT_APPEARANCE','APARÊNCIA')."</b></td></tr>";

				print "<tr><td>".TRANS('COLOR_MAIN_BAR','COR DA BARRA PRINCIPAL').":</td><td>".
						"<input type='text'  class='mini2' name='topo' id='idTopo' ".
							"value='".$row['tm_color_topo']."' readonly ";
							if ($row['tm_color_topo'] == "IMG_DEFAULT") {
								$IMG = "'../../includes/css/main_bar.png'";
								print " style=\"{background-image:'url(".$IMG.")'; }\">";
							} else
								print " style=\"{background-color:".$row['tm_color_topo'].";}\">";
						print "</td>".
					"</tr>";

				print "<tr><td>".TRANS('COLOR_FONT_MAIN_BAR','COR DA FONTE NA BARRA PRINCIPAL').":</td><td>".
						"<input type='text'  class='mini2' name='fonteTopo' id='idFonteTopo' ".
							"value='".$row['tm_color_topo_font']."' readonly ";
							print " style=\"{background-color:".$row['tm_color_topo_font'].";}\">";
							print "</td>".
					"</tr>";


				print "<tr><td>".TRANS('COLOR_MENU_BAR','COR DA BARRA DE MENUS').":</td><td>".
						"<input type='text'  class='mini2' name='barra' id='idBarra' ".
							"value='".$row['tm_color_barra']."' readonly ";
							if ($row['tm_color_barra'] == "IMG_DEFAULT") {
								print "style=\"{background-image: 'url(\"../../includes/css/aqua.png\")'; }\">";
							} else
								print "style=\"{background-color:".$row['tm_color_barra'].";}\">";
						print "</td>".
					"</tr>";

				print "<tr><td>".TRANS('COLOR_MENU_LAT_BACK','COR DE FUNDO DO MENU LATERAL').":</td><td>".
						"<input type='text'  class='mini2' name='menu' id='idMenu' ".
							"value='".$row['tm_color_menu']."' readonly ";
							if ($row['tm_color_menu'] == "IMG_DEFAULT") {
								print "style=\"{background-image: 'url(\"../../MENU_IMG.png\")'; }\">";
							} else
								print "style=\"{background-color:".$row['tm_color_menu'].";}\">";
							"</td>".
					"</tr>";

				print "<tr><td>".TRANS('COLOR_FONT_MENU_BAR','COR DA FONTE DA BARRA DE MENU').":</td><td>".
						"<input type='text'  class='mini2' name='fonteNormal' id='idFonteNormal' ".
							"value='".$row['tm_color_barra_font']."' readonly ";
							print "style=\"{background-color:".$row['tm_color_barra_font'].";}\">";
							print "</td>".
					"</tr>";


				print "<tr><td>".TRANS('COLOR_FONT_MENU_BAR_HOVER','COR DA FONTE HOVER DA BARRA DE MENU').":</td><td>".
						"<input type='text'  class='mini2' name='fonteHover' id='idFonteHover' ".
							"value='".$row['tm_color_barra_hover']."' readonly ";
							print "style=\"{background-color:".$row['tm_color_barra_hover'].";}\">";
							print "</td>".
					"</tr>";

				print "<tr><td>".TRANS('COLOR_FONT_MENU_BAR_SELECTED','COR DA FONTE SELECIONADA NA BARRA DE MENUS').":</td><td>".
						"<input type='text'  class='mini2' name='fonteDestaque' id='idFonteDestaque' ".
							"value='".$row['tm_barra_fonte_destaque']."' readonly ";
							print "style=\"{background-color:".$row['tm_barra_fonte_destaque'].";}\">";
							print "</td>".
					"</tr>";

				print "<tr><td>".TRANS('COLOR_MENU_BAR_SELECTED','COR DE FUNDO DO MENU SELECIONADO').":</td><td>".
						"<input type='text'  class='mini2' name='fundoDestaque' id='idFundoDestaque' ".
							"value='".$row['tm_barra_fundo_destaque']."' readonly ";
							print "style=\"{background-color:".$row['tm_barra_fundo_destaque'].";}\">";
							print "</td>".
					"</tr>";


				print "<tr><td>".TRANS('COLOR_PAIR_LINE','COR DAS LINHAS PARES').":</td><td>".
						"<input type='text'  class='mini2' name='cor_lin_par' id='idLin_par' ".
							"value='".$row['tm_color_lin_par']."' ".
							"style=\"{background-color:".$row['tm_color_lin_par'].";}\"readonly>".
						"</td>".
					"</tr>";

				print "<tr><td>".TRANS('COLOR_ODD_LINE','COR DAS LINHAS ÍMPARES').":</td><td>".
						"<input type='text'  class='mini2' name='cor_lin_impar' id='idLin_impar' ".
							"value='".$row['tm_color_lin_impar']."' ".
							"style=\"{background-color:".$row['tm_color_lin_impar'].";}\"readonly>".
						"</td>".
					"</tr>";

				print "<tr><td>".TRANS('COLOR_LINE_SELECTION','COR DA SELEÇÃO DE LINHAS').":</td><td>".
						"<input type='text'  class='mini2' name='cor_destaca' id='idDestaca' ".
							"value='".$row['tm_color_destaca']."' ".
							"style=\"{background-color:".$row['tm_color_destaca'].";}\"readonly></td>".
					"</tr>";
				print "<tr><td>".TRANS('COLOR_LINE_MARK','COR DA MARCAÇÃO DAS LINHAS').":</td><td>".
						"<input type='text'  class='mini2' name='cor_marca' id='idMarca' ".
							"value='".$row['tm_color_marca']."' ".
							"style=\"{background-color:".$row['tm_color_marca'].";}\"readonly></td>".
					"</tr>";
				print "<tr><td>".TRANS('COLOR_BACK','COR DE FUNDO').":</td><td>".
						"<input type='text'  class='mini2' name='cor_body' id='idBody' ".
							"value='".$row['tm_color_body']."' ".
							"style=\"{background-color:".$row['tm_color_body'].";}\" readonly></td>".
					"</tr>";
/*					"</tr>";		print "<tr><td>COR DO TD_COLOR:</td><td>".
						"<input type='text'  class='mini2' name='cor_tab' id='idTab' ".
							"value='".$row['tm_color_tab']."' ".
							"style=\"{background-color:".$row['tm_color_tab'].";}\"readonly></td>".
					"</tr>";*/
				print "<tr><td>".TRANS('COLOR_COL','COR DAS COLUNAS').":</td><td>".
						"<input type='text'  class='mini2' name='cor_td' id='idTD' ".
							"value='".$row['tm_color_td']."' ".
							"style=\"{background-color:".$row['tm_color_td'].";}\"readonly></td>".
					"</tr>";

				print "<tr><td>".TRANS('LINE_BORDER','BORDA DA LINHA').":</td><td>".
						"<input type='text'  class='mini2' name='borda' id='idBorda' ".
							"value='".$row['tm_borda_width']."' readonly ".
							"style=\"{border-bottom-width:".$row['tm_borda_width']."px; border-top-width:".$row['tm_borda_width']."px; }\">".
							"</td>".
					"</tr>";

				print "<tr><td>".TRANS('COLOR_LINE_BORDER','COR DA BORDA DA LINHA').":</td><td>".
						"<input type='text'  class='mini2' name='borda_color' id='idBordaColor' ".
							"value='".$row['tm_borda_color']."' ".
							"style=\"{background-color:".$row['tm_borda_color'].";}\" readonly>".
						"</td>".
					"</tr>";

				print "<tr><td>".TRANS('COLOR_TABLE_HEADER','COR DOS CABEÇALHOS DAS TABELAS').":</td><td>".
						"<input type='text'  class='mini2' name='tr_header' id='idTrHeader' ".
							"value='".$row['tm_tr_header']."' readonly ";
							if ($row['tm_tr_header'] == "IMG_DEFAULT") {
								print "style=\"{background-image: 'url(\"../../includes/css/header_bar.gif\")'; }\">";
							} else
								print "style=\"{background-color:".$row['tm_tr_header'].";}\">";
						print "</td>".
					"</tr>";

				print "<tr><td>".TRANS('COLOR_FONT_TABLE_HEADER','COR DA FONTE DOS CABEÇALHOS DAS TABELAS').":</td><td>".
						"<input type='text'  class='mini2' name='font_tr_header' id='idFontTrHeader' ".
							"value='".$row['tm_color_font_tr_header']."' readonly ";
							print "style=\"{background-color:".$row['tm_color_font_tr_header'].";}\">";
							print "</td>".
					"</tr>";

				print "<tr><td>".TRANS('COLOR_LINE_UNDER','COR DA LINHA SOB A DATA').":</td><td>".
						"<input type='text'  class='mini2' name='tm_color_borda_header_centro' id='idHeaderCentro' ".
							"value='".$row['tm_color_borda_header_centro']."' readonly ";
							print "style=\"{background-color:".$row['tm_color_borda_header_centro'].";}\">";
							print "</td>".
					"</tr>";



				print "<tr><td colspan='2'>&nbsp;</td></tr>";



				print "</TABLE>";
		}

	} else

	if ((isset($_GET['action']) && ($_GET['action']=="alter")) && empty($_POST['submit'])){


		print "<form id='form1' name='alter' action='".$_SERVER['PHP_SELF']."' method='post' onSubmit=\"return valida()\">"; //onSubmit='return valida()'
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<TR class='header'><td>".TRANS('OPT_DIRETIVA')."</TD><td>".TRANS('OPT_VALOR')."</TD></TD></tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td colspan='2'><b>".TRANS('OPT_APPEARANCE')."</b></td></tr>";

		print "<tr><td>".TRANS('COLOR_MAIN_BAR','COR DA BARRA PRINCIPAL').":</td><td>".
				"<input type='text'  class='mini2' name='topo' id='idTopo' ".
					"value='".$row['tm_color_topo']."' ";
					if ($row['tm_color_topo'] == "IMG_DEFAULT") {
						$IMG = "'../../includes/css/main_bar.png'";
						print " style=\"{background-image:'url(".$IMG.")'; }\">";
					} else
						print " style=\"{background-color:".$row['tm_color_topo'].";}\">";
					print "<a href='#' onClick=\"cp.select(document.forms[0].topo,'pickTopo');return false;\" name='pickTopo' id='pickTopo' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultHeader('idTopo', 'main_bar.png');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a>".
					"</td>".
			"</tr>";

		print "<tr><td>".TRANS('COLOR_FONT_MAIN_BAR','COR DA FONTE NA BARRA PRINCIPAL').":</td><td>".
				"<input type='text'  class='mini2' name='fonteTopo' id='idFonteTopo' ".
					"value='".$row['tm_color_topo_font']."' ";
					print " style=\"{background-color:".$row['tm_color_topo_font'].";}\">";
					print "<a href='#' onClick=\"cp.select(document.forms[0].fonteTopo,'pickFontTopo');return false;\" name='pickFontTopo' id='pickFontTopo' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idFonteTopo','#FFFFFF');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a>".
					"</td>".
			"</tr>";


		print "<tr><td>".TRANS('COLOR_MENU_BAR','COR DA BARRA DE MENUS').":</td><td>".
				"<input type='text'  class='mini2' name='barra' id='idBarra' ".
					"value='".$row['tm_color_barra']."' ";
					if ($row['tm_color_barra'] == "IMG_DEFAULT") {
						print "style=\"{background-image: 'url(\"../../includes/css/aqua.png\")'; }\">";
					} else
						print "style=\"{background-color:".$row['tm_color_barra'].";}\">";
					print "<a href='#' onClick=\"cp.select(document.forms[0].barra,'pickBarra');return false;\" name='pickBarra' id='pickBarra' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultHeader('idBarra', 'aqua.png');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a>".
					"</td>".
			"</tr>";

		print "<tr><td>".TRANS('COLOR_MENU_LAT_BACK','COR DE FUNDO DO MENU LATERAL').":</td><td>".
				"<input type='text'  class='mini2' name='menu' id='idMenu' ".
					"value='".$row['tm_color_menu']."' ";
					if ($row['tm_color_menu'] == "IMG_DEFAULT") {
						print "style=\"{background-image: 'url(\"../../MENU_IMG.png\")'; }\">";
					} else
						print "style=\"{background-color:".$row['tm_color_menu'].";}\">";
					print "<a href='#' onClick=\"cp.select(document.forms[0].menu,'pickMenu');return false;\" name='pickMenu' id='pickMenu' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultHeader('idMenu', 'MENU_IMG.png');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a>".
					"</td>".
			"</tr>";

		print "<tr><td>".TRANS('COLOR_FONT_MENU_BAR','COR DA FONTE DA BARRA DE MENU').":</td><td>".
				"<input type='text'  class='mini2' name='fonteNormal' id='idFonteNormal' ".
					"value='".$row['tm_color_barra_font']."' ";
					print "style=\"{background-color:".$row['tm_color_barra_font'].";}\">";
					print "<a href='#' onClick=\"cp.select(document.forms[0].fonteNormal,'pickFonteNormal');return false;\" name='pickFonteNormal' id='pickFonteNormal' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idFonteNormal','#675E66');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a>".
					"</td>".
			"</tr>";


		print "<tr><td>".TRANS('COLOR_FONT_MENU_BAR_HOVER','COR DA FONTE HOVER DA BARRA DE MENU').":</td><td>".
				"<input type='text'  class='mini2' name='fonteHover' id='idFonteHover' ".
					"value='".$row['tm_color_barra_hover']."' ";
					print "style=\"{background-color:".$row['tm_color_barra_hover'].";}\">";
					print "<a href='#' onClick=\"cp.select(document.forms[0].fonteHover,'pickFonteHover');return false;\" name='pickFonteHover' id='pickFonteHover' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idFonteHover','#FFFFFF');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a>".
					"</td>".
			"</tr>";

		print "<tr><td>".TRANS('COLOR_FONT_MENU_BAR_SELECTED','COR DA FONTE SELECIONADA NA BARRA DE MENUS').":</td><td>".
				"<input type='text'  class='mini2' name='fonteDestaque' id='idFonteDestaque' ".
					"value='".$row['tm_barra_fonte_destaque']."' ";
					print "style=\"{background-color:".$row['tm_barra_fonte_destaque'].";}\">";
					print "<a href='#' onClick=\"cp.select(document.forms[0].fonteDestaque,'pickFonteDestaque');return false;\" name='pickFonteDestaque' id='pickFonteDestaque' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idFonteDestaque','#FFFFFF');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a>".
					"</td>".
			"</tr>";

		print "<tr><td>".TRANS('COLOR_MENU_BAR_SELECTED','COR DE FUNDO DO MENU SELECIONADO').":</td><td>".
				"<input type='text'  class='mini2' name='fundoDestaque' id='idFundoDestaque' ".
					"value='".$row['tm_barra_fundo_destaque']."' ";
					print "style=\"{background-color:".$row['tm_barra_fundo_destaque'].";}\">";
					print "<a href='#' onClick=\"cp.select(document.forms[0].fundoDestaque,'pickFundoDestaque');return false;\" name='pickFundoDestaque' id='pickFundoDestaque' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idFundoDestaque','#666666');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a>".
					"</td>".
			"</tr>";


		print "<tr><td>".TRANS('COLOR_PAIR_LINE','COR DAS LINHAS PARES').":</td><td>".
				"<input type='text'  class='mini2' name='cor_lin_par' id='idLin_par' ".
					"value='".$row['tm_color_lin_par']."' ".
					"style=\"{background-color:".$row['tm_color_lin_par'].";}\">".
					"<a href='#' onClick=\"cp.select(document.forms[0].cor_lin_par,'pick3');return false;\" name='pick3' id='pick3' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idLin_par','#E3E1E1');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a></td>".
			"</tr>";
		print "<tr><td>".TRANS('COLOR_ODD_LINE','COR DAS LINHAS ÍMPARES').":</td><td>".
				"<input type='text'  class='mini2' name='cor_lin_impar' id='idLin_impar' ".
					"value='".$row['tm_color_lin_impar']."' ".
					"style=\"{background-color:".$row['tm_color_lin_impar'].";}\">".
					"<a href='#' onClick=\"cp.select(document.forms[0].cor_lin_impar,'pick4');return false;\" name='pic4' id='pick4' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idLin_impar','#F6F6F6');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a></td>".
			"</tr>";

		print "<tr><td>".TRANS('COLOR_LINE_SELECTION','COR DA SELEÇÃO DE LINHAS').":</td><td>".
				"<input type='text'  class='mini2' name='cor_destaca' id='idDestaca' ".
					"value='".$row['tm_color_destaca']."' ".
					"style=\"{background-color:".$row['tm_color_destaca'].";}\">".
					"<a href='#' onClick=\"cp.select(document.forms[0].cor_destaca,'pick');return false;\" name='pick' id='pick' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idDestaca','#CCCCCC');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a></td>".
			"</tr>";

		print "<tr><td>".TRANS('COLOR_LINE_MARK','COR DA MARCAÇÃO DAS LINHAS').":</td><td>".
				"<input type='text'  class='mini2' name='cor_marca' id='idMarca' ".
					"value='".$row['tm_color_marca']."' ".
					"style=\"{background-color:".$row['tm_color_marca'].";}\">".
					"<a href='#' onClick=\"cp.select(document.forms[0].cor_marca,'pick2');return false;\" name='pic2' id='pick2' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idMarca','#FFFFCC');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a></td>".
			"</tr>";

			print "<tr><td>".TRANS('COLOR_BACK','COR DE FUNDO').":</td><td>".
				"<input type='text'  class='mini2' name='cor_body' id='idBody' ".
					"value='".$row['tm_color_body']."' ".
					"style=\"{background-color:".$row['tm_color_body'].";}\">".
					"<a href='#' onClick=\"cp.select(document.forms[0].cor_body,'pick5');return false;\" name='pick5' id='pick5' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idBody','#F6F6F6');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a></td>".
			"</tr>";
/*			print "<tr><td>COR DO TD_COLOR:</td><td>".
				"<input type='text'  class='mini2' name='cor_tab' id='idTab' ".
					"value='".$row['tm_color_tab']."' ".
					"style=\"{background-color:".$row['tm_color_tab'].";}\">".
					"<a href='#' onClick=\"cp.select(document.forms[0].cor_tab,'pick6');return false;\" name='pic6' id='pick6' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idTab','#DBDBDB');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a></td>".
			"</tr>";*/
		print "<tr><td>".TRANS('COLOR_COL','COR DAS COLUNAS').":</td><td>".
				"<input type='text'  class='mini2' name='cor_td' id='idTD' ".
					"value='".$row['tm_color_td']."' ".
					"style=\"{background-color:".$row['tm_color_td'].";}\">".
					"<a href='#' onClick=\"cp.select(document.forms[0].cor_td,'pick7');return false;\" name='pick7' id='pick7' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idTD','#DBDBDB');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a></td>".
			"</tr>";

		print "<tr><td>".TRANS('LINE_BORDER','BORDA DA LINHA').":</td><td>".
				"<input type='text'  class='mini2' name='borda' id='idBorda' ".
					"value='".$row['tm_borda_width']."' ".
					"style=\"{border-bottom-width:".$row['tm_borda_width']."px; border-top-width:".$row['tm_borda_width']."px; }\">".
					"</td>".
			"</tr>";
		print "<tr><td>".TRANS('COLOR_LINE_BORDER','COR DA BORDA DA LINHA').":</td><td>".
				"<input type='text'  class='mini2' name='borda_color' id='idBordaColor' ".
					"value='".$row['tm_borda_color']."' ".
					"style=\"{background-color:".$row['tm_borda_color'].";}\">".
					"<a href='#' onClick=\"cp.select(document.forms[0].borda_color,'pickBC');return false;\" name='pickBC' id='pickBC' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idBordaColor','#F6F6F6');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a>".
					"</td>".
			"</tr>";

		print "<tr><td>".TRANS('COLOR_TABLE_HEADER','COR DOS CABEÇALHOS DAS TABELAS').":</td><td>".
				"<input type='text'  class='mini2' name='tr_header' id='idTrHeader' ".
					"value='".$row['tm_tr_header']."' ";
					if ($row['tm_tr_header'] == "IMG_DEFAULT") {
						print "style=\"{background-image: 'url(\"../../includes/css/header_bar.gif\")'; }\">";
					} else
						print "style=\"{background-color:".$row['tm_tr_header'].";}\">";
					print "<a href='#' onClick=\"cp.select(document.forms[0].tr_header,'pickTH');return false;\" name='pickTH' id='pickTH' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultHeader('idTrHeader', 'header_bar.gif');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a>".
					"</td>".
			"</tr>";

		print "<tr><td>".TRANS('COLOR_FONT_TABLE_HEADER','COR DA FONTE DOS CABEÇALHOS DAS TABELAS').":</td><td>".
				"<input type='text'  class='mini2' name='font_tr_header' id='idFontTrHeader' ".
					"value='".$row['tm_color_font_tr_header']."' ";
					print "style=\"{background-color:".$row['tm_color_font_tr_header'].";}\">";
					print "<a href='#' onClick=\"cp.select(document.forms[0].font_tr_header,'pickFTH');return false;\" name='pickFTH' id='pickFTH' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idFontTrHeader', '#000000');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a>".
					"</td>".
			"</tr>";

		print "<tr><td>".TRANS('COLOR_LINE_UNDER','COR DA LINHA SOB A DATA').":</td><td>".
				"<input type='text'  class='mini2' name='tm_color_borda_header_centro' id='idHeaderCentro' ".
					"value='".$row['tm_color_borda_header_centro']."' ";
					print "style=\"{background-color:".$row['tm_color_borda_header_centro'].";}\">";
					print "<a href='#' onClick=\"cp.select(document.forms[0].tm_color_borda_header_centro,'pickHC');return false;\" name='pickHC' id='pickHC' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"&nbsp;&nbsp;<a onClick=\"loadDefaultValue('idHeaderCentro', '#999999');\" title='".TRANS('HNT_LOAD_DEFAULT_COLOR')."'>".
					"<img src='".ICONS_PATH."rebuild.png' width='16' height='16' border='0'></a>".
					"</td>".
			"</tr>";



		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td colspan='2'><a class='href' onClick=\"loadAllDefaults();\" title='Carrega as cores padrão!'>".TRANS('OPT_SELECT_DEFAULT_SQUEMA','SELECIONA O ESQUEMA DE CORES PADRÃO')."</a></td>".
			"</tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td colspan='2'><a class='href' onClick=\"varreCampos('form1')\">".TRANS('OPT_SAVE_SQUEMA','SALVAR SELEÇÃO PARA ESQUEMA DE CORES')."</td></a>".
			"</tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td><a class='href' onClick=\"mini_popup('saveTheme.php?action=LOAD');\" title='Carrega um tema salvo previamente!'>".TRANS('OPT_LOAD_SQUEMA','CARREGA ESQUEMA SALVO')."</a></td>".
			"</tr>";



		print "<tr><td colspan='2'>&nbsp;</td></tr>";


		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td><input type='submit'  class='button' name='submit' value='".TRANS('BT_ALTER','Alterar')."'></td>";
		print "<td><input type='reset' name='reset'  class='button' value='".TRANS('BT_CANCEL','Cancelar')."' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if ($_POST['submit'] = TRANS('BT_ALTER')){

		if (isset($_POST['cor_destaca'])){
			$_SESSION['s_colorDestaca'] = $_POST['cor_destaca'];
		}
		if (isset($_POST['cor_marca'])){
			$_SESSION['s_colorMarca'] = $_POST['cor_marca'];
		}
		if (isset($_POST['cor_lin_par'])){
			$_SESSION['s_colorLinPar'] = $_POST['cor_lin_par'];
		}
		if (isset($_POST['cor_lin_impar'])){
			$_SESSION['s_colorLinImpar'] = $_POST['cor_lin_impar'];
		}



		$qry = "UPDATE styles SET ".
				"tm_color_destaca = '".noHtml($_POST['cor_destaca'])."', ".
				"tm_color_marca = '".noHtml($_POST['cor_marca'])."', ".
				"tm_color_lin_par = '".noHtml($_POST['cor_lin_par'])."', ".
				"tm_color_lin_impar = '".noHtml($_POST['cor_lin_impar'])."', ".
				"tm_color_body = '".noHtml($_POST['cor_body'])."', ".
				//"tm_color_tab = '".noHtml($_POST['cor_tab'])."', ".
				"tm_color_td = '".noHtml($_POST['cor_td'])."', ".
				"tm_borda_width = '".noHtml($_POST['borda'])."', ".
				"tm_borda_color = '".noHtml($_POST['borda_color'])."', ".
				"tm_tr_header = '".noHtml($_POST['tr_header'])."', ".
				"tm_color_topo = '".noHtml($_POST['topo'])."', ".
				"tm_color_barra = '".noHtml($_POST['barra'])."', ".
				"tm_color_menu = '".noHtml($_POST['menu'])."', ".
				"tm_color_barra_font = '".noHtml($_POST['fonteNormal'])."', ".
				"tm_color_barra_hover = '".noHtml($_POST['fonteHover'])."', ".
				"tm_barra_fonte_destaque = '".noHtml($_POST['fonteDestaque'])."', ".
				"tm_barra_fundo_destaque = '".noHtml($_POST['fundoDestaque'])."', ".
				"tm_color_font_tr_header = '".noHtml($_POST['font_tr_header'])."', ".
				"tm_color_borda_header_centro = '".noHtml($_POST['tm_color_borda_header_centro'])."', ".
				"tm_color_topo_font = '".noHtml($_POST['fonteTopo'])."' ".

				" ";

		//print $qry;
		//exit;
		$exec= mysql_query($qry) or die(TRANS('ERR_EDIT'));

		//print "<script>mensagem('Configuração alterada com sucesso!'); window.open('../../index.php?LOAD=ADMIN','_parent',''); </script>";
		print "<script>mensagem('".TRANS('MSG_SUCCESS_CONFIG_SQUEMA','Configuração alterada com sucesso!\\nO Esquema selecionado será carregado agora',0)."!'); window.open('../../index.php?LOAD=ADMIN','_parent',''); </script>";
		//redirect('configGeral.php');
	}

?>

<script type="text/javascript">
<!--
	function valida(){

		var ok = validaForm('idTopo','COR','Barra principal',1);
		if (ok) var ok =  validaForm('idBarra','COR','Barra de menus',1);
		if (ok) var ok =  validaForm('idMenu','COR','Fundo do menu lateral',1);
		if (ok) var ok =  validaForm('idFonteNormal','COR','Cor da fonte da barra de menus',1);
		if (ok) var ok =  validaForm('idFonteHover','COR','Cor da fonte Hover da barra de menus',1);
		if (ok) var ok =  validaForm('idFonteDestaque','COR','Cor da fonte em destaque',1);
		if (ok) var ok =  validaForm('idFundoDestaque','COR','Cor do fundo em destaque',1);
		if (ok) var ok =  validaForm('idLin_par','COR','Linhas pares',1);
		if (ok) var ok =  validaForm('idLin_impar','COR','Linhas impares',1);
		if (ok) var ok =  validaForm('idDestaca','COR','Cor de seleção de linhas',1);
		if (ok) var ok =  validaForm('idMarca','COR','Cor de marcação de linhas',1);
		if (ok) var ok =  validaForm('idBody','COR','Cor Body',1);
		//if (ok) var ok =  validaForm('idTab','QUALQUER','Cor Tab',1);
		if (ok) var ok =  validaForm('idTD','COR','Cor TD',1);
		if (ok) var ok =  validaForm('idBorda','INTEIROFULL','Largura da Borda',1);
		if (ok) var ok =  validaForm('idHeaderCentro','COR','Cor da linha sob a data',1);

		return ok;
	}




	function loadDefaultHeader(id, img){
		var obj = document.getElementById(id);
		loadDefaultValue(id,'IMG_DEFAULT');
		//if (obj.value = "IMG_DEFAULT") {
			//obj.style.background = 'url("../../includes/css/header_bar.gif")';
		var path = "../../includes/css/"+img;
		obj.style.background = 'url('+path+')';
		return false;
	}

	function loadAllDefaults(){
		loadDefaultValue('idLin_par','#E3E1E1');
		loadDefaultValue('idLin_impar','#F6F6F6');
		loadDefaultValue('idDestaca','#CCCCCC');
		loadDefaultValue('idMarca','#FFFFCC');
		loadDefaultValue('idBody','#F6F6F6');
		loadDefaultValue('idTD','#DBDBDB');
		loadDefaultValue('idBorda','2');
		loadDefaultValue('idBordaColor','#F6F6F6');
		//loadDefaultValue('idTrHeader','IMG_DEFAULT');
		loadDefaultHeader('idTrHeader', 'header_bar.gif');
		loadDefaultHeader('idTopo', 'main_bar.png');
		loadDefaultHeader('idBarra', 'aqua.png');
		loadDefaultHeader('idMenu', 'MENU_IMG.png');

		loadDefaultValue('idFonteNormal','#675E66');
		loadDefaultValue('idFonteHover','#FFFFFF');
		loadDefaultValue('idFonteDestaque','#FFFFFF');
		loadDefaultValue('idFundoDestaque','#666666');
		loadDefaultValue('idFontTrHeader','#000000');
		loadDefaultValue('idHeaderCentro','#999999');
		loadDefaultValue('idFonteTopo','#FFFFFF');

		return false;
	}

	function setBorderWidth(id){
		obj = document.getElementById(id);
		obj.style.borderTopWidth = obj.value;
		obj.style.borderBottomWidth = obj.value;
	}

	function varreCampos(formulario) {

		var form = document.getElementById(formulario);
		var qtdeCampos = form.elements.length;
		var indice = '';
		var sep = '&';
		var param = 'saveTheme.php?action=save';

		for(indice=0; indice<qtdeCampos; indice++) {
			param += sep+form.elements[indice].name+'='+form.elements[indice].value

		}
		mini_popup(replaceAll(param, "#", "|"));
	}



-->
</script>
<SCRIPT LANGUAGE="JavaScript">cp.writeDiv()</SCRIPT>
<?php 
print "</body>";
print "</html>";

?>