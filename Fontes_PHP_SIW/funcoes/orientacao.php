<?php 
include_once("../constants.inc");
$p_tipo = strtoupper($_REQUEST['p_tipo']);
header("Cache-Control: no-cache, must-revalidate",false);
?>
<script src="js/jquery.js"></script>
<script src="js/funcoes.js"></script>
<html>
<head>
<!-- <style>
.vertical{
    text-decoration: none;
}
.vertical:hover span{
    text-decoration: underline;
    }
</style>-->
<style>
body{ 
    behavior:url("csshover.htc"); 
}
label{
    text-decoration: none;
}
#rvert:hover span{
    text-decoration: underline;
    cursor:pointer;
}
</style>
</head>
<body>
    <form name="frm">
       <div id="conteudo"> 
       <div style="float:right"><img src="images/close.gif" style="cursor:pointer" onclick="closeMessage();"></div>
      <table align="center">
        <tr>
          <td>  
            <label style="cursor:pointer" for="rvert" id="rvert" onclick="gerar('PORTRAIT');" onmouseover="document.getElementById('vert').style.display = 'block';document.getElementById('hori').style.display = 'none';" value="retrato" >Retrato</label>
            <p>
            <label style="cursor:pointer" for="rhori" id="rhori" onclick="gerar('LANDSCAPE');" onmouseover="document.getElementById('hori').style.display = 'block';document.getElementById('vert').style.display = 'none';" value="paisagem" >Paisagem</label>
          </td>  
          <td>
            <div style="padding-left:60px">
              <img id="vert" src="images/vertical.gif" alt="Vertical" />
    
              <img style="display:none" id="hori" src="images/horizontal.gif" alt="Horizontal"/>
            <div>
          </td>
        </tr>              
      </table>
      </div>
      <div id="carregando" style="display:none">
         <center>
             <img src="images/load.gif" alt="Carregando">
             <br />
             <b> Carregando... </b>
         </center>
      </div> 
  </form>  
</body>
</html>
<script>  
    function gerar(orientacao){
        
        document.getElementById('conteudo').style.display     = 'none';
        document.getElementById('carregando').style.display   = '';
                       
       
        if (document.temp.opcao.value=='W') {
          window.location.href =  $("#word").val()+"&orientacao=" + orientacao;
        } else {
          window.open( $("#pdf").val()+"&orientacao=" + orientacao ,'pdf','resizable=yes,width=700,height=500');
        }
        closeMessage();
    }        
</script>