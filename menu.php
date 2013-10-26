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

error_reporting(0);

	include ("PATHS.php");
	//include ("".$includesPath."var_sessao.php");
	require_once("./includes/config.inc.php");
	include ("./includes/languages/".LANGUAGE."");
	require_once("./includes/functions/funcoes.inc");

	$OPERADOR_AREA = false;
	if(isset($_SESSION['s_area_admin']) && $_SESSION['s_area_admin'] == '1' && $_SESSION['s_nivel'] != '1')
		$OPERADOR_AREA = true;

	print "<style type='text/css'>";
	?>
	<!--
		@import url('includes/menu/phplm320/layerstreemenu-hidden.css');
	//-->
	</style>
	<script language='JavaScript' type='text/javascript'>
	<?php 
	require_once $phplmDirPath.'libjs/layersmenu-browser_detection.js'
	?>
	</script>
	<script language='JavaScript' type='text/javascript' src='includes/menu/phplm320/libjs/layerstreemenu-cookies.js'></script>
	<?php 
	require_once $phplmDirPath.'lib/PHPLIB.php';
	require_once $phplmDirPath.'lib/layersmenu-common.inc.php';
	require_once $phplmDirPath.'lib/treemenu.inc.php';
	$mid = new TreeMenu();

	//$mid->setDirroot($myDirPath);
	$mid->setLibjsdir($phplmDirPath.'libjs/');
	$mid->setImgdir($phplmDirPath.'menuimages/');
	$mid->setImgwww($phplmDirPath.'menuimages/');
	$mid->setIcondir($phplmDirPath.'menuicons/');
	$mid->setIconwww($phplmDirPath.'menuicons/');


	$menuInvmon = ".|".TRANS('MNL_INICIO')."|".$invDirPath."abertura.php|".TRANS('MNL_INICIO_HNT')."|".$iconsPath."gohome.png|centro
.|".TRANS('MNL_CAD')."
..|".TRANS('MNL_CAD_EQUIP')."|".$invDirPath."incluir_computador.php||".$iconsPath."computador.png|centro
..|".TRANS('MNL_CAD_DOC')."|".$invDirPath."documentos.php?action=incluir&cellStyle=true||".$iconsPath."contents.png|centro
..|".TRANS('MNL_CAD_ESTOQUE')."|".$invDirPath."estoque.php?action=incluir&cellStyle=true||".$iconsPath."mouse.png|centro
.|".TRANS('MNL_VIS')."
..|".TRANS('MNL_VIS_EQUIP')."|".$invDirPath."mostra_consulta_comp.php||".$iconsPath."computador.png|centro
..|".TRANS('MNL_VIS_DOC')."|".$invDirPath."documentos.php||".$iconsPath."contents.png|centro
..|".TRANS('MNL_VIS_ESTOQUE')."|".$invDirPath."estoque.php||".$iconsPath."mouse.png|centro
.|".TRANS('MNL_CON')."
..|".TRANS('MNL_CON_RAP')."|".$invDirPath."consulta_inv.php||".$iconsPath."search.png|centro
..|".TRANS('MNL_CON_ESP')."|".$invDirPath."consulta_comp.php||".$iconsPath."consulta.png|centro
..|".TRANS('MNL_VIS_ESTOQUE')."|".$invDirPath."estoque.php?action=search&cellStyle=true||".$iconsPath."mouse.png|centro
..|".TRANS('MNL_CON_HIST')."|
...|".TRANS('MNL_CON_HIST_TAG')."|".$invDirPath."consulta_hist_inv.php?from_menu=1||".$iconsPath."tag.png|centro
...|".TRANS('MNL_CON_HIST_LOCAL')."|".$invDirPath."consulta_hist_local.php|||centro
.|".TRANS('MNL_STAT_RELAT')."|".$invDirPath."relatorios.php||".$iconsPath."reports.png|centro";
//.|".TRANS('MNL_SENHA']."|".$invDirPath."altera_senha.php||".$iconsPath."password.png|centro";

	$mid->setMenuStructureString($menuInvmon);
	$mid->setIconsize(16, 16);
	$mid->parseStructureForMenu('treemenu1');
	//$mid->setTpldir('../../includes/menu/phplm320/templates/');
	//$mid->setSelectedItemByUrl('treemenu1', basename(__FILE__));

	$menuOcomon =".|".TRANS('MNL_INICIO')."|".$ocoDirPath."abertura.php|".TRANS('MNL_INICIO_HNT')."|".$iconsPath."gohome.png|centro
