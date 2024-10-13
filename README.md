
# 🎉 Sistema de Gerenciamento de Códigos Promocionais 🎉

Bem-vindo ao **Sistema de Gerenciamento de Códigos Promocionais**! Este sistema foi desenvolvido utilizando **Laravel 10** como backend para gerenciar diferentes tipos de usuários, como **administradores**, **patrocinadores** e **competidores**, com funcionalidades de geração e resgate de códigos promocionais.

## 🚀 Funcionalidades Principais

- 👑 **Admin** pode:
    - Gerar múltiplos códigos promocionais.
    - Listar todos os competidores.
    - Gerenciar patrocinadores e competidores.

- 🎯 **Competidor** pode:
    - Resgatar códigos promocionais.
    - Verificar sua pontuação total.

- 🎟️ **Códigos Promocionais**:
    - Cada código pode ter pontuação associada.
    - Resgates podem ser únicos ou múltiplos, com decrescimo progressivo.

## 📚 Tecnologias Utilizadas

- ⚡ **Laravel 10** — Framework PHP para o backend.
- 🔐 **Laravel Sanctum** — Autenticação e proteção de rotas via tokens.
- 🛡️ **Rate Limiting** — Prevenção de ataques de força bruta.
- 📜 **Swagger (L5 Swagger)** — Documentação automática da API.
- 🛠️ **MySQL** — Banco de dados relacional.

## 📦 Instalação

Siga os passos abaixo para instalar e configurar o projeto localmente.

1. **Clone o Repositório:**

```bash
git clone https://github.com/seuusuario/sistema-codigos-promocionais.git
cd sistema-codigos-promocionais
```

2. **Instale as Dependências:**

```bash
composer install
```

3. **Configure o .env:**

```bash
cp .env.example .env
```

4. **Gere a chave da aplicação:**

```bash
php artisan key:generate
```

5. **Configure o Banco de Dados:**
   Atualize as variáveis no arquivo `.env` para configurar o banco de dados MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

6. **Execute as Migrações:**

```bash
php artisan migrate
```

7. **Rodar o Servidor Local:**

```bash
php artisan serve
```

Acesse em `http://localhost:8000`.

## 🔐 Autenticação e Proteção

O sistema utiliza **Laravel Sanctum** para autenticação de usuários e proteção das rotas. Apenas competidores autenticados podem resgatar códigos, e somente administradores podem gerenciar usuários.

### Exemplo de Autenticação:

- Para se registrar como competidor, utilize a rota `/api/register`.
- Após login, obtenha seu token e adicione no **header** para realizar chamadas autenticadas.

## 🔧 Rodas e Endpoints Principais

### 🔐 Autenticação
- `POST /login`: Login de usuários.
- `POST /register`: Registro de competidores.

### 🎟️ Códigos Promocionais
- `POST /codigos/gerar-multiplos`: Gera múltiplos códigos promocionais.
- `POST /codigos/{codigo}/resgatar`: Resgata um código promocional (Competidor).
- `GET /competidor/pontuacao`: Exibe a pontuação total do competidor autenticado.

### 👑 Administração
- `GET /admin/competidores`: Lista todos os competidores (Apenas Admin).

## 🚀 Documentação da API

A documentação da API está disponível via **Swagger**. Para acessá-la:

- Após rodar o servidor, acesse a documentação em [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation).

## 🛡️ Segurança

- **Rate Limiting**: Implementado para evitar ataques de força bruta.
- **CAPTCHA**: Proteção para evitar ataques automatizados nas rotas sensíveis.
- **Autenticação via Token**: Rotas protegidas por token de acesso usando Laravel Sanctum.

## 🧑‍💻 Contribuições

Contribuições são bem-vindas! Por favor, siga os passos abaixo para colaborar:

1. Faça um fork do projeto.
2. Crie uma nova branch (`git checkout -b feature/nova-funcionalidade`).
3. Faça o commit das suas alterações (`git commit -m 'Adicionar nova funcionalidade'`).
4. Envie para a branch (`git push origin feature/nova-funcionalidade`).
5. Crie um **Pull Request**.

---

Feito com Odio por [GUSTAVO PANTOJA](https://github.com/seuusuario).
