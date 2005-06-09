<%
REM =========================================================================
REM Recupera as licitações a partir dos critérios informados
REM -------------------------------------------------------------------------
Sub DB_GetLcPortalLic(p_rs, p_cliente, p_usuario, p_menu, p_chave, p_restricao, _
       p_unidade, p_fonte, p_modalidade, p_finalidade, p_criterio, p_situacao, _
       p_aber_i, p_aber_f, p_objeto, p_processo, p_empenho, p_publicar, p_pais, _
       p_regiao, p_uf, p_cidade)
       
  Dim l_cliente, l_usuario, l_menu, l_chave, l_restricao, l_unidade, l_fonte
  Dim l_modalidade, l_finalidade, l_criterio, l_situacao, l_aber_i, l_aber_f
  Dim l_objeto, l_processo, l_empenho, l_publicar, l_pais, l_regiao, l_uf, l_cidade
  
  Set l_chave           = Server.CreateObject("ADODB.Parameter")
  Set l_cliente         = Server.CreateObject("ADODB.Parameter")
  Set l_usuario         = Server.CreateObject("ADODB.Parameter")
  Set l_menu            = Server.CreateObject("ADODB.Parameter")
  Set l_restricao       = Server.CreateObject("ADODB.Parameter")
  Set l_unidade         = Server.CreateObject("ADODB.Parameter")
  Set l_fonte           = Server.CreateObject("ADODB.Parameter")
  Set l_modalidade      = Server.CreateObject("ADODB.Parameter")
  Set l_finalidade      = Server.CreateObject("ADODB.Parameter")
  Set l_criterio        = Server.CreateObject("ADODB.Parameter")
  Set l_situacao        = Server.CreateObject("ADODB.Parameter")
  Set l_aber_i          = Server.CreateObject("ADODB.Parameter")
  Set l_aber_f          = Server.CreateObject("ADODB.Parameter")
  Set l_objeto          = Server.CreateObject("ADODB.Parameter")
  Set l_processo        = Server.CreateObject("ADODB.Parameter")
  Set l_empenho         = Server.CreateObject("ADODB.Parameter")
  Set l_publicar        = Server.CreateObject("ADODB.Parameter")
  Set l_pais            = Server.CreateObject("ADODB.Parameter")
  Set l_regiao          = Server.CreateObject("ADODB.Parameter")
  Set l_uf              = Server.CreateObject("ADODB.Parameter")
  Set l_cidade          = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_cliente         = .CreateParameter("l_cliente",        adInteger, adParamInput,    , p_cliente)
     set l_usuario         = .CreateParameter("l_usuario",        adInteger, adParamInput,    , tvl(p_usuario))
     set l_menu            = .CreateParameter("l_menu",           adInteger, adParamInput,    , tvl(p_menu))
     set l_chave           = .CreateParameter("l_chave",          adInteger, adParamInput,    , tvl(p_chave))
     set l_restricao       = .CreateParameter("l_restricao",      adVarchar, adParamInput,  30, tvl(p_restricao))
     set l_unidade         = .CreateParameter("l_unidade",        adInteger, adParamInput,    , tvl(p_unidade))
     set l_fonte           = .CreateParameter("l_fonte",          adInteger, adParamInput,    , tvl(p_fonte))
     set l_modalidade      = .CreateParameter("l_modalidade",     adInteger, adParamInput,    , tvl(p_modalidade))
     set l_finalidade      = .CreateParameter("l_finalidade",     adInteger, adParamInput,    , tvl(p_finalidade))
     set l_criterio        = .CreateParameter("l_criterio",       adInteger, adParamInput,    , tvl(p_criterio))
     set l_situacao        = .CreateParameter("l_situacao",       adInteger, adParamInput,    , tvl(p_situacao))
     set l_aber_i          = .CreateParameter("l_aber_i",         adDate,     adParamInput,   , Tvl(p_aber_i))
     set l_aber_f          = .CreateParameter("l_aber_f",         adDate,     adParamInput,   , Tvl(p_aber_f))
     set l_objeto          = .CreateParameter("l_objeto",         adVarchar, adParamInput,  90, tvl(p_objeto))
     set l_processo        = .CreateParameter("l_processo",       adVarchar, adParamInput,  30, tvl(p_processo))
     set l_empenho         = .CreateParameter("l_empenho",        adVarchar, adParamInput,  30, tvl(p_empenho))
     set l_publicar        = .CreateParameter("l_publicar",       adVarchar, adParamInput,   1, tvl(p_publicar))
     set l_pais            = .CreateParameter("l_pais",           adInteger, adParamInput,    , tvl(p_pais))
     set l_regiao          = .CreateParameter("l_regiao",         adInteger, adParamInput,    , tvl(p_regiao))
     set l_uf              = .CreateParameter("l_uf",             adVarchar, adParamInput,   2, tvl(p_uf))
     set l_cidade          = .CreateParameter("l_cidade",         adInteger, adParamInput,    , tvl(p_cidade))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_usuario
     .parameters.Append         l_menu
     .parameters.Append         l_chave
     .parameters.Append         l_restricao
     .parameters.Append         l_unidade
     .parameters.Append         l_fonte
     .parameters.Append         l_modalidade
     .parameters.Append         l_finalidade
     .parameters.Append         l_criterio
     .parameters.Append         l_situacao
     .parameters.Append         l_aber_i
     .parameters.Append         l_aber_f
     .parameters.Append         l_objeto
     .parameters.Append         l_processo
     .parameters.Append         l_empenho
     .parameters.Append         l_publicar
     .parameters.Append         l_pais
     .parameters.Append         l_regiao
     .parameters.Append         l_uf
     .parameters.Append         l_cidade
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLcPortalLic"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_usuario"
     .Parameters.Delete         "l_menu"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_restricao"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_fonte"
     .Parameters.Delete         "l_modalidade"
     .Parameters.Delete         "l_finalidade"
     .Parameters.Delete         "l_criterio"
     .Parameters.Delete         "l_situacao"
     .Parameters.Delete         "l_aber_i"
     .Parameters.Delete         "l_aber_f"
     .Parameters.Delete         "l_objeto"
     .Parameters.Delete         "l_processo"
     .Parameters.Delete         "l_empenho"
     .Parameters.Delete         "l_publicar"
     .Parameters.Delete         "l_pais"
     .Parameters.Delete         "l_regiao"
     .Parameters.Delete         "l_uf"
     .Parameters.Delete         "l_cidade"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os contratos de uma licitação
