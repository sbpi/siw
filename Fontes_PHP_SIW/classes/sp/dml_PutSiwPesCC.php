<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_PutSiwPesCC
*
* { Description :- 
*    Manipula registros de SIW_PESSOA_CC
* }
*/

class dml_PutSiwPesCC {
   function getInstanceOf($dbms, $operacao, $chave, $sq_menu, $sq_cc) {
     $sql='sp_putSiwPesCC';
     $params=array('operacao'           =>array($operacao,          B_VARCHAR,      1),
                   'chave'              =>array($chave,             B_NUMERIC,     32),
                   'sq_menu'            =>array($sq_menu,           B_NUMERIC,     32),
                   'sq_cc'              =>array($sq_cc,             B_NUMERIC,     32)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
