<%
REM =========================================================================
REM Manipula registros de S_TIPO_CURSO
REM -------------------------------------------------------------------------
Sub DML_STIPOCURSO(Operacao, Chave, sg_tipo_curso, ds_tipo_curso)
  Dim l_Operacao, l_Chave, l_sg_tipo_curso, l_ds_tipo_curso
  Set l_Operacao      = Server.CreateObject("ADODB.Parameter")
  Set l_Chave         = Server.CreateObject("ADODB.Parameter")
  Set l_sg_tipo_curso = Server.CreateObject("ADODB.Parameter")
  Set l_ds_tipo_curso = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",      adVarchar, adParamInput,     1, Operacao)
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,      , Tvl(chave))
     set l_sg_tipo_curso        = .CreateParameter("l_sg_tipo_curso", adVarChar, adParamInput,     3, Tvl(sg_tipo_curso))
     set l_ds_tipo_curso        = .CreateParameter("l_ds_tipo_curso", adVarChar,    adParamInput, 50, Tvl(ds_tipo_curso))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sg_tipo_curso
     .parameters.Append         l_ds_tipo_curso
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_PutSTipoCurso"
     Else
        .CommandText               = "ecw.SP_PutSTipoCurso"
     End If
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sg_tipo_curso"
     .parameters.Delete         "l_ds_tipo_curso"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

