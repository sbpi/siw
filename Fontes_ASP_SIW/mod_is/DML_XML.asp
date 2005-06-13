<%
REM =========================================================================
REM Mantйm a tabela PPA - Esfera
REM -------------------------------------------------------------------------
Sub DML_PutXMLEsfera(p_chave, p_nome, p_ativo)


  Dim l_Chave, l_nome, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,110, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLEsfera"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Unidade de Medida
REM -------------------------------------------------------------------------
Sub DML_PutXMLUnidade_Medida_PPA(p_chave, p_nome, p_ativo)


  Dim l_Chave, l_nome, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,110, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLUnidade_Medida_PPA"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Уrgao
REM -------------------------------------------------------------------------
Sub DML_PutXMLOrgao_PPA(p_chave, p_tipo_org, p_nome, p_sigla, p_ativo)


  Dim l_Chave, l_tipo_org, l_nome, l_sigla, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_org                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_sigla                   = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,  5, Tvl(p_chave))
     set l_tipo_org             = .CreateParameter("l_tipo_org",            adVarchar, adParamInput,  1, Tvl(p_tipo_org))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,110, Tvl(p_nome))
     set l_sigla                = .CreateParameter("l_sigla",               adVarchar, adParamInput, 10, Tvl(p_sigla))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_tipo_org
     .parameters.Append         l_nome
     .parameters.Append         l_sigla
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLOrgao_PPA"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_tipo_org"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Уrgao
REM -------------------------------------------------------------------------
Sub DML_PutXMLOrgao_Siorg_PPA(p_chave, p_pai, p_nome, p_orgao, p_tipo_org, p_ativo)


  Dim l_Chave, l_pai, l_tipo_org, l_nome, l_orgao, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_pai                     = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_orgao                   = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_org                = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_pai                  = .CreateParameter("l_pai",                 adInteger, adParamInput,   , Tvl(p_pai))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,255, Tvl(p_nome))
     set l_orgao                = .CreateParameter("l_orgao",               adVarchar, adParamInput,  5, Tvl(p_orgao))
     set l_tipo_org             = .CreateParameter("l_tipo_org",            adVarchar, adParamInput,  1, Tvl(p_tipo_org))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_pai
     .parameters.Append         l_nome
     .parameters.Append         l_orgao
     .parameters.Append         l_tipo_org
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLOrgao_Siorg_PPA"
     'On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_pai"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_orgao"
     .parameters.Delete         "l_tipo_org"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Unidade
REM -------------------------------------------------------------------------
Sub DML_PutXMLUnidade_PPA(p_chave, p_tipo_unid, p_orgao, p_tipo_org, p_nome)


  Dim l_Chave, l_tipo_unid, l_orgao, l_tipo_org, l_nome
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_unid               = Server.CreateObject("ADODB.Parameter") 
  Set l_orgao                   = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_org                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,  5, Tvl(p_chave))
     set l_tipo_unid            = .CreateParameter("l_tipo_unid",           adVarchar, adParamInput,  1, Tvl(p_tipo_unid))
     set l_orgao                = .CreateParameter("l_orgao",               adVarchar, adParamInput,  5, Tvl(p_orgao))
     set l_tipo_org             = .CreateParameter("l_tipo_org",            adVarchar, adParamInput,  1, Tvl(p_tipo_org))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,110, Tvl(p_nome))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_tipo_unid
     .parameters.Append         l_orgao
     .parameters.Append         l_tipo_org
     .parameters.Append         l_nome
  
     .CommandText               = Session("schema_is") & "SP_PutXMLUnidade_PPA"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_tipo_unid"
     .parameters.Delete         "l_orgao"
     .parameters.Delete         "l_tipo_org"
     .parameters.Delete         "l_nome"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Tipo de aзгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLTipo_Acao_PPA(p_chave, p_nome, p_ativo)


  Dim l_Chave, l_nome, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput, 50, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLTipo_Acao_PPA"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Tipo de despesa
REM -------------------------------------------------------------------------
Sub DML_PutXMLTipo_Despesa(p_chave, p_nome, p_ativo)


  Dim l_Chave, l_nome, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,100, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLTipo_Despesa"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Tipo de atualizaзгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLTipo_Atualizacao(p_chave, p_nome, p_ativo)


  Dim l_Chave, l_nome, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,100, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLTipo_Atualizacao"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Tipo de programa
