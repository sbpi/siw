<%
REM =========================================================================
REM Mantйm a tabela PPA - Esfera
REM -------------------------------------------------------------------------
Sub DML_PutXMLEsfera(p_resultado, p_chave, p_nome, p_ativo)


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
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Unidade de Medida
REM -------------------------------------------------------------------------
Sub DML_PutXMLUnidade_Medida_PPA(p_resultado, p_chave, p_nome, p_ativo)


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
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Уrgao
REM -------------------------------------------------------------------------
Sub DML_PutXMLOrgao_PPA(p_resultado, p_chave, p_tipo_org, p_nome, p_sigla, p_ativo)


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
        p_resultado = Err.Description
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
Sub DML_PutXMLOrgao_Siorg_PPA(p_resultado, p_chave, p_pai, p_nome, p_orgao, p_tipo_org, p_ativo)


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
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
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
Sub DML_PutXMLUnidade_PPA(p_resultado, p_chave, p_tipo_unid, p_orgao, p_tipo_org, p_nome)


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
        p_resultado = Err.Description
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
Sub DML_PutXMLTipo_Acao_PPA(p_resultado, p_chave, p_nome, p_ativo)


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
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Tipo de despesa
REM -------------------------------------------------------------------------
Sub DML_PutXMLTipo_Despesa(p_resultado, p_chave, p_nome, p_ativo)


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
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Tipo de atualizaзгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLTipo_Atualizacao(p_resultado, p_chave, p_nome, p_ativo)


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
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Tipo de programa
REM -------------------------------------------------------------------------
Sub DML_PutXMLTipo_Programa_PPA(p_resultado, p_chave, p_nome, p_ativo)


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
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Tipo de inclusгo da aзгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLTipo_Inclusao_Acao(p_resultado, p_chave, p_nome, p_ativo)


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
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Natureza
REM -------------------------------------------------------------------------
Sub DML_PutXMLNatureza(p_resultado, p_chave, p_nome, p_desc, p_ativo)


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
        p_resultado = Err.Description
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
Sub DML_PutXMLFuncao(p_resultado, p_chave, p_nome, p_ativo)


  Dim l_Chave, l_nome, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,  2, Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,110, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLFuncao"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Fonte
REM -------------------------------------------------------------------------
Sub DML_PutXMLFonte_PPA(p_resultado, p_chave, p_nome, p_desc, p_total)


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
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_desc"
     .parameters.Delete         "l_total"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIG - Fonte
REM -------------------------------------------------------------------------
Sub DML_PutXMLFonte_SIG(p_resultado, p_chave, p_nome, p_desc, p_observ, p_total, p_ativo)


  Dim l_Chave, l_nome, l_desc, l_observ, l_total, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_desc                    = Server.CreateObject("ADODB.Parameter") 
  Set l_observ                  = Server.CreateObject("ADODB.Parameter") 
  Set l_total                   = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,   5, Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  60, Tvl(p_nome))
     set l_desc                 = .CreateParameter("l_desc",                adVarchar, adParamInput,  60, Tvl(p_desc))
     set l_observ               = .CreateParameter("l_observ",              adVarchar, adParamInput,2000, Tvl(p_observ))
     set l_total                = .CreateParameter("l_total",               adVarchar, adParamInput,   1, Tvl(p_total))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,   1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_desc
     .parameters.Append         l_observ
     .parameters.Append         l_total
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLFonte_SIG"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_desc"
     .parameters.Delete         "l_observ"
     .parameters.Delete         "l_total"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Regiгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLRegiao(p_resultado, p_chave, p_nome, p_uf, p_regiao)


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
     If Err.Description > "" Then 
        p_resultado = Err.Description
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
Sub DML_PutXMLTipo_Orgao_SIG(p_resultado, p_chave, p_nome, p_ativo)


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
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub


REM =========================================================================
REM Mantйm a tabela PPA - Subfunзгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLSubfuncao(p_resultado, p_chave, p_funcao, p_desc)


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
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_funcao"
     .parameters.Delete         "l_desc"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Produto
REM -------------------------------------------------------------------------
Sub DML_PutXMLProduto_PPA(p_resultado, p_chave, p_nome, p_ativo)


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
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela de municнpios
REM -------------------------------------------------------------------------
Sub DML_PutXMLMunicipio(p_resultado, p_chave, p_regiao, p_nome)


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
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_regiao"
     .parameters.Delete         "l_nome"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Programa
REM -------------------------------------------------------------------------
Sub DML_PutXMLPrograma_PPA(p_resultado, p_cliente, p_ano, p_chave, p_orgao, p_tipo_org, p_orgao_siorg, p_tipo_prog, _
                          p_nome, p_mes_ini, p_ano_ini, p_mes_fim, p_ano_fim, p_objetivo, p_publico_alvo, _
                          p_justificativa, p_estrategia, p_valor_estimado, p_temporario, p_padronizado, p_observacao)


  Dim l_cliente, l_ano, l_Chave, l_orgao, l_tipo_org, l_orgao_siorg, l_tipo_prog, l_nome
  Dim l_mes_ini, l_ano_ini, l_mes_fim, l_ano_fim, l_objetivo, l_publico_alvo
  Dim l_justificativa, l_estrategia, l_valor_estimado, l_temporario, l_padronizado, l_observacao
  
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano                     = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_orgao                   = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_org                = Server.CreateObject("ADODB.Parameter") 
  Set l_orgao_siorg             = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_prog               = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_mes_ini                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano_ini                 = Server.CreateObject("ADODB.Parameter")
  Set l_mes_fim                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano_fim                 = Server.CreateObject("ADODB.Parameter")  
  Set l_objetivo                = Server.CreateObject("ADODB.Parameter") 
  Set l_publico_alvo            = Server.CreateObject("ADODB.Parameter") 
  Set l_justificativa           = Server.CreateObject("ADODB.Parameter") 
  Set l_estrategia              = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_estimado          = Server.CreateObject("ADODB.Parameter") 
  Set l_temporario              = Server.CreateObject("ADODB.Parameter") 
  Set l_padronizado             = Server.CreateObject("ADODB.Parameter") 
  Set l_observacao              = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,     , Tvl(p_cliente))
     set l_ano                  = .CreateParameter("l_ano",                 adInteger, adParamInput,     , Tvl(p_ano))
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,    4, Tvl(p_chave))
     set l_orgao                = .CreateParameter("l_orgao",               adVarchar, adParamInput,    5, Tvl(p_orgao))
     set l_tipo_org             = .CreateParameter("l_tipo_org",            adVarchar, adParamInput,    1, Tvl(p_tipo_org))
     set l_orgao_siorg          = .CreateParameter("l_orgao_siorg",         adInteger, adParamInput,     , Tvl(p_orgao_siorg))
     set l_tipo_prog            = .CreateParameter("l_tipo_prog",           adInteger, adParamInput,     , Tvl(p_tipo_prog))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  200, Tvl(p_nome))
     set l_mes_ini              = .CreateParameter("l_mes_ini",             adVarchar, adParamInput,    2, Tvl(p_mes_ini))
     set l_ano_ini              = .CreateParameter("l_ano_ini",             adVarchar, adParamInput,    4, Tvl(p_ano_ini))
     set l_mes_fim              = .CreateParameter("l_mes_fim",             adVarchar, adParamInput,    2, Tvl(p_mes_fim))
     set l_ano_fim              = .CreateParameter("l_ano_fim",             adVarchar, adParamInput,    4, Tvl(p_ano_fim))
     set l_objetivo             = .CreateParameter("l_objetivo",            adVarchar, adParamInput, 4000, Tvl(p_objetivo))
     set l_publico_alvo         = .CreateParameter("l_publico_alvo",        adVarchar, adParamInput, 4000, Tvl(p_publico_alvo))
     set l_justificativa        = .CreateParameter("l_justificativa",       adVarchar, adParamInput, 4000, Tvl(Mid(p_justificativa,1,4000)))
     set l_estrategia           = .CreateParameter("l_estrategia",          adVarchar, adParamInput, 4000, Tvl(Mid(p_estrategia,1,4000)))
     set l_valor_estimado       = .CreateParameter("l_valor_estimado",      adNumeric)
     l_valor_estimado.Precision    = 18
     l_valor_estimado.NumericScale = 2
     l_valor_estimado.Value        = Tvl(Replace(p_valor_estimado,".",","))
     set l_temporario           = .CreateParameter("l_temporario",          adVarchar, adParamInput,    1, Tvl(p_temporario))
     set l_padronizado          = .CreateParameter("l_padronizado",         adVarchar, adParamInput,    1, Tvl(p_padronizado))
     set l_observacao           = .CreateParameter("l_observacao",          adVarchar, adParamInput, 4000, Tvl(p_observacao))
          
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_Chave
     .parameters.Append         l_orgao
     .parameters.Append         l_tipo_org
     .parameters.Append         l_orgao_siorg
     .parameters.Append         l_tipo_prog
     .parameters.Append         l_nome
     .parameters.Append         l_mes_ini
     .parameters.Append         l_ano_ini
     .parameters.Append         l_mes_fim
     .parameters.Append         l_ano_fim
     .parameters.Append         l_objetivo
     .parameters.Append         l_publico_alvo
     .parameters.Append         l_justificativa
     .parameters.Append         l_estrategia
     .parameters.Append         l_valor_estimado
     .parameters.Append         l_temporario
     .parameters.Append         l_padronizado
     .parameters.Append         l_observacao
  
     .CommandText               = Session("schema_is") & "SP_PutXMLPrograma_PPA"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_orgao"
     .parameters.Delete         "l_tipo_org"
     .parameters.Delete         "l_orgao_siorg"
     .parameters.Delete         "l_tipo_prog"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_mes_ini"
     .parameters.Delete         "l_ano_ini"
     .parameters.Delete         "l_mes_fim"
     .parameters.Delete         "l_ano_fim"
     .parameters.Delete         "l_objetivo"
     .parameters.Delete         "l_publico_alvo"
     .parameters.Delete         "l_justificativa"
     .parameters.Delete         "l_estrategia"
     .parameters.Delete         "l_valor_estimado"
     .parameters.Delete         "l_temporario"
     .parameters.Delete         "l_padronizado"
     .parameters.Delete         "l_observacao"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Indicador
