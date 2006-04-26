<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class dml_SgPerMen
*
* { Description :- 
*    Manipula registros de SG_PERFIL_MENU
* }
*/

class dml_SgPerMen {
   function getInstanceOf($dbms, $operacao, $p_perfil, $p_menu, $p_endereco) {
     $sql='sp_putSgPerMen';
     $params=array('operacao'       =>array($operacao,      B_VARCHAR,      1),
                   'p_perfil'       =>array($p_perfil,      B_NUMERIC,     32),
                   'p_menu'         =>array($p_menu,        B_NUMERIC,     32),
                   'p_endereco'     =>array($p_endereco,    B_NUMERIC,     32)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) return false;  else return true;
   }
}
?>
