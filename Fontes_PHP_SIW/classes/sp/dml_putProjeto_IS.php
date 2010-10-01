<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putProjeto_IS
*
* { Description :- 
*    Mantém a tabela de programa interno
* }
*/

class dml_putProjeto_IS {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_cliente, $p_codigo, $p_nome, $p_responsavel, $p_telefone, $p_email, $p_ordem, $p_ativo, $p_padrao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTPROJETO_IS';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_codigo'                    =>array(tvl($p_codigo),                                   B_VARCHAR,        50),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       100),
                   'p_responsavel'               =>array(tvl($p_responsavel),                              B_VARCHAR,        60),
                   'p_telefone'                  =>array(tvl($p_telefone),                                 B_VARCHAR,        20),
                   'p_email'                     =>array(tvl($p_email),                                    B_VARCHAR,        60),
                   'p_ordem'                     =>array(tvl($p_ordem),                                    B_INTEGER,        32),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1),
                   'p_padrao'                    =>array(tvl($p_padrao),                                   B_VARCHAR,         1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
