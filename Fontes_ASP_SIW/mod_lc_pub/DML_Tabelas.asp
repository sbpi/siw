<%
REM =========================================================================
REM Mantém a tabela de critérios de julgamento de licitações
REM -------------------------------------------------------------------------
Sub DML_PutLcCriterio(Operacao, p_chave, p_cliente, p_nome, p_descricao, p_item, p_ativo, p_padrao)


  Dim l_Operacao, l_Chave, l_cliente, l_nome, l_descricao, l_item, l_ativo, l_padrao
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao               = Server.CreateObject("ADODB.Parameter") 
  Set l_item                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  Set l_padrao                  = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  80, Tvl(p_nome))
     set l_descricao            = .CreateParameter("l_descricao",           adVarchar, adParamInput,1000, Tvl(p_descricao))
     set l_item                 = .CreateParameter("l_item",                adVarchar, adParamInput,   1, Tvl(p_item))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,   1, Tvl(p_ativo))
     set l_padrao               = .CreateParameter("l_padrao",              adVarchar, adParamInput,   1, Tvl(p_padrao))
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_descricao
     .parameters.Append         l_item
     .parameters.Append         l_ativo
     .parameters.Append         l_padrao

     .CommandText               = Session("schema") & "SP_PutLcCriterio"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_item"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_padrao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de situações de licitação
REM -------------------------------------------------------------------------
Sub DML_PutLcSituacao(Operacao, p_chave, p_cliente, p_nome, p_descricao, p_ativo, p_padrao, p_publicar)


  Dim l_Operacao, l_Chave, l_cliente, l_nome, l_descricao, l_ativo, l_padrao, l_publicar
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao               = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  Set l_padrao                  = Server.CreateObject("ADODB.Parameter") 
  Set l_publicar                = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  80, Tvl(p_nome))
     set l_descricao            = .CreateParameter("l_descricao",           adVarchar, adParamInput,1000, Tvl(p_descricao))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,   1, Tvl(p_ativo))
     set l_padrao               = .CreateParameter("l_padrao",              adVarchar, adParamInput,   1, Tvl(p_padrao))
     set l_publicar             = .CreateParameter("l_publicar",            adVarchar, adParamInput,   1, Tvl(p_publicar))
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_descricao
     .parameters.Append         l_ativo
     .parameters.Append         l_padrao
     .parameters.Append         l_publicar

     .CommandText               = Session("schema") & "SP_PutLcSituacao"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_publicar"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de fontes de recurso
REM -------------------------------------------------------------------------
Sub DML_PutLcFonte(Operacao, p_chave, p_cliente, p_nome, p_descricao, p_ativo, p_padrao, p_orcamentario)


  Dim l_Operacao, l_Chave, l_cliente, l_nome, l_descricao, l_ativo, l_padrao, l_orcamentario
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao               = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  Set l_padrao                  = Server.CreateObject("ADODB.Parameter") 
  Set l_orcamentario            = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  80, Tvl(p_nome))
     set l_descricao            = .CreateParameter("l_descricao",           adVarchar, adParamInput,1000, Tvl(p_descricao))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,   1, Tvl(p_ativo))
     set l_padrao               = .CreateParameter("l_padrao",              adVarchar, adParamInput,   1, Tvl(p_padrao))
     set l_orcamentario         = .CreateParameter("l_orcamentario",        adVarchar, adParamInput,   1, Tvl(p_orcamentario))
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_descricao
     .parameters.Append         l_ativo
     .parameters.Append         l_padrao
     .parameters.Append         l_orcamentario

     .CommandText               = Session("schema") & "SP_PutLcFonte"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_orcamentario"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de modalidades de licitação
