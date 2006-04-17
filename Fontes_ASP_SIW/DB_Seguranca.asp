<%
REM =========================================================================
REM Recupera os links para manipulação
REM -------------------------------------------------------------------------
Sub DB_GetMenuLink(p_rs, p_cliente, p_chave, p_modulo, p_restricao)
  Dim l_cliente, l_chave, l_modulo, l_restricao
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_modulo    = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente",   adInteger, adParamInput, , p_cliente)
     set l_chave                = .CreateParameter("l_chave",     adInteger, adParamInput, , Tvl(p_chave))
     set l_modulo               = .CreateParameter("l_modulo",    adInteger, adParamInput, , Tvl(p_modulo))
     set l_restricao            = .CreateParameter("l_restricao", adVarChar, adParamInput, 20, p_restricao)
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_modulo
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetMenuLink"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_modulo"
     .Parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados de uma opção do menu
REM -------------------------------------------------------------------------
Sub DB_GetMenuData(p_rs, p_chave)
  Dim l_chave
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",   adInteger, adParamInput, , Tvl(p_chave))
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetMenuData"
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
  end with
End Sub

REM =========================================================================
REM Recupera os links aos quais uma opção pode ser subordinada
REM -------------------------------------------------------------------------
Sub DB_GetMenuList(p_rs, p_cliente, p_operacao, p_chave)
  Dim l_cliente, l_chave, l_operacao
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_operacao  = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente",  adInteger, adParamInput,  , p_cliente)
     set l_operacao             = .CreateParameter("l_operacao", adVarChar, adParamInput, 1, p_operacao)
     set l_chave                = .CreateParameter("l_chave",    adInteger, adParamInput,  , Tvl(p_chave))
     .parameters.Append         l_cliente
     .parameters.Append         l_operacao
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetMenuList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_operacao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM -- Recupera os módulos geridos pela pessoa
REM -------------------------------------------------------------------------
Sub DB_GetUserModule(p_rs, p_cliente, p_chave)
  Dim l_cliente, l_chave, l_operacao
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente",  adInteger, adParamInput,  , p_cliente)
     set l_chave                = .CreateParameter("l_chave",    adInteger, adParamInput,  , p_chave)
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetUserModule"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM -- Verifica se o usuário é gestor do módulo
REM -------------------------------------------------------------------------
Sub DB_GetModMaster(p_rs, p_cliente, p_pessoa, p_menu)
  Dim l_cliente, l_pessoa, l_menu, l_operacao
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa    = Server.CreateObject("ADODB.Parameter")
  Set l_menu      = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente",  adInteger, adParamInput,  , p_cliente)
     set l_pessoa               = .CreateParameter("l_pessoa",   adInteger, adParamInput,  , p_pessoa)
     set l_menu                 = .CreateParameter("l_menu",     adInteger, adParamInput,  , p_menu)
     .parameters.Append         l_cliente
     .parameters.Append         l_pessoa
     .parameters.Append         l_menu
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetModMaster"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_pessoa"
     .Parameters.Delete         "l_menu"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM -- Recupera os centros de custo que a pessoa tem visão geral
REM -------------------------------------------------------------------------
Sub DB_GetUserVision(p_rs, p_menu, p_chave)
  Dim l_menu, l_chave, l_operacao
  Set l_menu    = Server.CreateObject("ADODB.Parameter")
  Set l_chave   = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_menu                 = .CreateParameter("l_menu",    adInteger, adParamInput,  , tvl(p_menu))
     set l_chave                = .CreateParameter("l_chave",   adInteger, adParamInput,  , p_chave)
     .parameters.Append         l_menu
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetUserVision"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_menu"
     .Parameters.Delete         "l_chave"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera o número de ordem das outras opções irmãs à informada