REM -------------------------------------------------------------------------
Sub DML_PutXMLIndicador_PPA(p_resultado, p_cliente, p_ano, p_programa, p_chave, p_unidade_med, p_periodicidade, p_base_geo, p_nome, _
                           p_fonte, p_formula, p_valor_ano_1, p_valor_ano_2, p_valor_ano_3, p_valor_ano_4, p_valor_ano_5, _
                           p_valor_ano_6,  p_valor_ref, p_valor_final, p_apurado_ano_1,  p_apurado_ano_2, p_apurado_ano_3, p_apurado_ano_4, _
                           p_apurado_ano_5, p_apurado_ano_6, p_apurado_ref, p_apurado_final, p_apuracao, p_observacao)


  Dim l_cliente, l_ano, l_programa, l_Chave, l_unidade_med, l_periodicidade, l_base_geo, l_nome
  Dim l_fonte, l_formula, l_valor_ano_1, l_valor_ano_2, l_valor_ano_3, l_valor_ano_4, l_valor_ano_5
  Dim l_valor_ano_6, l_valor_ref, l_valor_final, l_apurado_ano_1, l_apurado_ano_2, l_apurado_ano_3, l_apurado_ano_4
  Dim l_apurado_ano_5, l_apurado_ano_6, l_apurado_ref, l_apurado_final, l_apuracao, l_observacao
  
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano                     = Server.CreateObject("ADODB.Parameter") 
  Set l_programa                = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_unidade_med             = Server.CreateObject("ADODB.Parameter") 
  Set l_periodicidade           = Server.CreateObject("ADODB.Parameter") 
  Set l_base_geo                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_fonte                   = Server.CreateObject("ADODB.Parameter") 
  Set l_formula                 = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_ano_1             = Server.CreateObject("ADODB.Parameter")
  Set l_valor_ano_2             = Server.CreateObject("ADODB.Parameter")
  Set l_valor_ano_3             = Server.CreateObject("ADODB.Parameter")
  Set l_valor_ano_4             = Server.CreateObject("ADODB.Parameter")
  Set l_valor_ano_5             = Server.CreateObject("ADODB.Parameter")
  Set l_valor_ano_6             = Server.CreateObject("ADODB.Parameter")          
  Set l_valor_ref               = Server.CreateObject("ADODB.Parameter")
  Set l_valor_final             = Server.CreateObject("ADODB.Parameter")
  Set l_apurado_ano_1           = Server.CreateObject("ADODB.Parameter")
  Set l_apurado_ano_2           = Server.CreateObject("ADODB.Parameter") 
  Set l_apurado_ano_3           = Server.CreateObject("ADODB.Parameter") 
  Set l_apurado_ano_4           = Server.CreateObject("ADODB.Parameter")        
  Set l_apurado_ano_5           = Server.CreateObject("ADODB.Parameter") 
  Set l_apurado_ano_6           = Server.CreateObject("ADODB.Parameter")   
  Set l_apurado_ref             = Server.CreateObject("ADODB.Parameter") 
  Set l_apurado_final           = Server.CreateObject("ADODB.Parameter") 
  Set l_observacao              = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,     , Tvl(p_cliente))
     set l_ano                  = .CreateParameter("l_ano",                 adInteger, adParamInput,     , Tvl(p_ano))
     set l_programa             = .CreateParameter("l_programa",            adVarchar, adParamInput,    4, Tvl(p_programa))
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,     , Tvl(p_chave))
     set l_unidade_med          = .CreateParameter("l_unidade_med",         adInteger, adParamInput,     , Tvl(p_unidade_med))
     set l_periodicidade        = .CreateParameter("l_periodicidade",       adInteger, adParamInput,     , Tvl(p_periodicidade))     
     set l_base_geo             = .CreateParameter("l_base_geo",            adInteger, adParamInput,     , Tvl(p_base_geo))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  200, Tvl(p_nome))
     set l_fonte                = .CreateParameter("l_fonte",               adVarchar, adParamInput,  200, Tvl(p_fonte))
     set l_formula              = .CreateParameter("l_formula",             adVarchar, adParamInput,  4000, Tvl(p_formula))
     set l_valor_ano_1          = .CreateParameter("l_valor_ano_1",         adNumeric)
     l_valor_ano_1.Precision    = 18
     l_valor_ano_1.NumericScale = 2
     l_valor_ano_1.Value        = Tvl(Replace(p_valor_ano_1,".",","))
     set l_valor_ano_2          = .CreateParameter("l_valor_ano_2",         adNumeric)
     l_valor_ano_2.Precision    = 18
     l_valor_ano_2.NumericScale = 2
     l_valor_ano_2.Value        = Tvl(Replace(p_valor_ano_2,".",","))
     set l_valor_ano_3          = .CreateParameter("l_valor_ano_3",         adNumeric)
     l_valor_ano_3.Precision    = 18
     l_valor_ano_3.NumericScale = 2
     l_valor_ano_3.Value        = Tvl(Replace(p_valor_ano_3,".",","))
     set l_valor_ano_4          = .CreateParameter("l_valor_ano_4",         adNumeric)
     l_valor_ano_4.Precision    = 18
     l_valor_ano_4.NumericScale = 2
     l_valor_ano_4.Value        = Tvl(Replace(p_valor_ano_4,".",","))
     set l_valor_ano_5          = .CreateParameter("l_valor_ano_5",         adNumeric)
     l_valor_ano_5.Precision    = 18
     l_valor_ano_5.NumericScale = 2
     l_valor_ano_5.Value        = Tvl(Replace(p_valor_ano_5,".",","))
     set l_valor_ano_6          = .CreateParameter("l_valor_ano_6",         adNumeric)
     l_valor_ano_6.Precision    = 18
     l_valor_ano_6.NumericScale = 2
     l_valor_ano_6.Value        = Tvl(Replace(p_valor_ano_6,".",","))
     set l_valor_ref            = .CreateParameter("l_valor_ref",           adNumeric)
     l_valor_ref.Precision      = 18
     l_valor_ref.NumericScale   = 2
     l_valor_ref.Value          = Tvl(Replace(p_valor_ref,".",","))
     set l_valor_final          = .CreateParameter("l_valor_final",         adNumeric)
     l_valor_final.Precision    = 18
     l_valor_final.NumericScale = 2
     l_valor_final.Value        = Tvl(Replace(p_valor_final,".",","))
     set l_apurado_ano_1        = .CreateParameter("l_apurado_ano_1",       adVarchar, adParamInput,    1, Tvl(p_apurado_ano_1))
     set l_apurado_ano_2        = .CreateParameter("l_apurado_ano_2",       adVarchar, adParamInput,    1, Tvl(p_apurado_ano_2))
     set l_apurado_ano_3        = .CreateParameter("l_apurado_ano_3",       adVarchar, adParamInput,    1, Tvl(p_apurado_ano_3))
     set l_apurado_ano_4        = .CreateParameter("l_apurado_ano_4",       adVarchar, adParamInput,    1, Tvl(p_apurado_ano_4))
     set l_apurado_ano_5        = .CreateParameter("l_apurado_ano_5",       adVarchar, adParamInput,    1, Tvl(p_apurado_ano_5))
     set l_apurado_ano_6        = .CreateParameter("l_apurado_ano_6",       adVarchar, adParamInput,    1, Tvl(p_apurado_ano_6))
     set l_apurado_ref          = .CreateParameter("l_apurado_ref",         adVarchar, adParamInput,    1, Tvl(p_apurado_ref))     
     set l_apurado_final        = .CreateParameter("l_apurado_final",       adVarchar, adParamInput,    1, Tvl(p_apurado_final))
     set l_apuracao             = .CreateParameter("l_apuracao",            adDate,    adParamInput,     , Tvl(p_apuracao))
     set l_observacao           = .CreateParameter("l_observacao",          adVarchar, adParamInput, 4000, Tvl(p_observacao))
          
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_programa
     .parameters.Append         l_Chave
     .parameters.Append         l_unidade_med
     .parameters.Append         l_periodicidade
     .parameters.Append         l_base_geo
     .parameters.Append         l_nome
     .parameters.Append         l_fonte
     .parameters.Append         l_formula
     .parameters.Append         l_valor_ano_1
     .parameters.Append         l_valor_ano_2
     .parameters.Append         l_valor_ano_3
     .parameters.Append         l_valor_ano_4
     .parameters.Append         l_valor_ano_5
     .parameters.Append         l_valor_ano_6
     .parameters.Append         l_valor_ref
     .parameters.Append         l_valor_final
     .parameters.Append         l_apurado_ano_1
     .parameters.Append         l_apurado_ano_2
     .parameters.Append         l_apurado_ano_3
     .parameters.Append         l_apurado_ano_4
     .parameters.Append         l_apurado_ano_5
     .parameters.Append         l_apurado_ano_6
     .parameters.Append         l_apurado_ref
     .parameters.Append         l_apurado_final
     .parameters.Append         l_apuracao
     .parameters.Append         l_observacao
  
     .CommandText               = Session("schema_is") & "SP_PutXMLIndicador_PPA"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_programa"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_unidade_med"
     .parameters.Delete         "l_periodicidade"
     .parameters.Delete         "l_base_geo"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_fonte"
     .parameters.Delete         "l_formula"
     .parameters.Delete         "l_valor_ano_1"
     .parameters.Delete         "l_valor_ano_2"
     .parameters.Delete         "l_valor_ano_3"
     .parameters.Delete         "l_valor_ano_4"
     .parameters.Delete         "l_valor_ano_5"
     .parameters.Delete         "l_valor_ano_6"                         
     .parameters.Delete         "l_valor_ref"
     .parameters.Delete         "l_valor_final"
     .parameters.Delete         "l_apurado_ano_1"
     .parameters.Delete         "l_apurado_ano_2"
     .parameters.Delete         "l_apurado_ano_3"
     .parameters.Delete         "l_apurado_ano_4"
     .parameters.Delete         "l_apurado_ano_5"
     .parameters.Delete         "l_apurado_ano_6"
     .parameters.Delete         "l_apurado_ref"
     .parameters.Delete         "l_apurado_final"
     .parameters.Delete         "l_apuracao"
     .parameters.Delete         "l_observacao"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Aзгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLAcao_PPA(p_resultado, p_cliente, p_ano, p_cd_programa, p_chave, p_cd_acao, p_unidade, p_tipo_unid, p_funcao, p_subfuncao, _
                       p_tipo_acao, p_cd_produto, p_ds_produto, p_unidade_med, p_tipo_inclusao, p_cd_esfera, p_orgao_siorg, _
                       p_nome, p_finalidade, p_descricao, p_base_legal, p_reperc_financ, p_vr_reperc_financ, p_padronizada, _
                       p_set_padronizada, p_direta, p_descentralizada, p_linha_credito, p_transf_obrig, _
                       p_transf_vol, p_transf_outras, p_despesa_obrig, p_bloqueio_prog, p_detalhamento, p_mes_ini, p_ano_ini, _
                       p_mes_fim, p_ano_fim, p_valor_total, p_valor_ano_ant, p_qtd_ano_ant, p_valor_ano_cor, p_qtd_ano_cor, _
                       p_ordem_pri, p_observacao, p_cd_sof, p_qtd_total, p_cd_sof_ref)


  Dim l_cliente, l_ano, l_cd_programa, l_Chave, l_cd_acao, l_unidade, l_tipo_unid, l_funcao, l_subfuncao
  Dim l_tipo_acao, l_cd_produto, l_ds_produto, l_unidade_med, l_tipo_inclusao, l_cd_esfera, l_orgao_siorg
  Dim l_nome, l_finalidade, l_descricao, l_base_legal, l_reperc_financ, l_vr_reperc_financ, l_padronizada
  Dim l_set_padronizada, l_direta, l_descentralizada, l_linha_credito, l_transf_obrig
  Dim l_transf_vol, l_transf_outras, l_despesa_obrig, l_bloqueio_prog, l_detalhamento, l_mes_ini, l_ano_ini
  Dim l_mes_fim, l_ano_fim, l_valor_total, l_valor_ano_ant, l_qtd_ano_ant, l_valor_ano_cor, l_qtd_ano_cor
  Dim l_ordem_pri, l_observacao, l_cd_sof, l_qtd_total, l_cd_sof_ref
  
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano                     = Server.CreateObject("ADODB.Parameter")
  Set l_cd_programa             = Server.CreateObject("ADODB.Parameter")  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_acao                 = Server.CreateObject("ADODB.Parameter") 
  Set l_unidade                 = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_unid               = Server.CreateObject("ADODB.Parameter") 
  Set l_funcao                  = Server.CreateObject("ADODB.Parameter") 
  Set l_subfuncao               = Server.CreateObject("ADODB.Parameter")
  Set l_tipo_acao               = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_produto              = Server.CreateObject("ADODB.Parameter") 
  Set l_ds_produto              = Server.CreateObject("ADODB.Parameter") 
  Set l_unidade_med             = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_inclusao           = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_esfera               = Server.CreateObject("ADODB.Parameter") 
  Set l_orgao_siorg             = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_finalidade              = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao               = Server.CreateObject("ADODB.Parameter")
  Set l_base_legal              = Server.CreateObject("ADODB.Parameter")  
  Set l_reperc_financ           = Server.CreateObject("ADODB.Parameter")  
  Set l_vr_reperc_financ        = Server.CreateObject("ADODB.Parameter")  
  Set l_padronizada             = Server.CreateObject("ADODB.Parameter")  
  Set l_set_padronizada         = Server.CreateObject("ADODB.Parameter")  
  Set l_direta                  = Server.CreateObject("ADODB.Parameter")  
  Set l_descentralizada         = Server.CreateObject("ADODB.Parameter")
  Set l_linha_credito           = Server.CreateObject("ADODB.Parameter")    
  Set l_transf_obrig            = Server.CreateObject("ADODB.Parameter")    
  Set l_transf_vol              = Server.CreateObject("ADODB.Parameter")    
  Set l_transf_outras           = Server.CreateObject("ADODB.Parameter")    
  Set l_despesa_obrig           = Server.CreateObject("ADODB.Parameter")    
  Set l_bloqueio_prog           = Server.CreateObject("ADODB.Parameter")    
  Set l_detalhamento            = Server.CreateObject("ADODB.Parameter")    
  Set l_mes_ini                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano_ini                 = Server.CreateObject("ADODB.Parameter")
  Set l_mes_fim                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano_fim                 = Server.CreateObject("ADODB.Parameter")  
  Set l_valor_total             = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_ano_ant           = Server.CreateObject("ADODB.Parameter") 
  Set l_qtd_ano_ant             = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_ano_cor           = Server.CreateObject("ADODB.Parameter") 
  Set l_qtd_ano_cor             = Server.CreateObject("ADODB.Parameter") 
  Set l_ordem_pri               = Server.CreateObject("ADODB.Parameter") 
  Set l_observacao              = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_sof                  = Server.CreateObject("ADODB.Parameter")
  Set l_qtd_total               = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_sof_ref              = Server.CreateObject("ADODB.Parameter")  
  
  with sp
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,     , Tvl(p_cliente))
     set l_ano                  = .CreateParameter("l_ano",                 adInteger, adParamInput,     , Tvl(p_ano))
     set l_cd_programa          = .CreateParameter("l_cd_programa",         adVarchar, adParamInput,    4, Tvl(p_cd_programa))
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,    5, Tvl(p_chave))
     set l_cd_acao              = .CreateParameter("l_cd_acao",             adVarchar, adParamInput,    4, Tvl(p_cd_acao))
     set l_unidade              = .CreateParameter("l_unidade",             adVarchar, adParamInput,    5, Tvl(p_unidade))
     set l_tipo_unid            = .CreateParameter("l_tipo_unid",           adVarchar, adParamInput,    1, Tvl(p_tipo_unid))
     set l_funcao               = .CreateParameter("l_funcao",              adVarchar, adParamInput,    2, Tvl(p_funcao))
     set l_subfuncao            = .CreateParameter("l_subfuncao",           adVarchar, adParamInput,    3, Tvl(p_subfuncao))
     set l_tipo_acao            = .CreateParameter("l_tipo_acao",           adInteger, adParamInput,     , Tvl(p_tipo_acao))
     set l_cd_produto           = .CreateParameter("l_cd_produto",          adInteger, adParamInput,     , Tvl(p_cd_produto))
     set l_ds_produto           = .CreateParameter("l_ds_produto",          adVarchar, adParamInput, 4000, Tvl(p_ds_produto))
     set l_unidade_med          = .CreateParameter("l_unidade_med",         adInteger, adParamInput,     , Tvl(p_unidade_med))
     set l_tipo_inclusao        = .CreateParameter("l_tipo_inclusao",       adInteger, adParamInput,     , Tvl(p_tipo_inclusao))
     set l_cd_esfera            = .CreateParameter("l_cd_esfera",           adInteger, adParamInput,     , Tvl(p_cd_esfera))
     set l_orgao_siorg          = .CreateParameter("l_orgao_siorg",         adInteger, adParamInput,     , Tvl(p_orgao_siorg))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  255, Tvl(p_nome))
     set l_finalidade           = .CreateParameter("l_finalidade",          adVarchar, adParamInput, 4000, Tvl(p_finalidade))
     set l_descricao            = .CreateParameter("l_descricao",           adVarchar, adParamInput, 4000, Tvl(p_descricao))
     set l_base_legal           = .CreateParameter("l_base_legal",          adVarchar, adParamInput, 4000, Tvl(p_base_legal))
     set l_reperc_financ        = .CreateParameter("l_reperc_financ",       adVarchar, adParamInput, 4000, Tvl(p_reperc_financ))
     set l_vr_reperc_financ     = .CreateParameter("l_vr_reperc_financ",      adNumeric)
     l_vr_reperc_financ.Precision    = 18
     l_vr_reperc_financ.NumericScale = 2
     l_vr_reperc_financ.Value        = Tvl(Replace(p_vr_reperc_financ,".",","))
     set l_padronizada          = .CreateParameter("l_padronizada",         adVarchar, adParamInput,    1, Tvl(p_padronizada))
     set l_set_padronizada      = .CreateParameter("l_set_padronizada",     adVarchar, adParamInput,    1, Tvl(p_set_padronizada))
     set l_direta               = .CreateParameter("l_direta",              adVarchar, adParamInput,    1, Tvl(p_direta))
     set l_descentralizada      = .CreateParameter("l_descentralizada",     adVarchar, adParamInput,    1, Tvl(p_descentralizada))
     set l_linha_credito        = .CreateParameter("l_linha_credito",       adVarchar, adParamInput,    1, Tvl(p_linha_credito))
     set l_transf_obrig         = .CreateParameter("l_transf_obrig",        adVarchar, adParamInput,    1, Tvl(p_transf_obrig))
     set l_transf_vol           = .CreateParameter("l_transf_vol",          adVarchar, adParamInput,    1, Tvl(p_transf_vol))
     set l_transf_outras        = .CreateParameter("l_transf_outras",       adVarchar, adParamInput,    1, Tvl(p_transf_outras))
     set l_despesa_obrig        = .CreateParameter("l_despesa_obrig",       adVarchar, adParamInput,    1, Tvl(p_despesa_obrig))
     set l_bloqueio_prog        = .CreateParameter("l_bloqueio_prog",       adVarchar, adParamInput,    1, Tvl(p_bloqueio_prog))
     set l_detalhamento         = .CreateParameter("l_detalhamento",        adVarchar, adParamInput, 4000, Tvl(p_detalhamento))
     set l_mes_ini              = .CreateParameter("l_mes_ini",             adVarchar, adParamInput,    2, Tvl(p_mes_ini))
     set l_ano_ini              = .CreateParameter("l_ano_ini",             adVarchar, adParamInput,    4, Tvl(p_ano_ini))
     set l_mes_fim              = .CreateParameter("l_mes_fim",             adVarchar, adParamInput,    2, Tvl(p_mes_fim))
     set l_ano_fim              = .CreateParameter("l_ano_fim",             adVarchar, adParamInput,    4, Tvl(p_ano_fim))
     set l_valor_total          = .CreateParameter("l_valor_total",         adNumeric)
     l_valor_total.Precision    = 18
     l_valor_total.NumericScale = 2
     l_valor_total.Value        = Tvl(Replace(p_valor_total,".",","))
     set l_valor_ano_ant        = .CreateParameter("l_valor_ano_ant",       adNumeric)
     l_valor_ano_ant.Precision    = 18
     l_valor_ano_ant.NumericScale = 2
     l_valor_ano_ant.Value        = Tvl(Replace(p_valor_ano_ant,".",","))
     set l_qtd_ano_ant          = .CreateParameter("l_qtd_ano_ant",         adNumeric)
     l_qtd_ano_ant.Precision    = 18
     l_qtd_ano_ant.NumericScale = 4
     l_qtd_ano_ant.Value        = Tvl(Replace(p_qtd_ano_ant,".",","))
     set l_valor_ano_cor        = .CreateParameter("l_valor_ano_cor",       adNumeric)
     l_valor_ano_cor.Precision    = 18
     l_valor_ano_cor.NumericScale = 2
     l_valor_ano_cor.Value        = Tvl(Replace(p_valor_ano_cor,".",","))
     set l_qtd_ano_cor          = .CreateParameter("l_qtd_ano_cor",         adNumeric)
     l_qtd_ano_cor.Precision    = 18
     l_qtd_ano_cor.NumericScale = 4
     l_qtd_ano_cor.Value        = Tvl(Replace(p_qtd_ano_cor,".",","))
     set l_ordem_pri            = .CreateParameter("l_ordem_pri",           adInteger, adParamInput,     , Tvl(p_ordem_pri))
     set l_observacao           = .CreateParameter("l_observacao",          adVarchar, adParamInput, 4000, Tvl(p_observacao))
     set l_cd_sof               = .CreateParameter("l_cd_sof",              adVarchar, adParamInput,    8, Tvl(p_cd_sof))
     set l_qtd_total            = .CreateParameter("l_qtd_total",           adNumeric)
     l_qtd_total.Precision    = 18
     l_qtd_total.NumericScale = 4
     l_qtd_total.Value        = Tvl(Replace(p_qtd_total,".",","))
     set l_cd_sof_ref           = .CreateParameter("l_cd_sof_ref",          adInteger, adParamInput,     , Tvl(p_cd_sof_ref))

     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_cd_programa
     .parameters.Append         l_Chave
     .parameters.Append         l_cd_acao
     .parameters.Append         l_unidade
     .parameters.Append         l_tipo_unid
     .parameters.Append         l_funcao
     .parameters.Append         l_subfuncao
     .parameters.Append         l_tipo_acao
     .parameters.Append         l_cd_produto
     .parameters.Append         l_ds_produto
     .parameters.Append         l_unidade_med
     .parameters.Append         l_tipo_inclusao
     .parameters.Append         l_cd_esfera
     .parameters.Append         l_orgao_siorg
     .parameters.Append         l_nome
     .parameters.Append         l_finalidade
     .parameters.Append         l_descricao
     .parameters.Append         l_base_legal
     .parameters.Append         l_reperc_financ
     .parameters.Append         l_vr_reperc_financ
     .parameters.Append         l_padronizada
     .parameters.Append         l_set_padronizada
     .parameters.Append         l_direta
     .parameters.Append         l_descentralizada
     .parameters.Append         l_linha_credito
     .parameters.Append         l_transf_obrig
     .parameters.Append         l_transf_vol
     .parameters.Append         l_transf_outras
     .parameters.Append         l_despesa_obrig
     .parameters.Append         l_bloqueio_prog
     .parameters.Append         l_detalhamento     
     .parameters.Append         l_mes_ini
     .parameters.Append         l_ano_ini
     .parameters.Append         l_mes_fim
     .parameters.Append         l_ano_fim
     .parameters.Append         l_valor_total
     .parameters.Append         l_valor_ano_ant
     .parameters.Append         l_qtd_ano_ant
     .parameters.Append         l_valor_ano_cor
     .parameters.Append         l_qtd_ano_cor
     .parameters.Append         l_ordem_pri
     .parameters.Append         l_observacao
     .parameters.Append         l_cd_sof
     .parameters.Append         l_qtd_total
     .parameters.Append         l_cd_sof_ref
  
     .CommandText               = Session("schema_is") & "SP_PutXMLAcao_PPA"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_cd_programa"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cd_acao"
     .parameters.Delete         "l_unidade"
     .parameters.Delete         "l_tipo_unid"
     .parameters.Delete         "l_funcao"
     .parameters.Delete         "l_subfuncao"
     .parameters.Delete         "l_tipo_acao"
     .parameters.Delete         "l_cd_produto"
     .parameters.Delete         "l_ds_produto"
     .parameters.Delete         "l_unidade_med"
     .parameters.Delete         "l_tipo_inclusao"
     .parameters.Delete         "l_cd_esfera"
     .parameters.Delete         "l_orgao_siorg"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_finalidade"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_base_legal"
     .parameters.Delete         "l_reperc_financ"
     .parameters.Delete         "l_vr_reperc_financ"
     .parameters.Delete         "l_padronizada"
     .parameters.Delete         "l_set_padronizada"
     .parameters.Delete         "l_direta"
     .parameters.Delete         "l_descentralizada"
     .parameters.Delete         "l_linha_credito"
     .parameters.Delete         "l_transf_obrig"
     .parameters.Delete         "l_transf_vol"
     .parameters.Delete         "l_transf_outras"
     .parameters.Delete         "l_despesa_obrig"
     .parameters.Delete         "l_bloqueio_prog"
     .parameters.Delete         "l_detalhamento"
     .parameters.Delete         "l_mes_ini"
     .parameters.Delete         "l_ano_ini"
     .parameters.Delete         "l_mes_fim"
     .parameters.Delete         "l_ano_fim"
     .parameters.Delete         "l_valor_total"
     .parameters.Delete         "l_valor_ano_ant"
     .parameters.Delete         "l_qtd_ano_ant"
     .parameters.Delete         "l_valor_ano_cor"
     .parameters.Delete         "l_qtd_ano_cor"
     .parameters.Delete         "l_ordem_pri"
     .parameters.Delete         "l_observacao"
     .parameters.Delete         "l_cd_sof"
     .parameters.Delete         "l_qtd_total"
     .parameters.Delete         "l_cd_sof_ref"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Localizador
