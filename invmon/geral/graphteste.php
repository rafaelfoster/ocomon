<?php 

	
	
	
	function graph($tabwidth,$tabheight, $altura, $altura2){
		return "
		
		<table width=$tabwidth height=$tabheight border=1>
			<tr valign=bottom>
			<td class='line'><img src=./png/blue.png  border=1 width=30 height=$altura> <img src=./png/red.png border=1 width=30 height=$altura2></td>
	
		</table>
		
		";
	
	}

	print graph(200, 400,70,140);
	
	
?>