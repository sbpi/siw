<%
REM =========================================================================
REM Manipula registros de S_AREA_ATUACAO
REM -------------------------------------------------------------------------
Sub DML_SAREAATUACAO(Operacao, Chave, co_area_atuacao, ds_area_atuacao)
  Dim l_Operacao, l_Chave, l_co_area_atuacao, l_ds_area_atuacao
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_co_area_atuacao = Server.CreateObject("ADODB.Parameter")
  Set l_ds_area_atuacao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(chave))
     set l_co_area_atuacao      = .CreateParameter("l_co_area_atuacao", adInteger, adParamInput,    , Tvl(co_area_atuacao))
     set l_ds_area_atuacao      = .CreateParameter("l_ds_area_atuacao", adVarChar, adParamInput,  52, Tvl(ds_area_atuacao))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_co_area_atuacao
     .parameters.Append         l_ds_area_atuacao
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_PutSAreaAtuacao"
     Else
        .CommandText               = "ecw.SP_PutSAreaAtuacao"
     End If
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If          
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_co_area_atuacao"
     .parameters.Delete         "l_ds_area_atuacao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

