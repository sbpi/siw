<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCVCurso
*
* { Description :- 
*    Mantém os dados de extensão acadêmica do colaborador
* }
*/

class dml_putCVCurso {
   function getInstanceOf($dbms, $operacao, $p_pessoa, $p_chave, $p_sq_area_conhecimento, $p_sq_formacao, $p_nome, $p_instituicao, $p_carga_horaria, $p_conclusao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_PUTCVCURSO';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_pessoa'                    =>array($p_pessoa,                                        B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_area_conhecimento'      =>array($p_sq_area_conhecimento,                          B_INTEGER,        32),
                   'p_sq_formacao'               =>array($p_sq_formacao,                                   B_INTEGER,        32),
                   'p_nome'                      =>array($p_nome,                                          B_VARCHAR,        80),
                   'p_instituicao'               =>array($p_instituicao,                                   B_VARCHAR,       100),
                   'p_carga_horaria'             =>array($p_carga_horaria,                                 B_INTEGER,        32),
                   'p_conclusao'                 =>array(tvl($p_conclusao),                                B_DATE,           32)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
