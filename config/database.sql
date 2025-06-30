-- Base de dados: `mercearia_db`
--
CREATE DATABASE IF NOT EXISTS `mercearia_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `mercearia_db`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
-- Guarda todos os produtos disponíveis na loja.
--
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `imagem` varchar(255) DEFAULT 'default.jpg', -- Imagem do produto
  `quantidade` int(11) NOT NULL DEFAULT 0, -- Quantidade em stock
  `preco` decimal(10,2) NOT NULL, -- Preço por unidade
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Inserir dados de exemplo na tabela `produtos`
--
INSERT INTO `produtos` (`nome`, `descricao`, `imagem`, `quantidade`, `preco`) VALUES
('Maçã Fuji', 'Maçãs frescas e crocantes, ideais para um lanche saudável.', 'maca_fuji.jpg', 100, '1.99'),
('Pão de Forma', 'Pão de forma fatiado, perfeito para sanduíches e torradas.', 'pao_forma.jpg', 50, '2.49'),
('Leite Integral', 'Leite de vaca integral, fonte de cálcio e vitaminas.', 'leite.jpg', 80, '0.89'),
('Ovos (Dúzia)', 'Dúzia de ovos frescos de galinhas criadas ao ar livre.', 'ovos.jpg', 60, '2.10'),
('Arroz Agulha', 'Arroz agulha de 1kg, ideal para acompanhar os seus pratos.', 'arroz.jpg', 120, '1.15'),
('Feijão Preto', 'Pacote de 1kg de feijão preto, rico em ferro e proteínas.', 'feijao.jpg', 90, '1.79'),
('Azeite Extra Virgem', 'Garrafa de 500ml de azeite extra virgem de alta qualidade.', 'azeite.jpg', 40, '5.99');

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
-- Armazena as credenciais do administrador da loja.
--
CREATE TABLE `utilizadores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_utilizador` varchar(50) NOT NULL,
  `palavra_passe` varchar(255) NOT NULL, -- A senha será armazenada de forma segura (hash)
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_utilizador` (`nome_utilizador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Inserir dados do administrador
-- NOTA: A palavra-passe 'admin123' está aqui como exemplo.
-- No código PHP, vamos usar password_hash() para a guardar de forma segura.
-- O hash para 'admin123' é: '$2y$10$E/g5V.y0jC4Qx2jJ.fJ8iO0.I.3g.U/D5f1i.o8c.z8k.z8k.z8k.'
--
INSERT INTO `utilizadores` (`nome_utilizador`, `palavra_passe`) VALUES
('admin', '$2y$10$9.QnLgGubGNAvFkI8dGzT.crqYJ2iA4L6h/y0aC8f.K3E.t7uG5aK'); -- A senha é 'admin123'

-- --------------------------------------------------------

--
-- Estrutura da tabela `encomendas`
-- Guarda a informação de cada encomenda realizada.
--
CREATE TABLE `encomendas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_cliente` varchar(100) NOT NULL,
  `data_nascimento` date NOT NULL,
  `morada` text NOT NULL,
  `preco_total` decimal(10,2) NOT NULL,
  `data_encomenda` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `detalhes_encomenda`
-- Tabela de ligação que detalha os produtos de cada encomenda.
--
CREATE TABLE `detalhes_encomenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_encomenda` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL, -- Preço do produto no momento da compra
  PRIMARY KEY (`id`),
  KEY `id_encomenda` (`id_encomenda`),
  KEY `id_produto` (`id_produto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Constraints para as tabelas
--

--
-- Adicionar Foreign Key à tabela `detalhes_encomenda` para ligar a `encomendas`
--
ALTER TABLE `detalhes_encomenda`
  ADD CONSTRAINT `fk_encomenda` FOREIGN KEY (`id_encomenda`) REFERENCES `encomendas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Adicionar Foreign Key à tabela `detalhes_encomenda` para ligar a `produtos`
--
ALTER TABLE `detalhes_encomenda`
  ADD CONSTRAINT `fk_produto` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

COMMIT;
