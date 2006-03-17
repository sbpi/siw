<%
REM =========================================================================
REM Mantщm a tabela principal de demandas
REM -------------------------------------------------------------------------
Sub DML_PutDemandaGeral(Operacao, p_chave, p_menu, p_unidade, p_solicitante, p_proponente, _
    p_cadastrador, p_executor, p_sqcc, p_descricao, p_justificativa, p_ordem, p_inicio, p_fim, p_valor, _
    p_data_hora, p_unid_resp, p_assunto, p_prioridade, p_aviso, p_dias, p_cidade, p_palavra_chave, p_inicio_real, _
    p_fim_real, p_concluida, p_data_conclusao, p_nota_conclusao, p_custo_real, p_opiniao, _
    p_projeto, p_atividade, p_projeto_ant, p_atividade_ant, p_chave_nova, p_copia)
    
  Dim l_Operacao, l_Chave, l_menu, l_unidade, l_solicitante, l_proponente
  Dim l_cadastrador, l_executor, l_sqcc, l_descricao, l_justificativa, l_ordem, l_inicio, l_fim, l_valor
  Dim l_data_hora, l_unid_resp, l_assunto, l_prioridade, l_aviso, l_dias, l_cidade, l_palavra_chave, l_inicio_real
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
  Set l_sqcc                = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  Set l_justificativa       = Server.CreateObject("ADODB.Parameter") 
  Set l_ordem               = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio              = Server.CreateObject("ADODB.Parameter") 
  Set l_fim                 = Server.CreateObject("ADODB.Parameter") 
  Set l_valor               = Server.CreateObject("ADODB.Parameter") 
  Set l_data_hora           = Server.CreateObject("ADODB.Parameter") 
  Set l_unid_resp           = Server.CreateObject("ADODB.Parameter") 
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
     set l_sqcc                 = .CreateParameter("l_sqcc",            adInteger, adParamInput,    , Tvl(p_sqcc))
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
     .parameters.Append         l_sqcc
     .parameters.Append         l_descricao
     .parameters.Append         l_justificativa
     .parameters.Append         l_ordem
     .parameters.Append         l_inicio
     .parameters.Append         l_fim
     .parameters.Append         l_valor
     .parameters.Append         l_data_hora
     .parameters.Append         l_unid_resp
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
     .CommandText               = Session("schema") & "SP_PutDemandaGeral"
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
     .parameters.Delete         "l_sqcc"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_justificativa"
     .parameters.Delete         "l_ordem"
     .parameters.Delete         "l_inicio"
     .parameters.Delete         "l_fim"
     .parameters.Delete         "l_valor"
     .parameters.Delete         "l_data_hora"
     .parameters.Delete         "l_unid_resp"
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
REM Mantщm a tabela de interessados em uma demanda
REM -------------------------------------------------------------------------
Sub DML_PutDemandaInter(Operacao, p_chave, p_chave_aux, p_tipo_visao, p_envia_email)
  Dim l_Operacao, l_chave, l_chave_aux, l_tipo_visao, l_envia_email
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_visao          = Server.CreateObject("ADODB.Parameter") 
  Set l_envia_email         = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",       adInteger, adParamInput,    , p_chave_aux)
     set l_tipo_visao           = .CreateParameter("l_tipo_visao",      adInteger, adParamInput,    , p_tipo_visao)
     set l_envia_email          = .CreateParameter("l_envia_email",     adVarchar, adParamInput,   1, p_envia_email)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_tipo_visao
     .parameters.Append         l_envia_email
     .CommandText               = Session("schema") & "SP_PutDemandaInter"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_tipo_visao"
     .parameters.Delete         "l_envia_email"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm a tabela de сreas envolvidas na execuчуo de uma demanda
