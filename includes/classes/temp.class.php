<?php

class queries {

	var $commit;
	var $linhas;
	var $select;
	var $row;
	var $k;
	var $campo;
	var $campos;
	var $sep;
	
	function executa ($query){
		$this->commit=mysql_query($query);
		$this->linhas=mysql_num_rows($this->commit);
	}
	
	# @$query = O SQL que será executado;
	# @$name = O nome do SELECT no HTML;
	# @$default = Valor default do SELECT;
	# @$selected = Texto padrao selecionado;
	# @$value = Campo da tabela que irá conter o valor da seleção;
	# @$saida = Campo que aparecerá no SELECT;
	# @$class = Nome da classe CSS que o select terá;
	
	function monta_cmb ($query,$name, $default, $selected, $value, $saida, $class){
		$this->executa($query);
		
		/*
		$this->k = 0;
		while($this->k < mysql_num_fields($this->commit)){
			$this->campo = mysql_fetch_field($this->commit,$this->k);
			$this->campos.=$this->campo->name.",";
			$this->k++;
		} // while
		$this->campos = substr($this->campos,0,-1);
		$this->sep = explode(",",$this->campos);
		*/
		
		for ($this->k=0; count($saida); $this->k++){
			tt = $saida[$this->k];
		}
		
		
		$this->select="<select name=".$name." class=".$class.">";	
		$this->select.="<option value=".$default.">".$selected."</option>";
		while($this->row=mysql_fetch_array($this->commit)){
			$this->select.="<option value=".$this->row[$value].">".$this->row[$saida]."</option>";
			
		} // while	
		$this->select.="</select>";
		print $this->select;
	}

	
	function monta_cmb_edit (){
		//
	
	
	}


}

/*
			$k=0;
			while($k < mysql_num_fields($commit)){ //quantidade de campos retornados da consulta
				$field = mysql_fetch_field($commit,$k);//Retorna um objeto com informações dos campos
				$fields.=$field->name; //Joga os nomes dos campos para uma string
				$k++;
			} // while

*/


?>