create or replace function CalculaValorContrato(p_chave in number, p_aditivo in number default null) return float is
  Result        float      := 0;
  w_existe      number(18);
  w_aditivo     number(18) := p_aditivo;
  w_inicio_con  siw_solicitacao.inicio%type;
  w_fim_con     siw_solicitacao.fim%type;
  w_inicio_ori  ac_acordo.inicio%type;
  w_fim_ori     ac_acordo.fim%type;
  w_prorrogacao ac_acordo_aditivo.prorrogacao%type;
  w_inicio_adi  ac_acordo_aditivo.inicio%type;
  w_fim_adi     ac_acordo_aditivo.fim%type;
  w_tipo_prazo  ac_tipo_acordo.prazo_indeterm%type;
  
  cursor c_dados is
     select a.sq_siw_solicitacao, sum(b.valor) as valor
       from siw_solicitacao                a
            inner   join ac_acordo_parcela b on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
            inner   join ac_acordo         x on (a.sq_siw_solicitacao  = x.sq_siw_solicitacao)
              inner join ac_tipo_acordo    y on (x.sq_tipo_acordo      = y.sq_tipo_acordo)
              inner join siw_solicitacao   z on (x.sq_siw_solicitacao  = z.sq_siw_solicitacao)
              inner join ac_parametro      w on (x.cliente             = w.cliente)
      where a.sq_siw_solicitacao = p_chave
        and (y.prazo_indeterm    = 'S' or
             (w_aditivo          is     null and b.vencimento between z.inicio and (z.fim+w.dias_pagamento)) or
             (w_aditivo          is not null and b.sq_acordo_aditivo = w_aditivo)
            )
     group by a.sq_siw_solicitacao;
begin
  -- Verifica se o contrato existe
  select count(*) into w_existe
    from siw_solicitacao             a 
         inner join siw_menu         b on (a.sq_menu            = b.sq_menu)
         inner join ac_acordo        c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
           inner join ac_tipo_acordo d on (c.sq_tipo_acordo     = d.sq_tipo_acordo)
   where a.sq_siw_solicitacao = p_chave;
  
  -- Se não existir contrato com a chave informada, retorna nulo
  If w_existe = 0 Then return null; End If;
  
  -- Caso contrário, recupera dados do contrato
  select a.inicio,     a.fim,     c.inicio,     c.fim,     d.prazo_indeterm 
    into w_inicio_con, w_fim_con, w_inicio_ori, w_fim_ori, w_tipo_prazo
    from siw_solicitacao             a 
         inner join ac_acordo        c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
           inner join ac_tipo_acordo d on (c.sq_tipo_acordo     = d.sq_tipo_acordo)
   where a.sq_siw_solicitacao = p_chave;

  If w_aditivo is not null Then
     -- Recupera os dados do aditivo informado e do contrato original
     select a.inicio,     a.fim,     a.prorrogacao 
       into w_inicio_adi, w_fim_adi, w_prorrogacao
       from ac_acordo_aditivo    a 
      where a.sq_acordo_aditivo = w_aditivo;
     
     If w_prorrogacao = 'N' Then
        If w_inicio_adi between w_inicio_ori and w_fim_ori Then
           -- Se aditivo ligado ao contrato original, trata o período do contrato original
           w_aditivo := null;
        Else 
           -- Caso contrário, trata o período do aditivo de prorrogação
           select sq_acordo_aditivo into w_aditivo 
             from ac_acordo_aditivo
            where sq_siw_solicitacao = p_chave
              and prorrogacao        = 'S'
              and w_inicio_adi between inicio and fim;
        End If;
     End If;
  Elsif w_tipo_prazo = 'N' Then
     -- Se pagamento não continuado, recupera a prorrogação mais recente
     select count(*) into w_existe from ac_acordo_aditivo where sq_siw_solicitacao = p_chave and prorrogacao = 'S';
     
     If w_existe = 0 Then
        w_aditivo := null;
     Else
        select max(sq_acordo_aditivo) into w_aditivo from ac_acordo_aditivo where sq_siw_solicitacao = p_chave and prorrogacao = 'S';
     End If;
  End If;
  
  for crec in c_dados loop Result := crec.valor; end loop;
  Return Result;
end CalculaValorContrato;
/
