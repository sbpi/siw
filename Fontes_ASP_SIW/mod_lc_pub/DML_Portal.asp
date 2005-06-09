<%
REM =========================================================================
REM Grava informações da licitação
REM -------------------------------------------------------------------------
Sub DML_PutLcPortalLic(Operacao, p_cliente, p_chave, p_objeto, p_edital, p_processo, p_empenho, _
       p_abertura, p_fundamentacao, p_observacao, p_modalidade, p_fonte, p_finalidade, _
       p_criterio, p_situacao, p_unidade, p_publicar, p_chave_nova, p_copia)
       
  Dim l_cliente, l_chave, l_copia, l_endereco, l_unidade, l_fonte
  Dim l_modalidade, l_finalidade, l_criterio, l_situacao, l_abertura, l_fundamentacao, l_observacao
  Dim l_objeto, l_edital, l_processo, l_empenho, l_publicar, l_operacao, l_chave_nova
  
  Set l_operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_cliente         = Server.CreateObject("ADODB.Parameter")
  Set l_chave           = Server.CreateObject("ADODB.Parameter")
  Set l_copia           = Server.CreateObject("ADODB.Parameter")
  Set l_objeto          = Server.CreateObject("ADODB.Parameter")
  Set l_edital          = Server.CreateObject("ADODB.Parameter")
  Set l_processo        = Server.CreateObject("ADODB.Parameter")
  Set l_empenho         = Server.CreateObject("ADODB.Parameter")
  Set l_abertura        = Server.CreateObject("ADODB.Parameter")
  Set l_fundamentacao   = Server.CreateObject("ADODB.Parameter")
  Set l_observacao      = Server.CreateObject("ADODB.Parameter")
  Set l_modalidade      = Server.CreateObject("ADODB.Parameter")
  Set l_fonte           = Server.CreateObject("ADODB.Parameter")
  Set l_finalidade      = Server.CreateObject("ADODB.Parameter")
  Set l_criterio        = Server.CreateObject("ADODB.Parameter")
  Set l_situacao        = Server.CreateObject("ADODB.Parameter")
  Set l_unidade         = Server.CreateObject("ADODB.Parameter")
  Set l_publicar        = Server.CreateObject("ADODB.Parameter")
  Set l_chave_nova      = Server.CreateObject("ADODB.Parameter") 

  with sp
     set l_Operacao        = .CreateParameter("l_operacao",     adVarchar, adParamInput,   1, Operacao)
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,    , p_cliente)
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,    , tvl(p_chave))
     set l_copia           = .CreateParameter("l_copia",        adInteger, adParamInput,    , tvl(p_copia))
     set l_objeto          = .CreateParameter("l_objeto",       adVarchar, adParamInput,2000, tvl(p_objeto))
     set l_edital          = .CreateParameter("l_edital",       adVarchar, adParamInput,  15, tvl(p_edital))
     set l_processo        = .CreateParameter("l_processo",     adVarchar, adParamInput,  30, tvl(p_processo))
     set l_empenho         = .CreateParameter("l_empenho",      adVarchar, adParamInput,  30, tvl(p_empenho))
     set l_abertura        = .CreateParameter("l_abertura",     adDate,    adParamInput,    , Tvl(p_abertura))
     set l_fundamentacao   = .CreateParameter("l_fundamentacao",adVarchar, adParamInput, 250, tvl(p_fundamentacao))
     set l_observacao      = .CreateParameter("l_observacao",   adVarchar, adParamInput,1000, tvl(p_observacao))
     set l_modalidade      = .CreateParameter("l_modalidade",   adInteger, adParamInput,    , tvl(p_modalidade))
     set l_fonte           = .CreateParameter("l_fonte",        adInteger, adParamInput,    , tvl(p_fonte))
     set l_finalidade      = .CreateParameter("l_finalidade",   adInteger, adParamInput,    , tvl(p_finalidade))
     set l_criterio        = .CreateParameter("l_criterio",     adInteger, adParamInput,    , tvl(p_criterio))
     set l_situacao        = .CreateParameter("l_situacao",     adInteger, adParamInput,    , tvl(p_situacao))
     set l_unidade         = .CreateParameter("l_unidade",      adInteger, adParamInput,    , tvl(p_unidade))
     set l_publicar        = .CreateParameter("l_publicar",     adVarchar, adParamInput,   1, tvl(p_publicar))
     set l_chave_nova      = .CreateParameter("l_chave_nova",   adInteger, adParamOutput,   , null)
     
     .parameters.Append         l_operacao
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_copia
     .parameters.Append         l_objeto
     .parameters.Append         l_edital
     .parameters.Append         l_processo
     .parameters.Append         l_empenho
     .parameters.Append         l_abertura
     .parameters.Append         l_fundamentacao
     .parameters.Append         l_observacao
     .parameters.Append         l_modalidade
     .parameters.Append         l_fonte
     .parameters.Append         l_finalidade
     .parameters.Append         l_criterio
     .parameters.Append         l_situacao
     .parameters.Append         l_unidade
     .parameters.Append         l_publicar
     .parameters.Append         l_chave_nova

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutLcPortalLic"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     p_chave_nova = l_chave_nova.Value
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_operacao"
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_copia"
     .Parameters.Delete         "l_objeto"
     .Parameters.Delete         "l_edital"
     .Parameters.Delete         "l_processo"
     .Parameters.Delete         "l_empenho"
     .Parameters.Delete         "l_abertura"
     .Parameters.Delete         "l_fundamentacao"
     .Parameters.Delete         "l_observacao"
     .Parameters.Delete         "l_modalidade"
     .Parameters.Delete         "l_fonte"
     .Parameters.Delete         "l_finalidade"
     .Parameters.Delete         "l_criterio"
     .Parameters.Delete         "l_situacao"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_publicar"
     .parameters.Delete         "l_chave_nova"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de itens de licitação
