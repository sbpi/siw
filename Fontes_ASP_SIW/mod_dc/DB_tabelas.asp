<%
REM =========================================================================
REM Recupera Eventos de Trigger
REM -------------------------------------------------------------------------
Sub DB_GetEventoTrigger(p_rs, p_chave)
  Dim l_chave
  Set l_chave = Server.CreateObject("ADODB.Parameter")
  with sp
    set l_chave          = .CreateParameter("l_chave",adInteger, adParamInput, ,p_chave)
    .parameters.Append   l_chave
    If Session("dbms")   = 1 Then .Properties("PLSQLRSet") = TRUE End If
    .CommandText         = Session("schema") & "SP_GetEventoTrigger"
    Set p_rs             = Server.CreateObject("ADODB.RecordSet")
    p_rs.cursortype      = adOpenStatic
    p_rs.cursorlocation  = adUseClient
    On Error Resume Next 
    Set p_rs             = .Execute
    If Err.Description > "" Then 
      TrataErro          
    End If     
    If Session("dbms")   = 1 Then 
      .Properties("PLSQLRSet") = FALSE 
    End If
    .Parameters.Delete   "l_chave"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera tipos de dado
REM -------------------------------------------------------------------------
Sub DB_GetTipoDado(p_rs, p_chave)
  Dim l_chave
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetTipoDado"
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
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------



REM =========================================================================
REM Recupera tipos de índice
REM -------------------------------------------------------------------------
Sub DB_GetTipoIndice(p_rs, p_chave)
  Dim l_chave
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetTipoIndice"
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
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera tipos de Stored Procedure
REM -------------------------------------------------------------------------
Sub DB_GetTipoSP(p_rs, p_chave)
  Dim l_chave
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetTipoSP"
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
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera tipos de tabelas
REM -------------------------------------------------------------------------
Sub DB_GetTipoTabela(p_rs, p_chave)
  Dim l_chave
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetTipoTabela"
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
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------


%>

