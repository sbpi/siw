<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_SiwMenEnd
*
* { Description :- 
*    Manipula registros de SIW_MENU_ENDERECO
* }
*/

class dml_SiwMenEnd {
   function getInstanceOf($dbms, $operacao, $p_menu, $p_endereco) {
     $sql=$strschema.'sp_putSiwMenEnd';
     $params=array('operacao'       =>array($operacao,      B_VARCHAR,      1),
                   'p_menu'         =>array($p_menu,        B_NUMERIC,     32),
                   'p_endereco'     =>array($p_endereco,    B_NUMERIC,     32)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
     if(!$l_rs->executeQuery()) { 
       error_reporting($l_error_reporting); 
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); 
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
