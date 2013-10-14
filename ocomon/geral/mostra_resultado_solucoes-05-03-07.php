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
  */session_start();

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

 	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	print "<BR><B>".TRANS('TTL_CONS_SOLVES','Consulta de Soluções')."</B><BR>";


		$probB = str_replace(" ","%",trim($_POST['problema'])); //SEM FORMATAÇÃO HTML
		$probA = htmlentities(str_replace(" ","%",trim($_POST['problema']))); //FORMATADOS EM HTML


		dump($probA, 'PROB A:');

		dump($probB, 'PROB B:');

		//Quantidade de palavras digitadas
		$termos = explode("%",$probA);
		$termos = array_unique($termos);
		reIndexArray($termos);

		dump ($termos, 'TERMOS:');

		$destacaProb = $probA."%".$probB; //TODOS OS TERMOS COM OU SEM FORMATAÇÃO HTML
		$palavrasA = explode("%", $destacaProb);
		$palavrasA = array_unique($palavrasA); //RETIRA OS ELEMENTOS REPETIDOS (DISTINGUE AS FORMAÇÕES HTML)

		reIndexArray($palavrasA);

		if (isset($_POST['anyword']) || (count ($termos)==1)) {
			$OPER = " [Pelo menos uma das palavras]. ";
		} else
			$OPER = " [Todas as palavras]. ";

	//----- TESTES PARA ENCONTRAR O BUG ---------//
		$todosTermos = "";
		$arrayTermos = array ();
		$palavrasA = array_unique($palavrasA);
		for ($b=0; $b<count($palavrasA); $b++) {
			$todosTermos.=$palavrasA[$b].", ";
			$arrayTermos[] = noHtml($palavrasA[$b]);
		}
		reIndexArray($arrayTermos);
		dump($arrayTermos, 'ARRAY TERMOS:');

		$arrayTeste = array();
		for ($b=0; $b<count($arrayTermos); $b++) {
			//$arrayTeste[] = $arrayTermos[$b];
			$arrayTeste[] = toHtml($arrayTermos[$b]);
		}

		array_unique($arrayTeste);
		reIndexArray($arrayTeste);
		dump($arrayTeste, 'TESTE INVERTENDO');



	//----- TESTES PARA ENCONTRAR O BUG ---------//

		print "Termo perquisado: \"".trim($_POST['problema'])."\". Critério: ".$OPER."</br>";
		print "Termo perquisado: ".$todosTermos.". Critério: ".$OPER."</br>";

		print "<br>Quantidade de Termos da pesquisa:".count($termos)."<br>";

		$qrySolucao = "";
		$qryAssentamento = "";
		$qryProblema = "";
		$qryDesc = "";

		$destacaProb = str_replace("%","|", $destacaProb);

		//SQL GLOBAL - RETORNA TODAS AS OCORRÊNCIAS QUE CONTENHAM PELO MENOS UM DOS TERMOS DE PESQUISA
		for ($i=0; $i<count($palavrasA); $i++){
			//Monta o SQL de forma dinâmica de acordo com a quantidade de palavras a serem pesquisadas
			if (isset($palavrasA[$i])) {
				if (strlen($qrySolucao)>0)
					$qrySolucao.= " OR ";
				$qrySolucao.= "\n (lower( s.solucao ) LIKE lower(  '%".$palavrasA[$i]."%' ) OR  ".
							"\n lower( s.problema ) LIKE lower(  '%".$palavrasA[$i]."%' )) ";

				if (strlen($qryAssentamento)>0)
					$qryAssentamento.= " OR ";
				$qryAssentamento.= "\n lower( a.assentamento ) LIKE lower(  '%".$palavrasA[$i]."%' ) ";

// 				if (strlen($qryProblema)>0)
// 					$qryProblema.= " OR ";
// 				$qryProblema.= "\n lower( s.problema ) LIKE lower(  '%".$palavrasA[$i]."%' ) ";

				if (strlen($qryDesc)>0)
					$qryDesc.= " OR ";
				$qryDesc.= "\n lower(o.descricao)  LIKE lower('%".$palavrasA[$i]."%') ";
			}
		}

		$query = "";

		$query = "SELECT s.numero as numero, s.problema as problema, s.solucao as solucao, s.data as data, ".
					"\n s.responsavel as responsavel, a.assentamento as assentamento, o.descricao as descricao, u.* ";

		$queryFrom = "\nFROM solucoes s, assentamentos a, ocorrencias as o, usuarios as u ";

		if (isset($_POST['onlyImgs'])) {
			$queryFrom.=", imagens i ";
		}

