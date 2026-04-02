# 🚀 Framework WEB - InfoTech

Este projeto utiliza **Vite** para o frontend e **Laravel** para o backend.

## 📌 Como usar

### 🖥️ Configuração do Vite
1. Instale as dependências:
   ```sh
   npm install --force
   ```
2. Inicie o servidor Vite na porta 4000:
   ```sh
   npx vite --port 4000
   ```

### 🛠️ Configuração do Laravel
1. Instale as dependências do backend:
   ```sh
   composer install
   ```
2. Gere a chave da aplicação:
   ```sh
   php artisan key:generate
   ```
3. Configure o arquivo `.env`.
4. Execute as migrações do banco de dados:
   ```sh
   php artisan migrate
   ```
5. Crie o link simbólico para o armazenamento:
   ```sh
   php artisan storage:link
   ```
6. Inicie o servidor Laravel:
   ```sh
   php artisan serve
   ```

🚀 Agora seu projeto está pronto para rodar! 🎉