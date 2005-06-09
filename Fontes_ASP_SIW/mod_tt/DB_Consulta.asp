<%
REM =========================================================================
REM Recupera Eventos de Trigger
REM -------------------------------------------------------------------------
Sub DB_GetRamalUsuarioAtivo(p_rs, p_cliente)
  Dim l_cliente
  
  Set l_cliente       = Server.CreateObject("ADODB.Parameter")
  with sp
  
    set l_cliente = .CreateParameter("l_cliente", adInteger, adParamInput, 18, p_cliente)
    
    .parameters.Append   l_cliente
    If Session("dbms")   = 1 Then .Properties("PLSQLRSet") = TRUE End If
    .CommandText         = Session("schema") & "SP_GetTTRamalUsuarioAtivo"
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
    
    .Parameters.Delete   "l_cliente"
  
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>