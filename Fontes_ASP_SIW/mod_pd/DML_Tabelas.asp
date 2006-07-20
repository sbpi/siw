<%
REM =========================================================================
REM Mantém a tabela de tipos de contrato
REM -------------------------------------------------------------------------
Sub DML_PutAgreeType(Operacao, p_chave, p_chave_pai, p_cliente, p_nome, p_sigla, p_modalidade, _
       p_prazo_indeterm, p_pessoa_juridica, p_pessoa_fisica, p_ativo)
  Dim l_Operacao, l_chave, l_chave_pai, l_cliente, l_nome, l_sigla, l_modalidade
  Dim l_prazo_indeterm, l_pessoa_juridica, l_pessoa_fisica, l_ativo
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_pai           = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente             = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_sigla               = Server.CreateObject("ADODB.Parameter") 
  Set l_modalidade          = Server.CreateObject("ADODB.Parameter") 
  Set l_prazo_indeterm      = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa_juridica     = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa_fisica       = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo               = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_pai            = .CreateParameter("l_chave_pai",       adInteger, adParamInput,    , Tvl(p_chave_pai))
     set l_cliente              = .CreateParameter("l_cliente",         adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome                 = .CreateParameter("l_nome",            adVarchar, adParamInput,  60, Tvl(p_nome))
     set l_sigla                = .CreateParameter("l_sigla",           adVarchar, adParamInput,  10, Tvl(p_sigla))
     set l_modalidade           = .CreateParameter("l_modalidade",      adVarchar, adParamInput,   1, Tvl(p_modalidade))
     set l_prazo_indeterm       = .CreateParameter("l_prazo_indeterm",  adVarchar, adParamInput,   1, Tvl(p_prazo_indeterm))
     set l_pessoa_juridica      = .CreateParameter("l_pessoa_juridica", adVarchar, adParamInput,   1, Tvl(p_pessoa_juridica))
     set l_pessoa_fisica        = .CreateParameter("l_pessoa_fisica",   adVarchar, adParamInput,   1, Tvl(p_pessoa_fisica))
     set l_ativo                = .CreateParameter("l_ativo",           adVarchar, adParamInput,   1, Tvl(p_ativo))
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_pai
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_sigla
     .parameters.Append         l_modalidade
     .parameters.Append         l_prazo_indeterm
     .parameters.Append         l_pessoa_juridica
     .parameters.Append         l_pessoa_fisica
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutAgreeType"
     On Error Resume Next
     .Execute
     If Err.Number <> 0 Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_pai"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_modalidade"
     .parameters.Delete         "l_prazo_indeterm"
     .parameters.Delete         "l_pessoa_juridica"
     .parameters.Delete         "l_pessoa_fisica"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantém a tabela de companhias de viagem
REM -------------------------------------------------------------------------
Sub DML_PutCiaTrans(Operacao, p_cliente, p_chave, p_nome, p_aereo, p_rodoviario, _
                     p_aquaviario, p_padrao, p_ativo)
  Dim l_Operacao, l_cliente, l_chave, l_nome, l_aereo, l_rodoviario
  Dim l_aquaviario, l_padrao, l_ativo
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_cliente             = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_aereo               = Server.CreateObject("ADODB.Parameter") 
  Set l_rodoviario          = Server.CreateObject("ADODB.Parameter") 
  Set l_aquaviario          = Server.CreateObject("ADODB.Parameter") 
  Set l_padrao              = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo               = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_cliente              = .CreateParameter("l_cliente",         adInteger, adParamInput,    , Tvl(p_cliente))     
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",            adVarchar, adParamInput,  60, Tvl(p_nome))
     set l_aereo                = .CreateParameter("l_aereo",           adVarchar, adParamInput,   1, Tvl(p_aereo))
     set l_rodoviario           = .CreateParameter("l_rodoviario",      adVarchar, adParamInput,   1, Tvl(p_rodoviario))
     set l_aquaviario           = .CreateParameter("l_aquaviario",      adVarchar, adParamInput,   1, Tvl(p_aquaviario))
     set l_padrao               = .CreateParameter("l_padrao",          adVarchar, adParamInput,   1, Tvl(p_padrao))
     set l_ativo                = .CreateParameter("l_ativo",           adVarchar, adParamInput,   1, Tvl(p_ativo))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_nome
     .parameters.Append         l_aereo
     .parameters.Append         l_rodoviario
     .parameters.Append         l_aquaviario
     .parameters.Append         l_padrao
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutCiaTrans"
     On Error Resume Next
     .Execute
     If Err.Number <> 0 Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_aereo"
     .parameters.Delete         "l_rodoviario"
     .parameters.Delete         "l_aquaviario"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantém a tabela de parâmetros do módulo de passagens e diárias
REM -------------------------------------------------------------------------
Sub DML_PutPDParametro(p_cliente, p_sequencial, p_ano_corrente, p_prefixo, p_sufixo, _
                       p_dias_antecedencia, p_dias_prest_contas, p_limite_unidade)
  Dim l_cliente, l_sequencial, l_ano_corrente, l_prefixo, l_sufixo
  Dim l_dias_antecedencia, l_dias_prest_contas, l_limite_unidade
  
  Set l_cliente             = Server.CreateObject("ADODB.Parameter") 
  Set l_sequencial          = Server.CreateObject("ADODB.Parameter") 
  Set l_ano_corrente        = Server.CreateObject("ADODB.Parameter") 
  Set l_prefixo             = Server.CreateObject("ADODB.Parameter") 
  Set l_sufixo              = Server.CreateObject("ADODB.Parameter") 
  Set l_dias_antecedencia   = Server.CreateObject("ADODB.Parameter") 
  Set l_dias_prest_contas   = Server.CreateObject("ADODB.Parameter")
  Set l_limite_unidade      = Server.CreateObject("ADODB.Parameter")  
  
  with sp
     set l_cliente              = .CreateParameter("l_cliente",           adInteger, adParamInput,    , Tvl(p_cliente))     
     set l_sequencial           = .CreateParameter("l_sequencial",        adInteger, adParamInput,    , Tvl(p_sequencial))
     set l_ano_corrente         = .CreateParameter("l_ano_corrente",      adInteger, adParamInput,    , Tvl(p_ano_corrente))
     set l_prefixo              = .CreateParameter("l_prefixo",           adVarchar, adParamInput,  10, Tvl(p_prefixo))
     set l_sufixo               = .CreateParameter("l_sufixo",            adVarchar, adParamInput,  10, Tvl(p_sufixo))
     set l_dias_antecedencia    = .CreateParameter("l_dias_antecedencia", adInteger, adParamInput,    , Tvl(p_dias_antecedencia))
     set l_dias_prest_contas    = .CreateParameter("l_dias_prest_contas", adInteger, adParamInput,    , Tvl(p_dias_prest_contas))
     set l_limite_unidade       = .CreateParameter("l_limite_unidade",    adVarchar, adParamInput,   1, Tvl(p_limite_unidade))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_sequencial
     .parameters.Append         l_ano_corrente
     .parameters.Append         l_prefixo
     .parameters.Append         l_sufixo
     .parameters.Append         l_dias_antecedencia
     .parameters.Append         l_dias_prest_contas
     .parameters.Append         l_limite_unidade
     .CommandText               = Session("schema") & "SP_PutPDParametro"
     On Error Resume Next
     .Execute
     If Err.Number <> 0 Then 
        TrataErro
     End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_sequencial"
     .parameters.Delete         "l_ano_corrente"
     .parameters.Delete         "l_prefixo"
     .parameters.Delete         "l_sufixo"
     .parameters.Delete         "l_dias_antecedencia"
     .parameters.Delete         "l_dias_prest_contas"
     .parameters.Delete         "l_limite_unidade"
  end with
End Sub

REM =========================================================================
REM Mantém as unidades do módulo de passagens e diárias
REM -------------------------------------------------------------------------
Sub DML_PutPDUnidade(Operacao, p_chave, p_ativo)

  Dim l_Operacao, l_Chave, l_ativo
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , p_chave)
     set l_ativo              = .CreateParameter("l_ativo",                 adVarchar, adParamInput,   1, p_ativo)
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_ativo

     .CommandText               = Session("schema") & "SP_PutPDUnidade"
     On Error Resume Next
     .Execute
     If Err.Number <> 0 Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantém os limites das unidades do módulo de passagens e diárias
