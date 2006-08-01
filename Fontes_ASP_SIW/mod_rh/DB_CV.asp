<%
REM =========================================================================
REM Recupera informações de um currículo a partir da chave primária
REM -------------------------------------------------------------------------
Sub DB_GetCV(p_rs, p_cliente, p_chave, p_sigla, p_tipo)
  Dim l_cliente, l_chave, l_sigla, l_tipo
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_sigla       = Server.CreateObject("ADODB.Parameter")
  Set l_tipo        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,   , p_cliente)
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     set l_sigla           = .CreateParameter("l_sigla",        adVarchar, adParamInput, 20, tvl(p_sigla))
     set l_tipo            = .CreateParameter("l_tipo",         adVarchar, adParamInput, 20, tvl(p_tipo))
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_sigla
     .parameters.Append         l_tipo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetCV"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_sigla"
     .Parameters.Delete         "l_tipo"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera informações de um currículo a partir do CPF
REM -------------------------------------------------------------------------
Sub DB_GetCV_Pessoa(p_rs, p_cliente, p_cpf)
  Dim l_cliente, l_cpf
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_cpf         = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,   , p_cliente)
     set l_cpf             = .CreateParameter("l_cpf",          adVarchar, adParamInput, 14, p_cpf)
     .parameters.Append         l_cliente
     .parameters.Append         l_cpf
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetCV_Pessoa"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_cpf"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera idiomas do colaborador
REM -------------------------------------------------------------------------
Sub DB_GetCVIdioma(p_rs, p_usuario, p_chave)
  Dim l_usuario, l_chave, l_sigla, l_tipo
  Set l_usuario     = Server.CreateObject("ADODB.Parameter")
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_usuario         = .CreateParameter("l_usuario",      adInteger, adParamInput,   , p_usuario)
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     .parameters.Append         l_usuario
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetCVIdioma"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_usuario"
     .Parameters.Delete         "l_chave"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera informações sobre a formação do colaborador
REM -------------------------------------------------------------------------
Sub DB_GetCVAcadForm(p_rs, p_usuario, p_chave, p_tipo)
  Dim l_usuario, l_chave, l_sigla, l_tipo
  Set l_usuario     = Server.CreateObject("ADODB.Parameter")
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_tipo        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_usuario         = .CreateParameter("l_usuario",      adInteger, adParamInput,   , p_usuario)
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     set l_tipo            = .CreateParameter("l_tipo",         adVarchar, adParamInput, 20, tvl(p_tipo))
     .parameters.Append         l_usuario
     .parameters.Append         l_chave
     .parameters.Append         l_tipo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetCVAcadForm"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_usuario"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_tipo"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera informações sobre as áreas do conhecimento
REM -------------------------------------------------------------------------
Sub DB_GetKnowArea(p_rs, p_chave, p_nome, p_tipo)
  Dim l_chave, l_nome, l_tipo
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_nome        = Server.CreateObject("ADODB.Parameter")
  Set l_tipo        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     set l_nome            = .CreateParameter("l_nome",         adVarchar, adParamInput, 30, tvl(p_nome))
     set l_tipo            = .CreateParameter("l_tipo",         adVarchar, adParamInput,  1, p_tipo)
     .parameters.Append         l_chave
     .parameters.Append         l_nome
     .parameters.Append         l_tipo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetKnowArea"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_nome"
     .Parameters.Delete         "l_tipo"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

