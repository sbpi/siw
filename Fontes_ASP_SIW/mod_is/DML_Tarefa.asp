<%
REM =========================================================================
REM Mantém a tabela principal de demandas
REM -------------------------------------------------------------------------
Sub DML_PutTarefaGeral(Operacao, p_chave, p_menu, p_unidade, p_solicitante, p_proponente, _
    p_cadastrador, p_executor, p_descricao, p_justificativa, p_ordem, p_inicio, p_fim, p_valor, _
    p_data_hora, p_unid_resp, p_titulo, p_assunto, p_prioridade, p_aviso, p_dias, p_cidade, p_palavra_chave, p_inicio_real, _
    p_fim_real, p_concluida, p_data_conclusao, p_nota_conclusao, p_custo_real, p_opiniao, _
    p_projeto, p_atividade, p_projeto_ant, p_atividade_ant, p_chave_nova, p_copia)
    
  Dim l_Operacao, l_Chave, l_menu, l_unidade, l_solicitante, l_proponente
  Dim l_cadastrador, l_executor, l_descricao, l_justificativa, l_ordem, l_inicio, l_fim, l_valor
  Dim l_data_hora, l_unid_resp, l_titulo, l_assunto, l_prioridade, l_aviso, l_dias, l_cidade, l_palavra_chave, l_inicio_real
  Dim l_fim_real, l_concluida, l_data_conclusao, l_nota_conclusao, l_custo_real, l_opiniao, l_chave_nova
  Dim l_projeto, l_atividade, l_projeto_ant, l_atividade_ant, l_copia
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_copia               = Server.CreateObject("ADODB.Parameter") 
  Set l_menu                = Server.CreateObject("ADODB.Parameter") 
  Set l_unidade             = Server.CreateObject("ADODB.Parameter") 
  Set l_solicitante         = Server.CreateObject("ADODB.Parameter") 
  Set l_proponente          = Server.CreateObject("ADODB.Parameter") 
  Set l_cadastrador         = Server.CreateObject("ADODB.Parameter") 
  Set l_executor            = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter")  
  Set l_justificativa       = Server.CreateObject("ADODB.Parameter") 
  Set l_ordem               = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio              = Server.CreateObject("ADODB.Parameter") 
  Set l_fim                 = Server.CreateObject("ADODB.Parameter") 
  Set l_valor               = Server.CreateObject("ADODB.Parameter") 
  Set l_data_hora           = Server.CreateObject("ADODB.Parameter") 
  Set l_unid_resp           = Server.CreateObject("ADODB.Parameter") 
  Set l_titulo              = Server.CreateObject("ADODB.Parameter") 
  Set l_assunto             = Server.CreateObject("ADODB.Parameter") 
  Set l_prioridade          = Server.CreateObject("ADODB.Parameter") 
  Set l_aviso               = Server.CreateObject("ADODB.Parameter") 
  Set l_dias                = Server.CreateObject("ADODB.Parameter") 
  Set l_cidade              = Server.CreateObject("ADODB.Parameter") 
  Set l_palavra_chave       = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio_real         = Server.CreateObject("ADODB.Parameter") 
  Set l_fim_real            = Server.CreateObject("ADODB.Parameter") 
  Set l_concluida           = Server.CreateObject("ADODB.Parameter") 
  Set l_data_conclusao      = Server.CreateObject("ADODB.Parameter") 
  Set l_nota_conclusao      = Server.CreateObject("ADODB.Parameter") 
  Set l_custo_real          = Server.CreateObject("ADODB.Parameter") 
  Set l_opiniao             = Server.CreateObject("ADODB.Parameter") 
  Set l_projeto             = Server.CreateObject("ADODB.Parameter") 
  Set l_atividade           = Server.CreateObject("ADODB.Parameter") 
  Set l_projeto_ant         = Server.CreateObject("ADODB.Parameter") 
  Set l_atividade_ant       = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_nova          = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_copia                = .CreateParameter("l_copia",           adInteger, adParamInput,    , Tvl(p_copia))
     set l_menu                 = .CreateParameter("l_menu",            adInteger, adParamInput,    , p_menu)
     set l_unidade              = .CreateParameter("l_unidade",         adInteger, adParamInput,    , Tvl(p_unidade))
     set l_solicitante          = .CreateParameter("l_solicitante",     adInteger, adParamInput,    , Tvl(p_solicitante))
     set l_proponente           = .CreateParameter("l_proponente",      adVarchar, adParamInput,  90, Tvl(p_proponente))
     set l_cadastrador          = .CreateParameter("l_cadastrador",     adInteger, adParamInput,    , Tvl(p_cadastrador))
     set l_executor             = .CreateParameter("l_executor",        adInteger, adParamInput,    , Tvl(p_executor))
     set l_descricao            = .CreateParameter("l_descricao",       adVarchar, adParamInput,2000, Tvl(p_descricao))
     set l_justificativa        = .CreateParameter("l_justificativa",   adVarchar, adParamInput,2000, Tvl(p_justificativa))
     set l_ordem                = .CreateParameter("l_ordem",           adInteger, adParamInput,   3, Tvl(p_ordem))
     set l_inicio               = .CreateParameter("l_inicio",          adDate,    adParamInput,    , Tvl(p_inicio))
     set l_fim                  = .CreateParameter("l_fim",             adDate,    adParamInput,    , Tvl(p_fim))
     set l_valor                = .CreateParameter("l_valor",           adNumeric ,adParamInput)
     l_valor.Precision    = 18
     l_valor.NumericScale = 2
     l_valor.Value        = Tvl(p_valor)
     set l_data_hora            = .CreateParameter("l_data_hora",       adVarchar, adParamInput,   1, Tvl(p_data_hora))
     set l_unid_resp            = .CreateParameter("l_unid_resp",       adInteger, adParamInput,    , Tvl(p_unid_resp))
     set l_titulo               = .CreateParameter("l_titulo",          adVarchar, adParamInput, 100, Tvl(p_titulo))     
     set l_assunto              = .CreateParameter("l_assunto",         adVarchar, adParamInput,2000, Tvl(p_assunto))
     set l_prioridade           = .CreateParameter("l_prioridade",      adInteger, adParamInput,    , Tvl(p_prioridade))
     set l_aviso                = .CreateParameter("l_aviso",           adVarchar, adParamInput,   1, Tvl(p_aviso))
     set l_dias                 = .CreateParameter("l_dias",            adInteger, adParamInput,    , Nvl(p_dias,0))
     set l_cidade               = .CreateParameter("l_cidade",          adInteger, adParamInput,    , Tvl(p_cidade))
     set l_palavra_chave        = .CreateParameter("l_palavra_chave",   adVarchar, adParamInput,  90, Tvl(p_palavra_chave))
     set l_inicio_real          = .CreateParameter("l_inicio_real",     adDate,    adParamInput,    , Tvl(p_inicio_real))
     set l_fim_real             = .CreateParameter("l_fim_real",        adDate,    adParamInput,    , Tvl(p_fim_real))
     set l_concluida            = .CreateParameter("l_concluida",       adVarchar, adParamInput,   1, Tvl(p_concluida))
     set l_data_conclusao       = .CreateParameter("l_data_conclusao",  adDate,    adParamInput,    , Tvl(p_data_conclusao))
     set l_nota_conclusao       = .CreateParameter("l_nota_conclusao",  adVarchar, adParamInput,2000, Tvl(p_nota_conclusao))
     set l_custo_real           = .CreateParameter("l_custo_real",      adNumeric ,adParamInput)
     l_custo_real.Precision    = 18
     l_custo_real.NumericScale = 2
     l_custo_real.Value        = Tvl(p_custo_real)
     set l_opiniao              = .CreateParameter("l_opiniao",         adInteger, adParamInput,    , Tvl(p_opiniao))
     set l_projeto              = .CreateParameter("l_projeto",         adInteger, adParamInput,    , Tvl(p_projeto))
     set l_atividade            = .CreateParameter("l_atividade",       adInteger, adParamInput,    , Tvl(p_atividade))
     set l_projeto_ant          = .CreateParameter("l_projeto_ant",     adInteger, adParamInput,    , Tvl(p_projeto_ant))
     set l_atividade_ant        = .CreateParameter("l_atividade_ant",   adInteger, adParamInput,    , Tvl(p_atividade_ant))
     set l_chave_nova           = .CreateParameter("l_chave_nova",      adInteger, adParamOutput,   , null)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_copia
     .parameters.Append         l_menu
     .parameters.Append         l_unidade
     .parameters.Append         l_solicitante
     .parameters.Append         l_proponente
     .parameters.Append         l_cadastrador
     .parameters.Append         l_executor
     .parameters.Append         l_descricao
     .parameters.Append         l_justificativa
     .parameters.Append         l_ordem
     .parameters.Append         l_inicio
     .parameters.Append         l_fim
     .parameters.Append         l_valor
     .parameters.Append         l_data_hora
     .parameters.Append         l_unid_resp
     .parameters.Append         l_titulo     
     .parameters.Append         l_assunto
     .parameters.Append         l_prioridade
     .parameters.Append         l_aviso
     .parameters.Append         l_dias
     .parameters.Append         l_cidade
     .parameters.Append         l_palavra_chave
     .parameters.Append         l_inicio_real
     .parameters.Append         l_fim_real
     .parameters.Append         l_concluida
     .parameters.Append         l_data_conclusao
     .parameters.Append         l_nota_conclusao
     .parameters.Append         l_custo_real
     .parameters.Append         l_opiniao
     .parameters.Append         l_projeto
     .parameters.Append         l_atividade
     .parameters.Append         l_projeto_ant
     .parameters.Append         l_atividade_ant
     .parameters.Append         l_chave_nova
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_PutTarefaGeral_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     p_chave_nova = l_chave_nova.Value
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_copia"
     .parameters.Delete         "l_menu"
     .parameters.Delete         "l_unidade"
     .parameters.Delete         "l_solicitante"
     .parameters.Delete         "l_proponente"
     .parameters.Delete         "l_cadastrador"
     .parameters.Delete         "l_executor"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_justificativa"
     .parameters.Delete         "l_ordem"
     .parameters.Delete         "l_inicio"
     .parameters.Delete         "l_fim"
     .parameters.Delete         "l_valor"
     .parameters.Delete         "l_data_hora"
     .parameters.Delete         "l_unid_resp"
     .parameters.Delete         "l_titulo"     
     .parameters.Delete         "l_assunto"
     .parameters.Delete         "l_prioridade"
     .parameters.Delete         "l_aviso"
     .parameters.Delete         "l_dias"
     .parameters.Delete         "l_cidade"
     .parameters.Delete         "l_palavra_chave"
     .parameters.Delete         "l_inicio_real"
     .parameters.Delete         "l_fim_real"
     .parameters.Delete         "l_concluida"
     .parameters.Delete         "l_data_conclusao"
     .parameters.Delete         "l_nota_conclusao"
     .parameters.Delete         "l_custo_real"
     .parameters.Delete         "l_opiniao"
     .parameters.Delete         "l_projeto"
     .parameters.Delete         "l_atividade"
     .parameters.Delete         "l_projeto_ant"
     .parameters.Delete         "l_atividade_ant"
     .parameters.Delete         "l_chave_nova"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Atualiza os responsaveis da tarefa e seus dados
