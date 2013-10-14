<?php 
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
  */
/**
 * Lock Class
 * @package Ocomon
 * @author Flávio Ribeiro
 * @copyright 2007 - Flávio Ribeiro
 */


class lock
{
	////////////////////////////////////////////////
	// PUBLIC VARIABLES
	///////////////////////////////////////////////

	/**
	* ID of the user editing the ticket
	* @var int
	*/
	var $UID;
	var $OCO = 0;
	var $U_NAME;
	var $SCRIPT = "";
	var $FORCE_EDIT = 0;


	/////////////////////////////////////////////
	//  METHODS
	////////////////////////////////////////////


	function setLock ($OCO, $UID, $FORCE_EDIT = 0){ //Constructor

		$this->OCO = $OCO;
		$this->UID = $UID;
		$this->FORCE_EDIT = $FORCE_EDIT;

		if ($this->OCO != ""){

			$chk_lock = "SELECT * FROM lock_oco WHERE lck_oco = ".$this->OCO."";
			$exec_chk = mysql_query($chk_lock) or die ("ERRO NA TENTATIVA DE ACESSAR AS INFORMAÇÕES DE LOCK!<br>".$chk_lock);
			$row = mysql_fetch_array($exec_chk);
			$achou = mysql_num_rows($exec_chk);

			if ($achou != 0) {
				if (($row['lck_uid'] == $this->UID) || ($this->FORCE_EDIT == 1)) {
					$this->unlock($this->OCO);
					$SQL = "INSERT INTO lock_oco (lck_uid, lck_oco) VALUES (".$this->UID.", ".$this->OCO.")";
					$EXEC = mysql_query($SQL) or die ('ERRO NA TENTATIVA DE CRIAR PONTO DE LOCK!');
				} else {
					$SQL = "SELECT nome FROM usuarios u, lock_oco l WHERE l.lck_uid = u.user_id and u.user_id = ".$row['lck_uid']." ";
					$EXEC = mysql_query($SQL);
					$row_nome = mysql_fetch_array($EXEC);
					print "<br>Essa Ocorrência já está em edição nesse instante pelo usuário <b><font color='red'>".$row_nome['nome']."</font></b>. Não pode ser editada! <br><a onclick='javascript:history.back()'>Voltar</a><br>";
					print "<a href='".$_SERVER['PHP_SELF']."?numero=".$this->OCO."&FORCE_EDIT=1'>Editar assim mesmo</a>";
					exit;
				}
			} else {
				$SQL = "INSERT INTO lock_oco (lck_uid, lck_oco) VALUES (".$this->UID.", ".$this->OCO.")";
				$EXEC = mysql_query($SQL) or die ('ERRO NA TENTATIVA DE CRIAR PONTO DE LOCK!');
			}

		} else {
			print "Numero vazio!!";
		}
	}


	function unlock ($OCO = 0) {
		if ($OCO != 0){
			$this->OCO = $OCO;
		}
		if ($this->OCO != 0){
			$SQL = "DELETE FROM lock_oco WHERE lck_oco = ".$this->OCO." ";
			$EXEC = mysql_query($SQL) or die('ERRO NA TENTATIVA DE EXCLUIR REGISTRO DE LOCK!');
		}
	}

}
?>