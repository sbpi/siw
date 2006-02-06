<%
REM =========================================================================
REM Mantém a tabela de recursos de uma etapa
REM -------------------------------------------------------------------------
Sub DML_PutTrigEvento(Operacao, p_chave, p_chave_aux)
  Dim l_Operacao, l_chave, l_chave_aux
  Set l_Operacao  = Server.CreateObject("ADODB.Parameter")
  Set l_chave     = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter") 
  with sp
    set l_Operacao  = .CreateParameter("l_operacao",  adVarchar, adParamInput,   1, Operacao)
    set l_chave     = .CreateParameter("l_chave",     adInteger, adParamInput,    , p_chave)
    set l_chave_aux = .CreateParameter("l_chave_aux", adInteger, adParamInput,    , Tvl(p_chave_aux))
    .parameters.Append  l_Operacao
    .parameters.Append  l_chave
    .parameters.Append  l_chave_aux
    .CommandText        = Session("schema") & "SP_PutTrigEvento"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
      .parameters.Delete  "l_Operacao"
      .parameters.Delete  "l_chave"
      .parameters.Delete  "l_chave_aux"
  end with
End Sub

REM =========================================================================
REM Mantém a tabela de recursos de uma etapa
REM -------------------------------------------------------------------------
Sub DML_PutSPTabs(Operacao, p_chave, p_chave_aux)
  Dim l_Operacao, l_chave, l_chave_aux
  Set l_Operacao   = Server.CreateObject("ADODB.Parameter")
  Set l_chave      = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux  = Server.CreateObject("ADODB.Parameter") 
  with sp
    set l_Operacao   = .CreateParameter("l_operacao",  adVarchar, adParamInput,   1, Operacao)
    set l_chave      = .CreateParameter("l_chave",     adInteger, adParamInput,    , p_chave)
    set l_chave_aux  = .CreateParameter("l_chave_aux", adInteger, adParamInput,    , Tvl(p_chave_aux))
    .parameters.Append  l_Operacao
    .parameters.Append  l_chave
    .parameters.Append  l_chave_aux
    .CommandText        = Session("schema") & "SP_PutSPTabs"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
      .parameters.Delete  "l_Operacao"
      .parameters.Delete  "l_chave"
      .parameters.Delete  "l_chave_aux"
  end with
End Sub

REM =========================================================================
REM Mantém a tabela de recursos de uma etapa
REM -------------------------------------------------------------------------
Sub DML_PutSPSP(Operacao, p_chave, p_chave_aux)
  Dim l_Operacao, l_chave, l_chave_aux
  Set l_Operacao   = Server.CreateObject("ADODB.Parameter")
  Set l_chave      = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux  = Server.CreateObject("ADODB.Parameter") 
  with sp
    set l_Operacao  = .CreateParameter("l_operacao",  adVarchar, adParamInput,   1, Operacao)
    set l_chave     = .CreateParameter("l_chave",     adInteger, adParamInput,    , p_chave)
    set l_chave_aux = .CreateParameter("l_chave_aux", adInteger, adParamInput,    , p_chave_aux)
    .parameters.Append  l_Operacao
    .parameters.Append  l_chave
    .parameters.Append  l_chave_aux
    .CommandText        = Session("schema") & "SP_PutSPSP"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
    .parameters.Delete  "l_Operacao"
    .parameters.Delete  "l_chave"
    .parameters.Delete  "l_chave_aux"
  end with
End Sub