.|".TRANS('MNL_ABRIR')."|".$ocoDirPath."incluir.php||".$iconsPath."fone.png|centro
.|".TRANS('MNL_CONSULTAR')."|".$ocoDirPath."consultar.php||".$iconsPath."consulta.png|centro
.|".TRANS('MNL_BUSCA_RAP')."|".$ocoDirPath."alterar.php||".$iconsPath."search.png|centro
.|".TRANS('MNL_SOLUCOES')."|".$ocoDirPath."consulta_solucoes.php||".$iconsPath."solucoes2.png|centro
.|".TRANS('MNL_EMPRESTIMOS')."|".$ocoDirPath."emprestimos.php||".$iconsPath."emprestimos.png|centro
.|".TRANS('MNL_MURAL')."|".$ocoDirPath."avisos.php||".$iconsPath."mural.png|centro
.|".TRANS('MNL_RELATORIOS')."|".$ocoDirPath."relatorios.php|||centro
..|SLAs|".$ocoDirPath."relatorio_slas_2.php||".$iconsPath."sla.png|centro";
//.|".TRANS('MNL_SENHA']."|".$invDirPath."altera_senha.php||".$iconsPath."password.png|centro

	$mid->setMenuStructureString($menuOcomon);
	$mid->parseStructureForMenu('treemenu2');
	//$mid->setTreeMenuTheme('kde_');

	$menuAdmin =".|".TRANS('MNL_CONF')."
..|".TRANS('MNL_CONF_GERAL')."|".$admDirPath."configGeral.php|||centro
..|".TRANS('MNL_CONF_ABERTURA')."|".$admDirPath."configuserscreen.php|||centro
..|".TRANS('MNL_SCREEN_PROFILE')."|".$admDirPath."screenprofiles.php|||centro
..|".TRANS('MNL_CONF_SMTP')."|".$admDirPath."configmail.php|||centro
..|".TRANS('MNL_CONF_MSG')."|".$admDirPath."configmsgs.php|||centro
..|".TRANS('MNL_CONF_APARENCIA')."|".$admDirPath."aparencia.php|||centro
.|".TRANS('MNL_OCORRENCIAS')."
..|".TRANS('MNL_AREAS')."|".$admDirPath."sistemas.php|||centro
..|".TRANS('MNL_CONF_AREAS')."|".$admDirPath."sistemas_conf_abertura.php|||centro
..|".TRANS('MNL_PROBLEMAS')."|".$admDirPath."problemas.php|||centro
..|".TRANS('MNL_STATUS')."|".$admDirPath."status.php|||centro
..|".TRANS('MNL_PRIORIDADES')."|".$admDirPath."prioridades.php|||centro
..|".TRANS('MNL_PRIORIDADES_ATEND')."|".$admDirPath."prioridades_atendimento.php|||centro
..|".TRANS('MNL_FERIADOS')."|".$admDirPath."feriados.php||".$iconsPath."feriado.png|centro
..|".TRANS('MNL_SOLUCOES')."|".$admDirPath."tipo_solucoes.php|||centro
..|".TRANS('MNL_SCRIPTS')."|".$admDirPath."scripts.php|||centro
..|".TRANS('MNL_OCORRENCIAS')."|".$ocoDirPath."ocorrencias.php|||centro
..|".TRANS('MNL_MAIL_TEMPLATES')."|".$admDirPath."mail_templates.php|||centro
..|".TRANS('MNL_DIST_LISTS')."|".$admDirPath."mail_distribution_lists.php|||centro
.|".TRANS('MNL_INVENTARIO')."
..|".TRANS('MNL_EQUIPAMENTOS')."|".$admDirPath."tipo_equipamentos.php|||centro
..|".TRANS('MNL_COMPONENTES')."|".$admDirPath."tipo_componentes.php|||centro
..|".TRANS('MNL_FABRICANTES')."|".$invDirPath."fabricantes.php|||centro
..|".TRANS('MNL_FORNECEDORES')."|".$invDirPath."fornecedores.php|||centro
..|".TRANS('MNL_SITUACOES')."|".$admDirPath."situacoes.php|||centro
..|".TRANS('MNL_GARANTIA')."|".$invDirPath."tempo_garantia.php|||centro
..|".TRANS('MNL_SW')."|".$invDirPath."softwares.php||".$iconsPath."softwares2.png|centro
.|".TRANS('MNL_USUARIOS')."|".$admDirPath."usuarios.php||".$iconsPath."kdmconfig.png|centro
.|".TRANS('MNL_LOCAIS')."|".$admDirPath."locais.php|||centro
.|".TRANS('MNL_UNIDADES')."|".$admDirPath."unidades.php|||centro
.|".TRANS('MNL_CC')."|".$admDirPath."ccustos.php|||centro
.|".TRANS('MNL_PERMISSOES')."|".$admDirPath."permissoes.php||".$iconsPath."permissao.png|centro";