REM -------------------------------------------------------------------------
Sub DML_PutDemandaAreas(Operacao, p_chave, p_chave_aux, p_papel)
  Dim l_Operacao, l_chave, l_chave_aux, l_papel
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_papel               = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",       adInteger, adParamInput,    , p_chave_aux)
     set l_papel                = .CreateParameter("l_papel",           adVarchar, adParamInput,2000, p_papel)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_papel
     .CommandText               = Session("schema") & "SP_PutDemandaAreas"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_papel"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Encaminha a demanda
REM -------------------------------------------------------------------------
Sub DML_PutDemandaEnvio(p_menu, p_chave, p_pessoa, p_tramite, p_novo_tramite, p_devolucao, p_observacao, p_destinatario, p_despacho, _
        p_caminho, p_tamanho, P_tipo, p_nome_original)
  Dim l_Operacao, l_menu, l_chave, l_pessoa, l_tramite, l_novo_tramite, l_devolucao, l_observacao, l_destinatario, l_despacho
  Dim l_caminho, l_tamanho, l_tipo, l_nome_original
  
  Set l_menu                = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa              = Server.CreateObject("ADODB.Parameter") 
  Set l_tramite             = Server.CreateObject("ADODB.Parameter") 
  Set l_novo_tramite        = Server.CreateObject("ADODB.Parameter") 
  Set l_devolucao           = Server.CreateObject("ADODB.Parameter") 
  Set l_observacao          = Server.CreateObject("ADODB.Parameter") 
  Set l_destinatario        = Server.CreateObject("ADODB.Parameter") 
  Set l_despacho            = Server.CreateObject("ADODB.Parameter") 
  Set l_caminho             = Server.CreateObject("ADODB.Parameter") 
  Set l_tamanho             = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo                = Server.CreateObject("ADODB.Parameter")
  Set l_nome_original       = Server.CreateObject("ADODB.Parameter")  
  with sp
     set l_menu                 = .CreateParameter("l_menu",            adInteger, adParamInput,    , p_menu)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , p_chave)
     set l_pessoa               = .CreateParameter("l_pessoa",          adInteger, adParamInput,    , p_pessoa)
     set l_tramite              = .CreateParameter("l_tramite",         adInteger, adParamInput,    , p_tramite)
     set l_novo_tramite         = .CreateParameter("l_novo_tramite",    adInteger, adParamInput,    , Tvl(p_novo_tramite))
     set l_devolucao            = .CreateParameter("l_devolucao",       adVarchar, adParamInput,   1, p_devolucao)
     set l_observacao           = .CreateParameter("l_observacao",      adVarchar, adParamInput,2000, p_observacao)
     set l_destinatario         = .CreateParameter("l_destinatario",    adInteger, adParamInput,    , Tvl(p_destinatario))
     set l_despacho             = .CreateParameter("l_despacho",        adVarchar, adParamInput,2000, p_despacho)
     set l_caminho              = .CreateParameter("l_caminho",         adVarchar, adParamInput, 255, Tvl(p_caminho))
     set l_tamanho              = .CreateParameter("l_tamanho",         adInteger, adParamInput,    , Tvl(p_tamanho))
     set l_tipo                 = .CreateParameter("l_tipo",            adVarchar, adParamInput,  60, Tvl(p_tipo))
     set l_nome_original        = .CreateParameter("l_nome_original",   adVarchar, adParamInput, 255, Tvl(p_nome_original))
     .parameters.Append         l_menu
     .parameters.Append         l_chave
     .parameters.Append         l_pessoa
     .parameters.Append         l_tramite
     .parameters.Append         l_novo_tramite
     .parameters.Append         l_devolucao
     .parameters.Append         l_observacao
     .parameters.Append         l_destinatario
     .parameters.Append         l_despacho
     .parameters.Append         l_caminho
     .parameters.Append         l_tamanho
     .parameters.Append         l_tipo
     .parameters.Append         l_nome_original
     .CommandText               = Session("schema") & "SP_PutDemandaEnvio"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_menu"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_pessoa"
     .parameters.Delete         "l_tramite"
     .parameters.Delete         "l_novo_tramite"
     .parameters.Delete         "l_devolucao"
     .parameters.Delete         "l_observacao"
     .parameters.Delete         "l_destinatario"
     .parameters.Delete         "l_despacho"
     .parameters.Delete         "l_caminho"
     .parameters.Delete         "l_tamanho"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_nome_original"
  end with
