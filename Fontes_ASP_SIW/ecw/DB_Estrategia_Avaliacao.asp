<%
REM =========================================================================
REM Recupera as Áreas de atuações existentes
REM -------------------------------------------------------------------------
Sub DB_GetAvaliationList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetAvalList"
     Else
        .CommandText               = "ecw.SP_GetAvalList"
     End If
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
REM Recupera os dados da área de atuação
REM -------------------------------------------------------------------------
Sub DB_GetAvaliationData(p_rs, p_co_tipo_avaliacao)
  Dim l_co_tipo_avaliacao
  Set l_co_tipo_avaliacao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_co_tipo_avaliacao    = .CreateParameter("l_co_tipo_avaliacao", adInteger, adParamInput, , p_co_tipo_avaliacao)
     .parameters.Append         l_co_tipo_avaliacao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetAvalData"
     Else
        .CommandText               = "ecw.SP_GetAvalData"
     End If
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_co_tipo_avaliacao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

