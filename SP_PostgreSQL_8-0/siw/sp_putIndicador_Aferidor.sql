create or replace FUNCTION sp_putIndicador_Aferidor
   (p_operacao         varchar,
    p_usuario          numeric,
    p_chave            numeric,
    p_chave_aux        numeric,
    p_pessoa           numeric,
    p_prazo            varchar,
    p_inicio           date,
    p_fim              date     
   ) RETURNS VOID AS $$
DECLARE
   w_chave  numeric(18);
   w_fim    date;
BEGIN
   -- Configura o término da responsabilidade. Se for prazo indefinido, coloca 31/12/2100
   If p_prazo is not null Then
     If p_prazo = 'N'
        Then w_fim := to_date('31/12/2100','dd/mm/yyyy');
        Else w_fim := p_fim;
     End If;
   End If;
   
   If p_operacao = 'I' or p_operacao = 'C' Then
      -- Gera a nova chave do registro, a partir da sequence
      select sq_eoindicador_aferidor.nextval into w_chave;

      -- Insere registro
      insert into eo_indicador_aferidor
        (sq_eoindicador_aferidor, sq_eoindicador, sq_pessoa, prazo_definido, inicio,   fim)
      values
        (w_chave,                 p_chave,        p_pessoa,  p_prazo,        p_inicio, nvl(p_fim,w_fim));
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update eo_indicador_aferidor
         set sq_eoindicador = p_chave,
             sq_pessoa      = p_pessoa,
             prazo_definido = p_prazo,
             inicio         = p_inicio,
             fim            = nvl(p_fim,w_fim)
       where sq_eoindicador_aferidor = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Recupera o período do registro
      DELETE FROM eo_indicador_aferidor where sq_eoindicador_aferidor = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;