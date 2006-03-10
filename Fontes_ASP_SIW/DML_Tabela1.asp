<%
REM =========================================================================
REM Manipula registros de CO_Tipo_Vinculo
REM -------------------------------------------------------------------------
Sub DML_COTipoVinc(Operacao, Chave, sq_tipo_pessoa, cliente, nome, interno, contratado, padrao, ativo)
  Dim l_Operacao, l_Chave, l_sq_tipo_pessoa, l_cliente, l_nome, l_interno, l_contratado, l_padrao, l_ativo
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_sq_tipo_pessoa  = Server.CreateObject("ADODB.Parameter")
  Set l_cliente         = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  Set l_interno         = Server.CreateObject("ADODB.Parameter")
  Set l_contratado      = Server.CreateObject("ADODB.Parameter")
  Set l_padrao          = Server.CreateObject("ADODB.Parameter")
  Set l_ativo           = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",      adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,    , Tvl(chave))
     set l_sq_tipo_pessoa       = .CreateParameter("l_sq_tipo_pessoa",adInteger, adParamInput,    , Tvl(sq_tipo_pessoa))
     set l_cliente              = .CreateParameter("l_cliente",       adInteger, adParamInput,    , Tvl(cliente))
     set l_nome                 = .CreateParameter("l_nome",          adVarChar, adParamInput,  20, nome)
     set l_interno              = .CreateParameter("l_interno",       adVarChar, adParamInput,   1, interno)
     set l_contratado           = .CreateParameter("l_contratado",    adVarChar, adParamInput,   1, contratado)
     set l_padrao               = .CreateParameter("l_padrao",        adVarChar, adParamInput,   1, padrao)
     set l_ativo                = .CreateParameter("l_ativo",         adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_tipo_pessoa
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_interno
     .parameters.Append         l_contratado
     .parameters.Append         l_padrao
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutCOTipoVinc"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_tipo_pessoa"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_interno"
     .parameters.Delete         "l_contratado"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Manipula registros de siw_cliente
REM -------------------------------------------------------------------------
Sub DML_SIWCliConf(Chave, tamanho_minimo_senha, tamanho_maximo_senha, maximo_tentativas, _
        dias_vigencia_senha, dias_aviso_expiracao, smtp_server, siw_email_nome, siw_email_conta, _
        siw_email_senha, logo, logo1, fundo, tipo, upload_maximo)
  Dim l_Chave, l_tamanho_minimo_senha, l_tamanho_maximo_senha, l_maximo_tentativas, l_dias_vigencia_senha, l_dias_aviso_expiracao
  Dim l_smtp_server, l_siw_email_nome, l_siw_email_conta, l_siw_email_senha, l_logo, l_logo1, l_fundo, l_tipo, l_upload_maximo
  Set l_Chave                 = Server.CreateObject("ADODB.Parameter")
  Set l_tamanho_minimo_senha  = Server.CreateObject("ADODB.Parameter")
  Set l_tamanho_maximo_senha  = Server.CreateObject("ADODB.Parameter")
  Set l_maximo_tentativas     = Server.CreateObject("ADODB.Parameter")
  Set l_dias_vigencia_senha   = Server.CreateObject("ADODB.Parameter")
  Set l_dias_aviso_expiracao  = Server.CreateObject("ADODB.Parameter")
  Set l_dias_aviso_expiracao  = Server.CreateObject("ADODB.Parameter")
  Set l_smtp_server           = Server.CreateObject("ADODB.Parameter")
  Set l_siw_email_nome        = Server.CreateObject("ADODB.Parameter")
  Set l_siw_email_conta       = Server.CreateObject("ADODB.Parameter")
  Set l_siw_email_senha       = Server.CreateObject("ADODB.Parameter")
  Set l_logo                  = Server.CreateObject("ADODB.Parameter")
  Set l_logo1                 = Server.CreateObject("ADODB.Parameter")
  Set l_fundo                 = Server.CreateObject("ADODB.Parameter")
  Set l_tipo                  = Server.CreateObject("ADODB.Parameter")
  Set l_upload_maximo         = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , chave)
     set l_tamanho_minimo_senha = .CreateParameter("l_tamanho_minimo_senha",adInteger, adParamInput,    , Tvl(tamanho_minimo_senha))
     set l_tamanho_maximo_senha = .CreateParameter("l_tamanho_maximo_senha",adInteger, adParamInput,    , Tvl(tamanho_maximo_senha))
     set l_maximo_tentativas    = .CreateParameter("l_maximo_tentativas",   adInteger, adParamInput,    , Tvl(maximo_tentativas))
     set l_dias_vigencia_senha  = .CreateParameter("l_dias_vigencia_senha", adInteger, adParamInput,    , Tvl(dias_vigencia_senha))
     set l_dias_aviso_expiracao = .CreateParameter("l_dias_aviso_expiracao",adInteger, adParamInput,    , Tvl(dias_aviso_expiracao))
     set l_dias_aviso_expiracao = .CreateParameter("l_dias_aviso_expiracao",adInteger, adParamInput,    , Tvl(dias_aviso_expiracao))
     set l_smtp_server          = .CreateParameter("smtp_server",           adVarChar, adParamInput,  60, Tvl(smtp_server))
     set l_siw_email_nome       = .CreateParameter("siw_email_nome",        adVarChar, adParamInput,  60, Tvl(siw_email_nome))
     set l_siw_email_conta      = .CreateParameter("siw_email_conta",       adVarChar, adParamInput,  60, Tvl(siw_email_conta))
     set l_siw_email_senha      = .CreateParameter("siw_email_senha",       adVarChar, adParamInput,  60, Tvl(siw_email_senha))
     set l_logo                 = .CreateParameter("logo",                  adVarChar, adParamInput,  60, Tvl(logo))
     set l_logo1                = .CreateParameter("logo1",                 adVarChar, adParamInput,  60, Tvl(logo1))
     set l_fundo                = .CreateParameter("fundo",                 adVarChar, adParamInput,  60, Tvl(fundo))
     set l_tipo                 = .CreateParameter("tipo",                  adVarChar, adParamInput,  15, tipo)
     set l_upload_maximo        = .CreateParameter("l_upload_maximo",       adInteger, adParamInput,    , upload_maximo)
     .parameters.Append         l_Chave
     .parameters.Append         l_tamanho_minimo_senha
     .parameters.Append         l_tamanho_maximo_senha
     .parameters.Append         l_maximo_tentativas
     .parameters.Append         l_dias_vigencia_senha
     .parameters.Append         l_dias_aviso_expiracao
     .parameters.Append         l_smtp_server
     .parameters.Append         l_siw_email_nome
     .parameters.Append         l_siw_email_conta
     .parameters.Append         l_siw_email_senha
     .parameters.Append         l_logo
     .parameters.Append         l_logo1
     .parameters.Append         l_fundo
     .parameters.Append         l_tipo
     .parameters.Append         l_upload_maximo
     .CommandText               = Session("schema") & "SP_PutSIWCliConf"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_tamanho_minimo_senha"
     .parameters.Delete         "l_tamanho_maximo_senha"
     .parameters.Delete         "l_maximo_tentativas"
     .parameters.Delete         "l_dias_vigencia_senha"
     .parameters.Delete         "l_dias_aviso_expiracao"
     .parameters.Delete         "l_smtp_server"
     .parameters.Delete         "l_siw_email_nome"
     .parameters.Delete         "l_siw_email_conta"
     .parameters.Delete         "l_siw_email_senha"
     .parameters.Delete         "l_logo"
     .parameters.Delete         "l_logo1"
     .parameters.Delete         "l_fundo"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_upload_maximo"
  end with
