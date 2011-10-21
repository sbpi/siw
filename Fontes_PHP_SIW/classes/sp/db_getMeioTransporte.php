<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/sp/db_exec.php');
/**
* class db_getMeioTransporte  
*
* { Description :- 
*    Recupera os meios de transporte
* }
*/

class db_getMeioTransporte {
   function getInstanceOf($dbms, $p_cliente, $p_restricao, $p_chave, $p_ativo, $p_nome) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $params=array('p_cliente'                  =>array($p_cliente,                       B_INTEGER,        32),
                   'p_restricao'                =>array(tvl($p_restricao),                B_VARCHAR,        20),
                   'p_chave'                    =>array(tvl($p_chave),                    B_INTEGER,        32),
                   'p_ativo'                    =>array(tvl($p_ativo),                    B_VARCHAR,         1),
                   'p_nome'                     =>array(tvl($p_nome),                     B_VARCHAR,        30),
                   'p_result'                   =>array(null,                             B_CURSOR,         -1)
                  );
     
     $sql = new db_exec; $par = $sql->normalize($params); extract($par,EXTR_OVERWRITE);

     $SQL = "select a.sq_meio_transporte as chave, a.nome, a.aereo, a.rodoviario, a.ferroviario, a.aquaviario, a.ativo, ".$crlf.
            "       case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo ".$crlf.
            "  from pd_meio_transporte a ".$crlf.
            " where a.cliente = $p_cliente$crlf".
            "   and ($p_nome   is null or ($p_nome   is not null and acentos(a.nome)      = acentos($p_nome)))$crlf".
            "   and ($p_chave  is null or ($p_chave  is not null and a.sq_meio_transporte = $p_chave))$crlf".
            "   and ($p_ativo  is null or ($p_ativo  is not null and a.ativo              = $p_ativo))$crlf";

     $l_rs = $sql->getInstanceOf($dbms,$SQL,$params);
     return $l_rs;
   }
}
?>
