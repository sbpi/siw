<%
REM =========================================================================
REM Manipula registros de S_CARGO
REM -------------------------------------------------------------------------
Sub DML_SCargo(Operacao, Chave, co_cargo, ds_cargo)
  Dim l_Operacao, l_Chave, l_co_cargo, l_ds_cargo
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_co_cargo        = Server.CreateObject("ADODB.Parameter")
  Set l_ds_cargo        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao        = .CreateParameter("l_operacao",   adVarchar, adParamInput,  1, Operacao)
     set l_chave           = .CreateParameter("l_chave",      adVarchar, adParamInput, 17, chave)
     set l_co_cargo        = .CreateParameter("l_co_cargo",   adVarchar, adParamInput, 17, Tvl(co_cargo))
     set l_ds_cargo        = .CreateParameter("l_ds_cargo",   adChar,    adParamInput, 30, Tvl(ds_cargo))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_co_cargo
     .parameters.Append         l_ds_cargo
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_PutSCARGO"
     Else
        .CommandText               = "ecw.SP_PutSCARGO"
     End If
     .Execute
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_co_cargo"
     .parameters.Delete         "l_ds_cargo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

