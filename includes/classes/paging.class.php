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
 * Paging - PHP-MySQL paging results class
 * @package Ocomon
 * @author Flávio Ribeiro
 * @copyright 2007 - Flávio Ribeiro
 */


class paging
{
	////////////////////////////////////////////////
	// PUBLIC VARIABLES
	///////////////////////////////////////////////

	/**
	* Number of register per page ( default = 10).
	* @var int
	*/
	var $regPerPage = 10;

	/**
	* Query for each page shown.
	* @var string
	*/
	var $SQL;

	/**
	* Query for all registers.
	* @var string
	*/
	var $SQLFull;

	var $CONDITION;

	/**
	* Number of all registers
	* @var int
	*/
	var $NUMBER_REGS;

	/**
	* Number of registers for the current page
	* @var int
	*/
	var $NUMBER_REGS_PAGE;

	/**
	* Number of pages to mount
	* @var int
	*/
	var $NUMBER_PAGES;

	/**
	* Number of the current page
	* @var string
	*/
	var $PAGE = 1;

	/**
	* Number of the first register of the current page
	* @var int
	*/
	var $LIMIT=0;

	/**
	* Result of mysql_query for the current page
	* @var string
	*/
	var $RESULT_SQL;

	/**
	* Result of mysql_query for the full registers
	* @var string
	*/
	var $RESULT_FULL;

	/**
	* String of $_GETs for each page
	* @var string
	*/
	var $GETS ="";

	/**
	* Array of $_POSTs for each page
	* @var array
	*/
	var $POSTS = array();

	var $POST = "";

	var $ALL;

	var $FULL = 0;

	var $BRACKETS_OPEN = "[";

	var $BRACKETS_CLOSE = "]";

	var $SEPARATOR = "";

	var $PAGE_ID;

	/////////////////////////////////////////////
	//  METHODS
	////////////////////////////////////////////


	function paging($ID=""){ //Constructor
		if ($ID == "") {
			$this->PAGE_ID = random();
		} else {
			$this->PAGE_ID = $ID;
		}
		$this->getRequestsGets();
	}



	/**
	* Sets numbers of registers per page
	* @param int $regPerPage
	* @return void
	*/
	function setRegPerPage($regPerPage){
		$this->regPerPage = $regPerPage;
	}

	/**
	* Increase the number of first page register
	* acording to the number of register per pages
	* @param int $regPerPage
	* @return void
	*/
	function increaseLIMIT()
	{
		$this->LIMIT =($this->LIMIT + $this->regPerPage);
	}

	function setLimit($LIMIT=0){
		$this->LIMIT = $LIMIT;
	}

	function getPage(){
		$this->PAGE = (($this->LIMIT / $this->regPerPage)+1);
		return $this->PAGE;
	}


	function setSQL($SQL, $FULL=0)
	{
		$this->SQLFull = $SQL;
		if ($FULL == 0) {
			$this->SQL = $SQL." LIMIT ".$this->LIMIT.", ".$this->regPerPage;
		} else {
				$this->SQL = $this->SQLFull; //To show all registers in an only one page
				$this->FULL = $FULL;
			}
	}

	function execSQL() {
		$this->RESULT_FULL =	mysql_query($this->SQLFull);
		$this->RESULT_SQL = mysql_query($this->SQL);
		$this->setNumberOfRegs();
		$this->getPage();
	}

	function getSQL(){
		print $this->SQL."<br>";
	}


	function random (){
		$rand ="";
		for ($i=0; $i<10; $i++) {
			$rand.= mt_rand(1,300);
		}
		//$this->PAGE_ID = $rand;
		return ($rand);
	}


	function setNumberOfRegs(){
		$this->NUMBER_REGS = mysql_num_rows($this->RESULT_FULL);
		$this->NUMBER_REGS_PAGE = mysql_num_rows($this->RESULT_SQL);
	}

	function getNumberOfPages (){
		$this->NUMBER_PAGES = ($this->NUMBER_REGS / $this->regPerPage);
		return $this->NUMBER_PAGES;
	}


	function getRequestsGets(){
		foreach ($_GET as $id =>$valor) {
			if ($this->GETS!="")
				$this->GETS.="&";
			if ($id != "LIMIT" && $id != "FULL" && $id != "PAGE_ID")
				$this->GETS.=$id."=".$valor;
		}
	}