REM -------------------------------------------------------------------------
Sub DML_PutRespTarefa_IS (p_chave, _
                          p_nm_responsavel, p_fn_responsavel, p_em_responsavel)

  Dim l_chave
  Dim l_nm_responsavel, l_fn_responsavel, l_em_responsavel
  
  Set l_chave              = Server.CreateObject("ADODB.Parameter") 
  Set l_nm_responsavel     = Server.CreateObject("ADODB.Parameter") 
  Set l_fn_responsavel     = Server.CreateObject("ADODB.Parameter") 
  Set l_em_responsavel     = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave            = .CreateParameter("l_chave",              adInteger, adParamInput,    , Tvl(p_chave))
     set l_nm_responsavel   = .CreateParameter("l_nm_responsavel",     adVarchar, adParamInput,  60, Tvl(p_nm_responsavel))
     set l_fn_responsavel   = .CreateParameter("l_fn_responsavel",     adVarchar, adParamInput,  20, Tvl(p_fn_responsavel))
     set l_em_responsavel   = .CreateParameter("l_em_responsavel",     adVarchar, adParamInput,  60, Tvl(p_em_responsavel))
  
     .parameters.Append         l_chave
     .parameters.Append         l_nm_responsavel
     .parameters.Append         l_fn_responsavel
     .parameters.Append         l_em_responsavel
     
     .CommandText               = Session("schema_is") & "SP_PutRespTarefa_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_nm_responsavel"
     .parameters.Delete         "l_fn_responsavel"
     .parameters.Delete         "l_em_responsavel"
  end with
