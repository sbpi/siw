<%
REM =========================================================================
REM Manipula registros de S_ORIGEM_ESCOLA
REM -------------------------------------------------------------------------
Sub DML_SORIGEMESCOLA(Operacao, Chave, ds_origem_escola)
  Dim l_Operacao, l_Chave, l_co_origem_escola, l_ds_origem_escola
  Set l_Operacao         = Server.CreateObject("ADODB.Parameter")
  Set l_Chave            = Server.CreateObject("ADODB.Parameter")
  Set l_ds_origem_escola = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao              = .CreateParameter("l_operacao",         adVarchar, adParamInput,   1, Operacao)
     set l_chave                 = .CreateParameter("l_chave",            adInteger, adParamInput,    , Tvl(chave))
     set l_ds_origem_escola      = .CreateParameter("l_ds_origem_escola", adVarChar, adParamInput,  30, Tvl(ds_origem_escola))
     .parameters.Append          l_Operacao
     .parameters.Append          l_Chave
     .parameters.Append          l_ds_origem_escola
     If Session("dbms") = 2 Then
        .CommandText                = "ecw.ecw.SP_PutSOrEscola"
     Else
        .CommandText                = "ecw.SP_PutSOrEscola"
     End If
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     .parameters.Delete          "l_Operacao"
     .parameters.Delete          "l_Chave"
     .parameters.Delete          "l_ds_origem_escola"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

