# 🚀 HUBIFPR - Incubadora

Este projeto utiliza **Blade** para o frontend e **Laravel** para o backend.

## 📌 Como usar

### 🛠️ Configuração do Laravel
1. Instale as dependências do backend:
   ```sh
   composer install
   ```
2. Inicie seu banco de dados:
   - **Windows**: Abra o XAMPP e inicie o MySQL.
   - **Linux**: Acesse no navegador:
     ```
     http://localhost/phpmyadmin
     ```
3. Renomeie o arquivo `.env.example` para `.env` e configure seus dados.
4. Gere a chave da aplicação:
   ```sh
   php artisan key:generate
   ```
5. Execute as migrações e seeders:
   ```sh
   php artisan migrate:fresh --seed
   ```
6. Crie um link simbólico para o armazenamento de arquivos PDF:
   ```sh
   php artisan storage:link
   ```
7. Inicie o servidor Laravel:
   ```sh
   php artisan serve
   ```

🚀 Agora seu projeto está pronto para rodar! 🎉
