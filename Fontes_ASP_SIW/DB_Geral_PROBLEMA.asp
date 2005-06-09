<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_verificausuario.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_verificasenha.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getCustomerData.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getCustomerSite.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getUserData.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getLinkData.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getLinkDataUser.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getLinkSubMenu.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getPersonData.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getDesktop_TT.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getDesktop.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getCCList.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getUorgList.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getCountryList.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getCountryData.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getRegionList.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getRegionData.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getStateList.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getStateData.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getCityList.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getCityData.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getPersonList.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_UpdatePassword.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/dbms/sp_getSolicList.asp" -->
<%
Dim l_sql
REM =========================================================================
REM Recupera os links do sub-menu
REM -------------------------------------------------------------------------
Sub DB_GetLinkSubMenu(p_rs, p_cliente, p_sg) : sp_GetLinkSubMenu p_rs, p_cliente, p_sg : End Sub

REM =========================================================================
REM Recupera os links permitidos ao usuário informado
REM -------------------------------------------------------------------------
Sub DB_GetLinkDataUser(p_rs, p_cliente, p_chave, p_restricao) : sp_GetLinkDataUser p_rs, p_cliente, cInt(p_chave), p_restricao : End Sub

REM =========================================================================
REM Verifica a existência de um usuário no banco de dados
REM -------------------------------------------------------------------------
Function DB_VerificaUsuario(p_cliente, p_username)
  Dim p_rs
  Set p_rs = Server.CreateObject("ADODB.RecordSet")

  SP_VerificaUsuario p_rs, p_cliente, p_username
  DB_VerificaUsuario = p_RS("existe")
  Set p_rs = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Atualiza a senha de acesso ou a assinatura eletrônica de um usuário
REM -------------------------------------------------------------------------
Sub DB_UpdatePassword(p_cliente, p_sq_pessoa, p_valor, p_tipo) : sp_UpdatePassword p_cliente, p_sq_pessoa, p_valor, p_tipo : End Sub

REM =========================================================================
REM Recupera os dados principais do cliente
REM -------------------------------------------------------------------------
Sub DB_GetCustomerData(p_rs, p_cliente) : SP_GetCustomerData p_rs, p_cliente : End Sub

REM =========================================================================
REM Recupera os dados principais do cliente
REM -------------------------------------------------------------------------
Sub DB_GetCustomerSite(p_rs, p_cliente) : SP_GetCustomerSite p_rs, p_cliente : End Sub

REM =========================================================================
REM Recupera os dados do usuário logado
REM -------------------------------------------------------------------------
Sub DB_GetUserData(p_rs, p_cliente, p_username) : SP_GetUserData p_rs, p_cliente, p_username : End Sub

REM =========================================================================
REM Recupera os dados do link informado e se ele tem links vinculados
REM -------------------------------------------------------------------------
Sub DB_GetLinkData(p_rs, p_cliente, p_sg) : SP_GetLinkData p_rs, p_cliente, p_sg : End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados de uma pessoa cadastrada no sistema
REM -------------------------------------------------------------------------
Sub DB_GetPersonData(p_rs, p_cliente, p_sq_pessoa, p_cpf, p_cnpj) : sp_GetPersonData p_rs, p_cliente, p_sq_pessoa, p_cpf, p_cnpj : End Sub

REM =========================================================================
REM Recupera a mesa de trabalho do usuário
REM -------------------------------------------------------------------------
Sub DB_GetDeskTop(p_rs, p_cliente, p_usuario) : sp_GetDeskTop p_rs, p_cliente, p_usuario : End Sub

REM =========================================================================
REM Recupera a mesa de trabalho de ligações
REM -------------------------------------------------------------------------
Sub DB_GetDeskTop_TT(p_rs, p_usuario) : sp_GetDeskTop_TT p_rs, p_usuario : End Sub

REM =========================================================================
REM Recupera o nível de acesso que um usuário tem para uma solicitação
REM -------------------------------------------------------------------------
Sub DB_GetSolicAcesso(p_solicitacao, p_usuario, p_acesso)
  ' Esta procedure faz chamada a uma função do banco de dados
    
  Dim l_solicitacao, l_usuario, l_acesso
  
  Set l_acesso                = Server.CreateObject("ADODB.Parameter") 
  Set l_solicitacao           = Server.CreateObject("ADODB.Parameter") 
  Set l_usuario               = Server.CreateObject("ADODB.Parameter") 

  with sp
     set l_acesso               = .CreateParameter("l_acesso",            adInteger, adParamReturnValue, , null)
     set l_solicitacao          = .CreateParameter("l_solicitacao",       adInteger, adParamInput,    , p_solicitacao)
     set l_usuario              = .CreateParameter("l_usuario",           adInteger, adParamInput,    , p_usuario)
     .parameters.Append         l_acesso
     .parameters.Append         l_solicitacao
     .parameters.Append         l_usuario
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "Acesso"
     On error Resume Next
     .Execute
     p_acesso = l_acesso.Value
     
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_acesso"
     .parameters.Delete         "l_solicitacao"
     .parameters.Delete         "l_usuario"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------.

