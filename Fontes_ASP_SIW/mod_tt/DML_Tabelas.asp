<%
REM =========================================================================
REM Mantém a tabela de centrais telefônicas
REM -------------------------------------------------------------------------
Sub DML_PutTTCentral(Operacao, p_chave, p_cliente, p_sq_pessoa_endereco, p_arquivo_bilhetes, p_recupera_bilhetes)

  Dim l_Operacao, l_Chave, l_cliente, l_sq_pessoa_endereco, l_sq_arquivo_bilhetes, l_arquivo_bilhetes, l_recupera_bilhetes
  
  Set l_Operacao           = Server.CreateObject("ADODB.Parameter")
  Set l_chave              = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente            = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_pessoa_endereco = Server.CreateObject("ADODB.Parameter")
  Set l_arquivo_bilhetes   = Server.CreateObject("ADODB.Parameter") 
  Set l_recupera_bilhetes  = Server.CreateObject("ADODB.Parameter")  
  
  with sp
     set l_Operacao           = .CreateParameter("l_operacao",           adVarchar, adParamInput,  1, Operacao)
     set l_chave              = .CreateParameter("l_chave",              adInteger, adParamInput, 18, Tvl(p_chave))
     set l_cliente            = .CreateParameter("l_cliente",            adInteger, adParamInput, 18, Tvl(p_cliente))
     set l_sq_pessoa_endereco = .CreateParameter("l_sq_pessoa_endereco", adInteger, adParamInput, 18, Tvl(p_sq_pessoa_endereco))
     set l_arquivo_bilhetes	  = .CreateParameter("l_arquivo_bilhetes",	 adVarchar, adParamInput, 60, Tvl(p_arquivo_bilhetes))
     set l_recupera_bilhetes  = .CreateParameter("l_recupera_bilhetes",	 adVarchar, adParamInput,  1, Tvl(p_recupera_bilhetes))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_pessoa_endereco
     .parameters.Append         l_arquivo_bilhetes
     .parameters.Append         l_recupera_bilhetes

     .CommandText               = Session("schema") & "SP_PutTTCentral"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_sq_pessoa_endereco"
     .parameters.Delete         "l_arquivo_bilhetes"
     .parameters.Delete         "l_recupera_bilhetes"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de centrais telefônicas
REM -------------------------------------------------------------------------
Sub DML_PutTTUsuarioCentral(Operacao, p_chave, p_cliente, p_usuario, p_sq_central_fone, p_codigo)

  Dim l_Operacao, l_Chave, l_cliente, l_usuario, l_sq_central_fone, l_codigo
  
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_chave           = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente         = Server.CreateObject("ADODB.Parameter") 
  Set l_usuario         = Server.CreateObject("ADODB.Parameter")
  Set l_sq_central_fone = Server.CreateObject("ADODB.Parameter") 
  Set l_codigo          = Server.CreateObject("ADODB.Parameter")  
  
  with sp
     set l_Operacao        = .CreateParameter("l_operacao",        adVarchar, adParamInput,  1, Operacao)
     set l_chave           = .CreateParameter("l_chave",           adInteger, adParamInput, 18, Tvl(p_chave))
     set l_cliente         = .CreateParameter("l_cliente",         adInteger, adParamInput, 18, Tvl(p_cliente))
     set l_usuario         = .CreateParameter("l_usuario",         adInteger, adParamInput, 18, Tvl(p_usuario))
     set l_sq_central_fone = .CreateParameter("l_sq_central_fone", adInteger, adParamInput, 18, Tvl(p_sq_central_fone))
     set l_codigo          = .CreateParameter("l_codigo",	       adVarchar, adParamInput,  4, Tvl(p_codigo))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_usuario
     .parameters.Append         l_sq_central_fone
     .parameters.Append         l_codigo

     .CommandText               = Session("schema") & "SP_PutTTUsuarioCentral"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_usuario"
     .parameters.Delete         "l_sq_central_fone"
     .parameters.Delete         "l_codigo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de centrais telefônicas
