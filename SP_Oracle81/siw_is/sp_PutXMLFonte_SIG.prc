create or replace procedure sp_PutXMLFonte_SIG
   (p_chave    in  number   default null,
    p_nome     in  varchar2,
    p_desc     in  varchar2 default null,
    p_observ   in  varchar2 default null,
    p_total    in  varchar2 default null,
    p_ativo    in  varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
begin
   If p_ativo = 'N' Then
      update is_sig_fonte set ativo = 'N' where cd_fonte = p_chave;
   Else
      select count(*) into w_cont from is_sig_fonte a where a.cd_fonte = p_chave;
      If w_cont = 0 
         Then w_operacao := 'I';
         Else w_operacao := 'A';
      End If;
      
      If w_operacao = 'I' Then
         -- Insere registro
         insert into is_sig_fonte (cd_fonte, nome, descricao, observacao, totalizacao, ativo, flag_inclusao)
         values (p_chave, p_nome, Nvl(p_desc,'Não informado.'), p_observ, p_total, 'S', sysdate);
      Else
         -- Altera registro
         update is_sig_fonte set
            nome          = p_nome,
            descricao     = Nvl(p_desc,descricao),
            observacao    = p_observ,
            totalizacao   = p_total,
            ativo         = 'S',
            flag_inclusao = sysdate
          where cd_fonte = p_chave;
      End If;
   End If;
end sp_PutXMLFonte_SIG;
/
