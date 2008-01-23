create or replace procedure sp_getMatImp
   (p_cliente       in number,
    p_usuario       in number,
    p_chave         in number    default null,
    p_chave_aux     in number    default null,
    p_restricao     in varchar2  default null,
    p_result        out sys_refcursor) is
begin
  If p_restricao is null Then
     open p_result for
       select a.sq_material_impedimento, a.sq_material, a.sq_pessoa, a.origem, a.documento, a.observacoes, a.tipo, 
              a.data_inicio, a.data_fim, a.numero_inicio, a.numero_fim, 
              to_char(a.inclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_inclusao, 
              to_char(a.ultima_alteracao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_alteracao,
              b.nome as nm_material,
              c.nome as nm_usuario 
         from cl_material_impedimento a
              inner join cl_material  b on (a.sq_material = b.sq_material)
              inner join co_pessoa    c on (a.sq_pessoa   = c.sq_pessoa)
        where b.cliente     = p_cliente
          and (p_chave      is null or (p_chave     is not null and a.sq_material             = p_chave))
          and (p_usuario    is null or (p_usuario   is not null and a.sq_pessoa               = p_usuario))
          and (p_chave_aux  is null or (p_chave_aux is not null and a.sq_material_impedimento = p_chave_aux));
  End If;
end sp_getMatImp;
/
