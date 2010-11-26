<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getCaixa
*
* { Description :- 
*    Recupera as caixas de arquivamento de documentos e processos
* }
*/

class db_getCaixa {
  function getInstanceOf($dbms, $p_chave,  $p_cliente ,$p_usuario, $p_unidade,$p_numero,$p_assunto,$p_unid_autua, $p_nu_guia, 
           $p_ano_guia, $p_ini, $p_fim, $p_restricao) {
   extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getCaixa';
   $params=array('p_chave'                =>array($p_chave,                                  B_INTEGER,        32),
                 'p_cliente'              =>array($p_cliente,                                B_INTEGER,        32),
                 'p_usuario'              =>array($p_usuario,                                B_INTEGER,        32),
                 'p_unidade'              =>array($p_unidade,                                B_INTEGER,        32),
                 'p_numero'               =>array($p_numero,                                 B_INTEGER,        32),
                 'p_assunto'              =>array(tvl($p_assunto),                           B_VARCHAR,       500),
                 'p_unid_autua'           =>array(tvl($p_unid_autua),                        B_INTEGER,        32),
                 'p_nu_guia'              =>array(tvl($p_nu_guia),                           B_INTEGER,        32),
                 'p_ano_guia'             =>array(tvl($p_ano_guia),                          B_INTEGER,        32),
                 'p_ini'                  =>array(tvl($p_ini),                               B_DATE,           32),
                 'p_fim'                  =>array(tvl($p_fim),                               B_DATE,           32),
                 'p_restricao'            =>array($p_restricao,                              B_VARCHAR,        20),
                 'p_result'               =>array(null,                                      B_CURSOR,         -1)
                );

   $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
   $l_error_reporting = error_reporting(); 
   error_reporting(0); 
   if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__);
   } else {
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