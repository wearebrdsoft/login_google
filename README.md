# Autenticação Google PHP

Biblioteca PHP que realiza autenticação com o Google utilizando o cliente oficial [`google-api-php-client`](https://github.com/googleapis/google-api-php-client), facilitando o processo de login e integração com os serviços e APIs do Google.

---

## 🚀 Recursos

* Autenticação OAuth2 com o Google
* Suporte ao login via conta Google
* Integração simples com APIs do Google (Drive, Gmail, Calendar, etc.)
* Baseado no cliente oficial da Google para PHP

---

## 📦 Instalação

Instale via [Composer](https://getcomposer.org/):

```bash
composer require brunodev/google-auth-php
```

---

## ⚙️ Configuração

A biblioteca depende de algumas **variáveis de ambiente** para funcionar corretamente. Configure-as no seu `.env` ou no ambiente do servidor:

```dotenv
# Caminho para o certificado CA usado pelo Guzzle (opcional, fallback para padrão do sistema)
GOOGLE_CA_CERT_PATH=/etc/ssl/certs/ca-certificates.crt

# Credenciais do Google em formato JSON (geradas pelo Google Cloud Console)
GOOGLE_CREDENTIALS_JSON='{"type":"service_account","project_id":"...","private_key_id":"...","private_key":"-----BEGIN PRIVATE KEY-----..."}'

# Redirect URI configurado no Google Cloud Console
GOOGLE_REDIRECT_URI=http://localhost:8000/callback

# Scopes da autenticação, em JSON (uma linha)
GOOGLE_SCOPES='["email","profile"]'

# ID do cliente no Google.
GOOGLE_CLIENT_ID=seu_id_aqui

# A chave secreta do cliente.
GOOGLE_CLIENT_SECRET=seu_secret_aqui
```

> Dica: caso `GOOGLE_CA_CERT_PATH` não seja definido, o Guzzle usará o certificado padrão do sistema.
> Scopes podem ser ajustados conforme os serviços que você deseja acessar.

---

## 📝 Uso básico

```php
<?php

    // PRIMEIRA PARTE -> Verificamos o link de redirecionamento e autorizamos a tela de login com o Google para o usuário.

    // Importamos a Biblioteca no arquivo.
    use AuthenticationGoogle\Library\GoogleClient;

    // Criamos a instância do cliente Google.
    $googleClient = new GoogleClient();

    // Inicializamos o cliente com as variáveis de ambiente.
    $googleClient->init();

    // Setamos em uma variável o Link para gerar a autenticação. Ao enviar esse link será retornardo um código.
    $link = $googleClient->createAuthUrl();

?>

<?php

    // SEGUNDA PARTE -> Apartir do link enviado obtemos os dados do usuário que realizou o login no Google.

    // Importamos a Biblioteca no arquivo.
    use AuthenticationGoogle\Library\GoogleClient;

    // Criamos a instância do cliente Google.
    $googleClient = new GoogleClient();

    // Verificamos se o usuário já autorizou.
    $authorized = $googleClient->authorized();

    // Caso o usuário esteja autorizado.
    if($authorized["status"]) {

        // Informamos na tela os dados do usuário enviados pelo Google.
        echo "Usuário autorizado: ";
        print_r($authorized["data"]);

    // Caso o usuário não está autorizado.
    } else {

        // Retornarmos que o usuário não está autorizado.
        echo "Usuário não autorizado!";
        
    }

?>
```