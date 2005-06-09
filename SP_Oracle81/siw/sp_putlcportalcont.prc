create or replace procedure SP_PutLcPortalCont
   (p_operacao            in  varchar2,
    p_cliente             in number,
    p_chave               in number    default null,
    p_chave_aux           in number    default null,
    p_numero              in varchar2  default null,
    p_objeto              in varchar2  default null,
    p_processo            in varchar2  default null,
    p_empenho             in varchar2  default null,
    p_assinatura          in date      default null,
    p_vigencia_i          in date      default null,
    p_vigencia_f          in date      default null,
    p_publicacao          in date      default null,
    p_valor               in number    default null,
    p_pessoa_juridica     in varchar2  default null,
    p_cnpj                in varchar2  default null,
    p_cpf                 in varchar2  default null,
    p_nome                in varchar2  default null,
    p_nome_resumido       in varchar2  default null,
    p_sexo                in varchar2  default null,
    p_sq_pessoa           in number    default null,
    p_unidade             in number    default null,    
    p_observacao          in varchar2  default null,
    p_publicar            in varchar2  default null
   ) is
   w_chave   number(18);
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_portal_contrato.nextval into w_Chave from dual;
       
      -- Insere registro em LC_PORTAL_CONTRATO
      insert into lc_portal_contrato
        (sq_portal_contrato,  cliente,     sq_unidade,  sq_pessoa,    vigencia_inicio,   
         vigencia_fim,        assinatura,  publicacao,  valor,        processo, 
         objeto,              publicar,    empenho,     observacao,   numero,
         pessoa_juridica,     cnpj,        cpf,         nome,         nome_resumido,
         sexo,                sq_portal_lic
        )
      values
        (w_chave,           p_cliente,        p_unidade,       p_sq_pessoa,          p_vigencia_i, 
         p_vigencia_f,      p_assinatura,     p_publicacao,    p_valor,              p_processo, 
         p_objeto,          p_publicar,       p_empenho,       p_observacao,         p_numero,
         p_pessoa_juridica, p_cnpj,           p_cpf,           p_nome,               p_nome_resumido,
         p_sexo,            p_chave
        );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de contratos
      update lc_portal_contrato set 
         sq_unidade         = p_unidade,
         sq_pessoa          = p_sq_pessoa,
         vigencia_inicio    = p_vigencia_i,
         vigencia_fim       = p_vigencia_f,
         assinatura         = p_assinatura,
         publicacao         = p_publicacao,
         valor              = p_valor,
         processo           = p_processo,
         objeto             = p_objeto,
         publicar           = p_publicar,
         empenho            = p_empenho,
         observacao         = p_observacao,
         numero             = p_numero,
         pessoa_juridica    = p_pessoa_juridica,
         cnpj               = p_cnpj,
         cpf                = p_cpf,
         nome               = p_nome,
         nome_resumido      = p_nome_resumido,
         sexo               = p_sexo
       where sq_portal_contrato = p_Chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove os registros vinculados à ao contrato
      delete lc_portal_contrato_item where sq_portal_contrato = p_chave_aux;
      delete lc_portal_contrato      where sq_portal_contrato = p_chave_aux;
   End If;
end SP_PutLcPortalCont;
/

