<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putSiwCliMod
*
* { Description :- 
*    Mantém a tabela de módulos do cliente.
* }
*/

class dml_putSiwCliMod {
   function getInstanceOf($dbms, $operacao, $p_modulo, $p_pessoa) {
     $sql='sp_putSiwCliMod';
     $params=array('operacao'           =>array($operacao,          B_VARCHAR,      1),
                   'p_modulo'           =>array($p_modulo,          B_NUMERIC,     32),
                   'p_pessoa'           =>array($p_pessoa,          B_NUMERIC,     32)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
