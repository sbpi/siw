<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getBenef
*
* { Description :- 
*    Recupera os dados de um beneficiário.
* }
*/

class db_getBenef {
   function getInstanceOf($dbms, $p_cliente, $p_sq_pessoa, $p_cpf, $p_cnpj, $p_nome, $p_tipo_pessoa,
        $p_passaporte_numero, $p_sq_pais_passaporte) {
     $sql='sp_getBenef';
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
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        if ($l_rs = $l_rs->getResultData()) {
          return $l_rs;
        } else {
          return array();
        }
     }
   }
}    
?>
