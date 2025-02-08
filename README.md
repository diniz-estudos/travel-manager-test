# Travel Manager Test

Este repositório contém uma aplicação de gerenciamento de pedidos de viagem, composta por um back-end em Laravel e um front-end em Vue.js.

## Pré-requisitos

Antes de começar, certifique-se de que você tenha os seguintes itens instalados em sua máquina:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Node.js](https://nodejs.org/) (opcional, apenas para desenvolvimento local)

## Criar a rede laravel_network

Necessário criar a rede manualmente antes de rodar os containers:

```sh
docker network create laravel_network
```

## Dependência do Backend

O front-end depende do backend (API Laravel) para funcionar corretamente. Certifique-se de que o backend esteja configurado e rodando antes de iniciar o front-end.

## Estrutura do Projeto

```text
    .
    ├── backend/
    │   ├── docker-compose.yml
    │   ├── Dockerfile
    │   ├── src/
    │   │   ├── .env.example
    │   │   ├── app/
    │   │   ├── bootstrap/
    │   │   ├── config/
    │   │   ├── database/
    │   │   ├── public/
    │   │   ├── resources/
    │   │   ├── routes/
    │   │   ├── storage/
    │   │   ├── tests/
    │   │   └── ...
    │   └── ...
    ├── frontend/
    │   ├── docker-compose.yml
    │   ├── Dockerfile
    │   ├── src/
    │   │   ├── assets/
    │   │   ├── components/
    │   │   ├── views/
    │   │   ├── router/
    │   │   ├── store/
    │   │   ├── services/
    │   │   ├── App.vue
    │   │   ├── main.js
    │   │   ├── config.js
    │   │   └── ...
    │   └── ...
    └── ...
```

## Como Rodar o Projeto Backend

Clone o repositório do projeto e navegue até o diretório do backend:

```bash
git clone https://github.com/eduvyres/travel-manager-test.git
cd travel-manager-test/backend
```

### Crie um arquivo .env a partir do exemplo fornecido:

```bash
cp src/.env.example src/.env
```

**Importante** : Atualize as variáveis de ambiente no arquivo .env conforme necessário.

### Construa e inicie os containers Docker

```bash
docker-compose up --build
```

Isso irá subir:

   - Container do Laravel (API).
   - Container do MySQL (banco de dados).
   - Container do Nginx (servidor web).

### Acesse o aplicativo no navegador

Abra o navegador e vá para http://localhost:8000/docs


## Como Rodar o Projeto Front-end

### Usando Docker (Recomendado)

#### Navegue até a pasta do front-end

```bash
cd travel-manager-test/frontend
```

#### Suba os containers do front-end

Execute o seguinte comando para construir e subir o container do front-end

```bash
docker-compose up --build
```

#### Acesse a aplicação

```text
http://localhost:8080
```

#### Instale as dependências

No diretório frontend, execute:

```bash
npm install
```

#### Configure as variáveis de ambiente

Crie um arquivo .env na raiz do projeto com o seguinte conteúdo:

```
VUE_APP_API_URL=http://localhost:8000
```

#### Inicie o servidor de desenvolvimento

Execute o seguinte comando para rodar o projeto em modo de desenvolvimento

```bash
npm run dev
```

#### Acesse a aplicação

Abra o navegador e acesse:

```
http://localhost:8080
```


## Variaveis de Ambiente

O projeto utiliza variáveis de ambiente para configurações sensíveis e específicas do ambiente. Aqui estão as principais:

- VUE_APP_API_URL: URL base da API Laravel. Exemplo: http://localhost:8000.

## Rodando os Testes

### Backend

Para rodar os testes do backend, execute o seguinte comando no diretório backend:

```bash
docker-compose exec app php artisan test
```

### Front-end

Para rodar os testes do front-end, execute o seguinte comando no diretório frontend:

```bash
npm run test
```

## Informações Relevantes

- Certifique-se de que o backend esteja rodando antes de iniciar o front-end.
- O front-end e o backend devem estar conectados à mesma rede Docker (laravel_network).
- Consulte os arquivos docker-compose.yml e Dockerfile para mais detalhes sobre a configuração dos containers Docker.


## Resumo das Etapas para Rodar o Projeto Completo
1. Crie a rede laravel_network no docker:
```bash
docker network create laravel_network
```

2. Suba o backend:
Navegue até backend e execute docker-compose up --build. Verifique se a API está acessível em http://localhost:8000/docs.

3. Suba o front-end:
Navegue até frontend e execute:
```bash
docker-compose up --build
```
Acesse a aplicação em http://localhost:8080.

4. Use o Sistema

Registre-se e faça login, crie pedidos de viagem, atualize status e explore todas as funcionalidades.