if($OPERADOR_AREA){
	$menuAdmin =".|".TRANS('MNL_OCORRENCIAS')."
..|".TRANS('MNL_PROBLEMAS')."|".$admDirPath."problemas.php|||centro
..|".TRANS('MNL_SCRIPTS')."|".$admDirPath."scripts.php|||centro
.|".TRANS('MNL_USUARIOS')."|".$admDirPath."usuarios.php||".$iconsPath."kdmconfig.png|centro";
}
//.|".TRANS('MNL_SENHA']."|".$invDirPath."altera_senha.php||".$iconsPath."password.png|centro";

/*
..|".TRANS('MNL_COMPONENTES_MODEL','Modelos de componentes')."
...|".TRANS('MNL_CDROM')."|".$invDirPath."itens.php?tipo=5||".$iconsPath."cdrom.png|centro
...|".TRANS('MNL_DVD')."|".$invDirPath."itens.php?tipo=8||".$iconsPath."cdrom.png|centro
...|".TRANS('MNL_GRAV')."|".$invDirPath."itens.php?tipo=9||".$iconsPath."cdrom.png|centro
...|".TRANS('MNL_HD')."|".$invDirPath."itens.php?tipo=1||".$iconsPath."hdd.png|centro
...|".TRANS('MNL_MB')."|".$invDirPath."itens.php?tipo=10|||centro
...|".TRANS('MNL_MEMO')."|".$invDirPath."itens.php?tipo=7||".$iconsPath."memoria.png|centro
...|".TRANS('MNL_MODEM')."|".$invDirPath."itens.php?tipo=6||".$iconsPath."placa.png|centro
...|".TRANS('MNL_PROC')."|".$invDirPath."itens.php?tipo=11|||centro
...|".TRANS('MNL_REDE')."|".$invDirPath."itens.php?tipo=3||".$iconsPath."placa.png|centro
...|".TRANS('MNL_SOM')."|".$invDirPath."itens.php?tipo=4||".$iconsPath."placa.png|centro
...|".TRANS('MNL_VIDEO')."|".$invDirPath."itens.php?tipo=2||".$iconsPath."placa.png|centro

*/

	//$mid->setMenuStructureFile('admin-menu.txt');
	$mid->setMenuStructureString($menuAdmin);
	$mid->parseStructureForMenu('treemenu3');


// ADICIONADO PARA USUARIO SOMENTE CONSULTAS E ABERTURA DE OCORRENCIA
//	$menuTheme = ".|".TRANS('MNL_THEME')."|".$ocoDirPath."user_theme.php|".TRANS('MNL_THEME_HNT')."|".$iconsPath."colors.png|centro";
//	$menuSenha = ".|".TRANS('MNL_SENHA')."|".$invDirPath."altera_senha.php||".$iconsPath."password.png|centro";
//	$menuLang = ".|".TRANS('MNL_LANG')."|".$ocoDirPath."user_lang.php||".$iconsPath."brasil-flag-icon.png|centro";
//	.|".TRANS('MNL_INICIO']."|".$ocoDirPath."abertura_user.php?action=listall|".TRANS('MNL_INICIO_HNT']."|".$iconsPath."gohome.png|centro
//	$menuSimples =".|||||