REM =========================================================================
REM Executa a função SP_GeraCPFEspecial
REM -------------------------------------------------------------------------
Sub DB_GetCNPJCPF(p_tipo, p_valor)
  Dim l_valor, l_tipo
  
  Set l_tipo             = Server.CreateObject("ADODB.Parameter") 
  Set l_valor            = Server.CreateObject("ADODB.Parameter") 

  with sp
     set l_valor            = .CreateParameter("l_valor",  adVarchar, adParamReturnValue, 20, null)
     set l_tipo             = .CreateParameter("l_tipo",   adInteger, adParamInput,    , p_tipo)
     .parameters.Append         l_valor
     .parameters.Append         l_tipo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "GeraCpfEspecial"
     On error Resume Next
     .Execute
     p_valor = l_valor.Value
     
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_valor"
     .parameters.Delete         "l_tipo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------.

REM =========================================================================
REM Verifica se o usuário informado é gestor do sistema ou do módulo ao qual
REM a solicitação pertence
REM -------------------------------------------------------------------------
Sub DB_GetGestor(p_solicitacao, p_usuario, p_acesso)
  ' Esta procedure faz chamada a uma função do banco de dados
    
  Dim l_solicitacao, l_usuario, l_acesso
  
  Set l_acesso                = Server.CreateObject("ADODB.Parameter") 
  Set l_solicitacao           = Server.CreateObject("ADODB.Parameter") 
  Set l_usuario               = Server.CreateObject("ADODB.Parameter") 

  with sp
     set l_acesso               = .CreateParameter("l_acesso",            adVarchar, adParamReturnValue, 1, null)
     set l_solicitacao          = .CreateParameter("l_solicitacao",       adInteger, adParamInput,    , p_solicitacao)
     set l_usuario              = .CreateParameter("l_usuario",           adInteger, adParamInput,    , p_usuario)
     .parameters.Append         l_acesso
     .parameters.Append         l_solicitacao
     .parameters.Append         l_usuario
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "Gestor"
     On error Resume Next
     .Execute
     p_acesso = l_acesso.Value
     
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_acesso"
     .parameters.Delete         "l_solicitacao"
     .parameters.Delete         "l_usuario"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------.

REM =========================================================================
REM Verifica se a senha está correta e se o usuário está ativo
REM -------------------------------------------------------------------------
Function DB_VerificaSenha(p_cliente, p_username, p_senha)
  Dim p_rs
  Set p_rs = Server.CreateObject("ADODB.RecordSet")

  sp_VerificaSenha p_rs, p_cliente, p_username, p_senha

  If p_rs.EOF Then
     DB_VerificaSenha = 2
  ElseIf p_rs("ativo") = "N" Then
     DB_VerificaSenha = 3
  Else
     DB_VerificaSenha = 0
  End If
  Set p_rs = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Verifica se a senha está correta e se o usuário está ativo
REM -------------------------------------------------------------------------
Function DB_VerificaAssinatura(p_cliente, p_username, p_senha)
  Dim l_rs, l_cliente, l_username, l_senha
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_username  = Server.CreateObject("ADODB.Parameter")
  Set l_senha     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente        = .CreateParameter("l_cliente",   adInteger, adParamInput,   , p_cliente)
     set l_username       = .CreateParameter("l_username",  adVarChar, adParamInput, 30, p_username)
     set l_senha          = .CreateParameter("l_senha",     adVarChar, adParamInput,255, p_senha)
     .parameters.Append         l_cliente
     .parameters.Append         l_username
     .parameters.Append         l_senha
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_VerificaAssinat"
     On Error Resume Next
     Set l_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_username"
     .Parameters.Delete         "l_senha"
  end with
  
  If l_RS.EOF Then
     DB_VerificaAssinatura = 2
  ElseIf l_RS("ativo") = "N" Then
     DB_VerificaAssinatura = 3
  Else
     DB_VerificaAssinatura = 0
  End If
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os usuários de um cliente
REM -------------------------------------------------------------------------
Sub DB_GetUserList(p_rs, p_cliente, p_localizacao, p_lotacao, p_gestor, p_nome, p_modulo, p_uf, p_ativo)
  Dim l_cliente, l_localizacao, l_lotacao, l_gestor, l_nome, l_modulo, l_uf, l_ativo
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_localizacao = Server.CreateObject("ADODB.Parameter")
  Set l_lotacao     = Server.CreateObject("ADODB.Parameter")
  Set l_gestor      = Server.CreateObject("ADODB.Parameter")
  Set l_nome        = Server.CreateObject("ADODB.Parameter")
  Set l_modulo      = Server.CreateObject("ADODB.Parameter")
  Set l_uf          = Server.CreateObject("ADODB.Parameter")
  Set l_ativo       = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente          = .CreateParameter("l_cliente",     adInteger, adParamInput,   , p_cliente)
     set l_localizacao      = .CreateParameter("l_localizacao", adInteger, adParamInput,   , tvl(p_localizacao))
     set l_lotacao          = .CreateParameter("l_lotacao",     adInteger, adParamInput,   , tvl(p_lotacao))
     set l_gestor           = .CreateParameter("l_gestor",      adVarchar, adParamInput,  1, tvl(p_gestor))
     set l_nome             = .CreateParameter("l_nome",        adVarchar, adParamInput, 60, tvl(p_nome))
     set l_modulo           = .CreateParameter("l_modulo",      adInteger, adParamInput,   , tvl(p_modulo))
     set l_uf               = .CreateParameter("l_uf",          adVarchar, adParamInput,  2, tvl(p_uf))
     set l_ativo            = .CreateParameter("l_ativo",       adVarchar, adParamInput,  1, tvl(p_ativo))
     .parameters.Append         l_cliente
     .parameters.Append         l_localizacao
     .parameters.Append         l_lotacao
     .parameters.Append         l_gestor
     .parameters.Append         l_nome
     .parameters.Append         l_modulo
     .parameters.Append         l_uf
     .parameters.Append         l_ativo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetUserList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_localizacao"
     .Parameters.Delete         "l_lotacao"
     .Parameters.Delete         "l_gestor"
     .Parameters.Delete         "l_nome"
     .Parameters.Delete         "l_modulo"
     .Parameters.Delete         "l_uf"
     .Parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os telefones de um cliente
