<?php

    namespace AuthenticationGoogle\Library;

    use Google\Client;
    use GuzzleHttp\Client as GuzzleClient;
    use Google\Service\Oauth2 as ServiceOauth2;
    use Google\Service\Oauth2\Userinfo;

    class GoogleClient {

        public Client $client;
        
        public userinfo $data;

        public function __construct() {

            $this->client = new Client();
            
        }

        public function init() {

            $path_cert = getenv("GOOGLE_CA_CERT_PATH");

            if ($path_cert && file_exists($path_cert)) {

                $verify = $path_cert;

            } else {

                $verify = true;

            }

            $guzzleClient = new GuzzleClient([

                "verify" => $verify

            ]);

            $this->client->setHttpClient($guzzleClient);

            $this->client->setAuthConfig(json_decode(getenv("GOOGLE_CREDENTIALS_JSON"), true));

            $this->client->setRedirectUri(getenv("GOOGLE_REDIRECT_URI"));

            $scopesJson = getenv("GOOGLE_SCOPES");

            $scopesArray = json_decode($scopesJson, true);

            foreach ($scopesArray as $scope) {

                $this->client->addScope($scope);

            }

        }

        public function authorized() {

            if(isset($_GET["code"])) {

                $this->init();

                $token = $this->client->fetchAccessTokenWithAuthCode($_GET["code"]);

                $this->client->setAccessToken($token["access_token"]);

                $googleService = new ServiceOauth2($this->client);

                $this->data = $googleService->userinfo->get();

                return [

                    "status" => true,
                    "data" => $this->data
                ];

            }

            return [

                "status" => false,
                "data" => ""

            ];

        }

        public function createAuthUrl() {

            $link = $this->client->createAuthUrl();

            return $link;

        }
        
    }

?>