REM -------------------------------------------------------------------------
Sub DML_PutXMLLocalizador_PPA(p_resultado, p_cliente, p_ano, p_cd_programa, p_cd_acao_ppa, p_chave, p_cd_localizador, p_cd_regiao, p_cd_municipio, _
                              p_nome, p_valor_total, p_valor_ano_ant, p_qtd_ano_ant, p_valor_ano_cor, p_qtd_ano_cor, p_reperc_financ, _
                              p_vr_reperc_financ, p_mes_ini, p_ano_ini, p_mes_fim, p_ano_fim, p_nome_alterado, p_observacao, _
                              p_qtd_total, p_cd_sof_ref)


  Dim l_cliente, l_ano, l_cd_programa, l_cd_acao_ppa, l_Chave, l_cd_localizador, l_cd_regiao, l_cd_municipio
  Dim l_nome, l_valor_total, l_valor_ano_ant, l_qtd_ano_ant, l_valor_ano_cor, l_qtd_ano_cor, l_reperc_financ
  Dim l_vr_reperc_financ, l_mes_ini, l_ano_ini, l_mes_fim, l_ano_fim, l_nome_alterado, l_observacao
  Dim l_qtd_total, l_cd_sof_ref
  
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano                     = Server.CreateObject("ADODB.Parameter")
  Set l_cd_programa             = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_acao_ppa             = Server.CreateObject("ADODB.Parameter")  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_localizador          = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_regiao               = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_municipio            = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_total             = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_ano_ant           = Server.CreateObject("ADODB.Parameter") 
  Set l_qtd_ano_ant             = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_ano_cor           = Server.CreateObject("ADODB.Parameter") 
  Set l_qtd_ano_cor             = Server.CreateObject("ADODB.Parameter") 
  Set l_reperc_financ           = Server.CreateObject("ADODB.Parameter") 
  Set l_vr_reperc_financ        = Server.CreateObject("ADODB.Parameter") 
  Set l_mes_ini                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano_ini                 = Server.CreateObject("ADODB.Parameter")
  Set l_mes_fim                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano_fim                 = Server.CreateObject("ADODB.Parameter")  
  Set l_nome_alterado           = Server.CreateObject("ADODB.Parameter") 
  Set l_observacao              = Server.CreateObject("ADODB.Parameter") 
  Set l_qtd_total               = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_sof_ref              = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,     , Tvl(p_cliente))
     set l_ano                  = .CreateParameter("l_ano",                 adInteger, adParamInput,     , Tvl(p_ano))
     set l_cd_programa          = .CreateParameter("l_cd_programa",         adVarchar, adParamInput,    4, Tvl(p_cd_programa))
     set l_cd_acao_ppa          = .CreateParameter("l_cd_acao_ppa",         adVarchar, adParamInput,    5, Tvl(p_cd_acao_ppa))
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,    5, Tvl(p_chave))
     set l_cd_localizador       = .CreateParameter("l_cd_localizador",      adVarchar, adParamInput,    4, Tvl(p_cd_localizador))
     set l_cd_regiao            = .CreateParameter("l_cd_regiao",           adVarchar, adParamInput,    2, Tvl(p_cd_regiao))
     set l_cd_municipio         = .CreateParameter("l_cd_municipio",        adVarchar, adParamInput,    7, Tvl(p_cd_municipio))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  255, Tvl(p_nome))
     set l_valor_total          = .CreateParameter("l_valor_total",         adNumeric)
     l_valor_total.Precision    = 18
     l_valor_total.NumericScale = 2
     l_valor_total.Value        = Tvl(Replace(p_valor_total,".",","))
     set l_valor_ano_ant        = .CreateParameter("l_valor_ano_ant",       adNumeric)
     l_valor_ano_ant.Precision    = 18
     l_valor_ano_ant.NumericScale = 2
     l_valor_ano_ant.Value        = Tvl(Replace(p_valor_ano_ant,".",","))
     set l_qtd_ano_ant          = .CreateParameter("l_qtd_ano_ant",         adNumeric)
     l_qtd_ano_ant.Precision    = 18
     l_qtd_ano_ant.NumericScale = 4
     l_qtd_ano_ant.Value        = Tvl(Replace(p_qtd_ano_ant,".",","))
     set l_valor_ano_cor        = .CreateParameter("l_valor_ano_cor",       adNumeric)
     l_valor_ano_cor.Precision    = 18
     l_valor_ano_cor.NumericScale = 2
     l_valor_ano_cor.Value        = Tvl(Replace(p_valor_ano_cor,".",","))
     set l_qtd_ano_cor          = .CreateParameter("l_qtd_ano_cor",         adNumeric)
     l_qtd_ano_cor.Precision    = 18
     l_qtd_ano_cor.NumericScale = 4
     l_qtd_ano_cor.Value        = Tvl(Replace(p_qtd_ano_cor,".",","))
     set l_reperc_financ        = .CreateParameter("l_reperc_financ",       adVarchar, adParamInput, 4000, Tvl(p_reperc_financ))
     set l_vr_reperc_financ     = .CreateParameter("l_vr_reperc_financ",    adNumeric)
     l_vr_reperc_financ.Precision    = 18
     l_vr_reperc_financ.NumericScale = 2
     l_vr_reperc_financ.Value        = Tvl(Replace(p_vr_reperc_financ,".",","))
     set l_mes_ini              = .CreateParameter("l_mes_ini",             adVarchar, adParamInput,    2, Tvl(p_mes_ini))
     set l_ano_ini              = .CreateParameter("l_ano_ini",             adVarchar, adParamInput,    4, Tvl(p_ano_ini))
     set l_mes_fim              = .CreateParameter("l_mes_fim",             adVarchar, adParamInput,    2, Tvl(p_mes_fim))
     set l_ano_fim              = .CreateParameter("l_ano_fim",             adVarchar, adParamInput,    4, Tvl(p_ano_fim))
     set l_nome_alterado        = .CreateParameter("l_nome_alterado",       adVarchar, adParamInput,    1, Tvl(p_nome_alterado))
     set l_observacao           = .CreateParameter("l_observacao",          adVarchar, adParamInput, 4000, Tvl(p_observacao))
     set l_qtd_total            = .CreateParameter("l_qtd_total",           adNumeric)
     l_qtd_total.Precision    = 18
     l_qtd_total.NumericScale = 4
     l_qtd_total.Value        = Tvl(Replace(p_qtd_total,".",","))
     set l_cd_sof_ref           = .CreateParameter("l_cd_sof_ref",          adInteger, adParamInput,     , Tvl(p_cd_sof_ref))
               
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_cd_programa
     .parameters.Append         l_cd_acao_ppa
     .parameters.Append         l_Chave
     .parameters.Append         l_cd_localizador
     .parameters.Append         l_cd_regiao
     .parameters.Append         l_cd_municipio
     .parameters.Append         l_nome
     .parameters.Append         l_valor_total
     .parameters.Append         l_valor_ano_ant
     .parameters.Append         l_qtd_ano_ant
     .parameters.Append         l_valor_ano_cor
     .parameters.Append         l_qtd_ano_cor
     .parameters.Append         l_reperc_financ
     .parameters.Append         l_vr_reperc_financ
     .parameters.Append         l_mes_ini
     .parameters.Append         l_ano_ini
     .parameters.Append         l_mes_fim
     .parameters.Append         l_ano_fim
     .parameters.Append         l_nome_alterado
     .parameters.Append         l_observacao
     .parameters.Append         l_qtd_total
     .parameters.Append         l_cd_sof_ref
  
     .CommandText               = Session("schema_is") & "SP_PutXMLLocalizador_PPA"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_cd_programa"
     .parameters.Delete         "l_cd_acao_ppa"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cd_localizador"
     .parameters.Delete         "l_cd_regiao"
     .parameters.Delete         "l_cd_municipio"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_valor_total"
     .parameters.Delete         "l_valor_ano_ant"
     .parameters.Delete         "l_qtd_ano_ant"
     .parameters.Delete         "l_valor_ano_cor"
     .parameters.Delete         "l_qtd_ano_cor"
     .parameters.Delete         "l_reperc_financ"
     .parameters.Delete         "l_vr_reperc_financ"
     .parameters.Delete         "l_mes_ini"
     .parameters.Delete         "l_ano_ini"
     .parameters.Delete         "l_mes_fim"
     .parameters.Delete         "l_ano_fim"
     .parameters.Delete         "l_nome_alterado"
     .parameters.Delete         "l_observacao"
     .parameters.Delete         "l_qtd_total"
     .parameters.Delete         "l_cd_sof_ref"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Dado Fнsico
REM -------------------------------------------------------------------------
Sub DML_PutXMLDadoFisico_PPA(p_resultado, p_cliente, p_ano, p_cd_programa, p_cd_acao_ppa, p_cd_localizador_ppa, _
                             p_qtd_ano_1, p_qtd_ano_2, p_qtd_ano_3, p_qtd_ano_4, p_qtd_ano_5, p_qtd_ano_6, _
                             p_observacao, p_cumulativa)


  Dim l_cliente, l_ano, l_cd_programa, l_cd_acao_ppa, l_cd_localizador_ppa
  Dim l_qtd_ano_1, l_qtd_ano_2, l_qtd_ano_3, l_qtd_ano_4, l_qtd_ano_5, l_qtd_ano_6
  Dim l_observacao, l_cumulativa
  
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano                     = Server.CreateObject("ADODB.Parameter")
  Set l_cd_programa             = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_acao_ppa             = Server.CreateObject("ADODB.Parameter")  
  Set l_cd_localizador_ppa      = Server.CreateObject("ADODB.Parameter") 
  Set l_qtd_ano_1               = Server.CreateObject("ADODB.Parameter")
  Set l_qtd_ano_2               = Server.CreateObject("ADODB.Parameter") 
  Set l_qtd_ano_3               = Server.CreateObject("ADODB.Parameter") 
  Set l_qtd_ano_4               = Server.CreateObject("ADODB.Parameter") 
  Set l_qtd_ano_5               = Server.CreateObject("ADODB.Parameter") 
  Set l_qtd_ano_6               = Server.CreateObject("ADODB.Parameter")  
  Set l_observacao              = Server.CreateObject("ADODB.Parameter") 
  Set l_cumulativa              = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,     , Tvl(p_cliente))
     set l_ano                  = .CreateParameter("l_ano",                 adInteger, adParamInput,     , Tvl(p_ano))
     set l_cd_programa          = .CreateParameter("l_cd_programa",         adVarchar, adParamInput,    4, Tvl(p_cd_programa))
     set l_cd_acao_ppa          = .CreateParameter("l_cd_acao_ppa",         adVarchar, adParamInput,    5, Tvl(p_cd_acao_ppa))
     set l_cd_localizador_ppa   = .CreateParameter("l_cd_localizador_ppa",  adVarchar, adParamInput,    5, Tvl(p_cd_localizador_ppa))
     set l_qtd_ano_1            = .CreateParameter("l_qtd_ano_1",           adNumeric)
     l_qtd_ano_1.Precision      = 18
     l_qtd_ano_1.NumericScale   = 4
     l_qtd_ano_1.Value          = Tvl(Replace(p_qtd_ano_1,".",","))
     set l_qtd_ano_2            = .CreateParameter("l_qtd_ano_2",           adNumeric)
     l_qtd_ano_2.Precision      = 18
     l_qtd_ano_2.NumericScale   = 4
     l_qtd_ano_2.Value          = Tvl(Replace(p_qtd_ano_2,".",","))
     set l_qtd_ano_3            = .CreateParameter("l_qtd_ano_3",           adNumeric)
     l_qtd_ano_3.Precision      = 18
     l_qtd_ano_3.NumericScale   = 4
     l_qtd_ano_3.Value          = Tvl(Replace(p_qtd_ano_3,".",","))
     set l_qtd_ano_4            = .CreateParameter("l_qtd_ano_4",           adNumeric)
     l_qtd_ano_4.Precision      = 18
     l_qtd_ano_4.NumericScale   = 4
     l_qtd_ano_4.Value          = Tvl(Replace(p_qtd_ano_4,".",","))
     set l_qtd_ano_5            = .CreateParameter("l_qtd_ano_5",           adNumeric)
     l_qtd_ano_5.Precision      = 18
     l_qtd_ano_5.NumericScale   = 4
     l_qtd_ano_5.Value          = Tvl(Replace(p_qtd_ano_5,".",","))
     set l_qtd_ano_6            = .CreateParameter("l_qtd_ano_6",           adNumeric)
     l_qtd_ano_6.Precision      = 18
     l_qtd_ano_6.NumericScale   = 4
     l_qtd_ano_6.Value          = Tvl(Replace(p_qtd_ano_6,".",","))
     set l_observacao           = .CreateParameter("l_observacao",          adVarchar, adParamInput, 4000, Tvl(p_observacao))
     set l_cumulativa           = .CreateParameter("l_cumulativa",          adVarchar, adParamInput,    1, Tvl(p_cumulativa))               
     
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_cd_programa
     .parameters.Append         l_cd_acao_ppa
     .parameters.Append         l_cd_localizador_ppa
     .parameters.Append         l_qtd_ano_1
     .parameters.Append         l_qtd_ano_2
     .parameters.Append         l_qtd_ano_3
     .parameters.Append         l_qtd_ano_4
     .parameters.Append         l_qtd_ano_5
     .parameters.Append         l_qtd_ano_6
     .parameters.Append         l_observacao
     .parameters.Append         l_cumulativa
  
     .CommandText               = Session("schema_is") & "SP_PutXMLDadoFisico_PPA"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_cd_programa"
     .parameters.Delete         "l_cd_acao_ppa"
     .parameters.Delete         "l_cd_localizador_ppa"
     .parameters.Delete         "l_qtd_ano_1"
     .parameters.Delete         "l_qtd_ano_2"
     .parameters.Delete         "l_qtd_ano_3"
     .parameters.Delete         "l_qtd_ano_4"
     .parameters.Delete         "l_qtd_ano_5"
     .parameters.Delete         "l_qtd_ano_6"                         
     .parameters.Delete         "l_observacao"
     .parameters.Delete         "l_cumulativa"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Dado Financeiro
REM -------------------------------------------------------------------------
Sub DML_PutXMLDadoFinanceiro_PPA(p_resultado, p_cliente, p_ano, p_cd_programa, p_cd_acao_ppa, p_cd_localizador_ppa, p_cd_fonte, _ 
                                 p_cd_natureza, p_cd_tipo_despesa, p_valor_ano_1, p_valor_ano_2, p_valor_ano_3, _
                                 p_valor_ano_4, p_valor_ano_5, p_valor_ano_6, p_observacao)


  Dim l_cliente, l_ano, l_cd_programa, l_cd_acao_ppa, l_cd_localizador_ppa, l_cd_fonte, l_cd_natureza, l_cd_tipo_despesa
  Dim l_valor_ano_1, l_valor_ano_2, l_valor_ano_3, l_valor_ano_4, l_valor_ano_5, l_valor_ano_6
  Dim l_observacao
  
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano                     = Server.CreateObject("ADODB.Parameter")
  Set l_cd_programa             = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_acao_ppa             = Server.CreateObject("ADODB.Parameter")  
  Set l_cd_localizador_ppa      = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_fonte                = Server.CreateObject("ADODB.Parameter")  
  Set l_cd_natureza             = Server.CreateObject("ADODB.Parameter")  
  Set l_cd_tipo_despesa         = Server.CreateObject("ADODB.Parameter")  
  Set l_valor_ano_1             = Server.CreateObject("ADODB.Parameter")
  Set l_valor_ano_2             = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_ano_3             = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_ano_4             = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_ano_5             = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_ano_6             = Server.CreateObject("ADODB.Parameter")  
  Set l_observacao              = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,     , Tvl(p_cliente))
     set l_ano                  = .CreateParameter("l_ano",                 adInteger, adParamInput,     , Tvl(p_ano))
     set l_cd_programa          = .CreateParameter("l_cd_programa",         adVarchar, adParamInput,    4, Tvl(p_cd_programa))
     set l_cd_acao_ppa          = .CreateParameter("l_cd_acao_ppa",         adVarchar, adParamInput,    5, Tvl(p_cd_acao_ppa))
     set l_cd_localizador_ppa   = .CreateParameter("l_cd_localizador_ppa",  adVarchar, adParamInput,    5, Tvl(p_cd_localizador_ppa))
     set l_cd_fonte             = .CreateParameter("l_cd_fonte",            adVarchar, adParamInput,    5, Tvl(p_cd_fonte))
     set l_cd_natureza          = .CreateParameter("l_cd_natureza",         adVarchar, adParamInput,    2, Tvl(p_cd_natureza))
     set l_cd_tipo_despesa      = .CreateParameter("l_cd_tipo_despesa",     adInteger, adParamInput,     , Tvl(p_cd_tipo_despesa))
     set l_valor_ano_1            = .CreateParameter("l_valor_ano_1",       adNumeric)
     l_valor_ano_1.Precision      = 18
     l_valor_ano_1.NumericScale   = 2
     l_valor_ano_1.Value          = Tvl(Replace(p_valor_ano_1,".",","))
     set l_valor_ano_2            = .CreateParameter("l_valor_ano_2",       adNumeric)
     l_valor_ano_2.Precision      = 18
     l_valor_ano_2.NumericScale   = 2
     l_valor_ano_2.Value          = Tvl(Replace(p_valor_ano_2,".",","))
     set l_valor_ano_3            = .CreateParameter("l_valor_ano_3",       adNumeric)
     l_valor_ano_3.Precision      = 18
     l_valor_ano_3.NumericScale   = 2
     l_valor_ano_3.Value          = Tvl(Replace(p_valor_ano_3,".",","))
     set l_valor_ano_4            = .CreateParameter("l_valor_ano_4",       adNumeric)
     l_valor_ano_4.Precision      = 18
     l_valor_ano_4.NumericScale   = 2
     l_valor_ano_4.Value          = Tvl(Replace(p_valor_ano_4,".",","))
     set l_valor_ano_5            = .CreateParameter("l_valor_ano_5",       adNumeric)
     l_valor_ano_5.Precision      = 18
     l_valor_ano_5.NumericScale   = 2
     l_valor_ano_5.Value          = Tvl(Replace(p_valor_ano_5,".",","))
     set l_valor_ano_6            = .CreateParameter("l_valor_ano_6",       adNumeric)
     l_valor_ano_6.Precision      = 18
     l_valor_ano_6.NumericScale   = 2
     l_valor_ano_6.Value          = Tvl(Replace(p_valor_ano_6,".",","))
     set l_observacao           = .CreateParameter("l_observacao",          adVarchar, adParamInput, 4000, Tvl(p_observacao))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_cd_programa
     .parameters.Append         l_cd_acao_ppa
     .parameters.Append         l_cd_localizador_ppa
     .parameters.Append         l_cd_fonte
     .parameters.Append         l_cd_natureza
     .parameters.Append         l_cd_tipo_despesa
     .parameters.Append         l_valor_ano_1
     .parameters.Append         l_valor_ano_2
     .parameters.Append         l_valor_ano_3
     .parameters.Append         l_valor_ano_4
     .parameters.Append         l_valor_ano_5
     .parameters.Append         l_valor_ano_6
     .parameters.Append         l_observacao
  
     .CommandText               = Session("schema_is") & "SP_PutXMLDadoFinanceiro_PPA"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_cd_programa"
     .parameters.Delete         "l_cd_acao_ppa"
     .parameters.Delete         "l_cd_localizador_ppa"
     .parameters.Delete         "l_cd_fonte"
     .parameters.Delete         "l_cd_natureza"
     .parameters.Delete         "l_cd_tipo_despesa"
     .parameters.Delete         "l_valor_ano_1"
     .parameters.Delete         "l_valor_ano_2"
     .parameters.Delete         "l_valor_ano_3"
     .parameters.Delete         "l_valor_ano_4"
     .parameters.Delete         "l_valor_ano_5"
     .parameters.Delete         "l_valor_ano_6"                         
     .parameters.Delete         "l_observacao"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela PPA - Periodicidade
