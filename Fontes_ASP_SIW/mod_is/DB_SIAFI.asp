<%
REM =========================================================================
REM Recupera ações do ppa
REM -------------------------------------------------------------------------
Sub DB_GetOrImport(p_rs, p_chave, p_cliente, p_responsavel, p_dt_ini, p_dt_fim, p_imp_ini, p_imp_fim)
  Dim l_chave, l_cliente, l_dt_ini, l_dt_fim, l_responsavel, l_imp_ini, l_imp_fim
  Set l_chave              = Server.CreateObject("ADODB.Parameter")
  Set l_responsavel        = Server.CreateObject("ADODB.Parameter")
  Set l_cliente            = Server.CreateObject("ADODB.Parameter")
  Set l_dt_ini             = Server.CreateObject("ADODB.Parameter")
  Set l_dt_fim             = Server.CreateObject("ADODB.Parameter")
  Set l_imp_ini            = Server.CreateObject("ADODB.Parameter")
  Set l_imp_fim            = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave              = .CreateParameter("l_chave",              adInteger, adParamInput,    , tvl(p_chave))
     set l_cliente            = .CreateParameter("l_cliente",            adInteger, adParamInput,    , p_cliente)
     set l_responsavel        = .CreateParameter("l_responsavel",        adVarchar, adParamInput,  60, Tvl(p_responsavel))
     set l_dt_ini             = .CreateParameter("l_dt_ini",             adDate,    adParamInput,    , Tvl(p_dt_ini))
     set l_dt_fim             = .CreateParameter("l_dt_fim",             adDate,    adParamInput,    , Tvl(p_dt_fim))
     set l_imp_ini            = .CreateParameter("l_imp_ini",            adDate,    adParamInput,    , Tvl(p_imp_ini))
     set l_imp_fim            = .CreateParameter("l_imp_fim",            adDate,    adParamInput,    , Tvl(p_imp_fim))
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_responsavel
     .parameters.Append         l_dt_ini
     .parameters.Append         l_dt_fim
     .parameters.Append         l_imp_ini
     .parameters.Append         l_imp_fim
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetORImport"
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
     .Parameters.Delete         "l_responsavel"
     .Parameters.Delete         "l_dt_ini"
     .Parameters.Delete         "l_dt_fim"
     .Parameters.Delete         "l_imp_ini"
     .Parameters.Delete         "l_imp_fim"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

