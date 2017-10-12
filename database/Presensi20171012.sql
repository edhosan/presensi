-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: presensi
-- ------------------------------------------------------
-- Server version	5.6.12-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `dispensasi`
--

DROP TABLE IF EXISTS `dispensasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dispensasi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `peg_id` int(10) unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `koreksi_jam` time NOT NULL,
  `alasan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dispensasi_peg_id_foreign` (`peg_id`),
  CONSTRAINT `dispensasi_peg_id_foreign` FOREIGN KEY (`peg_id`) REFERENCES `peg_data_induk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dispensasi`
--

LOCK TABLES `dispensasi` WRITE;
/*!40000 ALTER TABLE `dispensasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `dispensasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
INSERT INTO `event` VALUES (3,'Tahun Baru 2017 Masehi','2017-01-01 00:00:00','2017-01-01 00:00:00','2017-06-17 09:47:02','2017-06-17 09:47:02'),(4,'Tahun Baru Imlek 2568 Kongzili','2017-01-28 00:00:00','2017-01-28 00:00:00','2017-06-17 09:48:16','2017-06-17 09:48:16'),(5,'Hari Raya Nyepi Tahun Baru Saka 1939','2017-03-28 00:00:00','2017-03-28 00:00:00','2017-06-17 09:49:28','2017-10-08 22:48:51'),(6,'Hari Raya Idul Fitri 1438 Hijriyah','2017-06-25 00:00:00','2017-06-26 00:00:00','2017-06-17 10:08:18','2017-10-08 22:54:28'),(7,'Wafat Isa Almasih','2017-04-14 00:00:00','2017-04-14 00:00:00','2017-10-08 22:50:06','2017-10-08 22:50:06'),(8,'Isra Mikraj Nabi Muhammad SAW','2017-04-24 00:00:00','2017-04-24 00:00:00','2017-10-08 22:51:02','2017-10-08 22:51:02'),(9,'Hari Buruh International','2017-05-01 00:00:00','2017-05-01 00:00:00','2017-10-08 22:51:50','2017-10-08 22:51:50'),(10,'Hari Raya Waisak 2561','2017-05-11 00:00:00','2017-05-11 00:00:00','2017-10-08 22:52:35','2017-10-08 22:52:35'),(11,'Kenaikan Isa Al-Masih','2017-05-25 00:00:00','2017-05-25 00:00:00','2017-10-08 22:53:08','2017-10-08 22:53:08'),(12,'Hari Lahir Pancasila','2017-06-01 00:00:00','2017-06-01 00:00:00','2017-10-08 22:53:51','2017-10-08 22:53:51'),(13,'Hari Kemerdekaan Republik Indonesia','2017-08-17 00:00:00','2017-08-17 00:00:00','2017-10-08 22:55:13','2017-10-08 22:55:13'),(14,'Hari Raya Idul Adha 1438 Hijriyah','2017-09-01 00:00:00','2017-09-01 00:00:00','2017-10-08 22:58:07','2017-10-08 22:58:07'),(15,'Tahun Baru Islam 1439 Hijriyah','2017-09-21 00:00:00','2017-09-21 00:00:00','2017-10-08 22:58:58','2017-10-08 22:58:58'),(16,'Maulid Nabi Muhammad SAW','2017-12-01 00:00:00','2017-12-01 00:00:00','2017-10-08 22:59:42','2017-10-08 22:59:42'),(17,'Hari Raya Natal','2017-12-25 00:00:00','2017-12-25 00:00:00','2017-10-08 23:00:07','2017-10-08 23:00:07'),(18,'Cuti Bersama Tahun Baru 2017 Masehi','2017-01-02 00:00:00','2017-01-02 00:00:00','2017-10-08 23:00:55','2017-10-08 23:01:23'),(19,'Cuti Bersama Hari Raya Idul Fitri 1438 Hijriyah','2017-06-27 00:00:00','2017-06-30 00:00:00','2017-10-08 23:02:46','2017-10-08 23:02:46'),(20,'Cuti Bersama Hari Raya Natal','2017-12-26 00:00:00','2017-12-26 00:00:00','2017-10-08 23:03:26','2017-10-08 23:03:26');
/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hari_kerja`
--