REM -------------------------------------------------------------------------
Sub DML_PutXMLPeriodicidade_PPA(p_resultado, p_chave, p_nome, p_ativo)


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
  
     .CommandText               = Session("schema_is") & "SP_PutXMLPeriodicidade_PPA"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIG - Periodicidade
REM -------------------------------------------------------------------------
Sub DML_PutXMLPeriodicidade(p_resultado, p_chave, p_nome, p_ativo)


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
  
     .CommandText               = Session("schema_is") & "SP_PutXMLPeriodicidade"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIG - Opзгo Estratйgica
REM -------------------------------------------------------------------------
Sub DML_PutXMLOpcao_Estrat(p_resultado, p_chave, p_nome, p_ativo)


  Dim l_Chave, l_nome, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,  2, Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,255, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLOpcao_Estrat"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIG - Macro objetivo
REM -------------------------------------------------------------------------
Sub DML_PutXMLMacro_Objetivo(p_resultado, p_chave, p_nome, p_opcao, p_ativo)


  Dim l_Chave, l_nome, l_opcao, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_opcao                   = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,  2, Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,255, Tvl(p_nome))
     set l_opcao                = .CreateParameter("l_opcao",               adVarchar, adParamInput,  2, Tvl(p_opcao))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_opcao
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLMacro_Objetivo"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_opcao"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIG - Base geogrбfica
REM -------------------------------------------------------------------------
Sub DML_PutXMLBase_Geografica(p_resultado, p_chave, p_nome, p_ativo)


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
  
     .CommandText               = Session("schema_is") & "SP_PutXMLBase_Geografica"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIG - Produto
REM -------------------------------------------------------------------------
Sub DML_PutXMLProduto_SIG(p_resultado, p_chave, p_nome, p_ativo)
  Dim l_Chave, l_nome, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput, 80, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLProduto_SIG"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIG - Tipo restriзгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLTipo_Restricao(p_resultado, p_chave, p_nome, p_ativo)
  Dim l_Chave, l_nome, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput, 80, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLTipo_Restricao"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIG - Tipo situaзгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLTipo_Situacao(p_resultado, p_chave, p_nome, p_tipo, p_ativo)
  Dim l_Chave, l_nome, l_tipo, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,  2, Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput, 50, Tvl(p_nome))
     set l_tipo                 = .CreateParameter("l_tipo",                adVarchar, adParamInput,  2, Tvl(p_tipo))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_tipo
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLTipo_Situacao"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIG - Tipo aзгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLTipo_Acao(p_resultado, p_chave, p_nome, p_ativo)
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
  
     .CommandText               = Session("schema_is") & "SP_PutXMLTipo_Acao"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIG - Tipo programa
REM -------------------------------------------------------------------------
Sub DML_PutXMLTipo_Programa(p_resultado, p_chave, p_nome, p_ativo)
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
  
     .CommandText               = Session("schema_is") & "SP_PutXMLTipo_Programa"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIG - Unidade de medida
REM -------------------------------------------------------------------------
Sub DML_PutXMLUnidade_Medida_SIG(p_resultado, p_chave, p_nome, p_tipo, p_ativo)
  Dim l_Chave, l_nome, l_tipo, l_ativo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  On error Resume Next
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput, 80, Tvl(p_nome))
     set l_tipo                 = .CreateParameter("l_tipo",                adVarchar, adParamInput,  1, Tvl(p_tipo))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_tipo
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLUnidade_Medida_SIG"
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIGPLAN - Уrgao
REM -------------------------------------------------------------------------
Sub DML_PutXMLOrgao_SIG(p_resultado, p_ano, p_chave, p_tipo_org, p_nome, p_sigla, p_ativo)


  Dim l_ano, l_Chave, l_tipo_org, l_nome, l_sigla, l_ativo
  
  Set l_ano                     = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_org                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_sigla                   = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_ano                  = .CreateParameter("l_ano",                 adInteger, adParamInput,   , Tvl(p_ano))  
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,  5, Tvl(p_chave))
     set l_tipo_org             = .CreateParameter("l_tipo_org",            adVarchar, adParamInput,  1, Tvl(p_tipo_org))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,110, Tvl(p_nome))
     set l_sigla                = .CreateParameter("l_sigla",               adVarchar, adParamInput, 10, Tvl(p_sigla))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_ano
     .parameters.Append         l_Chave
     .parameters.Append         l_tipo_org
     .parameters.Append         l_nome
     .parameters.Append         l_sigla
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutXMLOrgao_SIG"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_tipo_org"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIGPLAN - Unidade
REM -------------------------------------------------------------------------
Sub DML_PutXMLUnidade_SIG(p_resultado, p_ano, p_chave, p_tipo_unid, p_orgao, p_tipo_org, p_nome)


  Dim l_ano, l_Chave, l_tipo_unid, l_orgao, l_tipo_org, l_nome
  
  Set l_ano                     = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_unid               = Server.CreateObject("ADODB.Parameter") 
  Set l_orgao                   = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_org                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_ano                  = .CreateParameter("l_ano",                 adInteger, adParamInput,   , Tvl(p_ano))  
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,  5, Tvl(p_chave))
     set l_tipo_unid            = .CreateParameter("l_tipo_unid",           adVarchar, adParamInput,  1, Tvl(p_tipo_unid))
     set l_orgao                = .CreateParameter("l_orgao",               adVarchar, adParamInput,  5, Tvl(p_orgao))
     set l_tipo_org             = .CreateParameter("l_tipo_org",            adVarchar, adParamInput,  1, Tvl(p_tipo_org))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,110, Tvl(p_nome))
  
     .parameters.Append         l_ano
     .parameters.Append         l_Chave
     .parameters.Append         l_tipo_unid
     .parameters.Append         l_orgao
     .parameters.Append         l_tipo_org
     .parameters.Append         l_nome
  
     .CommandText               = Session("schema_is") & "SP_PutXMLUnidade_SIG"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_tipo_unid"
     .parameters.Delete         "l_orgao"
     .parameters.Delete         "l_tipo_org"
     .parameters.Delete         "l_nome"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIG - Programa
REM -------------------------------------------------------------------------
Sub DML_PutXMLPrograma_SIG(p_resultado, p_cliente, p_ano, p_chave, p_tipo_org, p_orgao, p_nome, p_tipo_prog, _
                          p_macro, p_mes_ini, p_ano_ini, p_mes_fim, p_ano_fim, p_objetivo, p_publico_alvo, _
                          p_justificativa, p_estrategia, p_ln_programa, p_valor_estimado, p_valor_ppa, _
                          p_temporario, p_contexto, p_atual_contexto, p_estagio, p_andamento, p_cronograma, _
                          p_perc_execucao, p_comentario_sit, p_atual_sit, p_situacao_atual, p_resultados_obt, _
                          p_atual_sit_atual, p_coment_execucao)


  Dim l_cliente, l_ano, l_Chave, l_tipo_org, l_orgao, l_nome, l_tipo_prog, l_macro
  Dim l_mes_ini, l_ano_ini, l_mes_fim, l_ano_fim, l_objetivo, l_publico_alvo
  Dim l_justificativa, l_estrategia, l_ln_programa, l_valor_estimado, l_valor_ppa, l_temporario
  Dim l_contexto, l_atual_contexto, l_estagio, l_andamento, l_cronograma, l_perc_execucao
  Dim l_comentario_sit, l_atual_sit, l_situacao_atual, l_resultados_obt, l_atual_sit_atual
  Dim l_coment_execucao
  
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano                     = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_org                = Server.CreateObject("ADODB.Parameter") 
  Set l_orgao                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_prog               = Server.CreateObject("ADODB.Parameter") 
  Set l_macro                   = Server.CreateObject("ADODB.Parameter") 
  Set l_mes_ini                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano_ini                 = Server.CreateObject("ADODB.Parameter")
  Set l_mes_fim                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano_fim                 = Server.CreateObject("ADODB.Parameter")  
  Set l_objetivo                = Server.CreateObject("ADODB.Parameter") 
  Set l_publico_alvo            = Server.CreateObject("ADODB.Parameter") 
  Set l_justificativa           = Server.CreateObject("ADODB.Parameter") 
  Set l_estrategia              = Server.CreateObject("ADODB.Parameter")
  Set l_ln_programa             = Server.CreateObject("ADODB.Parameter")  
  Set l_valor_estimado          = Server.CreateObject("ADODB.Parameter")
  Set l_valor_ppa               = Server.CreateObject("ADODB.Parameter")  
  Set l_temporario              = Server.CreateObject("ADODB.Parameter") 
  Set l_contexto                = Server.CreateObject("ADODB.Parameter") 
  Set l_atual_contexto          = Server.CreateObject("ADODB.Parameter") 
  Set l_estagio                 = Server.CreateObject("ADODB.Parameter")
  Set l_andamento               = Server.CreateObject("ADODB.Parameter") 
  Set l_cronograma              = Server.CreateObject("ADODB.Parameter")  
  Set l_perc_execucao           = Server.CreateObject("ADODB.Parameter") 
  Set l_comentario_sit          = Server.CreateObject("ADODB.Parameter") 
  Set l_atual_sit               = Server.CreateObject("ADODB.Parameter") 
  Set l_situacao_atual          = Server.CreateObject("ADODB.Parameter") 
  Set l_resultados_obt          = Server.CreateObject("ADODB.Parameter") 
  Set l_atual_sit_atual         = Server.CreateObject("ADODB.Parameter") 
  Set l_coment_execucao         = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,     , Tvl(p_cliente))
     set l_ano                  = .CreateParameter("l_ano",                 adInteger, adParamInput,     , Tvl(p_ano))
     set l_chave                = .CreateParameter("l_chave",               adVarchar, adParamInput,    4, Tvl(p_chave))
     set l_tipo_org             = .CreateParameter("l_tipo_org",            adVarchar, adParamInput,    1, Tvl(p_tipo_org))     
     set l_orgao                = .CreateParameter("l_orgao",               adVarchar, adParamInput,    5, Tvl(p_orgao))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  120, Tvl(p_nome))
     set l_tipo_prog            = .CreateParameter("l_tipo_prog",           adInteger, adParamInput,     , Tvl(p_tipo_prog))
     set l_macro                = .CreateParameter("l_macro",               adVarchar, adParamInput,    2, Tvl(p_macro))
     set l_mes_ini              = .CreateParameter("l_mes_ini",             adVarchar, adParamInput,    2, Tvl(p_mes_ini))
     set l_ano_ini              = .CreateParameter("l_ano_ini",             adVarchar, adParamInput,    4, Tvl(p_ano_ini))
     set l_mes_fim              = .CreateParameter("l_mes_fim",             adVarchar, adParamInput,    2, Tvl(p_mes_fim))
     set l_ano_fim              = .CreateParameter("l_ano_fim",             adVarchar, adParamInput,    4, Tvl(p_ano_fim))
     set l_objetivo             = .CreateParameter("l_objetivo",            adVarchar, adParamInput, 4000, Tvl(p_objetivo))
     set l_publico_alvo         = .CreateParameter("l_publico_alvo",        adVarchar, adParamInput, 4000, Tvl(p_publico_alvo))
     set l_justificativa        = .CreateParameter("l_justificativa",       adVarchar, adParamInput, 4000, Tvl(Mid(p_justificativa,1,4000)))
     set l_estrategia           = .CreateParameter("l_estrategia",          adVarchar, adParamInput, 4000, Tvl(Mid(p_estrategia,1,4000)))
     set l_ln_programa          = .CreateParameter("l_ln_programa",         adVarchar, adParamInput,  120, Tvl(p_ln_programa))     
     set l_valor_estimado       = .CreateParameter("l_valor_estimado",      adNumeric)
     l_valor_estimado.Precision    = 18
     l_valor_estimado.NumericScale = 2
     l_valor_estimado.Value        = Tvl(Replace(p_valor_estimado,".",","))
     set l_valor_ppa            = .CreateParameter("l_valor_ppa",      adNumeric)
     l_valor_ppa.Precision      = 18
     l_valor_ppa.NumericScale   = 2
     l_valor_ppa.Value          = Tvl(Replace(p_valor_ppa,".",","))     
     set l_temporario           = .CreateParameter("l_temporario",          adVarchar, adParamInput,    1, Tvl(p_temporario))
     set l_contexto             = .CreateParameter("l_contexto",            adVarchar, adParamInput, 4000, Tvl(Mid(p_contexto,1,4000)))
     set l_atual_contexto       = .CreateParameter("l_atual_contexto",      adDate,    adParamInput,     , Tvl(p_atual_contexto))
     set l_estagio              = .CreateParameter("l_estagio",             adVarchar, adParamInput,    2, Tvl(p_estagio))
     set l_andamento            = .CreateParameter("l_andamento",           adVarchar, adParamInput,    2, Tvl(p_andamento))          
     set l_cronograma           = .CreateParameter("l_cronograma",          adVarchar, adParamInput,    2, Tvl(p_cronograma))
     set l_perc_execucao        = .CreateParameter("l_perc_execucao",       adInteger, adParamInput,     , Tvl(p_perc_execucao))
     set l_comentario_sit       = .CreateParameter("l_comentario_sit",      adVarchar, adParamInput, 4000, Tvl(Mid(p_comentario_sit,1,4000)))
     set l_atual_sit            = .CreateParameter("l_atual_sit",           adDate,    adParamInput,     , Tvl(p_atual_sit))
     set l_situacao_atual       = .CreateParameter("l_situacao_atual",      adVarchar, adParamInput, 4000, Tvl(Mid(p_situacao_atual,1,4000)))
     set l_resultados_obt       = .CreateParameter("l_resultados_obt",      adVarchar, adParamInput, 4000, Tvl(Mid(p_resultados_obt,1,4000)))
     set l_atual_sit_atual      = .CreateParameter("l_atual_sit_atual",     adDate,    adParamInput,     , Tvl(p_atual_sit_atual))
     set l_coment_execucao      = .CreateParameter("l_coment_execucao",     adVarchar, adParamInput, 4000, Tvl(Mid(p_coment_execucao,1,4000)))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_Chave
     .parameters.Append         l_tipo_org
     .parameters.Append         l_orgao
     .parameters.Append         l_nome
     .parameters.Append         l_tipo_prog
     .parameters.Append         l_macro
     .parameters.Append         l_mes_ini
     .parameters.Append         l_ano_ini
     .parameters.Append         l_mes_fim
     .parameters.Append         l_ano_fim
     .parameters.Append         l_objetivo
     .parameters.Append         l_publico_alvo
     .parameters.Append         l_justificativa
     .parameters.Append         l_estrategia
     .parameters.Append         l_ln_programa
     .parameters.Append         l_valor_estimado
     .parameters.Append         l_valor_ppa
     .parameters.Append         l_temporario
     .parameters.Append         l_contexto
     .parameters.Append         l_atual_contexto
     .parameters.Append         l_estagio
     .parameters.Append         l_andamento
     .parameters.Append         l_cronograma
     .parameters.Append         l_perc_execucao
     .parameters.Append         l_comentario_sit
     .parameters.Append         l_atual_sit
     .parameters.Append         l_situacao_atual
     .parameters.Append         l_resultados_obt
     .parameters.Append         l_atual_sit_atual
     .parameters.Append         l_coment_execucao
  
     .CommandText               = Session("schema_is") & "SP_PutXMLPrograma_SIG"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_tipo_org"     
     .parameters.Delete         "l_orgao"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_tipo_prog"
     .parameters.Delete         "l_macro"
     .parameters.Delete         "l_mes_ini"
     .parameters.Delete         "l_ano_ini"
     .parameters.Delete         "l_mes_fim"
     .parameters.Delete         "l_ano_fim"
     .parameters.Delete         "l_objetivo"
     .parameters.Delete         "l_publico_alvo"
     .parameters.Delete         "l_justificativa"
     .parameters.Delete         "l_estrategia"
     .parameters.Delete         "l_ln_programa"
     .parameters.Delete         "l_valor_estimado"
     .parameters.Delete         "l_valor_ppa"
     .parameters.Delete         "l_temporario"
     .parameters.Delete         "l_contexto"
     .parameters.Delete         "l_atual_contexto"
     .parameters.Delete         "l_estagio"
     .parameters.Delete         "l_andamento"
     .parameters.Delete         "l_cronograma"
     .parameters.Delete         "l_perc_execucao"
     .parameters.Delete         "l_comentario_sit"
     .parameters.Delete         "l_atual_sit"
     .parameters.Delete         "l_situacao_atual"
     .parameters.Delete         "l_resultados_obt"
     .parameters.Delete         "l_atual_sit_atual"
     .parameters.Delete         "l_coment_execucao"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIGPLAN - Indicador
