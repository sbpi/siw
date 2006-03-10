<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<%
REM =========================================================================
REM  /file.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Devolve arquivos físicos para o cliente
REM Mail     : alex@sbpi.com.br
REM Criacao  : 07/02/2006, 10:23
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
   
Response.Buffer = True
   
Dim dbms, sp, RS, w_cliente, w_id, w_erro
Dim w_nome, w_descricao, w_inclusao, w_tamanho, w_tipo, w_caminho, w_force, w_filename
w_cliente = Request("cliente")
w_id      = Request("id")
w_force   = Nvl(Request("force"), "false")
w_erro    = 0 ' Se tiver valor diferente de 0, exibe mensagem de erro
      
If Nvl(w_cliente, "") = "" or Nvl(w_id, "") = "" or Session("dbms") = "" Then 
   w_erro = 1 ' Parâmetros incorretos
ElseIf Instr(w_id,".") > 0 Then
   w_nome        = ""
   w_descricao   = ""
   w_inclusao    = ""
   w_tamanho     = ""
   w_tipo        = Mid(w_id,Instr(w_id,"."),30)
   w_caminho     = w_id
   w_filename    = w_id
Else
   ' Configura objetos de BD
   Set RS  = Server.CreateObject("ADODB.RecordSet")
   AbreSessao
   
   ' Tenta recuperar os dados do arquivo selecionado
   DB_GetSIWArquivo RS, w_cliente, w_id, null
   If RS.EOF Then
      w_erro = 2 ' Arquivo não encontrado
   Else
      w_nome        = RS("nome")
      w_descricao   = RS("descricao")
      w_inclusao    = RS("inclusao")
      w_tamanho     = RS("tamanho")
      w_tipo        = RS("tipo")
      w_caminho     = RS("caminho")
      w_filename    = RS("nome_original")
   End If
   DesconectaBD
   FechaSessao
End if

If w_erro > 0 Then ' Se houve erro, exibe HTML
   Cabecalho
   BodyOpenClean "onLoad=document.focus();"
   ShowHTML "<div align=center><center><b>"
   If w_erro = 1 Then
      ShowHTML "Parâmetros de chamada incorretos"
   Else
      ShowHTL "Arquivo inexistente"
   End If
   ShowHTML "</b></center></div>"
   Rodape
Else
    Dim strFileName 'name of the file to be downloaded
    strFileName=w_caminho

    If Len(strFileName) > 0 Then
        Call DownloadFile (strFileName, w_force)
    End If
End If

Set dbms        = Nothing 
Set sp          = Nothing 
Set RS          = Nothing 
Set w_cliente   = Nothing 
Set w_id        = Nothing 
Set w_erro      = Nothing 
Set w_nome      = Nothing 
Set w_descricao = Nothing 
Set w_inclusao  = Nothing 
Set w_tamanho   = Nothing 
Set w_tipo      = Nothing 
Set w_caminho   = Nothing 
Set w_force     = Nothing
Set w_filename  = Nothing

Sub DownloadFile(strFileName, blnForceDownload)
    Dim fso, objFile, strFilePath
    Dim fileSize, blnBinary, strExtension
    Dim objStream, strAllFile
    
    '----------------------
    'first step: verify the file exists
    '----------------------
    
    'build file path:
    strFilePath=conFilePhysical & w_cliente & "\"
    ' add backslash if needed:
    If Right(strFilePath, 1)<>"\" Then strFilePath=strFilePath&"\"
    strFilePath=strFilePath&strFileName
    
    'initialize file system object:
    Set fso=Server.CreateObject("Scripting.FileSystemObject")
    
    'check that the file exists:
    If Not(fso.FileExists(strFilePath)) Then
        Set fso=Nothing
        Err.Raise 20000, "Gerenciador de Download", "Erro: arquivo inexistente: "&strFilePath
        Response.END
    End If
    
    '----------------------
    'second step: get file size.
    '----------------------
    Set objFile=fso.GetFile(strFilePath)
    fileSize=objFile.Size
    Set objFile=Nothing
    
    '----------------------
    'third step: check whether file is binary or not and get content type of the file. (according to its extension)
    '----------------------
    blnBinary=GetContentType(w_tipo, strExtension)
    strAllFile=""
    If InStr(w_filename, ".") = 0 Then
       w_filename = w_filename & strExtension
    End If
    '----------------------
    'forth step: read the file contents.
    '----------------------
    If blnBinary Then
        Set objStream=Server.CreateObject("ADODB.Stream")
        objStream.Open
        objStream.Type = 1 'adTypeBinary
        objStream.LoadFromFile strFilePath
        strAllFile=objStream.Read(fileSize)
        objStream.Close
        Set objStream = Nothing
    Else  
        Set objFile=fso.OpenTextFile(strFilePath,1) 'forReading
        If Not(objFile.AtEndOfStream) Then
            strAllFile=objFile.ReadAll
        End If
        objFile.Close
        Set objFile=Nothing
    End If
    
    '----------------------
    'final step: apply content type and send file contents to the browser
    '----------------------
    If blnForceDownload="true" Then
        Response.AddHeader "Content-Disposition", "attachment; filename="&w_filename
    Else
        Response.AddHeader "Content-Disposition", "filename="&w_filename
    End If
    Response.AddHeader "Content-Length", fileSize
    If Instr(w_tipo,".") = 0 Then Response.ContentType = w_tipo End If
    If blnBinary Then
        Response.BinaryWrite(strAllFile)
    Else  
        Response.Write(strAllFile)
    End If
    
    'clean up:
    Set fso=Nothing
    Response.Flush
    Response.END
End Sub

Function GetContentType(ByVal strName, ByRef Extension)
    'return whether binary or not, put type into second parameter
    Select Case strName
        Case "video/x-ms-asf"
            Extension = ".asf"
            GetContentType=True
        Case "video/avi"
            Extension = ".avi"
            GetContentType=True
        Case "application/msword"
            Extension = ".doc"
            GetContentType=True
        Case "application/zip"
            Extension = ".zip"
            GetContentType=True
        Case "application/vnd.ms-excel"
            Extension = ".xls"
            GetContentType=True
        Case "application/vnd.ms-powerpoint"
            Extension = ".ppt"
            GetContentType=True
        Case "image/gif"
            Extension = ".gif"
            GetContentType=True
        Case "image/jpeg"
            Extension = ".jpg"
            GetContentType=True
        Case "audio/wav"
            Extension = ".wav"
            GetContentType=True
        Case "audio/mpeg3"
            Extension = ".mp3"
            GetContentType=True
        Case "video/mpeg"
            Extension = ".mpg"
            GetContentType=True
        Case "application/rtf"
            Extension = ".rtf"
            GetContentType=True
        Case "text/html"
            Extension = ".htm"
            GetContentType=False
        Case "text/asp"
            Extension = ".asp"
            GetContentType=False
        Case "text/plain"
            Extension = ".htm"
            GetContentType=False
        Case ".gif"
            Extension = ".gif"
            GetContentType=True
        Case ".js"
            Extension = ".js"
            GetContentType=true
        Case ".css"
            Extension = ".css"
            GetContentType=False
        Case ".jpg", ".jpeg"
            Extension = ".jpg"
            GetContentType=True
        Case Else
            'Handle All Other Files
            Extension = ""
            GetContentType=True
    End Select
End Function
%>