$menuSimples =".|".TRANS('MNL_INICIO')."|".$ocoDirPath."abertura.php|".TRANS('MNL_INICIO_HNT')."|".$iconsPath."gohome.png|centro
.|".TRANS('MNL_ABRIR')."|".$ocoDirPath."incluir.php|".TRANS('MNL_ABRIR_HNT')."|".$iconsPath."fone.png|centro
.|".TRANS('MNL_MEUS')."|".$ocoDirPath."abertura_user.php?action=listall|".TRANS('MNL_MEUS_HNT')."|".$iconsPath."search.png|centro
//".$menuTheme."
//".$menuSenha."
//".$menuLang."
";
//.|".TRANS('MNL_SENHA']."|".$invDirPath."altera_senha.php||".$iconsPath."password.png|centro

	$mid->setMenuStructureString($menuSimples);
	$mid->parseStructureForMenu('treemenu4');
	//$mid->setTreeMenuTheme('kde_');

	$menuHome =".|".TRANS('MNL_INICIO')."|home.php|".TRANS('MNL_INICIO_HNT')."|".$iconsPath."gohome.png|centro
.|".TRANS('MNL_MEUS')."|".$ocoDirPath."abertura_user.php?action=listall|".TRANS('MNL_MEUS_HNT')."|".$iconsPath."search.png|centro
.|".TRANS('MNL_THEME')."|".$ocoDirPath."user_theme.php|".TRANS('MNL_THEME_HNT')."|".$iconsPath."colors.png|centro
.|".TRANS('MNL_SENHA')."|".$invDirPath."altera_senha.php||".$iconsPath."password.png|centro";
//".$menuTheme."
//".$menuSenha."
//".$menuLang."

	$mid->setMenuStructureString($menuHome);
	$mid->parseStructureForMenu('treemenu5');

// FIM DA INCLUSAO	PARA USUARIO SOMENTE CONSULTAS E ABERTURA DE OCORRENCIA