REM -------------------------------------------------------------------------
Sub DML_PutLcModalidade(Operacao, p_chave, p_cliente, p_nome, p_sigla, p_descricao, p_fundamentacao, p_ativo, p_padrao)


  Dim l_Operacao, l_Chave, l_cliente, l_nome, l_sigla, l_descricao, l_fundamentacao, l_ativo, l_padrao
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_sigla                   = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao               = Server.CreateObject("ADODB.Parameter")
  Set l_fundamentacao           = Server.CreateObject("ADODB.Parameter")  
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  Set l_padrao                  = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  80, Tvl(p_nome))
     set l_sigla                = .CreateParameter("l_sigla",               adVarchar, adParamInput,   3, Tvl(p_sigla))
     set l_descricao            = .CreateParameter("l_descricao",           adVarchar, adParamInput,1000, Tvl(p_descricao))
     set l_fundamentacao        = .CreateParameter("l_fundamentacao",       adVarchar, adParamInput, 250, Tvl(p_fundamentacao))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,   1, Tvl(p_ativo))
     set l_padrao               = .CreateParameter("l_padrao",              adVarchar, adParamInput,   1, Tvl(p_padrao))
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_sigla
     .parameters.Append         l_descricao
     .parameters.Append         l_fundamentacao
     .parameters.Append         l_ativo
     .parameters.Append         l_padrao

     .CommandText               = Session("schema") & "SP_PutLcModalidade"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_fundamentacao"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_padrao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém as finalidades de licitação
REM -------------------------------------------------------------------------
Sub DML_PutLcFinalidade(Operacao, p_chave, p_cliente, p_nome, p_descricao, p_ativo, p_padrao)


  Dim l_Operacao, l_Chave, l_cliente, l_nome, l_descricao, l_ativo, l_padrao
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao               = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  Set l_padrao                  = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  80, Tvl(p_nome))
     set l_descricao            = .CreateParameter("l_descricao",           adVarchar, adParamInput,1000, Tvl(p_descricao))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,   1, Tvl(p_ativo))
     set l_padrao               = .CreateParameter("l_padrao",              adVarchar, adParamInput,   1, Tvl(p_padrao))
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_descricao
     .parameters.Append         l_ativo
     .parameters.Append         l_padrao

     .CommandText               = Session("schema") & "SP_PutLcFinalidade"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_padrao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém as finalidades de licitação
REM -------------------------------------------------------------------------
Sub DML_PutLcUnidade(Operacao, p_chave, p_cnpj, p_licita, p_contrata, p_ativo, p_padrao)

  Dim l_Operacao, l_Chave, l_cnpj, l_licita, l_contrata, l_ativo, l_padrao
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_cnpj                    = Server.CreateObject("ADODB.Parameter") 
  Set l_licita                  = Server.CreateObject("ADODB.Parameter") 
  Set l_contrata                = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  Set l_padrao                  = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    ,p_chave)
     set l_cnpj                 = .CreateParameter("l_cnpj",                adVarchar, adParamInput,  20, Tvl(p_cnpj))
     set l_licita               = .CreateParameter("l_licita",              adVarchar, adParamInput,   1, Tvl(p_licita))
     set l_contrata             = .CreateParameter("l_contrata",            adVarchar, adParamInput,   1, Tvl(p_contrata))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,   1, Tvl(p_ativo))
     set l_padrao               = .CreateParameter("l_padrao",              adVarchar, adParamInput,   1, Tvl(p_padrao))
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cnpj
     .parameters.Append         l_licita
     .parameters.Append         l_contrata
     .parameters.Append         l_ativo
     .parameters.Append         l_padrao

     .CommandText               = Session("schema") & "SP_PutLcUnidade"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cnpj"
     .parameters.Delete         "l_licita"
     .parameters.Delete         "l_contrata"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_padrao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------


REM =========================================================================
REM Mantém as unidades de fornecimento
REM -------------------------------------------------------------------------
Sub DML_PutLcUnidadeFornec(Operacao, p_chave, p_cliente, p_sigla, p_nome, p_descricao, p_ativo, p_padrao)


  Dim l_Operacao, l_Chave, l_cliente, l_sigla, l_nome, l_descricao, l_ativo, l_padrao
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_sigla                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao               = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  Set l_padrao                  = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,    , Tvl(p_cliente))
     set l_sigla                = .CreateParameter("l_sigla",               adVarchar, adParamInput,  10, Tvl(p_sigla))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  60, Tvl(p_nome))
     set l_descricao            = .CreateParameter("l_descricao",           adVarchar, adParamInput,1000, Tvl(p_descricao))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,   1, Tvl(p_ativo))
     set l_padrao               = .CreateParameter("l_padrao",              adVarchar, adParamInput,   1, Tvl(p_padrao))
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_sigla
     .parameters.Append         l_nome
     .parameters.Append         l_descricao
     .parameters.Append         l_ativo
     .parameters.Append         l_padrao

     .CommandText               = Session("schema") & "SP_PutLcUnidadeFornec"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_padrao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>