REM =========================================================================
REM Mantém a tabela de recursos de uma etapa
REM -------------------------------------------------------------------------
Sub DML_PutSPParametro(Operacao, p_chave, p_chave_aux, p_sq_dado_tipo, p_nome, p_descricao, p_tipo, p_ordem)
  Dim l_Operacao, l_chave, l_chave_aux, l_sq_dado_tipo, l_nome, l_descricao, l_tipo, l_ordem
  Set l_Operacao       = Server.CreateObject("ADODB.Parameter")
  Set l_chave          = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux      = Server.CreateObject("ADODB.Parameter")  
  Set l_sq_dado_tipo   = Server.CreateObject("ADODB.Parameter") 
  Set l_nome           = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao      = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo           = Server.CreateObject("ADODB.Parameter")  
  Set l_ordem          = Server.CreateObject("ADODB.Parameter")  
  with sp
    set l_Operacao       = .CreateParameter("l_operacao"     , adVarchar, adParamInput,   1, Operacao)
    set l_chave          = .CreateParameter("l_chave"        , adInteger, adParamInput,  18, p_chave)
    set l_chave_aux      = .CreateParameter("l_chave_aux"    , adInteger, adParamInput,  18, p_chave_aux)
    set l_sq_dado_tipo   = .CreateParameter("l_sq_dado_tipo" , adInteger, adParamInput,  18, p_sq_dado_tipo)
    set l_nome           = .CreateParameter("l_nome"         , adVarchar, adParamInput,  30, p_nome)
    set l_descricao      = .CreateParameter("l_descricao"    , adVarchar, adParamInput,4000, p_descricao)
    set l_tipo           = .CreateParameter("l_tipo"         , adVarchar, adParamInput,   1, p_tipo)
    set l_ordem          = .CreateParameter("l_ordem"        , adInteger, adParamInput,  18, p_ordem)
    .parameters.Append   l_Operacao
    .parameters.Append   l_chave
    .parameters.Append   l_chave_aux
    .parameters.Append   l_sq_dado_tipo
    .parameters.Append   l_nome
    .parameters.Append   l_descricao
    .parameters.Append   l_tipo
    .parameters.Append   l_ordem
    .CommandText         = Session("schema") & "SP_PutSPParametro"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
    .parameters.Delete  "l_Operacao"
    .parameters.Delete  "l_chave"
    .parameters.Delete  "l_chave_aux"
    .parameters.Delete  "l_sq_dado_tipo"
    .parameters.Delete  "l_nome"
    .parameters.Delete  "l_descricao"
    .parameters.Delete  "l_tipo"
    .parameters.Delete  "l_ordem"
  end with
End Sub


REM =========================================================================
REM Mantém a tabela de tipos de dado
REM -------------------------------------------------------------------------
Sub DML_PutSistema(Operacao, p_chave, p_chave_aux, p_nome, p_sigla, p_descricao)
  Dim l_Operacao, l_Chave, l_nome, l_descricao, l_sigla, l_chave_aux
  Set l_Operacao   = Server.CreateObject("ADODB.Parameter")
  Set l_chave      = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux  = Server.CreateObject("ADODB.Parameter") 
  Set l_nome       = Server.CreateObject("ADODB.Parameter") 
  Set l_sigla      = Server.CreateObject("ADODB.Parameter")
  Set l_descricao  = Server.CreateObject("ADODB.Parameter")
  with sp
    set l_Operacao   = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
    set l_chave      = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
    set l_chave_aux  = .CreateParameter("l_chave_aux",           adInteger, adParamInput,    , p_chave_aux)
    set l_nome       = .CreateParameter("l_nome",                adVarchar, adParamInput,  30, Tvl(p_nome))
    set l_sigla      = .CreateParameter("l_sigla",               adVarchar, adParamInput,  10, Tvl(p_sigla))
    set l_descricao	 = .CreateParameter("l_descricao",			adVarchar, adParamInput,4000, Tvl(p_descricao))
    .parameters.Append  l_Operacao
    .parameters.Append  l_Chave
    .parameters.Append  l_chave_aux
    .parameters.Append  l_nome
    .parameters.Append  l_sigla
    .parameters.Append  l_descricao
    .CommandText        = Session("schema") & "SP_PutSistema"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
    .parameters.Delete  "l_Operacao"
    .parameters.Delete  "l_Chave"
    .parameters.Delete  "l_Chave_aux"
    .parameters.Delete  "l_nome"
    .parameters.Delete  "l_sigla"
    .parameters.Delete  "l_descricao"
  end with
End Sub

