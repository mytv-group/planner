-- phpMyAdmin SQL Dump
-- version 4.4.13.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 06, 2015 at 07:53 PM
-- Server version: 5.5.25
-- PHP Version: 5.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `planner`
--

-- --------------------------------------------------------

--
-- Table structure for table `channel`
--

CREATE TABLE IF NOT EXISTS `channel` (
  `id` int(11) NOT NULL,
  `id_point` int(11) NOT NULL,
  `window_id` int(11) DEFAULT NULL,
  `internalId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=272 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `channel`
--

INSERT INTO `channel` (`id`, `id_point`, `window_id`, `internalId`) VALUES
(270, 11, 112, 1),
(271, 11, 113, 2);

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `duration` varchar(20) NOT NULL,
  `mime` varchar(100) NOT NULL,
  `path` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `visibility` tinyint(1) NOT NULL,
  `author` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`id`, `name`, `duration`, `mime`, `path`, `link`, `date_created`, `visibility`, `author`) VALUES
(5, '54e4fa854ee79UrokifotoshopaUrok2Rabotasosloyami..mp4', '551.635', 'video/mp4', 'Z:/home/local.rtvgroup.com.ua/public_html/spool/video/adv_all/54e4fa854ee79UrokifotoshopaUrok2Rabotasosloyami..mp4', 'http://local.rtvgroup.com.ua/spool/video/adv_all/54e4fa854ee79UrokifotoshopaUrok2Rabotasosloyami..mp4', '2015-02-18 20:48:05', 0, 'admin'),
(7, '551c47805df9fFinalmusiccompilation2014byHennessyLoungeFMpromodj.com.mp3', '3629.4530625', 'audio/mp3', 'Z:/home/local.rtvgroup.com.ua/public_html/spool/audio/bg_all/551c47805df9fFinalmusiccompilation2014byHennessyLoungeFMpromodj.com.mp3', 'http://local.rtvgroup.com.ua/spool/audio/bg_all/551c47805df9fFinalmusiccompilation2014byHennessyLoungeFMpromodj.com.mp3', '2015-04-01 19:31:17', 0, 'admin'),
(8, '551c4ef00c173Finalmusiccompilation2014byHennessyLoungeFMpromodj.com.mp3', '3629.4530625', 'audio/mp3', 'Z:/home/local.rtvgroup.com.ua/public_html/spool/audio/bg_all/551c4ef00c173Finalmusiccompilation2014byHennessyLoungeFMpromodj.com.mp3', 'http://local.rtvgroup.com.ua/spool/audio/bg_all/551c4ef00c173Finalmusiccompilation2014byHennessyLoungeFMpromodj.com.mp3', '2015-04-01 20:03:02', 1, 'admin'),
(10, '551e65305cf22AlwaysAshleyBeedleMix.mp3', '455.96734693878', 'audio/mp3', 'Z:/home/local.rtvgroup.com.ua/public_html/spool/audio/bg_all/551e65305cf22AlwaysAshleyBeedleMix.mp3', 'http://local.rtvgroup.com.ua/spool/audio/bg_all/551e65305cf22AlwaysAshleyBeedleMix.mp3', '2015-04-03 10:02:24', 0, 'admin'),
(13, '552eb2c3dcd38UrokifotoshopaUrok2Rabotasosloyami.mp4', '551.635', 'video/mp4', 'Z:/home/local.rtvgroup.com.ua/public_html/spool/video/bg_all/552eb2c3dcd38UrokifotoshopaUrok2Rabotasosloyami.mp4', 'http://local.rtvgroup.com.ua/spool/video/bg_all/552eb2c3dcd38UrokifotoshopaUrok2Rabotasosloyami.mp4', '2015-04-15 18:49:40', 0, 'admin'),
(15, '552ec497c75f7MaidwiththeFlaxenHair.mp3', '169.69141666667', 'audio/mp3', 'Z:/home/local.rtvgroup.com.ua/public_html/spool/audio/bg_all/552ec497c75f7MaidwiththeFlaxenHair.mp3', 'http://local.rtvgroup.com.ua/spool/audio/bg_all/552ec497c75f7MaidwiththeFlaxenHair.mp3', '2015-04-15 20:05:44', 0, 'admin'),
(16, '552ec49949d43SleepAway.mp3', '200.56816666667', 'audio/mp3', 'Z:/home/local.rtvgroup.com.ua/public_html/spool/audio/bg_all/552ec49949d43SleepAway.mp3', 'http://local.rtvgroup.com.ua/spool/audio/bg_all/552ec49949d43SleepAway.mp3', '2015-04-15 20:05:45', 0, 'admin'),
(17, '552ec49a50e36Kalimba.mp3', '348.0555', 'audio/mp3', 'Z:/home/local.rtvgroup.com.ua/public_html/spool/audio/bg_all/552ec49a50e36Kalimba.mp3', 'http://local.rtvgroup.com.ua/spool/audio/bg_all/552ec49a50e36Kalimba.mp3', '2015-04-15 20:05:47', 0, 'admin'),
(18, '552ecdd6ee7d4Finalmusiccompilation2014byHennessyLoungeFMpromodj.com.mp3', '3629.4530625', 'audio/mp3', '/var/www/planner/public_html/spool/audio/bg_all/552ecdd6ee7d4Finalmusiccompilation2014byHennessyLoungeFMpromodj.com.mp3', 'http://planner.rtvgroup.com.ua/spool/audio/bg_all/552ecdd6ee7d4Finalmusiccompilation2014byHennessyLoungeFMpromodj.com.mp3', '2015-04-15 20:45:11', 0, 'admin'),
(37, '555ec3492692cXzibitotvеtilnavыzovparnеyizRossii.Chtoonprokachaеt.mp4', '62.461666666667', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec3492692cXzibitotvеtilnavыzovparnеyizRossii.Chtoonprokachaеt.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec3492692cXzibitotvеtilnavыzovparnеyizRossii.Chtoonprokachaеt.mp4', '2015-05-22 05:48:57', 1, 'admin'),
(41, '555ec67d78ac2GoProCyrWheel.mp4', '66.572', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec67d78ac2GoProCyrWheel.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec67d78ac2GoProCyrWheel.mp4', '2015-05-22 06:02:37', 1, 'admin'),
(42, '555ec67db96c0GoProAndrewGeslersFrostyJerseyBarrelsGoProOfTheWorldJanuaryWinner.mp4', '70.52', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec67db96c0GoProAndrewGeslersFrostyJerseyBarrelsGoProOfTheWorldJanuaryWinner.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec67db96c0GoProAndrewGeslersFrostyJerseyBarrelsGoProOfTheWorldJanuaryWinner.mp4', '2015-05-22 06:02:37', 1, 'admin'),
(43, '555ec67e08b66GoProBarefootAirplaneWaterskiing.mp4', '93.809', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec67e08b66GoProBarefootAirplaneWaterskiing.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec67e08b66GoProBarefootAirplaneWaterskiing.mp4', '2015-05-22 06:02:38', 1, 'admin'),
(44, '555ec67e292f7GoProBienvenidosDoubleFrontflipatMartinSöderströmInvitational2014.mp4', '86.6', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec67e292f7GoProBienvenidosDoubleFrontflipatMartinSöderströmInvitational2014.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec67e292f7GoProBienvenidosDoubleFrontflipatMartinSöderströmInvitational2014.mp4', '2015-05-22 06:02:38', 1, 'admin'),
(45, '555ec67e470ccGoProDannyMacAskillRidesRotterdam.mp4', '102.853', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec67e470ccGoProDannyMacAskillRidesRotterdam.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec67e470ccGoProDannyMacAskillRidesRotterdam.mp4', '2015-05-22 06:02:38', 1, 'admin'),
(46, '555ec67e6700cGoProFurious7BehindtheScenes.mp4', '144.562', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec67e6700cGoProFurious7BehindtheScenes.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec67e6700cGoProFurious7BehindtheScenes.mp4', '2015-05-22 06:02:38', 1, 'admin'),
(47, '555ec67ecd93fGoProHelicopterSkydive.mp4', '95.93', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec67ecd93fGoProHelicopterSkydive.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec67ecd93fGoProHelicopterSkydive.mp4', '2015-05-22 06:02:38', 1, 'admin'),
(48, '555ec67f332c1GoProParaglideRopeSwingWithMatthiasGiraud.mp4', '96.108', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec67f332c1GoProParaglideRopeSwingWithMatthiasGiraud.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec67f332c1GoProParaglideRopeSwingWithMatthiasGiraud.mp4', '2015-05-22 06:02:39', 1, 'admin'),
(49, '555ec67f9bfdeGoProRCRacingHillsideRaceway.mp4', '92.56', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec67f9bfdeGoProRCRacingHillsideRaceway.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec67f9bfdeGoProRCRacingHillsideRaceway.mp4', '2015-05-22 06:02:39', 1, 'admin'),
(50, '555ec67fbfa5fGoProSkatingtheHighSierras.mp4', '118.953', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec67fbfa5fGoProSkatingtheHighSierras.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec67fbfa5fGoProSkatingtheHighSierras.mp4', '2015-05-22 06:02:39', 1, 'admin'),
(51, '555ec67fe7391GoProSwingARing.mp4', '76.944', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec67fe7391GoProSwingARing.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec67fe7391GoProSwingARing.mp4', '2015-05-22 06:02:39', 1, 'admin'),
(52, '555ec68011a2aGoProSpeedflyingThroughBuildings.mp4', '101.913', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec68011a2aGoProSpeedflyingThroughBuildings.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec68011a2aGoProSpeedflyingThroughBuildings.mp4', '2015-05-22 06:02:40', 1, 'admin'),
(53, '555ec68030a42GoProTheBASERace.mp4', '71.639', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec68030a42GoProTheBASERace.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec68030a42GoProTheBASERace.mp4', '2015-05-22 06:02:40', 1, 'admin'),
(54, '555ec680577b6GoProSkydiveSwoopNSlide.mp4', '193.214', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec680577b6GoProSkydiveSwoopNSlide.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec680577b6GoProSkydiveSwoopNSlide.mp4', '2015-05-22 06:02:40', 1, 'admin'),
(55, '555ec6807f52eGoProWarehouseTrickShotBasketball.mp4', '95.93', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec6807f52eGoProWarehouseTrickShotBasketball.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec6807f52eGoProWarehouseTrickShotBasketball.mp4', '2015-05-22 06:02:40', 1, 'admin'),
(56, '555ec680a184bGoProWaltznWithMyBikebyMatthiasDandois.mp4', '66.735', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec680a184bGoProWaltznWithMyBikebyMatthiasDandois.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec680a184bGoProWaltznWithMyBikebyMatthiasDandois.mp4', '2015-05-22 06:02:40', 1, 'admin'),
(57, '555ec680c39a2GoProTheWhipcracker.mp4', '126.549', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec680c39a2GoProTheWhipcracker.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec680c39a2GoProTheWhipcracker.mp4', '2015-05-22 06:02:40', 1, 'admin'),
(58, '555ec69025e32GoProKayakingtheStikinewithRafaOrtiz.mp4', '336.76', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec69025e32GoProKayakingtheStikinewithRafaOrtiz.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec69025e32GoProKayakingtheStikinewithRafaOrtiz.mp4', '2015-05-22 06:02:56', 1, 'admin'),
(59, '555ec690f0f1bGoProTORCMichigan2014Throwdown.mp4', '160.775', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec690f0f1bGoProTORCMichigan2014Throwdown.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec690f0f1bGoProTORCMichigan2014Throwdown.mp4', '2015-05-22 06:02:57', 1, 'admin'),
(60, '555ec6a938655GoProWildDownhillRidewithClaudioCaluori.mp4', '293.454', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec6a938655GoProWildDownhillRidewithClaudioCaluori.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec6a938655GoProWildDownhillRidewithClaudioCaluori.mp4', '2015-05-22 06:03:21', 1, 'admin'),
(62, '555ec87e68273P2015011400037MPEG4HDHigh1080p2510Mbits1.mp4', '82', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec87e68273P2015011400037MPEG4HDHigh1080p2510Mbits1.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec87e68273P2015011400037MPEG4HDHigh1080p2510Mbits1.mp4', '2015-05-22 06:11:12', 1, 'admin'),
(63, '555ec8808e3b3P2014120908464MPEG4HDHigh1080p2510Mbits.mp4', '99', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec8808e3b3P2014120908464MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec8808e3b3P2014120908464MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:11:13', 1, 'admin'),
(64, '555ec8826145fP2014102800299MPEG4HDHigh1080p2510Mbits.mp4', '115.64', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec8826145fP2014102800299MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec8826145fP2014102800299MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:11:16', 1, 'admin'),
(65, '555ec8857dd22P2014091700196MPEG4HDHigh1080p2510Mbits.mp4', '121.48', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec8857dd22P2014091700196MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec8857dd22P2014091700196MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:11:19', 1, 'admin'),
(66, '555ec88752f42P2014100800104MPEG4HDHigh1080p2510Mbits.mp4', '130.32', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec88752f42P2014100800104MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec88752f42P2014100800104MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:11:21', 1, 'admin'),
(67, '555ec8893d41dP2015013000207MPEG4HDHigh1080p2510Mbits.mp4', '71.04', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec8893d41dP2015013000207MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec8893d41dP2015013000207MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:11:22', 1, 'admin'),
(68, '555ec88a48c8aP2014092100389MPEG4HDHigh1080p2510Mbits.mp4', '171.48', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec88a48c8aP2014092100389MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec88a48c8aP2014092100389MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:11:24', 1, 'admin'),
(69, '555ec88d3d189P2015011600003MPEG4HDHigh1080p2510Mbits.mp4', '87.96', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec88d3d189P2015011600003MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec88d3d189P2015011600003MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:11:26', 1, 'admin'),
(70, '555ec88e8b3b6P2015021000073MPEG4HDHigh1080p2510Mbits.mp4', '72.36', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec88e8b3b6P2015021000073MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec88e8b3b6P2015021000073MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:11:27', 1, 'admin'),
(71, '555ec88fb17fcP2015042100141MPEG4HDHigh1080p2510Mbits.mp4', '40.08', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec88fb17fcP2015042100141MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec88fb17fcP2015042100141MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:11:28', 1, 'admin'),
(72, '555ec890e31f3P2015021600546MPEG4HDHigh1080p2510Mbits.mp4', '94.998', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec890e31f3P2015021600546MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec890e31f3P2015021600546MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:11:30', 1, 'admin'),
(73, '555ec89252636P2015042900901MPEG4HDHigh1080p2510Mbits.mp4', '84.68', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec89252636P2015042900901MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec89252636P2015042900901MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:11:31', 1, 'admin'),
(74, '555ec893b43b1P2015020500181MPEG4HDHigh1080p2510Mbits.mp4', '143', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec893b43b1P2015020500181MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec893b43b1P2015020500181MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:11:33', 1, 'admin'),
(75, '555ec8b05a8ffP2015043000066MPEG4HDHigh1080p2510Mbits.mp4', '95', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec8b05a8ffP2015043000066MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec8b05a8ffP2015043000066MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:12:00', 1, 'admin'),
(76, '555ec8fa5ea9dP2015050300419MPEG4HDHigh1080p2510Mbits.mp4', '150', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec8fa5ea9dP2015050300419MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec8fa5ea9dP2015050300419MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:13:14', 1, 'admin'),
(77, '555ec90044fc0P2015042300123MPEG4HDHigh1080p2510Mbits.mp4', '202.28', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec90044fc0P2015042300123MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec90044fc0P2015042300123MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:13:20', 1, 'admin'),
(78, '555ec93a294fbP2015050401027MPEG4HDHigh1080p2510Mbits.mp4', '185', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec93a294fbP2015050401027MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec93a294fbP2015050401027MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:14:18', 1, 'admin'),
(79, '555ec95c52f0dP2015050100006MPEG4HDHigh1080p2510Mbits.mp4', '299.36', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ec95c52f0dP2015050100006MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ec95c52f0dP2015050100006MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-22 06:14:53', 1, 'admin'),
(81, '555ecc37c4d03RTV2.mp4', '89.92', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ecc37c4d03RTV2.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ecc37c4d03RTV2.mp4', '2015-05-22 06:27:08', 1, 'admin'),
(82, '555ecdf737ac2RTV11.mp4', '90.922666666667', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ecdf737ac2RTV11.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ecdf737ac2RTV11.mp4', '2015-05-22 06:34:32', 1, 'admin'),
(83, '555ecf0515f25RTV41.mp4', '90.474666666667', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ecf0515f25RTV41.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ecf0515f25RTV41.mp4', '2015-05-22 06:39:02', 1, 'admin'),
(84, '555ecf06d6442RTV51.mp4', '90.474666666667', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ecf06d6442RTV51.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ecf06d6442RTV51.mp4', '2015-05-22 06:39:03', 1, 'admin'),
(85, '555ecf08a11ccRTV31.mp4', '91.925333333333', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ecf08a11ccRTV31.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ecf08a11ccRTV31.mp4', '2015-05-22 06:39:05', 1, 'admin'),
(86, '555ed37e2fcb0RTV9.mp4', '89.813333333333', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ed37e2fcb0RTV9.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ed37e2fcb0RTV9.mp4', '2015-05-22 06:58:07', 1, 'admin'),
(87, '555ed4ddbb498RTV71.mp4', '90.773333333333', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ed4ddbb498RTV71.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ed4ddbb498RTV71.mp4', '2015-05-22 07:03:59', 1, 'admin'),
(88, '555ed4e772941RTV81.mp4', '90.965333333333', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555ed4e772941RTV81.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555ed4e772941RTV81.mp4', '2015-05-22 07:04:11', 1, 'admin'),
(92, '555f66776f314DCJENEVA6.mp4', '157.99466666667', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555f66776f314DCJENEVA6.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555f66776f314DCJENEVA6.mp4', '2015-05-22 17:25:12', 1, 'nashakarta'),
(93, '555f6678b5abcDCJENEVA4.mp4', '150.08', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555f6678b5abcDCJENEVA4.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555f6678b5abcDCJENEVA4.mp4', '2015-05-22 17:25:13', 1, 'nashakarta'),
(94, '555f6696cbda7DCJENEVA5.mp4', '161.68533333333', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555f6696cbda7DCJENEVA5.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555f6696cbda7DCJENEVA5.mp4', '2015-05-22 17:25:43', 1, 'nashakarta'),
(95, '555f66d54b674FJEMILIOPUCCI080515.mp4', '255.36', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555f66d54b674FJEMILIOPUCCI080515.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555f66d54b674FJEMILIOPUCCI080515.mp4', '2015-05-22 17:26:47', 1, 'nashakarta'),
(96, '555f66ff504feFJALBERTOGUARDIANI070515.mp4', '210.45333333333', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555f66ff504feFJALBERTOGUARDIANI070515.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555f66ff504feFJALBERTOGUARDIANI070515.mp4', '2015-05-22 17:27:28', 1, 'nashakarta'),
(97, '555f6733df681RTV62.mp4', '92.117333333333', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555f6733df681RTV62.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555f6733df681RTV62.mp4', '2015-05-22 17:28:20', 1, 'admin'),
(98, '555f673b1ce4cFJELISABETTAFRANCHI130515.mp4', '288.128', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555f673b1ce4cFJELISABETTAFRANCHI130515.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555f673b1ce4cFJELISABETTAFRANCHI130515.mp4', '2015-05-22 17:28:29', 1, 'nashakarta'),
(99, '555f67c0b14aaFSALEXANDREDELIMA050515.mp4', '260.56533333333', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/555f67c0b14aaFSALEXANDREDELIMA050515.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/555f67c0b14aaFSALEXANDREDELIMA050515.mp4', '2015-05-22 17:30:41', 1, 'nashakarta'),
(101, '5565a5cae68fcP2014111200047MPEG4HDHigh1080p2510Mbits.mp4', '65.36', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a5cae68fcP2014111200047MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a5cae68fcP2014111200047MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:08:59', 1, 'admin'),
(102, '5565a5cbbe942P2015030200372MPEG4HDHigh1080p2510Mbits.mp4', '106.12', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a5cbbe942P2015030200372MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a5cbbe942P2015030200372MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:08:59', 1, 'admin'),
(103, '5565a5d2ea39dP2015040100121MPEG4HDHigh1080p2510Mbits.mp4', '123.32', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a5d2ea39dP2015040100121MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a5d2ea39dP2015040100121MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:09:07', 1, 'admin'),
(104, '5565a5d6dadccP2015040700456MPEG4HDHigh1080p2510Mbits.mp4', '60', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a5d6dadccP2015040700456MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a5d6dadccP2015040700456MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:09:11', 1, 'admin'),
(105, '5565a5d74a613P2013040300001MPEG4HDHigh1080p2510Mbits.mp4', '111.48', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a5d74a613P2013040300001MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a5d74a613P2013040300001MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:09:11', 1, 'admin'),
(106, '5565a5da9fc35P2015020500181MPEG4HDHigh1080p2510Mbits.mp4', '143', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a5da9fc35P2015020500181MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a5da9fc35P2015020500181MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:09:14', 1, 'admin'),
(107, '5565a5ddcab34P2014120904630MPEG4HDHigh1080p2510Mbits.mp4', '145.878', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a5ddcab34P2014120904630MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a5ddcab34P2014120904630MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:09:18', 1, 'admin'),
(108, '5565a69a9d110P2015042100141MPEG4HDHigh1080p2510Mbits.mp4', '40.08', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a69a9d110P2015042100141MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a69a9d110P2015042100141MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:12:26', 1, 'admin'),
(109, '5565a69aee7a4P2015050700174MPEG4HDHigh1080p2510Mbits.mp4', '63', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a69aee7a4P2015050700174MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a69aee7a4P2015050700174MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:12:27', 1, 'admin'),
(110, '5565a69fba6f2P2015043000066MPEG4HDHigh1080p2510Mbits.mp4', '95', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a69fba6f2P2015043000066MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a69fba6f2P2015043000066MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:12:31', 1, 'admin'),
(111, '5565a6a9b9d00P2015041200206MPEG4HDHigh1080p2510Mbits.mp4', '90.56', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a6a9b9d00P2015041200206MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a6a9b9d00P2015041200206MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:12:41', 1, 'admin'),
(112, '5565a6acb2949P2015051000332MPEG4HDHigh1080p2510Mbits.mp4', '97.76', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a6acb2949P2015051000332MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a6acb2949P2015051000332MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:12:45', 1, 'admin'),
(113, '5565a6c085200P2015051100624MPEG4HDHigh1080p2510Mbits.mp4', '181.32', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a6c085200P2015051100624MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a6c085200P2015051100624MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:13:04', 1, 'admin'),
(114, '5565a6ca96538P2015042300574MPEG4HDHigh1080p2510Mbits.mp4', '255.92', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a6ca96538P2015042300574MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a6ca96538P2015042300574MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:13:15', 1, 'admin'),
(115, '5565a712ad13cP2015051100816MPEG4HDHigh1080p2510Mbits.mp4', '42.44', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a712ad13cP2015051100816MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a712ad13cP2015051100816MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:14:26', 1, 'admin'),
(116, '5565a7131581fP2015052100103MPEG4HDHigh1080p2510Mbits.mp4', '56', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a7131581fP2015052100103MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a7131581fP2015052100103MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:14:27', 1, 'admin'),
(117, '5565a7226d6f0P2015052000265MPEG4HDHigh1080p2510Mbits.mp4', '85.24', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a7226d6f0P2015052000265MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a7226d6f0P2015052000265MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:14:42', 1, 'admin'),
(118, '5565a7273c4ceP2015052000264MPEG4HDHigh1080p2510Mbits.mp4', '99.2', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a7273c4ceP2015052000264MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a7273c4ceP2015052000264MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:14:48', 1, 'admin'),
(119, '5565a728ae163P2015051100820MPEG4HDHigh1080p2510Mbits.mp4', '87.2', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a728ae163P2015051100820MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a728ae163P2015051100820MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:14:48', 1, 'admin'),
(120, '5565a72bd5cffP2015052100323MPEG4HDHigh1080p2510Mbits.mp4', '58.92', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a72bd5cffP2015052100323MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a72bd5cffP2015052100323MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:14:51', 1, 'admin'),
(121, '5565a72d571c3P2015051805506MPEG4HDHigh1080p2510Mbits.mp4', '104', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a72d571c3P2015051805506MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a72d571c3P2015051805506MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:14:53', 1, 'admin'),
(122, '5565a8746da06P2015052600008MPEG4HDHigh1080p2510Mbits.mp4', '45.8', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a8746da06P2015052600008MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a8746da06P2015052600008MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:20:20', 1, 'admin'),
(123, '5565a875995a8P2015052600382MPEG4HDHigh1080p2510Mbits.mp4', '34.36', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a875995a8P2015052600382MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a875995a8P2015052600382MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:20:21', 1, 'admin'),
(124, '5565a878bcdabP2015052600005MPEG4HDHigh1080p2510Mbits.mp4', '72.16', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a878bcdabP2015052600005MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a878bcdabP2015052600005MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:20:25', 1, 'admin'),
(125, '5565a88162adcP2015052400423MPEG4HDHigh1080p2510Mbits.mp4', '116.64', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5565a88162adcP2015052400423MPEG4HDHigh1080p2510Mbits.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5565a88162adcP2015052400423MPEG4HDHigh1080p2510Mbits.mp4', '2015-05-27 11:20:33', 1, 'admin'),
(128, '5566f6236e1941podolsky.mov', '5', 'video/quicktime', '/var/www/planner/public_html/spool/video/bg_all/5566f6236e1941podolsky.mov', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5566f6236e1941podolsky.mov', '2015-05-28 11:04:04', 1, 'podolfitness'),
(129, '5566f6744e593amirpodolskiy.mp4', '144.23466666667', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5566f6744e593amirpodolskiy.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5566f6744e593amirpodolskiy.mp4', '2015-05-28 11:05:24', 1, 'podolfitness'),
(130, '5566f67d52849Finalfinal.mp4', '61.76', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5566f67d52849Finalfinal.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5566f67d52849Finalfinal.mp4', '2015-05-28 11:05:33', 1, 'podolfitness'),
(131, '5566f710d2078Olesyacolorfnal.mp4', '77.653333333333', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5566f710d2078Olesyacolorfnal.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5566f710d2078Olesyacolorfnal.mp4', '2015-05-28 11:08:01', 1, 'podolfitness'),
(132, '5566f7b401aceren.mov', '35.04', 'video/quicktime', '/var/www/planner/public_html/spool/video/bg_all/5566f7b401aceren.mov', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5566f7b401aceren.mov', '2015-05-28 11:10:44', 1, 'podolfitness'),
(133, '5566f85102fa5podolskiytriatlonlong1.mp4', '72.96', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5566f85102fa5podolskiytriatlonlong1.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5566f85102fa5podolskiytriatlonlong1.mp4', '2015-05-28 11:13:21', 1, 'podolfitness'),
(134, '5566f9450f52cpodolskiytraningpart3.mp4', '100.16', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5566f9450f52cpodolskiytraningpart3.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5566f9450f52cpodolskiytraningpart3.mp4', '2015-05-28 11:17:25', 1, 'podolfitness'),
(135, '5566f9480ab49Tran1.mp4', '151.04', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5566f9480ab49Tran1.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5566f9480ab49Tran1.mp4', '2015-05-28 11:17:28', 1, 'podolfitness'),
(136, '5566f9495042eTran2.mp4', '145.81333333333', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5566f9495042eTran2.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5566f9495042eTran2.mp4', '2015-05-28 11:17:29', 1, 'podolfitness'),
(137, '5566f9856e938podolskystepnew56.5m1.44c.mp4', '104.55466666667', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/5566f9856e938podolskystepnew56.5m1.44c.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/5566f9856e938podolskystepnew56.5m1.44c.mp4', '2015-05-28 11:18:29', 1, 'podolfitness'),
(138, '556701ac03cbfpodolskyportdebras57.6m1.48c.mp4', '108.43733333333', 'video/mp4', '/var/www/planner/public_html/spool/video/bg_all/556701ac03cbfpodolskyportdebras57.6m1.48c.mp4', 'http://planner.rtvgroup.com.ua/spool/video/bg_all/556701ac03cbfpodolskyportdebras57.6m1.48c.mp4', '2015-05-28 11:53:16', 1, 'podolfitness'),
(146, '55ebf7b73b797marks.mp4', '40.006666666667', 'video/mp4', 'Z:/home/local.planner.rtvgroup.com.ua/public_html/spool/video/bg_all/55ebf7b73b797marks.mp4', 'http://local.planner.rtvgroup.com.ua/spool/video/bg_all/55ebf7b73b797marks.mp4', '2015-09-06 08:22:15', 0, 'admin'),
(149, '55ec4f783dc10eset.mp4', '45', 'video/mp4', 'Z:/home/local.planner.rtvgroup.com.ua/public_html/spool/video/bg_all/55ec4f783dc10eset.mp4', 'http://local.planner.rtvgroup.com.ua/spool/video/bg_all/55ec4f783dc10eset.mp4', '2015-09-06 14:36:40', 0, 'admin'),
(150, '55ec4f7a6888amarks.mp4', '40.006666666667', 'video/mp4', 'Z:/home/local.planner.rtvgroup.com.ua/public_html/spool/video/bg_all/55ec4f7a6888amarks.mp4', 'http://local.planner.rtvgroup.com.ua/spool/video/bg_all/55ec4f7a6888amarks.mp4', '2015-09-06 14:36:42', 0, 'admin'),
(151, '55ec4f7eee025ducati.mp4', '122.57666666667', 'video/mp4', 'Z:/home/local.planner.rtvgroup.com.ua/public_html/spool/video/bg_all/55ec4f7eee025ducati.mp4', 'http://local.planner.rtvgroup.com.ua/spool/video/bg_all/55ec4f7eee025ducati.mp4', '2015-09-06 14:36:47', 0, 'admin'),
(152, '55ec5e1bc05f6McDonaldsWhatitis.QuarterPounderwithCheese..mp4', '30', 'video/mp4', 'Z:/home/local.planner.rtvgroup.com.ua/public_html/spool/video/bg_all/55ec5e1bc05f6McDonaldsWhatitis.QuarterPounderwithCheese..mp4', 'http://local.planner.rtvgroup.com.ua/spool/video/bg_all/55ec5e1bc05f6McDonaldsWhatitis.QuarterPounderwithCheese..mp4', '2015-09-06 15:39:08', 0, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `folder`
--

CREATE TABLE IF NOT EXISTS `folder` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` int(11) NOT NULL,
  `author` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `folder`
--

INSERT INTO `folder` (`id`, `name`, `path`, `author`) VALUES
(36, 'GoPro', 0, 'admin'),
(38, 'RedBull', 0, 'admin'),
(39, 'ID FASHION', 0, 'admin'),
(40, '22.05.2015', 36, 'admin'),
(61, '22.05.2015', 38, 'admin'),
(80, '22.05.2015', 39, 'admin'),
(91, 'HD Fashion', 0, 'nashakarta'),
(100, '27.05.2015', 38, 'admin'),
(126, 'New node', 0, 'national'),
(127, 'podolskiy', 0, 'podolfitness'),
(139, 'New node', 0, 'diamondtv');

-- --------------------------------------------------------

--
-- Table structure for table `net`
--

CREATE TABLE IF NOT EXISTS `net` (
  `id` int(5) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `screen_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `net_channel`
--

CREATE TABLE IF NOT EXISTS `net_channel` (
  `id` int(11) NOT NULL,
  `net_id` int(11) NOT NULL,
  `window_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `playlists`
--

CREATE TABLE IF NOT EXISTS `playlists` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `files` text NOT NULL,
  `fromDatetime` datetime NOT NULL,
  `toDatetime` datetime NOT NULL,
  `fromTime` time NOT NULL,
  `toTime` time NOT NULL,
  `type` tinyint(4) NOT NULL,
  `every` time NOT NULL,
  `sun` tinyint(1) NOT NULL,
  `mon` tinyint(1) NOT NULL,
  `tue` tinyint(1) NOT NULL,
  `wed` tinyint(1) NOT NULL,
  `thu` tinyint(1) NOT NULL,
  `fri` tinyint(1) NOT NULL,
  `sat` tinyint(1) NOT NULL,
  `author` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `playlists`
--

INSERT INTO `playlists` (`id`, `name`, `files`, `fromDatetime`, `toDatetime`, `fromTime`, `toTime`, `type`, `every`, `sun`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `author`) VALUES
(5, 'AAA', '146', '2015-04-17 15:00:00', '2015-09-30 17:08:07', '01:00:39', '23:00:43', 0, '00:10:00', 1, 1, 1, 1, 1, 1, 1, 'admin'),
(16, 'Подольский 1', '152', '2015-05-27 07:00:34', '2015-09-30 04:00:39', '00:00:42', '23:00:45', 1, '00:30:00', 1, 1, 1, 1, 1, 1, 1, 'admin'),
(17, 'bbb', '149,150,151', '2015-08-01 17:36:07', '2015-09-30 17:36:10', '01:00:14', '23:00:19', 0, '00:30:00', 1, 1, 1, 1, 1, 1, 1, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `playlist_to_channel`
--

CREATE TABLE IF NOT EXISTS `playlist_to_channel` (
  `channelId` int(11) NOT NULL,
  `playlistId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `playlist_to_channel`
--

INSERT INTO `playlist_to_channel` (`channelId`, `playlistId`) VALUES
(270, 17),
(271, 16),
(271, 17);

-- --------------------------------------------------------

--
-- Table structure for table `playlist_to_net_channel`
--

CREATE TABLE IF NOT EXISTS `playlist_to_net_channel` (
  `playlist_id` int(11) NOT NULL,
  `net_channel_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `point`
--

CREATE TABLE IF NOT EXISTS `point` (
  `id` int(5) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(100) NOT NULL,
  `sync_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `volume` smallint(11) NOT NULL,
  `TV` tinyint(1) NOT NULL,
  `tv_schedule_blocks` text NOT NULL,
  `screen_id` int(11) DEFAULT NULL,
  `sync` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `point`
--

INSERT INTO `point` (`id`, `name`, `username`, `password`, `ip`, `sync_time`, `update_time`, `volume`, `TV`, `tv_schedule_blocks`, `screen_id`, `sync`) VALUES
(11, 'Test1', 'admin', '1', '172.20.1.11', '2015-06-15 22:50:22', '2015-09-06 18:39:42', 100, 0, '', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `point_to_net`
--

CREATE TABLE IF NOT EXISTS `point_to_net` (
  `id` int(11) NOT NULL,
  `point_id` int(11) NOT NULL,
  `net_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `point_to_user`
--

CREATE TABLE IF NOT EXISTS `point_to_user` (
  `id` int(11) NOT NULL,
  `point_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `point_to_user`
--

INSERT INTO `point_to_user` (`id`, `point_id`, `user_id`, `time`) VALUES
(1, 11, 1, '2015-07-12 18:16:40');

-- --------------------------------------------------------

--
-- Table structure for table `relations`
--

CREATE TABLE IF NOT EXISTS `relations` (
  `id` int(11) NOT NULL,
  `type` varchar(200) NOT NULL,
  `requester` int(11) NOT NULL,
  `target` int(11) NOT NULL,
  `allowedBy` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `relations`
--

INSERT INTO `relations` (`id`, `type`, `requester`, `target`, `allowedBy`) VALUES
(5, 'fileToFolder', 37, 0, 'admin'),
(26, 'fileToFolder', 41, 40, 'admin'),
(27, 'fileToFolder', 42, 40, 'admin'),
(28, 'fileToFolder', 43, 40, 'admin'),
(29, 'fileToFolder', 44, 40, 'admin'),
(30, 'fileToFolder', 45, 40, 'admin'),
(31, 'fileToFolder', 46, 40, 'admin'),
(32, 'fileToFolder', 47, 40, 'admin'),
(33, 'fileToFolder', 48, 40, 'admin'),
(34, 'fileToFolder', 49, 40, 'admin'),
(35, 'fileToFolder', 50, 40, 'admin'),
(36, 'fileToFolder', 51, 40, 'admin'),
(37, 'fileToFolder', 52, 40, 'admin'),
(38, 'fileToFolder', 53, 40, 'admin'),
(39, 'fileToFolder', 54, 40, 'admin'),
(40, 'fileToFolder', 55, 40, 'admin'),
(41, 'fileToFolder', 56, 40, 'admin'),
(42, 'fileToFolder', 57, 40, 'admin'),
(43, 'fileToFolder', 58, 40, 'admin'),
(44, 'fileToFolder', 59, 40, 'admin'),
(45, 'fileToFolder', 60, 40, 'admin'),
(46, 'fileToFolder', 62, 61, 'admin'),
(47, 'fileToFolder', 63, 61, 'admin'),
(48, 'fileToFolder', 64, 61, 'admin'),
(49, 'fileToFolder', 65, 61, 'admin'),
(50, 'fileToFolder', 66, 61, 'admin'),
(51, 'fileToFolder', 67, 61, 'admin'),
(52, 'fileToFolder', 68, 61, 'admin'),
(53, 'fileToFolder', 69, 61, 'admin'),
(54, 'fileToFolder', 70, 61, 'admin'),
(55, 'fileToFolder', 71, 61, 'admin'),
(56, 'fileToFolder', 72, 61, 'admin'),
(57, 'fileToFolder', 73, 61, 'admin'),
(58, 'fileToFolder', 74, 61, 'admin'),
(59, 'fileToFolder', 75, 61, 'admin'),
(60, 'fileToFolder', 76, 61, 'admin'),
(61, 'fileToFolder', 77, 61, 'admin'),
(62, 'fileToFolder', 78, 61, 'admin'),
(63, 'fileToFolder', 79, 61, 'admin'),
(64, 'fileToFolder', 81, 80, 'admin'),
(65, 'fileToFolder', 82, 80, 'admin'),
(66, 'fileToFolder', 83, 80, 'admin'),
(67, 'fileToFolder', 84, 80, 'admin'),
(68, 'fileToFolder', 85, 80, 'admin'),
(69, 'fileToFolder', 86, 80, 'admin'),
(70, 'fileToFolder', 87, 80, 'admin'),
(71, 'fileToFolder', 88, 80, 'admin'),
(74, 'fileToFolder', 92, 91, 'nashakarta'),
(75, 'fileToFolder', 93, 91, 'nashakarta'),
(76, 'fileToFolder', 94, 91, 'nashakarta'),
(77, 'fileToFolder', 95, 91, 'nashakarta'),
(78, 'fileToFolder', 96, 91, 'nashakarta'),
(79, 'fileToFolder', 97, 80, 'admin'),
(80, 'fileToFolder', 98, 91, 'nashakarta'),
(81, 'fileToFolder', 99, 91, 'nashakarta'),
(82, 'fileToFolder', 101, 100, 'admin'),
(83, 'fileToFolder', 102, 100, 'admin'),
(84, 'fileToFolder', 103, 100, 'admin'),
(85, 'fileToFolder', 104, 100, 'admin'),
(86, 'fileToFolder', 105, 100, 'admin'),
(87, 'fileToFolder', 106, 100, 'admin'),
(88, 'fileToFolder', 107, 100, 'admin'),
(89, 'fileToFolder', 108, 100, 'admin'),
(90, 'fileToFolder', 109, 100, 'admin'),
(91, 'fileToFolder', 110, 100, 'admin'),
(92, 'fileToFolder', 111, 100, 'admin'),
(93, 'fileToFolder', 112, 100, 'admin'),
(94, 'fileToFolder', 113, 100, 'admin'),
(95, 'fileToFolder', 114, 100, 'admin'),
(96, 'fileToFolder', 115, 100, 'admin'),
(97, 'fileToFolder', 116, 100, 'admin'),
(98, 'fileToFolder', 117, 100, 'admin'),
(99, 'fileToFolder', 118, 100, 'admin'),
(100, 'fileToFolder', 119, 100, 'admin'),
(101, 'fileToFolder', 120, 100, 'admin'),
(102, 'fileToFolder', 121, 100, 'admin'),
(103, 'fileToFolder', 122, 100, 'admin'),
(104, 'fileToFolder', 123, 100, 'admin'),
(105, 'fileToFolder', 124, 100, 'admin'),
(106, 'fileToFolder', 125, 100, 'admin'),
(107, 'fileToFolder', 128, 127, 'podolfitness'),
(108, 'fileToFolder', 129, 127, 'podolfitness'),
(109, 'fileToFolder', 130, 127, 'podolfitness'),
(110, 'fileToFolder', 131, 127, 'podolfitness'),
(111, 'fileToFolder', 132, 127, 'podolfitness'),
(112, 'fileToFolder', 133, 127, 'podolfitness'),
(113, 'fileToFolder', 134, 127, 'podolfitness'),
(114, 'fileToFolder', 135, 127, 'podolfitness'),
(115, 'fileToFolder', 136, 127, 'podolfitness'),
(116, 'fileToFolder', 137, 127, 'podolfitness'),
(117, 'fileToFolder', 138, 127, 'podolfitness');

-- --------------------------------------------------------

--
-- Table structure for table `screen`
--

CREATE TABLE IF NOT EXISTS `screen` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `width` mediumint(9) NOT NULL,
  `height` mediumint(9) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `screen`
--

INSERT INTO `screen` (`id`, `name`, `width`, `height`, `user_id`) VALUES
(1, 'S1', 1000, 1000, 1),
(2, 'Screen 1', 1680, 1050, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tvschedule`
--

CREATE TABLE IF NOT EXISTS `tvschedule` (
  `id` int(11) NOT NULL,
  `point_id` int(11) NOT NULL,
  `from` datetime NOT NULL,
  `to` datetime NOT NULL,
  `author` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=207 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tvschedule`
--

INSERT INTO `tvschedule` (`id`, `point_id`, `from`, `to`, `author`) VALUES
(100, 10, '2015-04-01 23:08:00', '2015-04-03 23:08:00', 'admin'),
(101, 10, '2015-04-01 00:07:00', '2015-04-01 00:07:00', 'admin'),
(185, 14, '2015-05-20 18:30:59', '2015-05-22 17:10:06', 'admin'),
(203, 26, '2015-05-26 07:00:00', '2015-06-06 23:00:40', 'podolfitness'),
(206, 15, '2015-05-28 20:27:05', '2015-05-30 23:00:14', 'nashakarta');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(5) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `blocked` int(1) NOT NULL DEFAULT '0',
  `role` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `name`, `blocked`, `role`) VALUES
(1, 'admin', '964d72e72d053d501f2949969849b96c', 'admin', 0, 'admin'),
(7, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'user', 0, 'admin'),
(8, 'podolfitness', '4ef489c645e197299642368bbe461e53', 'podolfitness', 0, 'halfDemo'),
(9, 'diamondtv', 'd5878140631154cc07987db3b03872a3', 'Diamond Tv admin', 0, 'admin'),
(10, 'test', '098f6bcd4621d373cade4e832627b4f6', 'roman', 0, 'admin'),
(11, 'testUserWithPassTEST', '033bd94b1168d7e4f0d644c3c95e35bf', 'testUser', 0, 'admin'),
(12, 'tmmukraine', 'd28c7374179ba587a46647c7fd465924', 'TMM', 0, 'admin'),
(13, 'nashakarta', '0ac080beb29b14683c4b21b0aecf750b', 'nashakarta', 0, 'halfDemo'),
(14, 'diamondtvzh', '4b69e35cf31953162cd7f93d61f3160e', 'diamondtvzh', 0, 'halfDemo'),
(15, 'blockbuster', 'fbf1d24f2e78c7abd99593125bd2ca47', 'blockbuster', 0, 'halfDemo'),
(17, 'national', 'f82d71a6491c31b384ab5b57d85b6593', 'national', 0, 'halfDemo');

-- --------------------------------------------------------

--
-- Table structure for table `widget`
--

CREATE TABLE IF NOT EXISTS `widget` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `widget`
--

INSERT INTO `widget` (`id`, `name`, `content`, `user_id`, `created_dt`, `updated_dt`) VALUES
(3, 'Some widget 1', '<div style=''position:absolute; left:0px; bottom:0px; width:100%; text-align:center; font-size:50px; z-index:100; border:0px; background-color: transparant;''>\r\n                <INPUT TYPE="text" SIZE="130" id="ctc">\r\n</div>\r\n<script>\r\nvar line = "Some text Some text Some text Some text",\r\n                speed = 200, i = 0;\r\n\r\n            (function m_line() {\r\n                if ( i++ < line.length ) {\r\n                    document.getElementById(''ctc'').value = line.substring( 0, i );\r\n                } else{\r\n                     document.getElementById(''ctc'').value = " ";\r\n                    i = 0;\r\n                }\r\n                setTimeout( m_line, speed );\r\n            })();\r\n\r\n</script>', 1, '2015-09-06 10:11:50', '2015-09-06 16:51:35'),
(4, 'Some widget 2', '<img src=''http://i94.beon.ru/12/82/1988212/64/65650364/58gkz.jpeg'' style=''width:100%; height:100%; z-index:-1''/>', 1, '2015-09-06 10:11:58', '2015-09-06 16:47:47');

-- --------------------------------------------------------

--
-- Table structure for table `widget_to_channel`
--

CREATE TABLE IF NOT EXISTS `widget_to_channel` (
  `id` int(11) NOT NULL,
  `widget_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `widget_to_channel`
--

INSERT INTO `widget_to_channel` (`id`, `widget_id`, `channel_id`) VALUES
(12, 3, 270),
(13, 4, 271);

-- --------------------------------------------------------

--
-- Table structure for table `window`
--

CREATE TABLE IF NOT EXISTS `window` (
  `id` int(11) NOT NULL,
  `screen_id` int(11) DEFAULT NULL,
  `name` varchar(25) NOT NULL,
  `top` int(11) NOT NULL,
  `left` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `authorId` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `window`
--

INSERT INTO `window` (`id`, `screen_id`, `name`, `top`, `left`, `width`, `height`, `authorId`) VALUES
(110, 1, 'b1', 0, 0, 471, 995, 1),
(111, 1, 'b2', 0, 472, 297, 995, 1),
(112, 2, 'b1', 0, 0, 686, 1045, 1),
(113, 2, 'b2', 0, 688, 989, 1045, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `channel`
--
ALTER TABLE `channel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_point` (`id_point`),
  ADD KEY `screen_id` (`window_id`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `folder`
--
ALTER TABLE `folder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `net`
--
ALTER TABLE `net`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `screen_id` (`screen_id`);

--
-- Indexes for table `net_channel`
--
ALTER TABLE `net_channel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `net_id` (`net_id`),
  ADD KEY `window_id` (`window_id`);

--
-- Indexes for table `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `playlist_to_channel`
--
ALTER TABLE `playlist_to_channel`
  ADD KEY `channelId` (`channelId`),
  ADD KEY `playlistId` (`playlistId`);

--
-- Indexes for table `playlist_to_net_channel`
--
ALTER TABLE `playlist_to_net_channel`
  ADD KEY `net_channel_id` (`net_channel_id`),
  ADD KEY `playlist_id` (`playlist_id`);

--
-- Indexes for table `point`
--
ALTER TABLE `point`
  ADD PRIMARY KEY (`id`),
  ADD KEY `screen_id` (`screen_id`);

--
-- Indexes for table `point_to_net`
--
ALTER TABLE `point_to_net`
  ADD PRIMARY KEY (`id`),
  ADD KEY `point_id` (`point_id`,`net_id`),
  ADD KEY `net_id` (`net_id`);

--
-- Indexes for table `point_to_user`
--
ALTER TABLE `point_to_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `point_id` (`point_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `relations`
--
ALTER TABLE `relations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `screen`
--
ALTER TABLE `screen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `autorId` (`user_id`),
  ADD KEY `autorId_2` (`user_id`);

--
-- Indexes for table `tvschedule`
--
ALTER TABLE `tvschedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `widget`
--
ALTER TABLE `widget`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_3` (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_2` (`id`),
  ADD KEY `id_4` (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `widget_to_channel`
--
ALTER TABLE `widget_to_channel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `widget_id` (`widget_id`),
  ADD KEY `channel_id` (`channel_id`);

--
-- Indexes for table `window`
--
ALTER TABLE `window`
  ADD PRIMARY KEY (`id`),
  ADD KEY `screen_id` (`screen_id`),
  ADD KEY `authorId` (`authorId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `channel`
--
ALTER TABLE `channel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=272;
--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=153;
--
-- AUTO_INCREMENT for table `folder`
--
ALTER TABLE `folder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=140;
--
-- AUTO_INCREMENT for table `net`
--
ALTER TABLE `net`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `net_channel`
--
ALTER TABLE `net_channel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `playlists`
--
ALTER TABLE `playlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `point`
--
ALTER TABLE `point`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT for table `point_to_net`
--
ALTER TABLE `point_to_net`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `point_to_user`
--
ALTER TABLE `point_to_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `relations`
--
ALTER TABLE `relations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=118;
--
-- AUTO_INCREMENT for table `screen`
--
ALTER TABLE `screen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tvschedule`
--
ALTER TABLE `tvschedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=207;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `widget`
--
ALTER TABLE `widget`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `widget_to_channel`
--
ALTER TABLE `widget_to_channel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `window`
--
ALTER TABLE `window`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=114;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `channel`
--
ALTER TABLE `channel`
  ADD CONSTRAINT `channel_ibfk_1` FOREIGN KEY (`id_point`) REFERENCES `point` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `channel_ibfk_2` FOREIGN KEY (`window_id`) REFERENCES `window` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `net`
--
ALTER TABLE `net`
  ADD CONSTRAINT `net_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `net_ibfk_2` FOREIGN KEY (`screen_id`) REFERENCES `screen` (`id`);

--
-- Constraints for table `net_channel`
--
ALTER TABLE `net_channel`
  ADD CONSTRAINT `net_channel_ibfk_1` FOREIGN KEY (`net_id`) REFERENCES `net` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `net_channel_ibfk_2` FOREIGN KEY (`window_id`) REFERENCES `window` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `playlist_to_channel`
--
ALTER TABLE `playlist_to_channel`
  ADD CONSTRAINT `playlist_to_channel_ibfk_1` FOREIGN KEY (`channelId`) REFERENCES `channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `playlist_to_channel_ibfk_3` FOREIGN KEY (`playlistId`) REFERENCES `playlists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `playlist_to_net_channel`
--
ALTER TABLE `playlist_to_net_channel`
  ADD CONSTRAINT `playlist_to_net_channel_ibfk_1` FOREIGN KEY (`playlist_id`) REFERENCES `playlists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `playlist_to_net_channel_ibfk_2` FOREIGN KEY (`net_channel_id`) REFERENCES `net_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `point_to_net`
--
ALTER TABLE `point_to_net`
  ADD CONSTRAINT `point_to_net_ibfk_1` FOREIGN KEY (`net_id`) REFERENCES `net` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `point_to_net_ibfk_2` FOREIGN KEY (`point_id`) REFERENCES `point` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `point_to_user`
--
ALTER TABLE `point_to_user`
  ADD CONSTRAINT `point_to_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `point_to_user_ibfk_2` FOREIGN KEY (`point_id`) REFERENCES `point` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `screen`
--
ALTER TABLE `screen`
  ADD CONSTRAINT `screen_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `widget`
--
ALTER TABLE `widget`
  ADD CONSTRAINT `widget_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `widget_to_channel`
--
ALTER TABLE `widget_to_channel`
  ADD CONSTRAINT `widget_to_channel_ibfk_1` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `widget_to_channel_ibfk_2` FOREIGN KEY (`channel_id`) REFERENCES `channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `window`
--
ALTER TABLE `window`
  ADD CONSTRAINT `window_ibfk_1` FOREIGN KEY (`screen_id`) REFERENCES `screen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `window_ibfk_2` FOREIGN KEY (`authorId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
