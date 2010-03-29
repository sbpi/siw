create or replace procedure sp_getGPFamiliares
  (p_chave             in number,
   p_cliente           in number,
   p_colaborador       in number,   
   p_result            out sys_refcursor) is

begin
  -- Retorna os dados da pessoa e tipo de vínculo
  open p_result for 
  select b.nome, b.nome_resumido,
         c.cpf,    c.nascimento, c.sexo, a.sq_pessoa as chave,
         a.cliente, colaborador, 
         case tipo 
           when 10 then 'mãe' 
           when 20 then 'pai' 
           when 30 then 'madrasta'
           when 40 then 'padrasto'
           when 50 then 'cônjuge'
           when 55 then 'companheiro(a)'             
           when 60 then 'filha'
           when 70 then 'filho'             
           when 71 then 'irmã' 
           when 72 then 'irmão'                           
           when 80 then 'enteada'
           when 90 then 'enteado'                          
         end
         as parentesco, tipo,          seguro_vida,
         seguro_saude,  seguro_odonto, imposto_renda
    from gp_pessoa_vinculo a
   inner join co_pessoa b
      on (a.sq_pessoa = b.sq_pessoa)
   inner join co_pessoa_fisica c
      on (b.sq_pessoa = c.sq_pessoa)
   where a.colaborador = p_colaborador 
       and ((p_chave   is null) or (p_chave   is not null and a.sq_pessoa = p_chave))
       and ((p_cliente is null) or (p_cliente is not null and a.cliente = p_cliente));
     
end sp_getGPFamiliares;
/
