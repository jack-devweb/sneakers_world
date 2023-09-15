-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 14 sep. 2023 à 13:53
-- Version du serveur : 8.0.30
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `e_commerce_demo`
--

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `order_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `order_date`) VALUES
(12, 1, '1632.00', 'Expédié', NULL),
(14, 1, '199.00', 'EXPÉDIÉ', '2023-09-02'),
(15, 1, '200.00', 'EXPÉDIÉ', '2023-09-02'),
(16, 1, '321.00', 'EXPÉDIÉ', '2023-09-02'),
(17, 1, '199.00', 'EXPÉDIÉ', '2023-09-02'),
(18, 1, '199.00', 'EXPÉDIÉ', '2023-09-02'),
(19, 1, '199.00', 'EXPÉDIÉ', '2023-09-02'),
(20, 1, '2000.00', 'pending', '2023-09-02'),
(21, 1, '199.00', 'pending', '2023-09-02'),
(23, 1, '790.00', 'pending', '2023-09-08'),
(24, 1, '177.00', 'pending', '2023-09-08'),
(25, 1, '464.00', 'pending', '2023-09-08'),
(26, 1, '466.00', 'pending', '2023-09-08'),
(27, 1, '20.00', 'pending', '2023-09-08'),
(28, 6, '630.00', 'pending', '2023-09-08'),
(29, 6, '233.00', 'pending', '2023-09-14'),
(30, 6, '233.00', 'pending', '2023-09-14');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
