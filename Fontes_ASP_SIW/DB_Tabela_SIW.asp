<%
REM =========================================================================
REM Recupera o nome do segmento
REM -------------------------------------------------------------------------
Sub DB_GetSegName(p_rs, p_sq_segmento)
  Dim l_sq_segmento
  Set l_sq_segmento = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_segmento                = .CreateParameter("l_sq_segmento", adInteger, adParamInput, , p_sq_segmento)
     .parameters.Append         l_sq_segmento
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSegName"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_segmento"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados do segmento
REM -------------------------------------------------------------------------
Sub DB_GetSegVincData(p_rs, p_sigla, p_sq_segmento)
  Dim l_sigla, l_sq_segmento
  Set l_sigla       = Server.CreateObject("ADODB.Parameter")
  Set l_sq_segmento = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sigla                = .CreateParameter("l_sigla",       adVarchar, adParamInput, 30, p_sigla)
     set l_sq_segmento          = .CreateParameter("l_sq_segmento", adInteger, adParamInput, , p_sq_segmento)
     .parameters.Append         l_sigla
     .parameters.Append         l_sq_segmento
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSegVincData"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sigla"
     .Parameters.Delete         "l_sq_segmento"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados do módulo do segmento escolhido
REM -------------------------------------------------------------------------
Sub DB_GetSegModData(p_rs,p_sq_segmento, p_sq_modulo)
  Dim l_sq_segmento, l_sq_modulo
  Set l_sq_segmento = Server.CreateObject("ADODB.Parameter")
  Set l_sq_modulo   = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_segmento          = .CreateParameter("l_sq_segmento", adInteger, adParamInput, , p_sq_segmento)
     set l_sq_modulo            = .CreateParameter("l_sq_modulo",   adInteger, adParamInput, , p_sq_modulo)
     .parameters.Append         l_sq_segmento
     .parameters.Append         l_sq_modulo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSegModData"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_segmento"
     .Parameters.Delete         "l_sq_modulo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista de módulos de um segmento escolhido
REM -------------------------------------------------------------------------
Sub DB_GetSegModList(p_rs, p_sq_segmento)
  Dim l_sq_segmento
  Set l_sq_segmento = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_segmento          = .CreateParameter("l_sq_segmento", adInteger, adParamInput, , p_sq_segmento)
     .parameters.Append         l_sq_segmento
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSegModList"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_segmento"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados do módulo
REM -------------------------------------------------------------------------
Sub DB_GetModData(p_rs, p_sq_modulo)
  Dim l_sq_modulo
  Set l_sq_modulo = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_modulo                = .CreateParameter("l_sq_modulo", adInteger, adParamInput, , p_sq_modulo)
     .parameters.Append         l_sq_modulo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetModData"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_modulo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista de módulos
REM -------------------------------------------------------------------------
Sub DB_GetModList(p_rs)
  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetModList"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista de segmento
REM -------------------------------------------------------------------------
Sub DB_GetSegList(p_rs)
  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSegList"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados do segmento
REM -------------------------------------------------------------------------
Sub DB_GetSegData(p_rs, p_sq_segmento)
  Dim l_sq_segmento
  Set l_sq_segmento = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_segmento                = .CreateParameter("l_sq_segmento", adInteger, adParamInput, , p_sq_segmento)
     .parameters.Append         l_sq_segmento
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSegData"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_segmento"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>