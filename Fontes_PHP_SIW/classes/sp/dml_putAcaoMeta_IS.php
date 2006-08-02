<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAcaoMeta_IS
*
* { Description :- 
*    Mantém a tabela de metas de uma ação
* }
*/

class dml_putAcaoMeta_IS {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_chave_aux, $p_titulo, $p_descricao, $p_ordem, $p_inicio, $p_fim, $p_perc_conclusao, $p_orcamento, $p_programada, $p_cumulativa, $p_quantidade, $p_unidade_medida) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTACAOMETA_IS';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_titulo'                    =>array(tvl($p_titulo),                                   B_VARCHAR,       100),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,      2000),
                   'p_ordem'                     =>array(tvl($p_ordem),                                    B_VARCHAR,         3),
                   'p_inicio'                    =>array(tvl($p_inicio),                                   B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32),
                   'p_perc_conclusao'            =>array(tvl($p_perc_conclusao),                           B_INTEGER,        32),
                   'p_orcamento'                 =>array(toNumber(tvl($p_orcamento)),                      B_NUMERIC,      18,2),
                   'p_programada'                =>array($p_programada,                                    B_VARCHAR,         1),
                   'p_cumulativa'                =>array($p_cumulativa,                                    B_VARCHAR,         1),
                   'p_quantidade'                =>array(toNumber(tvl($p_quantidade)),                     B_NUMERIC,      18,2),
                   'p_unidade_medida'            =>array($p_unidade_medida,                                B_VARCHAR,        30)
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
