<%

REM =========================================================================
REM Recupera os tipos de acordo do cliente
REM -------------------------------------------------------------------------
Sub DB_GetAgreeType(p_rs, p_chave, p_chave_aux, p_cliente, p_restricao)
  Dim l_chave, l_chave_aux, l_cliente, l_restricao
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux    = Server.CreateObject("ADODB.Parameter")
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,   , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",   adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_cliente              = .CreateParameter("l_cliente",     adInteger, adParamInput,   , p_cliente)
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar, adParamInput, 50, Tvl(p_restricao))
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_cliente
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetAgreeType"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as companhias de transportes
REM -------------------------------------------------------------------------
Sub DB_GetCiaTrans(p_rs, p_cliente, p_chave, p_nome, p_aereo, p_rodoviario, _
                    p_aquaviario, p_padrao, p_ativo, p_chave_aux, p_restricao)
  
  Dim l_cliente, l_chave, l_nome, l_aereo, l_rodoviario, l_aquaviario, l_padrao, l_ativo
  Dim l_chave_aux, l_restricao
  
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_nome        = Server.CreateObject("ADODB.Parameter")
  Set l_aereo       = Server.CreateObject("ADODB.Parameter")
  Set l_rodoviario  = Server.CreateObject("ADODB.Parameter")
  Set l_aquaviario  = Server.CreateObject("ADODB.Parameter")
  Set l_padrao      = Server.CreateObject("ADODB.Parameter")
  Set l_ativo       = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux   = Server.CreateObject("ADODB.Parameter")
  Set l_restricao   = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,    , tvl(p_cliente))
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,    , tvl(p_chave))
     set l_nome            = .CreateParameter("l_nome",         adVarchar, adParamInput,  30, tvl(p_nome))
     set l_aereo           = .CreateParameter("l_aereo",        adVarchar, adParamInput,   1, tvl(p_aereo))
     set l_rodoviario      = .CreateParameter("l_rodoviario",   adVarchar, adParamInput,   1, tvl(p_rodoviario))
     set l_aquaviario      = .CreateParameter("l_aquaviario",   adVarchar, adParamInput,   1, tvl(p_aquaviario))
     set l_padrao          = .CreateParameter("l_padrao",       adVarchar, adParamInput,   1, tvl(p_padrao))
     set l_ativo           = .CreateParameter("l_ativo",        adVarchar, adParamInput,   1, tvl(p_ativo))
     set l_chave_aux       = .CreateParameter("l_chave_aux",    adInteger, adParamInput,    , tvl(p_chave_aux))
     set l_restricao       = .CreateParameter("l_restricao",     adVarchar, adParamInput, 30, tvl(p_restricao))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_nome
     .parameters.Append         l_aereo
     .parameters.Append         l_rodoviario
     .parameters.Append         l_aquaviario
     .parameters.Append         l_padrao
     .parameters.Append         l_ativo
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetCiaTrans"
     
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
     .Parameters.Delete         "l_nome"
     .Parameters.Delete         "l_aereo"
     .Parameters.Delete         "l_rodoviario"
     .Parameters.Delete         "l_aquaviario"
     .Parameters.Delete         "l_padrao"
     .Parameters.Delete         "l_ativo"
     .Parameters.Delete         "l_chave_aux"
     .Parameters.Delete         "l_restricao"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os parametros
REM -------------------------------------------------------------------------
Sub DB_GetPDParametro(p_rs, p_cliente, p_chave_aux, p_restricao)
  
  Dim l_cliente, l_chave_aux, l_restricao
  
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux    = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_cliente   = .CreateParameter("l_cliente",   adInteger, adParamInput,   , p_cliente)
     set l_chave_aux = .CreateParameter("l_chave_aux", adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao = .CreateParameter("l_restricao", adVarchar, adParamInput, 20, Tvl(p_restricao))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetPDParametro"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_restricao"

  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

