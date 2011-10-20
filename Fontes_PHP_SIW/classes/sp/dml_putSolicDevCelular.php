<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putSolicDevCelular
*
* { Description :- 
*    Informa dados da entrega do aparelho ao beneficiário
* }
*/

class dml_putSolicDevCelular {
   function getInstanceOf($dbms, $p_menu, $p_chave, $p_pessoa, $p_fim, $p_pendencia, $p_acessorios, $p_bloqueio, $p_observacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putSolicDevCelular';
     $params=array('p_menu'                     =>array($p_menu,                                    B_INTEGER,        32),
                   'p_chave'                    =>array($p_chave,                                   B_INTEGER,        32),
                   'p_pessoa'                   =>array($p_pessoa,                                  B_INTEGER,        32),
                   'p_fim'                      =>array(tvl($p_fim),                                B_DATE,           32),
                   'p_pendencia'                =>array(tvl($p_pendencia),                          B_VARCHAR,         1),
                   'p_acessorios'               =>array(tvl($p_acessorios),                         B_VARCHAR,      1000),
                   'p_bloqueio'                 =>array(tvl($p_bloqueio),                           B_VARCHAR,         1),
                   'p_observacao'               =>array(tvl($p_observacao),                         B_VARCHAR,      1000)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
