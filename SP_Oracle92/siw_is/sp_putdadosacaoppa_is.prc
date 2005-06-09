create or replace procedure SP_PutDadosAcaoPPA_IS
   (p_cliente   in  number               ,
    p_ano       in  number               ,
    p_unidade   in  number               ,
    p_programa  in  varchar2             ,
    p_acao      in  varchar2             ,
    p_subacao   in  varchar2             ,
    p_dotacao   in  number   default null,
    p_empenhado in  number   default null,
    p_liquidado in  number   default null
   ) is
begin
   -- Insere dados do SIAFI na tabela do SIGPLAN
   update is_sig_acao
      set 
          aprovado        = p_dotacao,
          empenhado       = p_empenhado,
          liquidado       = p_liquidado
    where cliente         = p_cliente
      and ano             = p_ano
      and cd_unidade      = p_unidade
      and cd_programa     = p_programa
      and cd_acao         = p_acao
      and cd_localizador  = p_subacao;
      
end SP_PutDadosAcaoPPA_IS;
/

