<%
REM =========================================================================
REM Manipula registros de S_TIPO_SALA
REM -------------------------------------------------------------------------
Sub DML_STIPOSALA(Operacao, Chave, ds_tipo_sala)
  Dim l_Operacao, l_Chave, l_co_tipo_sala, l_ds_tipo_sala
  Set l_Operacao         = Server.CreateObject("ADODB.Parameter")
  Set l_Chave            = Server.CreateObject("ADODB.Parameter")
  Set l_ds_tipo_sala     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao              = .CreateParameter("l_operacao",     adVarchar, adParamInput,   1, Operacao)
     set l_chave                 = .CreateParameter("l_chave",        adInteger, adParamInput,    , Tvl(chave))
     set l_ds_tipo_sala          = .CreateParameter("l_ds_tipo_sala", adVarChar, adParamInput,  30, Tvl(ds_tipo_sala))
     .parameters.Append          l_Operacao
     .parameters.Append          l_Chave
     .parameters.Append          l_ds_tipo_sala
     If Session("dbms") = 2 Then
        .CommandText                = "ecw.ecw.SP_PutSTipoSala"
     Else
        .CommandText                = "ecw.SP_PutSTipoSala"
     End If
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     .parameters.Delete          "l_Operacao"
     .parameters.Delete          "l_Chave"
     .parameters.Delete          "l_ds_tipo_sala"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

