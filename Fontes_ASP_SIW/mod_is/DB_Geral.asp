<%
REM =========================================================================
REM Recupera os responsáveis pelo cumprimento de um trâmite
REM -------------------------------------------------------------------------
Sub DB_GetSolicResp_IS(p_rs, p_chave, p_fase, p_restricao)
  Dim l_fase, l_chave, l_restricao
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_fase   = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",     adInteger, adParamInput,   , p_chave)
     set l_fase                 = .CreateParameter("l_fase",      advarchar, adParamInput, 20, Tvl(p_fase))
     set l_restricao            = .CreateParameter("l_restricao", adVarChar, adParamInput, 20, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_fase
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetSolicResp_IS"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_fase"
     .Parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as solicitações desejadas
REM -------------------------------------------------------------------------
Sub DB_GetSolicList_IS(p_rs, p_menu, p_pessoa, p_restricao, p_tipo, _
    p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
    p_unidade, p_prioridade, p_ativo, p_proponente, _
    p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
    p_uorg_resp, p_palavra, p_prazo, p_fase, p_projeto, p_atividade, p_programa, _
    p_codigo, p_orprior, p_cd_subacao, p_ano)

  Dim l_menu, l_pessoa, l_restricao
  Dim l_ini_i, l_ini_f, l_fim_i, l_fim_f, l_atraso, l_solicitante
  Dim l_unidade, l_prioridade, l_ativo, l_proponente, l_tipo
  Dim l_chave, l_assunto, l_pais, l_regiao, l_uf, l_cidade, l_usu_resp
  Dim l_uorg_resp, l_palavra, l_prazo, l_fase, l_sqcc, l_projeto, l_atividade
  Dim l_programa, l_codigo, l_orprior, l_cd_subacao, l_ano

  Set l_menu        = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa      = Server.CreateObject("ADODB.Parameter")
  Set l_restricao   = Server.CreateObject("ADODB.Parameter")
  Set l_tipo        = Server.CreateObject("ADODB.Parameter")
  Set l_ini_i       = Server.CreateObject("ADODB.Parameter")
  Set l_ini_f       = Server.CreateObject("ADODB.Parameter")
  Set l_fim_i       = Server.CreateObject("ADODB.Parameter")
  Set l_fim_f       = Server.CreateObject("ADODB.Parameter")
  Set l_atraso      = Server.CreateObject("ADODB.Parameter")
  Set l_solicitante = Server.CreateObject("ADODB.Parameter")
  Set l_unidade     = Server.CreateObject("ADODB.Parameter")
  Set l_prioridade  = Server.CreateObject("ADODB.Parameter")
  Set l_ativo       = Server.CreateObject("ADODB.Parameter")
  Set l_proponente  = Server.CreateObject("ADODB.Parameter")
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_assunto     = Server.CreateObject("ADODB.Parameter")
  Set l_pais        = Server.CreateObject("ADODB.Parameter")
  Set l_regiao      = Server.CreateObject("ADODB.Parameter")
  Set l_uf          = Server.CreateObject("ADODB.Parameter")
  Set l_cidade      = Server.CreateObject("ADODB.Parameter")
  Set l_usu_resp    = Server.CreateObject("ADODB.Parameter")
  Set l_uorg_resp   = Server.CreateObject("ADODB.Parameter")
  Set l_palavra     = Server.CreateObject("ADODB.Parameter")
  Set l_prazo       = Server.CreateObject("ADODB.Parameter")
  Set l_fase        = Server.CreateObject("ADODB.Parameter")
  Set l_projeto     = Server.CreateObject("ADODB.Parameter")
  Set l_atividade   = Server.CreateObject("ADODB.Parameter")
  Set l_programa    = Server.CreateObject("ADODB.Parameter")
  Set l_codigo      = Server.CreateObject("ADODB.Parameter")
  Set l_orprior     = Server.CreateObject("ADODB.Parameter")
  Set l_cd_subacao  = Server.CreateObject("ADODB.Parameter")
  Set l_ano         = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_menu                 = .CreateParameter("l_menu",        adInteger,  adParamInput,   , p_menu)
     set l_pessoa               = .CreateParameter("l_pessoa",      adInteger,  adParamInput,   , p_pessoa)
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar,  adParamInput, 20, p_restricao)
     set l_tipo                 = .CreateParameter("l_tipo",        adInteger,  adParamInput,   , p_tipo)
     set l_ini_i                = .CreateParameter("l_ini_i",       adDate,     adParamInput,   , Tvl(p_ini_i))
     set l_ini_f                = .CreateParameter("l_ini_f",       adDate,     adParamInput,   , Tvl(p_ini_f))
     set l_fim_i                = .CreateParameter("l_fim_i",       adDate,     adParamInput,   , Tvl(p_fim_i))
     set l_fim_f                = .CreateParameter("l_fim_f",       adDate,     adParamInput,   , Tvl(p_fim_f))
     set l_atraso               = .CreateParameter("l_atraso",      adVarchar,  adParamInput,  1, Tvl(p_atraso))
     set l_solicitante          = .CreateParameter("l_solicitante", adInteger,  adParamInput,   , Tvl(p_solicitante))
     set l_unidade              = .CreateParameter("l_unidade",     adInteger,  adParamInput,   , Tvl(p_unidade))
     set l_prioridade           = .CreateParameter("l_prioridade",  adInteger,  adParamInput,   , Tvl(p_prioridade))
     set l_ativo                = .CreateParameter("l_ativo",       adVarchar,  adParamInput, 10, Tvl(p_ativo))
     set l_proponente           = .CreateParameter("l_proponente",  adVarchar,  adParamInput, 90, Tvl(p_proponente))
     set l_chave                = .CreateParameter("l_chave",       adInteger,  adParamInput,   , Tvl(p_chave))
     set l_assunto              = .CreateParameter("l_assunto",     adVarchar,  adParamInput, 90, Tvl(p_assunto))
     set l_pais                 = .CreateParameter("l_pais",        adInteger,  adParamInput,   , Tvl(p_pais))
     set l_regiao               = .CreateParameter("l_regiao",      adInteger,  adParamInput,   , Tvl(p_regiao))
     set l_uf                   = .CreateParameter("l_uf",          adVarchar,  adParamInput, 2, Tvl(p_uf))
     set l_cidade               = .CreateParameter("l_cidade",      adInteger,  adParamInput,   , Tvl(p_cidade))
     set l_usu_resp             = .CreateParameter("l_usu_resp",    adInteger,  adParamInput,   , Tvl(p_usu_resp))
     set l_uorg_resp            = .CreateParameter("l_uorg_resp",   adInteger,  adParamInput,   , Tvl(p_uorg_resp))
     set l_palavra              = .CreateParameter("l_palavra",     adVarchar,  adParamInput, 90, Tvl(p_palavra))
     set l_prazo                = .CreateParameter("l_prazo",       adInteger,  adParamInput,   , Tvl(p_prazo))
     set l_fase                 = .CreateParameter("l_fase",        adVarchar,  adParamInput,200, Tvl(p_fase))
     set l_projeto              = .CreateParameter("l_projeto",     adInteger,  adParamInput,   , Tvl(p_projeto))
     set l_atividade            = .CreateParameter("l_atividade",   adInteger,  adParamInput,   , Tvl(p_atividade))
     set l_programa             = .CreateParameter("l_programa",    adVarchar,  adParamInput,  4, Tvl(p_programa))
     set l_codigo               = .CreateParameter("l_codigo",      adVarchar,  adParamInput,  4, Tvl(p_codigo))
     set l_orprior              = .CreateParameter("l_orprior",     adInteger,  adParamInput,   , Tvl(p_orprior))
     set l_cd_subacao           = .CreateParameter("l_cd_subacao",  adVarchar,  adParamInput,  4, Tvl(p_cd_subacao))
     set l_ano                  = .CreateParameter("l_ano",         adInteger,  adParamInput,   , Tvl(p_ano))
     .parameters.Append         l_menu
     .parameters.Append         l_pessoa
     .parameters.Append         l_restricao
     .parameters.Append         l_tipo
     .parameters.Append         l_ini_i
     .parameters.Append         l_ini_f
     .parameters.Append         l_fim_i
     .parameters.Append         l_fim_f
     .parameters.Append         l_atraso
     .parameters.Append         l_solicitante
     .parameters.Append         l_unidade
     .parameters.Append         l_prioridade
     .parameters.Append         l_ativo
     .parameters.Append         l_proponente
     .parameters.Append         l_chave
     .parameters.Append         l_assunto
     .parameters.Append         l_pais
     .parameters.Append         l_regiao
     .parameters.Append         l_uf
     .parameters.Append         l_cidade
     .parameters.Append         l_usu_resp
     .parameters.Append         l_uorg_resp
     .parameters.Append         l_palavra
     .parameters.Append         l_prazo
     .parameters.Append         l_fase
     .parameters.Append         l_projeto
     .parameters.Append         l_atividade
     .parameters.Append         l_programa
     .parameters.Append         l_codigo
     .parameters.Append         l_orprior
     .parameters.Append         l_cd_subacao
     .parameters.Append         l_ano
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetSolicList_IS"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_menu"
     .Parameters.Delete         "l_pessoa"
     .Parameters.Delete         "l_restricao"
     .Parameters.Delete         "l_tipo"
     .Parameters.Delete         "l_ini_i"
     .Parameters.Delete         "l_ini_f"
     .Parameters.Delete         "l_fim_i"
     .Parameters.Delete         "l_fim_f"
     .Parameters.Delete         "l_atraso"
     .Parameters.Delete         "l_solicitante"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_prioridade"
     .Parameters.Delete         "l_ativo"
     .Parameters.Delete         "l_proponente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_assunto"
     .Parameters.Delete         "l_pais"
     .Parameters.Delete         "l_regiao"
     .Parameters.Delete         "l_uf"
     .Parameters.Delete         "l_cidade"
     .Parameters.Delete         "l_usu_resp"
     .Parameters.Delete         "l_uorg_resp"
     .Parameters.Delete         "l_palavra"
     .Parameters.Delete         "l_prazo"
     .Parameters.Delete         "l_fase"
     .Parameters.Delete         "l_projeto"
     .Parameters.Delete         "l_atividade"
     .Parameters.Delete         "l_programa"
     .Parameters.Delete         "l_codigo"
     .Parameters.Delete         "l_orprior"
     .Parameters.Delete         "l_cd_subacao"
     .Parameters.Delete         "l_ano"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados de uma solicitacao
