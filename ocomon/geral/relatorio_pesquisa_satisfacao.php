<HTML>
   <HEAD>

<?php
        $sROOT = "http://".$_SERVER['SERVER_NAME'].substr(substr(__FILE__, strlen(realpath($_SERVER['DOCUMENT_ROOT']))), 0, - 14 - strlen(basename(__FILE__)))."/includes";

        print "<link type='text/css' href='$sROOT/css/tablesorter_blue/style.css' rel='stylesheet' />";
        print "<link type='text/css' href='$sROOT/css/grass/jquery-ui-1.8.21.custom.css' rel='stylesheet' />";
        print "<script type='text/javascript' src='$sROOT/js/jquery-1.7.2.min.js'></script>";
        print "<script type='text/javascript' src='$sROOT/js/jquery-ui-1.8.20.custom.min.js'></script>";
        print "<script type='text/javascript' src='$sROOT/js/jquery.tablesorter.js'></script>";

?>
	<script>
		$(function(){
			$( "#open_graphs" )
				.button()
				.click(function() {
				$( "#graficos_pesquisas" ).dialog( "open" );
			});

			$( "#graficos_pesquisas" ).dialog({
	                        autoOpen: false,
                        	height: 600,
                	        width: 700,
        	                modal: true
			});

			$( "#cel_coment" ).click(function(){
				alert("OK");
//				$( this ).dialog();
			});
		});
		function mostra_comentarios(coment){
			$(function(){

			   $("<p>" + coment + "</p>").dialog({
			   	modal: true
			   });

			});

		}

	</script>
	<style>
	.stop-scrolling {
		height: 100%;
		overflow: hidden;
	}
	</style>

   </HEAD>

</HTML>

<?php
	session_start();

	include ("../../includes/include_geral.inc.php");

	$cor=TD_COLOR;
	$cor1=TD_COLOR;
//	$percLimit = 20; //Tolerancia em percentual
//	$imgSlaR = 'sla1.png';
//	$imgSlaS = 'checked.png';

	$resultado = mysql_query("SELECT * FROM questionario ORDER by NOME"); // or die( "<script> alert('".mysql_error()."'); </script>");
	$linhas = mysql_numrows($resultado);

	print "<table border='1'>";
	if ($linhas==0)
	{
		$aviso = TRANS('MSG_NONE_OCCO_LOCATED');
		print "<script>alert('".TRANS('MSG_NONE_OCCO_LOCATED')."'); history.back();</script>";
	} else {
		if ($linhas>1) {
			print "<TR><td class='line'><B> NENHUM REGISTRO ENCONTRADO! ($linhas linhas)</B></TD></TR>";
		} else {
	               	print "<TR><td class='line'><B> 1 Ocorrencia </B></TD></TR>";
		}

			print "</table>";
			   print "<table border='0' id='tabela_consultgeral' class='tablesorter' align='center' >";
			   print "<thead>" ;

			$valign = " valign='top center' ";
			print "<TR>";
			print "<TH ".$valign.">Nome</TH>";
			print "<TH ".$valign." width='10%'>Integracao com a rede</TH>";
			print "<TH ".$valign." width='10%'>Visual do Sistema</TH>";
			print "<TH ".$valign." width='10%'>Facilidade<br> de Uso</TH>";
			print "<TH ".$valign." width='10%'>Acompanhamento dos Chamados</TH>";
			print "<TH ".$valign." width='10%'>Sistema <br>(em Geral)</TH>";
			print "<TH ".$valign.">Comentario</TH>";

			print "</TR>";
			print "</thead>";

			$i=0;
			$j=2;
			$calcula = false;
			$max_char = 40;
			while ($row = mysql_fetch_array($resultado))
			{
				$i++;
				if ($j % 2)
				{
					$color =  BODY_COLOR;
					$trClass= "lin_par";
				}
				else
				{
					$color = "white";
					$trClass = "lin_impar";
				}
				$comentario = $row['COMENTARIO'];
				if ( strlen($comentario) > $max_char ) {
					$comentario = substr($comentario,0,$max_char);
					$comentario .= " (...)";
				}

				$comentario_completo = $row['COMENTARIO'];

				print "<tr align='center'>";
				print "<TD align='left'".$valign.">".$row['NOME']."</TD>";
//				print "<TD class='line'".$valign.">".$row['LOGIN']."</TD>";
				print "<TD '".$valign."'>".$row['PERGUNTA1']."</TD>";
				print "<TD '".$valign."'>".$row['PERGUNTA2']."</TD>";
				print "<TD '".$valign."'>".$row['PERGUNTA3']."</TD>";
				print "<TD '".$valign."'>".$row['PERGUNTA4']."</TD>";
				print "<TD '".$valign."'>".$row['PERGUNTA5']."</TD>";
				if (strlen($comentario_completo) > 0) {
					print "<TD onclick=\"mostra_comentarios('$comentario_completo');\" name='cel_coment' id='cel_coment' '".$valign."'> $comentario </TD>";
				} else {
					print "<TD name='cel_coment' id='cel_coment' '".$valign."'> </TD>";
				}
				print "</tr>";


			} //while

                print "</tbody></TABLE>";

        }
?>