REM -------------------------------------------------------------------------
Sub DB_GetLcPortalCont(p_rs, p_cliente, p_chave, p_chave_aux, p_sq_lcfinalidade)
       
  Dim l_cliente, l_chave, l_chave_aux, l_sq_lcfinalidade
  
  Set l_chave           = Server.CreateObject("ADODB.Parameter")
  Set l_cliente         = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux       = Server.CreateObject("ADODB.Parameter")
  Set l_sq_lcfinalidade = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_cliente         = .CreateParameter("l_cliente",          adInteger, adParamInput,    , p_cliente)
     set l_chave           = .CreateParameter("l_chave",            adInteger, adParamInput,    , tvl(p_chave))
     set l_chave_aux       = .CreateParameter("l_chave_aux",        adInteger, adParamInput,    , tvl(p_chave_aux))
     set l_sq_lcfinalidade = .CreateParameter("l_sq_lcfinalidade",  adInteger, adParamInput,    , tvl(p_sq_lcfinalidade))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_sq_lcfinalidade
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLcPortalCont"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
     .Parameters.Delete         "l_sq_lcfinalidade"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os itens de uma licitação
REM -------------------------------------------------------------------------
Sub DB_GetLcPortalLicItem(p_rs, p_cliente, p_chave, p_chave_aux, p_nome, p_cancelado)
       
  Dim l_cliente, l_chave, l_chave_aux, l_nome, l_cancelado
  
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux   = Server.CreateObject("ADODB.Parameter")
  Set l_nome        = Server.CreateObject("ADODB.Parameter")
  Set l_cancelado   = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,    , p_cliente)
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,    , p_chave)
     set l_chave_aux       = .CreateParameter("l_chave_aux",    adInteger, adParamInput,    , tvl(p_chave_aux))
     set l_nome            = .CreateParameter("l_nome",         adVarchar, adParamInput,  60, tvl(p_nome))
     set l_cancelado       = .CreateParameter("l_cancelado",    adVarchar, adParamInput,   1, tvl(p_cancelado))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_nome
     .parameters.Append         l_cancelado
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLcPortalLicItem"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
     .Parameters.Delete         "l_nome"
     .Parameters.Delete         "l_cancelado"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os itens de uma licitação
REM -------------------------------------------------------------------------
Sub DB_GetLcPortalContItem(p_rs, p_cliente, p_chave, p_chave_aux, p_chave_aux1, p_cancelado)
       
  Dim l_cliente, l_chave, l_chave_aux, l_chave_aux1, l_cancelado
  
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux   = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux1  = Server.CreateObject("ADODB.Parameter")
  Set l_cancelado   = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,    , p_cliente)
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,    , tvl(p_chave))
     set l_chave_aux       = .CreateParameter("l_chave_aux",    adInteger, adParamInput,    , tvl(p_chave_aux))
     set l_chave_aux1      = .CreateParameter("l_chave_aux1",   adInteger, adParamInput,    , tvl(p_chave_aux1))
     set l_cancelado       = .CreateParameter("l_cancelado",    adVarchar, adParamInput,   1, tvl(p_cancelado))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_chave_aux1
     .parameters.Append         l_cancelado
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLcPortalContItem"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
     .Parameters.Delete         "l_chave_aux1"
     .Parameters.Delete         "l_cancelado"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os anexos de uma licitação
REM -------------------------------------------------------------------------
Sub DB_GetLcAnexo(p_rs, p_chave, p_chave_aux, p_cliente)
  Dim l_chave, l_chave_aux, l_cliente
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,   , p_chave)
     set l_chave_aux            = .CreateParameter("l_chave_aux",   adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_cliente              = .CreateParameter("l_cliente",     adInteger, adParamInput,   , p_cliente)
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLcAnexo"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
     .Parameters.Delete         "l_cliente"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

