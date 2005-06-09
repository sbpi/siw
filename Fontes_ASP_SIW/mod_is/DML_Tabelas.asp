<%
REM =========================================================================
REM Mantém os dados do SIAFI na tabela de ações do SIGPLAN
REM -------------------------------------------------------------------------
Sub DML_PutDadosAcaoPPA_IS(p_cliente, p_ano, p_unidade, p_programa, p_acao, p_subacao, _
                           p_aprovado, p_empenhado, p_liquidado)


  Dim l_cliente, l_ano, l_unidade, l_programa, l_acao, l_subacao
  Dim l_aprovado, l_empenhado, l_liquidado
  
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter")   
  Set l_ano                     = Server.CreateObject("ADODB.Parameter")
  Set l_unidade                 = Server.CreateObject("ADODB.Parameter")  
  Set l_programa                = Server.CreateObject("ADODB.Parameter") 
  Set l_acao                    = Server.CreateObject("ADODB.Parameter") 
  Set l_subacao                 = Server.CreateObject("ADODB.Parameter") 
  Set l_aprovado                = Server.CreateObject("ADODB.Parameter") 
  Set l_empenhado               = Server.CreateObject("ADODB.Parameter") 
  Set l_liquidado               = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_cliente              = .CreateParameter("l_cliente",         adInteger, adParamInput,    , Tvl(p_cliente))
     set l_ano                  = .CreateParameter("l_ano",             adInteger, adParamInput,    , Tvl(p_ano))
     set l_unidade              = .CreateParameter("l_unidade",         adVarchar, adParamInput,   5, Tvl(p_unidade))
     set l_programa             = .CreateParameter("l_programa",        adVarchar, adParamInput,   4, Tvl(p_programa))
     set l_acao                 = .CreateParameter("l_acao",            adVarchar, adParamInput,   4, Tvl(p_acao))
     set l_subacao              = .CreateParameter("l_subacao",         adVarchar, adParamInput,   4, Tvl(p_subacao))
     set l_aprovado             = .CreateParameter("l_aprovado",        adNumeric ,adParamInput)
     l_aprovado.Precision       = 18
     l_aprovado.NumericScale    = 2
     l_aprovado.Value           = Tvl(p_aprovado)
     set l_empenhado            = .CreateParameter("l_empenhado",       adNumeric ,adParamInput)
     l_empenhado.Precision      = 18
     l_empenhado.NumericScale   = 2
     l_empenhado.Value          = Tvl(p_empenhado)
     set l_liquidado            = .CreateParameter("l_liquidado",       adNumeric ,adParamInput)
     l_liquidado.Precision      = 18
     l_liquidado.NumericScale   = 2
     l_liquidado.Value          = Tvl(p_liquidado)
  
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_unidade
     .parameters.Append         l_programa
     .parameters.Append         l_acao
     .parameters.Append         l_subacao
     .parameters.Append         l_aprovado
     .parameters.Append         l_empenhado
     .parameters.Append         l_liquidado

     .CommandText               = Session("schema_is") & "SP_PutDadosAcaoPPA_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_unidade"
     .parameters.Delete         "l_programa"
     .parameters.Delete         "l_acao"
     .parameters.Delete         "l_subacao"
     .parameters.Delete         "l_aprovado"
     .parameters.Delete         "l_empenhado"
     .parameters.Delete         "l_liquidado"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de natureza do programa
