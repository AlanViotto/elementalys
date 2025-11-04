# Elementalys Controle

Sistema de gestão para a Elementalys com foco em produtos artesanais como velas, aromatizantes e sachês. Desenvolvido com PHP, MySQL, Bootstrap, jQuery, Sass/SCSS, Gulp, npm e Composer seguindo o namespace `Elementalys` (PSR-4).

## Recursos

- Autenticação com login e senha (usuário padrão `admin@elementalys.com` / `admin123`).
- Cadastros de clientes, fornecedores e produtos.
- Registro de vendas com atualização automática de estoque.
- Cálculo automático de preço de venda a partir do preço de custo e markup informado.
- Alertas visuais de produtos com estoque baixo e destaque no dashboard.
- Dashboard com indicadores de produtos, clientes, fornecedores, vendas e receita acumulada.

## Tecnologias

- **PHP 8.1+** com Composer e autoload PSR-4 (`Elementalys\\`).
- **MySQL** para persistência dos dados.
- **Bootstrap 5**, **jQuery** e assets compilados via **Sass/SCSS** + **Gulp**.
- **npm** para gerenciamento das dependências de front-end.

## Instalação

1. Instale as dependências do PHP:
   ```bash
   composer install
   ```
2. Instale as dependências do Node.js e gere os assets:
   ```bash
   npm install
   npm run build
   ```
3. Crie o banco de dados MySQL e execute o script:
   ```bash
   mysql -u seu_usuario -p elementalys < database/schema.sql
   ```
4. Configure as variáveis de ambiente (opcional) ou edite `config/config.php` com os dados do banco.
5. Sirva a aplicação (por exemplo com o servidor embutido do PHP):
   ```bash
   php -S localhost:8000 -t public
   ```
6. Acesse `http://localhost:8000` e autentique-se com o usuário padrão.

## Desenvolvimento

- Execute `npm run dev` para acompanhar mudanças nos arquivos SCSS.
- Estrutura principal:
  - `public/` – ponto de entrada (`index.php`) e assets compilados.
  - `src/Elementalys/` – controladores e classes PHP.
  - `views/` – templates com Bootstrap e componentes reutilizáveis.
  - `resources/scss/` – estilos em Sass.
  - `database/` – scripts SQL.

O projeto está pronto para ser expandido com novos relatórios, permissões e integrações conforme as necessidades da Elementalys.
