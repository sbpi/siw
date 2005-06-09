<%
REM =========================================================================
REM Recupera critérios de julgamento das licitações
REM -------------------------------------------------------------------------
Sub DB_GetLcCriterio(p_rs, p_chave, p_cliente)
  Dim l_chave, l_cliente
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,   , p_cliente)
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLcCriterio"
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
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera modalidades de licitação
REM -------------------------------------------------------------------------
Sub DB_GetLcModalidade(p_rs, p_chave, p_cliente)
  Dim l_chave, l_cliente
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,   , p_cliente)
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLcModalidade"
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
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista de situações de uma licitação
REM -------------------------------------------------------------------------
Sub DB_GetLcSituacao(p_rs, p_chave, p_cliente)
  Dim l_chave, l_cliente
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,   , p_cliente)
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLcSituacao"
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
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as fontes de recurso das licitações
REM -------------------------------------------------------------------------
Sub DB_GetLcFonte(p_rs, p_chave, p_cliente)
  Dim l_chave, l_cliente
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,   , p_cliente)
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLcFonte"
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
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as finalidades das licitações
REM -------------------------------------------------------------------------
Sub DB_GetLcFinalidade(p_rs, p_chave, p_cliente)
  Dim l_chave, l_cliente
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,   , p_cliente)
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLcFinalidade"
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
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as finalidades das licitações
REM -------------------------------------------------------------------------
Sub DB_GetLcUnidade(p_rs, p_chave, p_cliente)
  Dim l_chave, l_cliente
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,   , p_cliente)
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLcUnidade"
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
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as unidades de fornecimento
REM -------------------------------------------------------------------------
Sub DB_GetLcUnidadeFornec(p_rs, p_chave, p_cliente)
  Dim l_chave, l_cliente
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,   , p_cliente)
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLcUnidadeFornec"
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
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

