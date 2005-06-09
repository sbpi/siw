<%
REM =========================================================================
REM Recupera ações do ppa(tabela do SIGPLAN)
REM -------------------------------------------------------------------------
Sub DB_GetEsquema(p_rs, p_cliente, p_restricao, p_sq_esquema, p_sq_modulo, p_nome, p_tipo, p_formato, p_dt_ini, p_dt_fim, p_ref_ini, p_ref_fim)
  
  Dim l_cliente, l_restricao, l_sq_esquema, l_sq_modulo, l_nome, l_tipo, l_formato, l_dt_ini, l_dt_fim, l_ref_ini, l_ref_fim
  Set l_cliente            = Server.CreateObject("ADODB.Parameter")
  Set l_restricao          = Server.CreateObject("ADODB.Parameter")
  Set l_sq_esquema         = Server.CreateObject("ADODB.Parameter")
  Set l_sq_modulo          = Server.CreateObject("ADODB.Parameter")
  Set l_nome               = Server.CreateObject("ADODB.Parameter")
  Set l_tipo               = Server.CreateObject("ADODB.Parameter")
  Set l_formato            = Server.CreateObject("ADODB.Parameter")
  Set l_dt_ini             = Server.CreateObject("ADODB.Parameter")
  Set l_dt_fim             = Server.CreateObject("ADODB.Parameter")
  Set l_ref_ini            = Server.CreateObject("ADODB.Parameter")
  Set l_ref_fim            = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente      = .CreateParameter("l_cliente",          adInteger, adParamInput,    , p_cliente)
     set l_restricao    = .CreateParameter("l_restricao",        adVarchar, adParamInput,  60, Tvl(p_restricao))
     set l_sq_esquema   = .CreateParameter("l_sq_esquema",       adInteger, adParamInput,    , tvl(p_sq_esquema))
     set l_sq_modulo    = .CreateParameter("l_sq_modulo",        adInteger, adParamInput,    , tvl(p_sq_modulo))
     set l_nome         = .CreateParameter("l_nome",             adVarchar, adParamInput,  60, Tvl(p_nome))
     set l_tipo         = .CreateParameter("l_tipo",             adVarchar, adParamInput,   1, Tvl(p_tipo))
     set l_formato      = .CreateParameter("l_formato",          adVarchar, adParamInput,   1, Tvl(p_formato))
     set l_dt_ini       = .CreateParameter("l_dt_ini",           adDate,    adParamInput,    , Tvl(p_dt_ini))
     set l_dt_fim       = .CreateParameter("l_dt_fim",           adDate,    adParamInput,    , Tvl(p_dt_fim))
     set l_ref_ini      = .CreateParameter("l_ref_ini",          adDate,    adParamInput,    , Tvl(p_ref_ini))
     set l_ref_fim      = .CreateParameter("l_ref_fim",          adDate,    adParamInput,    , Tvl(p_ref_fim))
     .parameters.Append         l_cliente
     .parameters.Append         l_restricao
     .parameters.Append         l_sq_esquema
     .parameters.Append         l_sq_modulo
     .parameters.Append         l_nome
     .parameters.Append         l_tipo
     .parameters.Append         l_formato
     .parameters.Append         l_dt_ini
     .parameters.Append         l_dt_fim
     .parameters.Append         l_ref_ini
     .parameters.Append         l_ref_fim
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetEsquema"
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
     .Parameters.Delete         "l_restricao"
     .Parameters.Delete         "l_sq_esquema"
     .Parameters.Delete         "l_sq_modulo"
     .Parameters.Delete         "l_nome"
     .Parameters.Delete         "l_tipo"
     .Parameters.Delete         "l_formato"
     .Parameters.Delete         "l_dt_ini"
     .Parameters.Delete         "l_dt_fim"
     .Parameters.Delete         "l_ref_ini"
     .Parameters.Delete         "l_ref_fim"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera ações do ppa(tabela do SIGPLAN)
REM -------------------------------------------------------------------------
Sub DB_GetEsquemaTabela(p_rs, p_restricao, p_sq_esquema, p_sq_esquema_tabela)
  
  Dim l_restricao, l_sq_esquema, l_sq_esquema_tabela
  Set l_restricao          = Server.CreateObject("ADODB.Parameter")
  Set l_sq_esquema         = Server.CreateObject("ADODB.Parameter")
  Set l_sq_esquema_tabela  = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_restricao          = .CreateParameter("l_restricao",         adVarchar, adParamInput,  60, Tvl(p_restricao))
     set l_sq_esquema         = .CreateParameter("l_sq_esquema",        adInteger, adParamInput,    , tvl(p_sq_esquema))
     set l_sq_esquema_tabela  = .CreateParameter("l_sq_esquema_tabela", adInteger, adParamInput,    , tvl(p_sq_esquema_tabela))
     .parameters.Append         l_restricao
     .parameters.Append         l_sq_esquema
     .parameters.Append         l_sq_esquema_tabela
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetEsquemaTabela"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_restricao"
     .Parameters.Delete         "l_sq_esquema"
     .Parameters.Delete         "l_sq_esquema_tabela"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------


REM =========================================================================
REM Recupera ações do ppa(tabela do SIGPLAN)
REM -------------------------------------------------------------------------
Sub DB_GetEsquemaAtributo(p_rs, p_restricao, p_sq_esquema_tabela, p_sq_esquema_atributo, p_sq_coluna)
  
  Dim l_restricao, l_sq_esquema, l_sq_esquema_tabela, l_sq_esquema_atributo, l_sq_coluna
  Set l_restricao            = Server.CreateObject("ADODB.Parameter")
  Set l_sq_esquema_tabela    = Server.CreateObject("ADODB.Parameter")
  Set l_sq_esquema_atributo  = Server.CreateObject("ADODB.Parameter")
  Set l_sq_coluna            = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_restricao            = .CreateParameter("l_restricao",           adVarchar, adParamInput,  60, Tvl(p_restricao))
     set l_sq_esquema_tabela    = .CreateParameter("l_sq_esquema_tabela",   adInteger, adParamInput,    , tvl(p_sq_esquema_tabela))
     set l_sq_esquema_atributo  = .CreateParameter("l_sq_esquema_atributo", adInteger, adParamInput,    , tvl(p_sq_esquema_atributo))
     set l_sq_coluna            = .CreateParameter("l_sq_coluna",           adInteger, adParamInput,    , tvl(p_sq_coluna))
     .parameters.Append         l_restricao
     .parameters.Append         l_sq_esquema_tabela
     .parameters.Append         l_sq_esquema_atributo
     .parameters.Append         l_sq_coluna
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetEsquemaAtributo"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_restricao"
     .Parameters.Delete         "l_sq_esquema_tabela"
     .Parameters.Delete         "l_sq_esquema_atributo"
     .Parameters.Delete         "l_sq_coluna"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