// 		$queryWhere = " WHERE (lower( s.solucao ) LIKE lower(  '%".$probA."%' ) OR lower( s.solucao ) LIKE lower(  '%".$probB."%' ) OR ".
// 						"lower( a.assentamento ) LIKE lower(  '%".$probA."%' ) OR lower( a.assentamento ) LIKE lower(  '%".$probB."%' ) OR ".
// 						"lower( s.problema ) LIKE lower(  '%".$probA."%' ) OR lower( s.problema ) LIKE lower(  '%".$probB."%' ) OR ".
// 						"lower(o.descricao)  LIKE lower('%".$probA."%') OR lower(o.descricao)  LIKE lower('%".$probB."%')) ".
// 						"AND (a.ocorrencia = s.numero AND o.numero = s.numero and o.operador = u.user_id ";

		//O SQL, em um primeiro momento, pesquisa por qualquer uma das palavras digitadas.
		$queryWhere = "\nWHERE ((".$qrySolucao.") OR (".$qryAssentamento.")  OR (".$qryDesc.") )". //OR (".$qryProblema.")
						"\n AND (a.ocorrencia = s.numero AND o.numero = s.numero and o.operador = u.user_id ";


		if (isset($_POST['onlyImgs'])) {
			$queryWhere.="\n and o.numero = i.img_oco ";
		}

		$queryWhere.=" ) ";

		$query.=$queryFrom.$queryWhere;

                if (!empty($_POST['data_inicial']))
                {
                        $data_inicial = str_replace("-","/",$_POST['data_inicial']);
			$data_inicial = datam($data_inicial);
                        $query.="and o.data_abertura >='".$data_inicial."' and o.data_fechamento >= '".$data_inicial."' ";
                }

                if (!empty($_POST['data_final']))
                {
                        $data_final = str_replace("-","/",$_POST['data_final']);
			$data_final = datam($data_final);
                        $query.="and o.data_abertura <='".$data_final."' and o.data_fechamento <= '".$data_final."' ";
		}

                if (!empty($_POST['operador']) and $_POST['operador'] != -1)
                {
                        $query.="and s.responsavel=".$_POST['operador']." ";
                }

                $query.="\nGROUP BY numero ORDER BY numero";// Retorna todos os registros onde pelo menos um dos termos existe.
		$query2 = $query;

		$resultado = mysql_query($query) or die ('ERRO NA BUSCA DE INFORMAÇÕES DAS TABELAS!'.dump($query));
		$resultado2 = mysql_query($query2);

		$linhas = mysql_numrows($resultado);

		$qryChkOco = array();
		$qryChkAss = array();
		$qryChkSol = array();
		$achou = array();
		$totalE = 0;//quantidade de registros onde pelo menos uma das palavras não foi encontrada.

		if ($linhas==0)
		{
			print "Nenhuma solução localizada. (REMOVER ESSA LINHA!)";
			dump($query,"QUERY EXECUTADA:");
			exit;
			$aviso = "Nenhuma solução localizada com esses critérios de busca.";
			print "<script>mensagem('".$aviso."'); history.back();</script>";
		} else
		if (!isset($_POST['anyword']) && count($termos)!=1) { //Condição  para checar se todos os termos existem
			print "<br><b>Entrei na condição pra buscar chamados com todos os termos (AND)!</b><br>";
			//Esse laço serve apenas para contabilizar a quantidade de registros onde nem todas as palavras pesquisadas são encontradas
			while ($rowA = mysql_fetch_array($resultado2))
			{
				for ($i=0; $i<count($palavrasA); $i++) {
// 					$qryChkOco[$i] = "SELECT * FROM ocorrencias WHERE numero = ".$rowA['numero']." AND ".
// 								"\n lower(descricao) like lower('%".$palavrasA[$i]."%') ";
// 					$execChkOco[$i] = mysql_query($qryChkOco[$i]) or die ('ERRO NA CONSULTA!<br>'.dump($qryChkOco[$i]));
// 					if (mysql_numrows($execChkOco[$i])>0) {
// 						$achou[] = $palavrasA[$i];
// 					}
// 					$qryChkAss[$i] = "SELECT * FROM assentamentos WHERE ocorrencia = ".$rowA['numero']." AND ".
// 								"\n lower(assentamento) like lower('%".$palavrasA[$i]."%') ";
// 					$execChkAss[$i] = mysql_query($qryChkAss[$i])or die ('ERRO NA CONSULTA!<br>'.dump($qryChkAss[$i]));
// 					if (mysql_numrows($execChkAss[$i])>0) {
// 						$achou[] = $palavrasA[$i];
// 					}
// 					$qryChkSol[$i] = "SELECT * FROM solucoes WHERE numero = ".$rowA['numero']." AND (".
// 								"\n lower(solucao) like lower('%".$palavrasA[$i]."%') OR ".
// 								"\n lower(problema) like lower('%".$palavrasA[$i]."%') )";
// 					$execChkSol[$i] = mysql_query($qryChkSol[$i])or die ('ERRO NA CONSULTA!<br>'.dump($qryChkSol[$i]));
// 					if (mysql_numrows($execChkSol[$i])>0) {
// 						$achou[] = $palavrasA[$i];
// 					}
					if (isset($palavrasA[$i])) {
						$qryChkOco[$i] = "SELECT * FROM ocorrencias WHERE numero = ".$rowA['numero']." AND ".
									"\n (lower(descricao) like lower('%".$palavrasA[$i]."%') ".
									"\n OR lower(descricao) like lower('%".toHtml($palavrasA[$i])."%')) ";
						$execChkOco[$i] = mysql_query($qryChkOco[$i]) or die ('ERRO NA CONSULTA!<br>'.dump($qryChkOco));
						if (mysql_numrows($execChkOco[$i])>0) {
							$achou[] = toHtml($palavrasA[$i]);
							$achou = array_unique($achou);
							//$achou = reIndexArray($achou);
						}
						$qryChkAss[$i] = "SELECT * FROM assentamentos WHERE ocorrencia = ".$rowA['numero']." AND ".
									"\n (lower(assentamento) like lower('%".$palavrasA[$i]."%') ".
									"\n OR lower(assentamento) like lower('%".toHtml($palavrasA[$i])."%') )";
						$execChkAss[$i] = mysql_query($qryChkAss[$i])or die ('ERRO NA CONSULTA!<br>'.dump($qryChkAss));
						if (mysql_numrows($execChkAss[$i])>0) {
							$achou[] = toHtml($palavrasA[$i]);
							$achou = array_unique($achou);
							//$achou = reIndexArray($achou);
						}
						$qryChkSol[$i] = "SELECT * FROM solucoes WHERE numero = ".$rowA['numero']." AND (".
									"\n (lower(solucao) like lower('%".$palavrasA[$i]."%')) OR ".
									"\n (lower(problema) like lower('%".$palavrasA[$i]."%')) OR ".
									"\n (lower(solucao) like lower('%".toHtml($palavrasA[$i])."%')) OR ".
									"\n (lower(problema) like('%".toHtml($palavrasA[$i])."%')) )";
						$execChkSol[$i] = mysql_query($qryChkSol[$i])or die ('ERRO NA CONSULTA!<br>'.dump($qryChkSol));
						if (mysql_numrows($execChkSol[$i])>0) {
							$achou[] = toHtml($palavrasA[$i]);
							$achou = array_unique($achou);
							//$achou = reIndexArray($achou);
						}
						//$achou = array_unique($achou);
					}
				}
				reIndexArray($achou);
				//if (count($achou) != count($palavrasA)) { //Não achou o termo
				print "COUNT ACHOU: ".count($achou)."<br>";
				dump($achou,"ACHOU");
				if (count($achou) < count($termos)) { //Não achou o termo
					$totalE++;
				}
				//ZERANDO O ARRAY ACHOU
				for ($j=0; $j<count($achou); $j++){
					array_pop($achou);
				}
			}

/*			$notAll = false;
			foreach ($palavrasA as $valor){
				if (!in_array($valor, $achou)){
					$notAll = true;
				}
			}*/

			dump($palavrasA,'PALAVRAS: ');
			dump($achou, 'ACHOU: ');
			print "<br>TOTALE: ".$totalE."<BR>";
			unset($qryChkOco);
			unset($qryChkAss);
			unset($qryChkSol);
			//dump($achou,'ACHOU NO TOTALE:');


		}

