<?
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getUorgList
*
* { Description :- 
*    Retorna as opções do menu concedidas ao usuário indicado.
* }
*/

class db_getUserList {
   function getInstanceOf($dbms, $p_cliente, $p_localizacao, $p_lotacao, $p_gestor, $p_nome, $p_modulo, $p_uf, $p_ativo, $p_contratado) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getUserList';
     $params=array("p_cliente"      =>array($p_cliente,     B_NUMERIC,     32),
                   "p_localizacao"  =>array($p_localizacao, B_NUMERIC,     32),
                   "p_lotacao"      =>array($p_lotacao,     B_NUMERIC,     32),
                   "p_gestor"       =>array($p_gestor,      B_VARCHAR,     1),
                   "p_nome"         =>array($p_nome,        B_VARCHAR,     60),
                   "p_modulo"       =>array($p_modulo,      B_NUMERIC,     32),
                   "p_uf"           =>array($p_uf,          B_VARCHAR,     2),
                   "p_ativo"        =>array($p_ativo,       B_VARCHAR,     1),
                   "p_contratado"   =>array($p_contratado,  B_VARCHAR,     1),
                   "p_result"       =>array(null,           B_CURSOR,      -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0); if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
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
