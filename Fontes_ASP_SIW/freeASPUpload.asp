<%
'  Para exemplos, documentação e sua própria cópia livre, visite:
'  http://www.freeaspupload.net
'  Nota: Você pode copiar e usar este script livremente. Pode também alterar o código conforme sua necessidade
'  mas você não pode remover o estas linhas de comentário.

Class FreeASPUpload
	Public UploadedFiles
	Public FormElements

	Private VarArrayBinRequest
	Private StreamRequest
	Private uploadedYet

	Private Sub Class_Initialize()
		Set UploadedFiles = Server.CreateObject("Scripting.Dictionary")
		Set FormElements = Server.CreateObject("Scripting.Dictionary")
		Set StreamRequest = Server.CreateObject("ADODB.Stream")
		StreamRequest.Type = 1 'adTypeBinary
		StreamRequest.Open
		uploadedYet = false
	End Sub
	
	Private Sub Class_Terminate()
		If IsObject(UploadedFiles) Then
			UploadedFiles.RemoveAll()
			Set UploadedFiles = Nothing
		End If
		If IsObject(FormElements) Then
			FormElements.RemoveAll()
			Set FormElements = Nothing
		End If
		StreamRequest.Close
		Set StreamRequest = Nothing
	End Sub

	Public Property Get Form(sIndex)
		Form = ""
		If FormElements.Exists(LCase(sIndex)) Then Form = FormElements.Item(LCase(sIndex))
	End Property

	Public Property Get Files()
		Files = UploadedFiles.Items
	End Property

	'Calls Upload to extract the data from the binary request and then saves the uploaded files
	Public Sub Save(path, savefiles)
		Dim streamFile, fileItem

		if Right(path, 1) <> "\" then path = path & "\"

		if not uploadedYet then Upload

		if savefiles then
		   For Each fileItem In UploadedFiles.Items
			   Set streamFile = Server.CreateObject("ADODB.Stream")
			   streamFile.Type = 1
			   streamFile.Open
			   StreamRequest.Position=fileItem.Start
			   StreamRequest.CopyTo streamFile, fileItem.Length
			   streamFile.SaveToFile path & fileItem.FileName, 2
			   streamFile.close
			   Set streamFile = Nothing
			   fileItem.Path = path & fileItem.FileName
		    Next
		 end if
	End Sub

	Public Function SaveBinRequest(path) ' Para propósito de debugging
		StreamRequest.SaveToFile path & "\debugStream.bin", 2
	End Function

	Public Sub DumpData() 'Funciona apenas com arquivos plain text
		Dim i, aKeys, f
		response.write "Form Items:<br>"
		aKeys = FormElements.Keys
		For i = 0 To FormElements.Count -1 ' Varre o array
			response.write aKeys(i) & " = " & FormElements.Item(aKeys(i)) & "<BR>"
		Next
		response.write "Uploaded Files:<br>"
		For Each f In UploadedFiles.Items
			response.write "Nome: " & f.FileName & "<br>"
			response.write "Tipo: " & f.ContentType & "<br>"
			response.write "Início: " & f.Start & "<br>"
			response.write "Tamanho: " & f.Length & "<br>"
		 Next
   	End Sub

	Private Sub Upload()
		Dim nCurPos, nDataBoundPos, nLastSepPos
		Dim nPosFile, nPosBound
		Dim sFieldName, osPathSep, auxStr

		'RFC1867 Tokens
		Dim vDataSep
		Dim tNewLine, tDoubleQuotes, tTerm, tFilename, tName, tContentDisp, tContentType
		tNewLine = Byte2String(Chr(13))
		tDoubleQuotes = Byte2String(Chr(34))
		tTerm = Byte2String("--")
		tFilename = Byte2String("filename=""")
		tName = Byte2String("name=""")
		tContentDisp = Byte2String("Content-Disposition")
		tContentType = Byte2String("Content-Type:")

		uploadedYet = true

		on error resume next
		VarArrayBinRequest = Request.BinaryRead(Request.TotalBytes)
		if Err.Number <> 0 then 
			response.write "<br><br><B>Erro reportado pelo sistema:</B><p>"
			response.write Err.Description & "<p>"
			response.write "A causa mais provável para este erro é a configuração incorreta do AspMaxRequestEntityAllowed no IIS MetaBase. Favor verificar a <A HREF='http://www.freeaspupload.net/freeaspupload/requirements.asp'>página de instruções</a> do freeaspupload.net (em inglês).<p>"
			Exit Sub
		end if
		on error goto 0 'descarta tratamento de erros

		nCurPos = FindToken(tNewLine,1) 'Nota: nCurPos é 1-based (da mesma forma que InstrB, MidB, etc)

		If nCurPos <= 1  Then Exit Sub
		 
		'vDataSep é um separador do tipo -----------------------------21763138716045
		vDataSep = MidB(VarArrayBinRequest, 1, nCurPos-1)

		'Inicia o separador corrente
		nDataBoundPos = 1

		'Início da última linha
		nLastSepPos = FindToken(vDataSep & tTerm, 1)

		Do Until nDataBoundPos = nLastSepPos
			
			nCurPos = SkipToken(tContentDisp, nDataBoundPos)
			nCurPos = SkipToken(tName, nCurPos)
			sFieldName = ExtractField(tDoubleQuotes, nCurPos)

			nPosFile = FindToken(tFilename, nCurPos)
			nPosBound = FindToken(vDataSep, nCurPos)
			
			If nPosFile <> 0 And  nPosFile < nPosBound Then
				Dim oUploadFile
				Set oUploadFile = New UploadedFile
				
				nCurPos = SkipToken(tFilename, nCurPos)
				auxStr = ExtractField(tDoubleQuotes, nCurPos)
                ' Estamos interessados apenas no nome do arquivo, e não na path completa
                ' Separador de path é \ no windows e / no UNIX
                ' Enquanto o MS-IE parece colocar toda a pathname na stream, Mozilla tende a colocar apenas
                ' o nome do arquivo, então paths UNIX podem ser raras mas não impossíveis.
                osPathSep = "\"
                if InStr(auxStr, osPathSep) = 0 then osPathSep = "/"
				oUploadFile.FileName = Right(auxStr, Len(auxStr)-InStrRev(auxStr, osPathSep))

				if (Len(oUploadFile.FileName) > 0) then 'Campo arquivo preenchido
					nCurPos = SkipToken(tContentType, nCurPos)
					
                    auxStr = ExtractField(tNewLine, nCurPos)
                    ' NN no UNIX coloca coisas como esta na stream:
                    '    ?? python py type=?? python application/x-python
					oUploadFile.ContentType = Right(auxStr, Len(auxStr)-InStrRev(auxStr, " "))
					nCurPos = FindToken(tNewLine, nCurPos) + 4 'pula linha em branco
					
					oUploadFile.Start = nCurPos-1
					oUploadFile.Length = FindToken(vDataSep, nCurPos) - 2 - nCurPos
					
					If oUploadFile.Length > 0 Then UploadedFiles.Add LCase(sFieldName), oUploadFile
				End If
			Else
				Dim nEndOfData
				nCurPos = FindToken(tNewLine, nCurPos) + 4 'pula linha em branco
				nEndOfData = FindToken(vDataSep, nCurPos) - 2
				If Not FormElements.Exists(LCase(sFieldName)) Then FormElements.Add LCase(sFieldName), String2Byte(MidB(VarArrayBinRequest, nCurPos, nEndOfData-nCurPos))
			End If

			'Avança para o próximo separador
			nDataBoundPos = FindToken(vDataSep, nCurPos)
		Loop
		StreamRequest.Write(VarArrayBinRequest)
	End Sub

	Private Function SkipToken(sToken, nStart)
		SkipToken = InstrB(nStart, VarArrayBinRequest, sToken)
		If SkipToken = 0 then
			Response.write "Erro durante a verificação da uploaded binary request."
			Response.End
		end if
		SkipToken = SkipToken + LenB(sToken)
	End Function

	Private Function FindToken(sToken, nStart)
		FindToken = InstrB(nStart, VarArrayBinRequest, sToken)
	End Function

	Private Function ExtractField(sToken, nStart)
		Dim nEnd
		nEnd = InstrB(nStart, VarArrayBinRequest, sToken)
		If nEnd = 0 then
			Response.write "Erro durante a verificação da uploaded binary request."
			Response.End
		end if
		ExtractField = String2Byte(MidB(VarArrayBinRequest, nStart, nEnd-nStart))
	End Function

	'Conversão String para byte string
	Private Function Byte2String(sString)
		Dim i
		For i = 1 to Len(sString)
		   Byte2String = Byte2String & ChrB(AscB(Mid(sString,i,1)))
		Next
	End Function

	'Conversão Byte string para string
	Private Function String2Byte(bsString)
		Dim i
		String2Byte =""
		For i = 1 to LenB(bsString)
		   String2Byte = String2Byte & Chr(AscB(MidB(bsString,i,1))) 
		Next
	End Function
