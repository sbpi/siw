<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putSolicRelAnexo
*
* { Description :- 
*    Mant�m a tabela de arquivos
* }
*/

class dml_putSolicRelAnexo {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_chave_aux, $p_tipo_reg, $p_nome, $p_descricao, $p_caminho, $p_tamanho, $p_tipo, $p_nome_original) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PutSolicRelAnexo';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_tipo_reg'                  =>array(tvl($p_tipo_reg),                                 B_VARCHAR,         1),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       255),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,      1000),
                   'p_caminho'                   =>array(tvl($p_caminho),                                  B_VARCHAR,       255),
                   'p_tamanho'                   =>array(tvl($p_tamanho),                                  B_INTEGER,        32),
                   'p_tipo'                      =>array(tvl($p_tipo),                                     B_VARCHAR,       100),
                   'p_nome_original'             =>array(tvl($p_nome_original),                            B_VARCHAR,       255)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting();
     error_reporting(E_ERROR);
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
