<%
REM =========================================================================
REM Recupera informações consolidadas das solicitações
REM -------------------------------------------------------------------------
Sub DB_GetSolicGR(p_rs, p_menu, p_pessoa, p_restricao)
  Dim l_menu, l_pessoa, l_restricao
  Set l_menu      = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa    = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_menu                 = .CreateParameter("l_menu",        adInteger, adParamInput,   , p_menu)
     set l_pessoa               = .CreateParameter("l_pessoa",      adInteger, adParamInput,   , p_pessoa)
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar, adParamInput, 20, p_restricao)
     .parameters.Append         l_menu
     .parameters.Append         l_pessoa
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSolicList"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_menu"
     .Parameters.Delete         "l_pessoa"
     .Parameters.Delete         "l_restricao"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as solicitações desejadas
REM -------------------------------------------------------------------------
Sub DB_GetSolicGRA(p_rs, p_menu, p_pessoa, p_restricao, p_tipo, _
    p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
    p_unidade, p_prioridade, p_ativo, p_proponente, _
    p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
    p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade)

  Dim l_menu, l_pessoa, l_restricao
  Dim l_ini_i, l_ini_f, l_fim_i, l_fim_f, l_atraso, l_solicitante
  Dim l_unidade, l_prioridade, l_ativo, l_proponente, l_tipo
  Dim l_chave, l_assunto, l_pais, l_regiao, l_uf, l_cidade, l_usu_resp
  Dim l_uorg_resp, l_palavra, l_prazo, l_fase, l_sqcc, l_projeto, l_atividade

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
  Set l_sqcc        = Server.CreateObject("ADODB.Parameter")
  Set l_projeto     = Server.CreateObject("ADODB.Parameter")
  Set l_atividade   = Server.CreateObject("ADODB.Parameter")
  
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
     set l_sqcc                 = .CreateParameter("l_sqcc",        adInteger,  adParamInput,   , Tvl(p_sqcc))
     set l_projeto              = .CreateParameter("l_projeto",     adInteger,  adParamInput,   , Tvl(p_projeto))
     set l_atividade            = .CreateParameter("l_atividade",   adInteger,  adParamInput,   , Tvl(p_atividade))
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
     .parameters.Append         l_sqcc
     .parameters.Append         l_projeto
     .parameters.Append         l_atividade
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSolicGRA"
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
     .Parameters.Delete         "l_sqcc"
     .Parameters.Delete         "l_projeto"
     .Parameters.Delete         "l_atividade"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as solicitações desejadas
REM -------------------------------------------------------------------------
Sub DB_GetSolicGRI(p_rs, p_menu, p_pessoa, p_restricao, p_tipo, _
    p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
    p_unidade, p_prioridade, p_ativo, p_proponente, _
    p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
    p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade)

  Dim l_menu, l_pessoa, l_restricao
  Dim l_ini_i, l_ini_f, l_fim_i, l_fim_f, l_atraso, l_solicitante
  Dim l_unidade, l_prioridade, l_ativo, l_proponente, l_tipo
  Dim l_chave, l_assunto, l_pais, l_regiao, l_uf, l_cidade, l_usu_resp
  Dim l_uorg_resp, l_palavra, l_prazo, l_fase, l_sqcc, l_projeto, l_atividade

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
  Set l_sqcc        = Server.CreateObject("ADODB.Parameter")
  Set l_projeto     = Server.CreateObject("ADODB.Parameter")
  Set l_atividade   = Server.CreateObject("ADODB.Parameter")
  
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
     set l_sqcc                 = .CreateParameter("l_sqcc",        adInteger,  adParamInput,   , Tvl(p_sqcc))
     set l_projeto              = .CreateParameter("l_projeto",     adInteger,  adParamInput,   , Tvl(p_projeto))
     set l_atividade            = .CreateParameter("l_atividade",   adInteger,  adParamInput,   , Tvl(p_atividade))
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
     .parameters.Append         l_sqcc
     .parameters.Append         l_projeto
     .parameters.Append         l_atividade
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSolicGRI"
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
     .Parameters.Delete         "l_sqcc"
     .Parameters.Delete         "l_projeto"
     .Parameters.Delete         "l_atividade"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