REM -------------------------------------------------------------------------
Sub DB_GetFoneList(p_rs, p_cliente, p_chave, p_restricao)
  Dim l_cliente, l_chave, l_restricao
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente", adInteger, adParamInput, , p_cliente)
     set l_chave                = .CreateParameter("l_chave",   adInteger, adParamInput, , Tvl(p_chave))
     set l_restricao            = .CreateParameter("l_restricao", adVarChar, adParamInput, 20, Tvl(p_restricao))
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetFoneList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados do telefone informado
REM -------------------------------------------------------------------------
Sub DB_GetFoneData(p_rs, p_chave)
  Dim l_chave
  Set l_chave = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave", adInteger, adParamInput, , p_chave)
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetFoneData"
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
REM Recupera as contas bancárias de um cliente
REM -------------------------------------------------------------------------
Sub DB_GetContaBancoList(p_rs, p_cliente, p_chave, p_restricao)
  Dim l_cliente, l_chave, l_restricao
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente", adInteger, adParamInput, , p_cliente)
     set l_chave                = .CreateParameter("l_chave",   adInteger, adParamInput, , Tvl(p_chave))
     set l_restricao            = .CreateParameter("l_restricao", adVarChar, adParamInput, 20, Tvl(p_restricao))
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetBankAccList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados do endereco informado
REM -------------------------------------------------------------------------
Sub DB_GetContaBancoData(p_rs, p_chave)
  Dim l_chave
  Set l_chave = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave", adInteger, adParamInput, , p_chave)
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetBankAccData"
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
REM Recupera os endereços de um cliente
REM -------------------------------------------------------------------------
Sub DB_GetAddressList(p_rs, p_cliente, p_chave, p_restricao)
  Dim l_cliente, l_chave, l_restricao
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente", adInteger, adParamInput, , p_cliente)
     set l_chave                = .CreateParameter("l_chave",   adInteger, adParamInput, , Tvl(p_chave))
     set l_restricao            = .CreateParameter("l_restricao", adVarChar, adParamInput, 20, Tvl(p_restricao))
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetAddressList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os endereços de uma opção do menu
REM -------------------------------------------------------------------------
Sub DB_GetAddressMenu(p_rs, p_cliente, p_chave, p_restricao)
  Dim l_cliente, l_chave, l_restricao
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente", adInteger, adParamInput, , p_cliente)
     set l_chave                = .CreateParameter("l_chave",   adInteger, adParamInput, , p_chave)
     set l_restricao            = .CreateParameter("l_restricao", adVarChar, adParamInput, 20, p_restricao)
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetAddressMenu"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados do endereco informado
REM -------------------------------------------------------------------------
Sub DB_GetAddressData(p_rs, p_chave)
  Dim l_chave
  Set l_chave = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave", adInteger, adParamInput, , p_chave)
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetAddressData"
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
REM Recupera as localizações de um cliente
REM -------------------------------------------------------------------------
Sub DB_GetLocalList(p_rs, p_cliente, p_chave, p_restricao)
  Dim l_cliente, l_chave, l_restricao
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente", adInteger, adParamInput, , p_cliente)
     set l_chave                = .CreateParameter("l_chave",   adInteger, adParamInput, , p_chave)
     set l_restricao            = .CreateParameter("l_restricao", adVarChar, adParamInput, 20, p_restricao)
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLocalList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as unidades organizacionais de um cliente
REM -------------------------------------------------------------------------
Sub DB_GetUorgList(p_rs, p_cliente, p_chave, p_restricao, p_nome, p_sigla) : sp_GetUorgList p_rs, p_cliente, p_chave, p_restricao, p_nome, p_sigla : End Sub

