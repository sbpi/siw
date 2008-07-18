<?php 
include_once("../constants.inc");
$p_tipo = strtoupper($_REQUEST['p_tipo']);
?>
<html>
<body>
    <form name="frm">
       <div id="conteudo"> 
       <div style="float:right"><img src="images/close.gif" style="cursor:pointer" onclick="closeMessage();"></div>
    	<table align="center">
    		<tr>
    			<td>	
    				<label for="rvert"><input checked="checked" id="rvert" type="radio" value="retrato" name="tipo" onclick="document.getElementById('vert').style.display = 'block';document.getElementById('hori').style.display = 'none';">Retrato</label>
    				<p>
    				<label for="rhori"><input id="rhori" type="radio" value="paisagem" name="tipo" onclick="document.getElementById('hori').style.display = 'block';document.getElementById('vert').style.display = 'none';">Paisagem</label>
    			</td>	
    			<td>
    				<div style="padding-left:60px">
    					<img id="vert" src="images/vertical.gif" alt="Vertical" />
    
    					<img style="display:none" id="hori" src="images/horizontal.gif" alt="Horizontal"/>
    				<div>
    			</td>
    		</tr>							
    		<tr>
    		  <td colspan="2" align="center">
    		  <?php
    		    if ($p_tipo=='PDF') {
    		  ?>
    		      <input class="STB" type="button" onclick="window.open( gerar() ,'<?php echo $p_tipo ?>','resizable=yes,width=700,height=500');closeMessage();" value="Gerar">
    		  <?php 
            } else {
          ?>
    		      <input class="STB" type="button" onclick="window.location=gerar(); closeMessage();" value="Gerar">
    		  <?php 
            }
          ?>
    		  
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
    function gerar(){
        
        var orientacao = "";
        
        document.getElementById('conteudo').style.display     = 'none';
        document.getElementById('carregando').style.display   = '';
                       
        if(document.getElementById('rvert').checked){            
            orientacao = "PORTRAIT";
        }else{            
            orientacao = "LANDSCAPE";
        }
        
        var parametro = "<?php echo $conRootSIW . base64_decode($_GET['parametro']);?>";
                
        
        return  parametro + "&orientacao=" + orientacao ;
        
    }
</script>
