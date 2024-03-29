<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putProjetoRubrica
*
* { Description :- 
*    Mant�m a tabela de rubrica em um Projeto
* }
*/

class dml_putProjetoRubrica {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_chave_aux, $p_chave_pai, $p_unidade_medida, $p_ultimo_nivel, $p_sq_cc, 
           $p_codigo, $p_nome, $p_descricao, $p_ativo, $p_aplicacao_financeira, $p_exige_autorizacao, $p_copia) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTPROJETORUBRICA';
     $params=array('p_operacao'                 =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                    =>array($p_chave,                                         B_INTEGER,        32),
                   'p_chave_aux'                =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_chave_pai'                =>array(tvl($p_chave_pai),                                B_INTEGER,        32),
                   'p_unidade_medida'           =>array(tvl($p_unidade_medida),                           B_INTEGER,        32),
                   'p_ultimo_nivel'             =>array(tvl($p_ultimo_nivel),                             B_VARCHAR,         1),
                   'p_sq_cc'                    =>array($p_sq_cc,                                         B_INTEGER,        32),
                   'p_codigo'                   =>array(tvl($p_codigo),                                   B_VARCHAR,       100),
                   'p_nome'                     =>array(tvl($p_nome),                                     B_VARCHAR,       100),
                   'p_descricao'                =>array(tvl($p_descricao),                                B_VARCHAR,      2000),
                   'p_ativo'                    =>array(tvl($p_ativo),                                    B_VARCHAR,         1),
                   'p_aplicacao_financeira'     =>array(tvl($p_aplicacao_financeira),                     B_VARCHAR,         1),
                   'p_exige_autorizacao'        =>array(tvl($p_exige_autorizacao),                        B_VARCHAR,         1),
                   'p_copia'                    =>array($p_copia,                                         B_INTEGER,        32)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
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