REM -------------------------------------------------------------------------
Sub DB_GetMenuOrder(p_rs, p_cliente, p_chave, p_chave_aux, p_ultimo_nivel)
  Dim l_cliente, l_chave, l_operacao, l_chave_aux, l_ultimo_nivel
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente",      adInteger, adParamInput,  , p_cliente)
     set l_chave                = .CreateParameter("l_chave",        adInteger, adParamInput,  , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",    adInteger, adParamInput,  , Tvl(p_chave_aux))
     set l_ultimo_nivel         = .CreateParameter("l_ultimo_nivel", adVarchar, adParamInput, 1, Tvl(p_ultimo_nivel))
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_ultimo_nivel
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetMenuOrder"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
     .Parameters.Delete         "l_ultimo_nivel"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista de módulos disponíveis para o cliente
REM -------------------------------------------------------------------------
Sub DB_GetSiwCliModLis(p_rs, p_cliente, p_restricao)
  Dim l_cliente, l_restricao
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_restricao   = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente",   adInteger, adParamInput, , p_cliente)
     set l_restricao            = .CreateParameter("l_restricao", adVarChar, adParamInput, 20, Tvl(p_restricao))
     .parameters.Append         l_cliente
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSiwCliModLis"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_restricao"
  end with
End Sub

REM =========================================================================
REM Recupera as opções superiores à informada
REM -------------------------------------------------------------------------
Sub DB_GetMenuUpper(p_rs, p_sq_menu)
  Dim l_sq_menu
  Set l_sq_menu     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_menu              = .CreateParameter("l_sq_menu",   adInteger, adParamInput, , p_sq_menu)
     .parameters.Append         l_sq_menu
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetMenuUpper"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_menu"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os usuários ou tipos de vínculo habilitados para uma opção do menu
REM -------------------------------------------------------------------------
Sub DB_GetMenuUser(p_rs, p_cliente, p_sq_menu, p_ChaveAux, p_retorno)
  Dim l_cliente, l_sq_menu, l_retorno, l_ChaveAux
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_sq_menu     = Server.CreateObject("ADODB.Parameter")
  Set l_ChaveAux    = Server.CreateObject("ADODB.Parameter")
  Set l_retorno     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente",   adInteger, adParamInput,   , p_cliente)
     set l_sq_menu              = .CreateParameter("l_sq_menu",   adInteger, adParamInput,   , p_sq_menu)
     set l_ChaveAux             = .CreateParameter("l_ChaveAux",  adInteger, adParamInput,   , Tvl(p_ChaveAux))
     set l_retorno              = .CreateParameter("l_retorno",   adVarchar, adParamInput, 20, p_retorno)
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_menu
     .parameters.Append         l_ChaveAux
     .parameters.Append         l_retorno
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetMenuUser"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_sq_menu"
     .Parameters.Delete         "l_ChaveAux"
     .Parameters.Delete         "l_retorno"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os usuários ou tipos de vínculo habilitados para uma opção do menu
REM -------------------------------------------------------------------------
Sub DB_GetTramiteUser(p_rs, p_cliente, p_sq_menu, p_ChaveAux, p_retorno)
  Dim l_cliente, l_sq_menu, l_retorno, l_ChaveAux
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_sq_menu     = Server.CreateObject("ADODB.Parameter")
  Set l_ChaveAux    = Server.CreateObject("ADODB.Parameter")
  Set l_retorno     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente",   adInteger, adParamInput,   , p_cliente)
     set l_sq_menu              = .CreateParameter("l_sq_menu",   adInteger, adParamInput,   , p_sq_menu)
     set l_ChaveAux             = .CreateParameter("l_ChaveAux",  adInteger, adParamInput,   , Tvl(p_ChaveAux))
     set l_retorno              = .CreateParameter("l_retorno",   adVarchar, adParamInput, 20, p_retorno)
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_menu
     .parameters.Append         l_ChaveAux
     .parameters.Append         l_retorno
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetTramiteUser"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_sq_menu"
     .Parameters.Delete         "l_ChaveAux"
     .Parameters.Delete         "l_retorno"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados de um trâmite
REM -------------------------------------------------------------------------
Sub DB_GetTramiteData(p_rs, p_chave)
  Dim l_chave
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",   adInteger, adParamInput, , p_chave)
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetTramiteData"
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
REM Recupera os trâmites de um serviço
REM -------------------------------------------------------------------------
Sub DB_GetTramiteList(p_rs, p_chave, p_restricao)
  Dim l_chave, l_restricao
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",     adInteger, adParamInput, , p_chave)
     set l_restricao            = .CreateParameter("l_restricao", adVarChar, adParamInput, 20, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetTramiteList"
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
%>

