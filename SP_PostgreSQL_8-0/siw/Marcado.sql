create or replace function MARCADO
  (p_menu     in numeric,
   p_pessoa   in numeric,
   p_endereco in numeric,
   p_tramite  in numeric,
   p_fase     in numeric
  ) returns numeric as $$
/**********************************************************************************
* Nome      : Marcado
* Finalidade: Verificar se o usu�rio t�m acesso a uma op��o, de acordo com os par�metros informados
* Autor     : Alexandre Vinhadelli Papad�polis
* Data      :  28/03/2003, 21:24
* Par�metros:
*    p_menu   : chave prim�ria de siw_menu
*    p_pessoa   : chave de acesso do usu�rio
*    p_endere�o : opcional. Se informado, restringe a busca a este endere�o
*    p_tramite  : opcional. Se informado, restringe a busca a este tr�mite
*    p_fase     : opcional. Se informado, restringe a busca a esta fase
* Retorno: 
*    3: Se a op��o for de acesso geral
*    2: Se o usu�rio for um gestor de m�dulo, ou do sistema, ou ainda de seguran�a
*    1: Se o usu�rio j� tiver esta permiss�o concedida para o endere�o informado
*    0: Se o usu�rio n�o tem permiss�o para a op��o informada
***********************************************************************************/
declare
  w_sq_servico          varchar(1);
  w_sq_situacao_servico numeric(10);
  w_sg_modulo           varchar(10);
  w_sq_modulo           numeric(18);
  w_gestor_seguranca    varchar(10);
  w_gestor_sistema      varchar(10);
  w_acesso_geral        varchar(10);
  w_vinculo             numeric(10);
  w_existe              numeric(10);
  Result                numeric := 0;
begin

 -- Recupera as informa��es da op��o
 select a.tramite,     c.sq_modulo, c.sigla,     gestor_seguranca,   gestor_sistema,   a.acesso_geral, d.sq_tipo_vinculo
   into w_sq_servico,  w_sq_modulo, w_sg_modulo, w_gestor_seguranca, w_gestor_sistema, w_acesso_geral, w_vinculo
   from siw_menu        a,
        sg_autenticacao b,
        siw_modulo      c,
        co_pessoa       d
  where a.sq_modulo = c.sq_modulo
    and b.sq_pessoa = d.sq_pessoa
    and a.sq_menu   = p_menu
    and b.sq_pessoa = p_pessoa;
  
 If w_sq_servico = 'N' Then -- Se a op��o n�o for vinculada a servi�o
    -- Verifica se o usu�rio � gestor do m�dulo
    If (w_gestor_sistema = 'S'   and w_sg_modulo <> 'SG') or
       (w_gestor_seguranca = 'S' and w_sg_modulo = 'SG') 
    Then
       Result := 2;
    Else
      -- Verifica se o usu�rio � gestor do m�dulo da op��o
      select count(*) into w_existe
        from sg_pessoa_modulo a
       where a.sq_pessoa = p_pessoa
         and a.sq_modulo = w_sq_modulo
         and (p_endereco is null or (p_endereco is not null and a.sq_pessoa_endereco = p_endereco));
      If w_existe > 0 Then
         Result := 2;
      Else
         -- Verifica se o USU�RIO tem permiss�o concedida para a op��o
          select count(*) into w_existe from sg_pessoa_menu a
          where a.sq_pessoa = p_pessoa
            and a.sq_menu   = p_menu;
          If w_existe > 0 Then Result := 1; Else Result := 0; End If;
    
         -- Verifica se o PERFIL do usu�rio tem permiss�o concedida para a op��o
         If Result = 0 Then
             select count(*) into w_existe from sg_perfil_menu a, co_tipo_vinculo b, co_pessoa c
              where a.sq_tipo_vinculo = b.sq_tipo_vinculo
                and b.sq_tipo_vinculo = c.sq_tipo_vinculo
                and c.sq_pessoa       = p_pessoa
                and a.sq_menu         = p_menu;
             If w_existe > 0 Then Result := 1; Else Result := 0; End If;
         End If;
      End If;
    End If;
 Else -- Se a op��o for vinculada a servi�o
    -- Recupera o c�digo da situa��o de cadastramento
    select sq_siw_tramite
       into w_sq_situacao_servico
      from siw_tramite
     where sq_menu = p_menu
       and sigla   = 'CI';

    -- Se o tr�mite n�o foi informado, verifica se o usu�rio tem alguma permiss�o ao m�dulo
    If p_tramite is null Then
       If (w_gestor_sistema   = 'S' and w_sg_modulo <> 'SG') or
          (w_gestor_seguranca = 'S' and w_sg_modulo = 'SG')
       Then
          Result := 2;
       Else
          -- Verifica se o usu�rio � gestor do m�dulo da op��o
          select count(*) into w_existe
            from sg_pessoa_modulo a
           where a.sq_pessoa = p_pessoa
             and a.sq_modulo = w_sq_modulo
             and (p_endereco is null or (p_endereco is not null and a.sq_pessoa_endereco = p_endereco));
          If w_existe > 0 Then
             Result := 2;
          Else
             -- Verifica se o usu�rio tem alguma permiss�o concedida para a op��o 
             -- ou se a op��o � de acesso geral
             select count(*) into w_existe
             from sg_tramite_pessoa a,
                  sg_autenticacao   b,
                  siw_menu          c,
                  siw_tramite       d
             where a.sq_pessoa      = b.sq_pessoa
               and a.sq_siw_tramite = d.sq_siw_tramite
               and d.sq_menu        = c.sq_menu
               and c.sq_menu        = p_menu
               and b.sq_pessoa      = p_pessoa;
             If w_existe > 0 Then Result := 1; Else Result := 0; End If;
          End If;
             
       End If;
    Else -- Se o tr�mite foi informado
       If w_sq_situacao_servico = p_tramite and
          ((w_gestor_sistema = 'S'   and w_sg_modulo <> 'SG') or
           (w_gestor_seguranca = 'S' and w_sg_modulo = 'SG')
          )
       Then
          Result := 2;
       Else
          -- Verifica se o usu�rio tem alguma permiss�o concedida para a op��o
          select count(*)
          into w_existe
          from sg_tramite_pessoa   a,
               sg_autenticacao     b,
               siw_menu            c,
               siw_tramite         d
          where a.sq_pessoa      = b.sq_pessoa
            and a.sq_siw_tramite = d.sq_siw_tramite
            and d.sq_menu        = c.sq_menu   
            and c.sq_menu        = p_menu
            and b.sq_pessoa      = p_pessoa
            and d.sq_siw_tramite = p_tramite;
          If w_existe > 0 Then
             Result := 1;
          Else
             Result := 0;
          End If;
       End If;
    End If;
 End If;
 return(Result);
end; $$ language plpgsql volatile;
