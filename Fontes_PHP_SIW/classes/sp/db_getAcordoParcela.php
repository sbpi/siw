<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getAcordoParcela
*
* { Description :- 
*    Recupera a lista de acordos do cliente
* }
*/

class db_getAcordoParcela {
   function getInstanceOf($dbms, $p_chave, $p_chave_aux, $p_restricao, $p_outra_parte, $p_dt_ini, $p_dt_fim, $p_usuario, $p_fase, $p_menu, $p_sq_acordo_aditivo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETACORDOPARCELA';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        50),
                   'p_outra_parte'               =>array(tvl($p_outra_parte),                              B_VARCHAR,        60),
                   'p_dt_ini'                    =>array(tvl($p_dt_ini),                                   B_DATE,           32),
                   'p_dt_fim'                    =>array(tvl($p_dt_fim),                                   B_DATE,           32),
                   'p_usuario'                   =>array(tvl($p_usuario),                                  B_INTEGER,        32),
                   'p_fase'                      =>array(tvl($p_fase),                                     B_VARCHAR,        20),
                   'p_menu'                      =>array(tvl($p_menu),                                     B_INTEGER,        32),
                   'p_sq_acordo_aditivo'         =>array(tvl($p_sq_acordo_aditivo),                        B_INTEGER,        32),
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