REM =========================================================================
REM Recupera os dados do usuário logado
REM -------------------------------------------------------------------------
Sub DB_GetCompanyData(p_RS, p_cliente, p_cnpj)
  Dim l_cliente, l_cnpj
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_cnpj      = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente        = .CreateParameter("l_cliente",   adInteger, adParamInput,   , p_cliente)
     set l_cnpj           = .CreateParameter("l_cnpj",      adVarChar, adParamInput, 20, p_cnpj)
     .parameters.Append         l_cliente
     .parameters.Append         l_cnpj
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetCompanyData"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_cnpj"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os links permitidos ao usuário informado
REM -------------------------------------------------------------------------
Sub DB_GetCCData(p_rs, p_ctcc)
  Dim l_ctcc
  Set l_ctcc = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_ctcc           = .CreateParameter("l_ctcc", adInteger, adParamInput, , p_ctcc)
     .parameters.Append         l_ctcc
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetCCData"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_ctcc"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista dos tipo de unidades
REM -------------------------------------------------------------------------
Sub DB_GetUnitTypeList(p_rs, p_sq_pessoa)
  Dim l_sq_pessoa
  Set l_sq_pessoa = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_pessoa           = .CreateParameter("l_sq_pessoa", adInteger, adParamInput, , p_sq_pessoa)
     .parameters.Append         l_sq_pessoa
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetUnitTypeList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_pessoa"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista das áreas de atuações
REM -------------------------------------------------------------------------
Sub DB_GetEOAAtuac(p_rs, p_sq_pessoa)
  Dim l_sq_pessoa
  Set l_sq_pessoa = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_pessoa           = .CreateParameter("l_sq_pessoa", adInteger, adParamInput, , p_sq_pessoa)
     .parameters.Append         l_sq_pessoa
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetEOAAtuac"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_pessoa"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista unidades pais
REM -------------------------------------------------------------------------
Sub DB_GetEOUnitPaiList(p_rs, Operacao, p_sq_pessoa, p_sq_unidade)
  Dim l_Operacao, l_sq_pessoa, l_sq_unidade
  Set l_Operacao   = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pessoa  = Server.CreateObject("ADODB.Parameter")
  Set l_sq_unidade = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao            = .CreateParameter("l_Operacao",  adVarChar, adParamInput,  1,Operacao)
     set l_sq_pessoa           = .CreateParameter("l_sq_pessoa", adInteger, adParamInput,   ,Tvl(p_sq_pessoa))
     set l_sq_unidade          = .CreateParameter("l_sq_unidade",adInteger, adParamInput,   ,Tvl(p_sq_unidade))
     .parameters.Append         l_Operacao
     .parameters.Append         l_sq_pessoa
     .parameters.Append         l_sq_unidade
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetUnitPaiList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_Operacao"
     .Parameters.Delete         "l_sq_pessoa"
     .Parameters.Delete         "l_sq_unidade" 
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as pessoas vinculadas a um cliente
REM -------------------------------------------------------------------------
Sub DB_GetPersonList(p_rs, p_cliente, p_chave, p_restricao) : SP_GetPersonList p_rs, p_cliente, p_chave, p_restricao : End Sub

REM =========================================================================
REM Recupera os responsáveis pelo cumprimento de um trâmite
REM -------------------------------------------------------------------------
Sub DB_GetSolicResp(p_rs, p_chave, p_tramite, p_fase, p_restricao)
  Dim l_fase, l_chave, l_tramite, l_restricao
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_tramite   = Server.CreateObject("ADODB.Parameter")
  Set l_fase      = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",     adInteger, adParamInput,   , tvl(p_chave))
     set l_tramite              = .CreateParameter("l_tramite",   adInteger, adParamInput,   , tvl(p_tramite))
     set l_fase                 = .CreateParameter("l_fase",      advarchar, adParamInput, 20, Tvl(p_fase))
     set l_restricao            = .CreateParameter("l_restricao", adVarChar, adParamInput, 20, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_tramite
     .parameters.Append         l_fase
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSolicResp"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_tramite"
     .Parameters.Delete         "l_fase"
     .Parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados de um beneficiário
REM -------------------------------------------------------------------------
Sub DB_GetBenef(p_rs, p_cliente, p_sq_pessoa, p_cpf, p_cnpj, p_nome, p_tipo_pessoa, _
        p_passaporte_numero, p_sq_pais_passaporte)
  Dim l_cliente, l_sq_pessoa, l_cpf, l_cnpj, l_nome, l_tipo_pessoa
  Dim l_passaporte_numero, l_sq_pais_passaporte
  Set l_cliente             = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pessoa           = Server.CreateObject("ADODB.Parameter")
  Set l_cpf                 = Server.CreateObject("ADODB.Parameter")
  Set l_cnpj                = Server.CreateObject("ADODB.Parameter")
  Set l_nome                = Server.CreateObject("ADODB.Parameter")
  Set l_tipo_pessoa         = Server.CreateObject("ADODB.Parameter")
  Set l_passaporte_numero   = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pais_passaporte  = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,   , p_cliente)
     set l_sq_pessoa            = .CreateParameter("l_sq_pessoa",           adInteger, adParamInput,   , Tvl(p_sq_pessoa))
     set l_cpf                  = .CreateParameter("l_cpf",                 adVarchar, adParamInput, 14, Tvl(p_cpf))
     set l_cnpj                 = .CreateParameter("l_cnpj",                adVarchar, adParamInput, 18, Tvl(p_cnpj))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput, 20, Tvl(p_nome))
     set l_tipo_pessoa          = .CreateParameter("l_tipo_pessoa",         adInteger, adParamInput,   , Tvl(p_tipo_pessoa))
     set l_passaporte_numero    = .CreateParameter("l_passaporte_numero",   adVarchar, adParamInput, 20, Tvl(p_passaporte_numero))
     set l_sq_pais_passaporte   = .CreateParameter("l_sq_pais_passaporte",  adInteger, adParamInput,   , Tvl(p_sq_pais_passaporte))
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_pessoa
     .parameters.Append         l_cpf
     .parameters.Append         l_cnpj
     .parameters.Append         l_nome
     .parameters.Append         l_tipo_pessoa
     .parameters.Append         l_passaporte_numero
     .parameters.Append         l_sq_pais_passaporte
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetBenef"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_sq_pessoa"
     .Parameters.Delete         "l_cpf"
     .Parameters.Delete         "l_cnpj"
     .Parameters.Delete         "l_nome"
     .Parameters.Delete         "l_tipo_pessoa"
     .Parameters.Delete         "l_passaporte_numero"
     .Parameters.Delete         "l_sq_pais_passaporte"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os centros de custo do cliente
