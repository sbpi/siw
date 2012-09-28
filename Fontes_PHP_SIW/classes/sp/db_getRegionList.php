<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getRegionList
*
* { Description :- 
*    Recupera as regiões existentes
* }
*/

class db_getRegionList {
   function getInstanceOf($dbms, $p_pais, $p_restricao, $p_nome) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getRegionList';
     $params=array('p_pais'      =>array($p_pais,          B_NUMERIC,   32),
                   'p_nome'      =>array($p_nome,          B_VARCHAR,   20),
                   'p_restricao' =>array($p_restricao,     B_VARCHAR,   15),
                   'p_result'    =>array(null,             B_CURSOR,    -1)
                  );
     
    $w_restricao = nvl($p_restricao, '\'-\'');
    $sql = new db_exec;
    $par = $sql->normalize($params);
    extract($par, EXTR_OVERWRITE);
    $SQL = "select a.sq_regiao, a.nome, a.ordem, a.sigla, b.nome nome_pais, b.sq_pais, b.padrao,$crlf" .
            "       b.padrao, a.sq_regiao$crlf" .
            "  from co_regiao            a$crlf" .
            "       inner join co_pais   b on (a.sq_pais   = b.sq_pais)$crlf" .
            "       left join (select x.sq_regiao, count(x.sq_regiao) as qtd$crlf" .
            "                    from eo_indicador_afericao   x$crlf" .
            "                         inner join eo_indicador y on (x.sq_eoindicador = y.sq_eoindicador and$crlf" .
            "                                                       y.ativo          = 'S'$crlf" .
            "                                                      )$crlf" .
            "                   where y.cliente   = coalesce(to_number($p_nome),0) -- $$p_nome como chave de SIW_CLIENTE$crlf" .
            "                     and x.sq_regiao is not null$crlf" .
            "                  group by x.sq_regiao$crlf" .
            "                 )          c on (a.sq_regiao = c.sq_regiao)$crlf" .
            " where (coalesce($p_restricao,'-')   = 'N'          or (coalesce($p_restricao,'-') <> 'N' and a.sq_pais = b.sq_pais))$crlf" .
            "   and ((coalesce($p_restricao,'-')  = 'INDICADOR'  and c.sq_regiao is not null) or$crlf" .
            "        (coalesce($p_restricao,'-')  <> 'INDICADOR' and ($p_nome     is null      or ($p_nome is not null and acentos(a.nome) like '%'" . C . "acentos($p_nome)" . C . "'%')))$crlf" .
            "       )$crlf" .
            "   and ($p_pais        is null        or ($p_pais is not null and b.sq_pais = $p_pais))$crlf";
    $l_rs = $sql->getInstanceOf($dbms, $SQL, $params);
    return $l_rs;     
   }
}    
?>
