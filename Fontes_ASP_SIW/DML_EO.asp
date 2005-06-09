<%
REM =========================================================================
REM Manipula registros de EO_Unidade
REM -------------------------------------------------------------------------
Sub DML_EOUnidade(Operacao, Chave, sq_tipo_unidade, sq_area_atuacao, sq_unidade_gestora, _
                  sq_unidade_pai, sq_unidade_pagadora, sq_pessoa_endereco, ordem, email, _
                  codigo, cliente, nome, sigla, _
                  informal, vinculada, adm_central, unidade_gestora, unidade_pagadora, ativo)
  Dim l_Operacao, l_Chave, l_sq_tipo_unidade,  l_sq_area_atuacao, l_sq_unidade_gestora
  Dim l_sq_unidade_pai, l_sq_unidade_pagadora, l_sq_pessoa_endereco, l_ordem, l_email, l_codigo, l_cliente, l_nome, l_sigla
  Dim l_informal, l_vinculada, l_adm_central, l_unidade_gestora, l_unidade_pagadora, l_ativo
  Set l_Operacao              = Server.CreateObject("ADODB.Parameter")
  Set l_Chave                 = Server.CreateObject("ADODB.Parameter")
  Set l_sq_tipo_unidade       = Server.CreateObject("ADODB.Parameter")
  Set l_sq_area_atuacao       = Server.CreateObject("ADODB.Parameter")
  Set l_sq_unidade_gestora    = Server.CreateObject("ADODB.Parameter")
  Set l_sq_unidade_pai        = Server.CreateObject("ADODB.Parameter")
  Set l_sq_unidade_pagadora   = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pessoa_endereco    = Server.CreateObject("ADODB.Parameter")
  Set l_ordem                 = Server.CreateObject("ADODB.Parameter")
  Set l_email                 = Server.CreateObject("ADODB.Parameter")
  Set l_codigo                = Server.CreateObject("ADODB.Parameter")
  Set l_cliente               = Server.CreateObject("ADODB.Parameter")
  Set l_nome                  = Server.CreateObject("ADODB.Parameter")
  Set l_sigla                 = Server.CreateObject("ADODB.Parameter")
  Set l_informal              = Server.CreateObject("ADODB.Parameter")
  Set l_vinculada             = Server.CreateObject("ADODB.Parameter")
  Set l_adm_central           = Server.CreateObject("ADODB.Parameter")
  Set l_unidade_gestora       = Server.CreateObject("ADODB.Parameter")
  Set l_unidade_pagadora      = Server.CreateObject("ADODB.Parameter")
  Set l_ativo                 = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",           adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",              adInteger, adParamInput,    , Tvl(chave))
     set l_sq_tipo_unidade      = .CreateParameter("l_sq_tipo_unidade",    adInteger, adParamInput,    , Tvl(sq_tipo_unidade))
     set l_sq_area_atuacao      = .CreateParameter("l_sq_area_atuacao",    adInteger, adParamInput,    , Tvl(sq_area_atuacao))
     set l_sq_unidade_gestora   = .CreateParameter("l_sq_unidade_gestora", adInteger, adParamInput,    , Tvl(sq_unidade_gestora))
     set l_sq_unidade_pai       = .CreateParameter("l_sq_unidade_pai",     adInteger, adParamInput,    , Tvl(sq_unidade_pai))
     set l_sq_unidade_pagadora  = .CreateParameter("l_sq_unidade_pagadora",adInteger, adParamInput,    , Tvl(sq_unidade_pagadora))
     set l_sq_pessoa_endereco   = .CreateParameter("l_sq_pessoa_endereco", adInteger, adParamInput,    , Tvl(sq_pessoa_endereco))
     set l_ordem                = .CreateParameter("l_ordem",              adInteger, adParamInput,    , Tvl(ordem))
     set l_email                = .CreateParameter("l_email",              adVarChar, adParamInput,  60, email)
     set l_codigo               = .CreateParameter("l_codigo",             adVarChar, adParamInput,  15, codigo)
     set l_cliente              = .CreateParameter("l_cliente",            adInteger, adParamInput,    , cliente)
     set l_nome                 = .CreateParameter("l_nome",               adVarChar, adParamInput,  50, nome)
     set l_sigla                = .CreateParameter("l_sigla",              adVarChar, adParamInput,  20, sigla)
     set l_informal             = .CreateParameter("l_informal",           adVarChar, adParamInput,   1, informal)
     set l_vinculada            = .CreateParameter("l_vinculada",          adVarChar, adParamInput,   1, vinculada)
     set l_adm_central          = .CreateParameter("l_adm_central",        adVarChar, adParamInput,   1, adm_central)
     set l_unidade_gestora      = .CreateParameter("l_unidade_gestora",    adVarChar, adParamInput,   1, unidade_gestora)
     set l_unidade_pagadora     = .CreateParameter("l_unidade_pagadora",   adVarChar, adParamInput,   1, unidade_pagadora)
     set l_ativo                = .CreateParameter("l_ativo",              adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_tipo_unidade
     .parameters.Append         l_sq_area_atuacao
     .parameters.Append         l_sq_unidade_gestora
     .parameters.Append         l_sq_unidade_pai
     .parameters.Append         l_sq_unidade_pagadora
     .parameters.Append         l_sq_pessoa_endereco
     .parameters.Append         l_ordem
     .parameters.Append         l_email
     .parameters.Append         l_codigo
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_sigla
     .parameters.Append         l_informal
     .parameters.Append         l_vinculada
     .parameters.Append         l_adm_central
     .parameters.Append         l_unidade_gestora
     .parameters.Append         l_unidade_pagadora
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutEOUnidade"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_tipo_unidade"
     .parameters.Delete         "l_sq_unidade_gestora"
     .parameters.Delete         "l_sq_unidade_pai"
     .parameters.Delete         "l_sq_unidade_pagadora"
     .parameters.Delete         "l_sq_pessoa_endereco"
     .parameters.Delete         "l_ordem"
     .parameters.Delete         "l_email"
     .parameters.Delete         "l_codigo"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_informal"
     .parameters.Delete         "l_vinculada"
     .parameters.Delete         "l_adm_central"
     .parameters.Delete         "l_unidade_gestora"
     .parameters.Delete         "l_unidade_pagadora"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de EO_Localizacao
REM -------------------------------------------------------------------------
Sub DML_EOLocal(Operacao, Chave, sq_pessoa_endereco, sq_unidade, nome, fax, telefone, ramal, telefone2, ativo)
  Dim l_Operacao, l_Chave, l_sq_pessoa_endereco,  l_sq_unidade, l_nome, l_fax, l_telefone
  Dim l_ramal, l_telefone2, l_ativo
  Set l_Operacao           = Server.CreateObject("ADODB.Parameter")
  Set l_Chave              = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pessoa_endereco = Server.CreateObject("ADODB.Parameter")
  Set l_sq_unidade         = Server.CreateObject("ADODB.Parameter")
  Set l_nome               = Server.CreateObject("ADODB.Parameter")
  Set l_fax                = Server.CreateObject("ADODB.Parameter")
  Set l_telefone           = Server.CreateObject("ADODB.Parameter")
  Set l_ramal              = Server.CreateObject("ADODB.Parameter")
  Set l_telefone2          = Server.CreateObject("ADODB.Parameter")
  Set l_ativo              = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",           adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",              adInteger, adParamInput,    , Tvl(chave))
     set l_sq_pessoa_endereco   = .CreateParameter("l_sq_pessoa_endereco", adInteger, adParamInput,    , Tvl(sq_pessoa_endereco))
     set l_sq_unidade           = .CreateParameter("l_sq_unidade",         adInteger, adParamInput,    , sq_unidade)
     set l_nome                 = .CreateParameter("l_nome",               adVarChar, adParamInput,  30, nome)
     set l_fax                  = .CreateParameter("l_fax",                adVarChar, adParamInput,  12, fax)
     set l_telefone             = .CreateParameter("l_telefone",           adVarChar, adParamInput,  12, telefone)
     set l_ramal                = .CreateParameter("l_ramal",              adVarChar, adParamInput,   6, ramal)
     set l_telefone2            = .CreateParameter("l_telefone2",          adVarChar, adParamInput,  12, telefone2)
     set l_ativo                = .CreateParameter("l_ativo",              adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_pessoa_endereco
     .parameters.Append         l_sq_unidade
     .parameters.Append         l_nome
     .parameters.Append         l_fax
     .parameters.Append         l_telefone
     .parameters.Append         l_ramal
     .parameters.Append         l_telefone2
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutEOLocal"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_pessoa_endereco"
     .parameters.Delete         "l_sq_unidade"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_fax"
     .parameters.Delete         "l_telefone"
     .parameters.Delete         "l_ramal"
     .parameters.Delete         "l_telefone2"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de EO_Responsavel
REM -------------------------------------------------------------------------
Sub DML_EOResp(Operacao, Chave, fim_substituto, sq_pessoa_substituto, inicio_substituto, fim_titular, sq_pessoa, inicio_titular)
  Dim l_Operacao, l_Chave, l_fim_substituto,  l_sq_pessoa_substituto, l_inicio_substituto, l_fim_titular, l_sq_pessoa, l_inicio_titular
  'Response.Write "["&sq_pessoa_substituto&"]"
  'Response.Write "["&sq_pessoa&"]"
  'Response.end()
  Set l_Operacao              = Server.CreateObject("ADODB.Parameter")
  Set l_Chave                 = Server.CreateObject("ADODB.Parameter")
  Set l_fim_substituto        = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pessoa_substituto  = Server.CreateObject("ADODB.Parameter")
  Set l_inicio_substituto     = Server.CreateObject("ADODB.Parameter")
  Set l_fim_titular           = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pessoa             = Server.CreateObject("ADODB.Parameter")
  Set l_inicio_titular        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",              adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",                 adInteger, adParamInput,    , Tvl(Chave))
     set l_fim_substituto       =.CreateParameter("l_fim_substituto",         adDate,    adParamInput,  15, Tvl(fim_substituto))
     set l_sq_pessoa_substituto = .CreateParameter("l_sq_pessoa_substituto",  adInteger, adParamInput,    , Tvl(sq_pessoa_substituto))
     set l_inicio_substituto    = .CreateParameter("l_inicio_substituto",     adDate,    adParamInput,  15, Tvl(inicio_substituto))
     set l_fim_titular          = .CreateParameter("l_fim_titular",           adDate,    adParamInput,  15, Tvl(fim_titular))
     set l_sq_pessoa            = .CreateParameter("l_sq_pessoa",             adInteger, adParamInput,    , sq_pessoa)
     set l_inicio_titular       = .CreateParameter("l_inicio_titular",        adDate,    adParamInput,  15, Tvl(inicio_titular))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_fim_substituto
     .parameters.Append         l_sq_pessoa_substituto
     .parameters.Append         l_inicio_substituto
     .parameters.Append         l_fim_titular
     .parameters.Append         l_sq_pessoa
     .parameters.Append         l_inicio_titular
     .CommandText               = Session("schema") & "SP_PutEOResp"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_fim_substituto"
     .parameters.Delete         "l_sq_pessoa_substituto"
     .parameters.Delete         "l_inicio_substituto"
     .parameters.Delete         "l_fim_titular"
     .parameters.Delete         "l_sq_pessoa"
     .parameters.Delete         "l_inicio_titular"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>