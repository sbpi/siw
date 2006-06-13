<%
REM =========================================================================
REM Manipula clientes do SIW
REM -------------------------------------------------------------------------
Sub DML_PutSiwCliente(Operacao, p_chave, p_cliente, p_nome, p_nome_resumido, p_inicio_atividade, _
        p_cnpj, p_sede, p_inscricao_estadual, p_cidade, p_minimo_senha, p_maximo_senha, _
        p_dias_vigencia, p_aviso_expiracao, p_maximo_tentativas, p_agencia_padrao, p_segmento)
  Dim l_Operacao, l_Chave, l_cliente, l_nome, l_nome_resumido, l_inicio_atividade
  Dim l_cnpj, l_sede, l_inscricao_estadual, l_cidade, l_minimo_senha, l_maximo_senha
  Dim l_dias_vigencia, l_aviso_expiracao, l_maximo_tentativas, l_agencia_padrao, l_segmento
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente             = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome_resumido       = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio_atividade    = Server.CreateObject("ADODB.Parameter") 
  Set l_cnpj                = Server.CreateObject("ADODB.Parameter") 
  Set l_sede                = Server.CreateObject("ADODB.Parameter") 
  Set l_inscricao_estadual  = Server.CreateObject("ADODB.Parameter") 
  Set l_cidade              = Server.CreateObject("ADODB.Parameter") 
  Set l_minimo_senha        = Server.CreateObject("ADODB.Parameter") 
  Set l_maximo_senha        = Server.CreateObject("ADODB.Parameter") 
  Set l_dias_vigencia       = Server.CreateObject("ADODB.Parameter") 
  Set l_aviso_expiracao     = Server.CreateObject("ADODB.Parameter") 
  Set l_maximo_tentativas   = Server.CreateObject("ADODB.Parameter") 
  Set l_agencia_padrao      = Server.CreateObject("ADODB.Parameter") 
  Set l_segmento            = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,    , p_cliente)
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  60, p_nome)
     set l_nome_resumido        = .CreateParameter("l_nome_resumido",       adVarchar, adParamInput,  15, p_nome_resumido)
     set l_inicio_atividade     = .CreateParameter("l_inicio_atividade",    adDate,    adParamInput,    , p_inicio_atividade)
     set l_cnpj                 = .CreateParameter("l_cnpj",                adVarchar, adParamInput,  18, p_cnpj)
     set l_sede                 = .CreateParameter("l_sede",                adVarchar, adParamInput,   1, p_sede)
     set l_inscricao_estadual   = .CreateParameter("l_inscricao_estadual",  adVarchar, adParamInput,  20, p_inscricao_estadual)
     set l_cidade               = .CreateParameter("l_cidade",              adInteger, adParamInput,    , p_cidade)
     set l_minimo_senha         = .CreateParameter("l_minimo_senha",        adInteger, adParamInput,    , p_minimo_senha)
     set l_maximo_senha         = .CreateParameter("l_maximo_senha",        adInteger, adParamInput,    , p_maximo_senha)
     set l_dias_vigencia        = .CreateParameter("l_dias_vigencia",       adInteger, adParamInput,    , p_dias_vigencia)
     set l_aviso_expiracao      = .CreateParameter("l_aviso_expiracao",     adInteger, adParamInput,    , p_aviso_expiracao)
     set l_maximo_tentativas    = .CreateParameter("l_maximo_tentativas",   adInteger, adParamInput,    , p_maximo_tentativas)
     set l_agencia_padrao       = .CreateParameter("l_agencia_padrao",      adInteger, adParamInput,    , p_agencia_padrao)
     set l_segmento             = .CreateParameter("l_segmento",            adInteger, adParamInput,    , p_segmento)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_nome_resumido
     .parameters.Append         l_inicio_atividade
     .parameters.Append         l_cnpj
     .parameters.Append         l_sede
     .parameters.Append         l_inscricao_estadual
     .parameters.Append         l_cidade
     .parameters.Append         l_minimo_senha
     .parameters.Append         l_maximo_senha
     .parameters.Append         l_dias_vigencia
     .parameters.Append         l_aviso_expiracao
     .parameters.Append         l_maximo_tentativas
     .parameters.Append         l_agencia_padrao
     .parameters.Append         l_segmento
     .CommandText               = Session("schema") & "SP_PutSiwCliente"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_nome_resumido"
     .parameters.Delete         "l_inicio_atividade"
     .parameters.Delete         "l_cnpj"
     .parameters.Delete         "l_sede"
     .parameters.Delete         "l_inscricao_estadual"
     .parameters.Delete         "l_cidade"
     .parameters.Delete         "l_minimo_senha"
     .parameters.Delete         "l_maximo_senha"
     .parameters.Delete         "l_dias_vigencia"
     .parameters.Delete         "l_aviso_expiracao"
     .parameters.Delete         "l_maximo_tentativas"
     .parameters.Delete         "l_agencia_padrao"
     .parameters.Delete         "l_segmento"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula usuários do SIW