REM =========================================================================
REM Mantém a tabela de tipos de dado
REM -------------------------------------------------------------------------
Sub DML_PutEventoTrigger(Operacao, p_chave, p_nome, p_descricao)
  Dim l_Operacao, l_Chave, l_nome, l_descricao
  Set l_Operacao   = Server.CreateObject("ADODB.Parameter")
  Set l_chave      = Server.CreateObject("ADODB.Parameter") 
  Set l_nome       = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao  = Server.CreateObject("ADODB.Parameter") 
  with sp
    set l_Operacao   = .CreateParameter("l_operacao",  adVarchar, adParamInput,   1, Operacao)
    set l_chave      = .CreateParameter("l_chave",     adInteger, adParamInput,    , Tvl(p_chave))
    set l_nome       = .CreateParameter("l_nome",      adVarchar, adParamInput,  30, Tvl(p_nome))
    set l_descricao	 = .CreateParameter("l_descricao", adVarchar, adParamInput,4000, Tvl(p_descricao))
    .parameters.Append  l_Operacao
    .parameters.Append  l_Chave
    .parameters.Append  l_nome
    .parameters.Append  l_descricao
    .CommandText        = Session("schema") & "SP_PutEventoTrigger"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
    .parameters.Delete  "l_Operacao"
    .parameters.Delete  "l_Chave"
    .parameters.Delete  "l_nome"
    .parameters.Delete  "l_descricao"
  end with
End Sub

REM =========================================================================
REM Mantém a tabela de Usuarios
REM -------------------------------------------------------------------------
Sub DML_PutUsuario(Operacao, p_chave, p_sq_sistema, p_nome, p_descricao)
  Dim l_Operacao, l_Chave, l_sq_sistema, l_nome, l_descricao
  Set l_Operacao    = Server.CreateObject("ADODB.Parameter")
  Set l_chave       = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_sistema  = Server.CreateObject("ADODB.Parameter") 
  Set l_nome        = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao   = Server.CreateObject("ADODB.Parameter") 
  with sp
    set l_Operacao    = .CreateParameter("l_operacao"  , adVarchar, adParamInput,   1, Operacao)
    set l_chave       = .CreateParameter("l_chave"     , adInteger, adParamInput,    , Tvl(p_chave))
    set l_sq_sistema  = .CreateParameter("l_sq_sistema", adInteger, adParamInput,    , Tvl(p_sq_sistema))
    set l_nome        = .CreateParameter("l_nome"      , adVarchar, adParamInput,  30, Tvl(p_nome))
    set l_descricao	  = .CreateParameter("l_descricao" , adVarchar, adParamInput,4000, Tvl(p_descricao))
    .parameters.Append  l_Operacao
    .parameters.Append  l_Chave
    .parameters.Append  l_sq_sistema
    .parameters.Append  l_nome
    .parameters.Append  l_descricao
    .CommandText        = Session("schema") & "SP_PutUsuario"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
    .parameters.Delete  "l_Operacao"
    .parameters.Delete  "l_Chave"
    .parameters.Delete  "l_sq_sistema"
    .parameters.Delete  "l_nome"
    .parameters.Delete  "l_descricao"
  end with
End Sub

REM =========================================================================
REM Mantém a tabela de Arquivos
REM -------------------------------------------------------------------------
Sub DML_PutArquivo(Operacao, p_chave, p_sq_sistema, p_nome, p_descricao, p_tipo, p_diretorio)
  Dim l_Operacao, l_chave, l_sq_sistema, l_nome, l_descricao, l_tipo, l_diretorio
  Set l_Operacao    = Server.CreateObject("ADODB.Parameter")
  Set l_chave       = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_sistema  = Server.CreateObject("ADODB.Parameter") 
  Set l_nome        = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao   = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo        = Server.CreateObject("ADODB.Parameter")
  Set l_diretorio   = Server.CreateObject("ADODB.Parameter")
  with sp
    set l_Operacao    = .CreateParameter("l_operacao"  , adVarchar, adParamInput,   1, Operacao)
    set l_chave       = .CreateParameter("l_chave"     , adInteger, adParamInput,    , Tvl(p_chave))
    set l_sq_sistema  = .CreateParameter("l_sq_sistema", adInteger, adParamInput,    , Tvl(p_sq_sistema))
    set l_nome        = .CreateParameter("l_nome"      , adVarchar, adParamInput,  30, Tvl(p_nome))
    set l_descricao	  = .CreateParameter("l_descricao" , adVarchar, adParamInput,4000, Tvl(p_descricao))
    set l_tipo		  = .CreateParameter("l_tipo"      , adVarchar, adParamInput,   1, Tvl(p_tipo))
    set l_diretorio	  = .CreateParameter("l_diretorio" , adVarchar, adParamInput, 100, Tvl(p_diretorio))
    .parameters.Append  l_Operacao
    .parameters.Append  l_Chave
    .parameters.Append  l_sq_sistema
    .parameters.Append  l_nome
    .parameters.Append  l_descricao
    .parameters.Append  l_tipo
    .parameters.Append  l_diretorio
    .CommandText        = Session("schema") & "SP_PutArquivo"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
    .parameters.Delete  "l_Operacao"
    .parameters.Delete  "l_Chave"
    .parameters.Delete  "l_sq_sistema"
    .parameters.Delete  "l_nome"
    .parameters.Delete  "l_descricao"
    .parameters.Delete  "l_tipo"
    .parameters.Delete  "l_diretorio"
  end with
