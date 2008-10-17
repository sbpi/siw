<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getProtocolo
*
* { Description :- 
*    Recupera as solicitações desejadas
* }
*/

class db_getProtocolo {
   function getInstanceOf($dbms, $p_menu, $p_pessoa, $p_restricao, $p_chave, $p_chave_aux, $p_prefixo, 
        $p_numero, $p_ano, $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia, $p_ini, $p_fim, 
        $p_tipo, $p_despacho) {

     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getProtocolo';
     $params=array('p_menu'                 =>array($p_menu,                                    B_INTEGER,        32),
                   'p_pessoa'               =>array($p_pessoa,                                  B_INTEGER,        32),
                   'p_restricao'            =>array($p_restricao,                               B_VARCHAR,        20),
                   'p_chave'                =>array(tvl($p_chave),                              B_INTEGER,        32),
                   'p_chave_aux'            =>array(tvl($p_chave_aux),                          B_INTEGER,        32),
                   'p_prefixo'              =>array(tvl($p_prefixo),                            B_INTEGER,        32),
                   'p_numero'               =>array(tvl($p_numero),                             B_INTEGER,        32),
                   'p_ano'                  =>array(tvl($p_ano),                                B_INTEGER,        32),
                   'p_unid_autua'           =>array(tvl($p_unid_autua),                         B_INTEGER,        32),
                   'p_unid_posse'           =>array(tvl($p_unid_posse),                         B_INTEGER,        32),
                   'p_nu_guia'              =>array(tvl($p_nu_guia),                            B_INTEGER,        32),
                   'p_ano_guia'             =>array(tvl($p_ano_guia),                           B_INTEGER,        32),
                   'p_ini'                  =>array(tvl($p_ini),                                B_DATE,           32),
                   'p_fim'                  =>array(tvl($p_fim),                                B_DATE,           32),
                   'p_tipo'                 =>array($p_tipo,                                    B_INTEGER,        32),
                   'p_despacho'             =>array($p_despacho,                                B_INTEGER,        32),
                   'p_result'               =>array(null,                                       B_CURSOR,         -1)
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