REM -------------------------------------------------------------------------
Sub DML_PutTTPrefixo (Operacao, p_chave, p_prefixo, p_localidade, p_sigla, p_uf, p_ddd, p_controle, p_degrau)

  Dim l_Operacao, l_Chave, l_prefixo, l_localidade, l_sigla, l_uf, l_ddd, l_controle, l_degrau
  
  Set l_Operacao   = Server.CreateObject("ADODB.Parameter")
  Set l_chave      = Server.CreateObject("ADODB.Parameter") 
  Set l_prefixo    = Server.CreateObject("ADODB.Parameter") 
  Set l_localidade = Server.CreateObject("ADODB.Parameter")
  Set l_sigla      = Server.CreateObject("ADODB.Parameter") 
  Set l_uf         = Server.CreateObject("ADODB.Parameter")
  Set l_ddd        = Server.CreateObject("ADODB.Parameter")    
  Set l_controle   = Server.CreateObject("ADODB.Parameter")
  Set l_degrau     = Server.CreateObject("ADODB.Parameter")  
  
  with sp
     set l_Operacao   = .CreateParameter("l_operacao"   , adVarchar, adParamInput,  1, Operacao)
     set l_chave      = .CreateParameter("l_chave"      , adInteger, adParamInput, 18, Tvl(p_chave))
     set l_prefixo    = .CreateParameter("l_prefixo"    , adVarchar, adParamInput, 15, Tvl(p_prefixo))
     set l_localidade = .CreateParameter("l_localidade" , adVarchar, adParamInput, 25, Tvl(p_localidade))
     set l_sigla      = .CreateParameter("l_sigla"      , adVarchar, adParamInput,  4, Tvl(p_sigla))
     set l_uf         = .CreateParameter("l_uf"         , adVarchar, adParamInput,  2, Tvl(p_uf))
     set l_ddd        = .CreateParameter("l_ddd"        , adVarchar, adParamInput,  4, Tvl(p_ddd))
     set l_controle   = .CreateParameter("l_controle"   , adVarchar, adParamInput, 16, Tvl(p_controle))
     set l_degrau     = .CreateParameter("l_degrau"     , adVarchar, adParamInput,  3, Tvl(p_degrau))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_prefixo
     .parameters.Append         l_localidade
     .parameters.Append         l_sigla
     .parameters.Append         l_uf
     .parameters.Append         l_ddd
     .parameters.Append         l_controle
     .parameters.Append         l_degrau

     .CommandText               = Session("schema") & "SP_PutTTPrefixo"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_prefixo"
     .parameters.Delete         "l_localidade"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_uf"
     .parameters.Delete         "l_ddd"
     .parameters.Delete         "l_controle"
     .parameters.Delete         "l_degrau"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de Ramais
REM -------------------------------------------------------------------------
Sub DML_PutTTRamal(Operacao, p_chave, p_sq_central_fone, p_codigo)

  Dim l_Operacao, l_Chave, l_sq_central_fone, l_codigo
  
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_chave           = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_central_fone = Server.CreateObject("ADODB.Parameter") 
  Set l_codigo          = Server.CreateObject("ADODB.Parameter")
   
  with sp
     set l_Operacao        = .CreateParameter("l_operacao",        adVarchar, adParamInput,  1, Operacao)
     set l_chave           = .CreateParameter("l_chave",           adInteger, adParamInput, 18, Tvl(p_chave))
     set l_sq_central_fone = .CreateParameter("l_sq_central_fone", adInteger, adParamInput, 18, Tvl(p_sq_central_fone))
     set l_codigo	       = .CreateParameter("l_codigo",	       adVarchar, adParamInput,  4, Tvl(p_codigo))
     

     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_central_fone
     .parameters.Append         l_codigo

     .CommandText               = Session("schema") & "SP_PutTTRamal"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_central_fone"
     .parameters.Delete         "l_codigo"
  end with
