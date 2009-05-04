<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPD_Contas
*
* { Description :- 
*    Mant�m a tabela de arquivos
* }
*/

class dml_putPD_Contas {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_cumprimento, $p_nota_conclusao, $p_relatorio,
        $p_sq_relatorio, $p_exclui, $p_nome, $p_descricao, $p_caminho, $p_tamanho, $p_tipo, $p_nome_original) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPD_Contas';
     $params=array('p_cliente'             =>array($p_cliente,                               B_INTEGER,        32),
                   'p_chave'               =>array(tvl($p_chave),                            B_INTEGER,        32),
                   'p_cumprimento'         =>array(tvl($p_cumprimento),                      B_VARCHAR,         1),
                   'p_nota_conclusao'      =>array(tvl($p_nota_conclusao),                   B_VARCHAR,      2000),
                   'p_relatorio'           =>array(tvl($p_relatorio),                        B_VARCHAR,      4000),
                   'p_sq_relatorio'        =>array(tvl($p_sq_relatorio),                     B_INTEGER,        32),
                   'p_exclui'              =>array(tvl($p_exclui),                           B_VARCHAR,         1),
                   'p_nome'                =>array(tvl($p_nome),                             B_VARCHAR,       255),
                   'p_descricao'           =>array(tvl($p_descricao),                        B_VARCHAR,      1000),
                   'p_caminho'             =>array(tvl($p_caminho),                          B_VARCHAR,       255),
                   'p_tamanho'             =>array(tvl($p_tamanho),                          B_INTEGER,        32),
                   'p_tipo'                =>array(tvl($p_tipo),                             B_VARCHAR,       100),
                   'p_nome_original'       =>array(tvl($p_nome_original),                    B_VARCHAR,       255)
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
