create or replace FUNCTION sp_putPlanoEstrategico
   (p_operacao   varchar,
    p_cliente    numeric,
    p_chave      numeric,
    p_chave_pai  numeric,
    p_titulo     varchar,
    p_missao     varchar,
    p_valores    varchar,
    p_presente   varchar,
    p_futuro     varchar,
    p_inicio     date,
    p_fim        date,
    p_codigo     varchar,
    p_ativo      varchar,
    p_heranca    numeric   
   ) RETURNS VOID AS $$
DECLARE
   
   w_chave   numeric(18);
BEGIN
   If p_operacao = 'I' Then
      -- recupera a próxima chave
      select sq_plano.nextval into w_chave;
      
      -- Insere registro
      insert into pe_plano
        (sq_plano,         cliente,        sq_plano_pai,          titulo,      missao,      valores, 
         visao_presente,   visao_futuro,   inicio,                fim,         ativo,       codigo_externo)
      values (
         w_chave,          p_cliente,      p_chave_pai,           p_titulo,    p_missao,    p_valores, 
         p_presente,       p_futuro,       p_inicio,              p_fim,       p_ativo,     p_codigo
        );
        
      -- Se for cópia de outro plano, herda seus dados
      if p_heranca is not null then
         -- herda os objetivos estratégicos
         insert into pe_objetivo (sq_peobjetivo, cliente, sq_plano, nome, sigla, descricao, ativo, codigo_externo)
         (select sq_peobjetivo.nextval,          cliente, w_chave,  nome, sigla, descricao, ativo, codigo_externo
            from pe_objetivo
           where sq_plano = p_heranca
         );
         
         -- herda os serviços
         insert into pe_plano_menu (sq_plano, sq_menu) (select w_chave, sq_menu from pe_plano_menu where sq_plano = p_heranca);
      end if;

   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pe_plano
         set sq_plano_pai   = p_chave_pai,
             titulo         = p_titulo,
             missao         = p_missao,
             valores        = p_valores,
             visao_presente = p_presente,
             visao_futuro   = p_futuro,
             inicio         = p_inicio,
             fim            = p_fim,
             codigo_externo = p_codigo
       where sq_plano = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pe_plano_arq  where sq_plano = p_chave;
      DELETE FROM pe_plano_menu where sq_plano = p_chave;
      DELETE FROM pe_objetivo   where sq_plano = p_chave;
      DELETE FROM pe_plano      where sq_plano = p_chave;
   Elsif p_operacao = 'T' Then
      -- Ativa registro
      update pe_plano set ativo = 'S' where sq_plano = p_chave;
   Elsif p_operacao = 'D' Then
      -- Desativa registro
      update pe_plano set ativo = 'N' where sq_plano = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;