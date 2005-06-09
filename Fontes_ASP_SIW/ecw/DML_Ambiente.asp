<%
REM =========================================================================
REM Manipula registros de S_AMBIENTE
REM -------------------------------------------------------------------------
Sub DML_SAMBIENTE(Operacao, Chave, ds_ambiente)
  Dim l_Operacao, l_Chave, l_ds_ambiente
  Set l_Operacao    = Server.CreateObject("ADODB.Parameter")
  Set l_Chave       = Server.CreateObject("ADODB.Parameter")
  Set l_ds_ambiente = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,    , Tvl(chave))
     set l_ds_ambiente          = .CreateParameter("l_ds_ambiente", adVarChar, adParamInput,  30, Tvl(ds_ambiente))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_ds_ambiente
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_PutSAmbiente"
     Else
        .CommandText               = "ecw.SP_PutSAmbiente"
     End If
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_ds_ambiente"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

