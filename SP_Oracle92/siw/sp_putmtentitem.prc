create or replace procedure SP_PutMTEntItem
   (p_operacao                 in  varchar2,
    p_chave                    in  number   default null,
    p_chave_aux                in  number   default null,
    p_almoxarifado             in  number   default null,
    p_situacao                 in  number   default null,
    p_ordem                    in  number   default null,
    p_material                 in  number   default null,
    p_quantidade               in  number   default null,
    p_valor                    in  number   default null,
    p_fator                    in  varchar2 default null,
    p_validade                 in  date     default null,
    p_fabricacao               in  date     default null,
    p_vida_util                in  number   default null,
    p_lote                     in  varchar2 default null,
    p_marca                    in  varchar2 default null,
    p_modelo                   in  varchar2 default null,
    p_bloqueio                 in  varchar2 default null,
    p_motivo                   in  varchar2 default null
   ) is
   w_chave      number(18);
   w_valor      number(20,10);
   w_total      number(18,2);
begin
   If p_operacao in ('I','A') Then
      w_valor := trunc(p_valor / case when p_quantidade > 0 then p_quantidade else 1 end,10);
   End If;
   
   If p_operacao = 'I' Then
      select sq_entrada_item.nextval into w_chave from dual;
      
      -- Insere registro
      insert into mt_entrada_item
        (sq_entrada_item, sq_mtentrada,   sq_material,     sq_almoxarifado, sq_mtsituacao,  quantidade,
         valor_total,     valor_unitario, fator_embalagem, validade,        fabricacao,     vida_util,
         lote_numero,     marca,          modelo,          ordem,           lote_bloqueado, motivo_bloqueio)
      values
        (w_chave,         p_chave,        p_material,      p_almoxarifado,  p_situacao,    
         case p_bloqueio when 'S' then 0 else p_quantidade end,
         case p_bloqueio when 'S' then 0 else p_valor end,
         case p_bloqueio when 'S' then 0 else w_valor end,
         p_fator,
         case p_bloqueio when 'S' then null else p_validade end,
         p_fabricacao,    p_vida_util,    p_lote,
         case p_bloqueio when 'S' then null else p_marca end,
         case p_bloqueio when 'S' then null else p_modelo end,
         p_ordem,         p_bloqueio,     p_motivo
        );
      
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update mt_entrada_item
         set sq_material       = p_material,
             sq_almoxarifado   = p_almoxarifado,
             sq_mtsituacao     = p_situacao,
             ordem             = p_ordem,
             quantidade        = case p_bloqueio when 'S' then 0 else p_quantidade end,
             valor_total       = case p_bloqueio when 'S' then 0 else p_valor end,
             valor_unitario    = case p_bloqueio when 'S' then 0 else w_valor end,
             fator_embalagem   = p_fator,
             validade          = case p_bloqueio when 'S' then null else p_validade end,
             fabricacao        = p_fabricacao,
             vida_util         = p_vida_util,
             lote_numero       = p_lote,
             marca             = case p_bloqueio when 'S' then null else p_marca end,
             modelo            = case p_bloqueio when 'S' then null else p_modelo end,
             lote_bloqueado    = p_bloqueio,
             motivo_bloqueio   = p_motivo
       where sq_entrada_item = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Remove o registro
      delete mt_entrada_item where sq_entrada_item = p_chave_aux;
   End if;
   
   -- Recupera o valor da entrada
   select sum(valor_total) into w_total
     from mt_entrada_item
    where sq_mtentrada   = p_chave
      and lote_bloqueado = 'N';
      
   -- Atualiza o valor do documento de entrada
   update fn_lancamento_doc a set a.valor = w_total where sq_lancamento_doc = (select sq_lancamento_doc from mt_entrada where sq_mtentrada = p_chave);
   
   -- Atualiza o valor do financeiro
   update siw_solicitacao a set a.valor = w_total where sq_siw_solicitacao = (select sq_siw_solicitacao from fn_lancamento_doc where sq_lancamento_doc = (select sq_lancamento_doc from mt_entrada where sq_mtentrada = p_chave));
   
end SP_PutMTEntItem;
/