End Sub

REM =========================================================================
REM Mantém a tabela de Tabelas
REM -------------------------------------------------------------------------
Sub DML_PutTabela(Operacao, p_chave, p_sq_tabela_tipo, p_sq_usuario,p_sq_sistema, p_nome, p_descricao)
  Dim l_Operacao, l_chave, l_sq_tabela_tipo, l_sq_usuario, l_sq_sistema, l_nome, l_descricao
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_chave           = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_tabela_tipo  = Server.CreateObject("ADODB.Parameter")
  Set l_sq_usuario      = Server.CreateObject("ADODB.Parameter")
  Set l_sq_sistema      = Server.CreateObject("ADODB.Parameter") 
  Set l_nome            = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao       = Server.CreateObject("ADODB.Parameter") 
  with sp
    set l_Operacao        = .CreateParameter("l_operacao"      ,  adVarchar, adParamInput,    1, Operacao)
    set l_chave           = .CreateParameter("l_chave"         ,  adInteger, adParamInput,     , Tvl(p_chave))
    set l_sq_tabela_tipo  = .CreateParameter("l_sq_tabela_tipo",  adInteger, adParamInput,     , Tvl(p_sq_tabela_tipo))
    set l_sq_usuario      = .CreateParameter("l_sq_usuario"    ,  adInteger, adParamInput,     , Tvl(p_sq_usuario))
    set l_sq_sistema      = .CreateParameter("l_sq_sistema"    ,  adInteger, adParamInput,     , Tvl(p_sq_sistema))
    set l_nome            = .CreateParameter("l_nome"          ,  adVarchar, adParamInput,   30, Tvl(p_nome))
    set l_descricao		  = .CreateParameter("l_descricao"     ,  adVarchar, adParamInput, 4000, Tvl(p_descricao))
    .parameters.Append  l_Operacao
    .parameters.Append  l_Chave
    .parameters.Append  l_sq_tabela_tipo
    .parameters.Append  l_sq_usuario
    .parameters.Append  l_sq_sistema
    .parameters.Append  l_nome
    .parameters.Append  l_descricao
    .CommandText        = Session("schema") & "SP_PutTabela"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
    .parameters.Delete  "l_Operacao"
    .parameters.Delete  "l_Chave"
    .parameters.Delete  "l_sq_tabela_tipo"
    .parameters.Delete  "l_sq_usuario"
    .parameters.Delete  "l_sq_sistema"
    .parameters.Delete  "l_nome"
    .parameters.Delete  "l_descricao"
  end with
End Sub