End Sub
REM ============================================d=============================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de centrais telefônicas
REM -------------------------------------------------------------------------
Sub DML_PutTTTronco(Operacao, p_chave, p_cliente, p_sq_central_fone, p_sq_pessoa_telefone, p_codigo, p_ativo)
    
  Dim l_Operacao, l_Chave, l_cliente, l_sq_central_fone, l_sq_pessoa_telefone, l_codigo, l_ativo
  
  Set l_Operacao           = Server.CreateObject("ADODB.Parameter")
  Set l_chave              = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente            = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_central_fone    = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_pessoa_telefone = Server.CreateObject("ADODB.Parameter")
  Set l_codigo             = Server.CreateObject("ADODB.Parameter")
  Set l_ativo              = Server.CreateObject("ADODB.Parameter")
  
  with sp
       
     set l_Operacao           = .CreateParameter("l_operacao",           adVarchar, adParamInput,  1, Operacao)
     set l_chave              = .CreateParameter("l_chave",              adInteger, adParamInput, 18, Tvl(p_chave))
     set l_cliente            = .CreateParameter("l_cliente",            adInteger, adParamInput, 18, Tvl(p_cliente))
     set l_sq_central_fone    = .CreateParameter("l_sq_central_fone",    adInteger, adParamInput, 18, Tvl(p_sq_central_fone))
     set l_sq_pessoa_telefone = .CreateParameter("l_sq_pessoa_telefone", adInteger, adParamInput, 18, Tvl(p_sq_pessoa_telefone))
     set l_codigo             = .CreateParameter("l_codigo",             adVarchar, adParamInput, 10, Tvl(p_codigo))
     set l_ativo              = .CreateParameter("l_ativo",              adVarchar, adParamInput,  1, Tvl(p_ativo))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_central_fone
     .parameters.Append         l_sq_pessoa_telefone
     .parameters.Append         l_codigo
     .parameters.Append         l_ativo
     
     .CommandText               = Session("schema") & "SP_PutTTTronco"
     
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_sq_central_fone"
     .parameters.Delete         "l_sq_pessoa_telefone"
     .parameters.Delete         "l_codigo"
     .parameters.Delete         "l_ativo"
    end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de centrais telefônicas
REM -------------------------------------------------------------------------
Sub DML_PutTTRamalUsuario(Operacao, p_chave, p_chave_aux, p_chave_aux2, p_inicio, p_fim)

  Dim l_Operacao, l_Chave, l_chave_aux, l_chave_aux2, l_inicio, l_fim
  
  Set l_Operacao           = Server.CreateObject("ADODB.Parameter")
  Set l_chave              = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux          = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux2         = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio             = Server.CreateObject("ADODB.Parameter")
  Set l_fim                = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao           = .CreateParameter("l_operacao"           , adVarchar, adParamInput,  1, Operacao)
     set l_chave              = .CreateParameter("l_chave"              , adInteger, adParamInput, 18, p_chave)
     set l_chave_aux          = .CreateParameter("l_chave_aux"          , adInteger, adParamInput, 18, p_chave_aux)
     set l_chave_aux2         = .CreateParameter("l_chave_aux2"         , adDate   , adParamInput,   , Nvl(p_chave_aux2,p_inicio))
     set l_inicio             = .CreateParameter("l_inicio"             , adDate   , adParamInput,   , Tvl(p_inicio))
     set l_fim	              = .CreateParameter("l_fim"                , adDate   , adParamInput,   , Tvl(p_fim))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_chave_aux2
     .parameters.Append         l_inicio
     .parameters.Append         l_fim

     .CommandText               = Session("schema") & "SP_PutTTRamalUsuario"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_chave_aux2"
     .parameters.Delete         "l_inicio"
     .parameters.Delete         "l_fim"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>