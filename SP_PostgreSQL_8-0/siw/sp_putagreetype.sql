create or replace FUNCTION SP_PutAgreeType
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_chave_pai                 numeric,
    p_cliente                   numeric,
    p_nome                      varchar,
    p_sigla                     varchar,
    p_modalidade                varchar,
    p_prazo_indeterm            varchar,
    p_pessoa_juridica           varchar,
    p_pessoa_fisica             varchar,
    p_idec                      varchar,
    p_ativo                     varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into ac_tipo_acordo (
              sq_tipo_acordo, sq_tipo_acordo_pai,  cliente,         nome,          sigla, 
              modalidade,     prazo_indeterm,      pessoa_juridica, pessoa_fisica, exibe_idec, ativo)
      (select sq_tipo_acordo.nextval,
              p_chave_pai,
              p_cliente,
              trim(p_nome),
              trim(p_sigla),
              p_modalidade,                 
              p_prazo_indeterm,
              p_pessoa_juridica,
              p_pessoa_fisica,
              p_idec,
              p_ativo
        
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update ac_tipo_acordo set
         sq_tipo_acordo_pai   = p_chave_pai,
         nome                 = trim(p_nome),
         sigla                = trim(p_sigla),
         modalidade           = p_modalidade,
         prazo_indeterm       = p_prazo_indeterm,
         pessoa_juridica      = p_pessoa_juridica,
         pessoa_fisica        = p_pessoa_fisica,
         exibe_idec           = p_idec
      where sq_tipo_acordo    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM ac_tipo_acordo where sq_tipo_acordo = p_chave;
   Elsif p_operacao = 'T' Then
      -- Ativa o registro
      update ac_tipo_acordo set ativo = 'S' where sq_tipo_acordo = p_chave;
   Elsif p_operacao = 'D' Then
      -- Desativa o registro
      update ac_tipo_acordo set ativo = 'N' where sq_tipo_acordo = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;