REM -------------------------------------------------------------------------
Sub DML_PutXMLIndicador_SIG(p_resultado, p_cliente, p_ano, p_programa, p_chave, p_unidade_medida, p_periodicidade, p_base_geo, p_nome, _
                            p_fonte, p_formula, p_valor_apurado, p_valor_ppa, p_valor_programa, p_valor_mes_1, p_valor_mes_2, _
                            p_valor_mes_3, p_valor_mes_4, p_valor_mes_5, p_valor_mes_6, p_valor_mes_7, p_valor_mes_8, p_valor_mes_9, _
                            p_valor_mes_10, p_valor_mes_11, p_valor_mes_12, p_apuracao)


  Dim l_cliente, l_ano, l_programa, l_Chave, l_unidade_medida, l_periodicidade, l_base_geo, l_nome
  Dim l_fonte, l_formula, l_valor_apurado, l_valor_ppa, l_valor_programa
  Dim l_valor_mes_1, l_valor_mes_2, l_valor_mes_3, l_valor_mes_4, l_valor_mes_5, l_valor_mes_6, l_valor_mes_7
  Dim l_valor_mes_8, l_valor_mes_9, l_valor_mes_10, l_valor_mes_11, l_valor_mes_12, l_apuracao
  
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano                     = Server.CreateObject("ADODB.Parameter") 
  Set l_programa                = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_unidade_medida          = Server.CreateObject("ADODB.Parameter") 
  Set l_periodicidade           = Server.CreateObject("ADODB.Parameter") 
  Set l_base_geo                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_fonte                   = Server.CreateObject("ADODB.Parameter") 
  Set l_formula                 = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_apurado           = Server.CreateObject("ADODB.Parameter")
  Set l_valor_ppa               = Server.CreateObject("ADODB.Parameter")
  Set l_valor_programa          = Server.CreateObject("ADODB.Parameter")
  Set l_valor_mes_1             = Server.CreateObject("ADODB.Parameter")
  Set l_valor_mes_2             = Server.CreateObject("ADODB.Parameter")
  Set l_valor_mes_3             = Server.CreateObject("ADODB.Parameter")          
  Set l_valor_mes_4             = Server.CreateObject("ADODB.Parameter")
  Set l_valor_mes_5             = Server.CreateObject("ADODB.Parameter")
  Set l_valor_mes_6             = Server.CreateObject("ADODB.Parameter")
  Set l_valor_mes_7             = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_mes_8             = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_mes_9             = Server.CreateObject("ADODB.Parameter")        
  Set l_valor_mes_10            = Server.CreateObject("ADODB.Parameter")
  Set l_valor_mes_11            = Server.CreateObject("ADODB.Parameter")   
  Set l_valor_mes_12            = Server.CreateObject("ADODB.Parameter") 
  Set l_apuracao                = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,     , Tvl(p_cliente))
     set l_ano                  = .CreateParameter("l_ano",                 adInteger, adParamInput,     , Tvl(p_ano))
     set l_programa             = .CreateParameter("l_programa",            adVarchar, adParamInput,    4, Tvl(p_programa))
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,     , Tvl(p_chave))
     set l_unidade_medida       = .CreateParameter("l_unidade_medida",      adInteger, adParamInput,     , Tvl(p_unidade_medida))
     set l_periodicidade        = .CreateParameter("l_periodicidade",       adInteger, adParamInput,     , Tvl(p_periodicidade))     
     set l_base_geo             = .CreateParameter("l_base_geo",            adInteger, adParamInput,     , Tvl(p_base_geo))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  200, Tvl(p_nome))
     set l_fonte                = .CreateParameter("l_fonte",               adVarchar, adParamInput,  200, Tvl(p_fonte))
     set l_formula              = .CreateParameter("l_formula",             adVarchar, adParamInput,  4000, Tvl(p_formula))
     set l_valor_apurado        = .CreateParameter("l_valor_apurado",       adNumeric)
     l_valor_apurado.Precision     = 18
     l_valor_apurado.NumericScale  = 2
     l_valor_apurado.Value         = Tvl(Replace(p_valor_apurado,".",","))
     set l_valor_ppa            = .CreateParameter("l_valor_ppa",             adNumeric)
     l_valor_ppa.Precision         = 18
     l_valor_ppa.NumericScale      = 2
     l_valor_ppa.Value             = Tvl(Replace(p_valor_ppa,".",","))
     set l_valor_programa       = .CreateParameter("l_valor_programa",         adNumeric)
     l_valor_programa.Precision    = 18
     l_valor_programa.NumericScale = 2
     l_valor_programa.Value        = Tvl(Replace(p_valor_programa,".",","))
     set l_valor_mes_1          = .CreateParameter("l_valor_mes_1",         adNumeric)
     l_valor_mes_1.Precision    = 18
     l_valor_mes_1.NumericScale = 2
     l_valor_mes_1.Value        = Tvl(Replace(p_valor_mes_1,".",","))
     set l_valor_mes_2          = .CreateParameter("l_valor_mes_2",         adNumeric)
     l_valor_mes_2.Precision    = 18
     l_valor_mes_2.NumericScale = 2
     l_valor_mes_2.Value        = Tvl(Replace(p_valor_mes_2,".",","))
     set l_valor_mes_3          = .CreateParameter("l_valor_mes_3",         adNumeric)
     l_valor_mes_3.Precision    = 18
     l_valor_mes_3.NumericScale = 2
     l_valor_mes_3.Value        = Tvl(Replace(p_valor_mes_3,".",","))
     set l_valor_mes_4          = .CreateParameter("l_valor_mes_4",           adNumeric)
     l_valor_mes_4.Precision    = 18
     l_valor_mes_4.NumericScale = 2
     l_valor_mes_4.Value        = Tvl(Replace(p_valor_mes_4,".",","))
     set l_valor_mes_5          = .CreateParameter("l_valor_mes_5",         adNumeric)
     l_valor_mes_5.Precision    = 18
     l_valor_mes_5.NumericScale = 2
     l_valor_mes_5.Value        = Tvl(Replace(p_valor_mes_5,".",","))
     set l_valor_mes_6          = .CreateParameter("l_valor_mes_6",         adNumeric)
     l_valor_mes_6.Precision    = 18
     l_valor_mes_6.NumericScale = 2
     l_valor_mes_6.Value        = Tvl(Replace(p_valor_mes_6,".",","))
     set l_valor_mes_7          = .CreateParameter("l_valor_mes_7",         adNumeric)
     l_valor_mes_7.Precision    = 18
     l_valor_mes_7.NumericScale = 2
     l_valor_mes_7.Value        = Tvl(Replace(p_valor_mes_7,".",","))
     set l_valor_mes_8          = .CreateParameter("l_valor_mes_8",         adNumeric)
     l_valor_mes_8.Precision    = 18
     l_valor_mes_8.NumericScale = 2
     l_valor_mes_8.Value        = Tvl(Replace(p_valor_mes_8,".",","))
     set l_valor_mes_9          = .CreateParameter("l_valor_mes_9",         adNumeric)
     l_valor_mes_9.Precision    = 18
     l_valor_mes_9.NumericScale = 2
     l_valor_mes_9.Value        = Tvl(Replace(p_valor_mes_9,".",","))
     set l_valor_mes_10         = .CreateParameter("l_valor_mes_10",         adNumeric)
     l_valor_mes_10.Precision    = 18
     l_valor_mes_10.NumericScale = 2
     l_valor_mes_10.Value        = Tvl(Replace(p_valor_mes_10,".",","))
     set l_valor_mes_11         = .CreateParameter("l_valor_mes_11",         adNumeric)
     l_valor_mes_11.Precision    = 18
     l_valor_mes_11.NumericScale = 2
     l_valor_mes_11.Value        = Tvl(Replace(p_valor_mes_11,".",","))
     set l_valor_mes_12          = .CreateParameter("l_valor_mes_12",         adNumeric)
     l_valor_mes_12.Precision    = 18
     l_valor_mes_12.NumericScale = 2
     l_valor_mes_12.Value        = Tvl(Replace(p_valor_mes_12,".",","))
     set l_apuracao             = .CreateParameter("l_apuracao",            adDate,    adParamInput,     , Tvl(p_apuracao))
          
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_programa
     .parameters.Append         l_Chave
     .parameters.Append         l_unidade_medida
     .parameters.Append         l_periodicidade
     .parameters.Append         l_base_geo
     .parameters.Append         l_nome
     .parameters.Append         l_fonte
     .parameters.Append         l_formula
     .parameters.Append         l_valor_apurado
     .parameters.Append         l_valor_ppa
     .parameters.Append         l_valor_programa
     .parameters.Append         l_valor_mes_1   
     .parameters.Append         l_valor_mes_2
     .parameters.Append         l_valor_mes_3
     .parameters.Append         l_valor_mes_4
     .parameters.Append         l_valor_mes_5
     .parameters.Append         l_valor_mes_6
     .parameters.Append         l_valor_mes_7
     .parameters.Append         l_valor_mes_8
     .parameters.Append         l_valor_mes_9
     .parameters.Append         l_valor_mes_10
     .parameters.Append         l_valor_mes_11
     .parameters.Append         l_valor_mes_12
     .parameters.Append         l_apuracao
     .CommandText               = Session("schema_is") & "SP_PutXMLIndicador_SIG"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_programa"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_unidade_medida"
     .parameters.Delete         "l_periodicidade"
     .parameters.Delete         "l_base_geo"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_fonte"
     .parameters.Delete         "l_formula"
     .parameters.Delete         "l_valor_apurado"
     .parameters.Delete         "l_valor_ppa"
     .parameters.Delete         "l_valor_programa"
     .parameters.Delete         "l_valor_mes_1"
     .parameters.Delete         "l_valor_mes_2"
     .parameters.Delete         "l_valor_mes_3"                         
     .parameters.Delete         "l_valor_mes_4"
     .parameters.Delete         "l_valor_mes_5"
     .parameters.Delete         "l_valor_mes_6"
     .parameters.Delete         "l_valor_mes_7"
     .parameters.Delete         "l_valor_mes_8"
     .parameters.Delete         "l_valor_mes_9"
     .parameters.Delete         "l_valor_mes_10"
     .parameters.Delete         "l_valor_mes_11"
     .parameters.Delete         "l_valor_mes_12"
     .parameters.Delete         "l_apuracao"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIGPLAN - Aзгo
