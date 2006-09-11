<%
REM =========================================================================
REM Recupera ações do ppa
REM -------------------------------------------------------------------------
Sub DB_GetAcaoPPA(p_rs, p_chave, p_cliente, p_programa, p_acao, p_responsavel, _
        p_mpog, p_relevante, p_sq_siw_solicitacao, p_cod_programa, p_cod_acao, p_restricao)
  Dim l_chave, l_cliente, l_programa, l_acao, l_responsavel, l_mpog, l_relevante
  Dim l_sq_siw_solicitacao, l_cod_programa, l_cod_acao, l_restricao
  Set l_chave              = Server.CreateObject("ADODB.Parameter")
  Set l_cliente            = Server.CreateObject("ADODB.Parameter")
  Set l_programa           = Server.CreateObject("ADODB.Parameter")
  Set l_acao               = Server.CreateObject("ADODB.Parameter")
  Set l_responsavel        = Server.CreateObject("ADODB.Parameter")
  Set l_mpog               = Server.CreateObject("ADODB.Parameter")
  Set l_relevante          = Server.CreateObject("ADODB.Parameter")
  Set l_sq_siw_solicitacao = Server.CreateObject("ADODB.Parameter")
  Set l_cod_programa       = Server.CreateObject("ADODB.Parameter")
  Set l_cod_acao           = Server.CreateObject("ADODB.Parameter")
  Set l_restricao          = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave              = .CreateParameter("l_chave",              adInteger, adParamInput,    , tvl(p_chave))
     set l_cliente            = .CreateParameter("l_cliente",            adInteger, adParamInput,    , p_cliente)
     set l_programa           = .CreateParameter("l_programa",           adInteger, adParamInput,    , Tvl(p_programa))
     set l_acao               = .CreateParameter("l_acao",               adInteger, adParamInput,    , Tvl(p_acao))
     set l_responsavel        = .CreateParameter("l_responsavel",        adVarchar, adParamInput,  60, Tvl(p_responsavel))
     set l_mpog               = .CreateParameter("l_mpog",               adVarchar, adParamInput,   1, Tvl(p_mpog))
     set l_relevante          = .CreateParameter("l_relevante",          adVarchar, adParamInput,   1, Tvl(p_relevante))
     set l_sq_siw_solicitacao = .CreateParameter("l_sq_siw_solicitacao", adInteger, adParamInput,    , tvl(p_sq_siw_solicitacao))
     set l_cod_programa       = .CreateParameter("l_cod_programa",       adVarchar, adParamInput,  50, Tvl(p_cod_programa))
     set l_cod_acao           = .CreateParameter("l_cod_acao",           adVarchar, adParamInput,  50, Tvl(p_cod_acao))
     set l_restricao          = .CreateParameter("l_restricao",          adVarchar, adParamInput,  60, Tvl(p_restricao))
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_programa
     .parameters.Append         l_acao
     .parameters.Append         l_responsavel
     .parameters.Append         l_mpog
     .parameters.Append         l_relevante
     .parameters.Append         l_sq_siw_solicitacao
     .parameters.Append         l_cod_programa
     .parameters.Append         l_cod_acao
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetAcaoPPA"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_programa"
     .Parameters.Delete         "l_acao"
     .Parameters.Delete         "l_responsavel"
     .Parameters.Delete         "l_mpog"
     .Parameters.Delete         "l_relevante"
     .Parameters.Delete         "l_sq_siw_solicitacao"
     .Parameters.Delete         "l_cod_programa"
     .Parameters.Delete         "l_cod_acao"
     .Parameters.Delete         "l_restricao"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera ações do ppa
REM -------------------------------------------------------------------------
Sub DB_GetFinancAcaoPPA(p_rs, p_chave, p_cliente, p_sq_acao_ppa)
  Dim l_chave, l_cliente, l_sq_acao_ppa
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_sq_acao_ppa = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,    , p_chave)
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,    , p_cliente)
     set l_sq_acao_ppa     = .CreateParameter("l_sq_acao_ppa",  adInteger, adParamInput,    , tvl(p_sq_acao_ppa))
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_acao_ppa
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetFinacAcaoPPA"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_sq_acao_ppa"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera iniciativas prioritárias
REM -------------------------------------------------------------------------
Sub DB_GetOrPrioridade(p_rs, p_chave, p_cliente, p_sq_orprioridade, p_responsavel, p_mpog, p_relevante)
  Dim l_chave, l_cliente, l_sq_orprioridade, l_responsavel, l_mpog, l_relevante
  Set l_chave           = Server.CreateObject("ADODB.Parameter")
  Set l_cliente         = Server.CreateObject("ADODB.Parameter")
  Set l_sq_orprioridade = Server.CreateObject("ADODB.Parameter")
  Set l_responsavel     = Server.CreateObject("ADODB.Parameter")
  Set l_mpog            = Server.CreateObject("ADODB.Parameter")
  Set l_relevante       = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",          adInteger, adParamInput,   , tvl(p_chave))
     set l_cliente         = .CreateParameter("l_cliente",        adInteger, adParamInput,   , p_cliente)
     set l_sq_orprioridade = .CreateParameter("l_sq_orprioridade",adInteger, adParamInput,   , tvl(p_sq_orprioridade))
     set l_responsavel     = .CreateParameter("l_responsavel",    adVarchar, adParamInput, 60, tvl(p_responsavel))
     set l_mpog            = .CreateParameter("l_mpog",           adVarchar, adParamInput,  1, tvl(p_mpog))
     set l_relevante       = .CreateParameter("l_relevante",      adVarchar, adParamInput,  1, tvl(p_relevante))
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_orprioridade
     .parameters.Append         l_responsavel
     .parameters.Append         l_mpog
     .parameters.Append         l_relevante
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetOrPrioridade"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_sq_orprioridade"
     .Parameters.Delete         "l_responsavel"
     .Parameters.Delete         "l_mpog"
     .Parameters.Delete         "l_relevante"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera iniciativas prioritárias
REM -------------------------------------------------------------------------
Sub DB_Get10PercentDays(p_rs, p_inicio, p_fim)
  Dim l_inicio, l_fim
  Set l_inicio      = Server.CreateObject("ADODB.Parameter")
  Set l_fim         = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_inicio          = .CreateParameter("l_inicio",       adDate, adParamInput,   , p_inicio)
     set l_fim             = .CreateParameter("l_fim",          adDate, adParamInput,   , p_fim)
     .parameters.Append         l_inicio
     .parameters.Append         l_fim
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_Get10PercentDays"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_inicio"
     .Parameters.Delete         "l_fim"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
REM =========================================================================
REM Recupera iniciativas prioritárias
REM -------------------------------------------------------------------------
Sub DB_GetOrPrioridadeList(p_rs, p_chave, p_cliente, p_sq_orprioridade)
  Dim l_chave, l_cliente, l_sq_orprioridade
  Set l_chave            = Server.CreateObject("ADODB.Parameter")
  Set l_cliente          = Server.CreateObject("ADODB.Parameter")
  Set l_sq_orprioridade  = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",           adInteger, adParamInput,   , tvl(p_chave))
     set l_cliente         = .CreateParameter("l_cliente",         adInteger, adParamInput,   , p_cliente)
     set l_sq_orprioridade = .CreateParameter("l_sq_orprioridade", adInteger, adParamInput,   , tvl(p_sq_orprioridade))
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_orprioridade
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetOrPrioridadeList"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_sq_orprioridade"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