REM -------------------------------------------------------------------------
Sub DB_GetCCList(p_rs, p_cliente, p_chave, p_restricao) : sp_GetCCList p_rs, p_cliente, p_chave, p_restricao : End Sub

REM =========================================================================
REM Recupera os centros de custo do cliente
REM -------------------------------------------------------------------------
Sub DB_GetCCTree(p_rs, p_cliente, p_restricao)
  Dim l_cliente, l_restricao
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente        = .CreateParameter("l_cliente",   adInteger, adParamInput, , p_cliente)
     set l_restricao      = .CreateParameter("l_restricao", adVarChar, adParamInput, 10, p_restricao)
     .parameters.Append         l_cliente
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetCCTree"
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
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os centros de custo permitidos ao usuário informado
REM -------------------------------------------------------------------------
Sub DB_GetCCTreeVision(p_rs, p_cliente, p_pessoa, p_menu, p_restricao)
  Dim l_cliente, l_pessoa, l_menu, l_restricao
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa    = Server.CreateObject("ADODB.Parameter")
  Set l_menu      = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente        = .CreateParameter("l_cliente",   adInteger, adParamInput, , p_cliente)
     set l_pessoa         = .CreateParameter("l_pessoa",    adInteger, adParamInput, , tvl(p_pessoa))
     set l_menu           = .CreateParameter("l_menu",      adInteger, adParamInput, , tvl(p_menu))
     set l_restricao      = .CreateParameter("l_restricao", adVarChar, adParamInput, 10, p_restricao)
     .parameters.Append         l_cliente
     .parameters.Append         l_pessoa
     .parameters.Append         l_menu
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetCCTreeVision"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_pessoa"
     .Parameters.Delete         "l_menu"
     .Parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as formas de pagamento válidas para o serviço informado
REM -------------------------------------------------------------------------
Sub DB_GetFormaPagamento(p_rs, p_cliente, p_chave, p_chave_aux, p_restricao)
  Dim l_cliente, l_chave, l_chave_aux, l_restricao
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_chave    = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux      = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente        = .CreateParameter("l_cliente",   adInteger, adParamInput,   , p_cliente)
     set l_chave          = .CreateParameter("l_chave",     adInteger, adParamInput,   , tvl(p_chave))
     set l_chave_aux      = .CreateParameter("l_chave_aux", adVarChar, adParamInput, 10, tvl(p_chave_aux))
     set l_restricao      = .CreateParameter("l_restricao", adVarChar, adParamInput, 10, p_restricao)
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetFormaPagamento"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
     .Parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os centros de custo aos quais o atual pode ser subordinado
REM -------------------------------------------------------------------------
Sub DB_GetCCSubordination(p_rs, p_cliente, p_sqcc, p_restricao)
  Dim l_ctcc, l_cliente, l_restricao
  Set l_ctcc      = Server.CreateObject("ADODB.Parameter")
  Set l_cliente   = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente        = .CreateParameter("l_cliente",   adInteger, adParamInput, , p_cliente)
     set l_ctcc           = .CreateParameter("l_ctcc",      adInteger, adParamInput, , tvl(p_sqcc))
     set l_restricao      = .CreateParameter("l_restricao", adVarChar, adParamInput, 5, p_restricao)
     .parameters.Append         l_cliente
     .parameters.Append         l_ctcc
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetCCSubordinat"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_ctcc"
     .Parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os bancos existentes
REM -------------------------------------------------------------------------
Sub DB_GetBankList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetBankList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as etnias existentes
REM -------------------------------------------------------------------------
Sub DB_GetEtniaList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetEtniaList"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os idiomas existentes
REM -------------------------------------------------------------------------
Sub DB_GetIdiomList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetIdiomList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as formações existentes
REM -------------------------------------------------------------------------
Sub DB_GetFormationList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetFormatList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os grupos de deficiência existentes
REM -------------------------------------------------------------------------
Sub DB_GetDeficGroupList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetDeficGrpList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os grupos de deficiência existentes
REM -------------------------------------------------------------------------
Sub DB_GetDeficiencyList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetDefList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista de estados civis
REM -------------------------------------------------------------------------
Sub DB_GetCivStateList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetCivStateList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os tipos de endereços existentes
REM -------------------------------------------------------------------------
Sub DB_GetAdressTypeList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetAdressTPList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os tipos de pessoas existentes
REM -------------------------------------------------------------------------
Sub DB_GetUserTypeList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetUserTypeList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os tipos de telefones existentes
REM -------------------------------------------------------------------------
Sub DB_GetFoneTypeList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetFoneTypeList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------



