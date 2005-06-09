<%
REM =========================================================================
REM Manipula registros de EO_Tipo_unidade
REM -------------------------------------------------------------------------
Sub DML_PutEOTipoUni(Operacao, Chave, p_cliente, nome, ativo)
  Dim l_Operacao, l_Chave, l_cliente, l_nome, l_ativo
  Set l_Operacao           = Server.CreateObject("ADODB.Parameter")
  Set l_Chave              = Server.CreateObject("ADODB.Parameter")
  Set l_cliente            = Server.CreateObject("ADODB.Parameter")
  Set l_nome               = Server.CreateObject("ADODB.Parameter")
  Set l_ativo              = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",           adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",              adInteger, adParamInput,    , Tvl(chave))
     set l_cliente              = .CreateParameter("l_cliente",            adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome                 = .CreateParameter("l_nome",               adVarChar, adParamInput,  25, nome)
     set l_ativo                = .CreateParameter("l_ativo",              adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutEOTipoUni"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de EO_Tipo_unidade
REM -------------------------------------------------------------------------
Sub DML_PutEOAAtuac(Operacao, Chave, p_cliente, nome, ativo)
  Dim l_Operacao, l_Chave, l_cliente, l_nome, l_ativo
  Set l_Operacao           = Server.CreateObject("ADODB.Parameter")
  Set l_Chave              = Server.CreateObject("ADODB.Parameter")
  Set l_cliente            = Server.CreateObject("ADODB.Parameter")
  Set l_nome               = Server.CreateObject("ADODB.Parameter")
  Set l_ativo              = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",           adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",              adInteger, adParamInput,    , Tvl(chave))
     set l_cliente              = .CreateParameter("l_cliente",            adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome                 = .CreateParameter("l_nome",               adVarChar, adParamInput,  25, nome)
     set l_ativo                = .CreateParameter("l_ativo",              adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutEOAAtuac"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de EO_Tipo_Posto
REM -------------------------------------------------------------------------
Sub DML_PutEOTipoPosto(Operacao, Chave, p_cliente, nome, sigla, descricao, ativo, padrao)
  Dim l_Operacao, l_Chave, l_cliente, l_nome, l_sigla, l_descricao, l_ativo, l_padrao
  Set l_Operacao           = Server.CreateObject("ADODB.Parameter")
  Set l_Chave              = Server.CreateObject("ADODB.Parameter")
  Set l_cliente            = Server.CreateObject("ADODB.Parameter")
  Set l_nome               = Server.CreateObject("ADODB.Parameter")
  Set l_sigla              = Server.CreateObject("ADODB.Parameter")
  Set l_descricao          = Server.CreateObject("ADODB.Parameter")
  Set l_ativo              = Server.CreateObject("ADODB.Parameter")
  Set l_padrao             = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",           adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",              adInteger, adParamInput,    , Tvl(chave))
     set l_cliente              = .CreateParameter("l_cliente",            adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome                 = .CreateParameter("l_nome",               adVarChar, adParamInput,  30, nome)
     set l_sigla                = .CreateParameter("l_sigla",              adVarChar, adParamInput,   5, sigla)
     set l_descricao            = .CreateParameter("l_descricao",          adVarChar, adParamInput, 200, descricao)
     set l_ativo                = .CreateParameter("l_ativo",              adVarchar, adParamInput,   1, ativo)
     set l_padrao               = .CreateParameter("l_padrao",             adVarchar, adParamInput,   1, padrao)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_sigla
     .parameters.Append         l_descricao
     .parameters.Append         l_ativo
     .parameters.Append         l_padrao
     .CommandText               = Session("schema") & "SP_PutEOTipoPosto"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_padrao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>