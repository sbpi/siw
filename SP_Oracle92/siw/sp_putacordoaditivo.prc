create or replace procedure SP_PutAcordoAditivo
   (p_operacao            in  varchar2,
    p_chave               in  number   default null,
    p_chave_aux           in  number   default null,
    p_protocolo           in  number   default null,
    p_codigo              in  varchar2 default null,
    p_objeto              in  varchar2 default null,
    p_inicio              in  date     default null,
    p_fim                 in  date     default null,
    p_duracao             in  number   default null,
    p_documento_origem    in  varchar2 default null,
    p_documento_data      in  date     default null,
    p_variacao_valor      in  number   default null,
    p_prorrogacao         in  varchar2 default null,
    p_revisao             in  varchar2 default null,
    p_acrescimo           in  varchar2 default null,
    p_supressao           in  varchar2 default null,
    p_observacao          in  varchar2 default null,
    p_valor_inicial       in  number   default null,
    p_parcela_inicial     in  number   default null,
    p_valor_reajuste      in  number   default null,
    p_parcela_reajustada  in  number   default null,
    p_valor_acrescimo     in  number   default null,
    p_parcela_acrescida   in  number   default null,
    p_sq_cc               in  number   default null,
    p_chave_nova          out number
   ) is
   w_inicio         ac_acordo_aditivo.inicio%type := p_inicio;
   w_prorrogacao    ac_acordo_aditivo.prorrogacao%type := p_prorrogacao;
   w_valor          ac_acordo.valor_atual%type := (p_valor_inicial+p_valor_reajuste+p_valor_acrescimo);
   w_inicio_aditivo date;
   w_fim_aditivo    date;
   w_chave          number(18) := Nvl(p_chave,0);
begin
   -- Atualiza o valor do contrato
   If p_operacao = 'I' Then
      update siw_solicitacao set valor = coalesce(valor,0) + (p_valor_inicial+p_valor_reajuste+p_valor_acrescimo) where sq_siw_solicitacao = p_chave_aux;
   Elsif p_operacao = 'A' or p_operacao = 'E' Then
      -- � necess�rio recuperar o valor do aditivo que est� sendo alterado ou exclu�do
      select valor_aditivo into w_valor from ac_acordo_aditivo where sq_acordo_aditivo = p_chave;

      If p_operacao = 'A' Then
         update siw_solicitacao set 
           valor = valor - w_valor + (p_valor_inicial+p_valor_reajuste+p_valor_acrescimo)
         where sq_siw_solicitacao = p_chave_aux;
      Else
         update siw_solicitacao set valor = valor - w_valor where sq_siw_solicitacao = p_chave_aux;
      End If;
   End If;

   -- Trata a exclus�o de aditivos de prorroga��o
   If p_operacao = 'E' Then
      select prorrogacao, inicio into w_prorrogacao, w_inicio from ac_acordo_aditivo where sq_acordo_aditivo = p_chave;
   End If;
   
   -- Ajusta o t�rmino do contrato se for aditivo de prorroga��o
   If w_prorrogacao = 'S' Then
      If p_operacao = 'I' or p_operacao = 'A' Then
         update siw_solicitacao set fim = p_fim where sq_siw_solicitacao = p_chave_aux;
      Else
         update siw_solicitacao set fim = w_inicio-1 where sq_siw_solicitacao = p_chave_aux;
      End If;
   End If;
         
   If p_operacao = 'I' Then
      select sq_acordo_aditivo.nextval into w_chave from dual;
      -- Insere registro
      insert into ac_acordo_aditivo
        (       sq_acordo_aditivo,         sq_siw_solicitacao,   protocolo,           codigo,           objeto,            inicio,         fim, 
                duracao,                   documento_origem,     documento_data,      variacao_valor,   prorrogacao,       revisao, 
                acrescimo,                 supressao,            observacao,          valor_inicial,    parcela_inicial,   valor_reajuste, 
                parcela_reajustada,        valor_acrescimo,      parcela_acrescida,   sq_cc)
        
        (select w_chave,                   p_chave_aux,          p_protocolo,         p_codigo,         p_objeto,          p_inicio,       p_fim, 
                p_duracao,                 p_documento_origem,   p_documento_data,    p_variacao_valor, p_prorrogacao,     p_revisao,         
                p_acrescimo,               p_supressao,          p_observacao,        p_valor_inicial,  p_parcela_inicial, p_valor_reajuste, 
                p_parcela_reajustada,      p_valor_acrescimo,    p_parcela_acrescida, p_sq_cc
           from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update ac_acordo_aditivo
         set protocolo          = p_protocolo,
             codigo             = p_codigo,
             objeto             = trim(p_objeto),
             inicio             = p_inicio,
             fim                = p_fim,
             duracao            = p_duracao,
             documento_origem   = p_documento_origem,
             documento_data     = p_documento_data,
             variacao_valor     = p_variacao_valor,
             prorrogacao        = p_prorrogacao,
             revisao            = p_revisao,
             acrescimo          = p_acrescimo,
             supressao          = p_supressao,
             observacao         = p_observacao,
             valor_inicial      = p_valor_inicial,
             parcela_inicial    = p_parcela_inicial,
             valor_reajuste     = p_valor_reajuste,
             parcela_reajustada = p_parcela_reajustada,
             valor_acrescimo    = p_valor_acrescimo,
             parcela_acrescida  = p_parcela_acrescida,
             sq_cc              = p_sq_cc
       where sq_acordo_aditivo = p_chave;
   Elsif p_operacao = 'E' Then
      If w_prorrogacao = 'N' Then
         -- Atualiza o valor da parcela e remove o v�nculo com o aditivo
         update ac_acordo_parcela x set 
            valor             = valor_inicial + valor_reajuste, 
            valor_excedente   = 0, 
            sq_acordo_aditivo = (select max(b.sq_acordo_aditivo)
                                   from ac_acordo_parcela a
                                        left join ac_acordo_aditivo b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao and
                                                                           a.inicio             between b.inicio and b.fim and
                                                                           b.sq_acordo_aditivo  <> p_chave
                                                                          )
                                  where a.sq_acordo_parcela = x.sq_acordo_parcela
                                )
         where sq_acordo_aditivo = p_chave;
         
         -- Exclui registro
         delete ac_parcela_nota   where sq_acordo_nota in (select sq_acordo_nota from ac_acordo_nota where sq_acordo_aditivo = p_chave);
         delete ac_acordo_nota    where sq_acordo_aditivo = p_chave;
         delete ac_acordo_aditivo where sq_acordo_aditivo = p_chave;
      Else
         -- Exclui registro
         delete ac_acordo_parcela where sq_acordo_aditivo = p_chave;
         delete ac_acordo_nota    where sq_acordo_aditivo = p_chave;
         delete ac_acordo_aditivo where sq_acordo_aditivo = p_chave;
      End If;
   End If;
   
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;

end SP_PutAcordoAditivo;
/
