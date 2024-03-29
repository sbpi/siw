<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getMatServ
*
* { Description :- 
*    Recupera dados da tabela de materiais e servi�os
* }
*/

class db_getMatServ {
   function getInstanceOf($dbms, $p_cliente, $p_usuario, $p_chave, $p_tipo_material, $p_codigo, $p_nome, 
          $p_ativo, $p_catalogo, $p_ata_aviso, $p_ata_invalida, $p_ata_valida, $p_aviso, $p_invalida, $p_valida, 
          $p_branco, $p_arp, $p_item, $p_numero_ata, $p_acrescimo, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getMatServ';
     $params=array('p_cliente'           =>array(tvl($p_cliente),                B_INTEGER,        32),
                   'p_usuario'           =>array(tvl($p_usuario),                B_INTEGER,        32),
                   'p_chave'             =>array(tvl($p_chave),                  B_INTEGER,        32),
                   'p_tipo_material'     =>array(tvl($p_tipo_material),          B_INTEGER,        32),
                   'p_codigo'            =>array(tvl($p_codigo),                 B_VARCHAR,        30),
                   'p_nome'              =>array(tvl($p_nome),                   B_VARCHAR,       110),
                   'p_ativo'             =>array(tvl($p_ativo),                  B_VARCHAR,         1),
                   'p_catalogo'          =>array(tvl($p_catalogo),               B_VARCHAR,         1),
                   'p_ata_aviso'         =>array(tvl($p_ata_aviso),              B_VARCHAR,         1),
                   'p_ata_invalida'      =>array(tvl($p_ata_invalida),           B_VARCHAR,         1),
                   'p_ata_valida'        =>array(tvl($p_ata_valida),             B_VARCHAR,         1),
                   'p_aviso'             =>array(tvl($p_aviso),                  B_VARCHAR,         1),
                   'p_invalida'          =>array(tvl($p_invalida),               B_VARCHAR,         1),
                   'p_valida'            =>array(tvl($p_valida),                 B_VARCHAR,         1),
                   'p_branco'            =>array(tvl($p_branco),                 B_VARCHAR,         1),
                   'p_arp'               =>array(tvl($p_arp),                    B_VARCHAR,         1),
                   'p_item'              =>array(tvl($p_item),                   B_INTEGER,        32),
                   'p_numero_ata'        =>array(tvl($p_numero_ata),             B_VARCHAR,        60),
                   'p_acrescimo'         =>array(tvl($p_acrescimo),              B_VARCHAR,         1),
                   'p_restricao'         =>array(tvl($p_restricao),              B_VARCHAR,        15),
                   'p_result'            =>array(null,                           B_CURSOR,         -1)
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
