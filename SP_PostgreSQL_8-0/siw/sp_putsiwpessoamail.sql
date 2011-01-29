create or replace FUNCTION SP_PutSiwPessoaMail
   (p_operacao           varchar,
    p_pessoa             numeric,
    p_menu               numeric,
    p_alerta             varchar,
    p_tramitacao         varchar,
    p_conclusao          varchar,
    p_responsabilidade   varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro em SG_PESSOA_MAIL, para cada serviço que conténha a opção
      insert into sg_pessoa_mail (sq_pessoa_mail,         sq_pessoa, sq_menu, alerta_diario, tramitacao,
                                  conclusao,              responsabilidade) 
                          values (nextVal('sq_pessoa_mail'), p_pessoa,  p_menu, p_alerta,       p_tramitacao,
                                  p_conclusao,            p_responsabilidade);
   Elsif p_operacao = 'E' Then
      -- Remove a permissão
       DELETE FROM sg_pessoa_mail
        where sq_pessoa = p_pessoa;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;