REM =========================================================================
REM Recupera as agências existentes
REM -------------------------------------------------------------------------
Sub DB_GetBankHouseList(p_rs, p_sq_banco, p_nome, p_ordena)
  Dim l_sq_banco
  Set l_sq_banco = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_banco             = .CreateParameter("l_sq_banco", adInteger, adParamInput, , p_sq_banco)
     .parameters.Append         l_sq_banco
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetBankHousList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_banco"
     If Not IsNull(p_nome) Then 
        p_rs.Filter                = "nome like '*" & p_nome & "*'"
     End If
     If p_ordena > "" Then
        p_rs.sort               = p_ordena
     Else
        p_rs.sort               = "codigo"
     End If
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os paises existentes
REM -------------------------------------------------------------------------
Sub DB_GetCountryList(p_rs) : SP_GetCountryList p_rs : End Sub

REM =========================================================================
REM Recupera as regiões existentes
REM -------------------------------------------------------------------------
Sub DB_GetRegionList(p_rs, p_sq_pais, p_tipo) : SP_GetRegionList p_rs, p_pais, p_tipo : End Sub

REM =========================================================================
REM Recupera as cidades existentes em relação a um país
REM -------------------------------------------------------------------------
Sub DB_GetStateList(p_rs, p_sq_pais) : sp_GetStateList p_rs, p_sq_pais : End Sub

REM =========================================================================
REM Recupera as cidades existentes
REM -------------------------------------------------------------------------
Sub DB_GetCityList(p_rs, p_sq_pais, p_estado) : sp_GetCityList p_rs, p_sq_pais, p_estado : End Sub

REM =========================================================================
REM Recupera os dados do banco
REM -------------------------------------------------------------------------
Sub DB_GetBankData(p_rs, p_sq_banco)
  Dim l_sq_banco
  Set l_sq_banco = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_banco           = .CreateParameter("l_sq_banco", adInteger, adParamInput, , p_sq_banco)
     .parameters.Append         l_sq_banco
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetBankData"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_banco"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados da etnia
REM -------------------------------------------------------------------------
Sub DB_GetEtniaData(p_rs, p_sq_etnia)
  Dim l_sq_etnia
  Set l_sq_etnia = Server.CreateObject("ADODB.Parameter")
  with sp
     Set l_sq_etnia           = .CreateParameter("l_sq_etnia", adInteger, adParamInput, , p_sq_etnia)
     .parameters.Append         l_sq_etnia
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetEtniaData"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_etnia"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados do idioma
REM -------------------------------------------------------------------------
Sub DB_GetIdiomData(p_rs, p_sq_idioma)
  Dim l_sq_idioma
  Set l_sq_idioma = Server.CreateObject("ADODB.Parameter")
  with sp
     Set l_sq_idioma           = .CreateParameter("l_sq_idioma", adInteger, adParamInput, , p_sq_idioma)
     .parameters.Append         l_sq_idioma
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetIdiomData"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_idioma"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados da formação
REM -------------------------------------------------------------------------
Sub DB_GetFormationData(p_rs, p_sq_formacao)
  Dim l_sq_formacao
  Set l_sq_formacao = Server.CreateObject("ADODB.Parameter")
  with sp
     Set l_sq_formacao          = .CreateParameter("l_sq_formacao", adInteger, adParamInput, , p_sq_formacao)
     .parameters.Append         l_sq_formacao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetFormatData"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_formacao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados do grupo de dificiência
REM -------------------------------------------------------------------------
Sub DB_GetDeficiencyGroupData(p_rs, p_sq_grupo_deficiencia)
  Dim l_sq_grupo_deficiencia
  Set l_sq_grupo_deficiencia = Server.CreateObject("ADODB.Parameter")
  with sp
     Set l_sq_grupo_deficiencia = .CreateParameter("l_sq_grupo_deficiencia", adInteger, adParamInput, , p_sq_grupo_deficiencia)
     .parameters.Append         l_sq_grupo_deficiencia
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetDefGrpData"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_grupo_deficiencia"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados da deficiência
REM -------------------------------------------------------------------------
Sub DB_GetDeficiencyData(p_rs, p_sq_deficiencia)
  Dim l_sq_deficiencia
  Set l_sq_deficiencia = Server.CreateObject("ADODB.Parameter")
  with sp
     Set l_sq_deficiencia       = .CreateParameter("l_sq_deficiencia", adInteger, adParamInput, , p_sq_deficiencia)
     .parameters.Append         l_sq_deficiencia
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetDefData"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_deficiencia"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados do tipo de endereço
REM -------------------------------------------------------------------------
Sub DB_GetAdressTypeData(p_rs, p_sq_tipo_endereco)
  Dim l_sq_tipo_endereco
  Set l_sq_tipo_endereco = Server.CreateObject("ADODB.Parameter")
  with sp
     Set l_sq_tipo_endereco     = .CreateParameter("l_sq_tipo_endereco", adInteger, adParamInput, , p_sq_tipo_endereco)
     .parameters.Append         l_sq_tipo_endereco
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetAdressTPData"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_tipo_endereco"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados do tipo de pessoa
REM -------------------------------------------------------------------------
Sub DB_GetUserTypeData(p_rs, p_sq_tipo_pessoa)
  Dim l_sq_tipo_pessoa
  Set l_sq_tipo_pessoa = Server.CreateObject("ADODB.Parameter")
  with sp
     Set l_sq_tipo_pessoa       = .CreateParameter("l_sq_tipo_pessoa", adInteger, adParamInput, , p_sq_tipo_pessoa)
     .parameters.Append         l_sq_tipo_pessoa
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetUserTypeData"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_tipo_pessoa"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados do tipo de telefone
REM -------------------------------------------------------------------------
Sub DB_GetFoneTypeData(p_rs, p_sq_tipo_telefone)
  Dim l_sq_tipo_telefone
  Set l_sq_tipo_telefone = Server.CreateObject("ADODB.Parameter")
  with sp
     Set l_sq_tipo_telefone       = .CreateParameter("l_sq_tipo_telefone", adInteger, adParamInput, , p_sq_tipo_telefone)
     .parameters.Append         l_sq_tipo_telefone
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetFoneTypeData"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_tipo_telefone"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------



