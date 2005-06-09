<%
REM =========================================================================
REM Mantщm a tabela de aчѕes do PPA
REM -------------------------------------------------------------------------
Sub DML_PutAcaoPPA(Operacao, p_chave, p_cliente, p_sq_acao_ppa_pai, p_codigo, p_nome, _
       p_responsavel, p_telefone, p_email, p_ativo, p_padrao, p_aprovado, p_saldo, _
       p_empenhado, p_liquidado, p_liquidar, p_selecionada_mpog, p_selecionada_relevante, _
       p_cod_programa, p_cod_acao)


  Dim l_Operacao, l_Chave, l_cliente, l_sq_acao_ppa_pai, l_codigo, l_nome
  Dim l_responsavel, l_telefone, l_email, l_ativo, l_padrao
  Dim l_aprovado, l_saldo, l_empenhado, l_liquidado, l_liquidar
  Dim l_selecionada_mpog, l_selecionada_relevante, l_cod_programa, l_cod_acao
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_acao_ppa_pai         = Server.CreateObject("ADODB.Parameter") 
  Set l_codigo                  = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_responsavel             = Server.CreateObject("ADODB.Parameter") 
  Set l_telefone                = Server.CreateObject("ADODB.Parameter") 
  Set l_email                   = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  Set l_padrao                  = Server.CreateObject("ADODB.Parameter") 
  Set l_aprovado                = Server.CreateObject("ADODB.Parameter") 
  Set l_saldo                   = Server.CreateObject("ADODB.Parameter") 
  Set l_empenhado               = Server.CreateObject("ADODB.Parameter") 
  Set l_liquidado               = Server.CreateObject("ADODB.Parameter") 
  Set l_liquidar                = Server.CreateObject("ADODB.Parameter") 
  Set l_selecionada_mpog        = Server.CreateObject("ADODB.Parameter") 
  Set l_selecionada_relevante   = Server.CreateObject("ADODB.Parameter") 
  Set l_cod_programa            = Server.CreateObject("ADODB.Parameter")
  Set l_cod_acao                = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",         adInteger, adParamInput,    , Tvl(p_cliente))
     set l_sq_acao_ppa_pai      = .CreateParameter("l_sq_acao_ppa_pai", adInteger, adParamInput,    , Tvl(p_sq_acao_ppa_pai))
     set l_codigo               = .CreateParameter("l_codigo",          adVarchar, adParamInput,  60, Tvl(p_codigo))
     set l_nome                 = .CreateParameter("l_nome",            adVarchar, adParamInput, 100, Tvl(p_nome))
     set l_responsavel          = .CreateParameter("l_responsavel",     adVarchar, adParamInput,  60, Tvl(p_responsavel))
     set l_telefone             = .CreateParameter("l_telefone",        adVarchar, adParamInput,  20, Tvl(p_telefone))
     set l_email                = .CreateParameter("l_email",           adVarchar, adParamInput,  60, Tvl(p_email))
     set l_ativo                = .CreateParameter("l_ativo",           adVarchar, adParamInput,   1, Tvl(p_ativo))
     set l_padrao               = .CreateParameter("l_padrao",          adVarchar, adParamInput,   1, Tvl(p_padrao))
     set l_aprovado             = .CreateParameter("l_aprovado",        adNumeric ,adParamInput)
     l_aprovado.Precision       = 18
     l_aprovado.NumericScale    = 2
     l_aprovado.Value           = Tvl(p_aprovado)
     set l_saldo                = .CreateParameter("l_saldo",           adNumeric ,adParamInput)
     l_saldo.Precision          = 18
     l_saldo.NumericScale       = 2
     l_saldo.Value              = Tvl(p_saldo)
     set l_empenhado            = .CreateParameter("l_empenhado",       adNumeric ,adParamInput)
     l_empenhado.Precision      = 18
     l_empenhado.NumericScale   = 2
     l_empenhado.Value          = Tvl(p_empenhado)
     set l_liquidado            = .CreateParameter("l_liquidado",       adNumeric ,adParamInput)
     l_liquidado.Precision      = 18
     l_liquidado.NumericScale   = 2
     l_liquidado.Value          = Tvl(p_liquidado)
     set l_liquidar             = .CreateParameter("l_liquidar",        adNumeric ,adParamInput)
     l_liquidar.Precision       = 18
     l_liquidar.NumericScale    = 2
     l_liquidar.Value           = Tvl(p_liquidar)
     set l_selecionada_mpog     = .CreateParameter("l_selecionada_mpog",        adVarchar, adParamInput,   1, Tvl(p_selecionada_mpog))
     set l_selecionada_relevante= .CreateParameter("l_selecionada_relevante",   adVarchar, adParamInput,   1, Tvl(p_selecionada_relevante))
     set l_cod_programa         = .CreateParameter("l_cod_programa",            adVarchar, adParamInput,  50, Tvl(p_cod_programa))
     set l_cod_acao             = .CreateParameter("l_cod_acao",                adVarchar, adParamInput,  50, Tvl(p_cod_acao))
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_acao_ppa_pai
     .parameters.Append         l_codigo
     .parameters.Append         l_nome
     .parameters.Append         l_responsavel
     .parameters.Append         l_telefone
     .parameters.Append         l_email
     .parameters.Append         l_ativo
     .parameters.Append         l_padrao
     .parameters.Append         l_aprovado
     .parameters.Append         l_saldo
     .parameters.Append         l_empenhado
     .parameters.Append         l_liquidado
     .parameters.Append         l_liquidar
     .parameters.Append         l_selecionada_mpog
     .parameters.Append         l_selecionada_relevante
     .parameters.Append         l_cod_programa
     .parameters.Append         l_cod_acao

     .CommandText               = Session("schema") & "SP_PutAcaoPPA"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_sq_acao_ppa_pai"
     .parameters.Delete         "l_codigo"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_responsavel"
     .parameters.Delete         "l_telefone"
     .parameters.Delete         "l_email"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_aprovado"
     .parameters.Delete         "l_saldo"
     .parameters.Delete         "l_empenhado"
     .parameters.Delete         "l_liquidado"
     .parameters.Delete         "l_liquidar"
     .parameters.Delete         "l_selecionada_mpog"
     .parameters.Delete         "l_selecionada_relevante"
     .Parameters.Delete         "l_cod_programa"
     .Parameters.Delete         "l_cod_acao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm a tabela de iniciativas prioritсrias
