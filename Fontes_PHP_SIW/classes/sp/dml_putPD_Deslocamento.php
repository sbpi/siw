<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPD_Deslocamento
*
* { Description :- 
*    Grava a tela de parcelas
* }
*/

class dml_putPD_Deslocamento {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_chave_aux, $p_origem, $p_data_saida, $p_hora_saida, $p_destino, $p_data_chegada, $p_hora_chegada, $p_sq_cia_transporte, $p_codigo_voo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTPD_DESLOCAMENTO';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_origem'                    =>array(tvl($p_origem),                                   B_INTEGER,        32),
                   'p_data_saida'                =>array(tvl($p_data_saida),                               B_DATE,           32),
                   'p_hora_saida'                =>array(tvl($p_hora_saida),                               B_VARCHAR,         5),
                   'p_destino'                   =>array(tvl($p_destino),                                  B_INTEGER,        32),
                   'p_data_chegada'              =>array(tvl($p_data_chegada),                             B_DATE,           32),
                   'p_hora_chegada'              =>array(tvl($p_hora_chegada),                             B_VARCHAR,         5),
                   'p_sq_cia_transporte'         =>array(tvl($p_sq_cia_transporte),                        B_INTEGER,        32),
                   'p_codigo_voo'                =>array(tvl($p_codigo_voo),                               B_VARCHAR,        30)
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