REM =========================================================================
REM Mantém a tabela de Colunas
REM -------------------------------------------------------------------------
Sub DML_PutColuna(Operacao, p_chave, p_sq_tabela, p_sq_dado_tipo,p_nome, p_descricao, p_ordem, p_tamanho, p_precisao, p_escala, p_obrigatorio, p_valor_padrao)
  Dim l_Operacao, l_chave, l_sq_tabela, l_sq_dado_tipo, l_nome, l_descricao, l_ordem, l_tamanho, l_precisao, l_escala, l_obrigatorio, l_valor_padrao
  Set l_Operacao     = Server.CreateObject("ADODB.Parameter")
  Set l_chave        = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_tabela    = Server.CreateObject("ADODB.Parameter")
  Set l_sq_dado_tipo = Server.CreateObject("ADODB.Parameter")
  Set l_nome         = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao    = Server.CreateObject("ADODB.Parameter") 
  Set l_ordem        = Server.CreateObject("ADODB.Parameter")
  Set l_tamanho      = Server.CreateObject("ADODB.Parameter") 
  Set l_precisao     = Server.CreateObject("ADODB.Parameter") 
  Set l_escala       = Server.CreateObject("ADODB.Parameter") 
  Set l_obrigatorio  = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_padrao = Server.CreateObject("ADODB.Parameter")  
  with sp
    set l_Operacao      = .CreateParameter("l_operacao"      ,  adVarchar, adParamInput,    1, Operacao)
    set l_chave         = .CreateParameter("l_chave"         ,  adInteger, adParamInput,     , Tvl(p_chave))
    set l_sq_tabela     = .CreateParameter("l_sq_tabela"     ,  adInteger, adParamInput,     , Tvl(p_sq_tabela))
    set l_sq_dado_tipo  = .CreateParameter("l_sq_dado_tipo"  ,  adInteger, adParamInput,     , Tvl(p_sq_dado_tipo))
    set l_nome          = .CreateParameter("l_nome"          ,  adVarchar, adParamInput,   30, Tvl(p_nome))
    set l_descricao     = .CreateParameter("l_descricao"     ,  adVarchar, adParamInput, 4000, Tvl(p_descricao))
    set l_ordem    		= .CreateParameter("l_ordem"         ,	adInteger, adParamInput,   18, Tvl(p_ordem))
    set l_tamanho  		= .CreateParameter("l_tamanho"       ,	adInteger, adParamInput,   18, Tvl(p_tamanho))
    set l_precisao 		= .CreateParameter("l_precisao"      ,	adInteger, adParamInput,   18, Tvl(p_precisao))
    set l_escala   		= .CreateParameter("l_escala"        ,	adInteger, adParamInput,   18, Tvl(p_escala))
    set l_obrigatorio	= .CreateParameter("l_obrigatorio"   ,	adVarchar, adParamInput,    1, Tvl(p_obrigatorio))
    set l_valor_padrao  = .CreateParameter("l_valor_padrao"  ,	adVarchar, adParamInput,  255, Tvl(p_valor_padrao))     
    .parameters.Append  l_Operacao
    .parameters.Append  l_Chave
    .parameters.Append  l_sq_tabela
    .parameters.Append  l_sq_dado_tipo
    .parameters.Append  l_nome
    .parameters.Append  l_descricao
    .parameters.Append  l_ordem
    .parameters.Append  l_tamanho
    .parameters.Append  l_precisao
    .parameters.Append  l_escala
    .parameters.Append  l_obrigatorio
    .parameters.Append  l_valor_padrao
    .CommandText        = Session("schema") & "SP_PutColuna"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
    .parameters.Delete  "l_Operacao"
    .parameters.Delete  "l_Chave"
    .parameters.Delete  "l_sq_tabela"
    .parameters.Delete  "l_sq_dado_tipo"
    .parameters.Delete  "l_nome"
    .parameters.Delete  "l_descricao"
    .parameters.Delete  "l_ordem"
    .parameters.Delete  "l_tamanho"
    .parameters.Delete  "l_precisao"
    .parameters.Delete  "l_escala"
    .parameters.Delete  "l_obrigatorio"
    .parameters.Delete  "l_valor_padrao"
  end with
End Sub