print "<html>";
print "<title>OcoMon</title>";
print "<link rel=stylesheet type='text/css' href='includes/css/estilos.css.php'>";
//print "</head><body style={background-image:url('MENU_IMG.png'); background-repeat:no-repeat;}>"; //background-position:center; background-repeat:no-repeat;  background-attachment: fixed;
print "</head><body class='menu'>";

	//Para compatibilizar os scripts da versão 1.40 na restauração da sessão
	if (isset($_SESSION['s_page_ocomon']) && $_SESSION['s_page_ocomon'] == basename($_SESSION['s_page_ocomon'])) $_SESSION['s_page_ocomon'] = $ocoHome.$_SESSION['s_page_ocomon'];
	if (isset($_SESSION['s_page_simples']) && $_SESSION['s_page_simples'] == basename($_SESSION['s_page_simples'])) $_SESSION['s_page_simples'] = $simplesHome.$_SESSION['s_page_simples'];
	if (isset($_SESSION['s_page_invmon']) && $_SESSION['s_page_invmon'] == basename($_SESSION['s_page_invmon'])) $_SESSION['s_page_invmon'] = $invHome.$_SESSION['s_page_invmon'];
	if (isset($_SESSION['s_page_home']) && $_SESSION['s_page_home'] == basename($_SESSION['s_page_home'])) $_SESSION['s_page_home'] = $homeHome.$_SESSION['s_page_home'];
	if (isset($_SESSION['s_page_admin']) && $_SESSION['s_page_admin'] == basename($_SESSION['s_page_admin'])) $_SESSION['s_page_admin'] = $admHome.$_SESSION['s_page_admin'];


	if (isset($_SESSION['s_page_simples'])) $simplesHome = $_SESSION['s_page_simples']; else $simplesHome = $ocoDirPath."abertura_user.php?action=listall";
	if (isset($_SESSION['s_page_invmon'])) $invHome = $_SESSION['s_page_invmon']; else $invHome = $invDirPath."abertura.php";
	if (isset($_SESSION['s_page_home'])) $homeHome = $_SESSION['s_page_home']; else $homeHome = "home.php";
	if (isset($_SESSION['s_page_ocomon'])) $ocoHome = $_SESSION['s_page_ocomon']; else $ocoHome = $ocoDirPath."abertura.php";
	if (isset($_SESSION['s_page_admin'])) $admHome = $_SESSION['s_page_admin']; else $admHome = $admDirPath."sistemas.php";


	if (isset($_GET['LOAD']) && $_GET['LOAD'] == 'ADMIN'){//QUANDO A PÁGINA FOR RECARREGADA PARA NOVO TEMA
		$where = TRANS('MNL_ADM');
		$menu="treemenu3";
		print "<script>window.parent.frames['centro'].location = '".$admHome."'</script>";
	} else

	if ($_GET['sis']=="o") {
		$where = "<a class='menu' href='".$ocoDirPath."abertura.php' target='centro'>".TRANS('MNL_OCORRENCIAS')."</a>";
		print "<script>window.parent.frames['centro'].location = '".$ocoHome."'</script>";
		$menu = "treemenu2";
	} else
	if ($_GET['sis']=="i"){
		$where = "<a class='menu' href='".$invDirPath."abertura.php' target='centro'>".TRANS('MNL_INVENTARIO')."</a>";
		print "<script>window.parent.frames['centro'].location = '".$invHome."'</script>";

		$menu="treemenu1";
	} else
	if ($_GET['sis']=="a"){
		$where = TRANS('MNL_ADM');//<img src='".$phplmDirPath."menuicons/".$iconsPath."sysadmin.png'>
		$menu="treemenu3";
		print "<script>window.parent.frames['centro'].location = '".$admHome."'</script>";
	// para usuarios simples
	} else
	if ($_GET['sis']=="s"){
		$where = "<a class='menu' href='".$ocoDirPath."abertura_user.php?action=listall' target='centro'>".TRANS('MNL_OCORRENCIAS')."</a>";
		print "<script>window.parent.frames['centro'].location = '".$simplesHome."'</script>";
		$menu = "treemenu4";
	// fim da inclusao para usuario simples

	} else
	if ($_GET['sis']=="h"){
		//$where = "HOME";//<img src='".$phplmDirPath."menuicons/".$iconsPath."sysadmin.png'>
		$where = "<a class='menu' href='home.php' target='centro'>".TRANS('MNS_HOME')."</a>";
		if ($_SESSION['s_nivel'] > 2 ){
			$menu = "treemenu4";
			print "<script>window.parent.frames['centro'].location = '".$simplesHome."'</script>";
		} else {
			$menu="treemenu5";
			print "<script>window.parent.frames['centro'].location = '".$homeHome."'</script>";
		}
	// para usuarios simples
	} else {
		$where = "<a class='menu' href='menu.php' target='centro'>".TRANS('MNS_HOME')."</a>";
	}


	//print "<img src=".$logosPath."phpmyadmin.png>";
	//print "<table class='menutop' width='100%' border='0'><tr class='menutop'><td class='line'><b>".$where."</b></td></tr></table>";
	//#C7C8C6   //<tr class='menutop'> //bgcolor='".BODY_COLOR."'
	print "<TABLE  class='header_centro' cellspacing='1' border='0' cellpadding='1' width='100%'><tr><td><b>".$where."</b></td></tr></table>";

	//<TABLE bgcolor='#C7C8C6' STYLE='{border-bottom: thin solid #999999; }' cellspacing='1' border='0' cellpadding='1' align='center' width='100%'>
	print "<table class='menu'>";
		if (!empty($menu)){
		print $mid->newTreeMenu($menu);
		}
	print "</table>";



print "</body></html>";

?>
<script type="text/javascript">
	 function popup(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
      	//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
		return false
     }
</script>
