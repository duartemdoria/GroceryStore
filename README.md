# Mercearia Online

Este repositório contém o código-fonte de uma aplicação web para uma mercearia online. A aplicação permite que os clientes vejam produtos, os adicionem a um carrinho e finalizem a compra. Inclui também um painel de administração para o dono da loja gerir produtos e consultar encomendas.

## ✨ Funcionalidades Principais

### 🛍️ Área do Cliente
- **Visualização de Produtos:** Página inicial com uma grelha de todos os produtos disponíveis em stock.
- **Carrinho de Compras Dinâmico:** Adicione, remova e veja produtos no carrinho, com o total a ser atualizado em tempo real (via sessão PHP).
- **Checkout Seguro:** Formulário de finalização de compra com validações de dados (idade mínima de 18 anos, campos obrigatórios) e proteção contra XSS.
- **Atualização de Stock:** O stock dos produtos é atualizado automaticamente após cada compra bem-sucedida.

### ⚙️ Painel de Administração
- **Login Seguro:** Página de login exclusiva para o administrador com verificação de palavra-passe através de hash.
- **Dashboard Central:** Painel de controlo com um resumo das últimas encomendas e uma lista de todos os produtos.
- **Gestão de Produtos Completa:** Adicione novos produtos e edite os existentes (nome, descrição, preço, stock e imagem).
- **Gestão de Encomendas:** Visualize uma lista de todas as encomendas e consulte os detalhes de cada uma (dados do cliente e produtos comprados).

## 🛠️ Tecnologias Utilizadas
* **Back-end:** PHP
* **Base de Dados:** MySQL
* **Front-end:** HTML5, CSS3, Bootstrap 5
* **Servidor Web:** Apache (normalmente via XAMPP ou MAMP)

---

## 🚀 Como Executar o Projeto

Siga estes passos para configurar o ambiente de desenvolvimento local.

### Pré-requisitos
* Um ambiente de servidor local como [XAMPP](https://www.apachefriends.org/index.html) ou MAMP.
* Um gestor de base de dados como o phpMyAdmin (incluído no XAMPP).

### 1. Configurar a Base de Dados
-   Inicie os serviços Apache e MySQL no seu painel de controlo XAMPP/MAMP.
-   Abra o phpMyAdmin e crie uma nova base de dados chamada `mercearia_db`.
-   Importe o ficheiro `ecom.sql` (que se encontra na raiz do projeto) para dentro da base de dados `mercearia_db`.

### 2. Configurar o Projeto
-   Clone ou faça o download deste repositório para a pasta `htdocs` (no XAMPP) ou `htdocs` (no MAMP).
-   Abra o ficheiro `/config/database.php`.
-   Atualize as credenciais da base de dados de acordo com a sua configuração local (normalmente, o utilizador é `root` e a palavra-passe é vazia por defeito).
```php
$db_host = 'localhost';
$db_name = 'mercearia_db';
$db_user = 'root'; // O seu utilizador
$db_pass = '';     // A sua palavra-passe
```

### 3. Aceder à Aplicação
-   **Loja (Página Pública):** Abra o seu navegador e aceda a `http://localhost/mercearia-online/public/`.
-   **Painel de Administração:** Aceda a `http://localhost/mercearia-online/admin/`.
    -   **Utilizador:** `admin`
    -   **Palavra-passe:** `admin123`

---

## 📁 Estrutura de Pastas

O projeto está organizado de forma modular para separar as responsabilidades:

```
/
|-- /admin/             # Ficheiros do painel de administração
|-- /assets/            # Imagens, CSS, JS estáticos
|-- /config/            # Ficheiros de configuração (ex: base de dados)
|-- /public/            # Área pública do site, acessível aos clientes
|-- /templates/         # Partes reutilizáveis de HTML (cabeçalho, rodapé)
|-- ecom.sql            # Ficheiro de setup da base de dados
|-- README.md           # Este ficheiro
