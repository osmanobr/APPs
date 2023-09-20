-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 10-Ago-2021 às 13:50
-- Versão do servidor: 5.7.35
-- versão do PHP: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `empresa_banco`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_blog`
--

CREATE TABLE `tb_blog` (
  `id` int(11) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `texto` text,
  `foto` varchar(250) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `autor` varchar(100) DEFAULT NULL,
  `view` int(11) DEFAULT '0',
  `ativo` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_blog`
--

INSERT INTO `tb_blog` (`id`, `id_categoria`, `titulo`, `texto`, `foto`, `data`, `autor`, `view`, `ativo`) VALUES
(1, 1, 'Concurso aberto', 'O pedido de abertura do concurso INSS foi solicitado ao Ministério da Economia e segue no aguardo do aval. O diretor de Gestão de Pessoas e Administração do Instituto Nacional do Seguro Social, Rogério Souza, pontuou que é importante que seja realizado logo.\r\n\r\nOs novos servidores irão substituir os 3.000 profissionais temporários militares e civis aposentados que terão contratos encerrados ainda neste ano, além das futuras aposentadorias previstas.\r\n\r\n“A solicitação do concurso INSS foi enviado à Economia e agora aguardamos uma posição. Entendemos que outros órgãos da Administração Pública Federal também encaminharam suas solicitações e precisam reforçar os seus quadros. Temos que aguardar e vamos respeitar a questão orçamentária”, explicou Rogério.\r\n\r\nDe acordo com informações do INSS foram solicitadas 7.575 vagas, sendo 6.004 para técnico e 1.571 para analista que terão remunerações iniciais de R$5.186.79 para técnico e R$7.659,87, para analista.\r\nNa solicitação feita o que chamou atenção foi a mudança do requisito do cargo de técnico do seguro social sugerido pelo Instituto passando de nível médio para nível superior. Contudo, em resposta à Folha Dirigida, o diretor de Gestão de Pessoas afirmou que o ensino médio completo será o requisito oficial. “Não haverá alteração de escolaridade. Essa foi apenas uma sugestão. Um próximo concurso INSS deve exigir o nível médio para o técnico”, esclareceu.\r\nConcurso INSS e Assinatura ilimitada\r\nAgora é a hora de iniciar ou intensificar os estudos, concurseiro(a)! Adquira a assinatura ilimitada do Gran Cursos Online e tenha acesso aos melhores professores e materiais para sua preparação em concursos públicos que acontecerão. Potencialize sua aprendizagem!\r\n\r\nLembrando que diversas promoções já foram lançadas em nossa plataforma. Veja a que melhor se encaixa para atender as suas necessidades. Queremos que você seja mais um vitorioso no mundo dos concursos públicos. Clique aqui e adquira sua assinatura!', 'https://blog-static.infra.grancursosonline.com.br/wp-content/uploads/2020/10/14144715/INSS_destaque-450x270.jpg', '2021-06-23 18:29:27', 'Osmano', 0, 1),
(2, 2, 'Mulheres na área', 'O Google lançou nesta terça-feira (22) a nova edição do programa gratuito Cresça com o Google, voltada para as mulheres que querem desenvolver suas carreiras na área de tecnologia. O treinamento contará com a presença de especialistas da big tech e com as convidadas Mariana Pezarini, COO da PrograMaria; Camila Achutti, fundadora e CEO da MasterTech; e Silvana Bahia, codiretora da Olabi e coordenadora da PretaLab.\r\n\r\nSerão discutidas as oportunidades e os desafios enfrentados por elas ao longo da carreira. O objetivo é aumentar o número de mulheres na área de tecnologia, que, atualmente, é um segmento predominantemente masculino. “Trabalhar pela diversidade nas tecnologias é um caminho para construirmos futuros mais inclusivos e socialmente mais justos. Se a tecnologia media nossas escolhas, nossos gostos e pauta nossas ações, é fundamental que tenhamos uma multiplicidade de olhares, visões de mundo e cultura nessa produção tecnológica\", diz Silvana Bahia, do Olab.\r\n\r\nConfira a programação:\r\nPor onde começar a minha carreira (Mariana Pezarini, Diretora de Operações da PrograMaria);\r\nOportunidades na área de tecnologia para elas (Patricia Haizer, Engenheira de Software no Google);\r\nSeja a protagonista da sua carreira (Silvana Bahia, Diretora Executiva de Projetos na Olabi);\r\nA tecnologia a favor da produtividade (Melina López, Gerente de Produto no Google);\r\nProcesso seletivo: Como aplicar em vagas de tecnologia (Kelly Maia, Recrutadora de Engenharia do Google);\r\nTrajetória na Tecnologia (Camila Achutti, CEO e Fundadora da MasterTech).\r\nAlém disso, as mulheres inscritas no treinamento terão desconto exclusivo na matrícula dos cursos de programação da PrograMaria.\r\n\r\nPara fazer a inscrição no treinamento do Google, basta entrar no site do programa. A adesão é gratuita e o conteúdo fica disponível no portal do Cresça com o Google.', 'https://img.ibxk.com.br/2021/06/21/21172818075331.jpg?w=1120&h=420&mode=crop&scale=both', '2021-06-23 14:18:32', 'Osto', 0, 1),
(3, 2, '10 Boas notícias para você', 'Bom dia! Separamos as principais notícias do mundo da Ciência e Tecnologia para você saber tudo o que aconteceu na última segunda-feira (21). Para conferir cada notícia na íntegra, basta clicar nos links a seguir.\r\n\r\n1. Começa hoje (21) o Prime Day; dispositivos Amazon a partir de R$ 199. O gigante do e-commerce deve disponibilizar descontos em mais de dois milhões de produtos do site, incluindo nos setores Eletrônicos, Jogos, Esportivos, Livros e Brinquedos.\r\n\r\n2. Esposa filma ‘reaction’ de Antônio Fagundes em The Last of Us 2. “Momentos de tensão. Parece que está empacado e não sai dessa parte, está difícil”, brincou a esposa do ator.\r\n\r\n3. Vale mais a pena comprar celular no Amazon Prime Day ou na Black Friday? O TecMundo realizou um levantamento de preços e especulou os valores que os celulares poderão chegar durante a Black Friday.\r\n\r\n4. Windows 11: Microsoft derruba links de download da build vazada. Empresa está caçando endereços e armazenamento da ISO vazada, mas não negou que a build disponibilizada seja a verdadeira.\r\n\r\n5. O dia mais curto do ano: o solstício de inverno no Hemisfério Sul. É também solstício de verão no Hemisfério Norte, marcando seu dia mais longo do ano!\r\n\r\n6. Samsung constrói fábrica na Índia em 6 meses para sair da China. A fábrica da Samsung na cidade industrial de Noida recebeu incentivos do governo indiano de US$ 61 milhões.\r\n\r\n7. Manifest: 4ª temporada da série pode ir para a Netflix; entenda! A Netflix é conhecida por resgatar produções canceladas por outros estúdios.\r\n\r\n8. Nubank nomeia Anitta para o Conselho de Administração. A cantora auxiliará a direção do banco digital a aprimorar produtos, serviços e a comunicação com os clientes.\r\n\r\n9. Hyundai compra Boston Dynamics, do cão-robô Spot, por R$ 5,6 bilhões. O lançamento do produto de maior sucesso da Boston Dynamics, o cão-robô Spot, ocorreu quando a empresa estava sob controle da SoftBank.\r\n\r\n10. Posso comer alimentos fora do período de validade? Entenda como são estabelecidos os prazos de validade dos produtos consumíveis e como proceder com alimentos expirados.', 'https://img.ibxk.com.br/2021/06/21/21192704609424.jpg?w=1120&h=420&mode=crop&scale=both', '2021-06-23 19:21:10', 'Osto', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_categoria`
--

CREATE TABLE `tb_categoria` (
  `id` int(11) NOT NULL,
  `categoria` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_categoria`
--

INSERT INTO `tb_categoria` (`id`, `categoria`) VALUES
(1, 'CONCURSOS'),
(2, 'TECNOLOGIA');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_causa`
--

CREATE TABLE `tb_causa` (
  `id` int(11) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `texto` text,
  `foto` varchar(200) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `previsao` date DEFAULT NULL,
  `criador` varchar(200) DEFAULT NULL,
  `localizacao` varchar(250) DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  `ativo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_causa`
--

INSERT INTO `tb_causa` (`id`, `id_categoria`, `titulo`, `texto`, `foto`, `data`, `previsao`, `criador`, `localizacao`, `views`, `ativo`) VALUES
(1, 2, 'EDUCAÇÃO', 'Criar oportunidades para intercâmbios culturais por meio da leitura, da escrita e da oralidade, valorizando o protagonismo de pessoas e de comunidades rurais da Amazônia Legal Brasileira.', 'https://posgraduando.com/wp-content/uploads/2016/01/desk-07.jpg', '2021-06-10', '2022-06-10', 'osto', 'araguaina tocantins', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_comentario`
--

CREATE TABLE `tb_comentario` (
  `id` int(11) NOT NULL,
  `id_pessoa` int(11) DEFAULT NULL,
  `data_hora` datetime DEFAULT NULL,
  `comentario` varchar(250) DEFAULT NULL,
  `local` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_comentario`
--

INSERT INTO `tb_comentario` (`id`, `id_pessoa`, `data_hora`, `comentario`, `local`, `status`) VALUES
(1, 1, '2021-08-03 00:00:00', 'teste', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_contador`
--

CREATE TABLE `tb_contador` (
  `id` int(11) NOT NULL,
  `titulo` varchar(60) DEFAULT NULL,
  `icone` varchar(60) DEFAULT NULL,
  `valor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_contador`
--

INSERT INTO `tb_contador` (`id`, `titulo`, `icone`, `valor`) VALUES
(1, 'Associados', 'flaticon-book', 1000),
(2, 'Satisfação', 'flaticon-book', 100),
(3, 'Projetos', 'flaticon-book', 300),
(4, 'Causas', '', 15);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_contato`
--

CREATE TABLE `tb_contato` (
  `id` int(11) NOT NULL,
  `nome` varchar(160) DEFAULT NULL,
  `email` varchar(160) DEFAULT NULL,
  `celular` varchar(60) DEFAULT NULL,
  `assunto` varchar(160) DEFAULT NULL,
  `texto` text,
  `data_hora` datetime DEFAULT NULL,
  `ip` varchar(60) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_contato`
--

INSERT INTO `tb_contato` (`id`, `nome`, `email`, `celular`, `assunto`, `texto`, `data_hora`, `ip`, `status`) VALUES
(10, 'AUTO POSTO MARANATA', 'osmanobr@hotmail.com', '(63)99289-3682', 'teste', 'asdfasdf asd', '2021-07-21 20:42:01', '::1', 1),
(11, '', '', '', '', '', '2021-07-21 21:13:23', '::1', 1),
(12, 'TRANSMOZZATO FILHO', 'asdf@gmail.com', 'asdf', 'asdf', 'asdf', '2021-07-22 16:00:29', '::1', 1),
(13, 'Daniel Aires Brito', 'clientetested@gmail.com', '63992221321dd', 'adsf asd', 'asdf asd', '2021-07-22 18:23:04', '::1', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_departamento`
--

CREATE TABLE `tb_departamento` (
  `id` int(11) NOT NULL,
  `departamento` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_doacao`
--

CREATE TABLE `tb_doacao` (
  `id` int(11) NOT NULL,
  `id_projeto` int(11) DEFAULT NULL,
  `id_causa` int(11) DEFAULT NULL,
  `id_filiado` int(11) DEFAULT NULL,
  `valor` double DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `publico` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_doacao`
--

INSERT INTO `tb_doacao` (`id`, `id_projeto`, `id_causa`, `id_filiado`, `valor`, `data`, `publico`) VALUES
(1, 1, NULL, 1, 500, '2021-07-05 17:50:02', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_duplicata`
--

CREATE TABLE `tb_duplicata` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `id_associado` int(11) DEFAULT NULL,
  `data_criacao` int(11) DEFAULT NULL,
  `data_ciencia` datetime DEFAULT NULL,
  `data_vencimento` date DEFAULT NULL,
  `data_pagamento` date DEFAULT NULL,
  `forma_pagamento` int(11) DEFAULT NULL,
  `valor_bruto` double DEFAULT NULL,
  `valor_acrescimo` int(11) DEFAULT NULL,
  `valor_descoto` double DEFAULT NULL,
  `valor_haver` double DEFAULT NULL,
  `valor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_empresa`
--

CREATE TABLE `tb_empresa` (
  `id` int(11) NOT NULL,
  `razao` varchar(200) DEFAULT NULL,
  `fantasia` varchar(200) DEFAULT NULL,
  `cnpj` varchar(60) DEFAULT NULL,
  `cgc` varchar(60) DEFAULT NULL,
  `ie` varchar(60) DEFAULT NULL,
  `im` varchar(60) DEFAULT NULL,
  `logo` varchar(250) DEFAULT NULL,
  `banner` varchar(250) DEFAULT NULL,
  `banner2` varchar(250) DEFAULT NULL,
  `banner3` varchar(250) DEFAULT NULL,
  `lema` varchar(250) DEFAULT NULL,
  `data_criacao` date DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `municipio` varchar(200) DEFAULT NULL,
  `bairro` varchar(200) DEFAULT NULL,
  `endereco` varchar(250) DEFAULT NULL,
  `cep` varchar(30) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `sobre` text,
  `objetivo` text,
  `como_funciona` text,
  `fundacao` text,
  `associados` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_empresa`
--

INSERT INTO `tb_empresa` (`id`, `razao`, `fantasia`, `cnpj`, `cgc`, `ie`, `im`, `logo`, `banner`, `banner2`, `banner3`, `lema`, `data_criacao`, `pais`, `uf`, `municipio`, `bairro`, `endereco`, `cep`, `email`, `sobre`, `objetivo`, `como_funciona`, `fundacao`, `associados`) VALUES
(1, 'Confrafia Fortifica - CEE', 'CONFRARIA FORTIFICA', '42.682.322/0001-08', '', '12345621', '3215112', 'images/empresa/6f69f5ae41c40a046a98725b38f8eeba.png', 'images/empresa/1795ee0be175ffbed7fe35e08827136e.jpg', 'images/empresa/31f6b735faca6353ee2426c3f902113c.png', 'images/empresa/746c6bde730aab37eaed2c3b15d5607e.jpg', 'Juntos Podemos Fazer Mais Por Todos', '2021-06-01', 'Brasil', 'DF', 'Brasília', 'Taguatinga', 'Quadra QSC 15 Lote, 01', '72016-150', 'atendimento@confrariafortifcia.com.br', 'Idealizada em 2018 pelo atual presidente, Gustavo Lima Barreto, a confraria Fortifica é uma associação sem fins lucrativos que busca fortalecer o empresário e o empreendedor nos mais diversos ramos, no Distrito Federal e no entorno, por meio de iniciativas privadas de seus associados ou frentes junto ao Poder Público, fomentando sempre o desenvolvimento de seus membros.', 'Nossa missão:\n\nRealizar fomentos econômicos, eventos, treinamentos, capacitação profissional, parcerias comerciais, assistencialismo social para o desenvolvimento e crescimento de todos os seus associados, sendo empreendedores individuais, pequenas e médias empresas.', 'Faça parte do nosso time.', '', 'Nosso time possui um lugar para você !');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_evento`
--

CREATE TABLE `tb_evento` (
  `id` int(11) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `pais` varchar(200) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `cidade` varchar(160) DEFAULT NULL,
  `bairro` varchar(200) DEFAULT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `texto` text,
  `foto` varchar(200) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `ativo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_evento`
--

INSERT INTO `tb_evento` (`id`, `id_categoria`, `pais`, `uf`, `cidade`, `bairro`, `endereco`, `titulo`, `texto`, `foto`, `data`, `views`, `ativo`) VALUES
(1, 1, 'ARAGUAÍNA-TO', 'TO', 'PALMAS', '72', 'ALAMEDA 01, LT12 CASA 02', 'A WEB SECURITY CONSULTORIA', 'A WEB SECURITY CONSULTORIA, em parceria com a LATINNE GROUP, DPO LEGAL e a SOMAXI  tem o prazer de convidar a todos vocês para este Webinar onde será apresentada a Visão da Lei voltada para soluções inclusivasEvento que levará os participantes a uma experiência prática da importância da inclusão digital para as pessoas com deficiência e como podemos contribuir para o aprendizado dos fundamentos da Lei Geral de Proteção de Dados, sua correlação e aplicabilidade quando envolve as pessoas com deficiência.AberturaConvidado EspecialMediadores ConfirmadosDamiao Oliveira - DPO Somaxi MSP / SCDaniel Carnaúba - DPO e Coach / SPJorge Muniz - CEO Web Security / RJLeandro Venâncio - CEO DPO Legal / SCCERTIFICADO DE PARTICIPAÇÃO - PARA QUEM ESTIVER INSCRITO NA PLATAFORMAApós o Evento, será emitido de forma automática para o e-mail de cadastro. Favor conferir o nome que você for cadastrar para que o nome não seja impresso de forma erradaEvento que levará os participantes a uma experiência prática da importância da inclusão digital para as pessoas com deficiência e como podemos contribuir para o aprendizado dos fundamentos da Lei Geral de Proteção de Dados, sua correlação e aplicabilidade quando envolve as pessoas com deficiência.AberturaConvidado EspecialMediadores ConfirmadosDamiao Oliveira - DPO Somaxi MSP / SCDaniel Carnaúba - DPO e Coach / SPJorge Muniz - CEO Web Security / RJLeandro Venâncio - CEO DPO Legal / SCCERTIFICADO DE PARTICIPAÇÃO - PARA QUEM ESTIVER INSCRITO NA PLATAFORMAApós o Evento, será emitido de forma automática para o e-mail de cadastro. Favor conferir o nome que você for cadastrar para que o nome não seja impresso de forma errada', 'images/resource/case-8.jpg', '2021-08-03 00:00:00', 0, 1),
(2, 0, '', '', '', '', '', '', '', '', '2021-08-02 00:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_faq`
--

CREATE TABLE `tb_faq` (
  `id` int(11) NOT NULL,
  `faq` varchar(250) DEFAULT NULL,
  `texto` text,
  `ativo` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_faq`
--

INSERT INTO `tb_faq` (`id`, `faq`, `texto`, `ativo`) VALUES
(1, 'ISSO É UMA ASSOCIAÇÃO SEM FINS LUCRATIVOS?', 'Não, a ASTIDS é uma Associação de Analistas de Sistemas, Tecnólogos da Informação e Desenvolvedores de Softwares.\r\nO Objetivo principal é unir essa classe que tem contribuído fortemente na automatização de processos, armazenamento e segurança de dados trazendo um avanço tecnológico de 1º mundo para nosso país. É uma associação mantida por doações voluntárias de associados e não associados, venda de cursos e certificações.', NULL),
(2, 'QUANTO CUSTA PARA TORNAR-ME UM ASSOCIADO?', 'O cadastro para associados é gratuito e não tem mensalidades, mas o associado pode decidir se quer contribuir mensalmente ou adquirir algum de nossos cursos pagos. Existem dois tipos de associados:\r\n01 - ASSOCIADO PADRÃO\r\nAssociado que se beneficia das informações e auxílio jurídico da banco\r\n02 - ASSOCIADO CONTRIBUINTE\r\nAssociado que resolveu contribuir mensalmente com uma pequena ajuda financeira ou cursos.\r\n03 - ASSOCIADO MANTENEDOR\r\nSomente o associado mantenedor participa na eleição de nas chapas de presidente', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_filiado`
--

CREATE TABLE `tb_filiado` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `celular` varchar(30) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `rg` varchar(100) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `cidade_uf_origem` varchar(250) DEFAULT NULL,
  `profissao` varchar(250) DEFAULT NULL,
  `interesse` varchar(250) DEFAULT NULL,
  `foto_perfil` varchar(250) DEFAULT NULL,
  `sobre_mim` text,
  `endereco` varchar(250) DEFAULT NULL,
  `bairro` varchar(160) DEFAULT NULL,
  `cidade` varchar(160) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `data_filiacao` date DEFAULT NULL,
  `tipo_filiado` varchar(100) DEFAULT NULL,
  `senha` varchar(120) DEFAULT NULL,
  `exibir_time` tinyint(1) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_filiado`
--

INSERT INTO `tb_filiado` (`id`, `nome`, `email`, `celular`, `cpf`, `rg`, `data_nascimento`, `cidade_uf_origem`, `profissao`, `interesse`, `foto_perfil`, `sobre_mim`, `endereco`, `bairro`, `cidade`, `uf`, `data_filiacao`, `tipo_filiado`, `senha`, `exibir_time`, `status`) VALUES
(1, 'Osmano Torres de Brito', NULL, NULL, '49834878168', '1.350.213', '1970-05-03', 'São Geraldo do Araguaia - PA', 'Analista Desenvolvedor de Sistemas', 'desenvolvimento', 'https://sigaescola.online/osmano.jpg', 'Preocupado em acompanhar a tecnologia', 'Alameda 01 LT12 Casa 02', 'Arno 72 Plano Diretor Norte', 'Palmas', 'TO', '2021-07-01', 'Diretor', '2bdf5ff8109164d4f99e', 1, 1),
(2, 'joão', NULL, NULL, '49834878168', '1350213', '1970-05-03', 'são geraldo/pa', 'lavrador', 'participar', NULL, 'gosto de tudo isso é sobre mim', '605 norte alameda 01 lote 12', 'plano diretor norte', 'palmas', 'to', '2021-07-21', 'socio ', '123123', 1, 1),
(3, 'osmanito torres de brito', '0', '63992221321', '4521554411111', '144454', '1972-07-27', 'saga-to', 'tecnico', 'Ser um mantenedor', NULL, 'asdfasd', 'rua katia', 'centro', 'araguana', 'AC', '2021-07-21', 'Efetivo', NULL, 1, 1),
(4, 'Daniel Aires Brito', '0', '999999999', '12345678910', '123456789', '1994-02-01', 'Araguaina', 'Tecnico', 'Receber benefícios', NULL, 'Teste sobre minha pessoa', 'Alameda 01 LT12 Casa 02a sdfasdfa sdfasdasdfasdf asd', 'Plano Diretor Norte', 'Palmas', 'TO', '2021-08-03', 'Beneficiado', '010294', 1, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_fornecedor`
--

CREATE TABLE `tb_fornecedor` (
  `id` int(11) NOT NULL,
  `id_fornecedor` int(11) DEFAULT NULL,
  `cnpj_cpf` varchar(60) DEFAULT NULL,
  `nome_fantasia` varchar(100) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `razao_social` varchar(100) DEFAULT NULL,
  `endereco` varchar(250) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `bairro` varchar(250) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `tipo_pessoa` varchar(20) DEFAULT NULL,
  `inscricao_estadual` varchar(50) DEFAULT NULL,
  `inscricao_municipal` varchar(50) DEFAULT NULL,
  `tipo_contribuinte` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_fornecedor`
--

INSERT INTO `tb_fornecedor` (`id`, `id_fornecedor`, `cnpj_cpf`, `nome_fantasia`, `data`, `razao_social`, `endereco`, `numero`, `bairro`, `cidade`, `uf`, `tipo_pessoa`, `inscricao_estadual`, `inscricao_municipal`, `tipo_contribuinte`, `email`, `telefone`, `status`) VALUES
(7, 1, '49834878168', 'O. Torres de Brito', '2021-07-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(12, 2, '14854541654651549846', 'Daniel', '2021-08-01', 'Daniel Aires Brito', 'Alameda 01 LT12 Casa 02', '2', 'Plano Diretor Norte', 'Palmas', 'TO', 'Fisica', '3123213', '2132312', 'Não Contribuinte', 'aiesbala@hotmail.com', '63999999999', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_lote`
--

CREATE TABLE `tb_lote` (
  `id` int(11) NOT NULL,
  `lote` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_lote`
--

INSERT INTO `tb_lote` (`id`, `lote`) VALUES
(1, 'lote generico');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_marca`
--

CREATE TABLE `tb_marca` (
  `id` int(11) NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_menu`
--

CREATE TABLE `tb_menu` (
  `id` int(11) NOT NULL,
  `menu` varchar(40) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_menu`
--

INSERT INTO `tb_menu` (`id`, `menu`, `link`, `ativo`) VALUES
(1, 'INÍCIO', '#', 1),
(2, 'SOBRE', '#', 1),
(3, 'AÇÃO', '#', 1),
(4, 'NOTÍCIAS', '#', 1),
(5, 'CONTATO', '#', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_nfe`
--

CREATE TABLE `tb_nfe` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `chave` varchar(60) DEFAULT NULL,
  `xml` text,
  `situacao` varchar(60) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `tipo` varchar(10) NOT NULL DEFAULT 'ENTRADA',
  `CNPJ` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_pagina`
--

CREATE TABLE `tb_pagina` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `subtitulo` varchar(250) DEFAULT NULL,
  `texto` text,
  `foto` varchar(250) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_pagina`
--

INSERT INTO `tb_pagina` (`id`, `titulo`, `subtitulo`, `texto`, `foto`, `link`, `ativo`) VALUES
(1, 'Fundação', 'Origem histórica provável', 'Uma fundação é considerada um fundo autônomo, que tem, por finalidade, uma ação definida em seus estatutos por seu instituidor ou instituidores, definido por contrato de fidúcia. É diferente do chamado cooperativismo pois é impessoal, no sentido de sua operacionalização, podendo inclusive mudar a operacionalização e/ou ampliá-la, de conformidade, sempre com a lei. De forma geral, é uma instituição caracterizada como pessoa jurídica composta pela organização de um patrimônio mas que não tem proprietário, nem titular, nem sócios[carece de fontes]. É uma entidade de direito privado, constituída por ata de dotação patrimonial inter vivos ou causa mortis para determinada finalidade econômica não distributiva, ou seja, pela lei toda renda deve ser e estar re-investida no chamado \" operation found \" do inglês, segundo novo entendimento internacional que reporta ao usual através dos tempos, sendo dessa feita devida, fiscalizada pelo Ministério Público (Poder Judiciário do País).\r\n\r\nPortanto, é uma pessoa jurídica composta por um patrimônio juridicamente indissolúvel e personalizado, destacado pelo seu instituidor ou instituidores públicos ou privados, para uma ou mais finalidades específicas, não distributivas, com relação a sua renda, que deve forçosamente ser reincorporada. Não tem proprietário, nem titular, daí seu caráter não distributivo, que a lei estabelece, desde os primórdios tempos nem sócios ou acionistas[carece de fontes]. Consiste apenas num patrimônio administrado, segundo a Lei e destinado a um fim econômico, determinado pela própria lei que a autoriza, sendo acompanhada em sua atuação pelo Ministério Público da União, Estados ou Municípios, dependendo da esfera de atuação. Segundo novo entendimento internacional, é dirigido por administradores ou curadores, autorizados e fiscalizados, na conformidade de seus estatutos, esses aprovados pelo Ministério Público a que está juridicamente subordinado.', NULL, '?modulo=pagina&id=1', 1),
(2, 'ASSOCIADOS', 'ASSOCIADOS', 'ASSOCIADOS', NULL, '?modulo=pagina&id=2', 1),
(3, 'ASSOCIE SE', 'ASSOCIE SE', 'ASSOCIE SE', NULL, 'modulo=pagina&id=3', 1),
(4, 'Pagina de teste', 'Pagina de teste para', 'Essa é uma pagina de teste', '', '', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_pedido`
--

CREATE TABLE `tb_pedido` (
  `id` int(11) NOT NULL,
  `id_associado` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_session` varchar(200) DEFAULT NULL,
  `data` int(11) NOT NULL,
  `situacao` varchar(20) DEFAULT 'ABERTO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_pedido_item`
--

CREATE TABLE `tb_pedido_item` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `id_item` int(11) DEFAULT NULL,
  `valor_unitario` double DEFAULT NULL,
  `valor_promocional` double DEFAULT NULL,
  `percentual_desconto` double DEFAULT NULL,
  `quantidade` double DEFAULT NULL,
  `devolvido` double DEFAULT NULL,
  `valor_total` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_produto`
--

CREATE TABLE `tb_produto` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `descricao` text,
  `imagem` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_produto_estoque`
--

CREATE TABLE `tb_produto_estoque` (
  `id` int(11) NOT NULL,
  `nota_chave` varchar(66) DEFAULT NULL,
  `id_fornecedor` int(11) DEFAULT NULL,
  `id_lote` int(11) DEFAULT NULL,
  `id_departamento` int(11) DEFAULT NULL,
  `id_setor` int(11) DEFAULT NULL,
  `id_secao` int(11) DEFAULT NULL,
  `id_marca` int(11) DEFAULT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `valor_compra` double DEFAULT NULL,
  `imposto_federal` double DEFAULT NULL,
  `imposto_estadual` double DEFAULT NULL,
  `imposto_municipal` double DEFAULT NULL,
  `percentual_desconto` double DEFAULT NULL,
  `valor_venda` double DEFAULT NULL,
  `movimento` varchar(10) DEFAULT 'ENTRADA',
  `acao` int(11) DEFAULT NULL,
  `data_fabricacao` date DEFAULT NULL,
  `data_vencimento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_produto_estoque_acao`
--

CREATE TABLE `tb_produto_estoque_acao` (
  `id` int(11) NOT NULL,
  `acao` varchar(30) DEFAULT NULL,
  `sinal` varchar(1) NOT NULL DEFAULT '+'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_produto_estoque_acao`
--

INSERT INTO `tb_produto_estoque_acao` (`id`, `acao`, `sinal`) VALUES
(1, 'VENDA', '-'),
(2, 'COMPRA', '+'),
(3, 'DEVOLUCAO', '+'),
(4, 'EXTORNO', '-'),
(5, 'FURTO', '-'),
(6, 'VENCIMENTO', '-'),
(7, 'GANHO', '+'),
(8, 'DOACAO', '-'),
(9, 'EMPRESTIMO', '-'),
(10, 'RECEBIMENTO', '+');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_projeto`
--

CREATE TABLE `tb_projeto` (
  `id` int(11) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `texto` text,
  `foto` varchar(250) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `valor` double DEFAULT NULL,
  `valor_arrecadado` double DEFAULT NULL,
  `view` int(11) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_projeto`
--

INSERT INTO `tb_projeto` (`id`, `id_categoria`, `titulo`, `texto`, `foto`, `data`, `valor`, `valor_arrecadado`, `view`, `ativo`) VALUES
(1, 1, 'Hoteis viram apartamentos', 'Hotéis abandonados no Rio de Janeiro estão em vias de se transformar em residências e escritórios. A prefeitura carioca autorizou, na semana passada, que o Hotel Glória, primeiro cinco estrelas do Brasil, volte a abrir suas portas, transformando-se em um prédio de apartamentos e escritórios. No momento, o Executivo municipal analisa outros pedidos semelhantes.\r\n\r\nInaugurado em agosto de 1922, o Glória foi palco de reuniões dos membros da Assembleia Nacional Constituinte, em 1988, e teve suas portas fechadas em 2013, após a falência do Grupo EBX, do empresário Eike Batista, que havia adquirido o imóvel em 2008, por R$ 80 milhões.\r\n\r\nO presidente do Sindicato dos Meios de Hospedagem do Município do Rio de Janeiro (HotéisRIO), Alfredo Lopes, lembrou que o Hotel Glória ia ser reformado e deveria ter ficado pronto para a Copa do Mundo de 2014, mas a obra acabou não sendo concluída e perdeu o objetivo.\r\n\r\nEle avaliou que, em meio à revitalização do centro do Rio de Janeiro, essa transformação é muito importante.\r\n\r\n“Tudo isso garante a manutenção de empregos e a arrecadação de impostos. Isso é muito melhor. É mais inteligente do que ter um prédio, ícone da hotelaria durante muitas décadas, fechado, se acabando”, disse, em entrevista à Agência Brasil.\r\n\r\nRevitalização\r\nO secretário municipal de Desenvolvimento Econômico, Inovação e Simplificação, Chicão Bulhões, afirmou que a conversão do hotel em um prédio misto – com salas comerciais e apartamentos residenciais – permitirá que esses imóveis que hoje estão vazios retornem ao mercado.\r\n\r\nChico Bulhões informou que, além do Glória, já foi autorizada também pela prefeitura a conversão do Hotel Paissandu, outro hotel icônico da cidade, localizado no bairro do Flamengo.\r\n\r\n“A gente fica muito feliz de ver novamente a possibilidade de ver o Hotel Glória com vida, ocupado. É muito bom para o bairro, para a cidade e, também, obviamente, preserva o prédio histórico e a memória da cidade do Rio”, destacou.\r\n\r\nMais empreendimentos\r\nAlfredo Lopes estimou que outros 11 empreendimentos hoteleiros que antes eram destinados apenas a turistas têm potencial para se transformar em prédios mistos. Ele lembrou que, no caso do Hotel Glória, a legislação urbanística permite a transformação do prédio. Para outros hotéis, localizados em bairros distintos, é necessária uma lei específica para mudar a destinação do imóvel.\r\n\r\nLopes afirmou que está tentando viabilizar, junto à Câmara de Vereadores, um projeto de lei, para aprovação no segundo semestre, que possa viabilizar a conversão dessas unidades pela cidade.\r\n\r\nCom a queda do número de visitantes no Rio e a pressão do mercado imobiliário, outros hotéis buscam a mesma saída para a crise agravada pela pandemia de covid-19.\r\n\r\nDe acordo com o HotéisRIO, além do Glória, outros três projetos já estão avançados, sendo dois no Flamengo e um na Barra da Tijuca.\r\n\r\nRetração\r\nO HotéisRIO representa cerca de 300 instalações hoteleiras na capital que ofertam, atualmente, cerca de 52 mil quartos. Antes das Olimpíadas de 2016, a capital fluminense tinha à disposição do turista 30 mil quartos. Esse número chegou a 62 mil, logo após o evento esportivo.\r\n\r\n“Foi a rede mais moderna da América Latina por conta da Olimpíada”, afirmou Alfredo Lopes.\r\n\r\nA expectativa de negócios crescentes, entretanto, não se confirmou e a retração ficou ainda mais grave com a pandemia. “Hoje, a gente não tem um mercado pujante, até porque os eventos estão escassos e até paralisados.”\r\n\r\nO ano de 2021 começou para os hotéis cariocas com ocupação bem baixa. Em Copacabana, por exemplo, a média foi de 15% a 20%. Em seguida, houve uma melhora, com a ocupação subindo entre 25% e 28%, com alguns pontos fora da curva. No Dia dos Namorados, a ocupação subiu próximo de 70%. “Mas isso não se manteve porque faltam os eventos. São eles que mantêm a ocupação durante todo o ano. E nós não temos ainda um calendário de eventos por conta da pandemia”.\r\n\r\nDesde o início da pandemia, no ano passado, 80 hotéis suspenderam as operações. Cerca de 12 não voltaram a abrir até hoje, entre eles, o Hotel Everest, que pode ser convertido também em prédio misto, segundo Lopes. Outros que ainda não reabriram devem retornar até o final deste ano. Alguns, como o Ceasar Park e o Marina, estão em obras e têm previsão de reabrir no fim deste ano.', 'https://image.shutterstock.com/image-photo/office-meeting-conference-room-beautiful-600w-1919479706.jpg', '2021-06-24', 60000, 1800, 0, 1),
(2, 2, 'PROJETO', 'A Somaxi Tecnologia MSP juntamente com a Associação Nacional dos Empregados da Dataprev - ANED, tem o prazer em convidar você a participar deste Webinar que mostrará o \"Papel do Profissional de Proteção de Dados e as privatizações da DATAPREV e do SERPRO\"\r\n\r\n- Principais Desafios\r\n- Riscos\r\n- Números\r\n- Visão do Futuro\r\n\r\n\r\nEstes são alguns temas relevantes que iremos abordar\r\n\r\n\r\nQuem está com esta e outras dúvidas para participar de nossa Webinar, participe com envio de perguntas para o e-mail lgpd@somaxi.com.br e responderemos “ao vivo” na Live através dos especialistas da bancada:\r\n\r\nDamiao Oliveira - DPO da Somaxi Tecnologia (Mediador)\r\nLeo Santuchi, presidente da Associação Nacional dos Empregados da Dataprev - ANED \r\nMarcos Martins Melo - Analista de Redes do SERPRO\r\nDeivi Kuhn - Graduado em Ciências Econômicas e Mestrando em Administração da UNB. Analista de Sistemas do SERPRO\r\nAntonio Braga - Analista de Tecnologia da Informação - Data Science\r\n\r\nCERTIFICADO DE PARTICIPAÇÃO - PARA QUEM ESTIVER INSCRITO NA PLATAFORMA', 'https://image.shutterstock.com/image-photo/coffee-picker-show-red-cherries-600w-1707181633.jpg', '2021-06-29', 5000, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_secao`
--

CREATE TABLE `tb_secao` (
  `id` int(11) DEFAULT NULL,
  `secao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_setor`
--

CREATE TABLE `tb_setor` (
  `id` int(11) NOT NULL,
  `setor` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_sub_menu`
--

CREATE TABLE `tb_sub_menu` (
  `id` int(11) NOT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `sub_menu` varchar(60) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_sub_menu`
--

INSERT INTO `tb_sub_menu` (`id`, `id_menu`, `sub_menu`, `link`, `ativo`) VALUES
(1, 1, 'INICIO', 'index.php', 1),
(2, 1, 'SAIR', 'index.php?modulo=sair', 1),
(3, 4, 'BLOG', '?modulo=blog', 1),
(4, 5, 'CONTATO', 'index.php?modulo=contato', 1),
(5, 2, 'SOBRE NÓS', '?modulo=sobre', 1),
(6, 3, 'PROJETOS', '?modulo=projeto', 1),
(7, 3, 'CAUSAS', '?modulo=causa', 1),
(8, 2, 'FAQ', '?modulo=faq', 1),
(9, 1, 'AREA DO CLIENTE', '?modulo=area_cliente', 1),
(10, 2, 'NOSSO TIME', '?modulo=time', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_testemunho`
--

CREATE TABLE `tb_testemunho` (
  `id` int(11) NOT NULL,
  `id_pessoa` int(11) DEFAULT NULL,
  `testemunho` varchar(200) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_testemunho`
--

INSERT INTO `tb_testemunho` (`id`, `id_pessoa`, `testemunho`, `ativo`) VALUES
(1, 1, 'Um bom site, parabens!', 1),
(2, 2, 'Faço parte da turma e confesso tenho os melhores amigos!', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `id` int(11) NOT NULL,
  `id_filiado` int(11) NOT NULL,
  `usuario` varchar(120) DEFAULT NULL,
  `senha` varchar(120) DEFAULT NULL,
  `ultimo_acesso` datetime DEFAULT NULL,
  `nivel` int(11) DEFAULT '4',
  `token` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tb_blog`
--
ALTER TABLE `tb_blog`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_categoria`
--
ALTER TABLE `tb_categoria`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_causa`
--
ALTER TABLE `tb_causa`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_comentario`
--
ALTER TABLE `tb_comentario`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_contador`
--
ALTER TABLE `tb_contador`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_contato`
--
ALTER TABLE `tb_contato`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_departamento`
--
ALTER TABLE `tb_departamento`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_doacao`
--
ALTER TABLE `tb_doacao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_duplicata`
--
ALTER TABLE `tb_duplicata`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_empresa`
--
ALTER TABLE `tb_empresa`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_evento`
--
ALTER TABLE `tb_evento`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_faq`
--
ALTER TABLE `tb_faq`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_filiado`
--
ALTER TABLE `tb_filiado`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_fornecedor`
--
ALTER TABLE `tb_fornecedor`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_lote`
--
ALTER TABLE `tb_lote`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_marca`
--
ALTER TABLE `tb_marca`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_menu`
--
ALTER TABLE `tb_menu`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_nfe`
--
ALTER TABLE `tb_nfe`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_pagina`
--
ALTER TABLE `tb_pagina`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_pedido`
--
ALTER TABLE `tb_pedido`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_pedido_item`
--
ALTER TABLE `tb_pedido_item`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_produto`
--
ALTER TABLE `tb_produto`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_produto_estoque`
--
ALTER TABLE `tb_produto_estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_produto_estoque_acao`
--
ALTER TABLE `tb_produto_estoque_acao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_projeto`
--
ALTER TABLE `tb_projeto`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_setor`
--
ALTER TABLE `tb_setor`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_sub_menu`
--
ALTER TABLE `tb_sub_menu`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_testemunho`
--
ALTER TABLE `tb_testemunho`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_blog`
--
ALTER TABLE `tb_blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tb_categoria`
--
ALTER TABLE `tb_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tb_causa`
--
ALTER TABLE `tb_causa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_comentario`
--
ALTER TABLE `tb_comentario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_contador`
--
ALTER TABLE `tb_contador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tb_contato`
--
ALTER TABLE `tb_contato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `tb_departamento`
--
ALTER TABLE `tb_departamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_doacao`
--
ALTER TABLE `tb_doacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_duplicata`
--
ALTER TABLE `tb_duplicata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_empresa`
--
ALTER TABLE `tb_empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_evento`
--
ALTER TABLE `tb_evento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tb_faq`
--
ALTER TABLE `tb_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tb_filiado`
--
ALTER TABLE `tb_filiado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tb_fornecedor`
--
ALTER TABLE `tb_fornecedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `tb_lote`
--
ALTER TABLE `tb_lote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_marca`
--
ALTER TABLE `tb_marca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_menu`
--
ALTER TABLE `tb_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tb_nfe`
--
ALTER TABLE `tb_nfe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_pagina`
--
ALTER TABLE `tb_pagina`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tb_pedido`
--
ALTER TABLE `tb_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_pedido_item`
--
ALTER TABLE `tb_pedido_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_produto`
--
ALTER TABLE `tb_produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_produto_estoque`
--
ALTER TABLE `tb_produto_estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_produto_estoque_acao`
--
ALTER TABLE `tb_produto_estoque_acao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `tb_projeto`
--
ALTER TABLE `tb_projeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tb_setor`
--
ALTER TABLE `tb_setor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_sub_menu`
--
ALTER TABLE `tb_sub_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `tb_testemunho`
--
ALTER TABLE `tb_testemunho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
