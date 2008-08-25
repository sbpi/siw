alter procedure dbo.sp_putPlanoEstrategico
   (@p_operacao  varchar(1),
    @p_cliente   int           =null,
    @p_chave     int           =null,
    @p_chave_pai int           =null,
    @p_titulo    varchar(2000) =null,
    @p_missao    varchar(2000) =null,
    @p_valores   varchar(2000) =null,
    @p_presente  varchar(2000) =null,
    @p_futuro    varchar(2000) =null,
    @p_inicio    datetime      =null,
    @p_fim       datetime      =null,
    @p_codigo    varchar(30)   =null,
    @p_ativo     varchar(1)    =null,
    @p_heranca   int           =null
   ) as
   declare @w_chave int;
begin
   If @p_operacao = 'I' begin
      
      -- Insere registro
      insert into pe_plano
        (cliente,         sq_plano_pai,    titulo,       missao,       valores, 
         visao_presente,  visao_futuro,    inicio,       fim,          ativo,       
         codigo_externo
        )
      values (
         @p_cliente,      @p_chave_pai,    @p_titulo,    @p_missao,    @p_valores, 
         @p_presente,     @p_futuro,       @p_inicio,    @p_fim,       @p_ativo,
         @p_codigo
        );

      -- Se for cópia de outro plano, herda seus dados
      if @p_heranca is not null begin
         -- Recupera a chave utilizada
         Select @w_chave = @@IDENTITY
        
         -- herda os objetivos estratégicos
         insert into pe_objetivo (cliente, sq_plano, nome, sigla, descricao, ativo, codigo_externo)
         (select                  cliente, @w_chave,  nome, sigla, descricao, ativo, codigo_externo
            from pe_objetivo
           where sq_plano = @p_heranca
         );
         
         -- herda os serviços
         insert into pe_plano_menu (sq_plano, sq_menu) (select @w_chave, sq_menu from pe_plano_menu where sq_plano = @p_heranca);
      end

   end else if @p_operacao = 'A' begin
      -- Altera registro
      update pe_plano
         set sq_plano_pai   = @p_chave_pai,
             titulo         = @p_titulo,
             missao         = @p_missao,
             valores        = @p_valores,
             visao_presente = @p_presente,
             visao_futuro   = @p_futuro,
             inicio         = @p_inicio,
             fim            = @p_fim,
             codigo_externo = @p_codigo
       where sq_plano = @p_chave;
   end else if @p_operacao = 'E' begin
      -- Exclui registro
      delete pe_plano_arq  where sq_plano = @p_chave;
      delete pe_plano_menu where sq_plano = @p_chave;
      delete pe_objetivo   where sq_plano = @p_chave;
      delete pe_plano      where sq_plano = @p_chave;
   end else if @p_operacao = 'T' begin
      -- Ativa registro
      update pe_plano set ativo = 'S' where sq_plano = @p_chave;
   end else if @p_operacao = 'D' begin
      -- Desativa registro
      update pe_plano set ativo = 'N' where sq_plano = @p_chave;
   End
end