# travel-manager-test -  Front-end

Este é o front-end do sistema de gerenciamento de pedidos de viagem corporativa, desenvolvido em Vue.js. Ele consome uma API RESTful construída em Laravel para realizar operações como autenticação, criação, listagem e atualização de pedidos de viagem.

## Pré-requisitos

Antes de começar, certifique-se de que você tenha os seguintes itens instalados em sua máquina:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Node.js](https://nodejs.org/) (opcional, apenas para desenvolvimento local)

## Dependência do Backend

O front-end depende do backend (API Laravel) para funcionar corretamente. Certifique-se de que o backend esteja configurado e rodando antes de iniciar o front-end.

### Como Rodar o Backend

1. **Navegue até a pasta do backend**:

   No diretório raiz do projeto, acesse a pasta `backend`:

   ```bash
   cd travel-manager-test/backend
   ```

2. **Suba os containers do backend**:

   Execute o seguinte comando para construir e subir os containers do backend:

   ```bash
   docker-compose up --build
   ```

   Isso irá:
   - Subir o container do Laravel (API).
   - Subir o container do MySQL (banco de dados).
   - Subir o container do Nginx (servidor web).

3. **Verifique se o backend está rodando**:

   Acesse a API no navegador ou usando uma ferramenta como o Postman:

   ```
   http://localhost:8000/
   ```

   Se tudo estiver funcionando corretamente, você verá uma pagina do swagger.

4. **Mantenha o backend rodando**:

   O front-end depende do backend para todas as operações, então não feche o terminal onde o backend está rodando.

---

## Estrutura do Projeto Front-end

O projeto front-end está organizado da seguinte forma:

```
frontend/
  public/            # Arquivos estáticos (HTML, imagens, etc.)
  src/               # Código-fonte do Vue.js
    assets/          # Recursos estáticos (CSS, imagens, etc.)
    components/      # Componentes Vue.js reutilizáveis
    views/           # Páginas da aplicação
    router/          # Configuração de rotas
    store/           # Gerenciamento de estado com Vuex
    services/        # Serviços para consumir a API
    App.vue          # Componente principal
    main.js          # Ponto de entrada da aplicação
  .env               # Variáveis de ambiente para desenvolvimento
  .env.production    # Variáveis de ambiente para produção
  Dockerfile         # Configuração do Docker para o front-end
  docker-compose.yml # Configuração do Docker Compose
  package.json       # Dependências e scripts do projeto
  vue.config.js      # Configuração do Vue CLI
```

## Como Rodar o Projeto Front-end

### Usando Docker (Recomendado)

1. **Navegue até a pasta do front-end**:

   No diretório raiz do projeto, acesse a pasta `frontend`:

   ```bash
   cd travel-manager-test/frontend
   ```

2. **Suba os containers do front-end**:

   Execute o seguinte comando para construir e subir o container do front-end:

   ```bash
   docker-compose up --build
   ```

   Isso irá:
   - Construir a imagem Docker do front-end.
   - Subir o container do front-end.
   - Conectar o front-end à rede `laravel_network` (usada pelo backend).

3. **Acesse a aplicação**:

   Abra o navegador e acesse:

   ```
   http://localhost:8080
   ```

   Você verá a interface do sistema de gerenciamento de pedidos de viagem.

4. **Parar os containers**:

   Para parar os containers, execute:

   ```bash
   docker-compose down
   ```

### Rodando Localmente (Sem Docker)

Se preferir rodar o projeto localmente sem Docker, siga os passos abaixo:

1. **Instale as dependências**:

   No diretório `frontend`, execute:

   ```bash
   npm install
   ```

2. **Configure as variáveis de ambiente**:

   Crie um arquivo `.env` na raiz do projeto com o seguinte conteúdo:

   ```env
   VUE_APP_API_URL=http://localhost:8000/api
   ```

   Substitua a URL pela URL da sua API Laravel.

3. **Inicie o servidor de desenvolvimento**:

   Execute o seguinte comando para rodar o projeto em modo de desenvolvimento:

   ```bash
   npm run serve
   ```

4. **Acesse a aplicação**:

   Abra o navegador e acesse:

   ```
   http://localhost:8080
   ```

## Variáveis de Ambiente

O projeto utiliza variáveis de ambiente para configurações sensíveis e específicas do ambiente. Aqui estão as principais:

- **`VUE_APP_API_URL`**: URL base da API Laravel. Exemplo: `http://localhost:8000/api`.

---

### Resumo das Etapas para Rodar o Projeto Completo

1. **Suba o backend**:
   - Navegue até `backend/` e execute `docker-compose up --build`.
   - Verifique se a API está acessível em `http://localhost:8000/api`.

2. **Suba o front-end**:
   - Navegue até `frontend/` e execute `docker-compose up --build`.
   - Acesse a aplicação em `http://localhost:8080`.

3. **Use o sistema**:
   - Faça login, crie pedidos de viagem, atualize status e explore todas as funcionalidades.
