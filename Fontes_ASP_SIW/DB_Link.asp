<%
REM =========================================================================
REM Recupera os dados do link pai do que foi informado
REM -------------------------------------------------------------------------
Sub DB_GetLinkDataParent(p_rs, p_cliente, p_sg)
  Dim l_cliente, l_sg
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_sg        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente        = .CreateParameter("l_cliente",   adInteger, adParamInput,   , p_cliente)
     set l_sg             = .CreateParameter("l_sg",        adVarChar, adParamInput, 10, p_sg)
     .parameters.Append         l_cliente
     .parameters.Append         l_sg
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLnkDataPrnt"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_sg"
  end with
End Sub

REM =========================================================================
REM Recupera os dados dos pais do link informado
REM -------------------------------------------------------------------------
Sub DB_GetLinkDataParents(p_rs, p_sq_menu)
  Dim l_sq_menu
  Set l_sq_menu   = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_menu              = .CreateParameter("l_sq_menu",   adInteger, adParamInput,   , p_sq_menu)
     .parameters.Append         l_sq_menu
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLnkDataPrnts"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_menu"
  end with
End Sub
%>

