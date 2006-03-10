<%@EnableSessionState=False%>
<%
'Session must be off to work correctly.

Const RefreshTime = 1'Seconds

'Upload ID must be defined.
'Redirect to base script without the parameter.
if Request.QueryString("UploadID") = "" then 
   response.redirect "Upload.ASP"
end if

Server.ScriptTimeout = 10
Dim Form: Set Form = New ASPForm %><!--#INCLUDE FILE="_upload.asp"--><% 
  
'{b}Get current uploading form with UploadID.
on error resume next
Set Form = Form.getForm(Request.QueryString("UploadID"))'{/b}
  
if Err = 0 then '?Completted 0 = in progress
   on error goto 0
   if Form.BytesRead>0 then'Upload was started.
      Dim UpStateHTML
      'Get currently uploadded filenames and sizes
      UpStateHTML = FileStateInfo(Form)
   end if

   'Do not cache output data of this script.
   response.cachecontrol = "no-cache"
   response.AddHeader "Pragma","no-cache"
  
   'This script is progress bar.
   'There is a good idea to refresh it to show progress more than once :-).
   'Refresh time is in second
   response.addheader "Refresh", RefreshTime

   'Count progress indicators
   ' - percent and total read, total bytes, etc.
   Dim PercBytesRead, PercentRest, BytesRead, TotalBytes
   Dim UploadTime, RestTime, TransferRate
   BytesRead = Form.BytesRead
   TotalBytes = Form.TotalBytes

   if TotalBytes>0 then 
      'upload started.
      PercBytesRead = int(100*BytesRead/TotalBytes)
      PercentRest = 100-PercBytesRead
    
      if Form.ReadTime>0 then TransferRate = BytesRead / Form.ReadTime
      if TransferRate>0 then RestTime = FormatTime((TotalBytes-BytesRead) / TransferRate) 
      TransferRate = FormatSize(1000 * TransferRate)
   else
      'upload not started.
      RestTime = "?"
      PercBytesRead = 0
      PercentRest = 100
      TransferRate = "?"
   end if

   'Create graphics progress bar.
   'The bar is created with blue (TDsread, completted) / blank (TDsRemain, remaining) TD cells.
   Dim TDsread, TDsRemain
   TDsread = "<TD Width=""" & PercBytesRead & "%"" BGColor=blue Class=p>&nbsp;</TD>"
   TDsRemain = "<TD Width=""" & PercentRest & "%"" Class=p>&nbsp;</TD>"

   'Format output values.
   UploadTime = FormatTime(Form.ReadTime)
   TotalBytes = FormatSize(TotalBytes)
   BytesRead = FormatSize(BytesRead)

   'Simple utilities.
   'Formats milisedond to m:ss format.
   Function FormatTime(byval ms)
     ms = 0.001 * ms 'get second
     FormatTime = (ms \ 60) & ":" & right("0" & (ms mod 60),2) & "s"
   End Function 

   'Format bytes to a string
   Function FormatSize(byval Number)
     if isnumeric(Number) then
        if Number > &H100000 then'1M
          Number = FormatNumber (Number/&H100000,1) & "MB"
        elseif Number > 1024 then'1k
          Number = FormatNumber (Number/1024,1) & "kB"
        else
          Number = FormatNumber (Number,0) & "B"
        end if
     end if
     FormatSize = Number
   End Function

   Function FileStateInfo(Form)
     'enumerate uploaded fields.
     'and build report about its current state.
     On Error Resume Next
     Dim UpStateHTML, Field
     for each Field in Form.Files
       'Get field name
       UpStateHTML = UpStateHTML & "Arquivo:" & Field.Name
    
       if Field.InProgress then
          'this field is in progress now.
          UpStateHTML = UpStateHTML & ", transferindo: " & Field.FileName
       elseif Field.Length>0 then
          'This field was succefully uploaded.
          UpStateHTML = UpStateHTML & ", recebido: " & Field.FileName & ", " & FormatSize(Field.Length)
       end if
    
       UpStateHTML = UpStateHTML & "<br>"
     Next
     FileStateInfo = UpStateHTML
   End Function


   'Some comments for HTML
   'Page-Enter and revealTrans is for Flicker-Free progress.

   Response.Write "<HTML>" & VbCrLf
   Response.Write "<Head>" & VbCrLf
   Response.Write " <style type='text/css'>" & VbCrLf
   Response.Write "  BODY{font-size:10pt}" & VbCrLf
   Response.Write "  TD{font-size:9pt} " & VbCrLf
   Response.Write "  TD.p {font-size:6px;Height:20px;}" & VbCrLf
   Response.Write " </style>" & VbCrLf
   Response.Write " <meta http-equiv=""Page-Enter"" content=""revealTrans(Duration=0,Transition=6)""> " & VbCrLf
   Response.Write " <Title>" & PercBytesRead & "% completados - transferência para " & Request.ServerVariables("HTTP_HOST") & " em progresso </Title>" & VbCrLf
   Response.Write " <META HTTP-EQUIV=""Refresh"" CONTENT=""" & RefreshTime & """>" & VbCrLf

   Response.Write "</Head>" & VbCrLf

   Response.Write "<Body BGcolor=Silver LeftMargin=15 TopMargin=4 RIGHTMARGIN=4 BOTTOMMARGIN=4>" & VbCrLf

   Response.Write "<Table cellpadding=0 cellspacing=0 border=0 width=100% ><tr>" & VbCrLf
   Response.Write "<td>Transferindo:<br> " & TotalBytes & " para " & Request.ServerVariables("HTTP_HOST") & " ...<br></td>" & VbCrLf
   Response.Write "</tr></Table>" & VbCrLf

   Response.Write "<Table cellpadding=0 cellspacing=0 border=0 width=100% style=""border:1px inset white"">" & VbCrLf
   Response.Write "<tr>" & VbCrLf
   Response.Write TDsread & TDsRemain & VbCrLf
   Response.Write "</tr>" & VbCrLf
   Response.Write "</table>" & VbCrLf

   Response.Write "<Table cellpadding=0 cellspacing=0 border=0>" & VbCrLf
   Response.Write "<tr>" & VbCrLf
   Response.Write " <Td>Progresso</td>" & VbCrLf
   Response.Write " <Td>: " & BytesRead & " de " & TotalBytes & " (" & PercBytesRead & "%) </Td>" & VbCrLf
   Response.Write "</tr>" & VbCrLf
   Response.Write "<tr>" & VbCrLf
   Response.Write " <Td>Tempo </td>" & VbCrLf
   Response.Write " <Td>: " & UploadTime & " (" & TransferRate & "/s) </Td>" & VbCrLf
   Response.Write "</tr>" & VbCrLf
   Response.Write "<tr>" & VbCrLf
   Response.Write " <Td>Restante</td>" & VbCrLf
   Response.Write " <Td>: " & RestTime & " </Td>" & VbCrLf
   Response.Write "</tr>" & VbCrLf
   Response.Write "</table>" & VbCrLf

   Response.Write "<br><Center><Input Type=Button Value=""Cancelar"" OnClick=""Cancel()"" " & VbCrLf
   Response.Write " Style=""background-color:red;color:white;cursor:hand;font-weight:bold""></Center>" & VbCrLf
   Response.Write "<br>" & VbCrLf

   Response.Write UpStateHTML & VbCrLf

   Response.Write "<Script>" & VbCrLf
   Response.Write "//I'm sorry. IE enables switch-off refresh header. You can use script to do the same action" & VbCrLf
   Response.Write "//to be sure that progress window will refresh." & VbCrLf
   Response.Write "window.setTimeout('refresh()', " & RefreshTime & "*2000);" & VbCrLf

   Response.Write "function refresh(){" & VbCrLf
   Response.Write "    window.location.href = window.location.href;" & VbCrLf
   Response.Write "    window.setTimeout('refresh()', " & RefreshTime & "*2000);" & VbCrLf
   Response.Write "}" & VbCrLf
   Response.Write "function Cancel(){" & VbCrLf
   Response.Write "    //get opener location - this is URL of the main upload script." & VbCrLf
   Response.Write "    var l = ''+opener.document.location;" & VbCrLf
    
   Response.Write "    //Add Action=Cancel querystring parameter" & VbCrLf
   Response.Write "    if (l.indexOf('Action=Cancel')<0) {" & VbCrLf
   Response.Write "        l += (l.indexOf('?')<0 ? '?' : '&') + 'Action=Cancel'" & VbCrLf
   Response.Write "    };" & VbCrLf

   Response.Write "    //Set the new URL to opener (upload is cancelled)    " & VbCrLf
   Response.Write "    opener.document.location = l;" & VbCrLf

   Response.Write "    //Close this window." & VbCrLf
   Response.Write "    window.close();" & VbCrLf
   Response.Write "}" & VbCrLf
   Response.Write "</Script>" & VbCrLf

   Response.Write "</Body>" & VbCrLf
   Response.Write "</HTML>" & VbCrLf

else 'if Err = 0 then upload finished
   Response.Write "<HTML>" & VbCrLf
   Response.Write " <HEAD>" & VbCrLf
   Response.Write " <TITLE>Transferência completada</TITLE>" & VbCrLf
   Response.Write " <Script>window.close();</Script>" & VbCrLf
   Response.Write " </HEAD>" & VbCrLf
   Response.Write "</HTML>" & VbCrLf
End If 
%>