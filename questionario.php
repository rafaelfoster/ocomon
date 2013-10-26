<HTML>
	<head>
	<link type="text/css" href="includes/css/grass/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
        <script type="text/javascript" src="includes/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="includes/js/jquery-ui-1.8.20.custom.min.js"></script>
	<script>
	$(function() {
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-form" ).dialog({
			autoOpen: true,
			height: 620,
			width: 700,
			modal: true,
			dialogClass: 'no-close',
			closeOnEscape: false,
			buttons: {
				"OK": function(){
					var dados = $("#formulario").serialize();
					 for (i = 1; i < 5; i++){
						var controle = 0;
//						alert(document.formulario.elements.length);
						for (i=0;i<document.formulario.elements.length;i++){
							if (document.formulario.elements[i].type == "radio"){
								if (document.formulario.elements[i].checked == true){
									controle++;
								}
							}
						}
					}

					if (controle != 5){
						var msg = "<div align='center'><font color='white'><b> Voce precisa selecionar 1 alternativa de cada questao</b> </font></div>";
						$( msg ).dialog({
							open: function(){
								$( this ).siblings( 'div.ui-dialog-titlebar' ).remove();
							},
							dialogClass: 'ui-state-error ui-corner-all',
							modal: true,
							buttons: {
					                    Ok: function() {
			                                        $( this ).dialog( "close" );
                        	                            }
                        	                        }
						});
					}  else {

					$.ajax({
						type: "POST",
						url: "funcoes_grava_questionario.php",
						async: false,
						data: dados,
						success: function(data) {
							$("<p><font color='red'><b>Pesquisa concluida.<br>Muito Obrigado pela sua colaboracao</p></b></font>").dialog({
								modal: true,
								open: function(){
									$( this ).siblings( 'div.ui-dialog-titlebar' ).remove();
								},
								buttons: {
									"OK": function(){
										$( "#dialog-form" ).dialog( "close" );
										$( this ).dialog( "close" );
									},
								},
							});
						},
					});
				}
//					$( "#formulario" ).submit() ;
//				},
//				Cancel: function() {
//					$( this ).dialog( "close" );
				}
			}
		});

	});

	</script>

</HTML>

<?php
	session_start();

	include ("includes/include_geral_III.inc.php");

	$user = $_SESSION['s_usuario'];
	$result = mysql_query("SELECT nome, login, data_inc FROM usuarios WHERE login = '$user' ");

	while ( $row = mysql_fetch_array($result) )
	{
		$UserName = $row["nome"];
		$UserLogin = $row["login"];
		$DataInc = $row["data_inc"];
	}

	$respondido = mysql_query("SELECT ID FROM questionario WHERE LOGIN = '$UserLogin' ");
	$Linhas = mysql_num_rows($respondido);

	//echo date('l jS F (Y-m-d)', strtotime('-3 days'));
	//echo date('Y-m-d');
	$DataAtual = date('Y-m-d');
	$date1 = new DateTime($DataInc);
	$date2 = new DateTime($DataAtual);
	$interval = $date1->diff($date2);
	//echo "difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days ";
	//echo "<br>difference " .$interval->d." days ";

	$mes = $interval->y;
	$dias = $interval->d;

	$pergunta = array(
		1=>"1) O login de acesso ao Ocomon foi integrado ao seu login de rede. Você ficou satisfeito com esse facilitador?",
		2=>"2) Um dos objetivos da nossa equipe foi tornar o visual mais amigável. Qual o seu grau de satisfação com o visual do sistema?",
		3=>"3) Um dos objetivos da nossa equipe foi diminuir a complexibilidade, criando um caminho direto para a tela de “Abrir chamado”.<br> Qual o seu grau de satisfação com a facilidade de abertura de chamado?",
		4=>"4) O Ocomon propicia uma maior interação do colaborador(a) com o chamado.<br> Qual o seu grau de satisfação com os dados exibidos pelo sistema para acompanhamento do chamado?",
		5=>"5) Qual o seu grau de satisfação como um todo com relação ao sistema Ocomon?"
	);

	if ( ($dias >= 5 || $mes >= 1) && ($Linhas == 0) ){

		echo "<div id='dialog-form' title='Pesquisa de Satisfacao'>";
		echo "<div align='center'>";
		echo "<p><font size='2'><b>:::Bem-vindo a pesquisa de satisfação do sistema Ocomon:::<br>";
		echo "Abaixo disponibilizamos 5 perguntas que devem ser respondidas de acordo com o seu grau de satisfação, dedique alguns minutos do seu tempo para avaliar os questionamentos abaixo.";
		echo "<br>Pontue cada questão com base na classificação a seguir:<br><br>";

		echo "<table border='2' align='center'><tr><b>";
		echo "<td><b>Muito satisfeito</td>";
		echo "<td><b>Satisfeito</td>";
		echo "<td><b>Indiferente</td>";
		echo "<td><b>Insatisfeito</td>";
		echo "<td><b>Muito insatisfeito</td></tr></b>";
		echo "<tr>";


                echo "<tr><font color='red'><b> Obs.: É  obrigatorio o preenchimento deste questionario ao menos uma vez. </b></font></tr>";

		for ( $i = sizeof($pergunta); $i >= 1; $i-- ){
			echo "<td align='center'>$i</td>";
		}

		echo "</tr></table></font></p></div>";

		echo "<div align='left'>";
		echo "<form name='formulario' id='formulario' action='funcoes_grava_questionario.php' method='POST'>";
		echo "<table CELLPADDING='10' CELLSPACING='2'>";
		echo "</b><input type='hidden' width='100px' name='name' id='name' class='text ui-widget-content ui-corner-all' value='$UserName' /></td><br>";
		echo "</b><input type='hidden' width='100px' name='login' id='login' class='text ui-widget-content ui-corner-all' value='$UserLogin' /></td>";
		for ( $i = 1; $i <= sizeof($pergunta); $i++ ){
			echo "<td width='70%'><label for='pergunta$i'>$pergunta[$i]<br></label></td>";
			for ( $j = 5; $j >= 1; $j-- ){
				echo "<td style='margin: 0; padding: 0;' colspan='1'><input type='radio' name='pergunta$i' id='pergunta$i' value='$j' class='' />$j</td>";
			}
			echo "</tr>";
		}

		echo "</table>";
		echo "Gostaria de deixar algum comentario/sugestão???<br>";
		echo "<textarea name='comentarios' id='comentarios' rols='20' cols='70'>";
		echo "</textarea>";
		echo "</form>";
		echo "</div>";
		echo "</div>";

	}
?>
