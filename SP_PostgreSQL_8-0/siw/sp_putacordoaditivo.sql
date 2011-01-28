create or replace FUNCTION SP_PutAcordoAditivo
   (p_operacao             varchar,
    p_chave                numeric,
    p_chave_aux            numeric,
    p_protocolo            numeric,
    p_codigo               varchar,
    p_objeto               varchar,
    p_inicio               date,
    p_fim                  date,
    p_duracao              numeric,
    p_documento_origem     varchar,
    p_documento_data       date,
    p_variacao_valor       numeric,
    p_prorrogacao          varchar,
    p_revisao              varchar,
    p_acrescimo            varchar,
    p_supressao            varchar,
    p_observacao           varchar,
    p_valor_inicial        numeric,
    p_parcela_inicial      numeric,
    p_valor_reajuste       numeric,
    p_parcela_reajustada   numeric,
    p_valor_acrescimo      numeric,
    p_parcela_acrescida    numeric,
    p_sq_cc                numeric,
    p_chave_nova          numeric
   ) RETURNS VOID AS $$
DECLARE
   w_inicio         ac_acordo_aditivo.inicio%type := p_inicio;
   w_prorrogacao    ac_acordo_aditivo.prorrogacao%type := p_prorrogacao;
   w_valor          ac_acordo.valor_atual%type := (p_valor_inicial+p_valor_reajuste+p_valor_acrescimo);
   w_inicio_aditivo date;
   w_fim_aditivo    date;
   w_chave          numeric(18) := Nvl(p_chave,0);
BEGIN
   -- Atualiza o valor do contrato
   If p_operacao = 'I' Then
      update siw_solicitacao set valor = coalesce(valor,0) + (p_valor_inicial+p_valor_reajuste+p_valor_acrescimo) where sq_siw_solicitacao = p_chave_aux;
   Elsif p_operacao = 'A' or p_operacao = 'E' Then
      -- É necessário recuperar o valor do aditivo que está sendo alterado ou excluído
      select valor_aditivo into w_valor from ac_acordo_aditivo where sq_acordo_aditivo = p_chave;

      If p_operacao = 'A' Then
         update siw_solicitacao set 
           valor = valor - w_valor + (p_valor_inicial+p_valor_reajuste+p_valor_acrescimo)
         where sq_siw_solicitacao = p_chave_aux;
      Else
         update siw_solicitacao set valor = valor - w_valor where sq_siw_solicitacao = p_chave_aux;
      End If;
   End If;

   -- Trata a exclusão de aditivos de prorrogação
   If p_operacao = 'E' Then
      select prorrogacao, inicio into w_prorrogacao, w_inicio from ac_acordo_aditivo where sq_acordo_aditivo = p_chave;
   End If;
   
   -- Ajusta o término do contrato se for aditivo de prorrogação
   If w_prorrogacao = 'S' Then
      If p_operacao = 'I' or p_operacao = 'A' Then
         update siw_solicitacao set fim = p_fim where sq_siw_solicitacao = p_chave_aux;
      Else
         update siw_solicitacao set fim = w_inicio-1 where sq_siw_solicitacao = p_chave_aux;
      End If;
   End If;
         
   If p_operacao = 'I' Then
      select sq_acordo_aditivo.nextval into w_chave;
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
         -- Atualiza o valor da parcela e remove o vínculo com o aditivo
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
         DELETE FROM ac_parcela_nota   where sq_acordo_nota in (select sq_acordo_nota from ac_acordo_nota where sq_acordo_aditivo = p_chave);
         DELETE FROM ac_acordo_nota    where sq_acordo_aditivo = p_chave;
         DELETE FROM ac_acordo_aditivo where sq_acordo_aditivo = p_chave;
      Else
         -- Exclui registro
         DELETE FROM ac_acordo_parcela where sq_acordo_aditivo = p_chave;
         DELETE FROM ac_acordo_nota    where sq_acordo_aditivo = p_chave;
         DELETE FROM ac_acordo_aditivo where sq_acordo_aditivo = p_chave;
      End If;
   End If;
   
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;