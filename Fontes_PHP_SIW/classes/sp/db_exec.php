<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
include_once($w_dir_volta.'classes/db/DatabaseQueries.php');
/**
* class db_exec
*
* { Description :- 
*    Executa comandos SQL no banco de dados
* }
*/

class db_exec {
   function getInstanceOf($dbms, $p_sql, $params, $sp=null, &$numRows=null) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($p_sql, $dbms, null, $db_type=DB_TYPE);
     $l_error_reporting = error_reporting();  error_reporting(0); 
     if(!$l_rs->executeQuery()) { TrataErro($p_sql, $l_rs->getError(), $params, $sp, __LINE__, __CLASS__); 
     } else {
       error_reporting($l_error_reporting); 
       $numRows = $l_rs->getNumRows();
       if ($l_rs = $l_rs->getResultData()) {
         return $l_rs;
       } else {
         return array();
       }
     }
   }
   
   function normalize($params) {
     foreach($params as $paramName=>$value) {
       foreach($value as $k=>$v) {
         if ($v!='') {
           if($value[1]==B_VARCHAR) {
             if ($v!='') {
               // Trata aspas simples
               $v = str_replace("'","''",$v);
               // Limita tamanho máximo
               $v = substr($v,0,$value[2]);
               // Coloca aspas simples envolvendo a string
               $value[0] = "'$v'";
             }
           } else {
             $value[0] = $v;
           }
         } else {
           if (is_int($v)) $value[0] = $v; else $value[0] = 'null';
         }
         // Atualiza o array
         $params[$paramName][$k] = $value[0];
         break;
       }
     }

     $par = array();
     foreach($params as $k => $v) {
       foreach($v as $value => $resto) {
         $par[$k] = $resto;
         break;
       }
     }
     
     return $par;
  }
}
?>
