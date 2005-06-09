<%
REM =========================================================================
REM Manipula registros de S_TIPO_AVALIACAO
REM -------------------------------------------------------------------------
Sub DML_STIPOAVALIACAO(Operacao, Chave, ds_tipo_avaliacao)
  Dim l_Operacao, l_Chave, l_co_tipo_avaliacao, l_ds_tipo_avaliacao
  Set l_Operacao          = Server.CreateObject("ADODB.Parameter")
  Set l_Chave             = Server.CreateObject("ADODB.Parameter")
  Set l_ds_tipo_avaliacao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao              = .CreateParameter("l_operacao",         adVarchar, adParamInput,   1, Operacao)
     set l_chave                 = .CreateParameter("l_chave",            adInteger, adParamInput,    , Tvl(chave))
     set l_ds_tipo_avaliacao     = .CreateParameter("l_ds_tipo_avaliacao",adVarChar, adParamInput,  30, Tvl(ds_tipo_avaliacao))
     .parameters.Append          l_Operacao
     .parameters.Append          l_Chave
     .parameters.Append          l_ds_tipo_avaliacao
     If Session("dbms") = 2 Then
        .CommandText                = "ecw.ecw.SP_PutSTPAvaliacao"
     Else
        .CommandText                = "ecw.SP_PutSTPAvaliacao"
     End If
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete          "l_Operacao"
     .parameters.Delete          "l_Chave"
     .parameters.Delete          "l_ds_tipo_avaliacao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