DROP TABLE IF EXISTS `hari_kerja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hari_kerja` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jadwal_id` int(10) unsigned NOT NULL,
  `hari` enum('0','1','2','3','4','5','6') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `toleransi_terlambat` time NOT NULL,
  `toleransi_pulang` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `scan_in1` time NOT NULL,
  `scan_in2` time NOT NULL,
  `scan_out1` time NOT NULL,
  `scan_out2` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hari_kerja_jadwal_id_foreign` (`jadwal_id`),
  CONSTRAINT `hari_kerja_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal_kerja` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hari_kerja`
--

LOCK TABLES `hari_kerja` WRITE;
/*!40000 ALTER TABLE `hari_kerja` DISABLE KEYS */;
INSERT INTO `hari_kerja` VALUES (2,1,'1','07:30:00','14:00:00','00:15:00','00:15:00','2017-06-14 10:41:36','2017-08-16 01:24:01','06:15:00','11:00:00','14:00:00','18:00:00'),(3,1,'2','07:30:00','16:00:00','00:15:00','00:15:00','2017-06-17 09:50:32','2017-08-16 01:25:08','06:15:00','11:00:00','14:00:00','18:00:00'),(4,1,'3','07:30:00','16:00:00','00:15:00','00:15:00','2017-06-17 09:51:14','2017-08-16 01:25:38','06:15:00','11:00:00','14:00:00','18:00:00'),(5,1,'4','07:30:00','14:00:00','00:15:00','00:15:00','2017-08-15 03:09:11','2017-08-16 01:26:10','06:15:00','11:00:00','14:00:00','18:00:00'),(6,1,'5','07:30:00','11:00:00','00:15:00','00:15:00','2017-08-15 03:09:47','2017-08-16 01:30:18','06:15:00','09:00:00','09:00:00','13:00:00'),(7,2,'1','08:00:00','15:30:00','00:15:00','00:15:00','2017-10-09 01:03:47','2017-10-09 01:03:47','06:30:00','11:00:00','14:00:00','17:30:00'),(8,2,'2','08:00:00','15:30:00','00:15:00','00:15:00','2017-10-09 01:05:31','2017-10-09 01:05:31','06:30:00','11:00:00','14:00:00','17:30:00'),(9,2,'3','06:30:00','15:30:00','00:15:00','00:15:00','2017-10-09 01:07:06','2017-10-09 01:07:06','06:30:00','17:30:00','14:00:00','17:30:00'),(10,2,'4','08:00:00','15:30:00','00:15:00','00:15:00','2017-10-09 01:09:32','2017-10-09 01:09:32','06:30:00','11:00:00','14:00:00','17:30:00'),(11,2,'5','08:00:00','10:30:00','00:15:00','00:15:00','2017-10-09 16:18:46','2017-10-09 16:18:46','06:30:00','09:00:00','09:15:00','12:00:00'),(12,3,'1','07:30:00','16:00:00','00:15:00','00:15:00','2017-10-09 16:21:38','2017-10-09 16:21:38','06:30:00','11:00:00','14:00:00','17:30:00'),(13,3,'2','07:30:00','16:00:00','00:15:00','00:15:00','2017-10-09 16:22:46','2017-10-09 16:22:46','06:30:00','11:00:00','14:00:00','17:30:00'),(14,3,'3','07:30:00','16:00:00','00:15:00','00:15:00','2017-10-09 16:23:50','2017-10-09 16:23:50','06:30:00','11:00:00','14:00:00','17:30:00'),(15,3,'4','07:30:00','16:00:00','00:15:00','00:15:00','2017-10-09 16:24:45','2017-10-09 16:24:45','06:30:00','11:00:00','14:00:00','17:30:00'),(16,3,'5','07:30:00','11:00:00','00:15:00','00:15:00','2017-10-09 16:26:20','2017-10-09 16:26:20','06:30:00','09:00:00','09:15:00','13:00:00');
/*!40000 ALTER TABLE `hari_kerja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jadwal_kerja`
--

DROP TABLE IF EXISTS `jadwal_kerja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jadwal_kerja` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `id_unker` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_unker` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jadwal_kerja_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal_kerja`
--

LOCK TABLES `jadwal_kerja` WRITE;
/*!40000 ALTER TABLE `jadwal_kerja` DISABLE KEYS */;
INSERT INTO `jadwal_kerja` VALUES (1,'Reguler 1','Jadwal Kerja Reguler 1','2017-01-01 00:00:00','2017-05-24 00:00:00','2017-06-10 19:05:27','2017-10-09 01:01:58',NULL,'99990131','DINAS KOMUNIKASI DAN INFORMATIKA'),(2,'Jadwal Puasa','Jadwal Puasa 2017','2017-05-25 00:00:00','2017-06-30 00:00:00','2017-10-08 23:50:05','2017-10-08 23:50:05',NULL,'32000001','DINAS KOMUNIKASI DAN INFORMATIKA'),(3,'Reguler II 2017','Jadwal Kerja Reguler II 2017','2017-07-01 00:00:00','2017-12-31 00:00:00','2017-10-09 16:20:20','2017-10-09 16:20:20',NULL,'32000001','DINAS KOMUNIKASI DAN INFORMATIKA');
/*!40000 ALTER TABLE `jadwal_kerja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ketidakhadiran`
--

DROP TABLE IF EXISTS `ketidakhadiran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ketidakhadiran` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keterangan_id` int(10) unsigned NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `jam_start` time NOT NULL,
  `jam_end` time NOT NULL,
  `keperluan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `peg_id` int(10) unsigned NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ketidakhadiran_keterangan_id_foreign` (`keterangan_id`),
  KEY `ketidakhadiran_peg_id_foreign` (`peg_id`),
  CONSTRAINT `ketidakhadiran_keterangan_id_foreign` FOREIGN KEY (`keterangan_id`) REFERENCES `ref_ijin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ketidakhadiran_peg_id_foreign` FOREIGN KEY (`peg_id`) REFERENCES `peg_data_induk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ketidakhadiran`
