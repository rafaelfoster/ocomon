<?
    print "<html><head><title>Indicadores de SLA</title>";

	print "<style type=\"text/css\"><!--";
	print "body.corpo {background-color:#F6F6F6;}";
	print "p{font-size:12px; text-align:center;}";
	print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:left;
			border: 0px; border-spacing:1 ;background-color:#f6f6f6; padding-top:10px; }";
	print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";
	print "--></STYLE>";
print "</head><body class='corpo'>";



    if ($_GET['sla']=='r') {
        print "<p>SLA - Tempo de resposta: baseado no setor de origem do chamado.</p>";
        print "<table class='pop'>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/sla1.png'></td><td class='line'>Indica que o chamado ainda não teve resposta mas está dentro do limite de tempo estipulado para o primeiro atendimento;</td></tr>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/sla2.png'></td><td class='line'>Indica que o chamado ainda não teve resposta e o tempo decorrido desde sua abertura está até 20% acima do estipulado para o primeiro atendimento;</td></tr>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/sla3.png'></td><td class='line'>Indica que o chamado ainda não teve resposta e já ultrapassou 20% além do tempo máximo definido para resposta;</td></tr>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/checked.png'></td><td class='line'>Indica que o chamado já teve um primeiro atendimento.</td></tr>";
        print "</table>";
    } else {
        print "<p>SLA - Tempo de solucão: baseado no tipo de problema do chamado.</p>";
        print "<table class='pop'>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/sla1.png'></td><td class='line'>Indica que o chamado ainda não foi concluído mas está dentro do prazo estipulado para sua solução;</td></tr>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/sla2.png'></td><td class='line'>Indica que o chamado ainda não foi concluído e o tempo decorrido deste a sua abertura está até 20% acima do limite máximo estipulado para sua solução;</td></tr>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/sla3.png'></td><td class='line'>Indica que o chamado já ultrapassou 20% além do tempo máximo estipulado para solução desse tipo de problema;</td></tr>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/checked.png'></td><td class='line'>Indica que ainda não foi definido o tempo de solução limite para esse tipo de problema.</td></tr>";
        print "</table>";

    }
    print "</body></html>";

?>