REM -------------------------------------------------------------------------
Sub DML_PutXMLAcao_SIG(p_resultado, p_cliente, p_ano, p_cd_programa, p_cd_acao, p_cd_subacao, p_cd_localizador, p_cd_regiao, p_cd_acao_ppa, _
                       p_tipo_acao, p_cd_produto, p_unidade_med, p_unidade, p_tipo_unid, p_estagio, p_andamento, p_cronograma, _
                       p_perc_execucao, p_desc_acao, p_desc_subacao, p_comentario, p_direta, p_descentralizada, p_linha_credito, _
                       p_cumulativa, p_mes_ini, p_ano_ini, p_mes_fim, p_ano_fim, p_valor_ano_ant, p_coment_situacao, p_situacao_atual, _
                       p_result_obtidos, p_mes_conc, p_ano_conc, p_coment_fisica, p_coment_financ, p_coment_fisica_bgu, _
                       p_coment_financ_bgu, p_restos_pagar, p_coment_execucao, p_coment_restos, p_fiscal_segur, p_estatais, _
                       p_outras_fontes, p_cd_sof_ref)


  Dim l_cliente, l_ano, l_cd_programa, l_cd_acao, l_cd_subacao, l_cd_localizador, l_cd_regiao, l_cd_acao_ppa
  Dim l_tipo_acao, l_cd_produto, l_unidade_med, l_unidade, l_tipo_unid, l_estagio, l_andamento, l_cronograma
  Dim l_perc_execucao, l_desc_acao, l_desc_subacao, l_comentario, l_direta, l_descentralizada, l_linha_credito
  Dim l_cumulativa, l_mes_ini, l_ano_ini, l_mes_fim, l_ano_fim, l_valor_ano_ant, l_coment_situacao, l_situacao_atual
  Dim l_result_obtidos, l_mes_conc, l_ano_conc, l_coment_fisica, l_coment_financ, l_coment_fisica_bgu
  Dim l_coment_financ_bgu, l_restos_pagar, l_coment_execucao, l_coment_restos, l_fiscal_segur, l_estatais
  Dim l_outras_fontes, l_cd_sof_ref
  
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano                     = Server.CreateObject("ADODB.Parameter")
  Set l_cd_programa             = Server.CreateObject("ADODB.Parameter")  
  Set l_cd_acao                 = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_subacao              = Server.CreateObject("ADODB.Parameter")
  Set l_cd_localizador          = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_regiao               = Server.CreateObject("ADODB.Parameter")  
  Set l_cd_acao_ppa             = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_acao               = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_produto              = Server.CreateObject("ADODB.Parameter") 
  Set l_unidade_med             = Server.CreateObject("ADODB.Parameter") 
  Set l_unidade                 = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_unid               = Server.CreateObject("ADODB.Parameter") 
  Set l_estagio                 = Server.CreateObject("ADODB.Parameter") 
  Set l_andamento               = Server.CreateObject("ADODB.Parameter")
  Set l_cronograma              = Server.CreateObject("ADODB.Parameter") 
  Set l_perc_execucao           = Server.CreateObject("ADODB.Parameter") 
  Set l_desc_acao               = Server.CreateObject("ADODB.Parameter") 
  Set l_desc_subacao            = Server.CreateObject("ADODB.Parameter") 
  Set l_comentario              = Server.CreateObject("ADODB.Parameter") 
  Set l_direta                  = Server.CreateObject("ADODB.Parameter")
  Set l_descentralizada         = Server.CreateObject("ADODB.Parameter")  
  Set l_linha_credito           = Server.CreateObject("ADODB.Parameter")  
  Set l_cumulativa              = Server.CreateObject("ADODB.Parameter")  
  Set l_mes_ini                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano_ini                 = Server.CreateObject("ADODB.Parameter")
  Set l_mes_fim                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano_fim                 = Server.CreateObject("ADODB.Parameter")  
  Set l_valor_ano_ant           = Server.CreateObject("ADODB.Parameter") 
  Set l_coment_situacao         = Server.CreateObject("ADODB.Parameter") 
  Set l_situacao_atual          = Server.CreateObject("ADODB.Parameter") 
  Set l_result_obtidos          = Server.CreateObject("ADODB.Parameter") 
  Set l_mes_conc                = Server.CreateObject("ADODB.Parameter") 
  Set l_ano_conc                = Server.CreateObject("ADODB.Parameter") 
  Set l_coment_fisica           = Server.CreateObject("ADODB.Parameter")
  Set l_coment_financ           = Server.CreateObject("ADODB.Parameter") 
  Set l_coment_fisica_bgu       = Server.CreateObject("ADODB.Parameter")
  Set l_coment_financ_bgu       = Server.CreateObject("ADODB.Parameter") 
  Set l_restos_pagar            = Server.CreateObject("ADODB.Parameter")
  Set l_coment_execucao         = Server.CreateObject("ADODB.Parameter") 
  Set l_coment_restos           = Server.CreateObject("ADODB.Parameter")
  Set l_fiscal_segur            = Server.CreateObject("ADODB.Parameter")   
  Set l_estatais                = Server.CreateObject("ADODB.Parameter")
  Set l_outras_fontes           = Server.CreateObject("ADODB.Parameter")   
  Set l_cd_sof_ref              = Server.CreateObject("ADODB.Parameter")  
  
  with sp
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,     , Tvl(p_cliente))
     set l_ano                  = .CreateParameter("l_ano",                 adInteger, adParamInput,     , Tvl(p_ano))
     set l_cd_programa          = .CreateParameter("l_cd_programa",         adVarchar, adParamInput,    4, Tvl(p_cd_programa))
     set l_cd_acao              = .CreateParameter("l_cd_acao",             adVarchar, adParamInput,    4, Tvl(p_cd_acao))
     set l_cd_subacao           = .CreateParameter("l_cd_subacao",          adVarchar, adParamInput,    4, Tvl(p_cd_subacao))
     set l_cd_localizador       = .CreateParameter("l_cd_localizador",      adVarchar, adParamInput,    4, Tvl(p_cd_localizador))
     set l_cd_regiao            = .CreateParameter("l_cd_regiao",           adVarchar, adParamInput,    2, Tvl(p_cd_regiao))
     set l_cd_acao_ppa          = .CreateParameter("l_cd_acao_ppa",         adVarchar, adParamInput,    5, Tvl(p_cd_acao_ppa))
     set l_tipo_acao            = .CreateParameter("l_tipo_acao",           adInteger, adParamInput,     , Tvl(p_tipo_acao))
     set l_cd_produto           = .CreateParameter("l_cd_produto",          adInteger, adParamInput,     , Tvl(p_cd_produto))
     set l_unidade_med          = .CreateParameter("l_unidade_med",         adInteger, adParamInput,     , Tvl(p_unidade_med))
     set l_unidade              = .CreateParameter("l_unidade",             adVarchar, adParamInput,    5, Tvl(p_unidade))
     set l_tipo_unid            = .CreateParameter("l_tipo_unid",           adVarchar, adParamInput,    1, Tvl(p_tipo_unid))
     set l_estagio              = .CreateParameter("l_estagio",             adVarchar, adParamInput,    2, Tvl(p_estagio))
     set l_andamento            = .CreateParameter("l_andamento",           adVarchar, adParamInput,    2, Tvl(p_andamento))
     set l_cronograma           = .CreateParameter("l_cronograma",          adVarchar, adParamInput,    2, Tvl(p_cronograma))
     set l_perc_execucao        = .CreateParameter("l_perc_execucao",       adInteger, adParamInput,     , Tvl(p_perc_execucao))
     set l_desc_acao            = .CreateParameter("l_desc_acao",           adVarchar, adParamInput,  255, Tvl(p_desc_acao))
     set l_desc_subacao         = .CreateParameter("l_desc_subacao",        adVarchar, adParamInput,  300, Tvl(p_desc_subacao))
     set l_comentario           = .CreateParameter("l_comentario",          adVarchar, adParamInput, 4000, Tvl(p_comentario))
     set l_direta               = .CreateParameter("l_direta",              adVarchar, adParamInput,    1, Tvl(p_direta))
     set l_descentralizada      = .CreateParameter("l_descentralizada",     adVarchar, adParamInput,    1, Tvl(p_descentralizada))
     set l_linha_credito        = .CreateParameter("l_linha_credito",       adVarchar, adParamInput,    1, Tvl(p_linha_credito))
     set l_cumulativa           = .CreateParameter("l_cumulativa",          adVarchar, adParamInput,    1, Tvl(p_cumulativa))
     set l_mes_ini              = .CreateParameter("l_mes_ini",             adVarchar, adParamInput,    2, Tvl(p_mes_ini))
     set l_ano_ini              = .CreateParameter("l_ano_ini",             adVarchar, adParamInput,    4, Tvl(p_ano_ini))
     set l_mes_fim              = .CreateParameter("l_mes_fim",             adVarchar, adParamInput,    2, Tvl(p_mes_fim))
     set l_ano_fim              = .CreateParameter("l_ano_fim",             adVarchar, adParamInput,    4, Tvl(p_ano_fim))
     set l_valor_ano_ant        = .CreateParameter("l_valor_ano_ant",       adNumeric)
     l_valor_ano_ant.Precision    = 18
     l_valor_ano_ant.NumericScale = 2
     l_valor_ano_ant.Value        = Tvl(Replace(p_valor_ano_ant,".",","))
     set l_coment_situacao      = .CreateParameter("l_coment_situacao",     adVarchar, adParamInput, 4000, Tvl(p_coment_situacao))
     set l_situacao_atual       = .CreateParameter("l_situacao_atual",      adVarchar, adParamInput, 4000, Tvl(p_situacao_atual))
     set l_result_obtidos       = .CreateParameter("l_result_obtidos",      adVarchar, adParamInput, 4000, Tvl(p_result_obtidos))
     set l_mes_conc             = .CreateParameter("l_mes_conc",            adVarchar, adParamInput,    2, Tvl(p_mes_conc))
     set l_ano_conc             = .CreateParameter("l_ano_conc",            adVarchar, adParamInput,    4, Tvl(p_ano_conc))
     set l_coment_fisica        = .CreateParameter("l_coment_fisica",       adVarchar, adParamInput, 4000, Tvl(p_coment_fisica))
     set l_coment_financ        = .CreateParameter("l_coment_financ",       adVarchar, adParamInput, 4000, Tvl(p_coment_financ))     
     set l_coment_fisica_bgu    = .CreateParameter("l_coment_fisica_bgu",   adVarchar, adParamInput, 4000, Tvl(p_coment_fisica_bgu))
     set l_coment_financ_bgu    = .CreateParameter("l_coment_financ_bgu",   adVarchar, adParamInput, 4000, Tvl(p_coment_financ_bgu))
     set l_restos_pagar         = .CreateParameter("l_restos_pagar",        adVarchar, adParamInput,    1, Tvl(p_restos_pagar))                   
     set l_coment_execucao      = .CreateParameter("l_coment_execucao",     adVarchar, adParamInput, 4000, Tvl(p_coment_execucao))
     set l_coment_restos        = .CreateParameter("l_coment_restos",       adVarchar, adParamInput, 4000, Tvl(p_coment_restos))     
     set l_fiscal_segur         = .CreateParameter("l_fiscal_segur",        adVarchar, adParamInput,    1, Tvl(p_fiscal_segur))
     set l_estatais             = .CreateParameter("l_estatais",            adVarchar, adParamInput,    1, Tvl(p_estatais))
     set l_outras_fontes        = .CreateParameter("l_outras_fontes",       adVarchar, adParamInput,    1, Tvl(p_outras_fontes))
     set l_cd_sof_ref           = .CreateParameter("l_cd_sof_ref",          adInteger, adParamInput,     , Tvl(p_cd_sof_ref))

     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_cd_programa
     .parameters.Append         l_cd_acao
     .parameters.Append         l_cd_subacao
     .parameters.Append         l_cd_localizador
     .parameters.Append         l_cd_regiao
     .parameters.Append         l_cd_acao_ppa
     .parameters.Append         l_tipo_acao
     .parameters.Append         l_cd_produto
     .parameters.Append         l_unidade_med
     .parameters.Append         l_unidade
     .parameters.Append         l_tipo_unid
     .parameters.Append         l_estagio
     .parameters.Append         l_andamento
     .parameters.Append         l_cronograma
     .parameters.Append         l_perc_execucao
     .parameters.Append         l_desc_acao
     .parameters.Append         l_desc_subacao
     .parameters.Append         l_comentario
     .parameters.Append         l_direta
     .parameters.Append         l_descentralizada
     .parameters.Append         l_linha_credito
     .parameters.Append         l_cumulativa
     .parameters.Append         l_mes_ini
     .parameters.Append         l_ano_ini
     .parameters.Append         l_mes_fim
     .parameters.Append         l_ano_fim
     .parameters.Append         l_valor_ano_ant     
     .parameters.Append         l_coment_situacao
     .parameters.Append         l_situacao_atual
     .parameters.Append         l_result_obtidos
     .parameters.Append         l_mes_conc
     .parameters.Append         l_ano_conc
     .parameters.Append         l_coment_fisica
     .parameters.Append         l_coment_financ
     .parameters.Append         l_coment_fisica_bgu
     .parameters.Append         l_coment_financ_bgu
     .parameters.Append         l_restos_pagar
     .parameters.Append         l_coment_execucao
     .parameters.Append         l_coment_restos
     .parameters.Append         l_fiscal_segur
     .parameters.Append         l_estatais     
     .parameters.Append         l_outras_fontes
     .parameters.Append         l_cd_sof_ref
  
     .CommandText               = Session("schema_is") & "SP_PutXMLAcao_SIG"
     'On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_cd_programa"
     .parameters.Delete         "l_cd_acao"
     .parameters.Delete         "l_cd_subacao"
     .parameters.Delete         "l_cd_localizador"
     .parameters.Delete         "l_cd_regiao"
     .parameters.Delete         "l_cd_acao_ppa"
     .parameters.Delete         "l_tipo_acao"
     .parameters.Delete         "l_cd_produto"
     .parameters.Delete         "l_unidade_med"
     .parameters.Delete         "l_unidade"
     .parameters.Delete         "l_tipo_unid"
     .parameters.Delete         "l_estagio"
     .parameters.Delete         "l_andamento"
     .parameters.Delete         "l_cronograma"
     .parameters.Delete         "l_perc_execucao"
     .parameters.Delete         "l_desc_acao"
     .parameters.Delete         "l_desc_subacao"
     .parameters.Delete         "l_comentario"
     .parameters.Delete         "l_direta"
     .parameters.Delete         "l_descentralizada"
     .parameters.Delete         "l_linha_credito"
     .parameters.Delete         "l_cumulativa"
     .parameters.Delete         "l_mes_ini"
     .parameters.Delete         "l_ano_ini"
     .parameters.Delete         "l_mes_fim"
     .parameters.Delete         "l_ano_fim"
     .parameters.Delete         "l_valor_ano_ant"
     .parameters.Delete         "l_coment_situacao"
     .parameters.Delete         "l_situacao_atual"
     .parameters.Delete         "l_result_obtidos"
     .parameters.Delete         "l_mes_conc"
     .parameters.Delete         "l_ano_conc"
     .parameters.Delete         "l_coment_fisica"
     .parameters.Delete         "l_coment_financ"
     .parameters.Delete         "l_coment_fisica_bgu"
     .parameters.Delete         "l_coment_financ_bgu"
     .parameters.Delete         "l_restos_pagar"
     .parameters.Delete         "l_coment_execucao"
     .parameters.Delete         "l_coment_restos"
     .parameters.Delete         "l_fiscal_segur"
     .parameters.Delete         "l_estatais"
     .parameters.Delete         "l_outras_fontes"
     .parameters.Delete         "l_cd_sof_ref"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIGPLAM - Dado Fнsico
