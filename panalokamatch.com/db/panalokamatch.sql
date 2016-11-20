-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.0.17-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.3.0.5059
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table panalokamatch.ci_sessions
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  `time_login` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table panalokamatch.ci_sessions: ~0 rows (approximately)
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;

-- Dumping structure for table panalokamatch.t_general
CREATE TABLE IF NOT EXISTS `t_general` (
  `id` int(1) NOT NULL,
  `web_prus` varchar(255) NOT NULL,
  `nama_prus` varchar(255) NOT NULL,
  `alamat_prus` varchar(255) NOT NULL,
  `email_prus` varchar(100) NOT NULL,
  `copyright_prus` varchar(100) NOT NULL,
  `batas_lv` int(100) NOT NULL,
  `cost_pin` double(100,2) NOT NULL,
  `prefix_member` varchar(3) NOT NULL,
  `token` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table panalokamatch.t_general: 1 rows
/*!40000 ALTER TABLE `t_general` DISABLE KEYS */;
REPLACE INTO `t_general` (`id`, `web_prus`, `nama_prus`, `alamat_prus`, `email_prus`, `copyright_prus`, `batas_lv`, `cost_pin`, `prefix_member`, `token`) VALUES
	(1, 'wwww.panaloka.com', 'Panaloka Super Team', 'Jl.Ahmadyani No 782 Bandung, Indonesia', '', 'panalokasuperteam@2016', 7, 250.00, 'PM', '1@mGeniusMan!');
/*!40000 ALTER TABLE `t_general` ENABLE KEYS */;

-- Dumping structure for table panalokamatch.t_setting
CREATE TABLE IF NOT EXISTS `t_setting` (
  `setting` varchar(25) NOT NULL,
  `value` tinyint(4) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table panalokamatch.t_setting: ~1 rows (approximately)
/*!40000 ALTER TABLE `t_setting` DISABLE KEYS */;
REPLACE INTO `t_setting` (`setting`, `value`, `description`) VALUES
	('maintenance', 0, '');
/*!40000 ALTER TABLE `t_setting` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
