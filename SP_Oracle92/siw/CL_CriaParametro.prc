create or replace procedure CL_CriaParametro
   (p_chave      in  number, 
    p_numero_doc out varchar2
   ) is
   w_ano        number(4);
   w_sequencial number(18) := 0;

   cursor c_dados is
      select c.codigo_interno, g.numero_certame, coalesce(g.numero_certame,c.codigo_interno) as codigo, f.sq_unidade, 
             h.sq_lcmodalidade, h.sigla as prefixo, i.sq_unidade as existe,
             coalesce(i.sequencial,0) as sequencial, 
             coalesce(i.ano_corrente,1900) as ano_corrente, 
             coalesce(f.sigla, i.sufixo) as sufixo
        from siw_menu                            a
             inner     join siw_solicitacao      c on (a.sq_menu            = c.sq_menu)
               inner   join siw_tramite          d on (c.sq_siw_tramite     = d.sq_siw_tramite)
               inner   join cl_unidade           e on (c.sq_unidade         = e.sq_unidade)
                 inner join eo_unidade           f on (f.sq_unidade         = coalesce(e.sq_unidade_pai,e.sq_unidade))
               inner   join cl_solicitacao       g on (c.sq_siw_solicitacao = g.sq_siw_solicitacao)
                 inner join lc_modalidade        h on (g.sq_lcmodalidade    = h.sq_lcmodalidade
                                                      )
                 left  join cl_unidade_numeracao i on (f.sq_unidade         = i.sq_unidade and
                                                       h.sq_lcmodalidade    = i.sq_lcmodalidade
                                                      )
       where c.sq_siw_solicitacao = p_chave;
begin
  p_numero_doc := null;
  For crec in c_dados Loop
    -- Verifica se há necessidade de reinicializar o sequencial em função da troca do ano
    If to_char(sysdate,'yyyy') > crec.ano_corrente Then
       w_ano        := to_char(sysdate,'yyyy');
       w_sequencial := 1;
    Else
       w_ano        := crec.ano_corrente;
       w_sequencial := crec.sequencial + 1;
    End If;
  
    If crec.existe is null Then
      insert into cl_unidade_numeracao (sq_unidade, sq_lcmodalidade, sequencial, ano_corrente, sufixo)
      values (crec.sq_unidade, crec.sq_lcmodalidade, w_sequencial, w_ano, crec.sufixo);
    Else
      update cl_unidade_numeracao a
         set a.sequencial   = w_sequencial,
             a.ano_corrente = w_ano
       where a.sq_unidade      = crec.sq_unidade
         and a.sq_lcmodalidade = crec.sq_lcmodalidade;
    End If;
  
    --  Retorna o sequencial a ser usado no lançamento
    p_numero_doc := crec.prefixo||'-';
    p_numero_doc := p_numero_doc || substr(to_char(1000+w_sequencial),2,3)||'/';
    p_numero_doc := p_numero_doc || to_char(w_ano)||'-';
    p_numero_doc := p_numero_doc || crec.sufixo;
  End Loop;
end CL_CriaParametro;
/
