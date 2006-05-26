<%
REM =========================================================================
REM Mantém os endereços da pessoa
REM -------------------------------------------------------------------------
Sub DML_PutCoPesEnd(Operacao, p_chave, p_pessoa, p_tipo_endereco, p_logradouro, p_complemento, _
         p_cidade, p_bairro, p_cep, p_padrao)
  Dim l_Operacao, l_Chave, l_pessoa, l_logradouro, l_complemento
  Dim l_tipo_endereco, l_cidade, l_cep, l_bairro, l_padrao
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa              = Server.CreateObject("ADODB.Parameter") 
  Set l_logradouro          = Server.CreateObject("ADODB.Parameter") 
  Set l_complemento         = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_endereco       = Server.CreateObject("ADODB.Parameter") 
  Set l_cidade              = Server.CreateObject("ADODB.Parameter") 
  Set l_cep                 = Server.CreateObject("ADODB.Parameter") 
  Set l_bairro              = Server.CreateObject("ADODB.Parameter") 
  Set l_padrao              = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_pessoa               = .CreateParameter("l_pessoa",          adInteger, adParamInput,    , p_pessoa)
     set l_logradouro           = .CreateParameter("l_logradouro",      adVarchar, adParamInput,  60, p_logradouro)
     set l_complemento          = .CreateParameter("l_complemento",     adVarchar, adParamInput,  20, Tvl(p_complemento))
     set l_tipo_endereco        = .CreateParameter("l_tipo_endereco",   adVarchar, adParamInput,  15, p_tipo_endereco)
     set l_cidade               = .CreateParameter("l_cidade",          adInteger, adParamInput,    , Tvl(p_cidade))
     set l_cep                  = .CreateParameter("l_cep",             adVarchar, adParamInput,   9, Tvl(p_cep))
     set l_bairro               = .CreateParameter("l_bairro",          adVarchar, adParamInput,  30, Tvl(p_bairro))
     set l_padrao               = .CreateParameter("l_padrao",          adVarchar, adParamInput,   1, Tvl(p_padrao))
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_pessoa
     .parameters.Append         l_logradouro
     .parameters.Append         l_complemento
     .parameters.Append         l_tipo_endereco
     .parameters.Append         l_cidade
     .parameters.Append         l_cep
     .parameters.Append         l_bairro
     .parameters.Append         l_padrao
     .CommandText               = Session("schema") & "SP_PutCoPesEnd"
     'On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_pessoa"
     .parameters.Delete         "l_logradouro"
     .parameters.Delete         "l_complemento"
     .parameters.Delete         "l_tipo_endereco"
     .parameters.Delete         "l_cidade"
     .parameters.Delete         "l_cep"
     .parameters.Delete         "l_bairro"
     .parameters.Delete         "l_padrao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém os telefones da pessoa
REM -------------------------------------------------------------------------
Sub DML_PutCoPesTel(Operacao, p_chave, p_pessoa, p_tipo_telefone, p_cidade, p_ddd, p_numero, p_padrao)
  Dim l_Operacao, l_chave, l_pessoa, l_tipo_telefone, l_cidade, l_ddd, l_numero, l_padrao
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa              = Server.CreateObject("ADODB.Parameter") 
  Set l_ddd                 = Server.CreateObject("ADODB.Parameter") 
  Set l_numero              = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_telefone       = Server.CreateObject("ADODB.Parameter") 
  Set l_cidade              = Server.CreateObject("ADODB.Parameter") 
  Set l_padrao              = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_pessoa               = .CreateParameter("l_pessoa",          adInteger, adParamInput,    , p_pessoa)
     set l_ddd                  = .CreateParameter("l_ddd",             adVarchar, adParamInput,   4, p_ddd)
     set l_numero               = .CreateParameter("l_numero",          adVarchar, adParamInput,  25, p_numero)
     set l_tipo_telefone        = .CreateParameter("l_tipo_telefone",   adVarchar, adParamInput,  15, p_tipo_telefone)
     set l_cidade               = .CreateParameter("l_cidade",          adInteger, adParamInput,    , p_cidade)
     set l_padrao               = .CreateParameter("l_padrao",          adVarchar, adParamInput,   1, p_padrao)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_pessoa
     .parameters.Append         l_ddd
     .parameters.Append         l_numero
     .parameters.Append         l_tipo_telefone
     .parameters.Append         l_cidade
     .parameters.Append         l_padrao
     .CommandText               = Session("schema") & "SP_PutCoPesTel"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_pessoa"
     .parameters.Delete         "l_ddd"
     .parameters.Delete         "l_numero"
     .parameters.Delete         "l_tipo_telefone"
     .parameters.Delete         "l_cidade"
     .parameters.Delete         "l_padrao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém as contas bancárias da pessoa
REM -------------------------------------------------------------------------
Sub DML_PutCoPesConBan(Operacao, p_chave, p_pessoa, p_tipo_conta, p_agencia, p_oper, p_numero, p_ativo, p_padrao)
  Dim l_Operacao, l_chave, l_pessoa, l_tipo_conta, l_agencia, l_oper, l_numero, l_padrao, l_ativo
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa              = Server.CreateObject("ADODB.Parameter") 
  Set l_oper                = Server.CreateObject("ADODB.Parameter") 
  Set l_numero              = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_conta          = Server.CreateObject("ADODB.Parameter") 
  Set l_agencia             = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo               = Server.CreateObject("ADODB.Parameter") 
  Set l_padrao              = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_pessoa               = .CreateParameter("l_pessoa",          adInteger, adParamInput,    , p_pessoa)
     set l_oper                 = .CreateParameter("l_oper",            adVarchar, adParamInput,   6, Tvl(p_oper))
     set l_numero               = .CreateParameter("l_numero",          adVarchar, adParamInput,  30, p_numero)
     set l_tipo_conta           = .CreateParameter("l_tipo_conta",      adVarchar, adParamInput,   1, p_tipo_conta)
     set l_agencia              = .CreateParameter("l_agencia",         adInteger, adParamInput,    , p_agencia)
     set l_ativo                = .CreateParameter("l_ativo",           adVarchar, adParamInput,   4, p_ativo)
     set l_padrao               = .CreateParameter("l_padrao",          adVarchar, adParamInput,   1, p_padrao)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_pessoa
     .parameters.Append         l_agencia
     .parameters.Append         l_oper
     .parameters.Append         l_numero
     .parameters.Append         l_tipo_conta
     .parameters.Append         l_ativo
     .parameters.Append         l_padrao
     .CommandText               = Session("schema") & "SP_PutCoPesConBan"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_pessoa"
     .parameters.Delete         "l_agencia"
     .parameters.Delete         "l_oper"
     .parameters.Delete         "l_numero"
     .parameters.Delete         "l_tipo_conta"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_padrao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém as contas bancárias da pessoa
REM -------------------------------------------------------------------------
Sub DML_PutSiwCliMod(Operacao, p_modulo, p_pessoa)
  Dim l_Operacao, l_modulo, l_pessoa
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_modulo               = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa              = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_modulo               = .CreateParameter("l_modulo",          adInteger, adParamInput,    , Tvl(p_modulo))
     set l_pessoa               = .CreateParameter("l_pessoa",          adInteger, adParamInput,    , p_pessoa)
     .parameters.Append         l_Operacao
     .parameters.Append         l_modulo
     .parameters.Append         l_pessoa
     .CommandText               = Session("schema") & "SP_PutSiwCliMod"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_modulo"
     .parameters.Delete         "l_pessoa"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>