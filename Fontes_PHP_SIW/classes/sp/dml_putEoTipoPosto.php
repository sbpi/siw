<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putEOTipoPosto
*
* { Description :- 
*    Manipula registros de EO_Tipo_Posto
* }
*/

class dml_putEOTipoPosto {
   function getInstanceOf($dbms, $operacao, $chave, $p_cliente, $nome, $sigla, $descricao, $ativo, $padrao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTEOTIPOPOSTO';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($chave),                                      B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_nome'                      =>array($nome,                                            B_VARCHAR,        30),
                   'p_sigla'                     =>array($sigla,                                           B_VARCHAR,         5),
                   'p_descricao'                 =>array($descricao,                                       B_VARCHAR,       200),
                   'p_ativo'                     =>array($ativo,                                           B_VARCHAR,         1),
                   'p_padrao'                    =>array($padrao,                                          B_VARCHAR,         1)
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