REM -------------------------------------------------------------------------
Sub DML_PutSiwUsuario(Operacao, p_chave, p_cliente, p_nome, p_nome_resumido, p_vinculo, _
         p_tipo_pessoa, p_unidade, p_localizacao, p_username, p_email, p_gestor_seguranca, _
         p_gestor_sistema)
  Dim l_Operacao, l_Chave, l_cliente, l_nome, l_nome_resumido, l_vinculo
  Dim l_tipo_pessoa, l_unidade, l_localizacao, l_username, l_email, l_gestor_seguranca
  Dim l_gestor_sistema
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente             = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome_resumido       = Server.CreateObject("ADODB.Parameter") 
  Set l_vinculo             = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_pessoa         = Server.CreateObject("ADODB.Parameter") 
  Set l_unidade             = Server.CreateObject("ADODB.Parameter") 
  Set l_localizacao         = Server.CreateObject("ADODB.Parameter") 
  Set l_username            = Server.CreateObject("ADODB.Parameter") 
  Set l_email               = Server.CreateObject("ADODB.Parameter") 
  Set l_gestor_seguranca    = Server.CreateObject("ADODB.Parameter") 
  Set l_gestor_sistema      = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,    , p_cliente)
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  60, p_nome)
     set l_nome_resumido        = .CreateParameter("l_nome_resumido",       adVarchar, adParamInput,  15, p_nome_resumido)
     set l_vinculo              = .CreateParameter("l_vinculo",             adInteger, adParamInput,    , p_vinculo)
     set l_tipo_pessoa          = .CreateParameter("l_tipo_pessoa",         adVarchar, adParamInput,  15, p_tipo_pessoa)
     set l_unidade              = .CreateParameter("l_unidade",             adInteger, adParamInput,    , p_unidade)
     set l_localizacao          = .CreateParameter("l_localizacao",         adInteger, adParamInput,    , p_localizacao)
     set l_username             = .CreateParameter("l_username",            adVarchar, adParamInput,  30, p_username)
     set l_email                = .CreateParameter("l_email",               adVarchar, adParamInput,  60, p_email)
     set l_gestor_seguranca     = .CreateParameter("l_gestor_seguranca",    adVarchar, adParamInput,   1, p_gestor_seguranca)
     set l_gestor_sistema       = .CreateParameter("l_gestor_sistema",      adVarchar, adParamInput,   1, p_gestor_sistema)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_nome_resumido
     .parameters.Append         l_vinculo
     .parameters.Append         l_tipo_pessoa
     .parameters.Append         l_unidade
     .parameters.Append         l_localizacao
     .parameters.Append         l_username
     .parameters.Append         l_email
     .parameters.Append         l_gestor_seguranca
     .parameters.Append         l_gestor_sistema
     .CommandText               = Session("schema") & "SP_PutSiwUsuario"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_nome_resumido"
     .parameters.Delete         "l_vinculo"
     .parameters.Delete         "l_tipo_pessoa"
     .parameters.Delete         "l_unidade"
     .parameters.Delete         "l_localizacao"
     .parameters.Delete         "l_username"
     .parameters.Delete         "l_email"
     .parameters.Delete         "l_gestor_seguranca"
     .parameters.Delete         "l_gestor_sistema"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de SIW_MENU_ENDERECO