	function setPost($post){
		//if ($post!="")
			//$this->POST = $post;
		foreach ($post as $id => $valor) {
			$this->POST .= $id."|".$valor;
		}
		if ($this->POST != "") {
			$this->POST = "POST=".$this->POST;
		}
		if ($this->POST == "POST=") {
			$this->POST = "";
		}
	}

	function getPosts($GET) {
		if ($GET != "") {
			if (strpos($GET,"POST=")){
				$TMP = explode ("POST=",$post);
				//$TMP2 = $TMP[1]; //FULL STRING LESS THE "POST=" PART
				//$GET = str_replace ("|", "=", $TMP[1]);
				$this->POST = str_replace ("|", "=", $TMP[1]);
			}
		}
	}

	function getRequestsPosts(){
		foreach ($_POST as $id =>$valor) {
			//$_POST['id'] = $valor;
			$this->POSTS[$id] = $valor;
		}
		/*foreach ($this->POSTS as $id =>$valor) {
			//$_POST['id'] = $valor;
			$_POST[$id] = $valor;
		}*/
	}

	function setSeparator($sep){
		$this->SEPARATOR = $sep;
	}

	function setBrackets($open, $close){
		$this->BRACKETS_OPEN = $open;
		$this->BRACKETS_CLOSE = $close;
	}

	function linkShowAll(){
		$cssClass = "normal";
		if ($this->FULL == 1)
			$cssClass = "page";
		print "\n<a class='".$cssClass."' href='".$_SERVER['PHP_SELF']."?LIMIT=0&".$this->GETS."&FULL=1'>".$this->BRACKETS_OPEN."ALL".$this->BRACKETS_CLOSE."</a>";
	}


	function ShowOutputPages(){
		//$this->getRequestsGets(); //mudei para o construct
		$this->setLimit(0);
		$cssClass = "normal";
		if ($this->getNumberOfPages() > 1) { //Only show the pages if there are more than 1 page to be shown
			print "Páginas >>";
			if ($this->FULL ==0) { //Registers separated by pages
				for ($i = 0; $i < $this->getNumberOfPages(); $i++) {
					$page = $i+1;
					($this->PAGE == $page)?$cssClass ="page":$cssClass="normal";
					print "\n<a class='".$cssClass."' href='".$_SERVER['PHP_SELF']."?LIMIT=".$this->LIMIT."&".$this->GETS."&FULL=0'>".$this->BRACKETS_OPEN.$page.$this->BRACKETS_CLOSE."</a>\n".$this->SEPARATOR; //"&".$this->POST.
					//print "\n<a class='".$cssClass."' href='".$_SERVER['PHP_SELF']."?LIMIT=".$this->LIMIT."&".$this->GETS."&FULL=0&PAGE_ID=".$this->PAGE_ID."'>".$this->BRACKETS_OPEN.$page.$this->BRACKETS_CLOSE."</a>\n".$this->SEPARATOR; //"&".$this->POST.
					$this->increaseLIMIT();
				}
			} else {//Print all registers in only one page
				print "\n<a class='".$cssClass."' href='".$_SERVER['PHP_SELF']."?LIMIT=0&".$this->GETS."&FULL=0'>".$this->BRACKETS_OPEN."PAGES".$this->BRACKETS_CLOSE."</a>\n".$this->SEPARATOR;
			}
			$this->linkShowAll();
		}
	}

}
	// Style of selected page link
	print "<style type='text/css'><!--";
		print ".page
		{
			font-family: tahoma;
			font-size:13px;
			font-weight: bold;
		}"; //background-color: #F1F1F1; border: 1px solid #a4a4a4;
		print ".normal:hover
		{
			font-family: tahoma;
			background-color: #a4a4a4;
		}"; //font-size:13px;
	print "--></STYLE>";

	////////////////////////////////////////////////
	// USE EXAMPLE
	///////////////////////////////////////////////

	/*include ("path/to/paging.class.php");
	$PAGER = new paging;
	$PAGER->setRegPerPage(10);

	$query = "SELECT * FROM TABLE ORDER BY FIELD";

	if (isset($_GET['LIMIT']))
		$PAGER->setLimit($_GET['LIMIT']);
	$PAGER->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));
	$PAGER->execSQL();

	while ($row=mysql_fetch_array($PAGER->RESULT_SQL)){
		//Print rows
	}

	$PAGE->showOutputPages();
	*/

	//Other useful returns:
	//$PAGER->NUMBER_REGS : Returns the number of total pages registers
	//$PAGER->PAGE : Retuns the number of current page
	//$PAGER->NUMBER_REGS_PAGE : Retuns the number of current page registers
?>