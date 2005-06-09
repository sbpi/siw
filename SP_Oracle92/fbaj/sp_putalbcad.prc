create or replace procedure SP_PutAlbCad
   (p_operacao          in varchar2,
    p_chave             in number    default null,
    p_carteira          in varchar2,
    p_nome              in varchar2,
    p_nascimento	      in date      default null,
    p_endereco	        in varchar2  default null,
    p_bairro	          in varchar2  default null,
    p_cep	              in varchar2  default null,
    p_cidade	          in varchar2  default null,
    p_uf	              in varchar2  default null,
    p_ddd               in varchar2  default null,
    p_fone	            in varchar2  default null,
    p_cpf	              in varchar2  default null,
    p_rg_numero	        in varchar2  default null,
    p_rg_emissor	      in varchar2  default null,
    p_email	            in varchar2  default null,
    p_sexo	            in varchar2  default null,
    p_formacao	        in varchar2  default null,
    p_trabalha	        in varchar2  default null,
    p_email_trabalho	  in varchar2  default null,
    p_conhece_albergue  in varchar2  default null,
    p_visitas	          in number    default null,
    p_classificacao	    in varchar2  default null,
    p_destino	          in varchar2  default null,
    p_destino_outros  	in varchar2  default null,
    p_motivo_viagem	    in varchar2  default null,
    p_motivo_outros	    in varchar2  default null,
    p_forma_conhece	    in varchar2  default null,
    p_forma_outros	    in varchar2  default null,
    p_sq_cidade	        in number    default null,
    p_carteira_emissao	in date      default null,
    p_carteira_validade	in date      default null
    ) is

begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into alberguista 
         (sq_alberguista,             carteira,           nome,            nascimento,       endereco,      
          bairro,                     cep,                cidade,          uf,               ddd,           
          fone,                       cpf,                rg_numero,       rg_emissor,       email,
          sexo,                       formacao,           trabalha,        email_trabalho,   conhece_albergue,
          visitas,                    classificacao,      destino,         destino_outros,   motivo_viagem,
          motivo_outros,              forma_conhece,      forma_outros,    sq_cidade,        carteira_emissao,
          carteira_validade)  
  (select sq_alberguista.nextval,     p_carteira,         p_nome,          p_nascimento,     p_endereco, 
          p_bairro,                   p_cep,              p_cidade,        p_uf,             p_ddd, 
          p_fone,                     p_cpf,              p_rg_numero,     p_rg_emissor,     p_email, 
          p_sexo,                     p_formacao,         p_trabalha,      p_email_trabalho, p_conhece_albergue, 
          p_visitas,                  p_classificacao,    p_destino,       p_destino_outros, p_motivo_viagem,
          p_motivo_outros,            p_forma_conhece,    p_forma_outros,  p_sq_cidade,      p_carteira_emissao, 
          p_carteira_validade from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update alberguista set 
             carteira          = p_carteira,
             nome              = p_nome,
             nascimento        = p_nascimento,
             endereco          = p_endereco,
             bairro            = p_bairro,
             cep               = p_cep,
             cidade            = p_cidade,
             uf                = p_uf,
             ddd               = p_ddd,
             fone              = p_fone,
             cpf               = p_cpf,
             rg_numero         = p_rg_numero,
             rg_emissor        = p_rg_emissor,
             email             = p_email,
             sexo              = p_sexo,
             formacao          = p_formacao,
             trabalha          = p_trabalha,
             email_trabalho    = p_email_Trabalho,
             conhece_albergue  = p_conhece_albergue,
             visitas           = p_visitas,
             classificacao     = p_classificacao,
             destino           = p_destino,
             destino_outros    = p_destino_outros,
             motivo_viagem     = p_motivo_viagem,
             motivo_outros     = p_motivo_outros,
             forma_conhece     = p_forma_conhece,
             forma_outros      = p_forma_outros,
             sq_cidade         = p_sq_cidade,
             carteira_emissao  = p_carteira_emissao,
             carteira_validade = p_carteira_validade
      where sq_alberguista = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete alberguista where sq_alberguista = p_chave;
   End If;
end SP_PutAlbCad;
/

