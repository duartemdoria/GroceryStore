Documentação Técnica - Projeto Mercearia Online
1. Introdução
Este documento detalha a estrutura técnica e o funcionamento do projeto "Mercearia Online", uma aplicação web para a venda de produtos de mercearia. A aplicação foi desenvolvida utilizando PHP, MySQL e Bootstrap, e está dividida em duas áreas principais: uma área pública para os clientes e um painel de administração para o dono da loja.
O objetivo deste documento é servir como um guia completo para programadores que necessitem de dar manutenção, expandir ou entender a lógica da aplicação.
2. Estrutura de Pastas
O projeto está organizado de forma modular para separar as responsabilidades e facilitar a navegação.
/mercearia-online/
|
|-- /admin/             # Contém todos os ficheiros do painel de administração
|   |-- /includes/
|   |   |-- auth_check.php  # Script que verifica se o admin tem sessão iniciada
|   |-- /templates/
|   |   |-- header.php      # Cabeçalho HTML da área de administração
|   |   |-- footer.php      # Rodapé HTML da área de administração
|   |-- dashboard.php       # Página principal do painel de controlo
|   |-- encomenda_detalhe.php # Vê os detalhes de uma encomenda
|   |-- encomendas.php      # Lista todas as encomendas
|   |-- index.php           # Página de login do administrador
|   |-- logout.php          # Script para terminar a sessão do admin
|   |-- produtos_gerir.php  # Formulário para adicionar/editar produtos
|
|-- /assets/            # Contém todos os recursos estáticos
|   |-- /images/          # Imagens dos produtos
|
|-- /config/            # Ficheiros de configuração
|   |-- database.php      # Configuração e ligação à base de dados (PDO)
|
|-- /public/            # Área pública do site, acessível aos clientes
|   |-- carrinho.php      # Gere o carrinho de compras (adicionar, remover, ver)
|   |-- checkout.php      # Formulário para finalizar a compra
|   |-- index.php         # Página inicial que lista os produtos
|   |-- style.css         # Folha de estilos personalizada (se necessário)
|   |-- sucesso.php       # Página mostrada após uma compra bem-sucedida
|
|-- /templates/         # Templates HTML da área pública
    |-- header.php        # Cabeçalho HTML da loja
    |-- footer.php        # Rodapé HTML da loja


3. Base de Dados (mercearia_db)
A base de dados foi desenhada para ser relacional e normalizada, garantindo a integridade dos dados.
produtos: Armazena todos os detalhes dos produtos.
id (PK), nome, descricao, imagem, quantidade (stock), preco.
utilizadores: Guarda as credenciais do administrador.
id (PK), nome_utilizador, palavra_passe (armazenada com hash).
encomendas: Contém a informação principal de cada encomenda.
id (PK), nome_cliente, data_nascimento, morada, preco_total, data_encomenda.
detalhes_encomenda: Tabela de ligação que associa produtos a encomendas.
id (PK), id_encomenda (FK para encomendas), id_produto (FK para produtos), quantidade, preco_unitario (preço no momento da compra).
4. Funcionalidades Principais
Área Pública (/public)
Listagem de Produtos (index.php)
Liga-se à base de dados através do config/database.php.
Executa uma query SELECT na tabela produtos para ir buscar todos os produtos com quantidade > 0.
Renderiza cada produto num card do Bootstrap, mostrando imagem, nome, preço e stock.
Cada produto tem um formulário que faz POST para carrinho.php para adicionar itens.
Carrinho de Compras (carrinho.php)
Utiliza a $_SESSION do PHP para persistir os dados do carrinho.
Se acedido via POST, processa a adição de um novo item, verificando o stock disponível antes de o adicionar à sessão.
Se acedido via GET, mostra uma tabela com os produtos no carrinho, calcula o subtotal e o preço total.
Finalizar Compra (checkout.php)
Apresenta um formulário para o cliente inserir nome, data de nascimento e morada.
Validações:
Verifica se todos os campos estão preenchidos.
Calcula a idade do cliente para garantir que é maior de 18 anos.
Usa filter_input com FILTER_SANITIZE_STRING para proteger contra injeções de JavaScript/HTML.
Processamento da Encomenda:
Usa uma transação da base de dados (beginTransaction, commit, rollBack) para garantir que a encomenda só é criada se todas as operações forem bem-sucedidas.
Verifica o stock de cada produto uma última vez.
Insere os dados na tabela encomendas.
Insere cada produto na tabela detalhes_encomenda.
Atualiza (subtrai) a quantidade na tabela produtos.
Após o sucesso, limpa o carrinho ($_SESSION['carrinho']) e redireciona para sucesso.php.
Painel de Administração (/admin)
Autenticação (index.php, auth_check.php)
A página de login (index.php) recolhe as credenciais.
Compara a palavra-passe fornecida com o hash guardado na base de dados usando password_verify(), o que impede ataques de timing.
Se o login for bem-sucedido, guarda o id do admin na $_SESSION['admin_id'].
Todas as outras páginas do admin incluem o auth_check.php, que verifica se $_SESSION['admin_id'] existe. Caso contrário, redireciona o utilizador para a página de login.
Gestão de Produtos (produtos_gerir.php)
É uma página única para criar e editar produtos.
Se um id é passado via GET, a página entra em modo de edição e preenche o formulário com os dados do produto.
Caso contrário, entra em modo de criação.
Gere o upload de imagens, validando o tipo de ficheiro e movendo-o para a pasta /assets/images/.
Visualização de Encomendas (encomendas.php, encomenda_detalhe.php)
encomendas.php lista todas as encomendas feitas.
encomenda_detalhe.php mostra todos os dados de uma encomenda específica, incluindo os dados do cliente e a lista de produtos comprados, fazendo um JOIN entre as tabelas.
5. Como Configurar o Ambiente
Base de Dados: Importe o ficheiro ecom.sql (disponibilizado no início do projeto) para o seu gestor de base de dados (ex: phpMyAdmin).
Ligação: Abra o ficheiro config/database.php e atualize as variáveis $db_host, $db_name, $db_user, e $db_pass com as suas credenciais locais.
Servidor Web: Coloque a pasta do projeto na diretoria do seu servidor web (ex: htdocs no XAMPP) e aceda a http://localhost/mercearia-online/public/ no seu navegador.
