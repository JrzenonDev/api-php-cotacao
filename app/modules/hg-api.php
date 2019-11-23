<?php

    // class, pega as informações da url

    class HG_API {
        private $key = null;
        private $error = false;

        // construtor para passar a key da conexão
        // para inicializar a classe já com a key que vem do config
        function __construct ($key = null) {
            if(!empty($key)) $this->key = $key;
        }

        // function genérica para pegar a url e retornar o json
        function request ( $endpoint = '', $params = array() ) {
            $uri = "https://api.hgbrasil.com/" . $endpoint. "?key=" . $this->key . "&format=json";

            // Verifica se é array
            // faz varredura e concatena a url com os valores do array
            if(is_array($params)) {
                foreach($params as $key => $value) {
                    // Se vazio vai para o próximo each
                    if(empty($value)) continue;
                    $uri .= $key . '=' . urlencode($value) . '&';
                }
                // remove o & que estiver a mais
                $uri = substr($uri, 0, -1);

                // lê todo o conteúdo de um arquivo para uma string
                // diretiva @ ignora erro (ex: sem conexão com a internet, timeout)
                $response = @file_get_contents($uri);

                //var_dump($response);

                $this->error = false;

                return json_decode($response, true);
            } else {
                $this->error = true;
                return false;
            }
        }

        function is_error () {
            return $this->error;
        }

        function dolar_quotation () {
            $data = $this->request('finance/quotations');

            if (!empty($data) && is_array($data['results']['currencies']['USD'])) {
                $this->error = false;
                return $data['results']['currencies']['USD'];
            } else {
                $this->error = true;
                return false;
            }
        }

    }
?>