REM -------------------------------------------------------------------------
Sub DML_PutLcPortalLicItem(Operacao, p_cliente, p_chave, p_chave_aux, p_ordem, _
        p_nome, p_quantidade, p_descricao, p_unidade_fornec, p_cancelado, p_situacao)

  Dim l_Operacao, l_cliente, l_chave, l_chave_aux, l_ordem, l_nome, l_quantidade
  Dim l_descricao, l_unidade_fornec, l_cancelado, l_situacao
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_cliente             = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_ordem               = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_quantidade          = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter")
  Set l_unidade_fornec      = Server.CreateObject("ADODB.Parameter")  
  Set l_cancelado           = Server.CreateObject("ADODB.Parameter") 
  Set l_situacao            = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao        = .CreateParameter("l_operacao",       adVarchar, adParamInput,   1, Operacao)
     set l_cliente         = .CreateParameter("l_cliente",        adInteger, adParamInput,    , p_cliente)
     set l_chave           = .CreateParameter("l_chave",          adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux       = .CreateParameter("l_chave_aux",      adInteger, adParamInput,    , Tvl(p_chave_aux))
     set l_ordem           = .CreateParameter("l_ordem",          adInteger, adParamInput,    , tvl(p_ordem))
     set l_nome            = .CreateParameter("l_nome",           adVarchar, adParamInput,  60, tvl(p_nome))
     set l_quantidade      = .CreateParameter("l_quantidade",     adNumeric ,adParamInput)
     l_quantidade.Precision    = 18
     l_quantidade.NumericScale = 2
     l_quantidade.Value        = Tvl(p_quantidade)
     set l_descricao       = .CreateParameter("l_descricao",      adVarchar, adParamInput,2000, tvl(p_descricao))
     set l_unidade_fornec  = .CreateParameter("l_unidade_fornec", adInteger, adParamInput,    , Tvl(p_unidade_fornec))
     set l_cancelado       = .CreateParameter("l_cancelado",      adVarchar, adParamInput,   1, tvl(p_cancelado))
     set l_situacao        = .CreateParameter("l_situacao",       adVarchar, adParamInput, 500, tvl(p_situacao))
     .parameters.Append         l_Operacao
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_ordem
     .parameters.Append         l_nome
     .parameters.Append         l_quantidade
     .parameters.Append         l_descricao
     .parameters.Append         l_unidade_fornec
     .parameters.Append         l_cancelado
     .parameters.Append         l_situacao
     .CommandText               = Session("schema") & "SP_PutLcPortalLicItem"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_ordem"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_quantidade"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_unidade_fornec"
     .parameters.Delete         "l_cancelado"
     .parameters.Delete         "l_situacao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Grava informações da licitação
REM -------------------------------------------------------------------------
Sub DML_PutLcPortalCont(Operacao, p_cliente, p_chave, p_chave_aux, p_numero, p_objeto, p_processo, p_empenho, _
       p_assinatura, p_vigencia_inicio, p_vigencia_fim, p_publicacao, p_valor, p_pessoa_juridica, _
       p_cnpj, p_cpf, p_nome, p_nome_resumido, p_sexo, p_sq_pessoa,  p_unidade, p_observacao, p_publicar)
       
  Dim l_operacao, l_cliente, l_chave, l_chave_aux, l_numero, l_objeto, l_processo, l_empenho, l_assinatura, l_vigencia_inicio, l_vigencia_fim
  Dim l_publicacao, l_valor, l_pessoa_juridica, l_cnpj, l_cpf, l_nome, l_nome_resumido
  Dim l_sexo, l_sq_pessoa, l_unidade, l_observacao, l_publicar
  
  Set l_operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_cliente         = Server.CreateObject("ADODB.Parameter")
  Set l_chave           = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux       = Server.CreateObject("ADODB.Parameter")
  Set l_numero          = Server.CreateObject("ADODB.Parameter")
  Set l_objeto          = Server.CreateObject("ADODB.Parameter")
  Set l_processo        = Server.CreateObject("ADODB.Parameter")
  Set l_empenho         = Server.CreateObject("ADODB.Parameter")
  Set l_assinatura      = Server.CreateObject("ADODB.Parameter")
  Set l_vigencia_inicio = Server.CreateObject("ADODB.Parameter")
  Set l_vigencia_fim    = Server.CreateObject("ADODB.Parameter")
  Set l_publicacao      = Server.CreateObject("ADODB.Parameter")
  Set l_valor           = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa_juridica = Server.CreateObject("ADODB.Parameter")
  Set l_cnpj            = Server.CreateObject("ADODB.Parameter")
  Set l_cpf             = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  Set l_nome_resumido   = Server.CreateObject("ADODB.Parameter")
  Set l_sexo            = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pessoa       = Server.CreateObject("ADODB.Parameter")
  Set l_unidade         = Server.CreateObject("ADODB.Parameter")
  Set l_observacao      = Server.CreateObject("ADODB.Parameter")
  Set l_publicar        = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_Operacao        = .CreateParameter("l_operacao",         adVarchar, adParamInput,   1, Operacao)
     set l_cliente         = .CreateParameter("l_cliente",          adInteger, adParamInput,    , p_cliente)
     set l_chave           = .CreateParameter("l_chave",            adInteger, adParamInput,    , tvl(p_chave))
     set l_chave_aux       = .CreateParameter("l_chave_aux",        adInteger, adParamInput,    , tvl(p_chave_aux))
     set l_numero          = .CreateParameter("l_numero",           adVarchar, adParamInput,  15, tvl(p_numero))
     set l_objeto          = .CreateParameter("l_objeto",           adVarchar, adParamInput,2000, tvl(p_objeto))
     set l_processo        = .CreateParameter("l_processo",         adVarchar, adParamInput,  30, tvl(p_processo))
     set l_empenho         = .CreateParameter("l_empenho",          adVarchar, adParamInput,  30, tvl(p_empenho))
     set l_assinatura      = .CreateParameter("l_assinatura",       adDate,    adParamInput,    , tvl(p_assinatura))
     set l_vigencia_inicio = .CreateParameter("l_vigencia_inicio",  adDate,    adParamInput,    , tvl(p_vigencia_inicio))
     set l_vigencia_fim    = .CreateParameter("l_vigencia_fim",     adDate,    adParamInput,    , tvl(p_vigencia_fim))
     set l_publicacao      = .CreateParameter("l_publicacao",       adDate,    adParamInput,    , tvl(p_publicacao))
     set l_valor           = .CreateParameter("l_valor",            adNumeric, adParamInput)
     l_valor.Precision     = 18
     l_valor.NumericScale  = 2
     l_valor.Value         = Tvl(p_valor)
     set l_pessoa_juridica = .CreateParameter("l_pessoa_juridica",  adVarchar, adParamInput,   1, tvl(p_pessoa_juridica))
     set l_cnpj            = .CreateParameter("l_cnpj",             adVarchar, adParamInput,  18, tvl(p_cnpj))
     set l_cpf             = .CreateParameter("l_cpf",              adVarchar, adParamInput,  14, tvl(p_cpf))
     set l_nome            = .CreateParameter("l_nome",             adVarchar, adParamInput,  60, tvl(p_nome))
     set l_nome_resumido   = .CreateParameter("l_nome_resumido",    adVarchar, adParamInput,  15, tvl(p_nome_resumido))
     set l_sexo            = .CreateParameter("l_sexo",             adVarchar, adParamInput,   1, tvl(p_sexo))
     set l_sq_pessoa       = .CreateParameter("l_sq_pessoa",        adInteger, adParamInput,    , tvl(p_sq_pessoa))
     set l_unidade         = .CreateParameter("l_unidade",          adInteger, adParamInput,    , tvl(p_unidade))
     set l_observacao      = .CreateParameter("l_observacao",       adVarchar, adParamInput,1000, tvl(p_observacao))
     set l_publicar        = .CreateParameter("l_publicar",         adVarchar, adParamInput,   1, tvl(p_publicar))
     
     .parameters.Append         l_operacao
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_numero
     .parameters.Append         l_objeto
     .parameters.Append         l_processo
     .parameters.Append         l_empenho
     .parameters.Append         l_assinatura
     .parameters.Append         l_vigencia_inicio
     .parameters.Append         l_vigencia_fim
     .parameters.Append         l_publicacao
     .parameters.Append         l_valor
     .parameters.Append         l_pessoa_juridica
     .parameters.Append         l_cnpj
     .parameters.Append         l_cpf
     .parameters.Append         l_nome
     .parameters.Append         l_nome_resumido
     .parameters.Append         l_sexo
     .parameters.Append         l_sq_pessoa
     .parameters.Append         l_unidade
     .parameters.Append         l_observacao
     .parameters.Append         l_publicar

     .CommandText               = Session("schema") & "SP_PutLcPortalCont"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .Parameters.Delete         "l_operacao"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_numero"
     .parameters.Delete         "l_objeto"
     .parameters.Delete         "l_processo"
     .parameters.Delete         "l_empenho"
     .parameters.Delete         "l_assinatura"
     .parameters.Delete         "l_vigencia_inicio"
     .parameters.Delete         "l_vigencia_fim"
     .parameters.Delete         "l_publicacao"
     .parameters.Delete         "l_valor"
     .parameters.Delete         "l_pessoa_juridica"
     .parameters.Delete         "l_cnpj"
     .parameters.Delete         "l_cpf"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_nome_resumido"
     .parameters.Delete         "l_sexo"
     .parameters.Delete         "l_sq_pessoa"
     .parameters.Delete         "l_unidade"
     .parameters.Delete         "l_observacao"
     .parameters.Delete         "l_publicar"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de áreas envolvidas na execução de um Projeto
REM -------------------------------------------------------------------------
Sub DML_PutLcPortalContItem(Operacao, p_chave, p_sq_portal_lic_item, p_valor, p_quantidade)
  Dim l_Operacao, l_chave, l_sq_portal_lic_item, l_valor, l_quantidade
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter")
  Set l_sq_portal_lic_item  = Server.CreateObject("ADODB.Parameter")
  Set l_valor               = Server.CreateObject("ADODB.Parameter") 
  Set l_quantidade          = Server.CreateObject("ADODB.Parameter")  
  with sp
     set l_Operacao             = .CreateParameter("l_Operacao",           adVarchar, adParamInput, 1, Operacao)
     set l_chave                = .CreateParameter("l_chave",              adInteger, adParamInput,  ,p_chave)
     set l_sq_portal_lic_item   = .CreateParameter("l_sq_portal_lic_item", adInteger, adParamInput,  ,tvl(p_sq_portal_lic_item))
     set l_valor                = .CreateParameter("l_valor",           adNumeric ,adParamInput)',    , Tvl(p_valor))
     l_valor.Precision    = 18
     l_valor.NumericScale = 2
     l_valor.Value        = Tvl(p_valor)
     set l_quantidade           = .CreateParameter("l_quantidade",         adInteger, adParamInput,  ,tvl(p_quantidade))
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_sq_portal_lic_item
     .parameters.Append         l_valor
     .parameters.Append         l_quantidade
     .CommandText               = Session("schema") & "SP_PutLcPortalContItem"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_sq_portal_lic_item"
     .parameters.Delete         "l_valor"
     .parameters.Delete         "l_quantidade"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de arquivos
REM -------------------------------------------------------------------------
Sub DML_PutLcArquivo(Operacao, p_cliente, p_chave, p_chave_aux, p_nome, p_descricao, _
    p_caminho, p_tamanho, p_tipo)
    
  Dim l_Operacao, l_Chave, l_cliente, l_chave_aux, l_nome, l_descricao
  Dim l_caminho, l_tamanho, l_tipo
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_cliente             = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  Set l_caminho             = Server.CreateObject("ADODB.Parameter") 
  Set l_tamanho             = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo                = Server.CreateObject("ADODB.Parameter") 

  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_cliente              = .CreateParameter("l_cliente",         adInteger, adParamInput,    , p_cliente)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",       adInteger, adParamInput,    , Tvl(p_chave_aux))
     set l_nome                 = .CreateParameter("l_nome",            adVarchar, adParamInput, 255, Tvl(p_nome))
     set l_descricao            = .CreateParameter("l_descricao",       adVarchar, adParamInput,1000, Tvl(p_descricao))
     set l_caminho              = .CreateParameter("l_caminho",         adVarchar, adParamInput, 255, Tvl(p_caminho))
     set l_tamanho              = .CreateParameter("l_tamanho",         adInteger, adParamInput,    , Tvl(p_tamanho))
     set l_tipo                 = .CreateParameter("l_tipo",            adVarchar, adParamInput,  60, Tvl(p_tipo))
     .parameters.Append         l_Operacao
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_nome
     .parameters.Append         l_descricao
     .parameters.Append         l_caminho
     .parameters.Append         l_tamanho
     .parameters.Append         l_tipo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutLcArquivo"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_caminho"
     .parameters.Delete         "l_tamanho"
     .parameters.Delete         "l_tipo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>
