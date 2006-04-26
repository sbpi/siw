<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putSgTraPes
*
* { Description :- 
*    Manipula registros de SG_TRAMITE_PESSOA
* }
*/

class dml_putSgTraPes {
   function getInstanceOf($dbms, $operacao, $p_pessoa, $p_tramite, $p_endereco) {
     $sql='sp_putSgTraPes';
     $params=array('operacao'       =>array($operacao,      B_VARCHAR,      1),
                   'p_pessoa'       =>array($p_pessoa,      B_NUMERIC,     32),
                   'p_tramite'      =>array($p_tramite,     B_NUMERIC,     32),
                   'p_endereco'     =>array($p_endereco,    B_NUMERIC,     32)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
