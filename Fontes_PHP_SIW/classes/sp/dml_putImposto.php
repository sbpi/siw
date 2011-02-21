<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putImposto
*
* { Description :- 
*    Mantém a tabela de impostos
* }
*/

class dml_putImposto {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_cliente, $p_nome, $p_descricao, $p_lancamento, $p_documento, $p_sigla, 
          $p_esfera, $p_calculo, $p_dia_pagamento, $p_ativo, $p_tipo_benef, $p_sq_benef, $p_tipo_vinc, $p_sq_cc, $p_sq_solic) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putImposto';
     $params=array('p_operacao'                  =>array($operacao,                                B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                            B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($p_cliente),                          B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                             B_VARCHAR,        50),
                   'p_descricao'                 =>array(tvl($p_descricao),                        B_VARCHAR,       500),
                   'p_lancamento'                =>array(tvl($p_lancamento),                       B_INTEGER,        32),
                   'p_documento'                 =>array(tvl($p_documento),                        B_INTEGER,        32),
                   'p_sigla'                     =>array(tvl($p_sigla),                            B_VARCHAR,        15),
                   'p_esfera'                    =>array(tvl($p_esfera),                           B_VARCHAR,         1),
                   'p_calculo'                   =>array(tvl($p_calculo),                          B_INTEGER,        32),
                   'p_dia_pagamento'             =>array(tvl($p_dia_pagamento),                    B_INTEGER,        32),
                   'p_ativo'                     =>array(tvl($p_ativo),                            B_VARCHAR,         1),
                   'p_tipo_benef'                =>array(tvl($p_tipo_benef),                       B_INTEGER,        32),
                   'p_sq_benef'                  =>array(tvl($p_sq_benef),                         B_INTEGER,        32),
                   'p_tipo_vinc'                 =>array(tvl($p_tipo_vinc),                        B_INTEGER,        32),
                   'p_sq_cc'                     =>array(tvl($p_sq_cc),                            B_INTEGER,        32),
                   'p_sq_solic'                  =>array(tvl($p_sq_solic),                         B_INTEGER,        32)
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
