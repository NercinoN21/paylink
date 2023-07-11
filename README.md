# PayLink - Api Rest


[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/lumen-framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/lumen)](https://packagist.org/packages/laravel/lumen-framework)

API REST em PHP utilizando o framework Laravel/Lumen e PHP 8.2. 

## Run

> php -S 127.0.0.1:8000 -t public

## Modelos de dados
 * agencias 
 * autenticacao_tokens
 * transacoes 
 * contas
 * codigos_transacoes
 * users
 * enderecos
 * auth_tokens 

# Rotas

## POST - /login

### request 
    {
        "email": "admin2@gmail.com",
        "password": "admin123"
    }
### response
    {
        "token": "...",
    }

> ***Observação:*** Ao efetuar o login, verifica-se se o usuário existe na base de dados a partir do email. Se tudo ocorrer bem, um token é gerado a partir do email e da data atual, sendo salvo na tabela "autenticacao_tokens".

> ***Observação:*** Todas as rotas, com exceção do login, requerem o envio do token no cabeçalho da requisição.

> ***Exemplo:*** Authorization: token...

## Transação Bancaria

Para realizar uma transação bancária de depósito ou transferência, é necessário obter um código de transação. O código pode ser formado das seguintes formas: DEP0000 ou TRANSF0000. Para solicitar o código, é necessário enviar os dados de e-mail e senha, informar o tipo de transação e o número da conta de origem. Se estiver tudo certo, ele retornará um código de transação válido. Esse código será armazenado no banco de dados com a data de expiração.

> ***Observação:*** Em todas as transações de depósito ou transferência, o código deve ser informado.

## Rotas de Transação

### POST - /transacao/SolicitarCodigo

### request 
    {
        "email": "admin@example.com",
        "password": "admin123"
        "tipoTrasacao": 1,
        "numeroContaOrigem": "2613821813"
    }
### response
    {
    "Codigo": "TRANSF8803"
    }

### POST - /transacao/deposito

### request 
    {
        "numeroContaDestino": "1111111111", 
        "valor": 50.00,
        "codigoTrasacao":"DEP3538"
    }
### response
    {
    "Sucesso": "Mensagem de sucesso"
    }

### POST - /transacao/transferencia

### request 
    {
        "numeroContaDestino": "1111111111", 
        "valor": 50.00,
        "codigoTrasacao":"TRANSF3538"
    }
### response
    {
    "Sucesso": "Mensagem de sucesso"
    }

> ***Observação:*** Lembre-se de passar o token de login para acessar as rotas.


## Demais Rotas

* **Get** /users/
* **Get** /users/{id}
* **Post** /users/
* **Put** /users/{id}
* **Delete** /users/{id}
* **Get** /endereco/
* **Post** /endereco/
* **Get** /conta/
* **Get** /agencia/
* **Get** /CodigoTransacao/

## License

O Paylink esta licenciado sob a [MIT license](https://opensource.org/licenses/MIT).
