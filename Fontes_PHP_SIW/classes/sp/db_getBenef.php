<?
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getBenef
*
* { Description :- 
*    Recupera os dados de um benefici�rio.
* }
*/

class db_getBenef {
   function getInstanceOf($dbms, $p_cliente, $p_sq_pessoa, $p_cpf, $p_cnpj, $p_nome, $p_tipo_pessoa,
        $p_passaporte_numero, $p_sq_pais_passaporte) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getBenef';
     $params=array("p_cliente"              =>array($p_cliente,             B_NUMERIC,     32),
                   "p_sq_pessoa"            =>array($p_sq_pessoa,           B_NUMERIC,     32),
                   "p_cpf"                  =>array($p_cpf,                 B_VARCHAR,     14),
                   "p_cnpj"                 =>array($p_cnpj,                B_VARCHAR,     18),
                   "p_nome"                 =>array($p_nome,                B_VARCHAR,     20),
                   "p_tipo_pessoa"          =>array($p_tipo_pessoa,         B_NUMERIC,     32),
                   "p_passaporte_numero"    =>array($p_passaporte_numero,   B_VARCHAR,     20),
                   "p_sq_pais_passaporte"   =>array($p_sq_pais_passaporte,  B_NUMERIC,     32),
                   "p_result"               =>array(null,                   B_CURSOR,      -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0); if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
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