End Sub

REM =========================================================================
REM Atualiza o limite orçamentário da tarefa
REM -------------------------------------------------------------------------
Sub DML_PutTarefaLimite (p_chave, p_pessoa, p_tramite, p_custo_real)

  Dim l_chave, l_pessoa, l_tramite, l_custo_real
  
  Set l_chave              = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa             = Server.CreateObject("ADODB.Parameter") 
  Set l_tramite            = Server.CreateObject("ADODB.Parameter") 
  Set l_custo_real         = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave            = .CreateParameter("l_chave",              adInteger, adParamInput,    , Tvl(p_chave))
     set l_pessoa           = .CreateParameter("l_pessoa",             adInteger, adParamInput,    , Tvl(p_pessoa))
     set l_tramite          = .CreateParameter("l_tramite",            adInteger, adParamInput,    , Tvl(p_tramite))
     set l_custo_real       = .CreateParameter("l_custo_real",         adNumeric ,adParamInput)
     l_custo_real.Precision    = 18
     l_custo_real.NumericScale = 2
     l_custo_real.Value        = Tvl(p_custo_real)
  
     .parameters.Append         l_chave
     .parameters.Append         l_pessoa
     .parameters.Append         l_tramite
     .parameters.Append         l_custo_real
     
     .CommandText               = Session("schema_is") & "SP_PutTarefaLimite"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_pessoa"
     .parameters.Delete         "l_tramite"
     .parameters.Delete         "l_custo_real"
  end with
End Sub
%>