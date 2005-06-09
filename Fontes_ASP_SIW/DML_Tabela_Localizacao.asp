<%
REM =========================================================================
REM Manipula registros de CO_Pais
REM -------------------------------------------------------------------------
Sub DML_COPAIS(Operacao, Chave, nome, ativo, padrao, ddi, sigla)
  Dim l_Operacao, l_Chave, l_nome, l_ativo,l_padrao, l_ddi, l_sigla
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  Set l_ativo           = Server.CreateObject("ADODB.Parameter")
  Set l_padrao          = Server.CreateObject("ADODB.Parameter")
  Set l_ddi             = Server.CreateObject("ADODB.Parameter")
  Set l_sigla           = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,    , Tvl(chave))
     set l_nome                 = .CreateParameter("l_nome",        adVarChar, adParamInput,  60, nome)
     set l_ativo                = .CreateParameter("l_ativo",       adVarChar, adParamInput,   1, ativo)
     set l_padrao               = .CreateParameter("l_padrao",      adVarchar, adParamInput,   1, padrao)
     set l_ddi                  = .CreateParameter("l_ddi",         adVarchar, adParamInput,   10, ddi)
     set l_sigla                = .CreateParameter("l_sigla",       adVarchar, adParamInput,   3, sigla)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
     .parameters.Append         l_padrao
     .parameters.Append         l_ddi
     .parameters.Append         l_sigla
     .CommandText               = Session("schema") & "SP_PutCOPais"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_ddi"
     .parameters.Delete         "l_sigla"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de CO_Regiao
REM -------------------------------------------------------------------------
Sub DML_COREGIAO(Operacao, Chave, sq_pais, nome, sigla, ordem)
  Dim l_Operacao, l_Chave, l_sq_pais, l_nome, l_sigla, l_ordem
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pais         = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  Set l_sigla           = Server.CreateObject("ADODB.Parameter")
  Set l_ordem           = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,    , Tvl(chave))
     set l_sq_pais              = .CreateParameter("l_sq_pais",     adInteger, adParamInput,    , Tvl(sq_pais))
     set l_nome                 = .CreateParameter("l_nome",        adVarChar, adParamInput,  20, nome)
     set l_sigla                = .CreateParameter("l_sigla",       adVarchar, adParamInput,   2, sigla)
     set l_ordem                = .CreateParameter("l_ordem",       adInteger, adParamInput,   4, ordem)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_pais
     .parameters.Append         l_nome
     .parameters.Append         l_sigla
     .parameters.Append         l_ordem
     .CommandText               = Session("schema") & "SP_PutCORegiao"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_pais"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_ordem"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de CO_UF
REM -------------------------------------------------------------------------
Sub DML_COUF(Operacao, co_uf, sq_pais, sq_regiao, nome, ativo, padrao, codigo_ibge, ordem)
  Dim l_Operacao, l_co_uf, l_sq_pais, l_sq_regiao, l_nome, l_ativo, l_padrao, l_codigo_ibge, l_ordem
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_co_uf           = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pais         = Server.CreateObject("ADODB.Parameter")
  Set l_sq_regiao       = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  Set l_ativo           = Server.CreateObject("ADODB.Parameter")
  Set l_padrao          = Server.CreateObject("ADODB.Parameter")
  Set l_codigo_ibge     = Server.CreateObject("ADODB.Parameter")
  Set l_ordem           = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_co_uf                = .CreateParameter("l_co_uf",       adVarchar, adParamInput,   3, co_uf)
     set l_sq_pais              = .CreateParameter("l_sq_pais",     adInteger, adParamInput,    , sq_pais)
     set l_sq_regiao            = .CreateParameter("l_sq_regiao",   adInteger, adParamInput,    , Tvl(sq_regiao))
     set l_nome                 = .CreateParameter("l_nome",        adVarChar, adParamInput,  30, nome)
     set l_ativo                = .CreateParameter("l_ativo",       adVarchar, adParamInput,   1, ativo)
     set l_padrao               = .CreateParameter("l_padrao",      adVarchar, adParamInput,   1, padrao)
     set l_codigo_ibge          = .CreateParameter("l_codigo_ibge", adVarchar, adParamInput,   2, codigo_ibge)
     set l_ordem                = .CreateParameter("l_ordem",       adInteger, adParamInput,   5, ordem)
     .parameters.Append         l_Operacao
     .parameters.Append         l_co_uf
     .parameters.Append         l_sq_pais
     .parameters.Append         l_sq_regiao
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
     .parameters.Append         l_padrao
     .parameters.Append         l_codigo_ibge
     .parameters.Append         l_ordem
     .CommandText               = Session("schema") & "SP_PutCOUF"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_co_uf"
     .parameters.Delete         "l_sq_pais"
     .parameters.Delete         "l_sq_regiao"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_codigo_ibge"
     .parameters.Delete         "l_ordem"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de CO_CIDADE
REM -------------------------------------------------------------------------
Sub DML_COCIDADE(Operacao, sq_cidade, ddd, codigo_ibge, sq_pais, sq_regiao, co_uf, nome, capital)
  Dim l_Operacao, l_sq_cidade, l_ddd, l_codigo_ibge, l_sq_pais, l_sq_regiao, l_co_uf, l_nome, l_capital
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_sq_cidade       = Server.CreateObject("ADODB.Parameter")
  Set l_ddd             = Server.CreateObject("ADODB.Parameter")
  Set l_codigo_ibge     = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pais         = Server.CreateObject("ADODB.Parameter")
  Set l_sq_regiao       = Server.CreateObject("ADODB.Parameter")
  Set l_co_uf           = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  Set l_capital         = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",      adVarchar, adParamInput,   1, Operacao)
     set l_sq_cidade            = .CreateParameter("l_sq_cidade",     adInteger, adParamInput,    , Tvl(sq_cidade))
     set l_ddd                  = .CreateParameter("l_ddd",           adVarchar, adParamInput,   4, ddd)
     set l_codigo_ibge          = .CreateParameter("l_codigo_ibge",   adVarchar, adParamInput,  20, codigo_ibge)
     set l_sq_pais              = .CreateParameter("l_sq_pais",       adInteger, adParamInput,    , Tvl(sq_pais))
     set l_sq_regiao            = .CreateParameter("l_sq_regiao",     adInteger, adParamInput,    , Tvl( sq_regiao) )
     set l_co_uf                = .CreateParameter("l_co_uf",         adVarchar, adParamInput,   3, co_uf)
     set l_nome                 = .CreateParameter("l_nome",          adVarchar, adParamInput,   60, nome)
     set l_capital              = .CreateParameter("l_capital",       adVarchar, adParamInput,   1, capital)
     .parameters.Append         l_Operacao
     .parameters.Append         l_sq_cidade
     .parameters.Append         l_ddd
     .parameters.Append         l_codigo_ibge
     .parameters.Append         l_sq_pais
     .parameters.Append         l_sq_regiao
     .parameters.Append         l_co_uf
     .parameters.Append         l_nome
     .parameters.Append         l_capital
     .CommandText               = Session("schema") & "SP_PutCOCidade"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_sq_cidade"
     .parameters.Delete         "l_ddd"
     .parameters.Delete         "l_codigo_ibge"
     .parameters.Delete         "l_sq_pais"
     .parameters.Delete         "l_sq_regiao"
     .parameters.Delete         "l_co_uf"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_capital"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------


%>

