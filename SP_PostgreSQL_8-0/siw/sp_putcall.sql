create or replace FUNCTION SP_PutCall
   (p_Operacao  varchar,
    p_chave     numeric,
    p_destino   numeric,
    p_sq_cc     numeric,
    p_contato   varchar,
    p_assunto   varchar,
    p_pessoa    numeric,
    p_fax       varchar,
    p_trabalho  varchar ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_Operacao = 'I' Then
      -- Atualiza a ligação
      update tt_ligacao set
         sq_cc               = p_sq_cc,
         outra_parte_cont    = trim(p_contato),
         assunto             = trim(p_assunto),
         sq_usuario_central  = (select sq_usuario_central from tt_usuario where usuario = p_pessoa),
         fax                 = p_fax,
         trabalho            = p_trabalho
      where sq_ligacao       = p_chave;
   Elsif p_Operacao = 'A' Then
      -- insere o log de transferência
      insert into tt_ligacao_log 
             (sq_ligacao, data,     usuario_origem,       usuario_destino,      observacao)
      (select p_chave, now(),     a.sq_usuario_central, b.sq_usuario_central, ltrim(rtrim(p_assunto))
         from tt_usuario a,
              tt_usuario b
        where a.usuario = p_pessoa
          and b.usuario = p_destino
      );

      -- coloca a ligação para o destinatário da transferência
      update tt_ligacao set
         sq_usuario_central  = (select sq_usuario_central from tt_usuario where usuario = p_destino)
      where sq_ligacao       = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;