# Teste Técnico Onfly

Este projeto foi criado utilizando o [Laravel Sail](https://laravel.com/docs/11.x/sail) e o  [Laravel 11](https://laravel.com/docs/11.x) para implementação de um microserviço de Pedido de Viagens.
Segue abaixo as instruções de instalação.

## Requisitos

Antes de começar, você precisa ter o seguinte instalado:

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/)
- [Git](https://git-scm.com/)

## Instalando o Projeto

Siga os passos abaixo para instalar o projeto:

1. **Clone o repositório:**

    ```bash
    git clone https://github.com/feehm27/teste_onfly.git
    cd teste_onfly
    ```

2. **Instale as dependências do Laravel:**

   Instale as dependências do Laravel Sail com o seguinte comando:

    ```bash
    ./vendor/bin/sail up -d
    ```

   Isso irá inicializar o ambiente Docker.

4. **Configure o ambiente:**

   Copie o arquivo `.env.example` para um novo arquivo `.env`:

    ```bash
    cp .env.example .env
    ```

   Em seguida, gere a chave da aplicação:

    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

   Abra o arquivo `.env` e adicione ou atualize as seguintes variáveis para configurar a conexão com o banco de dados:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=sail
    DB_PASSWORD=password
    ```

   Essas configurações são específicas para o ambiente Laravel Sail, onde o banco de dados MySQL está rodando dentro de um contêiner Docker. Caso o nome do banco de dados ou as credenciais sejam diferentes, ajuste as configurações conforme necessário.

5. **Prepare o banco de dados executando as migrações e os seeders:**

   Para rodar tanto as migrações quanto os seeders ao mesmo tempo, use o seguinte comando:

    ```bash
    ./vendor/bin/sail artisan migrate --seed
    ```

6. **Acesse o aplicativo:**

   Após seguir os passos acima, você pode acessar a aplicação no navegador em:

    ```
    http://localhost
    ```

   Você será redirecionado automaticamente para a página da documentação.

## Executando os Testes

1. **Executar os testes unitários:**

   Laravel Sail já configura o PHPUnit para você. Para rodar os testes, execute o seguinte comando:

    ```bash
    ./vendor/bin/sail test
    ```

   Isso irá rodar todos os testes definidos na pasta `tests/`.

2. **Executar testes de integração específicos:**

   Você pode especificar o nome de um arquivo de teste específico para rodá-lo. Por exemplo:

    ```bash
    ./vendor/bin/sail test --filter=OrderTravelControllerSuccessTest
    ```

3. **Ver o resultado dos testes:**

   Após a execução dos testes, o PHPUnit irá exibir o resultado diretamente no terminal.

## Autenticação

Este projeto inclui funcionalidades de autenticação, permitindo o registro e o login de usuários através de endpoints de API. Para testar e utilizar esses endpoints, siga as instruções abaixo.

### Endpoints de Autenticação

1. **Registrar um novo usuário (Register)**

   O endpoint para registrar um novo usuário é `POST /api/v1/register`. Você deve enviar os dados do usuário no corpo da requisição conforme documentação.
   Se o registro for bem-sucedido, você receberá uma resposta com os dados do usuário e um token de autenticação.
   Esse token pode ser usado para autenticar requisições em endpoints protegidos.

   **Resposta Esperada:**

    ```json
    {
        "user": {
            "id": 1,
            "name": "Nome do Usuário",
            "email": "usuario@exemplo.com"
        },
        "token": "token_de_autenticacao_aqui"
    }
    ```


3. **Login de um usuário (Login)**

   O endpoint para realizar o login de um usuário é `POST /api/v1/login`. Você deve enviar o e-mail e a senha do usuário no corpo da requisição.
   Esse token será necessário para autenticar suas requisições em endpoints que requerem autenticação.

   **Resposta Esperada:**

   Se o login for bem-sucedido, você receberá um token de autenticação.

    ```json
    {
        "token": "token_de_autenticacao_aqui"
    }
    ```

## Parando o Ambiente Docker

Para parar o ambiente Docker, execute o comando:

```bash
./vendor/bin/sail down
