alter function acesso_recurso(@p_recurso int, @p_usuario int) returns int as
/**********************************************************************************
* Nome      : acesso_recurso
* Finalidade: Verificar se o usuário tem acesso a um recurso, de acordo com os parâmetros informados
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      :  02/03/2007, 12:05
* Parâmetros:
*    @p_recurso   : chave primária de EO_RECURSO
*    @p_usuario   : chave de acesso do usuário
* Retorno: campo do tipo bit
*    4: Se o usuário é titular ou substituto da unidade gestora do recurso
*    2: Se o usuário é gestor do sistema ou gestor do módulo que contém o pool de recursos (no endereço do recurso)
*    1: Se o usuário é titular ou substituto de alguma unidade superior à unidade gestora do recurso
*    0: Se o usuário não tem acesso ao recurso
***********************************************************************************/
begin
    Declare @w_gestor_sistema         varchar(1);
    Declare @w_modulo                 numeric(18);
    Declare @w_sq_unidade_gestora     numeric(18);     -- Chave da unidade gestora
    Declare @w_sq_pessoa_titular      numeric(18);     -- Titular da unidade gestora
    Declare @w_sq_pessoa_substituto   numeric(18);     -- Substituto da unidade gestora
    Declare @w_sq_endereco_unidade    numeric(18);     -- Endereço da unidade gestora
    Declare @w_existe                 numeric(18);
 -- Verifica se o recurso e o usuário informados existem
 select @w_existe = count(sq_recurso) from eo_recurso where sq_recurso = @p_recurso;
 If @w_existe = 0 Begin return 0; End
 
 select @w_existe = count(*) from sg_autenticacao where ativo = 'S' and sq_pessoa = @p_usuario;
 If @w_existe = 0 Begin return 0; End
 
 -- Verifica se o pool de recursos está disponível para o cliente do usuário
 select @w_existe = count(*)
   from siw_menu             a 
        inner join co_pessoa b on (a.sq_pessoa = b.sq_pessoa_pai) 
  where lower(a.link) like '%recurso.php?par=inicial' 
    and b.sq_pessoa = @p_usuario;
 
 If @w_existe = 0 Begin 
    Return 0; 
 End Else Begin
    select @w_modulo = a.sq_modulo
      from siw_menu             a 
           inner join co_pessoa b on (a.sq_pessoa = b.sq_pessoa_pai) 
     where lower(a.link) like '%recurso.php?par=inicial' 
       and b.sq_pessoa = @p_usuario;
 End
 
 -- Recupera os dados do usuário e do recurso
 select @w_gestor_sistema       = p.gestor_sistema,     
        @w_sq_unidade_gestora   = a.unidade_gestora,
        @w_sq_endereco_unidade  = b.sq_pessoa_endereco,  
        @w_sq_pessoa_titular    = b1.sq_pessoa,
        @w_sq_pessoa_substituto = b2.sq_pessoa
   from sg_autenticacao                    p,
        eo_recurso                         a
        inner  join eo_unidade             b  on (a.unidade_gestora        = b.sq_unidade)
          left join eo_unidade_resp        b1 on (b.sq_unidade             = b1.sq_unidade and
                                                  b1.tipo_respons          = 'T'           and
                                                  b1.fim                   is null
                                                )
          left join eo_unidade_resp        b2 on (b.sq_unidade             = b2.sq_unidade and
                                                  b2.tipo_respons          = 'S'           and
                                                  b2.fim                   is null
                                                )
  where a.sq_recurso = @p_recurso
    and p.sq_pessoa  = @p_usuario;

 -- Verifica se o usuário é titular ou substituto da unidade gestora do recurso
 If @w_sq_pessoa_titular = @p_usuario or @w_sq_pessoa_substituto = @p_usuario Begin return 4; End

 -- Verifica se o usuário é gestor do sistema ou gestor do módulo
 If @w_gestor_sistema = 'S' 
    return 2;
 Else Begin
    select @w_existe = count(sq_pessoa)
      from sg_pessoa_modulo a 
     where a.sq_modulo          = @w_modulo 
       and a.sq_pessoa          = @p_usuario 
       and a.sq_pessoa_endereco = @w_sq_endereco_unidade;
    If @w_existe > 0 Begin return 2; End
 End
 
 -- Verifica se o usuário é titular ou substituto de alguma unidade acima da unidade gestora do recurso
 select @w_existe = count(sq_pessoa)
   from eo_unidade_resp a
  where a.sq_pessoa  = @p_usuario
    and a.fim        is null
    and a.sq_unidade in (select chave from dbo.sp_fGetUnitPaiList(@w_sq_unidade_gestora,'UP'));

 If @w_existe > 0 Begin return 1; End
 
 return 0;
end
