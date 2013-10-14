<?php 

class sql_query {
	
	var $query;
	var $commit;
	var $row;
	var $linhas;
	
	
	function setCommit($qry){
		$this->commit = mysql_query($qry);
	}
	
	function setQuery($qry){
		$this->query = $qry; 	
	}

	
	function del($tabela, $campo, $valor){
		$qry = "delete from ".$tabela." where ".$campo."= ".$valor."";	
		$this->setQuery($qry);
		$this->setCommit($qry);
	}

		

}

?>