End Sub

REM =========================================================================
REM Manipula registros para integraчуo
REM -------------------------------------------------------------------------
Sub DML_PutCodigoExterno(p_cliente, p_restricao, p_chave, p_chave_externa, p_chave_aux)
  Dim l_cliente, l_restricao, l_chave, l_chave_externa, l_chave_aux
  Set l_cliente       = Server.CreateObject("ADODB.Parameter")
  Set l_restricao     = Server.CreateObject("ADODB.Parameter")
  Set l_chave         = Server.CreateObject("ADODB.Parameter")
  Set l_chave_externa = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente",       adInteger, adParamInput,    , Tvl(p_cliente))
     set l_restricao            = .CreateParameter("l_restricao",     adVarchar, adParamInput,  20, p_restricao)
     set l_chave                = .CreateParameter("l_chave",         adVarChar, adParamInput, 255, p_chave)
     set l_chave_externa        = .CreateParameter("l_chave_externa", adVarchar, adParamInput, 255, p_chave_externa)
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adVarchar, adParamInput, 255, p_chave_aux)     
     .parameters.Append         l_cliente
     .parameters.Append         l_restricao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_externa
     .parameters.Append         l_chave_aux
     .CommandText               = Session("schema") & "SP_PutCodigoExterno"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_restricao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_externa"
     .parameters.Delete         "l_chave_aux"
  end with
End Sub
%>