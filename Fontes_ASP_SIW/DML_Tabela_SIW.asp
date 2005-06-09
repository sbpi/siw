<%
REM =========================================================================
REM Manipula registros de DM_Segmento_Vinculo
REM -------------------------------------------------------------------------
Sub DML_DMSegVinc(Operacao, Chave, sq_segmento, sq_tipo_pessoa, nome, padrao, ativo, interno, contratado, ordem)
  Dim l_Operacao, l_Chave, l_sq_segmento,  l_sq_tipo_pessoa, l_nome, l_padrao, l_ativo
  Dim l_interno, l_contratado, l_ordem
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_sq_segmento     = Server.CreateObject("ADODB.Parameter")
  Set l_sq_tipo_pessoa  = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  Set l_padrao          = Server.CreateObject("ADODB.Parameter")
  Set l_ativo           = Server.CreateObject("ADODB.Parameter")
  Set l_interno         = Server.CreateObject("ADODB.Parameter")
  Set l_contratado      = Server.CreateObject("ADODB.Parameter")
  Set l_ordem           = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",      adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,    , Tvl(chave))
     set l_sq_segmento          = .CreateParameter("l_sq_segmento",   adInteger, adParamInput,    , Tvl(sq_segmento))
     set l_sq_tipo_pessoa       = .CreateParameter("l_sq_tipo_pessoa",adInteger, adParamInput,    , Tvl(sq_tipo_pessoa))
     set l_nome                 = .CreateParameter("l_nome",          adVarChar, adParamInput,  20, nome)
     set l_padrao               = .CreateParameter("l_padrao",        adVarChar, adParamInput,   1, padrao)
     set l_ativo                = .CreateParameter("l_ativo",         adVarchar, adParamInput,   1, ativo)
     set l_interno              = .CreateParameter("l_interno",       adVarchar, adParamInput,   1, interno)
     set l_contratado           = .CreateParameter("l_contratado",    adVarchar, adParamInput,   1, contratado)
     set l_ordem                = .CreateParameter("l_ordem",         adInteger, adParamInput,    , ordem)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_segmento
     .parameters.Append         l_sq_tipo_pessoa
     .parameters.Append         l_nome
     .parameters.Append         l_padrao
     .parameters.Append         l_ativo
     .parameters.Append         l_interno
     .parameters.Append         l_contratado
     .parameters.Append         l_ordem
     .CommandText               = Session("schema") & "SP_PutDMSegVinc"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_segmento"
     .parameters.Delete         "l_sq_tipo_pessoa"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_interno"
     .parameters.Delete         "l_contratado"
     .parameters.Delete         "l_ordem"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de DM_Segmento_Vinculo
REM -------------------------------------------------------------------------
Sub DML_SIWModSeg(Operacao, objetivo_especifico, sq_modulo, sq_segmento, comercializar, ativo)
  Dim l_Operacao, l_objetivo_especifico, l_sq_modulo, l_sq_segmento,  l_comercializar, l_ativo
  Set l_Operacao              = Server.CreateObject("ADODB.Parameter")
  Set l_objetivo_especifico   = Server.CreateObject("ADODB.Parameter")
  Set l_sq_modulo             = Server.CreateObject("ADODB.Parameter")
  Set l_sq_segmento           = Server.CreateObject("ADODB.Parameter")
  Set l_comercializar         = Server.CreateObject("ADODB.Parameter")
  Set l_ativo                 = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao               = .CreateParameter("l_operacao",                adVarchar, adParamInput,   1, Operacao)
     set l_objetivo_especifico    = .CreateParameter("l_objetivo_especifico",     adVarchar, adParamInput,4000, objetivo_especifico)
     set l_sq_modulo              = .CreateParameter("l_sq_modulo",               adInteger, adParamInput,    , Tvl(sq_modulo))
     set l_sq_segmento            = .CreateParameter("l_sq_segmento",             adInteger, adParamInput,    , Tvl(sq_segmento))
     set l_comercializar          = .CreateParameter("l_comercializar",           adVarChar, adParamInput,   1, comercializar)
     set l_ativo                  = .CreateParameter("l_ativo",                   adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_objetivo_especifico
     .parameters.Append         l_sq_modulo
     .parameters.Append         l_sq_segmento
     .parameters.Append         l_comercializar
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutSIWModSeg"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_objetivo_especifico"
     .parameters.Delete         "l_sq_modulo"
     .parameters.Delete         "l_sq_segmento"
     .parameters.Delete         "l_comercializar"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de SIW_Modulo
REM -------------------------------------------------------------------------
Sub DML_SIWModulo(Operacao, Chave, nome, sigla, objetivo_geral)
  Dim l_Operacao, l_Chave, l_nome, l_sigla,  l_objetivo_geral
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  Set l_sigla           = Server.CreateObject("ADODB.Parameter")
  Set l_objetivo_geral  = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao          = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_Chave             = .CreateParameter("l_Chave",           adInteger, adParamInput,    , Tvl(Chave))
     set l_nome              = .CreateParameter("l_nome",            adVarChar, adParamInput,  60, nome)
     set l_sigla             = .CreateParameter("l_sigla",           adVarchar, adParamInput,   3, sigla)
     set l_objetivo_geral    = .CreateParameter("l_objetivo_geral",  adVarChar, adParamInput,4000, objetivo_geral)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_sigla
     .parameters.Append         l_objetivo_geral
     .CommandText               = Session("schema") & "SP_PutSIWModulo"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_objetivo_geral"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de CO_Segmento
REM -------------------------------------------------------------------------
Sub DML_COSegmento(Operacao, Chave, nome, padrao, ativo)
  Dim l_Operacao, l_Chave, l_nome, l_padrao,  l_ativo
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  Set l_padrao          = Server.CreateObject("ADODB.Parameter")
  Set l_ativo           = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao          = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_Chave             = .CreateParameter("l_Chave",           adInteger, adParamInput,    , Tvl(Chave))
     set l_nome              = .CreateParameter("l_nome",            adVarChar, adParamInput,  40, nome)
     set l_padrao            = .CreateParameter("l_padrao",          adVarchar, adParamInput,   1, padrao)
     set l_ativo             = .CreateParameter("l_ativo",           adVarChar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_padrao
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutCOSegmento"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>