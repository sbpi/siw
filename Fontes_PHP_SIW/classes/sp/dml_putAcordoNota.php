<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAcordoNota
*
* { Description :- 
*    Grava a tela de notas de empenho do acordo
* }
*/

class dml_putAcordoNota {
   function getInstanceOf($dbms, $operacao, $p_chave_aux, $p_chave, $p_sq_tipo_documento, $p_sq_acordo_outra_parte, $p_sq_acordo_aditivo, $p_numero, $p_data,
                          $p_valor, $p_sq_lcfonte_recurso, $p_espec_despesa, $p_observacao, $p_abrange_inicial, $p_abrange_acrescimo, $p_abrange_reajuste, $p_sq_acordo_parcela, 
                          $p_chave_nova) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTACORDONOTA';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,        30),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_tipo_documento'         =>array(tvl($p_sq_tipo_documento),                        B_INTEGER,        32),
                   'p_sq_acordo_outra_parte'     =>array(tvl($p_sq_acordo_outra_parte),                    B_INTEGER,        32),
                   'p_sq_acordo_aditivo'         =>array(tvl($p_sq_acordo_aditivo),                        B_INTEGER,        32),                   
                   'p_numero'                    =>array(tvl($p_numero),                                   B_VARCHAR,        30),
                   'p_data'                      =>array(tvl($p_data),                                     B_DATE,           32),
                   'p_valor'                     =>array(toNumber(tvl($p_valor)),                          B_NUMERIC,      18,2),
                   'p_sq_lcfonte_recurso'        =>array(tvl($p_sq_lcfonte_recurso),                       B_INTEGER,        32),
                   'p_espec_despesa'             =>array(tvl($p_espec_despesa),                            B_INTEGER,        32),
                   'p_observacao'                =>array(tvl($p_observacao),                               B_VARCHAR,       500),
                   'p_abrange_inicial'           =>array(tvl($p_abrange_inicial),                          B_VARCHAR,         1),
                   'p_abrange_acrescimo'         =>array(tvl($p_abrange_acrescimo),                        B_VARCHAR,         1),
                   'p_abrange_reajuste'          =>array(tvl($p_abrange_reajuste),                         B_VARCHAR,         1),
                   'p_sq_acordo_parcela'         =>array(tvl($p_sq_acordo_parcela),                        B_INTEGER,        32),
                   'p_chave_nova'                =>array(&$p_chave_nova,                                   B_INTEGER,        32)
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
