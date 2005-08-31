create or replace procedure SP_PutGPColaborador
   (p_operacao                 in  varchar2              ,
    p_cliente                  in  number    default null,
    p_sq_pessoa                in  number    default null,
    p_ctps_numero              in  varchar2  default null,
    p_ctps_serie               in  varchar2  default null,
    p_ctps_emissor             in  varchar2  default null,    
    p_ctps_emissao             in  date      default null,
    p_pis_pasep                in  varchar2  default null,
    p_pispasep_numero          in  varchar2  default null,
    p_pispasep_cadastr         in  date      default null,
    p_te_numero                in  varchar2  default null,
    p_te_zona                  in  varchar2  default null,
    p_te_secao                 in  varchar2  default null,
    p_reservista_numero        in  varchar2  default null,
    p_reservista_csm           in  varchar2  default null,
    p_tipo_sangue              in  varchar2  default null,
    p_doador_sangue            in  varchar2  default null,
    p_doador_orgaos            in  varchar2  default null,
    p_observacoes              in  varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into gp_colaborador (sq_pessoa, cliente, ctps_numero, ctps_serie, ctps_emissor, ctps_emissao_data,
                                  pis_pasep, pispasep_numero, pispasep_cadastr, te_numero, te_zona, te_secao,
                                  reservista_numero, reservista_csm, tipo_sangue, doador_sangue, doador_orgaos, 
                                  observacoes) 
      values (p_sq_pessoa, p_cliente, p_ctps_numero, p_ctps_serie, p_ctps_emissor, p_ctps_emissao,
              p_pis_pasep, p_pispasep_numero, p_pispasep_cadastr, p_te_numero, p_te_zona, p_te_secao, 
              p_reservista_numero, p_reservista_csm, p_tipo_sangue, p_doador_sangue, p_doador_orgaos,
              p_observacoes);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update gp_colaborador
         set ctps_numero            = p_ctps_numero,
             ctps_serie             = p_ctps_serie,
             ctps_emissor           = p_ctps_emissor,
             ctps_emissao_data      = p_ctps_emissao,
             pis_pasep              = p_pis_pasep,
             pispasep_numero        = p_pispasep_numero,
             pispasep_cadastr       = p_pispasep_cadastr,
             te_numero              = p_te_numero,
             te_zona                = p_te_zona,
             te_secao               = p_te_secao,
             reservista_numero      = p_reservista_numero,
             reservista_csm         = p_reservista_csm,
             tipo_sangue            = p_tipo_sangue,
             doador_sangue          = p_doador_sangue,
             doador_orgaos          = p_doador_orgaos,
             observacoes            = p_observacoes
       where sq_pessoa = p_sq_pessoa;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete gp_contrato_colaborador where sq_pessoa = p_sq_pessoa;
      delete gp_colaborador where sq_pessoa = p_sq_pessoa;
   End If;
end SP_PutGPColaborador;
/
