create or replace procedure SP_PutPD_Fatura
   (p_operacao            in  varchar2,
    p_chave               in  number    default null,
    p_arquivo             in  number    default null,
    p_agencia             in  number    default null,
    p_tipo                in  number    default null,
    p_numero              in  varchar2  default null,
    p_inicio              in  date      default null,
    p_fim                 in  date      default null,
    p_emissao             in  date      default null,
    p_vencimento          in  date      default null,
    p_valor               in  number    default null,
    p_registros           in  number    default null,
    p_importados          in  number    default null,
    p_rejeitados          in  number    default null,
    p_chave_nova          out number
   ) is
   w_chave  number(18);
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_fatura_agencia.nextval into w_chave from dual;
      
      -- Insere registro na tabela de bilhetes
      insert into pd_fatura_agencia
        (sq_fatura_agencia, sq_arquivo_eletronico, agencia_viagem, numero,   inicio_decendio, fim_decendio, emissao,   vencimento,   valor,
         registros,         importados,            rejeitados,     tipo)
      values
        (w_chave,           p_arquivo,             p_agencia,      p_numero, p_inicio,        p_fim,        p_emissao, p_vencimento, p_valor, 
         p_registros,       p_importados,          p_rejeitados,   p_tipo);
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;

end SP_PutPD_Fatura;
/
