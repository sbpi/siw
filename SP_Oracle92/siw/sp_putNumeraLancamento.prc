create or replace procedure SP_PutNumeraLancamento
   (p_operacao                 in  varchar2,
    p_chave                    in  number
   ) is
   
   i  number(10);
   
   cursor c_dados is
      select a.sq_financeiro, a.quitacao, a.conclusao,
             (select count(*) from vw_projeto_financeiro w where w.sq_financeiro = a.sq_financeiro) qtd
        from (select distinct sq_financeiro, quitacao, conclusao
                from vw_projeto_financeiro 
               where sq_projeto = nvl(p_chave,0)
                 and sg_tramite = 'AT' 
                 and cd_financeiro_externo is null
             ) a
      order by quitacao, conclusao;

begin
   -- Só executa se for informado um projeto
   If p_chave is null Then
      Return;
   End If;
   
   If p_operacao = 'E' Then
      -- Apaga a numeração externa de todos os lançamentos do projeto informado
      update siw_solicitacao
         set codigo_externo = null
      where sq_siw_solicitacao in (select distinct sq_financeiro
                                     from vw_projeto_financeiro 
                                    where sq_projeto = nvl(p_chave,0)
                                      and sg_tramite = 'AT'
                                  );
   Elsif p_operacao = 'I' Then
      select nvl(max(to_number(replace(codigo_externo,'.'))),0)
        into i
        from siw_solicitacao
      where sq_siw_solicitacao in (select distinct sq_financeiro
                                     from vw_projeto_financeiro 
                                    where sq_projeto = nvl(p_chave,0)
                                      and sg_tramite = 'AT'
                                  );
      for crec in c_dados loop
          i := i + 1;
          update siw_solicitacao 
             set codigo_externo = case crec.qtd when 1 then to_char(i) else to_char(i)||'.' end
          where sq_siw_solicitacao = crec.sq_financeiro;
      end loop;
   End If;
end SP_PutNumeraLancamento;
/
