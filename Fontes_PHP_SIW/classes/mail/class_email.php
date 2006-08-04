<?php
    class email {
        private $_headers;
        private $_assunto;
        private $_destino;
        private $_mensagem;
        private $_filename;
        private $_filedata;
        private $_filemime;
        private $_DEBUG;

        function _construct() {
            $this->_header = Array();
            $this->_assunto = NULL;
            $this->_destino = NULL;
            $this->_mensagem = NULL;
            $this->_filename = Array();
            $this->_filedata = Array();
            $this->_filemime = Array();
            $this->_DEBUG = FALSE;
        }

        public function setHeader($_param,$_vlr) {
            $this->_headers[$_param] = $_vlr;
        }

        public function setHeaders($_headers) {
            if(is_array($_headers)) {
                foreach($_headers as $_param=>$_vlr) {
                    $this->setHeader($_param,$_vlr);
                    return TRUE;
                }
            } else {
                return FALSE;
            }
        }

        public function setDestino($_dst) {
            $this->_destino = $_dst;
        }

        public function getDestino() {
            return $this->_destino;
        }

        public function setAssunto($_ref) {
            $this->_assunto = $_ref;
        }

        public function setMensagem($_msg) {
            $this->_mensagem = $_msg;
        }

        public function setArquivo($_filename) {
            if(!file_exists($_filename)) {
                return FALSE;
            } else {
                $_fd = file_get_contents($_filename);
                if($_fd===FALSE) {
                    return FALSE;
                } else {
                    $this->_filename[] = basename($_filename);
                    $this->_filedata[] = $_fd;
                    $this->_filemime[] = 'application/octet-stream';//mime_content_type($_filename);
                    return TRUE;
                }
            }
        }
        public function setContentType($_arq,$_ct) {
            $this->_filemime[$_arq] = $_ct;
        }


        public function setDEBUG($_flg) {
            $this->_DEBUG = (bool) $_flg;
        }

        private function geraBound() {
            $_bound = "__WSPHP__";
            $_bound.= md5(uniqid(mt_rand(), TRUE));
            return $_bound;
        }

        public function enviar() {

             if($this->_destino==NULL||$this->_mensagem==NULL||$this->_assunto==NULL) {
                return FALSE;
            } else {
                $_atach = sizeof($this->_filename)&&sizeof($this->_filedata);
                if($_atach) {
                    $_ct = $this->_headers["Content-Type"];
                    unset($this->_headers["Content-Type"]);
                    $_cte = '7BIT'; //$this->_headers["Content-Transfer-Encoding"];
                    unset($this->_headers["Content-Transfer-Encoding"]);
                }
                $_MHeader='';
                $_MBody='';
                foreach($this->_headers as $_h=>$_vlr) {
                    $_MHeader .= "{$_h}: {$_vlr};\n";
                }
                if($_atach) {
                    $_MHeader .= "MIME-Version: 1.0;\n";
                    // Corpo da Mensagem...
                    $_bound = $this->geraBound();
                    $_MHeader     .= "Content-Type: multipart/mixed;\t";
                    $_MHeader     .= "boundary=\"{$_bound}\"\n";
                    $_MHeader     .= "Content-Transfer-Encoding: {$_cte}\n\n";
                    foreach($this->_filename as $_k=>$_fn) {
                        $_MHeader    .= "X-attachments: {$_fn}\n"; 
                    }
                    $_MBody       .= "--{$_bound}\n";
                    $_MBody       .= "Content-Type: {$_ct};\n";
                }
                $_MBody      .= $this->_mensagem . "\n";
                if($_atach) {
                    foreach($this->_filename as $_k=>$_fn) {
                        $_MBody    .= "\n--{$_bound}\n";
                        $_MBody    .= "Content-Type: {$this->_filemime[$_k]}; name=\"{$_fn}\"\n";
                        $_MBody    .= "Content-Transfer-Encoding: base64\n";
                        $_MBody    .= "Content-Disposition: attachment; filename=\"{$_fn}\"\n\n";
                        $_MBody    .=  chunk_split(base64_encode($this->_filedata[$_k])) . "\n";
                    }
                    $_MBody    .= "\n--{$_bound}--\n";
                }
                if($this->_DEBUG) {
                    echo "--DEBUG <br/>";
                    echo "Destino: {$this->_destino}<br/>";
                    echo "Assunto: {$this->_assunto}<br/>";
                    echo "Headers: <br/>{$_MHeader} <br/>";
                    echo "Body: <br/>{$_MBody}<br/>";
                    echo "DEBUG--<br/>";
                }
               $result = (@mail($this->_destino,$this->_assunto,$_MBody,$_MHeader) ? '' : "Erro: ".$php_errormsg);
               return $result;
            }
        }
    }
?>