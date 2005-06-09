<%

REM =========================================================================
REM Mantém a tabela de impostos
REM -------------------------------------------------------------------------
Sub DML_PutImposto(Operacao, p_chave,   p_cliente,       p_nome, p_descricao, p_sigla,_
                   p_esfera, p_calculo, p_dia_pagamento, p_ativo)
  
  Dim l_Operacao, l_chave, l_cliente, l_nome, l_sigla, l_descricao
  Dim l_esfera, l_calculo, l_dia_pagamento, l_ativo
  
  Set l_Operacao       = Server.CreateObject("ADODB.Parameter")
  Set l_chave          = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente        = Server.CreateObject("ADODB.Parameter") 
  Set l_nome           = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao      = Server.CreateObject("ADODB.Parameter") 
  Set l_sigla          = Server.CreateObject("ADODB.Parameter") 
  Set l_esfera         = Server.CreateObject("ADODB.Parameter") 
  Set l_calculo        = Server.CreateObject("ADODB.Parameter") 
  Set l_dia_pagamento  = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo          = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao      = .CreateParameter("l_operacao",      adVarchar, adParamInput,   1, Operacao)
     set l_chave         = .CreateParameter("l_chave",         adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente       = .CreateParameter("l_cliente",       adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome          = .CreateParameter("l_nome",          adVarchar, adParamInput,  50, Tvl(p_nome))
     set l_descricao     = .CreateParameter("l_descricao",     adVarchar, adParamInput, 500, Tvl(p_descricao))
     set l_sigla         = .CreateParameter("l_sigla",         adVarchar, adParamInput,  15, Tvl(p_sigla))
     set l_esfera        = .CreateParameter("l_esfera",        adVarchar, adParamInput,   1, Tvl(p_esfera))
     set l_calculo       = .CreateParameter("l_calculo",       adInteger, adParamInput,    , Tvl(p_calculo))
     set l_dia_pagamento = .CreateParameter("l_dia_pagamento", adInteger, adParamInput,    , Tvl(p_dia_pagamento))
     set l_ativo         = .CreateParameter("l_ativo",         adVarchar, adParamInput,   1, Tvl(p_ativo))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_descricao
     .parameters.Append         l_sigla
     .parameters.Append         l_esfera
     .parameters.Append         l_calculo
     .parameters.Append         l_dia_pagamento
     .parameters.Append         l_ativo
     
     .CommandText               = Session("schema") & "SP_PutImposto"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_esfera"
     .parameters.Delete         "l_calculo"
     .parameters.Delete         "l_dia_pagamento"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de tipos de documento
REM -------------------------------------------------------------------------
Sub DML_PutTipoDocumento(Operacao, p_chave, p_cliente, p_nome, p_sigla, p_ativo)
  
  Dim l_Operacao, l_chave, l_cliente, l_nome, l_sigla, l_ativo
  
  
  Set l_Operacao       = Server.CreateObject("ADODB.Parameter")
  Set l_chave          = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente        = Server.CreateObject("ADODB.Parameter") 
  Set l_nome           = Server.CreateObject("ADODB.Parameter") 
  Set l_sigla          = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo          = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao      = .CreateParameter("l_operacao",      adVarchar, adParamInput,   1, Operacao)
     set l_chave         = .CreateParameter("l_chave",         adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente       = .CreateParameter("l_cliente",       adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome          = .CreateParameter("l_nome",          adVarchar, adParamInput,  50, Tvl(p_nome))
     set l_sigla         = .CreateParameter("l_sigla",         adVarchar, adParamInput,  15, Tvl(p_sigla))
     set l_ativo         = .CreateParameter("l_ativo",         adVarchar, adParamInput,   1, Tvl(p_ativo))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_sigla
     .parameters.Append         l_ativo
     
     .CommandText               = Session("schema") & "SP_PutTipoDocumento"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de tipos de contrato
REM -------------------------------------------------------------------------
Sub DML_PutTipoLancamento(Operacao, p_chave, p_cliente, p_nome, p_descricao, p_receita,_
                          p_despesa, p_ativo)
  
  Dim l_Operacao, l_chave, l_cliente, l_nome, l_receita, l_descricao
  Dim l_despesa, l_ativo
  
  Set l_Operacao       = Server.CreateObject("ADODB.Parameter")
  Set l_chave          = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente        = Server.CreateObject("ADODB.Parameter") 
  Set l_nome           = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao      = Server.CreateObject("ADODB.Parameter") 
  Set l_receita        = Server.CreateObject("ADODB.Parameter") 
  Set l_despesa        = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo          = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao      = .CreateParameter("l_operacao",  adVarchar, adParamInput,   1, Operacao)
     set l_chave         = .CreateParameter("l_chave",     adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente       = .CreateParameter("l_cliente",   adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome          = .CreateParameter("l_nome",      adVarchar, adParamInput, 200, Tvl(p_nome))
     set l_descricao     = .CreateParameter("l_descricao", adVarchar, adParamInput, 200, Tvl(p_descricao))
     set l_receita       = .CreateParameter("l_receita",   adVarchar, adParamInput,   1, Tvl(p_receita))
     set l_despesa       = .CreateParameter("l_despesa",   adVarchar, adParamInput,   1, Tvl(p_despesa))
     set l_ativo         = .CreateParameter("l_ativo",     adVarchar, adParamInput,   1, Tvl(p_ativo))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_descricao
     .parameters.Append         l_receita
     .parameters.Append         l_despesa
     .parameters.Append         l_ativo
     
     .CommandText               = Session("schema") & "SP_PutTipoLancamento"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_receita"
     .parameters.Delete         "l_despesa"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>