<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAcaoPPA
*
* { Description :- 
*    Mantém a tabela de ações do PPA
* }
*/

class dml_putAcaoPPA {
   function getInstanceOf($dbms, $operacao, $l_chave, $l_cliente, $l_sq_acao_ppa_pai, $l_codigo, $l_nome, $l_responsavel, $l_telefone, $l_email, $l_ativo, $l_padrao, $l_aprovado, $l_saldo, $l_empenhado, $l_liquidado, $l_liquidar, $l_selecionada_mpog, $l_selecionada_relevante, $l_cod_programa, $l_cod_acao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_PUTACAOPPA';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($l_chave),                                    B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($l_cliente),                                  B_INTEGER,        32),
                   'p_sq_acao_ppa_pai'           =>array(tvl($l_sq_acao_ppa_pai),                          B_INTEGER,        32),
                   'p_codigo'                    =>array(tvl($l_codigo),                                   B_VARCHAR,        60),
                   'p_nome'                      =>array(tvl($l_nome),                                     B_VARCHAR,       100),
                   'p_responsavel'               =>array(tvl($l_responsavel),                              B_VARCHAR,        60),
                   'p_telefone'                  =>array(tvl($l_telefone),                                 B_VARCHAR,        20),
                   'p_email'                     =>array(tvl($l_email),                                    B_VARCHAR,        60),
                   'p_ativo'                     =>array(tvl($l_ativo),                                    B_VARCHAR,         1),
                   'p_padrao'                    =>array(tvl($l_padrao),                                   B_VARCHAR,         1),
                   'p_aprovado'                  =>array(toNumber(tvl($l_aprovado)),                       B_NUMERIC,      18,2),
                   'p_saldo'                     =>array(toNumber(tvl($l_saldo)),                          B_NUMERIC,      18,2),
                   'p_empenhado'                 =>array(toNumber(tvl($l_empenhado)),                      B_NUMERIC,      18,2),
                   'p_liquidado'                 =>array(toNumber(tvl($l_liquidado)),                      B_NUMERIC,      18,2),
                   'p_liquidar'                  =>array(toNumber(tvl($l_liquidar)),                       B_NUMERIC,      18,2),
                   'p_selecionada_mpog'          =>array(tvl($l_selecionada_mpog),                         B_VARCHAR,         1),
                   'p_selecionada_relevante'     =>array(tvl($l_selecionada_relevante),                    B_VARCHAR,         1),
                   'p_cod_programa'              =>array(tvl($l_cod_programa),                             B_VARCHAR,        50),
                   'p_cod_acao'                  =>array(tvl($l_cod_acao),                                 B_VARCHAR,        50)
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
