<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_SgPesMen
*
* { Description :- 
*    Manipula registros de SG_PESSOA_MENU
* }
*/

class dml_SgPesMen {
   function getInstanceOf($dbms, $operacao, $p_pessoa, $p_menu, $p_endereco) {
     $sql='sp_putSgPesMen';
     $params=array('operacao'       =>array($operacao,      B_VARCHAR,      1),
                   'p_pessoa'       =>array($p_pessoa,      B_NUMERIC,     32),
                   'p_menu'         =>array($p_menu,        B_NUMERIC,     32),
                   'p_endereco'     =>array($p_endereco,    B_NUMERIC,     32)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
