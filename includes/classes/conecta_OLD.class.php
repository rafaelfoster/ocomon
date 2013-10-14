<?php



class conexao {
	var $con;
	var $db;
	
	function conecta($BANCO){
		if (strtoupper($BANCO) =='MYSQL') {
		
			$this->con=mysql_pconnect(SQL_SERVER,SQL_USER,SQL_PASSWD)or die(mysql_error());	
			$this->db=mysql_select_db(SQL_DB,$this->con);	
	        if ($this->con == 0){
	        	$retorno = "ERRO DE CONEXÃO - SERVIDOR!<br>";
	        }
	        else if ($this->db == 0){
	        	$retorno = "ERRO DE CONEXÃO - BANCO DE DADOS!<br>";
	        } else {
	            $retorno = "";
	        }
	        
			return  $retorno;		
		
		}

	}

	function desconecta($BANCO){
		    mysql_close($this->con);
	}
}


?>