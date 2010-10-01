<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putGpFamiliares
*
* { Description :- 
*    Recupera os parametros
* }
*/

class dml_putGPFamiliares {
   function getInstanceOf($dbms,   $operacao,     $p_chave, $p_cliente, $p_colaborador,
                          $p_cpf,  $p_nome,       $p_nome_resumido,     $p_nascimento,
                          $p_sexo, $p_parentesco, $p_seguro_saude,      $p_seguro_odonto,
                          $p_seguro_vida,         $p_imposto_renda) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'sp_putGpFamiliares';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),     
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),     
                   'p_colaborador'               =>array(tvl($p_colaborador),                              B_INTEGER,        32),
                   'p_cpf'                       =>array(tvl($p_cpf),                                      B_VARCHAR,        14),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        60),
                   'p_nome_resumido'             =>array(tvl($p_nome_resumido),                            B_VARCHAR,        21),
                   'p_nascimento'                =>array(tvl($p_nascimento),                               B_DATE,           32),
                   'p_sexo'                      =>array(tvl($p_sexo),                                     B_VARCHAR,         1),
                   'p_parentesco'                =>array(tvl($p_parentesco),                               B_INTEGER,        32),
                   'p_seguro_vida'               =>array($p_seguro_vida,                                   B_VARCHAR,         1),
                   'p_seguro_saude'              =>array($p_seguro_saude,                                  B_VARCHAR,         1),
                   'p_seguro_odonto'             =>array($p_seguro_odonto,                                 B_VARCHAR,         1),
                   'p_imposto_renda'             =>array($p_imposto_renda,                                 B_VARCHAR,         1)                     
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0);
     if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
     else {
       error_reporting($l_error_reporting); 
       if ($l_rs = $l_rs->getResultData()) {
         return $l_rs;
       } else {
         return array();
       }
     }
   }
}
?>
