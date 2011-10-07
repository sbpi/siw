create or replace procedure SP_PutCelular
   (p_operacao                 in varchar2,
    p_cliente                  in number,
    p_chave                    in number   default null,
    p_numero                   in varchar2 default null,
    p_marca                    in varchar2 default null, 
    p_modelo                   in varchar2 default null, 
    p_sim_card                 in varchar2 default null, 
    p_imei                     in varchar2 default null, 
    p_acessorios               in varchar2 default null,
    p_bloqueio                 in varchar2 default null,
    p_inicio                   in date     default null,
    p_motivo                   in varchar2 default null,
    p_ativo                    in varchar2 default null 
   ) is
   
   w_reg sr_celular%rowtype;
   
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into sr_celular
        (sq_celular,         cliente,      numero_linha,    marca,        modelo,          sim_card,   imei,   
         acessorios,         bloqueado,    inicio_bloqueio, fim_bloqueio, motivo_bloqueio, ativo)
      values
        (sq_celular.nextval, p_cliente,    p_numero,        p_marca,      p_modelo,        p_sim_card, p_imei,
         p_acessorios,       p_bloqueio,   p_inicio,        null,         p_motivo,        p_ativo);
   Elsif p_operacao = 'A' Then
      -- Recupera os dados atuais do registro
      select * into w_reg from sr_celular where sq_celular = p_chave;
      
      -- Altera registro
      update sr_celular
         set numero_linha    = p_numero,
             marca           = p_marca,
             modelo          = p_modelo,
             sim_card        = p_sim_card,
             imei            = p_imei,
             acessorios      = p_acessorios,
             bloqueado       = p_bloqueio,
             inicio_bloqueio = coalesce(p_inicio,inicio_bloqueio),
             fim_bloqueio    = case p_bloqueio 
                                    when 'S' then null 
                                    else case w_reg.bloqueado when p_bloqueio then fim_bloqueio else trunc(sysdate) end
                               end,
             motivo_bloqueio = p_motivo,
             ativo           = p_ativo
       where sq_celular = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete sr_celular where sq_celular = p_chave;
   End If;
end SP_PutCelular;
/
