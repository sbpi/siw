<%
REM =========================================================================
REM Recupera as Áreas de atuações existentes
REM -------------------------------------------------------------------------
Sub DB_GetSerieList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetSerieList"
     Else
        .CommandText               = "ecw.SP_GetSerieList"
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
Sub DB_GetSerieData(p_rs, p_sg_serie)
  Dim l_sg_serie
  Set l_sg_serie = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sg_serie             = .CreateParameter("l_sg_serie", adVarChar, adParamInput, 5, p_sg_serie)
     .parameters.Append         l_sg_serie
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetSerieData"
     Else
        .CommandText               = "ecw.SP_GetSerieData"
     End If
     'Response.Write "["&l_sg_serie&"]"
     'Response.Write "<br>"
     'Response.Write sp.commandtext
     'Response.End()
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sg_serie"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

