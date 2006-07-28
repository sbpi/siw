<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putProgQualitativa_IS
*
* { Description :- 
*    Mantém a tabela IS_PROGRAMA do programa ou a tabela IS_ACAO da ação
* }
*/

class dml_putProgQualitativa_IS {
   function getInstanceOf($dbms, $p_chave, $p_resultados, $p_observacoes, $p_potencialidades, $p_problema, $p_objetivo, $p_publico_alvo, $p_estrategia, $p_sistematica, $p_metodologia, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTPROGQUALITATIVA_IS';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_resultados'                =>array(tvl($p_resultados),                               B_VARCHAR,      2000),
                   'p_observacoes'               =>array(tvl($p_observacoes),                              B_VARCHAR,      2000),
                   'p_potencialidades'           =>array(tvl($p_potencialidades),                          B_VARCHAR,      2000),
                   'p_problema'                  =>array(tvl($p_problema),                                 B_VARCHAR,      2000),
                   'p_objetivo'                  =>array(tvl($p_objetivo),                                 B_VARCHAR,      2000),
                   'p_publico_alvo'              =>array(tvl($p_publico_alvo),                             B_VARCHAR,      2000),
                   'p_estrategia'                =>array(tvl($p_estrategia),                               B_VARCHAR,      2000),
                   'p_sistematica'               =>array(tvl($p_sistematica),                              B_VARCHAR,      2000),
                   'p_metodologia'               =>array(tvl($p_metodologia),                              B_VARCHAR,      2000),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        30)
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