End Class

Class UploadedFile
	Public ContentType
	Public Start
	Public Length
	Public Path
	Private nameOfFile

    ' Remove caracteres válidos no UNIX mas não no Windows
    Public Property Let FileName(fN)
        nameOfFile = fN
        nameOfFile = SubstNoReg(nameOfFile, "\", "_")
        nameOfFile = SubstNoReg(nameOfFile, "/", "_")
        nameOfFile = SubstNoReg(nameOfFile, ":", "_")
        nameOfFile = SubstNoReg(nameOfFile, "*", "_")
        nameOfFile = SubstNoReg(nameOfFile, "?", "_")
        nameOfFile = SubstNoReg(nameOfFile, """", "_")
        nameOfFile = SubstNoReg(nameOfFile, "<", "_")
        nameOfFile = SubstNoReg(nameOfFile, ">", "_")
        nameOfFile = SubstNoReg(nameOfFile, "|", "_")
    End Property

    Public Property Get FileName()
        FileName = nameOfFile
    End Property

    'Propriedade pública Get FileN()ame
End Class


' Não depende do RegEx, que está disponível apenas em versões antigas do VBScript
' Não é recursivo, o que significa que não irá causar estouro de pilha
Function SubstNoReg(initialStr, oldStr, newStr)
    Dim currentPos, oldStrPos, skip
    If IsNull(initialStr) Or Len(initialStr) = 0 Then
        SubstNoReg = ""
    ElseIf IsNull(oldStr) Or Len(oldStr) = 0 Then
        SubstNoReg = initialStr
    Else
        If IsNull(newStr) Then newStr = ""
        currentPos = 1
        oldStrPos = 0
        SubstNoReg = ""
        skip = Len(oldStr)
        Do While currentPos <= Len(initialStr)
            oldStrPos = InStr(currentPos, initialStr, oldStr)
            If oldStrPos = 0 Then
                SubstNoReg = SubstNoReg & Mid(initialStr, currentPos, Len(initialStr) - currentPos + 1)
                currentPos = Len(initialStr) + 1
            Else
                SubstNoReg = SubstNoReg & Mid(initialStr, currentPos, oldStrPos - currentPos) & newStr
                currentPos = oldStrPos + skip
            End If
        Loop
    End If
End Function

' Verifica o ambiente
function TestEnvironment()
    Dim fso, fileName, testFile, streamTest
    TestEnvironment = ""
    Set fso = Server.CreateObject("Scripting.FileSystemObject")
    if not fso.FolderExists(uploadsDirVar) then
        TestEnvironment = "<B>Diretório " & uploadsDirVar & " não existe.</B><br>O valor da variável uploadsDirVar está incorreto. Edite uploadTester.asp e altere o valor de uploadsDirVar para um diretório com permissão de escrita."
        exit function
    end if
    fileName = uploadsDirVar & "\test.txt"
    on error resume next
    Set testFile = fso.CreateTextFile(fileName, true)
    If Err.Number<>0 then
        TestEnvironment = "<B>Diretório " & uploadsDirVar & " não tem permissão de escrita.</B><br>O valor da variável uploadsDirVar está incorreto. Edite uploadTester.asp e altere o valor de uploadsDirVar para um diretório com permissão de escrita."
        exit function
    end if
    Err.Clear
    testFile.Close
    fso.DeleteFile(fileName)
    If Err.Number<>0 then
        TestEnvironment = "<B>Diretório " & uploadsDirVar & " não tem permissão de remoção de arquivos</B>, apesar de ter permissão de escrita.<br>Mude as permissões do usuário IUSR_<I>computername</I> neste diretório."
        exit function
    end if
    Err.Clear
    Set streamTest = Server.CreateObject("ADODB.Stream")
    If Err.Number<>0 then
        TestEnvironment = "<B>O objeto ADODB <I>Stream</I> não está disponível no servidor.</B><br>Verifique a página de requisitos para informações sobre atualização das bibliotecas ADODB."
        exit function
    end if
    Set streamTest = Nothing
end function

' Lista os arquivos transferidos
function SaveFiles
    Dim Upload, fileName, fileSize, ks, i, fileKey

    Set Upload = New FreeASPUpload
    Upload.Save(uploadsDirVar)

	' If something fails inside the script, but the exception is handled
	If Err.Number<>0 then Exit function

    SaveFiles = ""
    ks = Upload.UploadedFiles.keys
    if (UBound(ks) <> -1) then
        SaveFiles = "<B>Files uploaded:</B> "
        for each fileKey in Upload.UploadedFiles.keys
            SaveFiles = SaveFiles & Upload.UploadedFiles(fileKey).FileName & " (" & Upload.UploadedFiles(fileKey).Length & "B) "
         next
    else
        SaveFiles = "O arquivo especificado no formulário de upload não tem correspondência para um arquivo válido no sistema."
    end if
end function
%>