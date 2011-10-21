<?php
extract($GLOBALS); include_once($w_dir_volta."classes/sp/db_exec.php");
/**
* class sp_getStateList
*
* { Description :- 
*    Recupera as cidades existentes em relação a um país
* }
*/
class db_getStateList {
   function getInstanceOf($dbms, $p_pais, $p_regiao, $p_ativo, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $params=array("p_pais"      =>array($p_pais,       B_NUMERIC,     32),
                   "p_regiao"    =>array($p_regiao,     B_NUMERIC,     32),
                   "p_ativo"     =>array($p_ativo,      B_VARCHAR,     1),
                   "p_restricao" =>array($p_restricao,  B_VARCHAR,     20),
                   "p_result"    =>array(null,          B_CURSOR,      -1)
                  );
     
    $sql = new db_exec;
    $par = $sql->normalize($params);
    extract($par, EXTR_OVERWRITE);

     $SQL = " select a.co_uf, a.sq_pais, a.sq_regiao, a.nome, a.ativo, a.padrao,$crlf" .
            "       coalesce(a.codigo_ibge,'-') as codigo_ibge,$crlf" .
            "       case a.padrao when 'S' then 'Sim' else 'Não' end as padraodesc,$crlf" .
            "       case a.ativo when 'S' then 'Sim' else 'Não' end as ativodesc,$crlf" .
            "       b.nome nome_pais,$crlf" .
            "       c.nome as nome_regiao,$crlf" .
            "       acentos(a.nome) as ordena$crlf" .
            "  from co_uf                a$crlf" .
            "       inner join co_pais   b on (a.sq_pais   = b.sq_pais)$crlf" .
            "       inner join co_regiao c on (a.sq_regiao = c.sq_regiao)$crlf" .
            "       left join (select x.sq_pais, x.co_uf, count(x.sq_pais) as qtd$crlf" .
            "                    from eo_indicador_afericao   x$crlf" .
            "                         inner join eo_indicador y on (x.sq_eoindicador = y.sq_eoindicador and$crlf" .
            "                                                       y.ativo          = 'S'$crlf" .
            "                                                      )$crlf" .
            "                   where y.cliente = coalesce(to_number($p_restricao),0) -- p_restricao como chave de SIW_CLIENTE$crlf" .
            "                     and x.co_uf   is not null$crlf" .
            "                  group by x.sq_pais, x.co_uf$crlf" .
            "                 )          d on (a.sq_pais = d.sq_pais and$crlf" .
            "                                  a.co_uf   = d.co_uf$crlf" .
            "                                 )$crlf" .
            " where b.sq_pais     = $p_pais$crlf" .
            "   and ($p_restricao  is null or ($p_restricao is not null and d.sq_pais is not null))$crlf" .
            "   and ($p_regiao     is null or ($p_regiao    is not null and a.sq_regiao = $p_regiao))$crlf" .
            "   and ($p_ativo      is null or ($p_ativo     is not null and a.ativo     = $p_ativo))$crlf";

    $l_rs = $sql->getInstanceOf($dbms, $SQL, $params);
    return $l_rs;    
  }  
}    
?>
