<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_SgPesMod
*
* { Description :- 
*    Grava permissões especiais de usuários a um módulo da SIW.
* }
*/

class dml_SgPesMod {
   function getInstanceOf($dbms, $operacao, $chave, $cliente, $sq_modulo, $sq_endereco) {
     $sql='sp_putSgPesMod';
     $params=array('operacao'           =>array($operacao,          B_VARCHAR,      1),
                   'chave'              =>array($chave,             B_NUMERIC,     32),
                   'cliente'            =>array($cliente,           B_NUMERIC,     32),
                   'sq_modulo'          =>array($sq_modulo,         B_NUMERIC,     32),
                   'sq_endereco'        =>array($sq_endereco,       B_NUMERIC,     32),
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
