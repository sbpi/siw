<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/sp/db_exec.php');
/**
* class sp_getCountryList
*
* { Description :- 
*    Recupera os países existentes.
* }
*/

class db_getCountryList {
   function getInstanceOf($dbms, $p_restricao, $p_nome, $p_ativo, $p_sigla) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getCountryList';
     $params=array("p_restricao"=>array($p_restricao,   B_VARCHAR,     30),
                   "p_nome"     =>array($p_nome,        B_VARCHAR,     60),
                   "p_ativo"    =>array($p_ativo,       B_VARCHAR,      1),
                   "p_sigla"    =>array($p_sigla,       B_VARCHAR,      3),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
                  );

     $sql = new db_exec; $par = $sql->normalize($params); extract($par,EXTR_OVERWRITE);

     $SQL = "select a.sq_pais, a.nome, coalesce(a.sigla,'-') as sigla, a.ddi, a.ativo, a.padrao,$crlf" .
            "             case a.ativo  when 'S' then 'Sim' else 'Não' end as ativodesc,$crlf" .
            "             case a.padrao when 'S' then 'Sim' else 'Não' end as padraodesc,$crlf" .
            "             case a.continente when 1 then 'América'$crlf" .
            "                               when 2 then 'Europa'$crlf" .
            "                               when 3 then 'Ásia'$crlf" .
            "                               when 4 then 'África'$crlf" .
            "                               else        'Oceania'$crlf" .
            "             end as nm_continente$crlf" .
            "        from co_pais              a$crlf" .
            "             left join (select x.sq_pais, count(x.sq_pais) as qtd$crlf" .
            "                          from eo_indicador_afericao   x$crlf" .
            "                               inner join eo_indicador y on (x.sq_eoindicador = y.sq_eoindicador and$crlf" .
            "                                                             y.ativo          = 'S'$crlf" .
            "                                                            )$crlf" .
            "                         where to_char(y.cliente) = coalesce($p_nome,'0') -- $p_nome como chave de SIW_CLIENTE$crlf" .
            "                           and x.sq_pais is not null$crlf" .
            "                        group by x.sq_pais$crlf" .
            "                       )          b on (a.sq_pais  = b.sq_pais)$crlf" .
            "       where ($p_restricao is null or ($p_restricao = 'ATIVO'        and a.ativo = 'S')$crlf" .
            "                                  or ($p_restricao = 'NOMEBRASIL'   and a.nome = 'Brasil')$crlf" .
            "                                  or ($p_restricao = 'NOMEFRANCA'   and a.nome = 'França')$crlf" .
            "                                  or ($p_restricao = 'BRASILFRANCA' and (a.nome = 'Brasil' or a.nome = 'França'))$crlf" .
            "                                  or ($p_restricao = 'INDICADOR')$crlf" .
            "                                  or ($p_restricao like 'CONTINENTE%' and a.continente = ".str_replace('CONTINENTE','',$p_restricao)."))$crlf" .
            "         and ((coalesce($p_restricao,'-')  = 'INDICADOR' and b.sq_pais is not null) or$crlf" .
            "              (coalesce($p_restricao,'-') <> 'INDICADOR' and$crlf" .
            "               ($p_nome  is null or ($p_nome is not null and acentos(a.nome) like '%'".C."acentos($p_nome)".C."'%'))$crlf" .
            "              )$crlf" .
            "             )$crlf" .
            "         and ($p_ativo is null or ($p_ativo is not null and a.ativo = $p_ativo))$crlf" .
            "         and ($p_sigla is null or ($p_sigla is not null and a.sigla = $p_sigla))$crlf";


    $l_rs = $sql->getInstanceOf($dbms, $SQL, $params);
    return $l_rs;
  }
}    
?>