REM =========================================================================
REM Recupera os dados da agência bancária
REM -------------------------------------------------------------------------
Sub DB_GetBankHouseData(p_rs, p_sq_agencia)
  Dim l_sq_agencia
  Set l_sq_agencia = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_agencia           = .CreateParameter("l_sq_agencia", adInteger, adParamInput, , p_sq_agencia)
     .parameters.Append         l_sq_agencia
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetBankHousData"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_agencia"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados do pais
REM -------------------------------------------------------------------------
Sub DB_GetCountryData(p_rs, p_sq_pais) : sp_GetCountryData p_rs, p_sq_pais : End Sub

REM =========================================================================
REM Recupera os dados da região
REM -------------------------------------------------------------------------
Sub DB_GetRegionData(p_rs, p_sq_regiao) : sp_GetRegionData p_rs, p_sq_regiao : End Sub

REM =========================================================================
REM Recupera os dados do estado
REM -------------------------------------------------------------------------
Sub DB_GetStateData(p_rs, p_sq_pais, p_co_uf) : sp_GetStateData p_rs, p_sq_pais, p_co_uf : End Sub

REM =========================================================================
REM Recupera os dados da cidade
REM -------------------------------------------------------------------------
Sub DB_GetCityData(p_rs, p_sq_cidade) : sp_GetCityData p_rs, p_sq_cidade : End Sub

REM =========================================================================
REM Recupera os idiomas existentes
REM -------------------------------------------------------------------------
Sub DB_GetKindPersonList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetKindPersList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os tipos de vínculos
REM -------------------------------------------------------------------------
Sub DB_GetVincKindList(p_rs, p_cliente)
  Dim l_cliente
  Set l_cliente = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente             = .CreateParameter("l_cliente", adInteger, adParamInput, , p_cliente)
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetVincKindList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os tipos de postos
REM -------------------------------------------------------------------------
Sub DB_GetTipoPostoList(p_rs, p_cliente, p_chave)
  Dim l_cliente, l_chave
  Set l_cliente = Server.CreateObject("ADODB.Parameter")
  Set l_chave   = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente             = .CreateParameter("l_cliente", adInteger, adParamInput, , p_cliente)
     set l_chave               = .CreateParameter("l_chave",   adInteger, adParamInput, , tvl(p_chave))
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetTipoPostoList"
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
REM Recupera os tipos de vínculos
REM -------------------------------------------------------------------------
Sub DB_GetVincKindData(p_rs, p_sq_tipo_vinculo)
  Dim l_sq_tipo_vinculo
  Set l_sq_tipo_vinculo = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_tipo_vinculo             = .CreateParameter("l_sq_tipo_vinculo", adInteger, adParamInput, , p_sq_tipo_vinculo)
     .parameters.Append         l_sq_tipo_vinculo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetVincKindData"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_tipo_vinculo"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as solicitações desejadas
REM -------------------------------------------------------------------------
Sub DB_GetSolicList(p_rs, p_menu, p_pessoa, p_restricao, p_tipo, _
    p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
    p_unidade, p_prioridade, p_ativo, p_proponente, _
    p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
    p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, _
    p_acao_ppa, p_orprior)

    sp_GetSolicList p_rs, p_menu, p_pessoa, p_restricao, p_tipo, _
    p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
    p_unidade, p_prioridade, p_ativo, p_proponente, _
    p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
    p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, _
    p_acao_ppa, p_orprior
End Sub

