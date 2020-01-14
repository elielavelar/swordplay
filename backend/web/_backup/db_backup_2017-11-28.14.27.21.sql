-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------
-- -------------------------------------------
-- TABLE `holidays`
-- -------------------------------------------
-- DROP TABLE IF EXISTS `holidays`;
CREATE TABLE IF NOT EXISTS `holidays` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Description` text COLLATE utf8_spanish_ci,
  `IdType` int(11) NOT NULL,
  `IdState` int(11) NOT NULL,
  `DateStart` date NOT NULL,
  `DateEnd` date NOT NULL,
  `IdFrequencyType` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdType` (`IdType`),
  KEY `IdState` (`IdState`),
  KEY `IdFrequencyType` (`IdFrequencyType`),
  CONSTRAINT `holidays_ibfk_1` FOREIGN KEY (`IdType`) REFERENCES `type` (`Id`),
  CONSTRAINT `holidays_ibfk_2` FOREIGN KEY (`IdState`) REFERENCES `state` (`Id`),
  CONSTRAINT `holidays_ibfk_3` FOREIGN KEY (`IdFrequencyType`) REFERENCES `type` (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- -------------------------------------------
-- INSERT OF DATA FROM holidays
-- -------------------------------------------
INSERT INTO holidays(`Id`,`Name`,`Description`,`IdType`,`IdState`,`DateStart`,`DateEnd`,`IdFrequencyType`) 
 VALUES ('1','Año Nuevo','Día Feriado','26','29','2018-01-01','2018-01-01','27'),
('2','Navidad',NULL,'26','29','2017-12-25','2017-12-31','27'); 

-- -------------------------------------------
-- TABLE `holidaysdetails`
-- -------------------------------------------
-- DROP TABLE IF EXISTS `holidaysdetails`;
CREATE TABLE IF NOT EXISTS `holidaysdetails` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `IdHoliday` int(11) NOT NULL,
  `IdServiceCentre` int(11) NOT NULL,
  `IdState` int(11) NOT NULL,
  `CustomDate` tinyint(1) NOT NULL DEFAULT '0',
  `DateStart` date DEFAULT NULL,
  `DateEnd` date DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdState` (`IdState`),
  KEY `IdHoliday` (`IdHoliday`),
  KEY `IdServiceCentre` (`IdServiceCentre`),
  CONSTRAINT `holidaysdetails_ibfk_1` FOREIGN KEY (`IdState`) REFERENCES `state` (`Id`),
  CONSTRAINT `holidaysdetails_ibfk_2` FOREIGN KEY (`IdHoliday`) REFERENCES `holidays` (`Id`),
  CONSTRAINT `holidaysdetails_ibfk_3` FOREIGN KEY (`IdServiceCentre`) REFERENCES `servicecentres` (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- -------------------------------------------
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------
