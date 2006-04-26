<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCodigoExterno
*
* { Description :- 
*    Manipula registros para integração
* }
*/

class dml_putCodigoExterno {
   function getInstanceOf($dbms, $p_cliente, $p_restricao, $p_chave, $p_chave_externa, $p_chave_aux) {
     $sql='sp_putCodigoExterno';
     $params=array('cliente'                =>array($cliente,           B_NUMERIC,     32),
                   'p_restricao'            =>array($p_restricao,       B_VARCHAR,     20),
                   'p_chave'                =>array($p_chave,           B_VARCHAR,    255),
                   'p_chave_externa'        =>array($p_chave_externa,   B_VARCHAR,    255),
                   'p_chave_aux'            =>array($p_chave_aux,       B_VARCHAR,    255)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