REM -------------------------------------------------------------------------
Sub DML_SiwMenEnd(Operacao, p_Menu, p_Endereco)
  Dim l_Operacao, l_Chave, l_cliente, l_Menu, l_Endereco
  
  Set l_Operacao         = Server.CreateObject("ADODB.Parameter")
  Set l_Menu             = Server.CreateObject("ADODB.Parameter") 
  Set l_Endereco         = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_Menu                 = .CreateParameter("l_Menu",        adInteger, adParamInput,    , p_Menu)
     set l_Endereco             = .CreateParameter("l_Endereco",    adInteger, adParamInput,    , Tvl(p_Endereco))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Menu
     .parameters.Append         l_Endereco
     .CommandText               = Session("schema") & "SP_PutSiwMenEnd"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Menu"
     .parameters.Delete         "l_Endereco"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de SG_PERFIL_MENU
REM -------------------------------------------------------------------------
Sub DML_SgPerMen(Operacao, p_Perfil, p_Menu, p_Endereco)
  Dim l_Operacao, l_Chave, l_cliente, l_Perfil, l_Menu, l_Endereco
  
  Set l_Operacao         = Server.CreateObject("ADODB.Parameter")
  Set l_Perfil           = Server.CreateObject("ADODB.Parameter") 
  Set l_Menu             = Server.CreateObject("ADODB.Parameter") 
  Set l_Endereco         = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_Perfil               = .CreateParameter("l_Perfil",      adInteger, adParamInput,    , p_Perfil)
     set l_Menu                 = .CreateParameter("l_Menu",        adInteger, adParamInput,    , p_Menu)
     set l_Endereco             = .CreateParameter("l_Endereco",    adInteger, adParamInput,    , p_Endereco)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Perfil
     .parameters.Append         l_Menu
     .parameters.Append         l_Endereco
     .CommandText               = Session("schema") & "SP_PutSgPerMen"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Perfil"
     .parameters.Delete         "l_Menu"
     .parameters.Delete         "l_Endereco"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de SG_PESSOA_MENU
REM -------------------------------------------------------------------------
Sub DML_SgPesMen(Operacao, p_Pessoa, p_Menu, p_Endereco)
  Dim l_Operacao, l_Chave, l_cliente, l_Pessoa, l_Menu, l_Endereco
  
  Set l_Operacao         = Server.CreateObject("ADODB.Parameter")
  Set l_Pessoa           = Server.CreateObject("ADODB.Parameter") 
  Set l_Menu             = Server.CreateObject("ADODB.Parameter") 
  Set l_Endereco         = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_Pessoa               = .CreateParameter("l_Pessoa",      adInteger, adParamInput,    , p_Pessoa)
     set l_Menu                 = .CreateParameter("l_Menu",        adInteger, adParamInput,    , p_Menu)
     set l_Endereco             = .CreateParameter("l_Endereco",    adInteger, adParamInput,    , Tvl(p_Endereco))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Pessoa
     .parameters.Append         l_Menu
     .parameters.Append         l_Endereco
     .CommandText               = Session("schema") & "SP_PutSgPesMen"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Pessoa"
     .parameters.Delete         "l_Menu"
     .parameters.Delete         "l_Endereco"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de SIW_MENU
REM -------------------------------------------------------------------------
Sub DML_SgpesMod(Operacao, Chave, cliente, sq_modulo, sq_endereco)
  Dim l_Operacao, l_Chave, l_cliente, l_sq_modulo, l_sq_endereco
  
  Set l_Operacao         = Server.CreateObject("ADODB.Parameter")
  Set l_Chave            = Server.CreateObject("ADODB.Parameter")
  Set l_cliente          = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_modulo        = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_endereco      = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(chave))
     set l_cliente              = .CreateParameter("l_cliente",         adInteger, adParamInput,    , Tvl(cliente))
     set l_sq_modulo            = .CreateParameter("l_sq_modulo",       adInteger, adParamInput,    , sq_modulo)
     set l_sq_endereco          = .CreateParameter("l_sq_endereco",     adInteger, adParamInput,    , sq_endereco)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_modulo
     .parameters.Append         l_sq_endereco
     .CommandText               = Session("schema") & "SP_PutSgPesMod"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_sq_modulo"
     .parameters.Delete         "l_sq_endereco"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de SIW_PESSOA_CC
