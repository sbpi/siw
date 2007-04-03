<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAcordoDadosAdicionais
*
* { Description :- 
*    Grava a tela de dados adicionais do acordo
* }
*/

class dml_putAcordoDadosAdicionais {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_numero_certame, $p_numero_ata, $p_tipo_reajuste, $p_limite_variacao, $p_indice_base, $p_sq_eoindicador, 
                          $p_sq_lcfonte_recurso, $p_espec_despesa, $p_sq_lcmodalidade, $p_numero_empenho, $p_numero_processo, $p_data_assinatura, $p_data_publicacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTACORDODADOSADICIONAIS';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_numero_certame'            =>array(tvl($p_numero_certame),                           B_VARCHAR,        30),
                   'p_numero_ata'                =>array(tvl($p_numero_ata),                               B_VARCHAR,        30),
                   'p_tipo_reajuste'             =>array(tvl($p_tipo_reajuste),                            B_INTEGER,        32),
                   'p_limite_variacao'           =>array(toNumber(tvl($p_limite_variacao)),                B_NUMERIC,      18,2),
                   'p_indice_base'               =>array(tvl($p_indice_base),                              B_VARCHAR,         7),
                   'p_sq_eoindicador'            =>array(tvl($p_sq_eoindicador),                           B_INTEGER,        32),                   
                   'p_sq_lcfonte_recurso'        =>array(tvl($p_sq_lcfonte_recurso),                       B_INTEGER,        32),
                   'p_espec_despesa'             =>array(tvl($p_espec_despesa),                            B_INTEGER,        32),
                   'p_sq_lcmodalidade'           =>array(tvl($p_sq_lcmodalidade),                          B_INTEGER,        32),                   
                   'p_numero_empenho'            =>array(tvl($p_numero_empenho),                           B_VARCHAR,        30),
                   'p_numero_processo'           =>array(tvl($p_numero_processo),                          B_VARCHAR,        30),
                   'p_data_assinatura'           =>array(tvl($p_data_assinatura),                          B_DATE,           32),
                   'p_data_publicacao'           =>array(tvl($p_data_publicacao),                          B_DATE,           32)
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