REM -------------------------------------------------------------------------
Sub DML_PutXMLDadoFisico_SIG(p_resultado, p_cliente, p_ano, p_cd_programa, p_cd_acao, p_cd_subacao, p_cd_regiao, _
                             p_cron_ini_mes_1, p_cron_ini_mes_2, p_cron_ini_mes_3, p_cron_ini_mes_4, p_cron_ini_mes_5, p_cron_ini_mes_6, _
                             p_cron_ini_mes_7, p_cron_ini_mes_8, p_cron_ini_mes_9, p_cron_ini_mes_10, p_cron_ini_mes_11, p_cron_ini_mes_12, _
                             p_cron_mes_1, p_cron_mes_2, p_cron_mes_3, p_cron_mes_4, p_cron_mes_5, p_cron_mes_6, _
                             p_cron_mes_7, p_cron_mes_8, p_cron_mes_9, p_cron_mes_10, p_cron_mes_11, p_cron_mes_12, _
                             p_real_mes_1, p_real_mes_2, p_real_mes_3, p_real_mes_4, p_real_mes_5, p_real_mes_6, _
                             p_real_mes_7, p_real_mes_8, p_real_mes_9, p_real_mes_10, p_real_mes_11, p_real_mes_12, _
                             p_previsao_ano, p_cron_ini_ano, p_atual_ano, p_cron_ano, p_real_ano, p_comentario_execucao)


  Dim l_cliente, l_ano, l_cd_programa, l_cd_acao, l_cd_subacao, l_cd_regiao
  Dim l_cron_ini_mes_1, l_cron_ini_mes_2, l_cron_ini_mes_3, l_cron_ini_mes_4, l_cron_ini_mes_5, l_cron_ini_mes_6
  Dim l_cron_ini_mes_7, l_cron_ini_mes_8, l_cron_ini_mes_9, l_cron_ini_mes_10, l_cron_ini_mes_11, l_cron_ini_mes_12
  Dim l_cron_mes_1, l_cron_mes_2, l_cron_mes_3, l_cron_mes_4, l_cron_mes_5, l_cron_mes_6
  Dim l_cron_mes_7, l_cron_mes_8, l_cron_mes_9, l_cron_mes_10, l_cron_mes_11, l_cron_mes_12
  Dim l_real_mes_1, l_real_mes_2, l_real_mes_3, l_real_mes_4, l_real_mes_5, l_real_mes_6
  Dim l_real_mes_7, l_real_mes_8, l_real_mes_9, l_real_mes_10, l_real_mes_11, l_real_mes_12
  Dim l_previsao_ano, l_cron_ini_ano, l_atual_ano, l_cron_ano, l_real_ano, l_comentario_execucao
  
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano                     = Server.CreateObject("ADODB.Parameter")
  Set l_cd_programa             = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_acao                 = Server.CreateObject("ADODB.Parameter")
  Set l_cd_subacao              = Server.CreateObject("ADODB.Parameter")    
  Set l_cd_regiao               = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_1          = Server.CreateObject("ADODB.Parameter")
  Set l_cron_ini_mes_2          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_3          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_4          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_5          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_6          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_7          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_8          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_9          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_10         = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_11         = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_12         = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_1              = Server.CreateObject("ADODB.Parameter")
  Set l_cron_mes_2              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_3              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_4              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_5              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_6              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_7              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_8              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_9              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_10             = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_11             = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_12             = Server.CreateObject("ADODB.Parameter")  
  Set l_real_mes_1              = Server.CreateObject("ADODB.Parameter")
  Set l_real_mes_2              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_3              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_4              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_5              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_6              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_7              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_8              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_9              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_10             = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_11             = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_12             = Server.CreateObject("ADODB.Parameter")  
  Set l_previsao_ano            = Server.CreateObject("ADODB.Parameter")
  Set l_cron_ini_ano            = Server.CreateObject("ADODB.Parameter")  
  Set l_atual_ano               = Server.CreateObject("ADODB.Parameter")  
  Set l_cron_ano                = Server.CreateObject("ADODB.Parameter")    
  Set l_real_ano                = Server.CreateObject("ADODB.Parameter") 
  Set l_comentario_execucao     = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,     , Tvl(p_cliente))
     set l_ano                  = .CreateParameter("l_ano",                 adInteger, adParamInput,     , Tvl(p_ano))
     set l_cd_programa          = .CreateParameter("l_cd_programa",         adVarchar, adParamInput,    4, Tvl(p_cd_programa))
     set l_cd_acao              = .CreateParameter("l_cd_acao",             adVarchar, adParamInput,    4, Tvl(p_cd_acao))
     set l_cd_subacao           = .CreateParameter("l_cd_subacao",          adVarchar, adParamInput,    4, Tvl(p_cd_subacao))
     set l_cd_regiao            = .CreateParameter("l_cd_regiao",           adVarchar, adParamInput,    4, Tvl(p_cd_regiao))
     set l_cron_ini_mes_1       = .CreateParameter("l_cron_ini_mes_1",      adNumeric)
     l_cron_ini_mes_1.Precision      = 18
     l_cron_ini_mes_1.NumericScale   = 4
     l_cron_ini_mes_1.Value          = Tvl(Replace(p_cron_ini_mes_1,".",","))
     set l_cron_ini_mes_2       = .CreateParameter("l_cron_ini_mes_2",      adNumeric)
     l_cron_ini_mes_2.Precision      = 18
     l_cron_ini_mes_2.NumericScale   = 4
     l_cron_ini_mes_2.Value          = Tvl(Replace(p_cron_ini_mes_2,".",","))
     set l_cron_ini_mes_3       = .CreateParameter("l_cron_ini_mes_3",      adNumeric)
     l_cron_ini_mes_3.Precision      = 18
     l_cron_ini_mes_3.NumericScale   = 4
     l_cron_ini_mes_3.Value          = Tvl(Replace(p_cron_ini_mes_3,".",","))
     set l_cron_ini_mes_4       = .CreateParameter("l_cron_ini_mes_4",      adNumeric)
     l_cron_ini_mes_4.Precision      = 18
     l_cron_ini_mes_4.NumericScale   = 4
     l_cron_ini_mes_4.Value          = Tvl(Replace(p_cron_ini_mes_4,".",","))
     set l_cron_ini_mes_5       = .CreateParameter("l_cron_ini_mes_5",      adNumeric)
     l_cron_ini_mes_5.Precision      = 18
     l_cron_ini_mes_5.NumericScale   = 4
     l_cron_ini_mes_5.Value          = Tvl(Replace(p_cron_ini_mes_5,".",","))
     set l_cron_ini_mes_6       = .CreateParameter("l_cron_ini_mes_6",      adNumeric)
     l_cron_ini_mes_6.Precision      = 18
     l_cron_ini_mes_6.NumericScale   = 4
     l_cron_ini_mes_6.Value          = Tvl(Replace(p_cron_ini_mes_6,".",","))
     set l_cron_ini_mes_7       = .CreateParameter("l_cron_ini_mes_7",      adNumeric)
     l_cron_ini_mes_7.Precision      = 18
     l_cron_ini_mes_7.NumericScale   = 4
     l_cron_ini_mes_7.Value          = Tvl(Replace(p_cron_ini_mes_7,".",","))
     set l_cron_ini_mes_8       = .CreateParameter("l_cron_ini_mes_8",      adNumeric)
     l_cron_ini_mes_8.Precision      = 18
     l_cron_ini_mes_8.NumericScale   = 4
     l_cron_ini_mes_8.Value          = Tvl(Replace(p_cron_ini_mes_8,".",","))
     set l_cron_ini_mes_9       = .CreateParameter("l_cron_ini_mes_9",      adNumeric)
     l_cron_ini_mes_9.Precision      = 18
     l_cron_ini_mes_9.NumericScale   = 4
     l_cron_ini_mes_9.Value          = Tvl(Replace(p_cron_ini_mes_9,".",","))
     set l_cron_ini_mes_10      = .CreateParameter("l_cron_ini_mes_10",     adNumeric)
     l_cron_ini_mes_10.Precision     = 18
     l_cron_ini_mes_10.NumericScale  = 4
     l_cron_ini_mes_10.Value         = Tvl(Replace(p_cron_ini_mes_10,".",","))
     set l_cron_ini_mes_11      = .CreateParameter("l_cron_ini_mes_11",     adNumeric)
     l_cron_ini_mes_11.Precision     = 18
     l_cron_ini_mes_11.NumericScale  = 4
     l_cron_ini_mes_11.Value         = Tvl(Replace(p_cron_ini_mes_11,".",","))
     set l_cron_ini_mes_12      = .CreateParameter("l_cron_ini_mes_12",     adNumeric)
     l_cron_ini_mes_12.Precision     = 18
     l_cron_ini_mes_12.NumericScale  = 4
     l_cron_ini_mes_12.Value         = Tvl(Replace(p_cron_ini_mes_12,".",","))
     set l_cron_mes_1           = .CreateParameter("l_cron_mes_1",          adNumeric)
     l_cron_mes_1.Precision         = 18
     l_cron_mes_1.NumericScale      = 4
     l_cron_mes_1.Value             = Tvl(Replace(p_cron_mes_1,".",","))
     set l_cron_mes_2           = .CreateParameter("l_cron_mes_2",          adNumeric)
     l_cron_mes_2.Precision         = 18
     l_cron_mes_2.NumericScale      = 4
     l_cron_mes_2.Value             = Tvl(Replace(p_cron_mes_2,".",","))
     set l_cron_mes_3           = .CreateParameter("l_cron_mes_3",          adNumeric)
     l_cron_mes_3.Precision         = 18
     l_cron_mes_3.NumericScale      = 4
     l_cron_mes_3.Value             = Tvl(Replace(p_cron_mes_3,".",","))
     set l_cron_mes_4           = .CreateParameter("l_cron_mes_4",          adNumeric)
     l_cron_mes_4.Precision         = 18
     l_cron_mes_4.NumericScale      = 4
     l_cron_mes_4.Value             = Tvl(Replace(p_cron_mes_4,".",","))
     set l_cron_mes_5           = .CreateParameter("l_cron_mes_5",          adNumeric)
     l_cron_mes_5.Precision         = 18
     l_cron_mes_5.NumericScale      = 4
     l_cron_mes_5.Value             = Tvl(Replace(p_cron_mes_5,".",","))
     set l_cron_mes_6           = .CreateParameter("l_cron_mes_6",          adNumeric)
     l_cron_mes_6.Precision         = 18
     l_cron_mes_6.NumericScale      = 4
     l_cron_mes_6.Value             = Tvl(Replace(p_cron_mes_6,".",","))
     set l_cron_mes_7           = .CreateParameter("l_cron_mes_7",          adNumeric)
     l_cron_mes_7.Precision         = 18
     l_cron_mes_7.NumericScale      = 4
     l_cron_mes_7.Value             = Tvl(Replace(p_cron_mes_7,".",","))
     set l_cron_mes_8           = .CreateParameter("l_cron_mes_8",          adNumeric)
     l_cron_mes_8.Precision         = 18
     l_cron_mes_8.NumericScale      = 4
     l_cron_mes_8.Value             = Tvl(Replace(p_cron_mes_8,".",","))
     set l_cron_mes_9           = .CreateParameter("l_cron_mes_9",          adNumeric)
     l_cron_mes_9.Precision         = 18
     l_cron_mes_9.NumericScale      = 4
     l_cron_mes_9.Value             = Tvl(Replace(p_cron_mes_9,".",","))
     set l_cron_mes_10          = .CreateParameter("l_cron_mes_10",         adNumeric)
     l_cron_mes_10.Precision        = 18
     l_cron_mes_10.NumericScale     = 4
     l_cron_mes_10.Value            = Tvl(Replace(p_cron_mes_10,".",","))
     set l_cron_mes_11          = .CreateParameter("l_cron_mes_11",         adNumeric)
     l_cron_mes_11.Precision        = 18
     l_cron_mes_11.NumericScale     = 4
     l_cron_mes_11.Value            = Tvl(Replace(p_cron_mes_11,".",","))
     set l_cron_mes_12          = .CreateParameter("l_cron_mes_12",         adNumeric)
     l_cron_mes_12.Precision        = 18
     l_cron_mes_12.NumericScale     = 4
     l_cron_mes_12.Value            = Tvl(Replace(p_cron_mes_12,".",","))
     set l_real_mes_1           = .CreateParameter("l_real_mes_1",          adNumeric)
     l_real_mes_1.Precision         = 18
     l_real_mes_1.NumericScale      = 4
     l_real_mes_1.Value             = Tvl(Replace(p_real_mes_1,".",","))
     set l_real_mes_2           = .CreateParameter("l_real_mes_2",          adNumeric)
     l_real_mes_2.Precision         = 18
     l_real_mes_2.NumericScale      = 4
     l_real_mes_2.Value             = Tvl(Replace(p_real_mes_2,".",","))
     set l_real_mes_3           = .CreateParameter("l_real_mes_3",          adNumeric)
     l_real_mes_3.Precision         = 18
     l_real_mes_3.NumericScale      = 4
     l_real_mes_3.Value             = Tvl(Replace(p_real_mes_3,".",","))
     set l_real_mes_4           = .CreateParameter("l_real_mes_4",          adNumeric)
     l_real_mes_4.Precision         = 18
     l_real_mes_4.NumericScale      = 4
     l_real_mes_4.Value             = Tvl(Replace(p_real_mes_4,".",","))
     set l_real_mes_5           = .CreateParameter("l_real_mes_5",          adNumeric)
     l_real_mes_5.Precision         = 18
     l_real_mes_5.NumericScale      = 4
     l_real_mes_5.Value             = Tvl(Replace(p_real_mes_5,".",","))
     set l_real_mes_6           = .CreateParameter("l_real_mes_6",          adNumeric)
     l_real_mes_6.Precision         = 18
     l_real_mes_6.NumericScale      = 4
     l_real_mes_6.Value             = Tvl(Replace(p_real_mes_6,".",","))
     set l_real_mes_7           = .CreateParameter("l_real_mes_7",          adNumeric)
     l_real_mes_7.Precision         = 18
     l_real_mes_7.NumericScale      = 4
     l_real_mes_7.Value             = Tvl(Replace(p_real_mes_7,".",","))
     set l_real_mes_8           = .CreateParameter("l_real_mes_8",          adNumeric)
     l_real_mes_8.Precision         = 18
     l_real_mes_8.NumericScale      = 4
     l_real_mes_8.Value             = Tvl(Replace(p_real_mes_8,".",","))
     set l_real_mes_9           = .CreateParameter("l_real_mes_9",          adNumeric)
     l_real_mes_9.Precision         = 18
     l_real_mes_9.NumericScale      = 4
     l_real_mes_9.Value             = Tvl(Replace(p_real_mes_9,".",","))
     set l_real_mes_10          = .CreateParameter("l_real_mes_10",         adNumeric)
     l_real_mes_10.Precision        = 18
     l_real_mes_10.NumericScale     = 4
     l_real_mes_10.Value            = Tvl(Replace(p_real_mes_10,".",","))
     set l_real_mes_11          = .CreateParameter("l_real_mes_11",         adNumeric)
     l_real_mes_11.Precision        = 18
     l_real_mes_11.NumericScale     = 4
     l_real_mes_11.Value            = Tvl(Replace(p_real_mes_11,".",","))
     set l_real_mes_12          = .CreateParameter("l_real_mes_12",         adNumeric)
     l_real_mes_12.Precision        = 18
     l_real_mes_12.NumericScale     = 4
     l_real_mes_12.Value            = Tvl(Replace(p_real_mes_12,".",","))
     set l_previsao_ano         = .CreateParameter("l_previsao_ano",        adNumeric)
     l_previsao_ano.Precision       = 18
     l_previsao_ano.NumericScale    = 4
     l_previsao_ano.Value           = Tvl(Replace(p_previsao_ano,".",","))
     set l_cron_ini_ano         = .CreateParameter("l_cron_ini_ano",        adNumeric)
     l_cron_ini_ano.Precision       = 18
     l_cron_ini_ano.NumericScale    = 4
     l_cron_ini_ano.Value           = Tvl(Replace(p_cron_ini_ano,".",","))
     set l_atual_ano            = .CreateParameter("l_atual_ano",        adNumeric)
     l_atual_ano.Precision          = 18
     l_atual_ano.NumericScale       = 4
     l_atual_ano.Value              = Tvl(Replace(p_atual_ano,".",","))
     set l_cron_ano             = .CreateParameter("l_cron_ano",        adNumeric)
     l_cron_ano.Precision           = 18
     l_cron_ano.NumericScale        = 4
     l_cron_ano.Value               = Tvl(Replace(p_cron_ano,".",","))
     set l_real_ano             = .CreateParameter("l_real_ano",        adNumeric)
     l_real_ano.Precision           = 18
     l_real_ano.NumericScale        = 4
     l_real_ano.Value               = Tvl(Replace(p_real_ano,".",","))
     set l_comentario_execucao  = .CreateParameter("l_comentario_execucao", adVarchar, adParamInput, 4000, Tvl(p_comentario_execucao))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_cd_programa
     .parameters.Append         l_cd_acao
     .parameters.Append         l_cd_subacao
     .parameters.Append         l_cd_regiao
     .parameters.Append         l_cron_ini_mes_1
     .parameters.Append         l_cron_ini_mes_2
     .parameters.Append         l_cron_ini_mes_3
     .parameters.Append         l_cron_ini_mes_4
     .parameters.Append         l_cron_ini_mes_5
     .parameters.Append         l_cron_ini_mes_6
     .parameters.Append         l_cron_ini_mes_7
     .parameters.Append         l_cron_ini_mes_8
     .parameters.Append         l_cron_ini_mes_9
     .parameters.Append         l_cron_ini_mes_10
     .parameters.Append         l_cron_ini_mes_11
     .parameters.Append         l_cron_ini_mes_12
     .parameters.Append         l_cron_mes_1
     .parameters.Append         l_cron_mes_2
     .parameters.Append         l_cron_mes_3
     .parameters.Append         l_cron_mes_4
     .parameters.Append         l_cron_mes_5
     .parameters.Append         l_cron_mes_6
     .parameters.Append         l_cron_mes_7
     .parameters.Append         l_cron_mes_8
     .parameters.Append         l_cron_mes_9
     .parameters.Append         l_cron_mes_10
     .parameters.Append         l_cron_mes_11
     .parameters.Append         l_cron_mes_12
     .parameters.Append         l_real_mes_1
     .parameters.Append         l_real_mes_2
     .parameters.Append         l_real_mes_3
     .parameters.Append         l_real_mes_4
     .parameters.Append         l_real_mes_5
     .parameters.Append         l_real_mes_6
     .parameters.Append         l_real_mes_7
     .parameters.Append         l_real_mes_8
     .parameters.Append         l_real_mes_9
     .parameters.Append         l_real_mes_10
     .parameters.Append         l_real_mes_11
     .parameters.Append         l_real_mes_12
     .parameters.Append         l_previsao_ano
     .parameters.Append         l_cron_ini_ano
     .parameters.Append         l_atual_ano
     .parameters.Append         l_cron_ano
     .parameters.Append         l_real_ano                         
     .parameters.Append         l_comentario_execucao
  
     .CommandText               = Session("schema_is") & "SP_PutXMLDadoFisico_SIG"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_cd_programa"
     .parameters.Delete         "l_cd_acao"
     .parameters.Delete         "l_cd_subacao"
     .parameters.Delete         "l_cd_regiao"
     .parameters.Delete         "l_cron_ini_mes_1"
     .parameters.Delete         "l_cron_ini_mes_2"
     .parameters.Delete         "l_cron_ini_mes_3"
     .parameters.Delete         "l_cron_ini_mes_4"
     .parameters.Delete         "l_cron_ini_mes_5"
     .parameters.Delete         "l_cron_ini_mes_6"                         
     .parameters.Delete         "l_cron_ini_mes_7"
     .parameters.Delete         "l_cron_ini_mes_8"
     .parameters.Delete         "l_cron_ini_mes_9"
     .parameters.Delete         "l_cron_ini_mes_10"
     .parameters.Delete         "l_cron_ini_mes_11"
     .parameters.Delete         "l_cron_ini_mes_12"
     .parameters.Delete         "l_cron_mes_1"
     .parameters.Delete         "l_cron_mes_2"
     .parameters.Delete         "l_cron_mes_3"
     .parameters.Delete         "l_cron_mes_4"
     .parameters.Delete         "l_cron_mes_5"
     .parameters.Delete         "l_cron_mes_6"                         
     .parameters.Delete         "l_cron_mes_7"
     .parameters.Delete         "l_cron_mes_8"
     .parameters.Delete         "l_cron_mes_9"
     .parameters.Delete         "l_cron_mes_10"
     .parameters.Delete         "l_cron_mes_11"
     .parameters.Delete         "l_cron_mes_12"
     .parameters.Delete         "l_real_mes_1"
     .parameters.Delete         "l_real_mes_2"
     .parameters.Delete         "l_real_mes_3"
     .parameters.Delete         "l_real_mes_4"
     .parameters.Delete         "l_real_mes_5"
     .parameters.Delete         "l_real_mes_6"                         
     .parameters.Delete         "l_real_mes_7"
     .parameters.Delete         "l_real_mes_8"
     .parameters.Delete         "l_real_mes_9"
     .parameters.Delete         "l_real_mes_10"
     .parameters.Delete         "l_real_mes_11"
     .parameters.Delete         "l_real_mes_12"
     .parameters.Delete         "l_previsao_ano"
     .parameters.Delete         "l_cron_ini_ano"
     .parameters.Delete         "l_atual_ano"
     .parameters.Delete         "l_cron_ano"
     .parameters.Delete         "l_real_ano"
     .parameters.Delete         "l_comentario_execucao"
  end with
End Sub

