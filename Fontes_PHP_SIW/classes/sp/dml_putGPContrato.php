<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putGPContrato
*
* { Description :- 
*    Mantém os dados do contrato de um colaborador
* }
*/

class dml_putGPContrato {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_sq_pessoa, $p_sq_posto_trabalho, $p_sq_modalidade_contrato, $p_sq_unidade_lotacao, $p_sq_unidade_exercicio, $p_sq_localizacao, $p_matricula, $p_inicio, $p_fim, $p_tipo_vinculo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_PUTGPCONTRATO';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_pessoa'                 =>array(tvl($p_sq_pessoa),                                B_INTEGER,        32),
                   'p_sq_posto_trabalho'         =>array(tvl($p_sq_posto_trabalho),                        B_INTEGER,        32),
                   'p_sq_modalidade_contrato'    =>array(tvl($p_sq_modalidade_contrato),                   B_INTEGER,        32),
                   'p_sq_unidade_lotacao'        =>array(tvl($p_sq_unidade_lotacao),                       B_INTEGER,        32),
                   'p_sq_unidade_exercicio'      =>array(tvl($p_sq_unidade_exercicio),                     B_INTEGER,        32),
                   'p_sq_localizacao'            =>array(tvl($p_sq_localizacao),                           B_INTEGER,        32),
                   'p_matricula'                 =>array(tvl($p_matricula),                                B_VARCHAR,        20),
                   'p_inicio'                    =>array(tvl($p_inicio),                                   B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32),
                   'p_tipo_vinculo'              =>array(tvl($p_tipo_vinculo),                             B_INTEGER,        32)
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
