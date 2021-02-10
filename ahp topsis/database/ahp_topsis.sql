/*
SQLyog Ultimate v9.50 
MySQL - 5.5.5-10.1.29-MariaDB : Database - ahp_topsis
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`ahp_topsis` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `ahp_topsis`;

/*Table structure for table `tb_alternatif` */

DROP TABLE IF EXISTS `tb_alternatif`;

CREATE TABLE `tb_alternatif` (
  `kode_alternatif` varchar(16) NOT NULL,
  `nama_alternatif` varchar(256) NOT NULL DEFAULT '',
  `keterangan` varchar(256) NOT NULL DEFAULT '',
  `total` double NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_alternatif`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tb_alternatif` */

insert  into `tb_alternatif`(`kode_alternatif`,`nama_alternatif`,`keterangan`,`total`,`rank`) values ('A1','Alternatif 1','-',0.5750575700854097,2),('A2','Alternatif 2','-',0.11131416805166411,3),('A3','Alternatif 3','-',0.7604891633037417,1);

/*Table structure for table `tb_kriteria` */

DROP TABLE IF EXISTS `tb_kriteria`;

CREATE TABLE `tb_kriteria` (
  `kode_kriteria` varchar(16) NOT NULL,
  `nama_kriteria` varchar(256) NOT NULL,
  `atribut` varchar(256) NOT NULL DEFAULT 'benefit',
  PRIMARY KEY (`kode_kriteria`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tb_kriteria` */

insert  into `tb_kriteria`(`kode_kriteria`,`nama_kriteria`,`atribut`) values ('C04-KAM','Kamera','benefit'),('C05-SO','Sistem Operasi','benefit'),('C03-MUS','Musik','benefit'),('C02-HAR','Harga','cost'),('C01-MER','Merek','benefit');

/*Table structure for table `tb_rel_alternatif` */

DROP TABLE IF EXISTS `tb_rel_alternatif`;

CREATE TABLE `tb_rel_alternatif` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `kode_alternatif` varchar(16) DEFAULT NULL,
  `kode_kriteria` varchar(16) DEFAULT NULL,
  `nilai` double DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=148 DEFAULT CHARSET=latin1;

/*Data for the table `tb_rel_alternatif` */

insert  into `tb_rel_alternatif`(`ID`,`kode_alternatif`,`kode_kriteria`,`nilai`) values (76,'A2','C05-SO',5),(75,'A2','C04-KAM',5),(74,'A2','C03-MUS',5),(73,'A2','C02-HAR',5),(72,'A2','C01-MER',5),(62,'A1','C05-SO',7),(61,'A1','C04-KAM',8),(60,'A1','C03-MUS',4),(59,'A1','C02-HAR',3),(58,'A1','C01-MER',7),(104,'A3','C05-SO',7),(103,'A3','C04-KAM',9),(102,'A3','C03-MUS',9),(101,'A3','C02-HAR',4),(100,'A3','C01-MER',7);

/*Table structure for table `tb_rel_kriteria` */

DROP TABLE IF EXISTS `tb_rel_kriteria`;

CREATE TABLE `tb_rel_kriteria` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID1` varchar(16) DEFAULT NULL,
  `ID2` varchar(16) DEFAULT NULL,
  `nilai` double DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=437 DEFAULT CHARSET=latin1;

/*Data for the table `tb_rel_kriteria` */

insert  into `tb_rel_kriteria`(`ID`,`ID1`,`ID2`,`nilai`) values (145,'C05-SO','C01-MER',0.25),(126,'C03-MUS','C01-MER',0.333333333),(144,'C03-MUS','C04-KAM',2),(129,'C01-MER','C03-MUS',3),(142,'C01-MER','C04-KAM',3),(143,'C02-HAR','C04-KAM',3),(128,'C03-MUS','C03-MUS',1),(130,'C02-HAR','C03-MUS',2),(127,'C03-MUS','C02-HAR',0.5),(124,'C02-HAR','C02-HAR',1),(125,'C01-MER','C02-HAR',2),(122,'C01-MER','C01-MER',1),(123,'C02-HAR','C01-MER',0.5),(141,'C04-KAM','C04-KAM',1),(140,'C04-KAM','C03-MUS',0.5),(139,'C04-KAM','C02-HAR',0.333333333),(138,'C04-KAM','C01-MER',0.333333333),(146,'C05-SO','C02-HAR',0.333333333),(147,'C05-SO','C03-MUS',0.333333333),(148,'C05-SO','C04-KAM',1),(149,'C05-SO','C05-SO',1),(150,'C01-MER','C05-SO',4),(151,'C02-HAR','C05-SO',3),(152,'C03-MUS','C05-SO',3),(153,'C04-KAM','C05-SO',1);

/*Table structure for table `tb_user` */

DROP TABLE IF EXISTS `tb_user`;

CREATE TABLE `tb_user` (
  `user` varchar(16) DEFAULT NULL,
  `pass` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tb_user` */

insert  into `tb_user`(`user`,`pass`) values ('admin','ADMIN');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
