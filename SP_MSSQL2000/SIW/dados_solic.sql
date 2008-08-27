alter function dados_solic(@p_chave int) returns varchar(2000) as
/**********************************************************************************
* Nome      : dados_solic
* Finalidade: Recuperar informa��es de uma solicita��o
* Autor     : Alexandre Vinhadelli Papad�polis
* Data      : 21/06/2007, 12:30
* Par�metros:
*    @p_chave : chave prim�ria de SIW_SOLICITACAO
* Retorno: se a solicita��o n�o existir, retorna nulo
*          se a solicita��o existir, retorna string contendo informa��es sobre ela.
*          A string cont�m v�rios peda�os separados por |@|
*          1  - string para exibi��o em listagens, composta da sigla do m�dulo e do c�digo da solicita��o
*          2  - codigo da solicita��o
*          3  - titulo da solicita��o
*          4  - siw_menu.sq_menu - chave do menu ao qual a solicita��o est� ligada ()
*          5  - siw_menu.nome    - nome do menu
*          6  - siw_menu.sigla   - sigla do menu
*          7  - siw_menu.p1      - valor de p1
*          8  - siw_menu.p2      - valor de p2
*          9  - siw_menu.p3      - valor de p3
*          10 - siw_menu.p4      - valor de p4
*          11 - siw_menu.link    - link para a rotina de visualiza��o
*          12 - siw_modulo.sigla - sigla do m�dulo da solicita��o
***********************************************************************************/
Begin
  Declare @Result varchar(2000);
  Set @Result = null;
  Declare @w_reg     int;

  Declare @codigo    varchar(255);
  Declare @titulo    varchar(255);
  Declare @sq_menu   varchar(255);
  Declare @nome      varchar(255);
  Declare @sigla     varchar(255);
  Declare @p1        varchar(255);
  Declare @p2        varchar(255);
  Declare @p3        varchar(255);
  Declare @p4        varchar(255);
  Declare @link      varchar(255);
  Declare @sg_modulo varchar(255);


  Declare c_dados cursor for
     select a.sq_menu, a.nome, a.sigla, a.p1, a.p2, a.p3, a.p4,
            coalesce(a1.link, replace(lower(a.link),'inicial','visual')) as link,
            a2.sigla as sg_modulo,
            b.sq_siw_solicitacao,
            coalesce(b.codigo_interno, cast(b.sq_siw_solicitacao as varchar(255))) as codigo,
            coalesce(b.titulo, c.destino, d.assunto, b.descricao, b.justificativa) as titulo
       from siw_menu                             a
            left  join siw_menu                  a1 on (a.sq_menu             = a1.sq_menu_pai and
                                                        a1.sigla              like '%VISUAL%'
                                                       )
            inner join siw_modulo                a2 on (a.sq_modulo           = a2.sq_modulo)
            inner join siw_solicitacao           b  on (a.sq_menu             = b.sq_menu)
            left  join sr_solicitacao_transporte c  on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
            left  join gd_demanda                d  on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
      where b.sq_siw_solicitacao = @p_chave;

  if @p_chave is not null Begin
    
     -- Verifica se a solicita��o existe e, se existir, recupera seus dados
     select @w_reg = count(sq_siw_solicitacao) from siw_solicitacao where sq_siw_solicitacao = @p_chave;
     if @w_reg > 0 Begin
     While  @@Fetch_Status = 0
     Begin
     Set @Result = @nome + ': ' + @codigo + '|@|' + @codigo + '|@|' + @titulo + '|@|' + @sq_menu + '|@|' + @nome + '|@|' + @sigla + '|@|' + @p1 + '|@|' + @p2 + '|@|' + @p3 + '|@|' + @p4 + '|@|' + @link + '|@|' + @sg_modulo;
     Fetch next from c_dados into @w_reg
	 End
    	 end
	  end
	  return(@Result);
end