REM -------------------------------------------------------------------------
Sub DB_GetSolicData_IS(p_rs, p_chave, p_restricao)
  Dim l_chave, l_restricao
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,   , p_chave)
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar, adParamInput, 20, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetSolicData_IS"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_restricao"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a atualização mensal das metas
REM -------------------------------------------------------------------------
Sub DB_GetMetaMensal_IS(p_rs, p_chave)
  Dim l_chave
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , p_chave)
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetMetaMensal_IS"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera iniciativas prioritárias
REM -------------------------------------------------------------------------
Sub DB_Get10PercentDays_IS(p_rs, p_inicio, p_fim)
  Dim l_inicio, l_fim
  Set l_inicio      = Server.CreateObject("ADODB.Parameter")
  Set l_fim         = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_inicio          = .CreateParameter("l_inicio",       adDate, adParamInput,   , p_inicio)
     set l_fim             = .CreateParameter("l_fim",          adDate, adParamInput,   , p_fim)
     .parameters.Append         l_inicio
     .parameters.Append         l_fim
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_Get10PercentDays_IS"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_inicio"
     .Parameters.Delete         "l_fim"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as metas de uma açao
REM -------------------------------------------------------------------------
Sub DB_GetSolicMeta_IS(p_rs, p_chave, p_chave_aux, p_restricao, p_ano, p_unidade, p_cd_programa, p_cd_acao, p_preenchida, p_meta_ppa, p_exequivel)
  Dim l_chave, l_chave_aux, l_restricao, l_ano, l_unidade, l_cd_programa, l_cd_acao, l_preenchida, l_meta_ppa, l_exequivel
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  Set l_ano       = Server.CreateObject("ADODB.Parameter")
  Set l_unidade   = Server.CreateObject("ADODB.Parameter")
  Set l_cd_programa = Server.CreateObject("ADODB.Parameter")
  Set l_cd_acao     = Server.CreateObject("ADODB.Parameter")
  Set l_preenchida  = Server.CreateObject("ADODB.Parameter")
  Set l_meta_ppa    = Server.CreateObject("ADODB.Parameter")
  Set l_exequivel   = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao            = .CreateParameter("l_restricao",     adVarchar, adParamInput, 20, p_restricao)
     set l_ano                  = .CreateParameter("l_ano",           adInteger, adParamInput,   , Tvl(p_ano))
     set l_unidade              = .CreateParameter("l_unidade",       adInteger, adParamInput,   , Tvl(p_unidade))
     set l_cd_programa          = .CreateParameter("l_cd_programa",   adVarchar, adParamInput,  4, Tvl(p_cd_programa))
     set l_cd_acao              = .CreateParameter("l_cd_acao",       adVarchar, adParamInput,  4, Tvl(p_cd_acao))
     set l_preenchida           = .CreateParameter("l_preenchida",    adVarchar, adParamInput,  1, Tvl(p_preenchida))
     set l_meta_ppa             = .CreateParameter("l_meta_ppa",      adVarchar, adParamInput,  1, Tvl(p_meta_ppa))
     set l_exequivel            = .CreateParameter("l_exequivel",     adVarchar, adParamInput,  1, Tvl(p_exequivel))
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao
     .parameters.Append         l_ano
     .parameters.Append         l_unidade
     .parameters.Append         l_cd_programa
     .parameters.Append         l_cd_acao
     .parameters.Append         l_preenchida
     .parameters.Append         l_meta_ppa
     .parameters.Append         l_exequivel
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetSolicMeta_IS"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
     .Parameters.Delete         "l_restricao"
     .Parameters.Delete         "l_ano"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_cd_programa"
     .Parameters.Delete         "l_cd_acao"
     .Parameters.Delete         "l_preenchida"
     .Parameters.Delete         "l_meta_ppa"
     .Parameters.Delete         "l_exequivel"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as periodicidades do SIGPLAN