REM =========================================================================
REM Mantém a tabela de Triggers
REM -------------------------------------------------------------------------
Sub DML_PutTrigger (Operacao, p_chave, p_sq_tabela, p_sq_usuario, p_sq_sistema, p_nome, p_descricao)
  Dim l_Operacao, l_Chave, l_sq_tabela, l_sq_usuario, l_sq_sistema, l_nome, l_descricao
  Set l_Operacao    = Server.CreateObject("ADODB.Parameter")
  Set l_chave       = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_tabela   = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_usuario  = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_sistema  = Server.CreateObject("ADODB.Parameter") 
  Set l_nome        = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao   = Server.CreateObject("ADODB.Parameter") 
  with sp
    set l_Operacao    = .CreateParameter("l_operacao"  , adVarchar, adParamInput,   1, Operacao)
    set l_chave       = .CreateParameter("l_chave"     , adInteger, adParamInput,    , Tvl(p_chave))
    set l_sq_tabela   = .CreateParameter("l_sq_tabela" , adInteger, adParamInput,    , Tvl(p_sq_tabela))
    set l_sq_usuario  = .CreateParameter("l_sq_usuario", adInteger, adParamInput,    , Tvl(p_sq_usuario))
    set l_sq_sistema  = .CreateParameter("l_sq_sistema", adInteger, adParamInput,    , Tvl(p_sq_sistema))
    set l_nome        = .CreateParameter("l_nome"      , adVarchar, adParamInput,  30, Tvl(p_nome))
    set l_descricao	  = .CreateParameter("l_descricao" , adVarchar, adParamInput,4000, Tvl(p_descricao))
    .parameters.Append  l_Operacao
    .parameters.Append  l_Chave
    .parameters.Append  l_sq_tabela
    .parameters.Append  l_sq_usuario
    .parameters.Append  l_sq_sistema
    .parameters.Append  l_nome
    .parameters.Append  l_descricao
    .CommandText        = Session("schema") & "SP_PutTrigger"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
    .parameters.Delete  "l_Operacao"
    .parameters.Delete  "l_Chave"
    .parameters.Delete  "l_sq_tabela"
    .parameters.Delete  "l_sq_usuario"
    .parameters.Delete  "l_sq_sistema"
    .parameters.Delete  "l_nome"
    .parameters.Delete  "l_descricao"
  end with
End Sub


REM =========================================================================
REM Mantém a tabela de Stored Procedures
REM -------------------------------------------------------------------------
Sub DML_PutStoredProcedure (Operacao, p_chave, p_sq_sp_tipo, p_sq_usuario, p_sq_sistema, p_nome, p_descricao)
  Dim l_Operacao, l_chave, l_sq_sp_tipo, l_sq_usuario, l_sq_sistema, l_nome, l_descricao
  Set l_Operacao    = Server.CreateObject("ADODB.Parameter")
  Set l_chave       = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_sp_tipo  = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_usuario  = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_sistema  = Server.CreateObject("ADODB.Parameter") 
  Set l_nome        = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao   = Server.CreateObject("ADODB.Parameter") 
  with sp
    set l_Operacao    = .CreateParameter("l_operacao"  , adVarchar, adParamInput,   1, Operacao)
    set l_chave       = .CreateParameter("l_chave"     , adInteger, adParamInput,    , Tvl(p_chave))
    set l_sq_sp_tipo  = .CreateParameter("l_sq_sp_tipo", adInteger, adParamInput,    , Tvl(p_sq_sp_tipo))
    set l_sq_usuario  = .CreateParameter("l_sq_usuario", adInteger, adParamInput,    , Tvl(p_sq_usuario))
    set l_sq_sistema  = .CreateParameter("l_sq_sistema", adInteger, adParamInput,    , Tvl(p_sq_sistema))
    set l_nome        = .CreateParameter("l_nome"      , adVarchar, adParamInput,  30, Tvl(p_nome))
    set l_descricao	  = .CreateParameter("l_descricao" , adVarchar, adParamInput,4000, Tvl(p_descricao))
    .parameters.Append  l_Operacao
    .parameters.Append  l_Chave
    .parameters.Append  l_sq_sp_tipo
    .parameters.Append  l_sq_usuario
    .parameters.Append  l_sq_sistema
    .parameters.Append  l_nome
    .parameters.Append  l_descricao
    .CommandText        = Session("schema") & "SP_PutStoredProcedure"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
    .parameters.Delete  "l_Operacao"
    .parameters.Delete  "l_Chave"
    .parameters.Delete  "l_sq_sp_tipo"
    .parameters.Delete  "l_sq_usuario"
    .parameters.Delete  "l_sq_sistema"
    .parameters.Delete  "l_nome"
    .parameters.Delete  "l_descricao"
  end with
End Sub

