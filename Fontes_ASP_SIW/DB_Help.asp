<%
REM =========================================================================
REM Recupera os links permitidos ao usuário informado
REM -------------------------------------------------------------------------
Sub DB_GetLinkDataHelp(p_rs, p_cliente, p_modulo, p_chave, p_restricao)
  Dim l_cliente, l_modulo, l_chave, l_restricao
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente", adInteger, adParamInput, , p_cliente)
     set l_modulo               = .CreateParameter("l_modulo",  adInteger, adParamInput, , p_modulo)
     set l_chave                = .CreateParameter("l_chave",   adInteger, adParamInput, , p_chave)
     set l_restricao            = .CreateParameter("l_restricao", adVarChar, adParamInput, 20, p_restricao)
     .parameters.Append         l_cliente
     .parameters.Append         l_modulo
     .parameters.Append         l_chave
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLinkDataHelp"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_modulo"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

