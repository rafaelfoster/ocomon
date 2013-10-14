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


class conexao {
	var $con;
	var $db;

	var $LDAP_HOST;
	var $LDAP_DOMAIN;
	var $LDAP_DN;
	var $LDAP_PASSWORD;
	var $DS;
	var $BIND;
	var $U_BIND;
	var $Upass; //senha digitada pelo usuario
	var $LDAP_INFO;
	var $UID;
	var $U_UID;
	var $U_DN;
	var $U_NAME;
	var $U_PASSWD;
	var $U_SALT;
	var $SEARCH;
	var $TOTAL;
	var $ERROR;
	//var $PREFIX;





	function conecta($BANCO){
		if (strtoupper($BANCO) =='MYSQL') {

		$this->con=mysql_connect(SQL_SERVER,SQL_USER,SQL_PASSWD)or die(mysql_error());
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
 //-----------------------------------------------------------------------------------//

	function set_ldapHost($HOST){
		$this->LDAP_HOST = $HOST;
	}

	function set_ldapDomain($DOMAIN){
		$this->LDAP_DOMAIN = $DOMAIN;
	}

	function set_ldapDN($DN){
		$this->LDAP_DN = $DN;
	}

	function set_ldapPasswd($PASSWD){
		$this->LDAP_PASSWORD= $PASSWD;
	}

	function set_ldapUID ($USER){
		$this->UID = $USER;
	}
	function set_Upass ($senha){
		$this->Upass = $senha;
	}


	function set_prefix($prefix = "{crypt}"){
		$this->PREFIX = $prefix;
	}


	function get_ldapHost(){
		print $this->LDAP_HOST;
	}

	function get_ldapDomain(){
		print $this->LDAP_DOMAIN;
	}
	function get_ldapDN(){
		print $this->LDAP_DN;
	}
	function get_ldapPasswd(){
		print $this->LDAP_PASSWORD;
	}




	function conLDAP ($host,$domain,$dn,$passwd){

		$this->set_ldapHost($host);
		$this->set_ldapDomain($domain);
		$this->set_ldapDN($dn);
		$this->set_ldapPasswd($passwd);
		$this->DS = ldap_connect($this->LDAP_HOST);
		if ($this->DS) {
			$this->BIND = @ldap_bind($this->DS, $this->LDAP_DN, $this->LDAP_PASSWORD);
			return true;
		} else {
			$this->ERROR = "Não foi possível conectar ao servidor LDAP!";
			print $this->ERROR;
			return false;
		}
	}


	function userLdap($uid,$pass){//Tem que estar conectado ao servidor LDAP

		$this->set_ldapUID($uid);
		$this->set_Upass($pass);
		$this->SEARCH = ldap_search($this->DS, $this->LDAP_DOMAIN, "uid=".$this->UID."");
		$this->TOTAL = ldap_count_entries ($this->DS,$this->SEARCH);
		$this->LDAP_INFO = ldap_get_entries($this->DS, $this->SEARCH);

		$this->U_NAME = $this->LDAP_INFO[0]["cn"][0];
		$this->U_UID = $this->LDAP_INFO[0]["uid"][0];
		$this->U_DN = $this->LDAP_INFO[0]["dn"];
		$this->U_PASSWD = $this->LDAP_INFO[0]["userpassword"][0];

 		$this->U_BIND =@ldap_bind($this->DS, $this->U_DN, $this->Upass);

		if ($this->U_BIND && $this->U_PASSWD && !empty($pass)) {
		    return true;
		} else
			return false;
	}


	function desconLDAP (){
		ldap_close ($this->DS);
	}

}



?>