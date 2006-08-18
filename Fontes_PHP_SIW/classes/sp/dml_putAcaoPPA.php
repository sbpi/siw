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
   function getInstanceOf($dbms, $operacao, $p_chave, $p_cliente, $p_sq_acao_ppa_pai, $p_codigo, $p_nome, $p_responsavel, $p_telefone, $p_email, $p_ativo, $p_padrao, $p_aprovado, $p_saldo, $p_empenhado, $p_liquidado, $p_liquidar, $p_selecionada_mpog, $p_selecionada_relevante, $p_cod_programa, $p_cod_acao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_PUTACAOPPA';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_sq_acao_ppa_pai'           =>array(tvl($p_sq_acao_ppa_pai),                          B_INTEGER,        32),
                   'p_codigo'                    =>array(tvl($p_codigo),                                   B_VARCHAR,        60),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       100),
                   'p_responsavel'               =>array(tvl($p_responsavel),                              B_VARCHAR,        60),
                   'p_telefone'                  =>array(tvl($p_telefone),                                 B_VARCHAR,        20),
                   'p_email'                     =>array(tvl($p_email),                                    B_VARCHAR,        60),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1),
                   'p_padrao'                    =>array(tvl($p_padrao),                                   B_VARCHAR,         1),
                   'p_aprovado'                  =>array(toNumber(tvl($p_aprovado)),                       B_NUMERIC,      18,2),
                   'p_saldo'                     =>array(toNumber(tvl($p_saldo)),                          B_NUMERIC,      18,2),
                   'p_empenhado'                 =>array(toNumber(tvl($p_empenhado)),                      B_NUMERIC,      18,2),
                   'p_liquidado'                 =>array(toNumber(tvl($p_liquidado)),                      B_NUMERIC,      18,2),
                   'p_liquidar'                  =>array(toNumber(tvl($p_liquidar)),                       B_NUMERIC,      18,2),
                   'p_selecionada_mpog'          =>array(tvl($p_selecionada_mpog),                         B_VARCHAR,         1),
                   'p_selecionada_relevante'     =>array(tvl($p_selecionada_relevante),                    B_VARCHAR,         1),
                   'p_cod_programa'              =>array(tvl($p_cod_programa),                             B_VARCHAR,        50),
                   'p_cod_acao'                  =>array(tvl($p_cod_acao),                                 B_VARCHAR,        50)
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
