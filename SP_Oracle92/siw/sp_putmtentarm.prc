create or replace procedure SP_PutMTEntArm
   (p_operacao                 in  varchar2,
    p_chave                    in  number,
    p_item                     in  number   default null,
    p_local                    in  number   default null
   ) is
   
   w_ent        mt_entrada%rowtype;
   w_estoque    mt_estoque.sq_estoque%type;
   w_item       mt_entrada_item%rowtype;
   w_situacao   mt_situacao.sq_mtsituacao%type;
   w_existe     number(18);
   w_medio      mt_estoque.preco_medio%type;
   
   cursor c_itens is
     select a.sq_entrada_item, a.quantidade, a.valor_unitario, c.sq_estoque
       from mt_entrada_item              a
            inner   join mt_estoque_item b on (a.sq_entrada_item = b.sq_entrada_item)
              inner join mt_estoque      c on (b.sq_estoque      = c.sq_estoque)
      where a.sq_mtentrada = p_chave
     order by a.ordem;
begin
   -- Recupera os dados da entrada, informada para armazenamento e para estorno de armazenamento
   select * into w_ent  from mt_entrada      where sq_mtentrada    = p_chave;
   
   If p_operacao = 'A' Then -- Armazenamento

      -- Recupera a chave do item, informada somente se for armazenamento
      select * into w_item from mt_entrada_item where sq_entrada_item = p_item;
      
      -- Recupera a situacao que indica armazenamento
      select sq_mtsituacao into w_situacao from mt_situacao where cliente = w_ent.cliente and consumo = 'S' and ativo = 'S' and sigla = 'AR';

      -- Verifica se já existe posição de estoque para o material no almoxarifado
      select count(*) into w_existe from mt_estoque where sq_almoxarifado = w_item.sq_almoxarifado and sq_material = w_item.sq_material;
      
      If w_existe = 0 Then
         -- Recupera a próxima chave
         select sq_estoque.nextval into w_estoque from dual;
         
         insert into mt_estoque
           (sq_estoque,            sq_almoxarifado,        sq_material,        ultima_entrada,             preco_medio,
            ultimo_preco_compra,   disponivel)
         values
           (w_estoque,             w_item.sq_almoxarifado, w_item.sq_material, w_ent.recebimento_efetivo,  w_item.valor_unitario,
            w_item.valor_unitario, 'S');
         
         insert into mt_estoque_item
           (sq_estoque_item,         sq_estoque, sq_almoxarifado_local, sq_entrada_item, saldo_atual)
         values
           (sq_estoque_item.nextval, w_estoque,  p_local,               p_item,          w_item.quantidade);
      Else
         -- Recupera o saldo atual
         select y.sq_estoque, sum(z.valor_total)/sum(z.quantidade) 
           into w_estoque, w_medio
           from mt_estoque                 y 
                inner join mt_entrada_item z on (y.sq_almoxarifado = z.sq_almoxarifado and y.sq_material = z.sq_material)
          where y.sq_material     = w_item.sq_material
            and y.sq_almoxarifado = w_item.sq_almoxarifado
         group by y.sq_estoque;

         -- Insere o novo item de estoque
         insert into mt_estoque_item
           (sq_estoque_item,         sq_estoque, sq_almoxarifado_local, sq_entrada_item, saldo_atual)
         values
           (sq_estoque_item.nextval, w_estoque,  p_local,               p_item,          w_item.quantidade);

         -- Atualiza os dados do estoque
         update mt_estoque
            set ultima_entrada      = w_ent.recebimento_efetivo,
                preco_medio         = w_medio,
                ultimo_preco_compra = w_item.valor_unitario,
                disponivel          = 'S'
          where sq_estoque = w_estoque;
         
      End If;
      
      -- Atualiza o item de entrada para armazenado
      update mt_entrada_item set sq_mtsituacao = w_situacao where sq_entrada_item = p_item;

      -- Se todos os itens de uma entrada estiverem armazenados, atualiza a situação da entrada
      select count(*) into w_existe from mt_entrada_item where sq_entrada_item = p_item and sq_mtsituacao <> w_situacao;
      
      If w_existe = 0 Then
         update mt_entrada set sq_mtsituacao = w_situacao where sq_mtentrada = p_chave;
      End If;
   Elsif p_operacao = 'E' Then -- Estorno de armazenamento
      -- Recupera a situacao que indica estorno
      select sq_mtsituacao into w_situacao from mt_situacao where cliente = w_ent.cliente and consumo = 'S' and ativo = 'S' and sigla = 'ES';

      for crec in c_itens loop
         -- Recupera o preço médio antigo
         select case sign(a.saldo-crec.quantidade) 
                     when 0 then 0
                     when -1 then 0
                     else ((b.preco_medio * a.saldo) - (w_item.valor_total)) / abs(crec.quantidade - a.saldo)
                end as medio
           into w_medio
           from (select sum(saldo_atual) as saldo from mt_estoque_item where sq_estoque = crec.sq_estoque) a,
                (select preco_medio from mt_estoque where sq_estoque = crec.sq_estoque) b;

         -- Atualiza o preço médio
         update mt_estoque set preco_medio = w_medio, disponivel = case w_medio when 0 then 'N' else 'S' end where sq_estoque = crec.sq_estoque;
         
         -- Remove do estoque os itens da entrada
         delete mt_estoque_item where sq_entrada_item = crec.sq_entrada_item;
         
         -- Atualiza o item de entrada para armazenado
         update mt_entrada_item set sq_mtsituacao = w_situacao where sq_entrada_item = crec.sq_entrada_item;
      
      end loop;
      
      -- Atualiza a situação da entrada
      update mt_entrada set sq_mtsituacao = w_situacao where sq_mtentrada = p_chave;
   End If;
end SP_PutMTEntArm;
/
