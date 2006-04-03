<?
/*
arr_multisort 1.0
Copyright: Left
---------------------------------------------------------------------------------
Version:        1.0
Date:           22 April 2004
---------------------------------------------------------------------------------
Authors:        Alexander Minkovsky (a_minkovsky@hotmail.com)
                Peter Panteleev (peter_pantaleev@mailbg.com)
---------------------------------------------------------------------------------
License:        Choose the more appropriated for You - I don't care.
---------------------------------------------------------------------------------
Description:
Class makes multicolumn sorting of associative arrays in format provided for example by mysql_fetch_assoc.
Column names and sort order to be used can be specified as well the sorting direction for each column.
Dates are sorted correctly if they comply with GNU date syntax
(http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html)
---------------------------------------------------------------------------------
Example usage: see example.php
---------------------------------------------------------------------------------
*/

define("SRT_ASC",1);
define("SRT_DESC",-1);

Class arr_multisort{

  //The array to be sorted
  var $arr = NULL;
  //Single dimensioned array with column names. Ex. array("UserName","Sex","Country")
  var $colNames = NULL;
  /*
  Single dimensioned array with sort directions. Ex. array(SRT_ASC,SRT_ASC,SRT_DESC)
  Must have the same length as $colNames array
  */
  var $colDirs = NULL;

  //Constructor
  function arr_multisort(&$arr,$colNames=array(),$colDirs=array()){
    $this->arr = $arr;
    $this->colNames = $colNames;
    $this->colDirs = $colDirs;
  }

  //sort() method
  function &sort(){
    usort($this->arr,array($this,"_compare"));
    return $this->arr;
  }

  //Comparison function [PRIVATE]
  function _compare($a,$b,$idx = 0){
    if(count($this->colNames) == 0 || count($this->colNames) != count($this->colDirs)) return 0;
    $a_cmp = $a[$this->colNames[$idx]];
    $b_cmp = $b[$this->colNames[$idx]];
    $a_dt = strtotime($a_cmp);
    $b_dt = strtotime($b_cmp);
    if(($a_dt == -1) || ($b_dt == -1))
      $ret = $this->colDirs[$idx]*strnatcasecmp($a_cmp,$b_cmp);
    else{
      $ret = $this->colDirs[$idx]*(($a_dt > $b_dt)?1:(($a_dt < $b_dt)?-1:0));
    }
    if($ret == 0){
      if($idx < (count($this->colNames)-1))
        return $this->_compare($a,$b,$idx+1);
      else
        return $ret;
    }
    else
      return $ret;
  }

}

?>