REM -------------------------------------------------------------------------
Sub DB_GetPeriodicidade_IS(p_rs, p_chave, p_ativo)
  
  Dim l_chave, l_ativo
  
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_ativo       = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_chave          = .CreateParameter("l_chave",      adInteger, adParamInput,    , tvl(p_chave))
     set l_ativo          = .CreateParameter("l_ativo",      adVarchar, adParamInput,   2, tvl(p_ativo))
     .parameters.Append         l_chave
     .parameters.Append         l_ativo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetPeriodicidade_IS"
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
     .Parameters.Delete         "l_ativo"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as bases geográficas do SIGPLAN
REM -------------------------------------------------------------------------
Sub DB_GetBaseGeografica_IS(p_rs, p_chave, p_ativo)
  
  Dim l_chave, l_ativo
  
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_ativo       = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_chave          = .CreateParameter("l_chave",      adInteger, adParamInput,    , tvl(p_chave))
     set l_ativo          = .CreateParameter("l_ativo",      adVarchar, adParamInput,   2, tvl(p_ativo))
     .parameters.Append         l_chave
     .parameters.Append         l_ativo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetBaseGeografica_IS"
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
     .Parameters.Delete         "l_ativo"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as unidades de medida do SIGPLAN
REM -------------------------------------------------------------------------
Sub DB_GetUniMedida_IS(p_rs, p_chave, p_ativo)
  
  Dim l_chave, l_ativo
  
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_ativo       = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_chave          = .CreateParameter("l_chave",      adInteger, adParamInput,    , tvl(p_chave))
     set l_ativo          = .CreateParameter("l_ativo",      adVarchar, adParamInput,   2, tvl(p_ativo))
     .parameters.Append         l_chave
     .parameters.Append         l_ativo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetUniMedida_IS"
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
     .Parameters.Delete         "l_ativo"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os indicadores de um programa
