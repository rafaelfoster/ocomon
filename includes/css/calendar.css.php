<?php session_start();
	require_once ('../../includes/config.inc.php');

	if (is_file("../includes/classes/conecta.class.php"))
		require_once ("../includes/classes/conecta.class.php"); else
		require_once ("../classes/conecta.class.php");

	$conec = new conexao;
	$conec->conecta('MYSQL');

	//$qry = "SELECT * FROM styles";
	//$exec = mysql_query($qry);
	//$row = mysql_fetch_array($exec);

	if (isset($_SESSION['s_uid'])) {
	//if (isset($_COOKIE['cook_oco_uid'])) {

		$qry = "SELECT * FROM temas t, uthemes u  WHERE u.uth_uid = ".$_SESSION['s_uid']." and t.tm_id = u.uth_thid";
		$exec = mysql_query($qry) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DO TEMA!<BR>'.$qry);
		$row = mysql_fetch_array($exec);
		$regs = mysql_num_rows($exec);
		if ($regs==0){ //SE NÃO ENCONTROU TEMA ESPECÍFICO PARA O USUÁRIO
			$qry = "SELECT * FROM styles";
			$exec = mysql_query($qry);
			$row = mysql_fetch_array($exec);
		}
	} else {
		$qry = "SELECT * FROM styles";
		$exec = mysql_query($qry);
		$row = mysql_fetch_array($exec);
	}



print "#calendarDiv{
	position:absolute;
	width:205px;
	border:1px solid #999999; ". //*#317082 - BORDA DA CAIXA DO CALENDÁRIO*/
	"padding:1px;
	background-color: #FFF; ".//*#FFF - COR DE FUNDO DO CALENDÁRIO*/
	"font-family:arial;
	font-size:10px;
	padding-bottom:20px;
	visibility:hidden;
}";
print "#calendarDiv span,#calendarDiv img{
	float:left;
}
#calendarDiv .selectBox,#calendarDiv .selectBoxOver{

	line-height:12px;
	padding:1px;
	cursor:pointer;
	padding-left:2px;
}

#calendarDiv .selectBoxTime,#calendarDiv .selectBoxTimeOver{
	line-height:12px;
	padding:1px;
	cursor:pointer;
	padding-left:2px;
}";

print "#calendarDiv td{
	padding:3px;
	margin:0px;
	font-size:10px;
}";

print "#calendarDiv .selectBox{";

	if ($row['tm_color_topo'] == "IMG_DEFAULT") {
		print "border:1px solid #E2EBED;";
		print "color: white; ";
	} else {
		print "border:1px solid ".$row['tm_color_topo_font'].";".	//*#E2EBED - BORDA DO MES E ANO NA CAIXA PRINCIPAL*/
			"color: ".$row['tm_color_topo_font']."; ";//*#E2EBED - COR DO MES E ANO NA CAIXA PRINCIPAL*/white
	}
	print "position:relative; ";
print "}";
print "#calendarDiv .selectBoxOver{
	border:1px solid #FFF;";

	if ($row['tm_color_topo'] == "IMG_DEFAULT") {
		print "border:1px solid #FFF;";
		print "background-color: #666666; ";
		print "color: #FFF;";
	} else {
	print "border:1px solid ".$row['tm_color_topo_font']."; background-color: ".$row['tm_color_topo'].";". //*#317082 -COR DO FUNDO DO TEXTO DE MES E ANO NA CAIXA PRINCIPAL - HOVER*/#666666
		"color: ".$row['tm_color_topo_font'].";";
	}
	print "position:relative;".
"}
#calendarDiv .selectBoxTime{
	border:1px solid #317082;
	color: #317082;
	position:relative;
}";
print "#calendarDiv .selectBoxTimeOver{
	border:1px solid #216072;
	color: #216072;
	position:relative;
}

#calendarDiv .topBar{
	height:16px;
	padding:2px;";
	if ($row['tm_color_topo'] == "IMG_DEFAULT") {
		print "background-color: #6F666E; ";
	} else {
		print "background-color: ".$row['tm_color_topo'].";"; //*#317082 - BARRA SUPERIOR*/ #6F666E;
		print "background-repeat: repeat ;";
	}
print "}";

print "#calendarDiv .activeDay{".	//* Active day in the calendar */
	"color:#FF0000; ".//*#FF0000*/
"}";
print "#calendarDiv .todaysDate{
	height:17px;
	line-height:17px;
	padding:2px;
	background-color: #F0F0F0;". //*#E2EBED  - BARRA INFERIOR*/
	"text-align:center;
	position:absolute;
	bottom:0px;
	width:201px;
}
#calendarDiv .todaysDate div{
	float:left;
}";

print "#calendarDiv .timeBar{
	height:17px;
	line-height:17px;
	background-color: #E2EBED;". //*#E2EBED*/
	"width:72px;
	color:#FFF;
	position:absolute;
	right:0px;
}

#calendarDiv .timeBar div{
	float:left;
	margin-right:1px;
}";


print "#calendarDiv .monthYearPicker{
	background-color: #F0F0F0;". //*#E2EBED - FUNDO DA CAIXA DE SELEÇÃO DESDOBRADA*/
	"border:1px solid #AAAAAA;". //*#AAAAAA - BORDA DA CAIXA DE SELEÇÃO DO ANO E MES DESDOBRADA*/
	"position:absolute;
	color: #5E515B; ".//*#317082  - COR DO ANO E MES NA CAIXA DE SELEÇÃO DESDOBRADA*/
	"left:0px;
	top:15px;
	z-index:1000;
	display:none;

}
#calendarDiv #monthSelect{
	width:70px;
}
#calendarDiv .monthYearPicker div{
	float:none;
	clear:both;
	padding:1px;
	margin:1px;
	cursor:pointer;
}";
print "#calendarDiv .monthYearActive{
	background-color:#DBDBDB; ".//*#317082 HOVER DA CAIXA DE SELEÇÃO ANO-MES DESDOBRADA - FUNDO*/
	"color: black;". //*#E2EBED - HOVER DA CAIXA DE SELEÇÃO ANO-MES DESDOBRADA - FONTE*/
"}";

print "#calendarDiv td{
	text-align:right;
	cursor:pointer;
}

#calendarDiv .topBar img{
	cursor:pointer;
}
#calendarDiv .topBar div{
	float:left;
	margin-right:1px;
}";
?>