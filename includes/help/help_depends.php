<?
    print "<html><head><title>Dependências de Ocorrências</title>";

	print "<style type=\"text/css\"><!--";
	print "body.corpo {background-color:#F6F6F6;}";
	print "p{font-size:12px; text-align:center;}";
	print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:left;
			border: 0px; border-spacing:1 ;background-color:#f6f6f6; padding-top:10px; }";
	print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";
	print "--></STYLE>";
print "</head><body class='corpo'>";


        print "<p>Dependências de chamados</p>";
        print "<table class='pop'>";
        print "<tr class='linha'><td valign='top'><img height='16' width='16' src='../../includes/icons/view_tree_green.png'></td>".
        		"<td class='line'>Indica que o chamado possui vínculo com pelo menos outro chamado mas que não possui restrições ".
        			"para seu encerramento.".
        	"</td></tr>";
        print "<tr class='linha'><td valign='top'><img height='16' width='16' src='../../includes/icons/view_tree_red.png'></td>".
        		"<td class='line'>Indica que o chamado possui vínculo com pelo menos outro chamado e possui restrições para seu encerramento.".
        	"</td></tr>";
        print "<tr class='linha'><td valign='top'><img height='16' width='16' src='../../includes/icons/view_tree.png'></td>".
        		"<td class='line'>Indica que o chamado possui algum tipo de vínculo com pelo menos outro chamado.</td></tr>";
        print "</table>";


    print "</body></html>";

?>