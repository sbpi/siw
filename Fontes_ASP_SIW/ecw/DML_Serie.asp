<%
REM =========================================================================
REM Manipula registros de S_SERIE
REM -------------------------------------------------------------------------
Sub DML_SSERIE(Operacao, Chave, co_serie, co_tipo_curso, ds_serie)
  Dim l_Operacao, l_Chave, l_co_serie, l_co_tipo_curso, l_ds_serie
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_co_serie        = Server.CreateObject("ADODB.Parameter")
  Set l_co_tipo_curso   = Server.CreateObject("ADODB.Parameter")
  Set l_ds_serie        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adVarchar, adParamInput,   5, chave)
     set l_co_serie             = .CreateParameter("l_co_serie",        adVarchar, adParamInput,   5, Tvl(co_serie))
     set l_co_tipo_curso        = .CreateParameter("l_co_tipo_curso",   adInteger, adParamInput,    , Tvl(co_tipo_curso))
     set l_ds_serie             = .CreateParameter("l_ds_serie",        adVarChar, adParamInput,  50, Tvl(ds_serie))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_co_serie
     .parameters.Append         l_co_tipo_curso
     .parameters.Append         l_ds_serie
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_PutSSerie"
     Else
        .CommandText               = "ecw.SP_PutSSerie"
     End If
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If          
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_co_serie"
     .parameters.Delete         "l_co_tipo_curso"
     .parameters.Delete         "l_ds_serie"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