REM =========================================================================
REM Mantém a tabela de Procedure
REM -------------------------------------------------------------------------
Sub DML_PutProcedure (Operacao, p_chave, p_sq_arquivo, p_sq_sistema, p_sq_sp_tipo, p_nome, p_descricao)
  Dim l_Operacao, l_chave, l_sq_arquivo, l_sq_sistema, l_sq_sp_tipo, l_nome, l_descricao
  Set l_Operacao    = Server.CreateObject("ADODB.Parameter")
  Set l_chave       = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_arquivo  = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_sistema  = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_sp_tipo  = Server.CreateObject("ADODB.Parameter") 
  Set l_nome        = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao   = Server.CreateObject("ADODB.Parameter") 
  with sp
    set l_Operacao    = .CreateParameter("l_operacao"  , adVarchar, adParamInput,   1, Operacao)
    set l_chave       = .CreateParameter("l_chave"     , adInteger, adParamInput,    , Tvl(p_chave))
    set l_sq_arquivo  = .CreateParameter("l_sq_arquivo", adInteger, adParamInput,    , Tvl(p_sq_arquivo))
    set l_sq_sistema  = .CreateParameter("l_sq_sistema", adInteger, adParamInput,    , Tvl(p_sq_sistema))
    set l_sq_sp_tipo  = .CreateParameter("l_sq_sp_tipo", adInteger, adParamInput,    , Tvl(p_sq_sp_tipo))
    set l_nome        = .CreateParameter("l_nome"      , adVarchar, adParamInput,  30, Tvl(p_nome))
    set l_descricao	  = .CreateParameter("l_descricao" , adVarchar, adParamInput,4000, Tvl(p_descricao))
    .parameters.Append  l_Operacao
    .parameters.Append  l_Chave
    .parameters.Append  l_sq_arquivo
    .parameters.Append  l_sq_sistema
    .parameters.Append  l_sq_sp_tipo
    .parameters.Append  l_nome
    .parameters.Append  l_descricao
    .CommandText        = Session("schema") & "SP_PutProcedure"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
    .parameters.Delete  "l_Operacao"
    .parameters.Delete  "l_Chave"
    .parameters.Delete  "l_sq_arquivo"
    .parameters.Delete  "l_sq_sistema"
    .parameters.Delete  "l_sq_sp_tipo"
    .parameters.Delete  "l_nome"
    .parameters.Delete  "l_descricao"
  end with
End Sub

REM =========================================================================
REM Mantém a tabela de Procedure
REM -------------------------------------------------------------------------
Sub DML_PutRelacionamento (Operacao, p_chave, p_nome, p_descricao, p_sq_tabela_pai, p_sq_tabela_filha, p_sq_sistema)
  Dim l_operacao, l_chave, l_nome, l_descricao, l_sq_tabela_pai, l_sq_tabela_filha, l_sq_sistema
  Set l_Operacao         = Server.CreateObject("ADODB.Parameter")
  Set l_chave            = Server.CreateObject("ADODB.Parameter") 
  Set l_nome             = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao        = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_tabela_pai    = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_tabela_filha  = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_sistema       = Server.CreateObject("ADODB.Parameter") 
  with sp
    set l_Operacao         = .CreateParameter("l_operacao"       , adVarchar, adParamInput,   1, Operacao)
    set l_chave            = .CreateParameter("l_chave"          , adInteger, adParamInput,    , Tvl(p_chave))
    set l_nome             = .CreateParameter("l_nome"           , adVarchar, adParamInput,  30, Tvl(p_nome))
    set l_descricao		   = .CreateParameter("l_descricao"      , adVarchar, adParamInput,4000, Tvl(p_descricao))
    set l_sq_tabela_pai    = .CreateParameter("l_sq_tabela_pai"  , adInteger, adParamInput,    , Tvl(p_sq_tabela_pai))
    set l_sq_tabela_filha  = .CreateParameter("l_sq_tabela_filha", adInteger, adParamInput,    , Tvl(p_sq_tabela_filha))
    set l_sq_sistema       = .CreateParameter("l_sq_sistema"     , adInteger, adParamInput,    , Tvl(p_sq_sistema))
    .parameters.Append  l_Operacao
    .parameters.Append  l_Chave
    .parameters.Append  l_nome
    .parameters.Append  l_descricao
    .parameters.Append  l_sq_tabela_pai
    .parameters.Append  l_sq_tabela_filha
    .parameters.Append  l_sq_sistema
    .CommandText        = Session("schema") & "SP_PutRelacionamento"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
    .parameters.Delete  "l_Operacao"
    .parameters.Delete  "l_Chave"
    .parameters.Delete  "l_nome"
    .parameters.Delete  "l_descricao"
    .parameters.Delete  "l_sq_tabela_pai"
    .parameters.Delete  "l_sq_tabela_filha"
    .parameters.Delete  "l_sq_sistema"
  end with
