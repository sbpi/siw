<%
REM =========================================================================
REM Recupera Eventos de Trigger
REM -------------------------------------------------------------------------
Sub DB_GetCentralTel(p_rs, p_chave, p_cliente, p_sq_pessoa_endereco, p_sq_pessoa_telefone, p_restricao)
  
  Dim l_chave, l_cliente, l_sq_pessoa_endereco, l_sq_pessoa_telefone, l_restricao
  
  Set l_chave              = Server.CreateObject("ADODB.Parameter")
  Set l_cliente            = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pessoa_endereco = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pessoa_telefone = Server.CreateObject("ADODB.Parameter")
  Set l_restricao          = Server.CreateObject("ADODB.Parameter")
    
  with sp
  
    set l_chave              = .CreateParameter("l_chave",              adInteger, adParamInput,   , Tvl(p_chave))
    set l_cliente            = .CreateParameter("l_cliente",            adInteger, adParamInput,   , Tvl(p_cliente))
    set l_sq_pessoa_endereco = .CreateParameter("l_sq_pessoa_endereco", adInteger, adParamInput,   , Tvl(p_sq_pessoa_endereco))
    set l_sq_pessoa_telefone = .CreateParameter("l_sq_pessoa_telefone", adInteger, adParamInput,   , Tvl(p_sq_pessoa_telefone))
    set l_restricao          = .CreateParameter("l_restricao",          adVarchar, adParamInput, 10, Tvl(p_restricao))
      
    .parameters.Append   l_chave
    .parameters.Append   l_cliente
    .parameters.Append   l_sq_pessoa_endereco
    .parameters.Append   l_sq_pessoa_telefone
    .parameters.Append   l_restricao
      
    If Session("dbms")   = 1 Then .Properties("PLSQLRSet") = TRUE End If
    .CommandText         = Session("schema") & "SP_GetCentralTel"
    Set p_rs             = Server.CreateObject("ADODB.RecordSet")
    p_rs.cursortype      = adOpenStatic
    p_rs.cursorlocation  = adUseClient
    On Error Resume Next 
    Set p_rs             = .Execute
    If Err.Description > "" Then 
      TrataErro          
    End If     
    If Session("dbms")   = 1 Then 
      .Properties("PLSQLRSet") = FALSE 
    End If
    
    .Parameters.Delete   "l_chave"
    .Parameters.Delete   "l_cliente"
    .Parameters.Delete   "l_sq_pessoa_endereco"
    .Parameters.Delete   "l_sq_pessoa_telefone"
    .Parameters.Delete   "l_restricao"
      
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Eventos de Trigger
REM -------------------------------------------------------------------------
Sub DB_GetTTUsuario (p_rs, p_chave, p_cliente, p_usuario, p_sq_central_fone, p_codigo)
  
  Dim l_chave, l_cliente, l_usuario, l_sq_central_fone, l_codigo
  
  Set l_chave              = Server.CreateObject("ADODB.Parameter")
  Set l_cliente            = Server.CreateObject("ADODB.Parameter")
  Set l_usuario            = Server.CreateObject("ADODB.Parameter")
  Set l_sq_central_fone    = Server.CreateObject("ADODB.Parameter")
  Set l_codigo             = Server.CreateObject("ADODB.Parameter")
    
  with sp
  
    set l_chave           = .CreateParameter("l_chave"           , adInteger, adParamInput, 18, Tvl(p_chave))
    set l_cliente         = .CreateParameter("l_cliente"         , adInteger, adParamInput, 18, Tvl(p_cliente))
    set l_usuario         = .CreateParameter("l_usuario"         , adInteger, adParamInput, 18, Tvl(p_usuario))
    set l_sq_central_fone = .CreateParameter("l_sq_central_fone" , adInteger, adParamInput, 18, Tvl(p_sq_central_fone))
    set l_codigo          = .CreateParameter("l_codigo"          , adVarchar, adParamInput,  4, Tvl(p_codigo))
    
      
    .parameters.Append   l_chave
    .parameters.Append   l_cliente
    .parameters.Append   l_usuario
    .parameters.Append   l_sq_central_fone
    .parameters.Append   l_codigo
    
      
    If Session("dbms")   = 1 Then .Properties("PLSQLRSet") = TRUE End If
    .CommandText         = Session("schema") & "SP_GetTTUsuario"
    Set p_rs             = Server.CreateObject("ADODB.RecordSet")
    p_rs.cursortype      = adOpenStatic
    p_rs.cursorlocation  = adUseClient
    On Error Resume Next 
    Set p_rs             = .Execute
    If Err.Description > "" Then 
      TrataErro          
    End If     
    If Session("dbms")   = 1 Then 
      .Properties("PLSQLRSet") = FALSE 
    End If
    
    .Parameters.Delete   "l_chave"
    .Parameters.Delete   "l_cliente"
    .Parameters.Delete   "l_usuario"
    .Parameters.Delete   "l_sq_central_fone"
    .Parameters.Delete   "l_codigo"
    
      
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Eventos de Trigger
REM -------------------------------------------------------------------------
Sub DB_GetTTRamal(p_rs, p_chave, p_sq_central_fone, p_codigo, p_restricao)
  
  Dim l_chave, l_sq_central_fone, l_codigo, l_restricao
  
  Set l_chave           = Server.CreateObject("ADODB.Parameter")
  Set l_sq_central_fone = Server.CreateObject("ADODB.Parameter")
  Set l_codigo          = Server.CreateObject("ADODB.Parameter")
  Set l_restricao       = Server.CreateObject("ADODB.Parameter")
    
  with sp
  
    set l_chave           = .CreateParameter("l_chave"           , adInteger, adParamInput, 18, Tvl(p_chave))
    set l_sq_central_fone = .CreateParameter("l_sq_central_fone" , adInteger, adParamInput, 18, Tvl(p_sq_central_fone))
    set l_codigo          = .CreateParameter("l_codigo"          , adVarchar, adParamInput,  4, Tvl(p_codigo))
    set l_restricao       = .CreateParameter("l_restricao"       , adVarchar, adParamInput,  4, Tvl(p_restricao))
      
    .parameters.Append   l_chave
    .parameters.Append   l_sq_central_fone
    .parameters.Append   l_codigo
    .parameters.Append   l_restricao
      
    If Session("dbms")   = 1 Then .Properties("PLSQLRSet") = TRUE End If
    .CommandText         = Session("schema") & "SP_GetTTRamal"
    Set p_rs             = Server.CreateObject("ADODB.RecordSet")
    p_rs.cursortype      = adOpenStatic
    p_rs.cursorlocation  = adUseClient
    On Error Resume Next 
    Set p_rs             = .Execute
    If Err.Description > "" Then 
      TrataErro          
    End If     
    If Session("dbms")   = 1 Then 
      .Properties("PLSQLRSet") = FALSE 
    End If
    
    .Parameters.Delete   "l_chave"
    .Parameters.Delete   "l_sq_central_fone"
    .Parameters.Delete   "l_codigo"
    .Parameters.Delete   "l_restricao"      
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Eventos de Trigger
REM -------------------------------------------------------------------------
Sub DB_GetPessoaTel(p_rs, p_chave)
  
  Dim l_chave
  
  Set l_chave              = Server.CreateObject("ADODB.Parameter")
        
  with sp
  
    set l_chave = .CreateParameter("l_chave", adInteger, adParamInput, , Tvl(p_chave))
            
    .parameters.Append   l_chave
        
    If Session("dbms")   = 1 Then .Properties("PLSQLRSet") = TRUE End If
    .CommandText         = Session("schema") & "SP_GetPessoaTel"
    Set p_rs             = Server.CreateObject("ADODB.RecordSet")
    p_rs.cursortype      = adOpenStatic
    p_rs.cursorlocation  = adUseClient
    On Error Resume Next 
    Set p_rs             = .Execute
    If Err.Description > "" Then 
      TrataErro          
    End If     
    If Session("dbms")   = 1 Then 
      .Properties("PLSQLRSet") = FALSE 
    End If
    
    .Parameters.Delete   "l_chave"
  
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Eventos de Trigger
REM -------------------------------------------------------------------------
Sub DB_GetPrefixo (p_rs, p_chave, p_prefixo, p_uf)
  
  Dim l_chave, l_prefixo, l_uf
  
  Set l_chave   = Server.CreateObject("ADODB.Parameter")
  Set l_prefixo = Server.CreateObject("ADODB.Parameter")
  Set l_uf      = Server.CreateObject("ADODB.Parameter")
      
  with sp
  
    set l_chave   = .CreateParameter("l_chave"   , adInteger, adParamInput,   , Tvl(p_chave))
    set l_prefixo = .CreateParameter("l_prefixo" , adVarchar, adParamInput, 15, Tvl(p_prefixo))
    set l_uf      = .CreateParameter("l_uf"      , adVarchar, adParamInput,  2, Tvl(p_uf))
          
    .parameters.Append   l_chave
    .parameters.Append   l_prefixo
    .parameters.Append   l_uf
          
    If Session("dbms")   = 1 Then .Properties("PLSQLRSet") = TRUE End If
    .CommandText         = Session("schema") & "SP_GetTTPrefixo"
    Set p_rs             = Server.CreateObject("ADODB.RecordSet")
    p_rs.cursortype      = adOpenStatic
    p_rs.cursorlocation  = adUseClient
    On Error Resume Next 
    Set p_rs             = .Execute
    If Err.Description > "" Then 
      TrataErro          
    End If     
    If Session("dbms")   = 1 Then 
      .Properties("PLSQLRSet") = FALSE 
    End If
    
    .Parameters.Delete   "l_chave"
    .Parameters.Delete   "l_prefixo"
    .Parameters.Delete   "l_uf"
          
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>