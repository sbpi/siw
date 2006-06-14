<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getTTUsuario 
*
* { Description :- 
*    Recupera Eventos de Trigger
* }
*/

class db_getTTUsuario  {
   function getInstanceOf($dbms, $p_chave, $p_cliente, $p_usuario, $p_sq_central_fone, $p_codigo) {
     $sql=$strschema.'SP_GETTTUSUARIO';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        18),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        18),
                   'p_usuario'                   =>array(tvl($p_usuario),                                  B_INTEGER,        18),
                   'p_sq_centrap_fone'           =>array(tvl($p_sq_central_fone),                          B_INTEGER,        18),
                   'p_codigo'                    =>array(tvl($p_codigo),                                   B_VARCHAR,         4),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0);
     if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
     else {
       error_reporting($l_error_reporting); 
       if ($l_rs = $l_rs->getResultData()) {
         return $l_rs;
       } else {
         return array();
       }
     }
   }
}
?>