End Sub

REM =========================================================================
REM Conclui a demanda
REM -------------------------------------------------------------------------
Sub DML_PutDemandaConc(p_menu, p_chave, p_pessoa, p_tramite, p_inicio_real, p_fim_real, p_nota_conclusao, p_custo_real, _
        p_caminho, p_tamanho, P_tipo, p_nome_original)
  Dim l_menu, l_chave, l_pessoa, l_tramite
  Dim l_inicio_real, l_fim_real, l_nota_conclusao, l_custo_real
  Dim l_caminho, l_tamanho, l_tipo, l_nome_original
  
  Set l_menu                = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa              = Server.CreateObject("ADODB.Parameter") 
  Set l_tramite             = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio_real         = Server.CreateObject("ADODB.Parameter") 
  Set l_fim_real            = Server.CreateObject("ADODB.Parameter") 
  Set l_nota_conclusao      = Server.CreateObject("ADODB.Parameter") 
  Set l_custo_real          = Server.CreateObject("ADODB.Parameter") 
  Set l_caminho             = Server.CreateObject("ADODB.Parameter") 
  Set l_tamanho             = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo                = Server.CreateObject("ADODB.Parameter")
  Set l_nome_original       = Server.CreateObject("ADODB.Parameter")  
  with sp
     set l_menu                 = .CreateParameter("l_menu",            adInteger, adParamInput,    , p_menu)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , p_chave)
     set l_pessoa               = .CreateParameter("l_pessoa",          adInteger, adParamInput,    , p_pessoa)
     set l_tramite              = .CreateParameter("l_tramite",         adInteger, adParamInput,    , p_tramite)
     set l_inicio_real          = .CreateParameter("l_inicio_real",     adDate,    adParamInput,    , Tvl(p_inicio_real))
     set l_fim_real             = .CreateParameter("l_fim_real",        adDate,    adParamInput,    , Tvl(p_fim_real))
     set l_nota_conclusao       = .CreateParameter("l_nota_conclusao",  adVarchar, adParamInput,2000, Tvl(p_nota_conclusao))
     set l_custo_real           = .CreateParameter("l_custo_real",      adNumeric ,adParamInput)
     l_custo_real.Precision    = 18
     l_custo_real.NumericScale = 2
     l_custo_real.Value        = Tvl(p_custo_real)
     set l_caminho              = .CreateParameter("l_caminho",         adVarchar, adParamInput, 255, Tvl(p_caminho))
     set l_tamanho              = .CreateParameter("l_tamanho",         adInteger, adParamInput,    , Tvl(p_tamanho))
     set l_tipo                 = .CreateParameter("l_tipo",            adVarchar, adParamInput,  60, Tvl(p_tipo))
     set l_nome_original        = .CreateParameter("l_nome_original",   adVarchar, adParamInput, 255, Tvl(p_nome_original))
     .parameters.Append         l_menu
     .parameters.Append         l_chave
     .parameters.Append         l_pessoa
     .parameters.Append         l_tramite
     .parameters.Append         l_inicio_real
     .parameters.Append         l_fim_real
     .parameters.Append         l_nota_conclusao
     .parameters.Append         l_custo_real
     .parameters.Append         l_caminho
     .parameters.Append         l_tamanho
     .parameters.Append         l_tipo
     .parameters.Append         l_nome_original
     .CommandText               = Session("schema") & "SP_PutDemandaConc"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_menu"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_pessoa"
     .parameters.Delete         "l_tramite"
     .parameters.Delete         "l_inicio_real"
     .parameters.Delete         "l_fim_real"
     .parameters.Delete         "l_nota_conclusao"
     .parameters.Delete         "l_custo_real"
     .parameters.Delete         "l_caminho"
     .parameters.Delete         "l_tamanho"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_nome_original"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>