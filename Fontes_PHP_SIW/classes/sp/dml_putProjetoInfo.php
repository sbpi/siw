<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putProjetoInfo
*
* { Description :- 
*    Mantém a tabela OR_ACAO do projeto
* }
*/

class dml_putProjetoInfo {
   function getInstanceOf($dbms, $p_chave, $p_descricao, $p_justificativa, $p_problema, $p_ds_acao, $p_publico_alvo, $p_estrategia, $p_indicadores, $p_objetivo) {
     $sql=$strschema.'SP_PUTPROJETOINFO';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,      2000),
                   'p_justificativa'             =>array(tvl($p_justificativa),                            B_VARCHAR,      2000),
                   'p_problema'                  =>array(tvl($p_problema),                                 B_VARCHAR,      2000),
                   'p_ds_acao'                   =>array(tvl($p_ds_acao),                                  B_VARCHAR,      2000),
                   'p_publico_alvo'              =>array(tvl($p_publico_alvo),                             B_VARCHAR,      2000),
                   'p_estrategia'                =>array(tvl($p_estrategia),                               B_VARCHAR,      2000),
                   'p_indicadores'               =>array(tvl($p_indicadores),                              B_VARCHAR,      2000),
                   'p_objetivo'                  =>array(tvl($p_objetivo),                                 B_VARCHAR,      2000)
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