REM =========================================================================
REM Recupera os dados de uma solicitacao
REM -------------------------------------------------------------------------
Sub DB_GetSolicData(p_rs, p_chave, p_restricao)
  Dim l_chave, l_restricao
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,   , p_chave)
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar, adParamInput, 20, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSolicData"
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
REM Recupera as etapas de um projeto
REM -------------------------------------------------------------------------
Sub DB_GetSolicEtapa(p_rs, p_chave, p_chave_aux, p_restricao)
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
     .CommandText               = Session("schema") & "SP_GetSolicEtapa"
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
REM Recupera as etapas irmãs da etapa informada
REM -------------------------------------------------------------------------
Sub DB_GetEtapaOrder(p_rs, p_chave, p_chave_aux)
  Dim l_chave, l_chave_aux
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , p_chave)
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adInteger, adParamInput,   , Tvl(p_chave_aux))
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetEtapaOrder"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados da etapa superior à que foi informada
REM -------------------------------------------------------------------------
Sub DB_GetEtapaDataParent(p_rs, p_chave)
  Dim l_chave
  Set l_chave   = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",   adInteger, adParamInput,   , p_chave)
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetEtpDataPrnt"
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
REM Recupera os dados das etapas superiores à que foi informada
REM -------------------------------------------------------------------------
Sub DB_GetEtapaDataParents(p_rs, p_chave)
  Dim l_chave
  Set l_chave   = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",   adInteger, adParamInput,   , p_chave)
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetEtpDataPrnts"
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
REM Recupera os recursos de um projeto
REM -------------------------------------------------------------------------
Sub DB_GetSolicRecurso(p_rs, p_chave, p_chave_aux, p_restricao)
  Dim l_chave, l_chave_aux, l_restricao
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , p_chave)
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao            = .CreateParameter("l_restricao",     adVarchar, adParamInput, 20, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSolicRecurso"
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
REM Recupera os recursos de um projeto
REM -------------------------------------------------------------------------
Sub DB_GetSolicEtpRec(p_rs, p_chave, p_chave_aux)
  Dim l_chave, l_chave_aux
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , p_chave)
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adInteger, adParamInput,   , Tvl(p_chave_aux))
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSolicEtpRec"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os anexos de uma solicitação
REM -------------------------------------------------------------------------
Sub DB_GetSolicAnexo(p_rs, p_chave, p_chave_aux, p_cliente)
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
     .CommandText               = Session("schema") & "SP_GetSolicAnexo"
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

REM =========================================================================
REM Recupera os interessados em uma solicitação
REM -------------------------------------------------------------------------
Sub DB_GetSolicInter(p_rs, p_chave, p_chave_aux, p_restricao)
  Dim l_chave, l_chave_aux, l_restricao
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , p_chave)
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao            = .CreateParameter("l_restricao",     adVarchar, adParamInput, 20, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSolicInter"
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
REM Recupera as áreas envolvidas na execução de uma solicitação
REM -------------------------------------------------------------------------
Sub DB_GetSolicAreas(p_rs, p_chave, p_chave_aux, p_restricao)
  Dim l_chave, l_chave_aux, l_restricao
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , p_chave)
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao            = .CreateParameter("l_restricao",     adVarchar, adParamInput, 20, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSolicAreas"
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
REM Recupera os encaminhamentos de uma solicitação
REM -------------------------------------------------------------------------
Sub DB_GetSolicLog(p_rs, p_chave, p_chave_aux, p_restricao)
  Dim l_chave, l_chave_aux, l_restricao
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , p_chave)
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao            = .CreateParameter("l_restricao",     adVarchar, adParamInput, 20, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSolicLog"
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
REM Recupera o código de uma opção do menu a partir da sigla
REM -------------------------------------------------------------------------
Sub DB_GetMenuCode(p_rs, p_cliente, p_sigla)
  Dim l_cliente, l_sigla
  Set l_cliente = Server.CreateObject("ADODB.Parameter")
  Set l_sigla   = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente",      adInteger, adParamInput,   , p_cliente)
     set l_sigla                = .CreateParameter("l_sigla",        adVarchar, adParamInput, 20, p_sigla)
     .parameters.Append         l_cliente
     .parameters.Append         l_sigla
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetMenuCode"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_sigla"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a atualização mensal das metas
REM -------------------------------------------------------------------------
Sub DB_GetEtapaMensal(p_rs, p_chave)
  Dim l_chave
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , p_chave)
     .parameters.Append         l_chave
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetEtapaMensal"
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
REM Recupera os tipos de apoios
REM -------------------------------------------------------------------------
Sub DB_GetTipoApoioList(p_rs, p_cliente, p_chave, p_nome, p_sigla, p_ativo)
  Dim l_cliente, l_chave, l_nome, l_sigla, l_ativo
  Set l_cliente = Server.CreateObject("ADODB.Parameter")
  Set l_chave   = Server.CreateObject("ADODB.Parameter")
  Set l_nome    = Server.CreateObject("ADODB.Parameter")
  Set l_sigla   = Server.CreateObject("ADODB.Parameter")
  Set l_ativo   = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente             = .CreateParameter("l_cliente", adInteger, adParamInput,   , p_cliente)
     set l_chave               = .CreateParameter("l_chave",   adInteger, adParamInput,   , tvl(p_chave))
     set l_nome                = .CreateParameter("l_nome",    adVarchar, adParamInput, 50, tvl(p_nome))
     set l_sigla               = .CreateParameter("l_sigla",   adVarchar, adParamInput, 10, tvl(p_sigla))
     set l_ativo               = .CreateParameter("l_ativo",   adVarchar, adParamInput,  1, tvl(p_ativo))
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_nome
     .parameters.Append         l_sigla
     .parameters.Append         l_ativo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetTipoApoioList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_nome"
     .Parameters.Delete         "l_sigla"
     .Parameters.Delete         "l_ativo"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista de apoios de um projeto
REM -------------------------------------------------------------------------
Sub DB_GetSolicApoioList(p_rs, p_chave, p_chave_aux, p_restricao)
  Dim l_chave, l_chave_aux, l_restricao
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux    = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,   , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",   adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar, adParamInput, 50, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSolicApoioList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