REM -------------------------------------------------------------------------
Sub DML_PutSiwPesCC(Operacao, Chave, sq_menu, sq_cc)
  Dim l_Operacao, l_Chave, l_sq_menu, l_sq_cc
  
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_sq_menu         = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_cc           = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , chave)
     set l_sq_menu              = .CreateParameter("l_sq_menu",         adInteger, adParamInput,    , sq_menu)
     set l_sq_cc                = .CreateParameter("l_sq_cc",           adInteger, adParamInput,    , tvl(sq_cc))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_menu
     .parameters.Append         l_sq_cc
     .CommandText               = Session("schema") & "SP_PutSiwPesCC"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_menu"
     .parameters.Delete         "l_sq_cc"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de SIW_MENU
REM -------------------------------------------------------------------------
Sub DML_SiwMenu(Operacao, Chave, sq_menu_pai, link, p1, p2, p3, p4, sigla, imagem, target, _
         emite_os, consulta_opiniao, envia_email, exibe_relatorio, como_funciona, vinculacao, _
         data_hora, envia_dia_util, descricao, justificativa, finalidade, _
         cliente, nome, acesso_geral, sq_modulo, sq_unidade_exec, _
         tramite, ultimo_nivel, descentralizado, externo, ativo, ordem, envio, controla_ano, _
         libera_edicao)
  Dim l_Operacao, l_Chave, l_sq_menu_pai, l_link
  Dim l_p1, l_p2, l_p3, l_p4, l_sigla, l_imagem, l_target 
  Dim l_emite_os, l_consulta_opiniao, l_envia_email, l_exibe_relatorio, l_como_funciona, l_vinculacao
  Dim l_data_hora, l_envia_dia_util, l_descricao, l_justificativa, l_finalidade
  Dim l_cliente, l_nome, l_acesso_geral, l_sq_modulo, l_sq_unidade_exec
  Dim l_tramite, l_ultimo_nivel, l_descentralizado, l_externo, l_ativo, l_ordem, l_envio, l_controla_ano
  Dim l_libera_edicao
  
  Set l_Operacao         = Server.CreateObject("ADODB.Parameter")
  Set l_Chave            = Server.CreateObject("ADODB.Parameter")
  Set l_sq_menu_pai      = Server.CreateObject("ADODB.Parameter") 
  Set l_link             = Server.CreateObject("ADODB.Parameter")
  Set l_p1               = Server.CreateObject("ADODB.Parameter") 
  Set l_p2               = Server.CreateObject("ADODB.Parameter") 
  Set l_p3               = Server.CreateObject("ADODB.Parameter") 
  Set l_p4               = Server.CreateObject("ADODB.Parameter") 
  Set l_sigla            = Server.CreateObject("ADODB.Parameter") 
  Set l_imagem           = Server.CreateObject("ADODB.Parameter") 
  Set l_target           = Server.CreateObject("ADODB.Parameter") 
  Set l_emite_os         = Server.CreateObject("ADODB.Parameter") 
  Set l_consulta_opiniao = Server.CreateObject("ADODB.Parameter") 
  Set l_envia_email      = Server.CreateObject("ADODB.Parameter") 
  Set l_exibe_relatorio  = Server.CreateObject("ADODB.Parameter") 
  Set l_como_funciona    = Server.CreateObject("ADODB.Parameter") 
  Set l_vinculacao       = Server.CreateObject("ADODB.Parameter") 
  Set l_data_hora        = Server.CreateObject("ADODB.Parameter") 
  Set l_envia_dia_util   = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao        = Server.CreateObject("ADODB.Parameter") 
  Set l_justificativa    = Server.CreateObject("ADODB.Parameter") 
  Set l_finalidade       = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente          = Server.CreateObject("ADODB.Parameter") 
  Set l_nome             = Server.CreateObject("ADODB.Parameter") 
  Set l_acesso_geral     = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_modulo        = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_unidade_exec  = Server.CreateObject("ADODB.Parameter") 
  Set l_tramite          = Server.CreateObject("ADODB.Parameter") 
  Set l_ultimo_nivel     = Server.CreateObject("ADODB.Parameter") 
  Set l_descentralizado  = Server.CreateObject("ADODB.Parameter") 
  Set l_externo          = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo            = Server.CreateObject("ADODB.Parameter") 
  Set l_ordem            = Server.CreateObject("ADODB.Parameter")
  Set l_envio            = Server.CreateObject("ADODB.Parameter")
  Set l_controla_ano     = Server.CreateObject("ADODB.Parameter") 
  Set l_libera_edicao    = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(chave))
     set l_cliente              = .CreateParameter("l_cliente",         adInteger, adParamInput,    , cliente)
     set l_nome                 = .CreateParameter("l_nome",            adVarChar, adParamInput,  40, nome)
     set l_sq_menu_pai          = .CreateParameter("l_sq_menu_pai",     adInteger, adParamInput,    , Tvl(sq_menu_pai))
     set l_link                 = .CreateParameter("l_link",            adVarchar, adParamInput,  60, Tvl(link))
     set l_p1                   = .CreateParameter("l_p1",              adInteger, adParamInput,    , Tvl(p1))
     set l_p2                   = .CreateParameter("l_p2",              adInteger, adParamInput,    , Tvl(p2))
     set l_p3                   = .CreateParameter("l_p3",              adInteger, adParamInput,    , Tvl(p3))
     set l_p4                   = .CreateParameter("l_p4",              adInteger, adParamInput,    , Tvl(p4))
     set l_sigla                = .CreateParameter("l_sigla",           adVarchar, adParamInput,  10, Tvl(sigla))
     set l_imagem               = .CreateParameter("l_imagem",          adVarchar, adParamInput,  60, Tvl(imagem))
     set l_target               = .CreateParameter("l_target",          adVarchar, adParamInput,  15, Tvl(target))
     set l_emite_os             = .CreateParameter("l_emite_os",        adVarchar, adParamInput,   1, Tvl(emite_os))
     set l_consulta_opiniao     = .CreateParameter("l_consulta_opiniao",adVarchar, adParamInput,   1, Tvl(consulta_opiniao))
     set l_envia_email          = .CreateParameter("l_envia_email",     adVarchar, adParamInput,   1, Tvl(envia_email))
     set l_exibe_relatorio      = .CreateParameter("l_exibe_relatorio", adVarchar, adParamInput,   1, Tvl(exibe_relatorio))
     set l_como_funciona        = .CreateParameter("l_como_funciona",   adVarchar, adParamInput,1000, Tvl(como_funciona))
     set l_vinculacao           = .CreateParameter("l_vinculacao",      adVarchar, adParamInput,   1, Tvl(vinculacao))
     set l_data_hora            = .CreateParameter("l_data_hora",       adVarchar, adParamInput,   1, Tvl(data_hora))
     set l_envia_dia_util       = .CreateParameter("l_envia_dia_util",  adVarchar, adParamInput,   1, Tvl(envia_dia_util))
     set l_descricao            = .CreateParameter("l_descricao",       adVarchar, adParamInput,   1, Tvl(descricao))
     set l_justificativa        = .CreateParameter("l_justificativa",   adVarchar, adParamInput,   1, Tvl(justificativa))
     set l_finalidade           = .CreateParameter("l_finalidade",      adVarchar, adParamInput, 200, Tvl(finalidade))
     set l_acesso_geral         = .CreateParameter("l_acesso_geral",    adVarchar, adParamInput,   1, acesso_geral)
     set l_sq_modulo            = .CreateParameter("l_sq_modulo",       adInteger, adParamInput,    , sq_modulo)
     set l_sq_unidade_exec      = .CreateParameter("l_sq_unidade_exec", adInteger, adParamInput,    , Tvl(sq_unidade_exec))
     set l_tramite              = .CreateParameter("l_tramite",         adVarchar, adParamInput,   1, tramite)
     set l_ultimo_nivel         = .CreateParameter("l_ultimo_nivel",    adVarchar, adParamInput,   1, ultimo_nivel)
     set l_descentralizado      = .CreateParameter("l_descentralizado", adVarchar, adParamInput,   1, descentralizado)
     set l_externo              = .CreateParameter("l_externo",         adVarchar, adParamInput,   1, externo)
     set l_ativo                = .CreateParameter("l_ativo",           adVarchar, adParamInput,   1, ativo)
     set l_ordem                = .CreateParameter("l_ordem",           adInteger, adParamInput,    , ordem)
     set l_envio                = .CreateParameter("l_envio",           adVarchar, adParamInput,   1, envio)
     set l_controla_ano         = .CreateParameter("l_controla_ano",    adVarchar, adParamInput,   1, Tvl(controla_ano))
     set l_libera_edicao         = .CreateParameter("l_libera_edicao",    adVarchar, adParamInput,   1, Tvl(libera_edicao))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_acesso_geral
     .parameters.Append         l_sq_modulo
     .parameters.Append         l_tramite
     .parameters.Append         l_ultimo_nivel
     .parameters.Append         l_descentralizado
     .parameters.Append         l_externo
     .parameters.Append         l_ativo
     .parameters.Append         l_ordem
     .parameters.Append         l_sq_menu_pai
     .parameters.Append         l_link
     .parameters.Append         l_p1
     .parameters.Append         l_p2
     .parameters.Append         l_p3
     .parameters.Append         l_p4
     .parameters.Append         l_sigla
     .parameters.Append         l_imagem
     .parameters.Append         l_target
     .parameters.Append         l_sq_unidade_exec
     .parameters.Append         l_emite_os
     .parameters.Append         l_consulta_opiniao
     .parameters.Append         l_envia_email
     .parameters.Append         l_exibe_relatorio
     .parameters.Append         l_como_funciona
     .parameters.Append         l_vinculacao
     .parameters.Append         l_data_hora
     .parameters.Append         l_envia_dia_util
     .parameters.Append         l_descricao
     .parameters.Append         l_justificativa
     .parameters.Append         l_finalidade
     .parameters.Append         l_envio
     .parameters.Append         l_controla_ano
     .parameters.Append         l_libera_edicao

     .CommandText               = Session("schema") & "SP_PutSiwMenu"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_menu_pai"
     .parameters.Delete         "l_link"
     .parameters.Delete         "l_p1"
     .parameters.Delete         "l_p2"
     .parameters.Delete         "l_p3"
     .parameters.Delete         "l_p4"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_imagem"
     .parameters.Delete         "l_target"
     .parameters.Delete         "l_emite_os"
     .parameters.Delete         "l_consulta_opiniao"
     .parameters.Delete         "l_envia_email"
     .parameters.Delete         "l_exibe_relatorio"
     .parameters.Delete         "l_como_funciona"
     .parameters.Delete         "l_vinculacao"
     .parameters.Delete         "l_data_hora"
     .parameters.Delete         "l_envia_dia_util"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_justificativa"
     .parameters.Delete         "l_finalidade"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_acesso_geral"
     .parameters.Delete         "l_sq_modulo"
     .parameters.Delete         "l_sq_unidade_exec"
     .parameters.Delete         "l_tramite"
     .parameters.Delete         "l_ultimo_nivel"
     .parameters.Delete         "l_descentralizado"
     .parameters.Delete         "l_externo"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_ordem"
     .parameters.Delete         "l_envio"
     .parameters.Delete         "l_controla_ano"
     .parameters.Delete         "l_libera_edicao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de SIW_TRAMITE
