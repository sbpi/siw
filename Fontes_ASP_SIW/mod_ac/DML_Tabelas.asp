<%

REM =========================================================================
REM Mantém a tabela de tipos de contrato
REM -------------------------------------------------------------------------
Sub DML_PutAgreeType(Operacao, p_chave, p_chave_pai, p_cliente, p_nome, p_sigla, p_modalidade, _
       p_prazo_indeterm, p_pessoa_juridica, p_pessoa_fisica, p_ativo)
  Dim l_Operacao, l_chave, l_chave_pai, l_cliente, l_nome, l_sigla, l_modalidade
  Dim l_prazo_indeterm, l_pessoa_juridica, l_pessoa_fisica, l_ativo
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_pai           = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente             = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_sigla               = Server.CreateObject("ADODB.Parameter") 
  Set l_modalidade          = Server.CreateObject("ADODB.Parameter") 
  Set l_prazo_indeterm      = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa_juridica     = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa_fisica       = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo               = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_pai            = .CreateParameter("l_chave_pai",       adInteger, adParamInput,    , Tvl(p_chave_pai))
     set l_cliente              = .CreateParameter("l_cliente",         adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome                 = .CreateParameter("l_nome",            adVarchar, adParamInput,  60, Tvl(p_nome))
     set l_sigla                = .CreateParameter("l_sigla",           adVarchar, adParamInput,  10, Tvl(p_sigla))
     set l_modalidade           = .CreateParameter("l_modalidade",      adVarchar, adParamInput,   1, Tvl(p_modalidade))
     set l_prazo_indeterm       = .CreateParameter("l_prazo_indeterm",  adVarchar, adParamInput,   1, Tvl(p_prazo_indeterm))
     set l_pessoa_juridica      = .CreateParameter("l_pessoa_juridica", adVarchar, adParamInput,   1, Tvl(p_pessoa_juridica))
     set l_pessoa_fisica        = .CreateParameter("l_pessoa_fisica",   adVarchar, adParamInput,   1, Tvl(p_pessoa_fisica))
     set l_ativo                = .CreateParameter("l_ativo",           adVarchar, adParamInput,   1, Tvl(p_ativo))
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_pai
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_sigla
     .parameters.Append         l_modalidade
     .parameters.Append         l_prazo_indeterm
     .parameters.Append         l_pessoa_juridica
     .parameters.Append         l_pessoa_fisica
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutAgreeType"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_pai"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_modalidade"
     .parameters.Delete         "l_prazo_indeterm"
     .parameters.Delete         "l_pessoa_juridica"
     .parameters.Delete         "l_pessoa_fisica"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>