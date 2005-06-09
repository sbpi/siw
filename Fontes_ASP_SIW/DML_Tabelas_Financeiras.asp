<%
REM =========================================================================
REM Manipula registros de CT_CC
REM -------------------------------------------------------------------------
Sub DML_CTCC(Operacao, Chave, sq_cc_pai, cliente, nome, descricao, sigla, receita, regular, ativo)
  Dim l_Operacao, l_Chave, l_sq_cc_pai, l_cliente, l_nome, l_descricao, l_sigla, l_receita, l_regular, l_ativo
  Set l_Operacao  = Server.CreateObject("ADODB.Parameter")
  Set l_Chave     = Server.CreateObject("ADODB.Parameter")
  Set l_sq_cc_pai = Server.CreateObject("ADODB.Parameter")
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_nome      = Server.CreateObject("ADODB.Parameter")
  Set l_descricao = Server.CreateObject("ADODB.Parameter")
  Set l_sigla     = Server.CreateObject("ADODB.Parameter")
  Set l_receita   = Server.CreateObject("ADODB.Parameter")
  Set l_regular   = Server.CreateObject("ADODB.Parameter")
  Set l_ativo     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,    , Tvl(chave))
     set l_sq_cc_pai            = .CreateParameter("l_sq_cc_pai",   adInteger, adParamInput,    , Tvl(sq_cc_pai))
     set l_cliente              = .CreateParameter("l_cliente",     adInteger, adParamInput,    , cliente)
     set l_nome                 = .CreateParameter("l_nome",        adVarChar, adParamInput,  60, nome)
     set l_descricao            = .CreateParameter("l_descricao",   adVarChar, adParamInput, 500, descricao)
     set l_sigla                = .CreateParameter("l_sigla",       adVarChar, adParamInput,  20, sigla)
     set l_receita              = .CreateParameter("l_receita",     adVarchar, adParamInput,   1, receita)
     set l_regular              = .CreateParameter("l_regular",     adVarchar, adParamInput,   1, regular)
     set l_ativo                = .CreateParameter("l_ativo",       adVarchar, adParamInput,   1, Tvl(ativo))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_cc_pai
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_descricao
     .parameters.Append         l_sigla
     .parameters.Append         l_receita
     .parameters.Append         l_regular
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutCTCC"
     .Execute
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_cc_pai"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_receita"
     .parameters.Delete         "l_regular"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de CO_BANCO
REM -------------------------------------------------------------------------
Sub DML_COBANCO(Operacao, Chave, nome, codigo, padrao, ativo)
  Dim l_Operacao, l_Chave, l_nome, l_codigo, l_padrao, l_ativo
  Set l_Operacao  = Server.CreateObject("ADODB.Parameter")
  Set l_Chave     = Server.CreateObject("ADODB.Parameter")
  Set l_nome      = Server.CreateObject("ADODB.Parameter")
  Set l_codigo    = Server.CreateObject("ADODB.Parameter")
  Set l_padrao    = Server.CreateObject("ADODB.Parameter")
  Set l_ativo     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,    , Tvl(chave))
     set l_nome                 = .CreateParameter("l_nome",        adVarChar, adParamInput,  60, nome)
     set l_codigo               = .CreateParameter("l_codigo",      adVarChar, adParamInput,  30, codigo)
     set l_padrao               = .CreateParameter("l_padrao",      adVarchar, adParamInput,   1, padrao)
     set l_ativo                = .CreateParameter("l_ativo",       adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_codigo
     .parameters.Append         l_padrao
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutCOBANCO"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_codigo"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de CO_agencia
REM -------------------------------------------------------------------------
Sub DML_COAGENCIA(Operacao, Chave, banco, nome, codigo, padrao, ativo)
  Dim l_Operacao, l_Chave, l_sq_banco, l_nome, l_codigo, l_padrao, l_ativo
  Set l_Operacao  = Server.CreateObject("ADODB.Parameter")
  Set l_Chave     = Server.CreateObject("ADODB.Parameter")
  Set l_sq_banco  = Server.CreateObject("ADODB.Parameter")
  Set l_nome      = Server.CreateObject("ADODB.Parameter")
  Set l_codigo    = Server.CreateObject("ADODB.Parameter")
  Set l_padrao    = Server.CreateObject("ADODB.Parameter")
  Set l_ativo     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,    , Tvl(chave))
     set l_sq_banco             = .CreateParameter("l_sq_banco",    adInteger, adParamInput,    , banco)
     set l_nome                 = .CreateParameter("l_nome",        adVarChar, adParamInput,  60, nome)
     set l_codigo               = .CreateParameter("l_codigo",      adVarChar, adParamInput,  30, codigo)
     set l_padrao               = .CreateParameter("l_padrao",      adVarchar, adParamInput,   1, padrao)
     set l_ativo                = .CreateParameter("l_ativo",       adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_banco
     .parameters.Append         l_nome
     .parameters.Append         l_codigo
     .parameters.Append         l_padrao
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutCOagencia"
     .Execute
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_banco"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_codigo"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

