create or replace procedure carrega_petty_cash is
  cursor dados is
      select a.codigo, a.rubrica, a.finalidade, a.forma, a.data, 
             a.cpf, a.tipo, a.numero, a.emissao, a.valor,
             c.sq_forma_pagamento, 
             b.sq_tipo_documento, 
             e.sq_tipo_lancamento, 
             d.sq_siw_solicitacao, d.sq_moeda,
             g.sq_projeto_rubrica,
             h.sq_pessoa, h.sq_tipo_pessoa, 
             a.codigo||a.rubrica||a.finalidade||a.data||a.cpf||to_char(a.emissao,'yyyymmdd')||to_char(a.valor) atual
        from petty                         a
             inner join fn_tipo_documento  b on (a.tipo               = b.sigla)
             inner join co_forma_pagamento c on (a.forma              = c.sigla)
             inner join siw_solicitacao    d on (a.codigo             = d.codigo_interno)
             inner join siw_solicitacao    f on (d.sq_solic_pai       = f.sq_siw_solicitacao)
             inner join pj_rubrica         g on (f.sq_siw_solicitacao = g.sq_siw_solicitacao and
                                                 a.rubrica            = g.codigo
                                                )
             inner join (select nvl(x.cpf, y.cnpj) cpf, w.sq_pessoa, w.sq_tipo_pessoa
                           from co_pessoa                     w
                                left  join co_pessoa_fisica   x on (w.sq_pessoa = x.sq_pessoa)
                                left  join co_pessoa_juridica y on (w.sq_pessoa = y.sq_pessoa)
                          order by w.sq_pessoa
                        )                  h on (a.cpf                = h.cpf),
             fn_tipo_lancamento            e
       where e.cliente = 17305 and e.despesa = 'S'
         and a.importado = 'N'
      order by 1,5,6,10;
      
  SG                  varchar2(255) := 'FNDFUNDO';
  O                   varchar2(255) := 'I';

  w_user              number(18)    := '20265';
  w_client            number(18)    := '17305';
  w_troca             varchar2(255) := null;
  w_copia             varchar2(255) := null;
  w_chave             number(18)    := null;
  w_data_hora         number(18)    := '1';
  w_menu              number(18)    := '11503';
  w_tramite           number(18)    := '3193';
  w_cidade            number(18)    := '1724';
  w_solicitante       number(18)    := '20265';
  w_sq_unidade        number(18)    := '17141';
  w_forma_atual       number(18)    := null;
  w_vencimento_atual  date          := null;
  w_sq_acordo_parcela number(18)    := null;
  w_aviso             varchar2(255) := 'S';
  w_dias              number(18)    := '3';
  w_codigo_interno    varchar2(255) := null;
  w_tipo_rubrica      number(18)    := null;
  w_qtd_nota          number(18)    := null;
  w_tipo              number(18)    := null;
  w_tipo_pessoa       number(18)    := null;
  w_chave_doc         number(18)    := null;

  w_pessoa            number(18)    := null;
  obj_origem          number(18)    := null;


  w_valor_doc         number(18,2)  := null;
  
  w_atual             varchar2(2000) := '-';
  
  w_reg               number(10) := 1;

begin

for crec in dados loop
    If crec.atual <> w_atual Then
        w_atual := crec.atual;
        
        update petty a set importado = 'S' where w_atual = a.codigo||a.rubrica||a.finalidade||a.data||a.cpf||to_char(a.emissao,'yyyymmdd')||to_char(a.valor);
        
        sp_putfinanceirogeral(p_operacao => O,
                              p_cliente => w_client,
                              p_chave => null,
                              p_menu => w_menu,
                              p_sq_unidade => w_sq_unidade,
                              p_solicitante => w_solicitante,
                              p_cadastrador => w_solicitante,
                              p_sqcc => null, --w_sqcc,
                              p_descricao => crec.finalidade,
                              p_vencimento => crec.data,
                              p_valor => crec.valor,
                              p_data_hora => w_data_hora,
                              p_aviso => w_aviso,
                              p_dias => w_dias,
                              p_cidade => w_cidade,
                              p_projeto => crec.sq_siw_solicitacao, --w_projeto,
                              p_sq_acordo_parcela => w_sq_acordo_parcela,
                              p_observacao => null, --w_observacao,
                              p_sq_tipo_lancamento => crec.sq_tipo_lancamento,
                              p_sq_forma_pagamento => crec.sq_forma_pagamento,
                              p_sq_tipo_pessoa => crec.sq_tipo_pessoa,
                              p_forma_atual => w_forma_atual,
                              p_vencimento_atual => w_vencimento_atual,
                              p_tipo_rubrica => null,
                              p_numero_processo => null,
                              p_per_ini => null,
                              p_per_fim => null,
                              p_condicao => null,
                              p_vinculo => null,
                              p_rubrica => crec.sq_projeto_rubrica,
                              p_solic_apoio => null,
                              p_data_autorizacao => null,
                              p_texto_autorizacao => null,
                              p_moeda => crec.sq_moeda,
                              p_chave_nova => w_chave,
                              p_codigo_interno => w_codigo_interno);
                              
        update fn_lancamento k set k.pessoa = crec.sq_pessoa where sq_siw_solicitacao = w_chave;
        
        sp_putlancamentodoc(p_operacao => O,
                            p_chave => w_chave,
                            p_chave_aux => null,
                            p_sq_tipo_documento => crec.sq_tipo_documento,
                            p_numero => crec.numero,
                            p_data => crec.emissao,
                            p_serie => null,
                            p_moeda => crec.sq_moeda,
                            p_valor => crec.valor,
                            p_patrimonio => 'N',
                            p_retencao => 'N',
                            p_tributo => 'N',
                            p_nota => null,
                            p_inicial => 0,
                            p_excedente => 0,
                            p_reajuste => 0,
                            p_chave_nova => w_chave_doc);
        
        sp_putfinanceiroconc(p_menu => w_menu,
                             p_chave => w_chave,
                             p_pessoa => w_user,
                             p_tramite => w_tramite,
                             p_quitacao => crec.data,
                             p_valor_real => crec.valor,
                             p_codigo_deposito => null,
                             p_conta => null,
                             p_tipo_lancamento => crec.sq_tipo_lancamento,
                             p_rubrica => null,
                             p_observacao => 'Conclusão automática de pagamento por fundo fixo.',
                             p_caminho => null,
                             p_tamanho => null,
                             p_tipo => null,
                             p_nome_original => null);
    End If;
end loop;
end carrega_petty_cash;
/
