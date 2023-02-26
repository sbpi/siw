<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getPD_Fatura
*
* { Description :- 
*    Recupera faturas de agência de viagem
* }
*/

class db_getPD_Fatura {
   function getInstanceOf($dbms, $p_cliente, $p_agencia, $p_fatura, $p_bilhete, $p_numero_fat, $p_arquivo, $p_cia_trans, $p_solic_viagem,
        $p_codigo_viagem, $p_solic_pai, $p_numero_bil, $p_ini_dec, $p_fim_dec, $p_ini_emifat, $p_fim_emifat, $p_ini_ven, $p_fim_ven,
        $p_ini_emibil, $p_fim_emibil, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getPD_Fatura';
     $params=array('p_cliente'            =>array(tvl($p_cliente),                  B_INTEGER,        32),
                   'p_agencia'            =>array(tvl($p_agencia),                  B_INTEGER,        32),
                   'p_fatura'             =>array(tvl($p_fatura),                   B_INTEGER,        32),
                   'p_bilhete'            =>array(tvl($p_bilhete),                  B_INTEGER,        32),
                   'p_numero_fat'         =>array(tvl($p_numero_fat),               B_VARCHAR,        30),
                   'p_arquivo'            =>array(tvl($p_arquivo),                  B_INTEGER,        32),
                   'p_cia_trans'          =>array(tvl($p_cia_trans),                B_INTEGER,        32),
                   'p_solic_viagem'       =>array(tvl($p_solic_viagem),             B_INTEGER,        32),
                   'p_codigo_viagem'      =>array(tvl($p_codigo_viagem),            B_VARCHAR,        60),
                   'p_solic_pai'          =>array(tvl($p_solic_pai),                B_INTEGER,        32),
                   'p_numero_bil'         =>array(tvl($p_numero_bil),               B_VARCHAR,        20),
                   'p_ini_dec'            =>array(tvl($p_ini_dec),                  B_DATE,           32),
                   'p_fim_dec'            =>array(tvl($p_fim_dec),                  B_DATE,           32),
                   'p_ini_emifat'         =>array(tvl($p_ini_emifat),               B_DATE,           32),
                   'p_fim_emifat'         =>array(tvl($p_fim_emifat),               B_DATE,           32),
                   'p_ini_ven'            =>array(tvl($p_ini_ven),                  B_DATE,           32),
                   'p_fim_ven'            =>array(tvl($p_fim_ven),                  B_DATE,           32),
                   'p_ini_emibil'         =>array(tvl($p_ini_emibil),               B_DATE,           32),
                   'p_fim_emibil'         =>array(tvl($p_fim_emibil),               B_DATE,           32),
                   'p_restricao'          =>array(tvl($p_restricao),                B_VARCHAR,        20),
                   'p_result'             =>array(null,                             B_CURSOR,         -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR);
     if(!$l_rs->executeQuery()) {
       error_reporting($l_error_reporting);
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__);
     } else {
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