REM -------------------------------------------------------------------------
Sub DML_PutXMLTipo_Programa_PPA(p_chave, p_nome, p_ativo)


  Dim l_Chave, l_nome, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput, 50, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLTipo_Programa_PPA"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Tipo de inclusгo da aзгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLTipo_Inclusao_Acao(p_chave, p_nome, p_ativo)


  Dim l_Chave, l_nome, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,250, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLTipo_Inclusao_Acao"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Natureza
REM -------------------------------------------------------------------------
Sub DML_PutXMLNatureza(p_chave, p_nome, p_desc, p_ativo)


  Dim l_Chave, l_nome, l_desc, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_desc                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput, 50, Tvl(p_nome))
     set l_desc                 = .CreateParameter("l_desc",                adVarchar, adParamInput, 50, Tvl(p_desc))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_desc
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLNatureza"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_desc"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Funзгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLFuncao(p_chave, p_nome, p_ativo)


  Dim l_Chave, l_nome, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,110, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLFuncao"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Fonte
REM -------------------------------------------------------------------------
Sub DML_PutXMLFonte_PPA(p_chave, p_nome, p_desc, p_total)


  Dim l_Chave, l_nome, l_desc, l_total
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_desc                    = Server.CreateObject("ADODB.Parameter") 
  Set l_total                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,  5, Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput, 60, Tvl(p_nome))
     set l_desc                 = .CreateParameter("l_desc",                adVarchar, adParamInput, 60, Tvl(p_desc))
     set l_total                = .CreateParameter("l_total",               adVarchar, adParamInput,  1, Tvl(p_total))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_desc
     .parameters.Append         l_total
  
     .CommandText               = Session("schema_is") & "SP_PutXMLFonte_PPA"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_desc"
     .parameters.Delete         "l_total"
  end with
End Sub


REM =========================================================================
REM Mantйm a tabela PPA - Regiгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLRegiao(p_chave, p_nome, p_uf, p_regiao)


  Dim l_Chave, l_nome, l_uf, l_regiao
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_uf                      = Server.CreateObject("ADODB.Parameter") 
  Set l_regiao                  = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,  2, Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,120, Tvl(p_nome))
     set l_uf                   = .CreateParameter("l_uf",                  adVarchar, adParamInput, 20, Tvl(p_uf))
     set l_regiao               = .CreateParameter("l_regiao",              adVarchar, adParamInput,  2, Tvl(p_regiao))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_uf
     .parameters.Append         l_regiao
  
     .CommandText               = Session("schema_is") & "SP_PutXMLRegiao"
     On error Resume Next
     .Execute
     If Err.ufription > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_uf"
     .parameters.Delete         "l_regiao"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Tipo de Уrgгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLTipo_Orgao_SIG(p_chave, p_nome, p_ativo)


  Dim l_Chave, l_nome, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,  1, Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput, 10, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLTipo_Orgao_SIG"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub


REM =========================================================================
REM Mantйm a tabela PPA - Subfunзгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLSubfuncao(p_chave, p_funcao, p_desc)


  Dim l_Chave, l_funcao, l_desc
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_funcao                  = Server.CreateObject("ADODB.Parameter") 
  Set l_desc                    = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",             adVarchar, adParamInput,  3, Tvl(p_chave))
     set l_funcao               = .CreateParameter("l_funcao",            adVarchar, adParamInput,  2, Tvl(p_funcao))
     set l_desc                 = .CreateParameter("l_desc",              adVarchar, adParamInput,120, Tvl(p_desc))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_funcao
     .parameters.Append         l_desc
  
     .CommandText               = Session("schema_is") & "SP_PutXMLSubfuncao"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_funcao"
     .parameters.Delete         "l_desc"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Produto
REM -------------------------------------------------------------------------
Sub DML_PutXMLProduto_PPA(p_chave, p_nome, p_ativo)


  Dim l_Chave, l_nome, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,110, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLProduto_PPA"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela de municнpios
REM -------------------------------------------------------------------------
Sub DML_PutXMLMunicipio(p_chave, p_regiao, p_nome)


  Dim l_Chave, l_regiao, l_nome
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_regiao                  = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,  7, Tvl(p_chave))
     set l_regiao               = .CreateParameter("l_regiao",              adVarchar, adParamInput,  2, Tvl(p_regiao))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput, 50, Tvl(p_nome))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_regiao
     .parameters.Append         l_nome
  
     .CommandText               = Session("schema_is") & "SP_PutXMLMunicipio"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_regiao"
     .parameters.Delete         "l_nome"
  end with
End Sub

%>