REM =========================================================================
REM Mantйm a tabela SIGPLAM - Dado Financeiro
REM -------------------------------------------------------------------------
Sub DML_PutXMLDadoFinanceiro_SIG(p_resultado, p_cliente, p_ano, p_cd_programa, p_cd_acao, p_cd_subacao, p_cd_fonte, p_cd_regiao, _
                             p_cron_ini_mes_1, p_cron_ini_mes_2, p_cron_ini_mes_3, p_cron_ini_mes_4, p_cron_ini_mes_5, p_cron_ini_mes_6, _
                             p_cron_ini_mes_7, p_cron_ini_mes_8, p_cron_ini_mes_9, p_cron_ini_mes_10, p_cron_ini_mes_11, p_cron_ini_mes_12, _
                             p_cron_mes_1, p_cron_mes_2, p_cron_mes_3, p_cron_mes_4, p_cron_mes_5, p_cron_mes_6, _
                             p_cron_mes_7, p_cron_mes_8, p_cron_mes_9, p_cron_mes_10, p_cron_mes_11, p_cron_mes_12, _
                             p_real_mes_1, p_real_mes_2, p_real_mes_3, p_real_mes_4, p_real_mes_5, p_real_mes_6, _
                             p_real_mes_7, p_real_mes_8, p_real_mes_9, p_real_mes_10, p_real_mes_11, p_real_mes_12, _
                             p_previsao_ano, p_cron_ini_ano, p_atual_ano, p_cron_ano, p_real_ano, p_comentario_execucao)


  Dim l_cliente, l_ano, l_cd_programa, l_cd_acao, l_cd_subacao, l_cd_fonte, l_cd_regiao
  Dim l_cron_ini_mes_1, l_cron_ini_mes_2, l_cron_ini_mes_3, l_cron_ini_mes_4, l_cron_ini_mes_5, l_cron_ini_mes_6
  Dim l_cron_ini_mes_7, l_cron_ini_mes_8, l_cron_ini_mes_9, l_cron_ini_mes_10, l_cron_ini_mes_11, l_cron_ini_mes_12
  Dim l_cron_mes_1, l_cron_mes_2, l_cron_mes_3, l_cron_mes_4, l_cron_mes_5, l_cron_mes_6
  Dim l_cron_mes_7, l_cron_mes_8, l_cron_mes_9, l_cron_mes_10, l_cron_mes_11, l_cron_mes_12
  Dim l_real_mes_1, l_real_mes_2, l_real_mes_3, l_real_mes_4, l_real_mes_5, l_real_mes_6
  Dim l_real_mes_7, l_real_mes_8, l_real_mes_9, l_real_mes_10, l_real_mes_11, l_real_mes_12
  Dim l_previsao_ano, l_cron_ini_ano, l_atual_ano, l_cron_ano, l_real_ano, l_comentario_execucao
  
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano                     = Server.CreateObject("ADODB.Parameter")
  Set l_cd_programa             = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_acao                 = Server.CreateObject("ADODB.Parameter")
  Set l_cd_subacao              = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_fonte                = Server.CreateObject("ADODB.Parameter")    
  Set l_cd_regiao               = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_1          = Server.CreateObject("ADODB.Parameter")
  Set l_cron_ini_mes_2          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_3          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_4          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_5          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_6          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_7          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_8          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_9          = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_10         = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_11         = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_ini_mes_12         = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_1              = Server.CreateObject("ADODB.Parameter")
  Set l_cron_mes_2              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_3              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_4              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_5              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_6              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_7              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_8              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_9              = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_10             = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_11             = Server.CreateObject("ADODB.Parameter") 
  Set l_cron_mes_12             = Server.CreateObject("ADODB.Parameter")  
  Set l_real_mes_1              = Server.CreateObject("ADODB.Parameter")
  Set l_real_mes_2              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_3              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_4              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_5              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_6              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_7              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_8              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_9              = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_10             = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_11             = Server.CreateObject("ADODB.Parameter") 
  Set l_real_mes_12             = Server.CreateObject("ADODB.Parameter")  
  Set l_previsao_ano            = Server.CreateObject("ADODB.Parameter")
  Set l_cron_ini_ano            = Server.CreateObject("ADODB.Parameter")  
  Set l_atual_ano               = Server.CreateObject("ADODB.Parameter")  
  Set l_cron_ano                = Server.CreateObject("ADODB.Parameter")    
  Set l_real_ano                = Server.CreateObject("ADODB.Parameter") 
  Set l_comentario_execucao     = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,     , Tvl(p_cliente))
     set l_ano                  = .CreateParameter("l_ano",                 adInteger, adParamInput,     , Tvl(p_ano))
     set l_cd_programa          = .CreateParameter("l_cd_programa",         adVarchar, adParamInput,    4, Tvl(p_cd_programa))
     set l_cd_acao              = .CreateParameter("l_cd_acao",             adVarchar, adParamInput,    4, Tvl(p_cd_acao))
     set l_cd_subacao           = .CreateParameter("l_cd_subacao",          adVarchar, adParamInput,    4, Tvl(p_cd_subacao))
     set l_cd_fonte              = .CreateParameter("l_cd_fonte",           adVarchar, adParamInput,    5, Tvl(p_cd_fonte))
     set l_cd_regiao            = .CreateParameter("l_cd_regiao",           adVarchar, adParamInput,    2, Tvl(p_cd_regiao))
     set l_cron_ini_mes_1       = .CreateParameter("l_cron_ini_mes_1",      adNumeric)
     l_cron_ini_mes_1.Precision      = 18
     l_cron_ini_mes_1.NumericScale   = 2
     l_cron_ini_mes_1.Value          = Tvl(Replace(p_cron_ini_mes_1,".",","))
     set l_cron_ini_mes_2       = .CreateParameter("l_cron_ini_mes_2",      adNumeric)
     l_cron_ini_mes_2.Precision      = 18
     l_cron_ini_mes_2.NumericScale   = 2
     l_cron_ini_mes_2.Value          = Tvl(Replace(p_cron_ini_mes_2,".",","))
     set l_cron_ini_mes_3       = .CreateParameter("l_cron_ini_mes_3",      adNumeric)
     l_cron_ini_mes_3.Precision      = 18
     l_cron_ini_mes_3.NumericScale   = 2
     l_cron_ini_mes_3.Value          = Tvl(Replace(p_cron_ini_mes_3,".",","))
     set l_cron_ini_mes_4       = .CreateParameter("l_cron_ini_mes_4",      adNumeric)
     l_cron_ini_mes_4.Precision      = 18
     l_cron_ini_mes_4.NumericScale   = 2
     l_cron_ini_mes_4.Value          = Tvl(Replace(p_cron_ini_mes_4,".",","))
     set l_cron_ini_mes_5       = .CreateParameter("l_cron_ini_mes_5",      adNumeric)
     l_cron_ini_mes_5.Precision      = 18
     l_cron_ini_mes_5.NumericScale   = 2
     l_cron_ini_mes_5.Value          = Tvl(Replace(p_cron_ini_mes_5,".",","))
     set l_cron_ini_mes_6       = .CreateParameter("l_cron_ini_mes_6",      adNumeric)
     l_cron_ini_mes_6.Precision      = 18
     l_cron_ini_mes_6.NumericScale   = 2
     l_cron_ini_mes_6.Value          = Tvl(Replace(p_cron_ini_mes_6,".",","))
     set l_cron_ini_mes_7       = .CreateParameter("l_cron_ini_mes_7",      adNumeric)
     l_cron_ini_mes_7.Precision      = 18
     l_cron_ini_mes_7.NumericScale   = 2
     l_cron_ini_mes_7.Value          = Tvl(Replace(p_cron_ini_mes_7,".",","))
     set l_cron_ini_mes_8       = .CreateParameter("l_cron_ini_mes_8",      adNumeric)
     l_cron_ini_mes_8.Precision      = 18
     l_cron_ini_mes_8.NumericScale   = 2
     l_cron_ini_mes_8.Value          = Tvl(Replace(p_cron_ini_mes_8,".",","))
     set l_cron_ini_mes_9       = .CreateParameter("l_cron_ini_mes_9",      adNumeric)
     l_cron_ini_mes_9.Precision      = 18
     l_cron_ini_mes_9.NumericScale   = 2
     l_cron_ini_mes_9.Value          = Tvl(Replace(p_cron_ini_mes_9,".",","))
     set l_cron_ini_mes_10      = .CreateParameter("l_cron_ini_mes_10",     adNumeric)
     l_cron_ini_mes_10.Precision     = 18
     l_cron_ini_mes_10.NumericScale  = 2
     l_cron_ini_mes_10.Value         = Tvl(Replace(p_cron_ini_mes_10,".",","))
     set l_cron_ini_mes_11      = .CreateParameter("l_cron_ini_mes_11",     adNumeric)
     l_cron_ini_mes_11.Precision     = 18
     l_cron_ini_mes_11.NumericScale  = 2
     l_cron_ini_mes_11.Value         = Tvl(Replace(p_cron_ini_mes_11,".",","))
     set l_cron_ini_mes_12      = .CreateParameter("l_cron_ini_mes_12",     adNumeric)
     l_cron_ini_mes_12.Precision     = 18
     l_cron_ini_mes_12.NumericScale  = 2
     l_cron_ini_mes_12.Value         = Tvl(Replace(p_cron_ini_mes_12,".",","))
     set l_cron_mes_1           = .CreateParameter("l_cron_mes_1",          adNumeric)
     l_cron_mes_1.Precision         = 18
     l_cron_mes_1.NumericScale      = 2
     l_cron_mes_1.Value             = Tvl(Replace(p_cron_mes_1,".",","))
     set l_cron_mes_2           = .CreateParameter("l_cron_mes_2",          adNumeric)
     l_cron_mes_2.Precision         = 18
     l_cron_mes_2.NumericScale      = 2
     l_cron_mes_2.Value             = Tvl(Replace(p_cron_mes_2,".",","))
     set l_cron_mes_3           = .CreateParameter("l_cron_mes_3",          adNumeric)
     l_cron_mes_3.Precision         = 18
     l_cron_mes_3.NumericScale      = 2
     l_cron_mes_3.Value             = Tvl(Replace(p_cron_mes_3,".",","))
     set l_cron_mes_4           = .CreateParameter("l_cron_mes_4",          adNumeric)
     l_cron_mes_4.Precision         = 18
     l_cron_mes_4.NumericScale      = 4
     l_cron_mes_4.Value             = Tvl(Replace(p_cron_mes_4,".",","))
     set l_cron_mes_5           = .CreateParameter("l_cron_mes_5",          adNumeric)
     l_cron_mes_5.Precision         = 18
     l_cron_mes_5.NumericScale      = 2
     l_cron_mes_5.Value             = Tvl(Replace(p_cron_mes_5,".",","))
     set l_cron_mes_6           = .CreateParameter("l_cron_mes_6",          adNumeric)
     l_cron_mes_6.Precision         = 18
     l_cron_mes_6.NumericScale      = 2
     l_cron_mes_6.Value             = Tvl(Replace(p_cron_mes_6,".",","))
     set l_cron_mes_7           = .CreateParameter("l_cron_mes_7",          adNumeric)
     l_cron_mes_7.Precision         = 18
     l_cron_mes_7.NumericScale      = 2
     l_cron_mes_7.Value             = Tvl(Replace(p_cron_mes_7,".",","))
     set l_cron_mes_8           = .CreateParameter("l_cron_mes_8",          adNumeric)
     l_cron_mes_8.Precision         = 18
     l_cron_mes_8.NumericScale      = 2
     l_cron_mes_8.Value             = Tvl(Replace(p_cron_mes_8,".",","))
     set l_cron_mes_9           = .CreateParameter("l_cron_mes_9",          adNumeric)
     l_cron_mes_9.Precision         = 18
     l_cron_mes_9.NumericScale      = 2
     l_cron_mes_9.Value             = Tvl(Replace(p_cron_mes_9,".",","))
     set l_cron_mes_10          = .CreateParameter("l_cron_mes_10",         adNumeric)
     l_cron_mes_10.Precision        = 18
     l_cron_mes_10.NumericScale     = 2
     l_cron_mes_10.Value            = Tvl(Replace(p_cron_mes_10,".",","))
     set l_cron_mes_11          = .CreateParameter("l_cron_mes_11",         adNumeric)
     l_cron_mes_11.Precision        = 18
     l_cron_mes_11.NumericScale     = 2
     l_cron_mes_11.Value            = Tvl(Replace(p_cron_mes_11,".",","))
     set l_cron_mes_12          = .CreateParameter("l_cron_mes_12",         adNumeric)
     l_cron_mes_12.Precision        = 18
     l_cron_mes_12.NumericScale     = 2
     l_cron_mes_12.Value            = Tvl(Replace(p_cron_mes_12,".",","))
     set l_real_mes_1           = .CreateParameter("l_real_mes_1",          adNumeric)
     l_real_mes_1.Precision         = 18
     l_real_mes_1.NumericScale      = 2
     l_real_mes_1.Value             = Tvl(Replace(p_real_mes_1,".",","))
     set l_real_mes_2           = .CreateParameter("l_real_mes_2",          adNumeric)
     l_real_mes_2.Precision         = 18
     l_real_mes_2.NumericScale      = 2
     l_real_mes_2.Value             = Tvl(Replace(p_real_mes_2,".",","))
     set l_real_mes_3           = .CreateParameter("l_real_mes_3",          adNumeric)
     l_real_mes_3.Precision         = 18
     l_real_mes_3.NumericScale      = 2
     l_real_mes_3.Value             = Tvl(Replace(p_real_mes_3,".",","))
     set l_real_mes_4           = .CreateParameter("l_real_mes_4",          adNumeric)
     l_real_mes_4.Precision         = 18
     l_real_mes_4.NumericScale      = 2
     l_real_mes_4.Value             = Tvl(Replace(p_real_mes_4,".",","))
     set l_real_mes_5           = .CreateParameter("l_real_mes_5",          adNumeric)
     l_real_mes_5.Precision         = 18
     l_real_mes_5.NumericScale      = 2
     l_real_mes_5.Value             = Tvl(Replace(p_real_mes_5,".",","))
     set l_real_mes_6           = .CreateParameter("l_real_mes_6",          adNumeric)
     l_real_mes_6.Precision         = 18
     l_real_mes_6.NumericScale      = 2
     l_real_mes_6.Value             = Tvl(Replace(p_real_mes_6,".",","))
     set l_real_mes_7           = .CreateParameter("l_real_mes_7",          adNumeric)
     l_real_mes_7.Precision         = 18
     l_real_mes_7.NumericScale      = 2
     l_real_mes_7.Value             = Tvl(Replace(p_real_mes_7,".",","))
     set l_real_mes_8           = .CreateParameter("l_real_mes_8",          adNumeric)
     l_real_mes_8.Precision         = 18
     l_real_mes_8.NumericScale      = 2
     l_real_mes_8.Value             = Tvl(Replace(p_real_mes_8,".",","))
     set l_real_mes_9           = .CreateParameter("l_real_mes_9",          adNumeric)
     l_real_mes_9.Precision         = 18
     l_real_mes_9.NumericScale      = 2
     l_real_mes_9.Value             = Tvl(Replace(p_real_mes_9,".",","))
     set l_real_mes_10          = .CreateParameter("l_real_mes_10",         adNumeric)
     l_real_mes_10.Precision        = 18
     l_real_mes_10.NumericScale     = 2
     l_real_mes_10.Value            = Tvl(Replace(p_real_mes_10,".",","))
     set l_real_mes_11          = .CreateParameter("l_real_mes_11",         adNumeric)
     l_real_mes_11.Precision        = 18
     l_real_mes_11.NumericScale     = 2
     l_real_mes_11.Value            = Tvl(Replace(p_real_mes_11,".",","))
     set l_real_mes_12          = .CreateParameter("l_real_mes_12",         adNumeric)
     l_real_mes_12.Precision        = 18
     l_real_mes_12.NumericScale     = 2
     l_real_mes_12.Value            = Tvl(Replace(p_real_mes_12,".",","))
     set l_previsao_ano         = .CreateParameter("l_previsao_ano",        adNumeric)
     l_previsao_ano.Precision       = 18
     l_previsao_ano.NumericScale    = 2
     l_previsao_ano.Value           = Tvl(Replace(p_previsao_ano,".",","))
     set l_cron_ini_ano         = .CreateParameter("l_cron_ini_ano",        adNumeric)
     l_cron_ini_ano.Precision       = 18
     l_cron_ini_ano.NumericScale    = 2
     l_cron_ini_ano.Value           = Tvl(Replace(p_cron_ini_ano,".",","))
     set l_atual_ano            = .CreateParameter("l_atual_ano",        adNumeric)
     l_atual_ano.Precision          = 18
     l_atual_ano.NumericScale       = 2
     l_atual_ano.Value              = Tvl(Replace(p_atual_ano,".",","))
     set l_cron_ano             = .CreateParameter("l_cron_ano",        adNumeric)
     l_cron_ano.Precision           = 18
     l_cron_ano.NumericScale        = 2
     l_cron_ano.Value               = Tvl(Replace(p_cron_ano,".",","))
     set l_real_ano             = .CreateParameter("l_real_ano",        adNumeric)
     l_real_ano.Precision           = 18
     l_real_ano.NumericScale        = 2
     l_real_ano.Value               = Tvl(Replace(p_real_ano,".",","))
     set l_comentario_execucao  = .CreateParameter("l_comentario_execucao", adVarchar, adParamInput, 4000, Tvl(p_comentario_execucao))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_cd_programa
     .parameters.Append         l_cd_acao
     .parameters.Append         l_cd_subacao
     .parameters.Append         l_cd_fonte
     .parameters.Append         l_cd_regiao
     .parameters.Append         l_cron_ini_mes_1
     .parameters.Append         l_cron_ini_mes_2
     .parameters.Append         l_cron_ini_mes_3
     .parameters.Append         l_cron_ini_mes_4
     .parameters.Append         l_cron_ini_mes_5
     .parameters.Append         l_cron_ini_mes_6
     .parameters.Append         l_cron_ini_mes_7
     .parameters.Append         l_cron_ini_mes_8
     .parameters.Append         l_cron_ini_mes_9
     .parameters.Append         l_cron_ini_mes_10
     .parameters.Append         l_cron_ini_mes_11
     .parameters.Append         l_cron_ini_mes_12
     .parameters.Append         l_cron_mes_1
     .parameters.Append         l_cron_mes_2
     .parameters.Append         l_cron_mes_3
     .parameters.Append         l_cron_mes_4
     .parameters.Append         l_cron_mes_5
     .parameters.Append         l_cron_mes_6
     .parameters.Append         l_cron_mes_7
     .parameters.Append         l_cron_mes_8
     .parameters.Append         l_cron_mes_9
     .parameters.Append         l_cron_mes_10
     .parameters.Append         l_cron_mes_11
     .parameters.Append         l_cron_mes_12
     .parameters.Append         l_real_mes_1
     .parameters.Append         l_real_mes_2
     .parameters.Append         l_real_mes_3
     .parameters.Append         l_real_mes_4
     .parameters.Append         l_real_mes_5
     .parameters.Append         l_real_mes_6
     .parameters.Append         l_real_mes_7
     .parameters.Append         l_real_mes_8
     .parameters.Append         l_real_mes_9
     .parameters.Append         l_real_mes_10
     .parameters.Append         l_real_mes_11
     .parameters.Append         l_real_mes_12
     .parameters.Append         l_previsao_ano
     .parameters.Append         l_cron_ini_ano
     .parameters.Append         l_atual_ano
     .parameters.Append         l_cron_ano
     .parameters.Append         l_real_ano                         
     .parameters.Append         l_comentario_execucao
  
     .CommandText               = Session("schema_is") & "SP_PutXMLDadoFinanceiro_SIG"
     'On error Resume Next
     .Execute
     If Err.Description > "" Then 
        p_resultado = Err.Description
     End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_cd_programa"
     .parameters.Delete         "l_cd_acao"
     .parameters.Delete         "l_cd_subacao"
     .parameters.Delete         "l_cd_fonte"
     .parameters.Delete         "l_cd_regiao"
     .parameters.Delete         "l_cron_ini_mes_1"
     .parameters.Delete         "l_cron_ini_mes_2"
     .parameters.Delete         "l_cron_ini_mes_3"
     .parameters.Delete         "l_cron_ini_mes_4"
     .parameters.Delete         "l_cron_ini_mes_5"
     .parameters.Delete         "l_cron_ini_mes_6"                         
     .parameters.Delete         "l_cron_ini_mes_7"
     .parameters.Delete         "l_cron_ini_mes_8"
     .parameters.Delete         "l_cron_ini_mes_9"
     .parameters.Delete         "l_cron_ini_mes_10"
     .parameters.Delete         "l_cron_ini_mes_11"
     .parameters.Delete         "l_cron_ini_mes_12"
     .parameters.Delete         "l_cron_mes_1"
     .parameters.Delete         "l_cron_mes_2"
     .parameters.Delete         "l_cron_mes_3"
     .parameters.Delete         "l_cron_mes_4"
     .parameters.Delete         "l_cron_mes_5"
     .parameters.Delete         "l_cron_mes_6"                         
     .parameters.Delete         "l_cron_mes_7"
     .parameters.Delete         "l_cron_mes_8"
     .parameters.Delete         "l_cron_mes_9"
     .parameters.Delete         "l_cron_mes_10"
     .parameters.Delete         "l_cron_mes_11"
     .parameters.Delete         "l_cron_mes_12"
     .parameters.Delete         "l_real_mes_1"
     .parameters.Delete         "l_real_mes_2"
     .parameters.Delete         "l_real_mes_3"
     .parameters.Delete         "l_real_mes_4"
     .parameters.Delete         "l_real_mes_5"
     .parameters.Delete         "l_real_mes_6"                         
     .parameters.Delete         "l_real_mes_7"
     .parameters.Delete         "l_real_mes_8"
     .parameters.Delete         "l_real_mes_9"
     .parameters.Delete         "l_real_mes_10"
     .parameters.Delete         "l_real_mes_11"
     .parameters.Delete         "l_real_mes_12"
     .parameters.Delete         "l_previsao_ano"
     .parameters.Delete         "l_cron_ini_ano"
     .parameters.Delete         "l_atual_ano"
     .parameters.Delete         "l_cron_ano"
     .parameters.Delete         "l_real_ano"
     .parameters.Delete         "l_comentario_execucao"
  end with
End Sub
%>