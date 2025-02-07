
# Teste Técnico Onfly

Microserviço de pedido de viagens com operações de cadastro, atualização, visualização e listagem.

**Conteúdo**

- [Dependências globais](#dependências-globais)
- [Instalação do Projeto](#instalação-do-projeto)
- [Geração de token para autenticação](#geração-de-token-para-autenticação)
    - [Criar um novo usuário](#criar-um-novo-usuário)
    - [Obter token de usuário cadastrado](#obter-token-de-usuário-cadastrado)
- [Execução dos Testes](#execução-dos-testes)

### Dependências globais

Você precisa ter três principais dependências instaladas:

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/)
- [Git](https://git-scm.com/)

## Instalação do Projeto

Para instalação do projeto é necessário seguir as seguintes orientações:

1. Clonar o projeto: https://github.com/feehm27/teste_onfly.git
2. Navegar até a pasta do projeto: ``` cd teste_onfly ```
3. Rodar os comandos do docker: ```docker-compose build && docker-compose up -d```
4. Criar o arquivo .env ``` cp .env.example .env ```
5. Acessar o container e instalar as dependências do projeto: ``` composer install ```
6. Executar as migrations: ``` php artisan migrate ```
7. Executar as seeders: ``` php artisan db:seed ```
8. Navegar até o http://localhost para acessar a documentação da API

### Geração de token para autenticação

No ambiente de desenvolvimento você poderá criar usuários manualmente para conseguir autenticar na rotas que tem proteção.

#### Criar um novo usuário

1. Após subir os serviços, acesse o localhost (http://localhost)
2. Acesse o endpoint http://localhost/api/v1/register
3. Preencha os dados e utilize **qualquer email** com formato válido, mesmo que este email não exista, por exemplo: `teste@teste.com`
4. O backend irá retornar os dados do usuário juntamente com o campo `token`.
5. Copie o campo token e utilize nas apis que tem proteção de autenticação no formato **Bearer** `token`.

#### Obter token de usuário cadastrado
1. Após subir os serviços, acesse o localhost(http://localhost)
2. Acesse o endpoint http://localhost/api/v1/login
3. Preencha os dados de um usuário que foi cadastrado posteriormente.
4. O backend irá retornar os dados do usuário juntamente com o campo `token`.
5. Copie o campo token e utilize nas apis que tem proteção de autenticação no formato **Bearer** `token`.

### Execução dos testes

Para execução dos testes é necessário seguir as seguintes orientações:

1. Configurar um arquivo .env.testing, 
2. Executar as migrations:``` php artisan migrate ```
3. Executar as seeders:``` php artisan db:seed ```
4. Executar o comando: ``` php artisan test ```

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
