<%
REM =========================================================================
REM Grava dados da importaηγo de arquivos oriundos do SIAFI
REM -------------------------------------------------------------------------
Sub DML_PutOrImport(Operacao, p_chave, p_cliente, p_sq_pessoa, p_data_arquivo, _
       p_arquivo_recebido, p_caminho_recebido, p_tamanho_recebido, p_tipo_recebido, _
       p_arquivo_registro, p_caminho_registro, p_tamanho_registro, p_tipo_registro, _
       p_registros, p_importados, p_rejeitados, p_situacao, p_nome_recebido, p_nome_registro)


  Dim l_Operacao, l_Chave, l_cliente, l_sq_pessoa, l_data_arquivo
  Dim l_arquivo_recebido, l_caminho_recebido, l_tamanho_recebido, l_tipo_recebido
  Dim l_arquivo_registro, l_caminho_registro, l_tamanho_registro, l_tipo_registro
  Dim l_registros, l_importados, l_rejeitados, l_situacao, l_nome_recebido, l_nome_registro
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_pessoa               = Server.CreateObject("ADODB.Parameter") 
  Set l_data_arquivo            = Server.CreateObject("ADODB.Parameter") 
  Set l_arquivo_recebido        = Server.CreateObject("ADODB.Parameter") 
  Set l_caminho_recebido        = Server.CreateObject("ADODB.Parameter") 
  Set l_tamanho_recebido        = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_recebido           = Server.CreateObject("ADODB.Parameter") 
  Set l_arquivo_registro        = Server.CreateObject("ADODB.Parameter") 
  Set l_caminho_registro        = Server.CreateObject("ADODB.Parameter") 
  Set l_tamanho_registro        = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_registro           = Server.CreateObject("ADODB.Parameter") 
  Set l_registros               = Server.CreateObject("ADODB.Parameter") 
  Set l_importados              = Server.CreateObject("ADODB.Parameter") 
  Set l_rejeitados              = Server.CreateObject("ADODB.Parameter") 
  Set l_situacao                = Server.CreateObject("ADODB.Parameter")
  Set l_nome_recebido           = Server.CreateObject("ADODB.Parameter")
  Set l_nome_registro           = Server.CreateObject("ADODB.Parameter")    
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",         adInteger, adParamInput,    , Tvl(p_cliente))
     set l_sq_pessoa            = .CreateParameter("l_sq_pessoa",       adInteger, adParamInput,    , Tvl(p_sq_pessoa))
     set l_data_arquivo         = .CreateParameter("l_data_arquivo",    adVarchar, adParamInput,  17, Tvl(p_data_arquivo))
     set l_arquivo_recebido     = .CreateParameter("l_arquivo_recebido",adVarchar, adParamInput, 255, Tvl(p_arquivo_recebido))
     set l_arquivo_recebido     = .CreateParameter("l_arquivo_recebido",adVarchar, adParamInput, 255, Tvl(p_arquivo_recebido))
     set l_caminho_recebido     = .CreateParameter("l_caminho_recebido",adVarchar, adParamInput, 255, Tvl(p_caminho_recebido))
     set l_tamanho_recebido     = .CreateParameter("l_tamanho_recebido",adInteger, adParamInput,    , Tvl(p_tamanho_recebido))
     set l_tipo_recebido        = .CreateParameter("l_tipo_recebido",   adVarchar, adParamInput,  60, Tvl(p_tipo_recebido))
     set l_arquivo_registro     = .CreateParameter("l_arquivo_registro",adVarchar, adParamInput, 255, Tvl(p_arquivo_registro))
     set l_caminho_registro     = .CreateParameter("l_caminho_registro",adVarchar, adParamInput, 255, Tvl(p_caminho_registro))
     set l_tamanho_registro     = .CreateParameter("l_tamanho_registro",adInteger, adParamInput,    , Tvl(p_tamanho_registro))
     set l_tipo_registro        = .CreateParameter("l_tipo_registro",   adVarchar, adParamInput,  60, Tvl(p_tipo_registro))
     set l_registros            = .CreateParameter("l_registros",       adInteger, adParamInput,    , Tvl(p_registros))
     set l_importados           = .CreateParameter("l_importados",      adInteger, adParamInput,    , Tvl(p_importados))
     set l_rejeitados           = .CreateParameter("l_rejeitados",      adInteger, adParamInput,    , Tvl(p_rejeitados))
     set l_situacao             = .CreateParameter("l_situacao",        adInteger, adParamInput,    , Tvl(p_situacao))
     set l_nome_recebido        = .CreateParameter("l_nome_recebido",   adVarchar, adParamInput, 255, Tvl(p_nome_recebido))
     set l_nome_registro        = .CreateParameter("l_nome_registro",   adVarchar, adParamInput, 255, Tvl(p_nome_registro))
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_pessoa
     .parameters.Append         l_data_arquivo
     .parameters.Append         l_arquivo_recebido
     .parameters.Append         l_caminho_recebido
     .parameters.Append         l_tamanho_recebido
     .parameters.Append         l_tipo_recebido
     .parameters.Append         l_arquivo_registro
     .parameters.Append         l_caminho_registro
     .parameters.Append         l_tamanho_registro
     .parameters.Append         l_tipo_registro
     .parameters.Append         l_registros
     .parameters.Append         l_importados
     .parameters.Append         l_rejeitados
     .parameters.Append         l_situacao
     .parameters.Append         l_nome_recebido
     .parameters.Append         l_nome_registro

     .CommandText               = Session("schema") & "SP_PutOrImport"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_sq_pessoa"
     .parameters.Delete         "l_data_arquivo"
     .parameters.Delete         "l_arquivo_recebido"
     .parameters.Delete         "l_caminho_recebido"
     .parameters.Delete         "l_tamanho_recebido"
     .parameters.Delete         "l_tipo_recebido"
     .parameters.Delete         "l_arquivo_registro"
     .parameters.Delete         "l_caminho_registro"
     .parameters.Delete         "l_tamanho_registro"
     .parameters.Delete         "l_tipo_registro"
     .parameters.Delete         "l_registros"
     .parameters.Delete         "l_importados"
     .parameters.Delete         "l_rejeitados"
     .parameters.Delete         "l_situacao"
     .parameters.Delete         "l_nome_recebido"
     .parameters.Delete         "l_nome_registro"
  end with
End Sub

%>