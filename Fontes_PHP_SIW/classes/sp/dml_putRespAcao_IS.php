<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putRespAcao_IS
*
* { Description :- 
*    Atualiza os responsaveis e seus dados na Ação do PPA, Açao e Iniciativa
* }
*/

class dml_putRespAcao_IS {
   function getInstanceOf($dbms, $p_chave, $p_responsavel, $p_telefone, $p_email, $p_tipo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTRESPACAO_IS';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_responsavel'               =>array(tvl($p_responsavel),                              B_VARCHAR,        60),
                   'p_telefone'                  =>array(tvl($p_telefone),                                 B_VARCHAR,        20),
                   'p_email'                     =>array(tvl($p_email),                                    B_VARCHAR,        60),
                   'p_tipo'                      =>array(tvl($p_tipo),                                     B_INTEGER,        32)
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