REM -------------------------------------------------------------------------
Sub DML_SiwTramite(Operacao, Chave, p_sq_menu, p_nome, p_ordem, p_sigla, p_descricao, _
                   p_chefia_imediata, p_ativo, p_solicita_cc, p_envia_mail)
  Dim l_Operacao, l_Chave, l_sq_menu, l_nome, l_ordem, l_sigla, l_descricao
  Dim l_chefia_imediata, l_ativo, l_solicita_cc, l_envia_mail
  
  Set l_Operacao         = Server.CreateObject("ADODB.Parameter")
  Set l_Chave            = Server.CreateObject("ADODB.Parameter")
  Set l_sq_menu          = Server.CreateObject("ADODB.Parameter") 
  Set l_nome             = Server.CreateObject("ADODB.Parameter") 
  Set l_ordem            = Server.CreateObject("ADODB.Parameter")
  Set l_sigla            = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao        = Server.CreateObject("ADODB.Parameter") 
  Set l_chefia_imediata  = Server.CreateObject("ADODB.Parameter") 
  Set l_ativo            = Server.CreateObject("ADODB.Parameter") 
  Set l_solicita_cc      = Server.CreateObject("ADODB.Parameter") 
  Set l_envia_mail       = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(chave))
     set l_sq_menu              = .CreateParameter("l_sq_menu",         adInteger, adParamInput,    , p_sq_menu)
     set l_nome                 = .CreateParameter("l_nome",            adVarChar, adParamInput,  50, Tvl(p_nome))
     set l_ordem                = .CreateParameter("l_ordem",           adInteger, adParamInput,    , Tvl(p_ordem))
     set l_sigla                = .CreateParameter("l_sigla",           adVarchar, adParamInput,  2, Tvl(p_sigla))
     set l_descricao            = .CreateParameter("l_descricao",       adVarchar, adParamInput, 500, Tvl(p_descricao))
     set l_chefia_imediata      = .CreateParameter("l_chefia_imediata", adVarchar, adParamInput,   1, Tvl(p_chefia_imediata))
     set l_ativo                = .CreateParameter("l_ativo",           adVarchar, adParamInput,   1, Tvl(p_ativo))
     set l_solicita_cc          = .CreateParameter("l_solicita_cc",     adVarchar, adParamInput,   1, Tvl(p_solicita_cc))
     set l_envia_mail           = .CreateParameter("l_envia_mail",      adVarchar, adParamInput,   1, Tvl(p_envia_mail))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_menu
     .parameters.Append         l_nome
     .parameters.Append         l_ordem
     .parameters.Append         l_sigla
     .parameters.Append         l_descricao
     .parameters.Append         l_chefia_imediata
     .parameters.Append         l_ativo
     .parameters.Append         l_solicita_cc
     .parameters.Append         l_envia_mail

     .CommandText               = Session("schema") & "SP_PutSiwTramite"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_menu"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ordem"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_chefia_imediata"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_solicita_cc"
     .parameters.Delete         "l_envia_mail"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de SG_TRAMITE_PESSOA
REM -------------------------------------------------------------------------
Sub DML_PutSgTraPes(Operacao, p_Pessoa, p_Tramite, p_Endereco)
  Dim l_Operacao, l_Chave, l_cliente, l_Pessoa, l_tramite, l_Endereco
  
  Set l_Operacao         = Server.CreateObject("ADODB.Parameter")
  Set l_Pessoa           = Server.CreateObject("ADODB.Parameter") 
  Set l_Tramite          = Server.CreateObject("ADODB.Parameter") 
  Set l_Endereco         = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_Pessoa               = .CreateParameter("l_Pessoa",      adInteger, adParamInput,    , p_Pessoa)
     set l_Tramite              = .CreateParameter("l_Tramite",     adInteger, adParamInput,    , p_tramite)
     set l_Endereco             = .CreateParameter("l_Endereco",    adInteger, adParamInput,    , Tvl(p_Endereco))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Pessoa
     .parameters.Append         l_Tramite
     .parameters.Append         l_Endereco
     .CommandText               = Session("schema") & "SP_PutSgTraPes"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Pessoa"
     .parameters.Delete         "l_Tramite"
     .parameters.Delete         "l_Endereco"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>