// 		if (count($achou) != count($palavrasA)) {
// 			$totalRegs = $linhas-$totalE; //TOTAL DE REGISTROS ABSOLUTOS MENOS A QDT DE REGISTROS ONDE NEM TODOS TERMOS FORAM ENCONTRADOS
// 		} else
// 			$totalRegs = $linhas;

		$totalRegs = $linhas-$totalE;

		unset($achou);

                $cor=TD_COLOR;
                $cor1=TD_COLOR;

                print "<td class='line'>";
                if ($totalRegs>1)
                        print "<TR><td class='line'><B>Foram encontradas ".$totalRegs." soluções possíveis com os critérios passados. </B></TD></TR>";
                else
		if ($totalRegs==1)
                        print "<TR><td class='line'><B>Foi encontrada somente 1 solução possível com o critério passado.</B></TD></TR>";
		else
		{
			print "Nenhuma solução localizada. (REMOVER ESSA LINHA!)";
			dump($query,"QUERY EXECUTADA:");
			exit;
			$aviso = "Nenhuma solução localizada com os critérios passados.";
			print "<script>mensagem('".$aviso."'); history.back();</script>";
		}

		print "</TD>";
		print "<br>".dump($query,"QUERY EXECUTADA");


		while ($row = mysql_fetch_array($resultado))
		{
/*
			for ($i=0; $i<count($palavrasA); $i++) {
				$qryChkOco[$i] = "SELECT * FROM ocorrencias WHERE numero = ".$row['numero']." AND ".
							"\n ( lower(descricao) like lower('%".$palavrasA[$i]."%') ".
							"\n OR lower(descricao) like lower('%".toHtml($palavrasA[$i])."%') )";
				$execChkOco[$i] = mysql_query($qryChkOco[$i]) or die ('ERRO NA CONSULTA!<br>'.dump($qryChkOco));
				if (mysql_numrows($execChkOco[$i])>0) {
					$achou[] = toHtml($palavrasA[$i]);
					$achou = array_unique($achou);
				}
				$qryChkAss[$i] = "SELECT * FROM assentamentos WHERE ocorrencia = ".$row['numero']." AND ".
							"\n (lower(assentamento) like lower('%".$palavrasA[$i]."%') ".
							"\n OR lower(assentamento) like lower('%".toHtml($palavrasA[$i])."%') )";
				$execChkAss[$i] = mysql_query($qryChkAss[$i])or die ('ERRO NA CONSULTA!<br>'.dump($qryChkAss));
				if (mysql_numrows($execChkAss[$i])>0) {
					$achou[] = toHtml($palavrasA[$i]);
					$achou = array_unique($achou);
				}
				$qryChkSol[$i] = "SELECT * FROM solucoes WHERE numero = ".$row['numero']." AND (".
							"\n lower(solucao) like lower('%".$palavrasA[$i]."%') OR ".
							"\n lower(problema) like lower('%".$palavrasA[$i]."%') OR ".
							"\n lower(solucao) like lower('%".toHtml($palavrasA[$i])."%') OR ".
							"\n lower(problema) like('%".toHtml($palavrasA[$i])."%') )";
				$execChkSol[$i] = mysql_query($qryChkSol[$i])or die ('ERRO NA CONSULTA!<br>'.dump($qryChkSol));
				if (mysql_numrows($execChkSol[$i])>0) {
					$achou[] = toHtml($palavrasA[$i]);
					$achou = array_unique($achou);
				}
			}
			*/


			for ($i=0; $i<count($palavrasA); $i++) {
				$qryChkOco[$i] = "SELECT * FROM ocorrencias WHERE numero = ".$row['numero']." AND ".
							"\n ( lower(descricao) like lower('%".$palavrasA[$i]."%') ".
							"\n OR lower(descricao) like lower('%".toHtml($palavrasA[$i])."%') )";
				$execChkOco[$i] = mysql_query($qryChkOco[$i]) or die ('ERRO NA CONSULTA!<br>'.dump($qryChkOco));
				if (mysql_numrows($execChkOco[$i])>0) {
					$achou[] = toHtml($palavrasA[$i]);
					$achou = array_unique($achou);
				}
				$qryChkAss[$i] = "SELECT * FROM assentamentos WHERE ocorrencia = ".$row['numero']." AND ".
							"\n (lower(assentamento) like lower('%".$palavrasA[$i]."%') ".
							"\n OR lower(assentamento) like lower('%".toHtml($palavrasA[$i])."%') )";
				$execChkAss[$i] = mysql_query($qryChkAss[$i])or die ('ERRO NA CONSULTA!<br>'.dump($qryChkAss));
				if (mysql_numrows($execChkAss[$i])>0) {
					$achou[] = toHtml($palavrasA[$i]);
					$achou = array_unique($achou);
				}
				$qryChkSol[$i] = "SELECT * FROM solucoes WHERE numero = ".$row['numero']." AND (".
							"\n lower(solucao) like lower('%".$palavrasA[$i]."%') OR ".
							"\n lower(problema) like lower('%".$palavrasA[$i]."%') OR ".
							"\n lower(solucao) like lower('%".toHtml($palavrasA[$i])."%') OR ".
							"\n lower(problema) like('%".toHtml($palavrasA[$i])."%') )";
				$execChkSol[$i] = mysql_query($qryChkSol[$i])or die ('ERRO NA CONSULTA!<br>'.dump($qryChkSol));
				if (mysql_numrows($execChkSol[$i])>0) {
					$achou[] = toHtml($palavrasA[$i]);
					$achou = array_unique($achou);
				}
			}





			//$achou = array_unique($achou);
			reIndexArray($achou);

			//if ((!isset($_POST['anyword']) && count($achou) >= count($termos)) || (isset($_POST['anyword']))) {
			if ((!isset($_POST['anyword']) && count($achou) >= count($termos)) || (isset($_POST['anyword']))) {

				print "<TABLE border='1' style=\"{border-collapse:collapse;}\" align='center' width='100%'>";
				print "<tr>";
					print "<TD align='left' bgcolor='".TD_COLOR."'>Número:</TD>";
					print "<TD align='left'><a onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."&destaca=".$destacaProb."')\">".
							"<font color='blue'>".$row['numero']."</font></a></TD>";
					print "<TD align='left'>Data:</TD>";
					print "<TD align='left'>".datab($row['data'])."</TD>";
					print "<TD align='left'>Operador:</TD>";
					print "<TD align='left'>".$row['nome']."</TD>";
				print "</TR>";
				print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Problema:</TD>";
					print "<TD colspan='5' width='80%' align='left'>".destaca($destacaProb, nl2br($row['problema']))."</TD>";
				print "</TR>";

				print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Solução:</TD>";
					print "<TD colspan='5' width='80%' align='left'>".destaca($destacaProb, nl2br($row['solucao']))."</TD>";
				print "</TR>";

				print "<HR>";

				print "</TABLE>";
			}
			//ZERANDO O ARRAY ACHOU
			for ($j=0; $j<count($achou); $j++){
				array_pop($achou);
			}

		}//while


		dump($linhas,"LINHAS:");
		print "<br>";
		dump($totalE,"TOTALe:");
		print "<br>";
		dump($achou,"VARIÁVEL ACHOU:");
		print "<br>";
		dump($palavrasA,"VARIÁVEL PALAVRAS:");
		print "<br>";
		dump($_REQUEST,"TODAS VARIÁVEIS:");

?>
<script type='text/javascript'>

	function popup_alerta(pagina)	{ //Exibe uma janela popUP
      		x = window.open(pagina,'_blank','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false
     	}

</script>
<?
print "</body>";
print "</html>";
?>
