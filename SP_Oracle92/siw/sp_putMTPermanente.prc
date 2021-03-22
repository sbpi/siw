create or replace procedure sp_putMTPermanente
   (p_operacao        in  varchar2,
    p_cliente         in  number,
    p_usuario         in  number,
    p_chave           in  number   default null,
    p_copia           in  number   default null,
    p_localizacao     in  number   default null,
    p_almoxarifado    in  number   default null,
    p_projeto         in  number   default null,
    p_sqcc            in  number   default null,
    p_material        in  number   default null,
    p_entrada         in  number   default null,
    p_situacao        in  number   default null,
    p_forn_garantia   in  number   default null,
    p_numero_rgp      in  number   default null,
    p_tombamento      in  date     default null,
    p_descricao       in  varchar2 default null,
    p_codigo_externo  in  varchar2 default null,
    p_numero_serie    in  varchar2 default null,
    p_marca           in  varchar2 default null,
    p_modelo          in  varchar2 default null,
    p_fim_garantia    in  date     default null,
    p_vida_util       in  number   default null,
    p_observacao      in  varchar2 default null,
    p_cc_patrimonial  in varchar2  default null,
    p_cc_depreciacao  in varchar2  default null,
    p_valor_brl       in  number   default null,
    p_valor_usd       in  number   default null,
    p_valor_eur       in  number   default null,
    p_data_brl        in  date     default null,
    p_data_usd        in  date     default null,
    p_data_eur        in  date     default null,
    p_chave_nova      out number
   ) is
   
   w_chave number(18) := p_chave;
   i       number(2);
   w_moeda co_moeda.sq_moeda%type;
   w_valor mt_bem_cotacao.valor_atual%type;
   w_data  mt_bem_cotacao.data_valor_atual%type;
   w_existe number(2);
begin
   If p_operacao = 'I' or p_operacao = 'C' Then
      -- Gera a nova chave do registro, a partir da sequence
      select sq_permanente.nextval into w_chave from dual;

      -- Insere registro
      insert into mt_permanente
        (sq_permanente, cliente,         sq_localizacao,         sq_almoxarifado, sq_cc,   sq_material, sq_entrada_item,   sq_mtsituacao, fornecedor_garantia, 
         numero_rgp,    data_tombamento, descricao_complementar, numero_serie,    marca,   modelo,      data_fim_garantia, vida_util, 
         observacao,    ativo,           sq_projeto,             codigo_externo
        )
      values
        (w_chave,       p_cliente,       p_localizacao,          p_almoxarifado,  p_sqcc,  p_material,  p_entrada,         p_situacao,    p_forn_garantia,
         p_numero_rgp,  p_tombamento,    p_descricao,            p_numero_serie,  p_marca, p_modelo,    p_fim_garantia,    p_vida_util,
         p_observacao,  'S',             p_projeto,              p_codigo_externo
        );
        
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update mt_permanente
         set sq_permanente          = p_chave,
             cliente                = p_cliente,
             sq_localizacao         = p_localizacao,
             sq_almoxarifado        = p_almoxarifado,
             sq_cc                  = p_sqcc,
             sq_material            = p_material,
             sq_entrada_item        = p_entrada,
             sq_mtsituacao          = p_situacao,
             fornecedor_garantia    = p_forn_garantia,
             numero_rgp             = p_numero_rgp,
             data_tombamento        = p_tombamento,
             descricao_complementar = p_descricao,
             numero_serie           = p_numero_serie,
             marca                  = p_marca,
             modelo                 = p_modelo,
             data_fim_garantia      = p_fim_garantia,
             vida_util              = p_vida_util,
             observacao             = p_observacao,
             sq_projeto             = p_projeto,
             codigo_externo         = p_codigo_externo
       where sq_permanente = p_chave;
   Elsif p_operacao = 'E' Then
      -- Remove os valores do bem
      delete mt_bem_cotacao where sq_permanente = p_chave;
      delete mt_permanente  where sq_permanente = p_chave;
   End If;
   
   -- Na inclusão e alteração, registra os valores maiores que zero, em reais, dólar e euro
   If p_operacao in ('I','A','C') Then
      For i in 1..3 Loop
          select sq_moeda into w_moeda
            from co_moeda
           where sigla = case i when 1 then 'BRL' when 2 then 'USD' when 3 then 'EUR' end;
          
          select case i when 1 then p_valor_brl when 2 then p_valor_usd when 3 then p_valor_eur end into w_valor from dual;
          select case i when 1 then p_data_brl  when 2 then p_data_usd  when 3 then p_data_eur  end into w_data  from dual;
          
          If w_valor > 0 Then
              select count(*) into w_existe from mt_bem_cotacao where sq_permanente = w_chave and sq_moeda = w_moeda;
              If w_existe = 0 Then
                  insert into mt_bem_cotacao (sq_bem_cotacao, sq_permanente, sq_moeda, valor_aquisicao, valor_atual, data_valor_atual)
                  select sq_bem_cotacao.nextval, w_chave, a.sq_moeda, 
                         case i when 1 then p_valor_brl when 2 then p_valor_usd when 3 then p_valor_eur end, 
                         case i when 1 then p_valor_brl when 2 then p_valor_usd when 3 then p_valor_eur end, 
                         case i when 1 then p_data_brl  when 2 then p_data_usd  when 3 then p_data_eur  end
                    from co_moeda a
                   where a.sigla = case i when 1 then 'BRL' when 2 then 'USD' when 3 then 'EUR' end;
              Else
                  update mt_bem_cotacao w
                     set valor_atual      = w_valor, 
                         data_valor_atual = w_data
                   where sq_permanente = w_chave
                     and sq_bem_cotacao = (select a.sq_bem_cotacao
                                             from mt_bem_cotacao a
                                            where a.sq_bem_cotacao = w.sq_bem_cotacao
                                              and a.sq_moeda       = w_moeda
                                          );
              End If;
          Else
              delete mt_bem_cotacao w
              where sq_permanente = w_chave
                and sq_bem_cotacao = (select sq_bem_cotacao
                                        from mt_bem_cotacao a
                                       where a.sq_bem_cotacao = w.sq_bem_cotacao
                                         and a.sq_moeda       = w_moeda
                                     );
          End If;
      End Loop;
      
      -- Grava informações contábeis
      SP_PutContaPatrimonio(p_usuario, w_chave, p_cc_patrimonial, p_cc_depreciacao);
   
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end sp_putMTPermanente;
/
