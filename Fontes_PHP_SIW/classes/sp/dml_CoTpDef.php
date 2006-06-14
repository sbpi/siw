<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_CoTpDef
*
* { Description :- 
*    Manipula registros de CO_DEFICIENCIA
* }
*/

class dml_CoTpDef {
   function getInstanceOf($dbms, $operacao, $chave, $sq_grupo_deficiencia, $p_codigo, $p_nome, $p_descricao, $p_ativo) {
     $sql=$strschema.'sp_putCoTpDef';
     $params=array('operacao'               =>array($operacao,              B_VARCHAR,      1),
                   'chave'                  =>array($chave,                 B_NUMERIC,     32),
                   'sq_grupo_deficiencia'   =>array($sq_grupo_deficiencia,  B_NUMERIC,     32),
                   'p_codigo'                =>array($p_codigo,             B_VARCHAR,      3),
                   'p_nome'                 =>array($p_nome,                B_VARCHAR,     50),
                   'p_descricao'            =>array($p_descricao,           B_VARCHAR,    200),
                   'p_ativo'                =>array($p_ativo,               B_VARCHAR,      1)
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
