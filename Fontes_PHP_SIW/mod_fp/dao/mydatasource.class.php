<?php

class MyDataSource {
        var $Con;
        var $GetCon;
        var $DirCon;
        var $QueryLista;
        var $OracleQueryLista;
        var $TotalLista;
        var $TotalRegistros;
        var $Lista;
        var $ExeQuery;
        var $ExecQueryOracle;
        var $TipoCon = 3;     //1 = SQL SERVER, 2 = MYSQL, 3 = PSGREE, 4 = ORACLE                           
        var $Server = "127.0.0.1"; 
        var $Database ="siw_db";         
        var $UserDb = "folhapg"; 
        var $PassWDDb = "folhapg"; 
        var $Erro_db = "Erro ao se conectar ao Banco de Dados.!!";
        var $CloseDatabase;
        var $DirecHome = "/path/pro/diretorio/do/oracle";
        
        /*# - Construtor da classe - #*/
        function MyDataSource() {
                        $this->ConexaoODBC();
        }
        
        /*# - Metodo que conecta oo banco de dados e é chamado pelo Construtor da classe - #*/
        function ConexaoODBC(){
                switch($this->TipoCon){ 
                        //MSSQL (MICROSOFT SQL SERVER)
                        case 1:
                        $this->Con = mssql_connect($this->Server,$this->UserDb,$this->PassWDDb) or die($this->Erro_db);
                        $this->GetCon = mssql_select_db($this->Database,$this->Con);
                        break;
                        //MYSQL
                        case 2:
                        $this->Con = mysql_connect($this->Server,$this->UserDb,$this->PassWDDb) or die($this->Erro_db);
                        $this->GetCon = mysql_select_db($this->Database,$this->Con);
                        break;
                        //POSTGRESQL
                        case 3:         
                        $this->Con = pg_connect("dbname=$this->Database port=5432 host=$this->Server user=$this->UserDb password=$this->PassWDDb") 
                                         or die($this->Erro_db);
                        pg_query($this->Con, "set client_encoding to 'LATIN1'"); 
                        break;           
                        //ORACLE 8I,9I,10G
                        case 4:
                        $this->Con = @ocilogon($this->UserDb, $this->PassWDDb, $this->Server) 
                                         or die($this->Erro_db);
                        $this->DirCon = putenv("ORACLE_HOME=".$this->DirecHome."");
                        $this->GetCon = putenv("ORACLE_SID=".$this->Database."");
                        break; 
                }                
                
        }
        
        /*# - Executa query de banco - #*/
        function ExecDatabase($StringSQL){
                switch($this->TipoCon){
                        case 1:
                                if($this->ExeQuery = mssql_query($StringSQL, $this->Con)){
                                        return true;
                                }else{
                                        return false;
                                }
                        break; 
                        case 2:
                                if($this->ExeQuery = mysql_query($StringSQL, $this->Con)){
                                        return true;
                                }else{
                                        return false;
                                }
                        break;
                        case 3:
                                if($this->ExeQuery = pg_query($this->Con, $StringSQL)){
                                        return true;
                                }else{
                                        return false;
                                }
                        break;
                        case 4:
                                $this->ExecQueryOracle = ociparse($this->Con, $StringSQL);
                        $this->ExeQuery = ociexecute($this->ExecQueryOracle);
                        if($this->ExeQuery){
                                        return true;
                                }else{
                                        return false;
                                }
                        break;
                        
                }
        }
        
        /*# - Monta o objeto completo dos resultados da query - #*/
        function ViewDatabase(){
                switch($this->TipoCon){
                 case 1:
                        if($this->Lista = mssql_fetch_array($this->ExeQuery)){
                                        return TRUE;
                                }else{
                                        return FALSE;
                                }
                 break;
                 case 2:
                        if( $this->Lista = mysql_fetch_array($this->ExeQuery) ){
                                        return TRUE;
                                }else{
                                        return FALSE;
                                }
                 break;
                 case 3:
                        if($this->Lista = pg_fetch_array($this->ExeQuery)){
                                        return TRUE;
                                }else{
                                        return FALSE;
                                }
                 break;
                 case 4:
                        if($this->Lista = ocifetch($this->ExeQuery )){
                                        return TRUE;
                                }else{
                                        return FALSE;
                                }
                 break;
                }
        }
        
        /*# - Retorna o total de linhas afetadas - #*/
        function TotalNumRegistros(){
                switch($this->TipoCon){
                 case 1:
                        $this->TotalRegistros = mssql_num_rows($this->ExeQuery);
                 break;
                 case 2:
                         $this->TotalRegistros = mysql_num_rows($this->ExeQuery);
                 break;
                 case 3:
                         $this->TotalRegistros = pg_num_rows($this->ExeQuery);
                 break;
                 case 4:
                        $this->TotalRegistros = ocinumcols($this->ExeQuery);
                 break;
                }
                return $this->TotalRegistros; 
        }
        
        /*# - Fecha conexão com o banco de dados - #*/
        function CloseODBC(){
                switch($this->TipoCon){
                        case 1:
                                $this->CloseDatabase = mssql_close($this->Con);
                        break;
                        case 2:
                                $this->CloseDatabase = mysql_close($this->Con);
                        break;
                        case 3:
                                $this->CloseDatabase = pg_close($this->Con);
                        break;
                        case 4:
                                $this->CloseDatabase = ocilogoff($this->Con);
                        break;
                }
        }
        
        /*# - Destrutor da classe - #*/
        function __destruct(){
                @CloseODBC();
        }
        
}
?>