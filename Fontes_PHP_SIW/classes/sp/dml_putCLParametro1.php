<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');

/**
* class dml_putCLParametro
*
* { Description :- 
*    Mantém a tabela de parâmetros do módulo Compras e licitações.
* }
*/

class dml_putCLParametro {
   function getInstanceOf($dbms, $p_cliente, $p_ano_corrente, $p_dias_validade_pesquisa, $p_dias_aviso_pesquisa, $p_percentual_acrescimo,  
            $p_compra_central, $p_pesquisa_central, $p_contrato_central, $p_banco_ata_central, $p_banco_preco_central,
            $p_codificacao_central) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putCLParametro';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_ano_corrente'              =>array(tvl($p_ano_corrente),                             B_INTEGER,        32),
                   'p_dias_validade_pesquisa'    =>array(tvl($p_dias_validade_pesquisa),                   B_INTEGER,        32),
                   'p_dias_aviso_pesquisa'       =>array(tvl($p_dias_aviso_pesquisa),                      B_INTEGER,        32),                   
                   'p_percentual_acrescimo'      =>array(tvl($p_percentual_acrescimo),                     B_INTEGER,        32),
                   'p_compra_central'            =>array(tvl($p_compra_central),                           B_VARCHAR,         1),
                   'p_pesquisa_central'          =>array(tvl($p_pesquisa_central),                         B_VARCHAR,         1),
                   'p_contrato_central'          =>array(tvl($p_contrato_central),                         B_VARCHAR,         1),
                   'p_banco_ata_central'         =>array(tvl($p_banco_ata_central),                        B_VARCHAR,         1),
                   'p_banco_preco_central'       =>array(tvl($p_banco_preco_central),                      B_VARCHAR,         1),
                   'p_codificacao_central'       =>array(tvl($p_codificacao_central),                      B_VARCHAR,         1),
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