End Sub

REM =========================================================================
REM Mantém a tabela de Índice
REM -------------------------------------------------------------------------
Sub DML_PutIndice (Operacao, p_chave, p_sq_indice_tipo, p_sq_usuario, p_sq_sistema, p_nome, p_descricao)
  Dim l_Operacao, l_chave, l_sq_indice_tipo, l_sq_usuario, l_sq_sistema, l_nome, l_descricao
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_chave           = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_indice_tipo  = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_usuario      = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_sistema      = Server.CreateObject("ADODB.Parameter") 
  Set l_nome            = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao       = Server.CreateObject("ADODB.Parameter") 
  with sp
    set l_Operacao        = .CreateParameter("l_operacao"      , adVarchar, adParamInput,   1, Operacao)
    set l_chave           = .CreateParameter("l_chave"         , adInteger, adParamInput,    , Tvl(p_chave))
    set l_sq_indice_tipo  = .CreateParameter("l_sq_indice_tipo", adInteger, adParamInput,    , Tvl(p_sq_indice_tipo))
    set l_sq_usuario      = .CreateParameter("l_sq_usuario"    , adInteger, adParamInput,    , Tvl(p_sq_usuario))
    set l_sq_sistema      = .CreateParameter("l_sq_sistema"    , adInteger, adParamInput,    , Tvl(p_sq_sistema))
    set l_nome            = .CreateParameter("l_nome"          , adVarchar, adParamInput,  30, Tvl(p_nome))
    set l_descricao		  = .CreateParameter("l_descricao"     , adVarchar, adParamInput,4000, Tvl(p_descricao))
    .parameters.Append  l_Operacao
    .parameters.Append  l_Chave
    .parameters.Append  l_sq_indice_tipo
    .parameters.Append  l_sq_usuario
    .parameters.Append  l_sq_sistema
    .parameters.Append  l_nome
    .parameters.Append  l_descricao
    .CommandText        = Session("schema") & "SP_PutIndice"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
    .parameters.Delete  "l_Operacao"
    .parameters.Delete  "l_Chave"
    .parameters.Delete  "l_sq_indice_tipo"
    .parameters.Delete  "l_sq_usuario"
    .parameters.Delete  "l_sq_sistema"
    .parameters.Delete  "l_nome"
    .parameters.Delete  "l_descricao"
  end with
End Sub

REM =========================================================================
REM Atualiza o dicionário de dados do usuário indicado
REM -------------------------------------------------------------------------
Sub DML_PutDicionario(p_cliente, p_sg_sistema, p_sg_usuario)
  Dim l_cliente, l_sg_sistema, l_sg_usuario
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_sg_sistema  = Server.CreateObject("ADODB.Parameter")
  Set l_sg_usuario  = Server.CreateObject("ADODB.Parameter") 
  with sp
    set l_cliente     = .CreateParameter("l_cliente"   ,  adInteger, adParamInput,     , p_cliente)
    set l_sg_sistema  = .CreateParameter("l_sg_sistema",  adVarchar, adParamInput,   50, p_sg_sistema)
    set l_sg_usuario  = .CreateParameter("l_sg_usuario",  adVarchar, adParamInput,   50, p_sg_usuario)
    .parameters.Append  l_cliente
    .parameters.Append  l_sg_sistema
    .parameters.Append  l_sg_usuario
    .CommandText        = "SYS.SP_PutDicionario"
    On error Resume Next
    .Execute
    If Err.Description > "" Then 
      TrataErro
    End If
    .parameters.Delete  "l_cliente"
    .parameters.Delete  "l_sg_sistema"
    .parameters.Delete  "l_sg_usuario"
  end with
End Sub
%>