REM -------------------------------------------------------------------------
Sub DB_GetSolicIndic_IS(p_rs, p_chave, p_chave_aux, p_restricao)
  Dim l_chave, l_chave_aux, l_restricao
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao            = .CreateParameter("l_restricao",     adVarchar, adParamInput, 20, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetSolicIndic_IS"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
     .Parameters.Delete         "l_restricao"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os tipos de restrições do SIGPLAN
REM -------------------------------------------------------------------------
Sub DB_GetTPRestricao_IS(p_rs, p_chave, p_ativo)
  
  Dim l_chave, l_ativo
  
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_ativo       = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_chave          = .CreateParameter("l_chave",      adInteger, adParamInput,    , tvl(p_chave))
     set l_ativo          = .CreateParameter("l_ativo",      adVarchar, adParamInput,   2, tvl(p_ativo))
     .parameters.Append         l_chave
     .parameters.Append         l_ativo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetTipoRestricao_IS"
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
     .Parameters.Delete         "l_ativo"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados financeiros do programa PPA
REM -------------------------------------------------------------------------
Sub DB_GetPPADadoFinanc_IS(p_rs, p_chave, p_unidade, ano, cliente, p_restricao)
  
  Dim l_chave, l_unidade, l_ano, l_cliente, l_restricao
  
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_unidade   = Server.CreateObject("ADODB.Parameter")
  Set l_ano       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_chave                = .CreateParameter("l_chave",     adVarchar, adParamInput,  4, p_chave)
     set l_unidade              = .CreateParameter("l_unidade",   adVarchar, adParamInput,  5, tvl(p_unidade))
     set l_ano                  = .CreateParameter("l_ano",       adInteger, adParamInput,   , Tvl(ano))
     set l_cliente              = .CreateParameter("l_cliente",   adInteger, adParamInput,   , Tvl(cliente))
     set l_restricao            = .CreateParameter("l_restricao", adVarChar, adParamInput, 20, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_unidade
     .parameters.Append         l_ano
     .parameters.Append         l_cliente
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetPPADadoFinanc_IS"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_ano"
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------


REM =========================================================================
REM Recupera as restrições de uma ação
REM -------------------------------------------------------------------------
Sub DB_GetPPALocalizador_IS(p_rs, cliente, ano, p_programa, p_acao, p_unidade, p_subacao)
  
  Dim l_cliente, l_ano, l_programa, l_acao, l_unidade, l_subacao
  
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_ano       = Server.CreateObject("ADODB.Parameter")
  Set l_programa  = Server.CreateObject("ADODB.Parameter")
  Set l_acao      = Server.CreateObject("ADODB.Parameter")
  Set l_unidade   = Server.CreateObject("ADODB.Parameter")
  Set l_subacao   = Server.CreateObject("ADODB.Parameter")  
  
  with sp
     set l_cliente              = .CreateParameter("l_cliente",       adInteger, adParamInput,   , Tvl(cliente))
     set l_ano                  = .CreateParameter("l_ano",           adInteger, adParamInput,   , Tvl(ano))
     set l_programa             = .CreateParameter("l_programa",      adVarchar, adParamInput,  4, Tvl(p_programa))
     set l_acao                 = .CreateParameter("l_acao",          adVarchar, adParamInput,  4, Tvl(p_acao))
     set l_unidade              = .CreateParameter("l_unidade",       adVarchar, adParamInput,  5, Tvl(p_unidade))
     set l_subacao              = .CreateParameter("l_subacao",       adVarchar, adParamInput,  4, Tvl(p_subacao))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_programa
     .parameters.Append         l_acao
     .parameters.Append         l_unidade
     .parameters.Append         l_subacao
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetPPALocalizador_IS"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_ano"
     .Parameters.Delete         "l_programa"
     .Parameters.Delete         "l_acao"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_subacao"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Verifica se o programa já foi cadastrado
REM -------------------------------------------------------------------------
Sub DB_GetPrograma_IS(p_rs, p_cd_programa, ano, cliente, restricao)
  
  Dim l_cd_programa, l_ano, l_cliente, l_restricao
  
  Set l_cd_programa = Server.CreateObject("ADODB.Parameter")
  Set l_ano         = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_restricao   = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_cd_programa          = .CreateParameter("l_cd_programa", adVarChar, adParamInput, 4, p_cd_programa)
     set l_ano                  = .CreateParameter("l_ano",         adInteger, adParamInput,  , ano)
     set l_cliente              = .CreateParameter("l_cliente",     adInteger, adParamInput,  , cliente)
     set l_restricao            = .CreateParameter("l_restricao",   adVarChar, adParamInput,30, tvl(restricao))

     .parameters.Append         l_cd_programa
     .parameters.Append         l_ano
     .parameters.Append         l_cliente
     .parameters.Append         l_restricao
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetPrograma_IS"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cd_programa"
     .Parameters.Delete         "l_ano"
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Verfica se a ação já foi cadastrada
REM -------------------------------------------------------------------------
Sub DB_GetAcao_IS(p_rs, p_cd_programa, p_cd_acao, p_cd_unidade, ano, cliente, restricao, p_sq_isprojeto)
  
  Dim l_cd_programa, l_cd_acao, l_cd_unidade, l_ano, l_cliente, l_restricao, l_sq_isprojeto
  
  Set l_cd_programa  = Server.CreateObject("ADODB.Parameter")
  Set l_cd_acao      = Server.CreateObject("ADODB.Parameter")
  Set l_cd_unidade   = Server.CreateObject("ADODB.Parameter")
  Set l_ano          = Server.CreateObject("ADODB.Parameter")
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  Set l_sq_isprojeto = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_cd_programa           = .CreateParameter("l_cd_programa", adVarChar, adParamInput, 4, tvl(p_cd_programa))
     set l_cd_acao               = .CreateParameter("l_cd_acao",     adVarChar, adParamInput, 4, tvl(p_cd_acao))
     set l_cd_unidade            = .CreateParameter("l_cd_unidade",  adVarChar, adParamInput, 5, tvl(p_cd_unidade))
     set l_ano                   = .CreateParameter("l_ano",         adInteger, adParamInput,  , ano)
     set l_cliente               = .CreateParameter("l_cliente",     adInteger, adParamInput,  , cliente)
     set l_restricao             = .CreateParameter("l_restricao",   adVarChar, adParamInput,30, tvl(restricao))
     set l_sq_isprojeto          = .CreateParameter("l_sq_isprojeto",adInteger, adParamInput,  , tvl(p_sq_isprojeto))
     
     .parameters.Append         l_cd_programa
     .parameters.Append         l_cd_acao
     .parameters.Append         l_cd_unidade
     .parameters.Append         l_ano
     .parameters.Append         l_cliente
     .parameters.Append         l_restricao
     .parameters.Append         l_sq_isprojeto
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetAcao_IS"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     
     .Parameters.Delete         "l_cd_programa"
     .Parameters.Delete         "l_cd_acao"
     .Parameters.Delete         "l_cd_unidade"
     .Parameters.Delete         "l_ano"
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_restricao"
     .Parameters.Delete         "l_sq_isprojeto"

  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as restrições de uma solicitação
REM -------------------------------------------------------------------------
Sub DB_GetRestricao_IS(p_rs, p_restricao, p_chave, p_chave_aux)
  
  Dim l_restricao, l_chave, l_chave_aux
  
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_restricao            = .CreateParameter("l_restricao",     adVarchar, adParamInput, 11, Tvl(p_restricao))
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adInteger, adParamInput,   , Tvl(p_chave_aux))
     
     .parameters.Append         l_restricao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetRestricao_IS"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_restricao"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