REM -------------------------------------------------------------------------
Sub DML_PutPDUnidLimite(Operacao, p_chave, p_limite_passagem, p_limite_diaria, p_ano)

  Dim l_Operacao, l_Chave, l_limite_passagem, l_limite_diaria, l_ano
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_limite_passagem         = Server.CreateObject("ADODB.Parameter") 
  Set l_limite_diaria           = Server.CreateObject("ADODB.Parameter")
  Set l_ano                     = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , p_chave)
     set l_limite_passagem      = .CreateParameter("l_limite_passagem",     adNumeric ,adParamInput)
     l_limite_passagem.Precision    = 18
     l_limite_passagem.NumericScale = 2
     l_limite_passagem.Value        = Tvl(p_limite_passagem)     
     set l_limite_diaria      = .CreateParameter("l_limite_diaria",         adNumeric ,adParamInput)
     l_limite_diaria.Precision    = 18
     l_limite_diaria.NumericScale = 2
     l_limite_diaria.Value        = Tvl(p_limite_diaria)          
     set l_ano                = .CreateParameter("l_ano",                   adInteger, adParamInput,    , p_ano)
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_limite_passagem
     .parameters.Append         l_limite_diaria
     .parameters.Append         l_ano

     .CommandText               = Session("schema") & "SP_PutPDUnidLimite"
     On Error Resume Next
     .Execute
     If Err.Number <> 0 Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_limite_passagem"
     .parameters.Delete         "l_limite_diaria"
     .parameters.Delete         "l_ano"
  end with
End Sub

REM =========================================================================
REM Mantém os usuários do módulo de passagens e diárias
REM -------------------------------------------------------------------------
Sub DML_PutPDUsuario(Operacao, p_cliente, p_chave)

  Dim l_Operacao, l_cliente, l_chave
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , p_chave)
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,    , p_cliente)
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_cliente
     .parameters.Append         l_chave

     .CommandText               = Session("schema") & "SP_PutPDUsuario"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
  end with
End Sub
%>