REM -------------------------------------------------------------------------
Sub DML_PutNatureza_IS(Operacao, p_chave, p_cliente, p_nome, p_ativo)


  Dim l_Operacao, l_Chave, l_cliente, l_nome, l_ativo
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,  1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,   , Tvl(p_cliente))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput, 30, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutNatureza_IS"
     On error Resume Next
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
REM Mantém a tabela de Horizonte do programa
REM -------------------------------------------------------------------------
Sub DML_PutHorizonte_IS(Operacao, p_chave, p_cliente, p_nome, p_ativo)


  Dim l_Operacao, l_Chave, l_cliente, l_nome, l_ativo
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo                   = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,  1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,   , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,   , Tvl(p_cliente))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput, 30, Tvl(p_nome))
     set l_ativo                = .CreateParameter("l_ativo",               adVarchar, adParamInput,  1, Tvl(p_ativo))
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
  
     .CommandText               = Session("schema_is") & "SP_PutHorizonte_IS"
     On error Resume Next
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
REM Mantém a tabela de plano/projetos específicos
REM -------------------------------------------------------------------------
Sub DML_PutProjeto_IS(Operacao, p_chave, p_cliente, p_codigo, p_nome, p_responsavel, p_telefone, p_email, p_ordem, p_ativo, p_padrao)


  Dim l_Operacao, l_chave, l_cliente, l_codigo, l_nome, l_responsavel, l_telefone, l_email, l_ordem, l_ativo, l_padrao
  
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_codigo      = Server.CreateObject("ADODB.Parameter")
  Set l_nome        = Server.CreateObject("ADODB.Parameter")
  Set l_responsavel = Server.CreateObject("ADODB.Parameter")
  Set l_telefone    = Server.CreateObject("ADODB.Parameter")
  Set l_email       = Server.CreateObject("ADODB.Parameter")  
  Set l_ordem       = Server.CreateObject("ADODB.Parameter")
  Set l_ativo       = Server.CreateObject("ADODB.Parameter")
  Set l_padrao      = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao    = .CreateParameter("l_operacao"   , adVarchar, adParamInput,   1, Operacao)
     set l_chave       = .CreateParameter("l_chave"      , adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente     = .CreateParameter("l_cliente"    , adInteger, adParamInput,    , Tvl(p_cliente))
     set l_codigo      = .CreateParameter("l_codigo"     , adVarchar, adParamInput,  50, Tvl(p_codigo))
     set l_nome        = .CreateParameter("l_nome"       , adVarchar, adParamInput, 100, Tvl(p_nome))
     set l_responsavel = .CreateParameter("l_responsavel", adVarchar, adParamInput,  60, Tvl(p_responsavel))
     set l_telefone    = .CreateParameter("l_telefone"   , adVarchar, adParamInput,  20, Tvl(p_telefone))
     set l_email       = .CreateParameter("l_email"      , adVarchar, adParamInput,  60, Tvl(p_email))
     set l_ordem       = .CreateParameter("l_ordem"      , adInteger, adParamInput,    , Tvl(p_ordem))
     set l_ativo       = .CreateParameter("l_ativo"      , adVarchar, adParamInput,   1, Tvl(p_ativo))
     set l_padrao      = .CreateParameter("l_padrao"     , adVarchar, adParamInput,   1, Tvl(p_padrao))
  
     .parameters.Append l_operacao
     .parameters.Append l_chave
     .parameters.Append l_cliente
     .parameters.Append l_codigo
     .parameters.Append l_nome
     .parameters.Append l_responsavel
     .parameters.Append l_telefone
     .parameters.Append l_email
     .parameters.Append l_ordem
     .parameters.Append l_ativo
     .parameters.Append l_padrao
  
     .CommandText               = Session("schema_is") & "SP_PutProjeto_IS"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete "l_chave"
     .parameters.Delete "l_cliente"
     .parameters.Delete "l_codigo"
     .parameters.Delete "l_nome"
     .parameters.Delete "l_responsavel"
     .parameters.Delete "l_telefone"
     .parameters.Delete "l_email"
     .parameters.Delete "l_ordem"
     .parameters.Delete "l_ativo"
     .parameters.Delete "l_padrao"
     
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém as unidade do módulo infra-sig
REM -------------------------------------------------------------------------
Sub DML_PutIsUnidade_IS(Operacao, p_chave, p_administrativa, p_planejamento)

  Dim l_Operacao, l_Chave, l_administrativa, l_planejamento
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_administrativa          = Server.CreateObject("ADODB.Parameter") 
  Set l_planejamento            = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , p_chave)
     set l_administrativa       = .CreateParameter("l_administrativa",      adVarchar, adParamInput,   1, p_administrativa)
     set l_planejamento         = .CreateParameter("l_planejamento",        adVarchar, adParamInput,   1, p_planejamento)
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_administrativa
     .parameters.Append         l_planejamento

     .CommandText               = Session("schema_is") & "SP_PutIsUnidade_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_administrativa"
     .parameters.Delete         "l_planejamento"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>