<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getSolicAcesso
*
* { Description :- 
*    Recupera o n�vel de acesso que um usu�rio tem para uma solicita��o
* }
*/

class db_getSolicAcesso {
   function getInstanceOf($dbms, $p_solicitacao, $p_usuario) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql='FUNCTION'.$strschema.'ACESSO';
     $params=array('p_solicitacao'               =>array($p_solicitacao,                                   B_INTEGER,        32),
                   'p_usuario'                   =>array($p_usuario,                                       B_INTEGER,        32)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
     if(!$l_rs->executeQuery()) {
       error_reporting($l_error_reporting); 
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__);
     } else {
       error_reporting($l_error_reporting); 
        if ($l_rs = $l_rs->getResultArray()) {
         foreach($l_rs as $k => $v) return $v;
        } else {
          return 0;
        }
     }
   }
}
?>
