<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPD_Alteracao
*
* { Description :- 
*    Grava alteracao de viagem
* }
*/

class dml_putPD_Alteracao {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_chave_aux, $p_valor_tar, $p_valor_tax, 
        $p_valor_hsp, $p_valor_dia, $p_justificativa, $p_pessoa, $p_cargo, $p_data, $p_exclui, $p_caminho,
        $p_tamanho, $p_tipo, $p_nome_original) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPD_Alteracao';
     $params=array('p_operacao'              =>array($operacao,                           B_VARCHAR,         1),
                   'p_cliente'               =>array(tvl($p_cliente),                     B_INTEGER,        32),
                   'p_chave'                 =>array(tvl($p_chave),                       B_INTEGER,        32),
                   'p_chave_aux'             =>array(tvl($p_chave_aux),                   B_INTEGER,        32),
                   'p_valor_tar'             =>array(toNumber(tvl($p_valor_tar)),         B_NUMERIC,      18,2),
                   'p_valor_tax'             =>array(toNumber(tvl($p_valor_tax)),         B_NUMERIC,      18,2),
                   'p_valor_hsp'             =>array(toNumber(tvl($p_valor_hsp)),         B_NUMERIC,      18,2),
                   'p_valor_dia'             =>array(toNumber(tvl($p_valor_dia)),         B_NUMERIC,      18,2),
                   'p_justificativa'         =>array(tvl($p_justificativa),               B_VARCHAR,      2000),
                   'p_pessoa'                =>array(tvl($p_pessoa),                      B_INTEGER,        32),
                   'p_cargo'                 =>array(tvl($p_cargo),                       B_VARCHAR,        90),
                   'p_data'                  =>array(tvl($p_data),                        B_DATE,           32),
                   'p_exclui'                =>array(tvl($p_exclui),                      B_VARCHAR,         1),
                   'p_caminho'               =>array(tvl($p_caminho),                     B_VARCHAR,       255),
                   'p_tamanho'               =>array(tvl($p_tamanho),                     B_INTEGER,        32),
                   'p_tipo'                  =>array(tvl($p_tipo),                        B_VARCHAR,       100),
                   'p_nome_original'         =>array(tvl($p_nome_original),               B_VARCHAR,       255)
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
