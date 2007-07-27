create or replace procedure SP_PutContasRegistro
   (p_operacao            in  varchar2,
    p_chave               in number    default null,
    p_contas_cronograma   in number    default null,
    p_prestacao_contas    in number    default null,
    p_pendencia           in varchar2  default null,
    p_observacao          in varchar2  default null
   ) is
   w_chave    number(18);
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_contas_registro.nextval into w_chave from dual;
      
      -- Insere registro na tabela de registros de cronogramas
      Insert Into siw_contas_registro
         ( sq_contas_registro,   sq_contas_cronograma, sq_prestacao_contas, pendencia,   observacao)
      Values
         ( w_chave,              p_contas_cronograma,  p_prestacao_contas,  p_pendencia, p_observacao);
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de cronograma da rubrica
      delete siw_contas_registro where sq_contas_cronograma = p_contas_cronograma;
   End If;
end SP_PutContasRegistro;
/
