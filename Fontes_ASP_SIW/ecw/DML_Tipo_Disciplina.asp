<%
REM =========================================================================
REM Manipula registros de S_TIPO_DISCIPLINA
REM -------------------------------------------------------------------------
Sub DML_STIPODISCIPLINA(Operacao, Chave, sg_disciplina, ds_tipo_disciplina)
  Dim l_Operacao, l_Chave, l_sg_disciplina, l_ds_tipo_disciplina
  Set l_Operacao      = Server.CreateObject("ADODB.Parameter")
  Set l_Chave         = Server.CreateObject("ADODB.Parameter")
  Set l_sg_disciplina = Server.CreateObject("ADODB.Parameter")
  Set l_ds_tipo_disciplina = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",           adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",              adInteger, adParamInput,    , Tvl(chave))
     set l_sg_disciplina        = .CreateParameter("l_sg_disciplina",      adChar,    adParamInput,   4, Tvl(sg_disciplina))
     set l_ds_tipo_disciplina   = .CreateParameter("l_ds_tipo_disciplina", adVarChar, adParamInput,  60, Tvl(ds_tipo_disciplina))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sg_disciplina
     .parameters.Append         l_ds_tipo_disciplina
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_PutSTipoDisc"
     Else
        .CommandText               = "ecw.SP_PutSTipoDisc"
     End If
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sg_disciplina"
     .parameters.Delete         "l_ds_tipo_disciplina"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