--

LOCK TABLES `ketidakhadiran` WRITE;
/*!40000 ALTER TABLE `ketidakhadiran` DISABLE KEYS */;
/*!40000 ALTER TABLE `ketidakhadiran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2017_05_16_095950_rename_email_to_username',2),(4,'2017_05_19_090400_change_table_users',3),(6,'2017_05_21_012418_add_api_token_field_users',4),(7,'2017_05_23_150752_add_column_nama_unker',5),(8,'2017_05_24_172437_entrust_setup_tables',6),(9,'2017_05_25_064919_drop_column_tipe_users',7),(10,'2017_05_26_094827_create_datainduk_table',8),(11,'2017_06_03_172136_create_event_table',9),(16,'2017_06_04_200234_drop_column_class_event',10),(17,'2017_06_05_035411_create_jadwal_kerja_table',10),(18,'2017_06_05_165408_add_column_opd_jadwal_kerja',10),(22,'2017_06_09_233456_create_table_peg_jadwal',11),(23,'2017_06_13_204113_add_column_event_peg_jadwal',12),(24,'2017_06_18_202430_remove_column_hari_id',13),(25,'2017_07_24_001856_create_table_ref_ijin',14),(26,'2017_07_25_135052_create_table_ketidakhadiran_pegawai',15),(27,'2017_07_26_080204_add_column_peg_id',16),(28,'2017_08_01_022349_change_column_keperluan',17),(29,'2017_08_09_070253_add_column_filename',17),(30,'2017_08_14_113340_edit_table_peg_jadwal',18),(31,'2017_08_16_171035_edit_column_peg_jadwal',19),(32,'2017_08_18_213606_edit_table_ref_ijin',20),(33,'2017_08_19_131209_add_column_status',21),(34,'2017_09_23_164600_create_table_dispensasi',22),(35,'2017_09_30_165857_change_table_dispensasi',23);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peg_data_induk`
--

DROP TABLE IF EXISTS `peg_data_induk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peg_data_induk` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_finger` int(11) DEFAULT NULL,
  `type` enum('pns','nonpns') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gelar_depan` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gelar_belakang` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_unker` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_unker` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_subunit` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_subunit` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_pangkat` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `golru` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pangkat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_jabatan` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_jabatan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_eselon` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tmt_pangkat` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peg_data_induk`
--

LOCK TABLES `peg_data_induk` WRITE;
/*!40000 ALTER TABLE `peg_data_induk` DISABLE KEYS */;
/*!40000 ALTER TABLE `peg_data_induk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peg_jadwal`
--

DROP TABLE IF EXISTS `peg_jadwal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peg_jadwal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `peg_id` int(10) unsigned NOT NULL,
  `jadwal_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `event_id` int(10) unsigned DEFAULT NULL,
  `ketidakhadiran_id` int(10) unsigned DEFAULT NULL,
  `in` time DEFAULT '00:00:00',
  `out` time DEFAULT '00:00:00',
  `jam_kerja` time NOT NULL DEFAULT '00:00:00',
  `terlambat` time NOT NULL DEFAULT '00:00:00',
  `pulang_awal` time NOT NULL DEFAULT '00:00:00',
  `status` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `peg_jadwal_peg_id_foreign` (`peg_id`),
  KEY `peg_jadwal_jadwal_id_foreign` (`jadwal_id`),
  KEY `peg_jadwal_event_id_foreign` (`event_id`),
  CONSTRAINT `peg_jadwal_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `peg_jadwal_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal_kerja` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `peg_jadwal_peg_id_foreign` FOREIGN KEY (`peg_id`) REFERENCES `peg_data_induk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1270 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peg_jadwal`
--

LOCK TABLES `peg_jadwal` WRITE;
/*!40000 ALTER TABLE `peg_jadwal` DISABLE KEYS */;
/*!40000 ALTER TABLE `peg_jadwal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_role`
--

LOCK TABLES `permission_role` WRITE;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` VALUES (5,15),(5,16),(6,16),(7,16),(8,16),(5,17);
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (5,'add-event','Tambah Data Event','Tambah Data Event Kalendar','2017-10-07 17:06:15','2017-10-07 17:06:15'),(6,'edit-event','Ubah Event Kalender',NULL,'2017-10-07 17:11:39','2017-10-07 17:11:39'),(7,'delete-event','Hapus Event Kalendar',NULL,'2017-10-07 17:11:57','2017-10-07 17:11:57'),(8,'create-post','Tambah Data User',NULL,'2017-10-07 17:34:55','2017-10-07 17:34:55');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_ijin`
--

DROP TABLE IF EXISTS `ref_ijin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ref_ijin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `symbol` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_ijin`
--

LOCK TABLES `ref_ijin` WRITE;
/*!40000 ALTER TABLE `ref_ijin` DISABLE KEYS */;
INSERT INTO `ref_ijin` VALUES (1,'Cuti','2017-07-30 08:38:56','2017-08-18 13:42:21',NULL,'C'),(2,'Ijin','2017-08-15 02:45:12','2017-08-18 13:42:36',NULL,'I'),(4,'Dinas Luar','2017-08-15 02:45:36','2017-08-25 01:02:16',NULL,'DL'),(5,'Tugas Belajar','2017-08-15 02:45:47','2017-08-18 13:43:06',NULL,'TB'),(6,'Sakit','2017-08-15 02:45:56','2017-08-18 13:42:44',NULL,'S');
/*!40000 ALTER TABLE `ref_ijin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` VALUES (3,15),(26,15),(3,16),(19,16);
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (15,'admin','OPD Administrator',NULL,'2017-05-24 23:34:12','2017-10-07 17:06:39'),(16,'super-admin','Super Administrator',NULL,'2017-05-26 01:35:37','2017-05-26 01:35:37'),(17,'user-report','User Report',NULL,'2017-10-07 17:22:35','2017-10-07 17:22:35');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_token` char(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unker` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nm_unker` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'Edo Santra DIjaya','admin','$2y$10$OH.Xo3ejBYSf5UNh4tbNbOQ3hSq0KReajasl6aRVOpJa65PhQKlFK','l8RZ2Shl7tl3HOTf5b6Pkizl6aoO2mh0ulnC4WyKJGgZ6jQpXQzFFMZdod59','f125t4jQJONJdEGdpt3sq0wCfPot0lQKJaW5rtKwYZfqfIM39ibZRJMJG7LX','2017-05-20 17:48:04','2017-09-21 08:59:24','',NULL),(19,'Super Administrator','root','$2y$10$fsGKH5dqxgX9cfWurlEs8O9gThT9kplzYtqx3.dCbHEVjBhCJ9vsG','TavhC4Cq46BxQnq9cSepYV37setrmhHafmtV81b0swVNn9c8qZeRK8rkh1QA','mJrEHIme1cjakLsHSYxgcbvZEQmsgtpAKz9qqsdjBi7aOaeftmgBViVNwgoj','2017-05-26 01:36:19','2017-05-26 01:36:19',NULL,NULL),(26,'Operator Diskominfo','operator','$2y$10$AVgo.BI.IPWhiWCTEKZpnuPTblXzSivw9HMMle4CMmfZqhpyPGIQa','6wIb1PO46OneUsYa7xjzBZUFox967izRpr4M1Ftn5KcICaZAP54GGudq4ecd','sulF37gMSmSsIsfOIfVNDeuzI7AepydTkM4SKraavrsqRK5aVc2fozlCZf2K','2017-10-07 17:36:18','2017-10-07 17:36:18','99990131','DINAS KOMUNIKASI DAN INFORMATIKA');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'presensi'
--

--
-- Dumping routines for database 'presensi'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-10-12 22:38:54
