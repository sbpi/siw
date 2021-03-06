SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SG_GeraMenu(@p_sq_cliente int) as
Begin
  Declare @w_chave int, @w_origem int, @w_destino int
  Declare @i       int

  Declare @sq_modulo      int,         @ativo         varchar(1),  @nome           varchar(40),   @finalidade       varchar(200)
  Declare @link           varchar(60), @tramite       varchar(1),  @ordem          int,           @ultimo_nivel     varchar(1)
  Declare @p1             int,         @p2            int,         @p3             int,           @p4               int
  Declare @sigla          varchar(10), @imagem        varchar(60), @externo        varchar(1),    @descentralizado  varchar(1)
  Declare @target         varchar(15), @emite_os      varchar(1),  @vinculacao     varchar(1),    @consulta_opiniao varchar(1)
  Declare @envia_email    varchar(1),  @data_hora     varchar(1),  @descricao      varchar(1),    @exibe_relatorio  varchar(1)
  Declare @justificativa  varchar(1),  @arquivo_proced varchar(60),@como_funciona  varchar(1000), @envia_dia_util   varchar(1)
  Declare @sq_segmento_menu int,       @sq_seg_menu_pai int

  Declare @w_menu table (
      chave                 int,
      sq_menu_destino       int,
      sq_menu_origem        int,
      sq_menu_pai_origem    int
     )
  Declare @w_menu_pai table (
      segmento              int,
      chave                 int
     )
  
  Declare c_Segmento_Menu cursor for
     select a.sq_modulo,     a.ativo,          a.nome,           a.finalidade,       a.link,        a.tramite,   a.ordem,     a.ultimo_nivel,
            a.p1,            a.p2,             a.p3,             a.p4,               a.sigla,       a.imagem,    a.externo,   a.descentralizado,
            a.target,        a.emite_os,       a.vinculacao,     a.consulta_opiniao, a.envia_email, a.data_hora, a.descricao, a.exibe_relatorio,
            a.justificativa, a.arquivo_proced, a.como_funciona,  a.envia_dia_util,   a.sq_segmento_menu, a.sq_seg_menu_pai
       from dm_segmento_menu   a,
            siw_cliente_modulo b,
            co_pessoa_segmento c
      where b.sq_pessoa      = c.sq_pessoa
        and a.sq_segmento    = c.sq_segmento
        and a.sq_modulo      = b.sq_modulo
        and b.sq_pessoa      = @p_sq_cliente
     order by IsNull(a.sq_seg_menu_pai,0),a.ordem
     
  Set @i = 0

  Open c_Segmento_Menu
  Fetch next from c_Segmento_Menu into
     @sq_modulo,     @ativo,          @nome,           @finalidade,       @link,        @tramite,   @ordem,     @ultimo_nivel,
     @p1,            @p2,             @p3,             @p4,               @sigla,       @imagem,    @externo,   @descentralizado,
     @target,        @emite_os,       @vinculacao,     @consulta_opiniao, @envia_email, @data_hora, @descricao, @exibe_relatorio,
     @justificativa, @arquivo_proced, @como_funciona,  @envia_dia_util,   @sq_segmento_menu, @sq_seg_menu_pai


  While @@Fetch_Status = 0
  Begin
     -- Insere registro no menu do cliente
     insert into siw_menu
           (sq_modulo,          sq_pessoa,                  ativo,
            nome,               finalidade,                 link,              sq_unid_executora,
            tramite,            ordem,                      ultimo_nivel,      p1, 
            p2,                 p3,                         p4,                sigla, 
            imagem,             descentralizado,            externo,           target,
            emite_os,           consulta_opiniao,           envia_email,       exibe_relatorio,
            como_funciona,      arquivo_proced,             vinculacao,        data_hora,
            envia_dia_util,     descricao,                  justificativa
           )
    values (
            @sq_modulo,         @p_sq_cliente,              @ativo,
            @nome,              @finalidade,                @link,             null,
            @tramite,           @ordem,                     @ultimo_nivel,     @p1,
            @p2,                @p3,                        @p4,               @sigla,
            @imagem,            @descentralizado,           @externo,          @target,
            @emite_os,          @consulta_opiniao,          @envia_email,      @exibe_relatorio,
            @como_funciona,     @arquivo_proced,            @vinculacao,       @data_hora,
            @envia_dia_util,    @descricao,                 @justificativa
           )

     -- Recupera chave primária
     Set @w_chave = @@IDENTITY
     
     -- Guarda pai do registro original
     Set @i = @i + 1
     insert into @w_menu values (@i, @w_chave, @sq_segmento_menu, @sq_seg_menu_pai)
     
     insert into @w_menu_pai values (@sq_segmento_menu, @w_chave)
          
     Fetch next from c_Segmento_Menu into
        @sq_modulo,     @ativo,          @nome,           @finalidade,       @link,        @tramite,   @ordem,     @ultimo_nivel,
        @p1,            @p2,             @p3,             @p4,               @sigla,       @imagem,    @externo,   @descentralizado,
        @target,        @emite_os,       @vinculacao,     @consulta_opiniao, @envia_email, @data_hora, @descricao, @exibe_relatorio,
        @justificativa, @arquivo_proced, @como_funciona,  @envia_dia_util,   @sq_segmento_menu, @sq_seg_menu_pai
  End
  Close c_Segmento_Menu
  Deallocate c_Segmento_Menu
  
  -- Acerta o vínculo entre os registros
  Set @i = 0
  While @i <= (select count(*) from @w_menu)
  Begin
     select @w_origem = sq_menu_pai_origem, @w_destino = sq_menu_destino
       from @w_menu
      where chave = @i
     If @w_origem is not null Begin
        update siw_menu
           set sq_menu_pai = (select chave from @w_menu_pai where segmento = @w_origem)
         where sq_menu     = @w_destino
     End
     Set @i = @i + 1
  End
End



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

