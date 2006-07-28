<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putRespTarefa_IS
*
* { Description :- 
*    Atualiza os responsaveis da tarefa e seus dados
* }
*/

class dml_putRespTarefa_IS {
   function getInstanceOf($dbms, $p_chave, $p_nm_responsavel, $p_fn_responsavel, $p_em_responsavel) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTRESPTAREFA_IS';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_nm_responsavel'            =>array(tvl($p_nm_responsavel),                           B_VARCHAR,        60),
                   'p_fn_responsavel'            =>array(tvl($p_fn_responsavel),                           B_VARCHAR,        20),
                   'p_em_responsavel'            =>array(tvl($p_em_responsavel),                           B_VARCHAR,        60)
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