REM -------------------------------------------------------------------------
Sub DML_PutOrPrioridade(Operacao, p_chave, p_cliente, p_codigo, p_nome, _
       p_responsavel, p_telefone, p_email, p_ordem, p_ativo, p_padrao)


  Dim l_Operacao, l_Chave, l_cliente, l_codigo, l_nome
  Dim l_responsavel, l_telefone, l_email, l_ordem, l_ativo, l_padrao
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_codigo                  = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_responsavel             = Server.CreateObject("ADODB.Parameter") 
  Set l_telefone                = Server.CreateObject("ADODB.Parameter") 
  Set l_email                   = Server.CreateObject("ADODB.Parameter") 
  Set l_ordem                   = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  Set l_padrao                  = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,    , Tvl(p_cliente))
     set l_codigo               = .CreateParameter("l_codigo",              adVarchar, adParamInput,  60, Tvl(p_codigo))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput, 100, Tvl(p_nome))
     set l_responsavel          = .CreateParameter("l_responsavel",         adVarchar, adParamInput,  60, Tvl(p_responsavel))
     set l_telefone             = .CreateParameter("l_telefone",            adVarchar, adParamInput,  20, Tvl(p_telefone))
     set l_email                = .CreateParameter("l_email",               adVarchar, adParamInput,  60, Tvl(p_email))
     set l_ordem                = .CreateParameter("l_ordem",               adInteger, adParamInput,    , Tvl(p_ordem))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,   1, Tvl(p_ativo))
     set l_padrao               = .CreateParameter("l_padrao",              adVarchar, adParamInput,   1, Tvl(p_padrao))
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_codigo
     .parameters.Append         l_nome
     .parameters.Append         l_responsavel
     .parameters.Append         l_telefone
     .parameters.Append         l_email
     .parameters.Append         l_ordem
     .parameters.Append         l_ativo
     .parameters.Append         l_padrao

     .CommandText               = Session("schema") & "SP_PutOrPrioridade"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_codigo"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_responsavel"
     .parameters.Delete         "l_telefone"
     .parameters.Delete         "l_ordem"
     .parameters.Delete         "l_email"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_padrao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Atualiza os responsaveis e seus dados na Aчуo do PPA, Aчao e Iniciativa
REM -------------------------------------------------------------------------

Sub DML_PutRespAcao(p_chave, p_responsavel, p_telefone, p_email, p_tipo)

  Dim l_Chave, l_Responsavel, l_Telefone, l_Email, l_Tipo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_responsavel             = Server.CreateObject("ADODB.Parameter") 
  Set l_telefone                = Server.CreateObject("ADODB.Parameter") 
  Set l_email                   = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo                    = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_chave                = .CreateParameter("l_chave",                   adInteger, adParamInput,    , Tvl(p_chave))
     set l_responsavel          = .CreateParameter("l_responsavel",             adVarchar, adParamInput,  60, Tvl(p_responsavel))
     set l_telefone             = .CreateParameter("l_telefone",                adVarchar, adParamInput,  20, Tvl(p_telefone))
     set l_email                = .CreateParameter("l_email",                   adVarchar, adParamInput,  60, Tvl(p_email))
     set l_tipo                 = .CreateParameter("l_tipo",                    adInteger, adParamInput,    , Tvl(p_tipo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_responsavel
     .parameters.Append         l_telefone
     .parameters.Append         l_email
     .parameters.Append         l_tipo

     .CommandText               = Session("schema") & "SP_PutRespAcao"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_responsavel"
     .parameters.Delete         "l_telefone"
     .parameters.Delete         "l_email"
     .parameters.Delete         "l_tipo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>