<%
REM =========================================================================
REM Manipula registros de S_TURNO
REM -------------------------------------------------------------------------
Sub DML_STURNO(Operacao, Chave, co_turno, ds_turno)
  Dim l_Operacao, l_Chave, l_co_turno, l_ds_turno
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_co_turno = Server.CreateObject("ADODB.Parameter")
  Set l_ds_turno = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adchar   , adParamInput,   2, chave)
     set l_co_turno             = .CreateParameter("l_co_turno",        adchar   , adParamInput,   2, Tvl(co_turno))
     set l_ds_turno             = .CreateParameter("l_ds_turno",        adVarChar, adParamInput,  30, Tvl(ds_turno))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_co_turno
     .parameters.Append         l_ds_turno
     If Session("dbms") = 2 Then 
        .CommandText               = "ecw.ecw.SP_PutSTurno"
     Else
        .CommandText               = "ecw.SP_PutSTurno"
     End If
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If          
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_co_turno"
     .parameters.Delete         "l_ds_turno"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

