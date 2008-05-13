<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAcordoFornecimento
*
* { Description :- 
*    Grava a tela de autorizações de fornecimento
* }
*/

class dml_putAcordoFornecimento {
   function getInstanceOf($dbms, $operacao, $p_chave_aux, $p_chave, $p_ordem_fornecimento, $p_numero, $p_local_entrega, 
          $p_agendamento, $p_mail, $p_numero_processo, $p_nota_empenho, $p_valor_empenho, $p_data_prevista,
          $p_autorizador_nome, $p_autorizador_funcao, $p_solicitante, $p_responsavel_nome, $p_responsavel_funcao,
          $p_responsavel_rg, $p_responsavel_data, $p_situacao, $p_sq_item, $p_quantidade, $p_valor_item, 
          $p_chave_nova) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putAcordoFornecimento';
     $params=array('p_operacao'             =>array($operacao,                                B_VARCHAR,        30),
                   'p_chave_aux'            =>array(tvl($p_chave_aux),                        B_INTEGER,        32),
                   'p_chave'                =>array(tvl($p_chave),                            B_INTEGER,        32),
                   'p_ordem_fornecimento'   =>array(tvl($p_ordem_fornecimento),               B_VARCHAR,         1),
                   'p_numero'               =>array(tvl($p_numero),                           B_VARCHAR,        30),
                   'p_local_entrega'        =>array(tvl($p_local_entrega),                    B_VARCHAR,       100),
                   'p_agendamento'          =>array(tvl($p_agendamento),                      B_VARCHAR,       255),
                   'p_mail'                 =>array(tvl($p_mail),                             B_VARCHAR,        80),
                   'p_numero_processo'      =>array(tvl($p_numero_processo),                  B_VARCHAR,        30),
                   'p_nota_empenho'         =>array(tvl($p_nota_empenho),                     B_VARCHAR,        30),
                   'p_valor_empenho'        =>array(toNumber(tvl($p_valor_empenho)),          B_NUMERIC,      18,2),
                   'p_data_prevista'        =>array(tvl($p_data_prevista),                    B_DATE,           32),
                   'p_autorizador_nome'     =>array(tvl($p_autorizador_nome),                 B_VARCHAR,        60),
                   'p_autorizador_funcao'   =>array(tvl($p_autorizador_funcao),               B_VARCHAR,        60),
                   'p_solicitante'          =>array(tvl($p_solicitante),                      B_VARCHAR,        60),
                   'p_responsavel_nome'     =>array(tvl($p_responsavel_nome),                 B_VARCHAR,        60),
                   'p_responsavel_funcao'   =>array(tvl($p_responsavel_funcao),               B_VARCHAR,        60),
                   'p_responsavel_rg'       =>array(tvl($p_responsavel_nome),                 B_VARCHAR,        60),
                   'p_responsavel_data'     =>array(tvl($p_responsavel_data),                 B_DATE,           32),
                   'p_situacao'             =>array(tvl($p_situacao),                         B_VARCHAR,         1),
                   'p_sq_item'              =>array(tvl($p_sq_item),                          B_INTEGER,        32),
                   'p_quantidade'           =>array(toNumber(tvl($p_quantidade)),             B_INTEGER,        32),
                   'p_valor_item'           =>array(toNumber(tvl($p_valor_item)),             B_NUMERIC,      18,2),
                   'p_chave_nova'           =>array(&$p_chave_nova,                           B_INTEGER,        32)
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
