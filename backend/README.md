# travel-manager-test - Backend 

Para rodar o projeto backend usando as configurações do Docker, siga os passos abaixo:

1. **Certifique-se de ter o Docker e o Docker Compose instalados em sua máquina.**

2. Criar a rede laravel_network

Necessário criar a rede antes de rodar os containers:

    ```sh
    docker network create laravel_network
    ```

3. **Clone o repositório do projeto e navegue até o diretório do backend:**
    ```sh
    git clone https://github.com/eduvyres/travel-manager-test.git
    cd backend
    ```

4. **Crie um arquivo `.env` a partir do exemplo fornecido:**
    ```sh
    cp src/.env.example src/.env
    ```

5. **Atualize as variáveis de ambiente no arquivo `.env` conforme necessário.**

6. **Construa e inicie os containers Docker:**
    ```sh
    docker-compose up --build
    ```

7. **Acesse o aplicativo no navegador:**
    Abra o navegador e vá para `http://localhost:8000`.

Esses passos irão configurar e iniciar o ambiente de desenvolvimento do backend usando Docker, incluindo o servidor PHP, o servidor Nginx e o banco de dados MySQL.

Para mais detalhes, consulte o arquivo `docker-compose.yml` e o `Dockerfile`.
