<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCLARPItem
*
* { Description :- 
*    Grava da itens do contrato de ARP
* }
*/

class dml_putCLARPItem {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_usuario, $p_solic, $p_item, $p_ordem, $p_codigo, 
          $p_fabricante, $p_marca_modelo, $p_embalagem, $p_fator, $p_quantidade, $p_valor, 
          $p_cancelado, $p_motivo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putCLARPItem';
     $params=array('p_operacao'             =>array($operacao,                          B_VARCHAR,         1),
                   'p_cliente'              =>array($p_cliente,                         B_INTEGER,        32),
                   'p_usuario'              =>array($p_usuario,                         B_INTEGER,        32),
                   'p_solic'                =>array($p_solic,                           B_INTEGER,        32),
                   'p_item'                 =>array(tvl($p_item),                       B_INTEGER,        32),
                   'p_ordem'                =>array(tvl($p_ordem),                      B_VARCHAR,        10),
                   'p_codigo'               =>array(tvl($p_codigo),                     B_VARCHAR,        60),
                   'p_fabricante'           =>array(tvl($p_fabricante),                 B_VARCHAR,        50),
                   'p_marca_modelo'         =>array(tvl($p_marca_modelo),               B_VARCHAR,        50),
                   'p_embalagem'            =>array(tvl($p_embalagem),                  B_VARCHAR,        20),
                   'p_fator'                =>array(tvl($p_fator),                      B_INTEGER,        32),
                   'p_quantidade'           =>array(tonumber(tvl($p_quantidade)),       B_NUMERIC,      18,2),
                   'p_valor'                =>array(tonumber(tvl($p_valor)),            B_NUMERIC,      18,4),
                   'p_cancelado'            =>array(tvl($p_cancelado),                  B_VARCHAR,         1),
                   'p_motivo'               =>array(tvl($p_motivo),                     B_VARCHAR,       500)
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
