-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 20, 2026 at 10:39 AM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lifedigital_livestock`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `c_id` int(11) NOT NULL,
  `c_ct_id` int(11) NOT NULL DEFAULT 0,
  `c_name` varchar(255) NOT NULL DEFAULT '',
  `c_email` varchar(255) NOT NULL DEFAULT '',
  `c_address` varchar(500) NOT NULL DEFAULT '',
  `c_phone` varchar(255) NOT NULL DEFAULT '',
  `c_description` varchar(500) NOT NULL DEFAULT '',
  `c_img_url` varchar(255) NOT NULL DEFAULT '',
  `c_status` int(11) NOT NULL DEFAULT 0,
  `c_created_by` int(11) NOT NULL DEFAULT 0,
  `c_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `c_updated_by` int(11) NOT NULL DEFAULT 0,
  `c_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_payment`
--

CREATE TABLE `client_payment` (
  `cp_id` int(11) NOT NULL,
  `cp_c_id` int(11) NOT NULL DEFAULT 0,
  `cp_prss_id` int(11) NOT NULL DEFAULT 0,
  `cp_lsss_id` int(11) NOT NULL DEFAULT 0,
  `cp_received_amount` double NOT NULL DEFAULT 0,
  `cp_paid_by` varchar(255) NOT NULL DEFAULT '',
  `cp_cheque_no` varchar(255) NOT NULL DEFAULT '',
  `cp_reference` varchar(255) NOT NULL DEFAULT '',
  `cp_date` date NOT NULL DEFAULT '1970-01-01',
  `cp_description` varchar(255) NOT NULL DEFAULT '',
  `cp_status` int(11) NOT NULL DEFAULT 0,
  `cp_sale_type` int(11) NOT NULL DEFAULT 0,
  `cp_created_by` int(11) NOT NULL DEFAULT 0,
  `cp_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `cp_updated_by` int(11) NOT NULL DEFAULT 0,
  `cp_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_type`
--

CREATE TABLE `client_type` (
  `ct_id` int(11) NOT NULL,
  `ct_name` varchar(255) NOT NULL DEFAULT '',
  `ct_description` varchar(500) NOT NULL DEFAULT '',
  `ct_status` int(11) NOT NULL DEFAULT 0,
  `ct_created_by` int(11) NOT NULL DEFAULT 0,
  `ct_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `ct_updated_by` int(11) NOT NULL DEFAULT 0,
  `ct_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `ex_id` int(11) NOT NULL,
  `ex_expense_id` varchar(255) NOT NULL DEFAULT '',
  `ex_name` varchar(255) NOT NULL DEFAULT '',
  `ex_amount` double NOT NULL DEFAULT 0,
  `ex_exc_id` int(11) NOT NULL DEFAULT 0,
  `ex_exsc_id` int(11) NOT NULL DEFAULT 0,
  `ex_date` date NOT NULL DEFAULT '1970-01-01',
  `ex_description` varchar(500) NOT NULL DEFAULT '',
  `ex_status` int(11) NOT NULL DEFAULT 0,
  `ex_created_by` int(11) NOT NULL DEFAULT 0,
  `ex_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `ex_updated_by` int(11) NOT NULL DEFAULT 0,
  `ex_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_category`
--

CREATE TABLE `expense_category` (
  `exc_id` int(11) NOT NULL,
  `exc_name` varchar(255) NOT NULL DEFAULT '',
  `exc_description` varchar(500) NOT NULL DEFAULT '',
  `exc_status` int(11) NOT NULL DEFAULT 0,
  `exc_created_by` int(11) NOT NULL DEFAULT 0,
  `exc_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `exc_updated_by` int(11) NOT NULL DEFAULT 0,
  `exc_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_payment`
--

CREATE TABLE `expense_payment` (
  `exp_id` int(11) NOT NULL,
  `exp_ex_id` int(11) NOT NULL DEFAULT 0,
  `exp_paid_amount` double NOT NULL DEFAULT 0,
  `exp_paid_by` varchar(255) NOT NULL DEFAULT '',
  `exp_cheque_no` varchar(255) NOT NULL DEFAULT '',
  `exp_date` date NOT NULL DEFAULT '1970-01-01',
  `exp_description` varchar(255) NOT NULL DEFAULT '',
  `exp_status` int(11) NOT NULL DEFAULT 0,
  `exp_created_by` int(11) NOT NULL DEFAULT 0,
  `exp_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `exp_updated_by` int(11) NOT NULL DEFAULT 0,
  `exp_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_subcategory`
--

CREATE TABLE `expense_subcategory` (
  `exsc_id` int(11) NOT NULL,
  `exsc_name` varchar(255) NOT NULL DEFAULT '',
  `exsc_exc_id` int(11) NOT NULL DEFAULT 0,
  `exsc_description` varchar(500) NOT NULL DEFAULT '',
  `exsc_status` int(11) NOT NULL DEFAULT 0,
  `exsc_created_by` int(11) NOT NULL DEFAULT 0,
  `exsc_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `exsc_updated_by` int(11) NOT NULL DEFAULT 0,
  `exsc_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_distributed_summary`
--

CREATE TABLE `food_distributed_summary` (
  `fdds_id` int(11) NOT NULL,
  `fdds_fd_id` int(11) NOT NULL DEFAULT 0,
  `fdds_date` date NOT NULL DEFAULT '1970-01-01',
  `fdds_total` double NOT NULL DEFAULT 0,
  `fdds_status` int(11) NOT NULL DEFAULT 0,
  `fdds_created_by` int(11) NOT NULL DEFAULT 0,
  `fdds_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `fdds_updated_by` int(11) NOT NULL DEFAULT 0,
  `fdds_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_distributed_value`
--

CREATE TABLE `food_distributed_value` (
  `fddv_id` int(11) NOT NULL,
  `fddv_fdds_id` int(11) NOT NULL DEFAULT 0,
  `fddv_shed_id` int(11) NOT NULL DEFAULT 0,
  `fddv_batch_id` int(11) NOT NULL DEFAULT 0,
  `fddv_need_quantity` double NOT NULL DEFAULT 0,
  `fddv_distributed_quantity` double NOT NULL DEFAULT 0,
  `fddv_description` varchar(255) DEFAULT NULL,
  `fddv_status` int(11) NOT NULL DEFAULT 0,
  `fddv_created_by` int(11) NOT NULL DEFAULT 0,
  `fddv_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `fddv_updated_by` int(11) NOT NULL DEFAULT 0,
  `fddv_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_purchase_summary`
--

CREATE TABLE `food_purchase_summary` (
  `fdps_id` int(11) NOT NULL,
  `fdps_s_id` int(11) NOT NULL DEFAULT 0,
  `fdps_date` date NOT NULL DEFAULT '1970-01-01',
  `fdps_sub_total` double NOT NULL DEFAULT 0,
  `fdps_grand_discount` double NOT NULL DEFAULT 0,
  `fdps_grand_total` double NOT NULL DEFAULT 0,
  `fdps_description` varchar(500) NOT NULL DEFAULT '',
  `fdps_status` int(11) NOT NULL DEFAULT 0,
  `fdps_created_by` int(11) NOT NULL DEFAULT 0,
  `fdps_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `fdps_updated_by` int(11) NOT NULL DEFAULT 0,
  `fdps_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_purchase_value`
--

CREATE TABLE `food_purchase_value` (
  `fdpv_id` int(11) NOT NULL,
  `fdpv_fdps_id` int(11) NOT NULL DEFAULT 0,
  `fdpv_fd_id` int(11) NOT NULL DEFAULT 0,
  `fdpv_unit_price` double NOT NULL DEFAULT 0,
  `fdpv_quantity` double NOT NULL DEFAULT 0,
  `fdpv_discount` double NOT NULL DEFAULT 0,
  `fdpv_total` double NOT NULL DEFAULT 0,
  `fdpv_status` int(11) NOT NULL DEFAULT 0,
  `fdpv_created_by` int(11) NOT NULL DEFAULT 0,
  `fdpv_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `fdpv_updated_by` int(11) NOT NULL DEFAULT 0,
  `fdpv_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_summary`
--

CREATE TABLE `food_summary` (
  `fds_id` int(11) NOT NULL,
  `fds_food_title` varchar(255) NOT NULL DEFAULT '',
  `fds_description` varchar(255) NOT NULL DEFAULT '',
  `fds_unit_id` int(11) NOT NULL DEFAULT 0,
  `fds_status` int(11) NOT NULL DEFAULT 0,
  `fds_created_by` int(11) NOT NULL DEFAULT 0,
  `fds_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `fds_updated_by` int(11) NOT NULL DEFAULT 0,
  `fds_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_value`
--

CREATE TABLE `food_value` (
  `fdv_id` int(11) NOT NULL,
  `fdv_fds_id` int(11) NOT NULL DEFAULT 0,
  `fdv_weight` double NOT NULL DEFAULT 0,
  `fdv_assigned_shed_id` int(11) NOT NULL DEFAULT 0,
  `fdv_assigned_batch_id` int(11) NOT NULL DEFAULT 0,
  `fdv_description` varchar(255) NOT NULL DEFAULT '',
  `fdv_status` int(11) NOT NULL DEFAULT 0,
  `fdv_created_by` int(11) NOT NULL DEFAULT 0,
  `fdv_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `fdv_updated_by` int(11) NOT NULL DEFAULT 0,
  `fdv_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_wasted`
--

CREATE TABLE `food_wasted` (
  `fdw_id` int(11) NOT NULL,
  `fdw_fd_id` int(11) NOT NULL DEFAULT 0,
  `fdw_quantity` double NOT NULL DEFAULT 0,
  `fdw_description` varchar(500) NOT NULL DEFAULT '',
  `fdw_status` int(11) NOT NULL DEFAULT 0,
  `fdw_created_by` int(11) NOT NULL DEFAULT 0,
  `fdw_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `fdw_updated_by` int(11) NOT NULL DEFAULT 0,
  `fdw_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'members', 'General User'),
(4, 'driver', 'driver'),
(5, 'Client', '');

-- --------------------------------------------------------

--
-- Table structure for table `livestock`
--

CREATE TABLE `livestock` (
  `ls_id` int(11) NOT NULL,
  `ls_lst_type_id` int(11) NOT NULL DEFAULT 0,
  `ls_name` varchar(500) NOT NULL DEFAULT '',
  `ls_description` varchar(1000) NOT NULL DEFAULT '',
  `ls_status` int(11) NOT NULL DEFAULT 0,
  `ls_created_by` int(11) NOT NULL DEFAULT 0,
  `ls_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `ls_updated_by` int(11) NOT NULL DEFAULT 0,
  `ls_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livestock_death_quantity`
--

CREATE TABLE `livestock_death_quantity` (
  `ld_id` int(11) NOT NULL,
  `ld_sh_id` int(11) NOT NULL DEFAULT 0,
  `ld_batch_id` int(11) NOT NULL DEFAULT 0,
  `ld_lshs_id` int(11) NOT NULL DEFAULT 0,
  `ld_purv_purs_id` int(11) NOT NULL DEFAULT 0,
  `ld_purv_ls_id` int(11) NOT NULL DEFAULT 0,
  `ld_purv_lst_id` int(11) NOT NULL DEFAULT 0,
  `ld_death_quantity` double NOT NULL DEFAULT 0,
  `ld_death_date` date NOT NULL DEFAULT '1970-01-01',
  `ld_death_reason` varchar(500) NOT NULL DEFAULT '',
  `ld_status` int(11) NOT NULL DEFAULT 0,
  `ld_created_by` int(11) NOT NULL DEFAULT 0,
  `ld_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `ld_updated_by` int(11) NOT NULL DEFAULT 0,
  `ld_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livestock_purchase_summary`
--

CREATE TABLE `livestock_purchase_summary` (
  `purs_id` int(11) NOT NULL,
  `purs_bill_no` int(11) NOT NULL DEFAULT 0,
  `purs_supp_id` int(11) NOT NULL DEFAULT 0,
  `purs_date` date NOT NULL DEFAULT '1970-01-01',
  `purs_sub_total` double NOT NULL DEFAULT 0,
  `purs_grand_discount` double NOT NULL DEFAULT 0,
  `purs_grand_total` double NOT NULL DEFAULT 0,
  `purs_note` varchar(1000) NOT NULL DEFAULT '',
  `purs_status` int(11) NOT NULL DEFAULT 0,
  `purs_created_by` int(11) NOT NULL DEFAULT 0,
  `purs_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `purs_updated_by` int(11) NOT NULL DEFAULT 0,
  `purs_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livestock_purchase_value`
--

CREATE TABLE `livestock_purchase_value` (
  `purv_id` int(11) NOT NULL,
  `purv_purs_id` int(11) NOT NULL DEFAULT 0,
  `purv_ls_id` int(11) NOT NULL DEFAULT 0,
  `purv_lst_id` int(11) NOT NULL DEFAULT 0,
  `purv_unit_price` double NOT NULL DEFAULT 0,
  `purv_quantity` double NOT NULL DEFAULT 0,
  `purv_discount` double NOT NULL DEFAULT 0,
  `purv_total` double NOT NULL DEFAULT 0,
  `purv_status` int(11) NOT NULL DEFAULT 0,
  `purv_created_by` int(11) NOT NULL DEFAULT 0,
  `purv_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `purv_updated_by` int(11) NOT NULL DEFAULT 0,
  `purv_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livestock_reproduction`
--

CREATE TABLE `livestock_reproduction` (
  `lrp_id` int(11) NOT NULL,
  `lrp_batch_id` int(11) NOT NULL DEFAULT 0,
  `lrp_sh_id` int(11) NOT NULL DEFAULT 0,
  `lrp_ls_id` int(11) NOT NULL DEFAULT 0,
  `lrp_lst_id` int(11) NOT NULL DEFAULT 0,
  `lrp_birth_quantity` double NOT NULL DEFAULT 0,
  `lrp_approx_selling_price` double NOT NULL DEFAULT 0,
  `lrp_birth_date` date NOT NULL DEFAULT '1970-01-01',
  `lrp_description` varchar(500) NOT NULL DEFAULT '',
  `lrp_status` int(11) NOT NULL DEFAULT 0,
  `lrp_created_by` int(11) NOT NULL DEFAULT 0,
  `lrp_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `lrp_updated_by` int(11) NOT NULL DEFAULT 0,
  `lrp_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livestock_sale_summary`
--

CREATE TABLE `livestock_sale_summary` (
  `lsss_id` int(11) NOT NULL,
  `lsss_c_id` int(11) NOT NULL DEFAULT 0,
  `lsss_date` date NOT NULL DEFAULT '1970-01-01',
  `lsss_sub_total` double NOT NULL DEFAULT 0,
  `lsss_grand_discount` double NOT NULL DEFAULT 0,
  `lsss_grand_total` double NOT NULL DEFAULT 0,
  `lsss_note` varchar(1000) NOT NULL DEFAULT '',
  `lsss_status` int(11) NOT NULL DEFAULT 0,
  `lsss_created_by` int(11) NOT NULL DEFAULT 0,
  `lsss_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `lsss_updated_by` int(11) NOT NULL DEFAULT 0,
  `lsss_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livestock_sale_value`
--

CREATE TABLE `livestock_sale_value` (
  `lssv_id` int(11) NOT NULL,
  `lssv_lsss_id` int(11) NOT NULL DEFAULT 0,
  `lssv_batch_id` int(11) NOT NULL DEFAULT 0,
  `lssv_shed_id` int(11) NOT NULL DEFAULT 0,
  `lssv_ls_id` int(11) NOT NULL DEFAULT 0,
  `lssv_lst_id` int(11) NOT NULL DEFAULT 0,
  `lssv_unit_price` double NOT NULL DEFAULT 0,
  `lssv_quantity` double NOT NULL DEFAULT 0,
  `lssv_discount` double NOT NULL DEFAULT 0,
  `lssv_total` double NOT NULL DEFAULT 0,
  `lssv_status` int(11) NOT NULL DEFAULT 0,
  `lssv_created_by` int(11) NOT NULL DEFAULT 0,
  `lssv_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `lssv_updated_by` int(11) NOT NULL DEFAULT 0,
  `lssv_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livestock_transfer_quantity`
--

CREATE TABLE `livestock_transfer_quantity` (
  `ltr_id` int(11) NOT NULL,
  `ltr_sh_id` int(11) NOT NULL DEFAULT 0,
  `ltr_batch_id` int(11) NOT NULL DEFAULT 0,
  `ltr_target_sh_id` int(11) NOT NULL DEFAULT 0,
  `ltr_target_batch_id` int(11) NOT NULL DEFAULT 0,
  `ltr_purv_purs_id` int(11) NOT NULL DEFAULT 0,
  `ltr_purv_ls_id` int(11) NOT NULL DEFAULT 0,
  `ltr_purv_lst_id` int(11) NOT NULL DEFAULT 0,
  `ltr_transfer_date` date NOT NULL DEFAULT '1970-01-01',
  `ltr_transfer_quantity` double NOT NULL DEFAULT 0,
  `ltr_description` varchar(500) NOT NULL DEFAULT '',
  `ltr_status` int(11) NOT NULL DEFAULT 0,
  `ltr_created_by` int(11) NOT NULL DEFAULT 0,
  `ltr_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `ltr_updated_by` int(11) NOT NULL DEFAULT 0,
  `ltr_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livestock_type`
--

CREATE TABLE `livestock_type` (
  `lst_id` int(11) NOT NULL,
  `lst_ls_id` int(11) NOT NULL DEFAULT 0,
  `lst_title` varchar(500) NOT NULL DEFAULT '',
  `lst_description` varchar(1000) NOT NULL DEFAULT '',
  `lst_status` int(11) NOT NULL DEFAULT 0,
  `lst_created_by` int(11) NOT NULL DEFAULT 0,
  `lst_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `lst_updated_by` int(11) NOT NULL DEFAULT 0,
  `lst_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_assigned_shed`
--

CREATE TABLE `live_assigned_shed` (
  `lsh_id` int(11) NOT NULL,
  `lsh_lshs_id` int(11) NOT NULL DEFAULT 0,
  `lsh_sh_id` int(11) NOT NULL DEFAULT 0,
  `lsh_batch_id` int(11) NOT NULL DEFAULT 0,
  `lsh_purs_id` int(11) NOT NULL DEFAULT 0,
  `lsh_purv_id` int(11) NOT NULL DEFAULT 0,
  `lsh_purv_ls_id` int(11) NOT NULL DEFAULT 0,
  `lsh_purv_lst_id` int(11) NOT NULL DEFAULT 0,
  `lsh_assign_quantity` double NOT NULL DEFAULT 0,
  `lsh_status` int(11) NOT NULL DEFAULT 0,
  `lsh_transfer_quantity` double NOT NULL DEFAULT 0,
  `lsh_transfer_status` int(11) NOT NULL DEFAULT 0,
  `lsh_reproduction_id` int(11) NOT NULL DEFAULT 0,
  `lsh_created_by` int(11) NOT NULL DEFAULT 0,
  `lsh_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `lsh_updated_by` int(11) NOT NULL DEFAULT 0,
  `lsh_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_assigned_shed_summary`
--

CREATE TABLE `live_assigned_shed_summary` (
  `lshs_id` int(11) NOT NULL,
  `lshs_sh_id` int(11) NOT NULL DEFAULT 0,
  `lshs_batch_id` int(11) NOT NULL DEFAULT 0,
  `lshs_assign_date` date NOT NULL DEFAULT '1970-01-01',
  `lshs_assign_total_quantity` double NOT NULL DEFAULT 0,
  `lshs_batch_title` varchar(255) NOT NULL DEFAULT '',
  `lshs_description` varchar(500) NOT NULL DEFAULT '',
  `lshs_status` int(11) NOT NULL DEFAULT 0,
  `lshs_transfer_date` date NOT NULL DEFAULT '1970-01-01',
  `lshs_transfer_status` int(11) NOT NULL DEFAULT 0,
  `lshs_transfer_total_quantity` double NOT NULL DEFAULT 0,
  `lshs_reproduction_id` int(11) NOT NULL DEFAULT 0,
  `lshs_reproduction_status` int(11) NOT NULL DEFAULT 0,
  `lshs_type` int(11) NOT NULL DEFAULT 0,
  `lshs_active_status` int(11) NOT NULL DEFAULT 0,
  `lshs_created_by` int(11) NOT NULL DEFAULT 0,
  `lshs_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `lshs_updated_by` int(11) NOT NULL DEFAULT 0,
  `lshs_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `login` varchar(100) NOT NULL DEFAULT '',
  `time` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `id` int(11) NOT NULL,
  `medicine_id` varchar(222) NOT NULL DEFAULT '',
  `duration` varchar(250) NOT NULL DEFAULT '',
  `no` varchar(250) NOT NULL DEFAULT '',
  `p_date` varchar(250) NOT NULL DEFAULT '',
  `n_date` varchar(250) NOT NULL DEFAULT '',
  `l_date` varchar(250) NOT NULL DEFAULT '',
  `add_date` varchar(250) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL DEFAULT 0,
  `date` varchar(100) DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `client_id` varchar(100) NOT NULL DEFAULT '',
  `local_sale_id` text DEFAULT NULL,
  `return_id` int(11) DEFAULT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `supplier` varchar(100) NOT NULL DEFAULT '',
  `reference_no` varchar(50) NOT NULL DEFAULT '',
  `transaction_id` varchar(50) DEFAULT NULL,
  `paid_by` varchar(20) NOT NULL DEFAULT '',
  `cheque_no` varchar(20) DEFAULT NULL,
  `cc_no` varchar(20) DEFAULT NULL,
  `cc_holder` varchar(25) DEFAULT NULL,
  `cc_month` varchar(2) DEFAULT NULL,
  `cc_year` varchar(4) DEFAULT NULL,
  `cc_type` varchar(20) DEFAULT NULL,
  `amount` varchar(100) NOT NULL DEFAULT '',
  `currency` varchar(10) DEFAULT NULL,
  `dollar_amount` varchar(100) NOT NULL DEFAULT '',
  `dollar_rate` varchar(100) NOT NULL DEFAULT '',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `attachment` varchar(55) DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT '',
  `note` varchar(1000) DEFAULT NULL,
  `pos_paid` decimal(25,4) DEFAULT 0.0000,
  `pos_balance` decimal(25,4) DEFAULT 0.0000,
  `approval_code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pr_id` int(11) NOT NULL,
  `pr_prc_id` int(11) NOT NULL DEFAULT 0,
  `pr_unit_id` int(11) NOT NULL DEFAULT 0,
  `pr_name` varchar(255) NOT NULL DEFAULT '',
  `pr_description` varchar(500) NOT NULL DEFAULT '',
  `pr_img_url` varchar(255) NOT NULL DEFAULT '',
  `pr_selling_price` double NOT NULL DEFAULT 0,
  `pr_status` int(11) NOT NULL DEFAULT 0,
  `pr_created_by` int(11) NOT NULL DEFAULT 0,
  `pr_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `pr_updated_by` int(11) NOT NULL DEFAULT 0,
  `pr_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_assign`
--

CREATE TABLE `product_assign` (
  `pra_id` int(11) NOT NULL,
  `pra_pr_id` int(11) NOT NULL DEFAULT 0,
  `pra_shed_id` int(11) NOT NULL DEFAULT 0,
  `pra_batch_id` int(11) NOT NULL DEFAULT 0,
  `pra_description` varchar(500) NOT NULL DEFAULT '',
  `pra_status` int(11) NOT NULL DEFAULT 0,
  `pra_created_by` int(11) NOT NULL DEFAULT 0,
  `pra_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `pra_updated_by` int(11) NOT NULL DEFAULT 0,
  `pra_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `prc_id` int(11) NOT NULL,
  `prc_name` varchar(255) NOT NULL DEFAULT '',
  `prc_description` varchar(500) NOT NULL DEFAULT '',
  `prc_status` int(11) NOT NULL DEFAULT 0,
  `prc_created_by` int(11) NOT NULL DEFAULT 0,
  `prc_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `prc_updated_by` int(11) NOT NULL DEFAULT 0,
  `prc_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_sale_summary`
--

CREATE TABLE `product_sale_summary` (
  `prss_id` int(11) NOT NULL,
  `prss_c_id` int(11) NOT NULL DEFAULT 0,
  `prss_date` date NOT NULL DEFAULT '1970-01-01',
  `prss_sub_total` double NOT NULL DEFAULT 0,
  `prss_grand_discount` double NOT NULL DEFAULT 0,
  `prss_grand_total` double NOT NULL DEFAULT 0,
  `prss_note` varchar(1000) NOT NULL DEFAULT '',
  `prss_status` int(11) NOT NULL DEFAULT 0,
  `prss_created_by` int(11) NOT NULL DEFAULT 0,
  `prss_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `prss_updated_by` int(11) NOT NULL DEFAULT 0,
  `prss_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_sale_value`
--

CREATE TABLE `product_sale_value` (
  `prsv_id` int(11) NOT NULL,
  `prsv_prss_id` int(11) NOT NULL DEFAULT 0,
  `prsv_pra_id` int(11) NOT NULL DEFAULT 0,
  `prsv_product_id` int(11) NOT NULL DEFAULT 0,
  `prsv_unit_price` double NOT NULL DEFAULT 0,
  `prsv_quantity` double NOT NULL DEFAULT 0,
  `prsv_discount` double NOT NULL DEFAULT 0,
  `prsv_total` double NOT NULL DEFAULT 0,
  `prsv_status` int(11) NOT NULL DEFAULT 0,
  `prsv_created_by` int(11) NOT NULL DEFAULT 0,
  `prsv_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `prsv_updated_by` int(11) NOT NULL DEFAULT 0,
  `prsv_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_stock`
--

CREATE TABLE `product_stock` (
  `prs_id` int(11) NOT NULL,
  `prs_pr_id` int(11) NOT NULL DEFAULT 0,
  `prs_pra_id` int(11) NOT NULL DEFAULT 0,
  `prs_shed_id` int(11) NOT NULL DEFAULT 0,
  `prs_batch_id` int(11) NOT NULL DEFAULT 0,
  `prs_production_quantity` double NOT NULL DEFAULT 0,
  `prs_description` varchar(500) NOT NULL DEFAULT '',
  `prs_date` date NOT NULL DEFAULT '1970-01-01',
  `prs_status` int(11) NOT NULL DEFAULT 0,
  `prs_created_by` int(11) NOT NULL DEFAULT 0,
  `prs_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `prs_updated_by` int(11) NOT NULL DEFAULT 0,
  `prs_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_wasted`
--

CREATE TABLE `product_wasted` (
  `prw_id` int(11) NOT NULL,
  `prw_pr_id` int(11) NOT NULL DEFAULT 0,
  `prw_pra_id` int(11) NOT NULL DEFAULT 0,
  `prw_shed_id` int(11) NOT NULL DEFAULT 0,
  `prw_batch_id` int(11) NOT NULL DEFAULT 0,
  `prw_wasted_quantity` double NOT NULL DEFAULT 0,
  `prw_description` varchar(500) NOT NULL DEFAULT '',
  `prw_date` date NOT NULL DEFAULT '1970-01-01',
  `prw_status` int(11) NOT NULL DEFAULT 0,
  `prw_created_by` int(11) NOT NULL DEFAULT 0,
  `prw_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `prw_updated_by` int(11) NOT NULL DEFAULT 0,
  `prw_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `id` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL DEFAULT '',
  `product` varchar(100) NOT NULL DEFAULT '',
  `supplier` varchar(100) NOT NULL DEFAULT '',
  `date` varchar(100) NOT NULL DEFAULT '',
  `unit_price` varchar(100) NOT NULL DEFAULT '',
  `quantity` varchar(100) NOT NULL DEFAULT '',
  `discount` varchar(100) NOT NULL DEFAULT '',
  `amount_payable` decimal(65,2) NOT NULL DEFAULT 0.00,
  `paid_amount` varchar(100) NOT NULL DEFAULT '',
  `payment_status` varchar(100) NOT NULL DEFAULT '',
  `purchase_status` int(11) NOT NULL DEFAULT 0,
  `note` varchar(1000) NOT NULL DEFAULT '',
  `x` varchar(100) NOT NULL DEFAULT '',
  `status` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE `sale` (
  `id` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL DEFAULT '',
  `category` varchar(100) NOT NULL DEFAULT '',
  `category_name` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  `client_id` varchar(100) NOT NULL DEFAULT '',
  `sale_status` varchar(100) NOT NULL DEFAULT '',
  `date` varchar(100) NOT NULL DEFAULT '',
  `amount` varchar(100) NOT NULL DEFAULT '',
  `vat` varchar(100) NOT NULL DEFAULT '0',
  `flat_vat` varchar(100) NOT NULL DEFAULT '',
  `discount` varchar(100) NOT NULL DEFAULT '0',
  `flat_discount` varchar(100) NOT NULL DEFAULT '',
  `gross_total` varchar(100) NOT NULL DEFAULT '',
  `amount_received` varchar(100) NOT NULL DEFAULT '',
  `balance` varchar(100) NOT NULL DEFAULT '',
  `payment_status` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `system_vendor` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `address` varchar(100) NOT NULL DEFAULT '',
  `phone` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `facebook_id` varchar(100) NOT NULL DEFAULT '',
  `currency` varchar(100) NOT NULL DEFAULT '',
  `unit` varchar(100) NOT NULL DEFAULT '',
  `discount` varchar(100) NOT NULL DEFAULT '',
  `vat` varchar(100) NOT NULL DEFAULT '',
  `date_format` varchar(100) NOT NULL DEFAULT '',
  `login_title` varchar(100) NOT NULL DEFAULT '',
  `codec_username` varchar(100) NOT NULL DEFAULT '',
  `codec_purchase_code` varchar(100) NOT NULL DEFAULT '',
  `language` varchar(250) NOT NULL DEFAULT '',
  `img_url` varchar(256) NOT NULL DEFAULT '',
  `toggle_status` int(11) NOT NULL DEFAULT 0,
  `timezone` varchar(64) NOT NULL DEFAULT 'Asia/Dhaka',
  `low_stock_threshold` int(11) NOT NULL DEFAULT 10,
  `overdue_payment_days` int(11) NOT NULL DEFAULT 7
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `system_vendor`, `title`, `address`, `phone`, `email`, `facebook_id`, `currency`, `unit`, `discount`, `vat`, `date_format`, `login_title`, `codec_username`, `codec_purchase_code`, `language`, `img_url`, `toggle_status`, `timezone`, `low_stock_threshold`, `overdue_payment_days`) VALUES
(1, 'Livestock Management System', 'LIVESTOCK', 'Test Address', '+12345678', 'admin@example.com', '#', 'TK.', 'pc', '0', 'percentage', 'd-m-Y', 'Live Stock', '', '', 'english', 'uploads/logo11.png', 1, 'Asia/Dhaka', 10, 7);

-- --------------------------------------------------------

--
-- Table structure for table `shed`
--

CREATE TABLE `shed` (
  `sh_id` int(11) NOT NULL,
  `sh_no` varchar(255) NOT NULL DEFAULT '',
  `sh_title` varchar(255) NOT NULL DEFAULT '',
  `sh_type` varchar(255) NOT NULL DEFAULT '',
  `sh_date` date NOT NULL DEFAULT '1970-01-01',
  `sh_description` varchar(1000) NOT NULL DEFAULT '',
  `sh_status` int(11) NOT NULL DEFAULT 0,
  `sh_created_by` int(11) NOT NULL DEFAULT 0,
  `sh_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `sh_updated_by` int(11) NOT NULL DEFAULT 0,
  `sh_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `sf_id` int(11) NOT NULL,
  `sf_sft_id` int(11) NOT NULL DEFAULT 0,
  `sf_name` varchar(255) NOT NULL DEFAULT '',
  `sf_email` varchar(255) NOT NULL DEFAULT '',
  `sf_address` varchar(500) NOT NULL DEFAULT '',
  `sf_phone` varchar(255) NOT NULL DEFAULT '',
  `sf_description` varchar(500) NOT NULL DEFAULT '',
  `sf_img_url` varchar(255) NOT NULL DEFAULT '',
  `sf_status` int(11) NOT NULL DEFAULT 0,
  `sf_created_by` int(11) NOT NULL DEFAULT 0,
  `sf_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `sf_updated_by` int(11) NOT NULL DEFAULT 0,
  `sf_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_payment`
--

CREATE TABLE `staff_payment` (
  `sfp_id` int(11) NOT NULL,
  `sfp_sf_id` int(11) NOT NULL DEFAULT 0,
  `sfp_received_amount` double NOT NULL DEFAULT 0,
  `sfp_payment_amount` double NOT NULL DEFAULT 0,
  `sfp_paid_by` varchar(255) NOT NULL DEFAULT '',
  `sfp_cheque_no` varchar(255) NOT NULL DEFAULT '',
  `sfp_reference` varchar(255) NOT NULL DEFAULT '',
  `sfp_date` date NOT NULL DEFAULT '1970-01-01',
  `sfp_description` varchar(255) NOT NULL DEFAULT '',
  `sfp_status` int(11) NOT NULL DEFAULT 0,
  `sfp_created_by` int(11) NOT NULL DEFAULT 0,
  `sfp_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `sfp_updated_by` int(11) NOT NULL DEFAULT 0,
  `sfp_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_type`
--

CREATE TABLE `staff_type` (
  `sft_id` int(11) NOT NULL,
  `sft_name` varchar(255) NOT NULL DEFAULT '',
  `sft_description` varchar(500) NOT NULL DEFAULT '',
  `sft_status` int(11) NOT NULL DEFAULT 0,
  `sft_created_by` int(11) NOT NULL DEFAULT 0,
  `sft_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `sft_updated_by` int(11) NOT NULL DEFAULT 0,
  `sft_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `s_id` int(11) NOT NULL,
  `s_st_id` int(11) NOT NULL DEFAULT 0,
  `s_name` varchar(255) NOT NULL DEFAULT '',
  `s_email` varchar(255) NOT NULL DEFAULT '',
  `s_address` varchar(500) NOT NULL DEFAULT '',
  `s_phone` varchar(255) NOT NULL DEFAULT '',
  `s_description` varchar(500) NOT NULL DEFAULT '',
  `s_img_url` varchar(255) NOT NULL DEFAULT '',
  `s_status` int(11) NOT NULL DEFAULT 0,
  `s_created_by` int(11) NOT NULL DEFAULT 0,
  `s_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `s_updated_by` int(11) NOT NULL DEFAULT 0,
  `s_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_payment`
--

CREATE TABLE `supplier_payment` (
  `sp_id` int(11) NOT NULL,
  `sp_s_id` int(11) NOT NULL DEFAULT 0,
  `sp_purs_id` int(11) NOT NULL DEFAULT 0,
  `sp_fdps_id` int(11) NOT NULL DEFAULT 0,
  `sp_vps_id` int(11) NOT NULL DEFAULT 0,
  `sp_payment_amount` double NOT NULL DEFAULT 0,
  `sp_paid_by` varchar(255) NOT NULL DEFAULT '',
  `sp_cheque_no` varchar(255) NOT NULL DEFAULT '',
  `sp_reference` varchar(255) NOT NULL DEFAULT '',
  `sp_date` date NOT NULL DEFAULT '1970-01-01',
  `sp_description` varchar(255) NOT NULL DEFAULT '',
  `sp_status` int(11) NOT NULL DEFAULT 0,
  `sp_purchase_type` int(11) NOT NULL DEFAULT 0,
  `sp_created_by` int(11) NOT NULL DEFAULT 0,
  `sp_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `sp_updated_by` int(11) NOT NULL DEFAULT 0,
  `sp_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_type`
--

CREATE TABLE `supplier_type` (
  `st_id` int(11) NOT NULL,
  `st_name` varchar(255) NOT NULL DEFAULT '',
  `st_description` varchar(500) NOT NULL DEFAULT '',
  `st_status` int(11) NOT NULL DEFAULT 0,
  `st_created_by` int(11) NOT NULL DEFAULT 0,
  `st_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `st_updated_by` int(11) NOT NULL DEFAULT 0,
  `st_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `un_id` int(11) NOT NULL,
  `un_name` varchar(255) NOT NULL DEFAULT '',
  `un_description` varchar(500) NOT NULL DEFAULT '',
  `un_status` int(11) NOT NULL DEFAULT 0,
  `un_created_by` int(11) NOT NULL DEFAULT 0,
  `un_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `un_updated_by` int(11) NOT NULL DEFAULT 0,
  `un_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `username` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL DEFAULT '',
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(10) UNSIGNED DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `last_login` int(10) UNSIGNED DEFAULT NULL,
  `active` tinyint(3) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'Admin', '$2y$08$g1u0qcMP1wTgxK0yyho4xuc5EdtJwuCD8ETe6OQQWkAQmbFBA.DQ.', 'NULL', 'admin@example.com', 'NULL', 'wTvWME9FIkBM3h8t1St41O225d1010c68b1aed53', 1674545202, NULL, 1268889823, 1781951700, 1, 'Admin', 'istrator', 'ADMIN', '0');

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `group_id` mediumint(8) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `vaccination`
--

CREATE TABLE `vaccination` (
  `vccn_id` int(11) NOT NULL,
  `vccn_vcc_id` int(11) NOT NULL DEFAULT 0,
  `vccn_ls_id` int(11) NOT NULL DEFAULT 0,
  `vccn_lst_id` int(11) NOT NULL DEFAULT 0,
  `vccn_status` int(11) NOT NULL DEFAULT 0,
  `vccn_created_by` int(11) NOT NULL DEFAULT 0,
  `vccn_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `vccn_updated_by` int(11) NOT NULL DEFAULT 0,
  `vccn_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vaccine`
--

CREATE TABLE `vaccine` (
  `vcc_id` int(11) NOT NULL,
  `vcc_vaccine_id` int(11) NOT NULL DEFAULT 0,
  `vcc_name` varchar(255) NOT NULL DEFAULT '',
  `vcc_unit_id` int(11) NOT NULL DEFAULT 0,
  `vcc_description` varchar(255) NOT NULL DEFAULT '',
  `vcc_status` int(11) NOT NULL DEFAULT 0,
  `vcc_created_by` int(11) NOT NULL DEFAULT 0,
  `vcc_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `vcc_updated_by` int(11) NOT NULL DEFAULT 0,
  `vcc_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_dose_assigned_quantity`
--

CREATE TABLE `vaccine_dose_assigned_quantity` (
  `vdq_id` int(11) NOT NULL,
  `vdq_vccn_id` int(11) NOT NULL DEFAULT 0,
  `vdq_dose_serial` int(11) NOT NULL DEFAULT 0,
  `vdq_vcc_id` int(11) NOT NULL DEFAULT 0,
  `vdq_vaccination_date` date NOT NULL DEFAULT '1970-01-01',
  `vdq_vaccine_route` int(11) NOT NULL DEFAULT 0,
  `vdq_description` varchar(500) NOT NULL DEFAULT '',
  `vdq_status` int(11) NOT NULL DEFAULT 0,
  `vdq_created_by` int(11) NOT NULL DEFAULT 0,
  `vdq_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `vdq_updated_by` int(11) NOT NULL DEFAULT 0,
  `vdq_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_dose_status`
--

CREATE TABLE `vaccine_dose_status` (
  `vds_id` int(11) NOT NULL,
  `vds_vdq_id` int(11) NOT NULL DEFAULT 0,
  `vds_vcc_id` int(11) NOT NULL DEFAULT 0,
  `vds_vccn_id` int(11) NOT NULL DEFAULT 0,
  `vds_batch_id` int(11) NOT NULL DEFAULT 0,
  `vds_lshs_id` int(11) NOT NULL DEFAULT 0,
  `vds_sh_id` int(11) NOT NULL DEFAULT 0,
  `vds_status` int(11) NOT NULL DEFAULT 0,
  `vds_created_by` int(11) NOT NULL DEFAULT 0,
  `vds_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `vds_updated_by` int(11) NOT NULL DEFAULT 0,
  `vds_updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `vds_vaccine_used_quantity` double NOT NULL DEFAULT 0,
  `vds_description` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_purchase_summary`
--

CREATE TABLE `vaccine_purchase_summary` (
  `vps_id` int(11) NOT NULL,
  `vps_s_id` int(11) NOT NULL DEFAULT 0,
  `vps_date` date NOT NULL DEFAULT '1970-01-01',
  `vps_sub_total` double NOT NULL DEFAULT 0,
  `vps_grand_discount` double NOT NULL DEFAULT 0,
  `vps_grand_total` double NOT NULL DEFAULT 0,
  `vps_description` varchar(500) NOT NULL DEFAULT '',
  `vps_status` int(11) NOT NULL DEFAULT 0,
  `vps_created_by` int(11) NOT NULL DEFAULT 0,
  `vps_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `vps_updated_by` int(11) NOT NULL DEFAULT 0,
  `vps_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_purchase_value`
--

CREATE TABLE `vaccine_purchase_value` (
  `vpv_id` int(11) NOT NULL,
  `vpv_vps_id` int(11) NOT NULL DEFAULT 0,
  `vpv_vcc_id` int(11) NOT NULL DEFAULT 0,
  `vpv_unit_price` double NOT NULL DEFAULT 0,
  `vpv_quantity` double NOT NULL DEFAULT 0,
  `vpv_discount` double NOT NULL DEFAULT 0,
  `vpv_total` double NOT NULL DEFAULT 0,
  `vpv_status` int(11) NOT NULL DEFAULT 0,
  `vpv_created_by` int(11) NOT NULL DEFAULT 0,
  `vpv_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `vpv_updated_by` int(11) NOT NULL DEFAULT 0,
  `vpv_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_route`
--

CREATE TABLE `vaccine_route` (
  `vccr_id` int(11) NOT NULL,
  `vccr_name` varchar(255) NOT NULL DEFAULT '',
  `vccr_description` varchar(500) NOT NULL DEFAULT '',
  `vccr_status` int(11) NOT NULL DEFAULT 0,
  `vccr_created_by` int(11) NOT NULL DEFAULT 0,
  `vccr_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `vccr_updated_by` int(11) NOT NULL DEFAULT 0,
  `vccr_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_used`
--

CREATE TABLE `vaccine_used` (
  `vccu_id` int(11) NOT NULL,
  `vccu_vcc_id` int(11) NOT NULL DEFAULT 0,
  `vccu_used_quantity` double NOT NULL DEFAULT 0,
  `vccu_description` varchar(255) NOT NULL DEFAULT '',
  `vccu_status` int(11) NOT NULL DEFAULT 0,
  `vccu_created_by` int(11) NOT NULL DEFAULT 0,
  `vccu_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `vccu_updated_by` int(11) NOT NULL DEFAULT 0,
  `vccu_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_wasted`
--

CREATE TABLE `vaccine_wasted` (
  `vccw_id` int(11) NOT NULL,
  `vccw_vcc_id` int(11) NOT NULL DEFAULT 0,
  `vccw_quantity` double NOT NULL DEFAULT 0,
  `vccw_description` varchar(500) NOT NULL DEFAULT '',
  `vccw_status` int(11) NOT NULL DEFAULT 0,
  `vccw_created_by` int(11) NOT NULL DEFAULT 0,
  `vccw_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `vccw_updated_by` int(11) NOT NULL DEFAULT 0,
  `vccw_updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `client_payment`
--
ALTER TABLE `client_payment`
  ADD PRIMARY KEY (`cp_id`);

--
-- Indexes for table `client_type`
--
ALTER TABLE `client_type`
  ADD PRIMARY KEY (`ct_id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`ex_id`);

--
-- Indexes for table `expense_category`
--
ALTER TABLE `expense_category`
  ADD PRIMARY KEY (`exc_id`);

--
-- Indexes for table `expense_payment`
--
ALTER TABLE `expense_payment`
  ADD PRIMARY KEY (`exp_id`);

--
-- Indexes for table `expense_subcategory`
--
ALTER TABLE `expense_subcategory`
  ADD PRIMARY KEY (`exsc_id`);

--
-- Indexes for table `food_distributed_summary`
--
ALTER TABLE `food_distributed_summary`
  ADD PRIMARY KEY (`fdds_id`);

--
-- Indexes for table `food_distributed_value`
--
ALTER TABLE `food_distributed_value`
  ADD PRIMARY KEY (`fddv_id`);

--
-- Indexes for table `food_purchase_summary`
--
ALTER TABLE `food_purchase_summary`
  ADD PRIMARY KEY (`fdps_id`);

--
-- Indexes for table `food_purchase_value`
--
ALTER TABLE `food_purchase_value`
  ADD PRIMARY KEY (`fdpv_id`);

--
-- Indexes for table `food_summary`
--
ALTER TABLE `food_summary`
  ADD PRIMARY KEY (`fds_id`);

--
-- Indexes for table `food_value`
--
ALTER TABLE `food_value`
  ADD PRIMARY KEY (`fdv_id`);

--
-- Indexes for table `food_wasted`
--
ALTER TABLE `food_wasted`
  ADD PRIMARY KEY (`fdw_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `livestock`
--
ALTER TABLE `livestock`
  ADD PRIMARY KEY (`ls_id`);

--
-- Indexes for table `livestock_death_quantity`
--
ALTER TABLE `livestock_death_quantity`
  ADD PRIMARY KEY (`ld_id`);

--
-- Indexes for table `livestock_purchase_summary`
--
ALTER TABLE `livestock_purchase_summary`
  ADD PRIMARY KEY (`purs_id`);

--
-- Indexes for table `livestock_purchase_value`
--
ALTER TABLE `livestock_purchase_value`
  ADD PRIMARY KEY (`purv_id`);

--
-- Indexes for table `livestock_reproduction`
--
ALTER TABLE `livestock_reproduction`
  ADD PRIMARY KEY (`lrp_id`);

--
-- Indexes for table `livestock_sale_summary`
--
ALTER TABLE `livestock_sale_summary`
  ADD PRIMARY KEY (`lsss_id`);

--
-- Indexes for table `livestock_sale_value`
--
ALTER TABLE `livestock_sale_value`
  ADD PRIMARY KEY (`lssv_id`);

--
-- Indexes for table `livestock_transfer_quantity`
--
ALTER TABLE `livestock_transfer_quantity`
  ADD PRIMARY KEY (`ltr_id`);

--
-- Indexes for table `livestock_type`
--
ALTER TABLE `livestock_type`
  ADD PRIMARY KEY (`lst_id`);

--
-- Indexes for table `live_assigned_shed`
--
ALTER TABLE `live_assigned_shed`
  ADD PRIMARY KEY (`lsh_id`);

--
-- Indexes for table `live_assigned_shed_summary`
--
ALTER TABLE `live_assigned_shed_summary`
  ADD PRIMARY KEY (`lshs_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pr_id`);

--
-- Indexes for table `product_assign`
--
ALTER TABLE `product_assign`
  ADD PRIMARY KEY (`pra_id`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`prc_id`);

--
-- Indexes for table `product_sale_summary`
--
ALTER TABLE `product_sale_summary`
  ADD PRIMARY KEY (`prss_id`);

--
-- Indexes for table `product_sale_value`
--
ALTER TABLE `product_sale_value`
  ADD PRIMARY KEY (`prsv_id`);

--
-- Indexes for table `product_stock`
--
ALTER TABLE `product_stock`
  ADD PRIMARY KEY (`prs_id`);

--
-- Indexes for table `product_wasted`
--
ALTER TABLE `product_wasted`
  ADD PRIMARY KEY (`prw_id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shed`
--
ALTER TABLE `shed`
  ADD PRIMARY KEY (`sh_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`sf_id`);

--
-- Indexes for table `staff_payment`
--
ALTER TABLE `staff_payment`
  ADD PRIMARY KEY (`sfp_id`);

--
-- Indexes for table `staff_type`
--
ALTER TABLE `staff_type`
  ADD PRIMARY KEY (`sft_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `supplier_payment`
--
ALTER TABLE `supplier_payment`
  ADD PRIMARY KEY (`sp_id`);

--
-- Indexes for table `supplier_type`
--
ALTER TABLE `supplier_type`
  ADD PRIMARY KEY (`st_id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`un_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- Indexes for table `vaccination`
--
ALTER TABLE `vaccination`
  ADD PRIMARY KEY (`vccn_id`);

--
-- Indexes for table `vaccine`
--
ALTER TABLE `vaccine`
  ADD PRIMARY KEY (`vcc_id`);

--
-- Indexes for table `vaccine_dose_assigned_quantity`
--
ALTER TABLE `vaccine_dose_assigned_quantity`
  ADD PRIMARY KEY (`vdq_id`);

--
-- Indexes for table `vaccine_dose_status`
--
ALTER TABLE `vaccine_dose_status`
  ADD PRIMARY KEY (`vds_id`);

--
-- Indexes for table `vaccine_purchase_summary`
--
ALTER TABLE `vaccine_purchase_summary`
  ADD PRIMARY KEY (`vps_id`);

--
-- Indexes for table `vaccine_purchase_value`
--
ALTER TABLE `vaccine_purchase_value`
  ADD PRIMARY KEY (`vpv_id`);

--
-- Indexes for table `vaccine_route`
--
ALTER TABLE `vaccine_route`
  ADD PRIMARY KEY (`vccr_id`);

--
-- Indexes for table `vaccine_used`
--
ALTER TABLE `vaccine_used`
  ADD PRIMARY KEY (`vccu_id`);

--
-- Indexes for table `vaccine_wasted`
--
ALTER TABLE `vaccine_wasted`
  ADD PRIMARY KEY (`vccw_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_payment`
--
ALTER TABLE `client_payment`
  MODIFY `cp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_type`
--
ALTER TABLE `client_type`
  MODIFY `ct_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `ex_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_category`
--
ALTER TABLE `expense_category`
  MODIFY `exc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_payment`
--
ALTER TABLE `expense_payment`
  MODIFY `exp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_subcategory`
--
ALTER TABLE `expense_subcategory`
  MODIFY `exsc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_distributed_summary`
--
ALTER TABLE `food_distributed_summary`
  MODIFY `fdds_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_distributed_value`
--
ALTER TABLE `food_distributed_value`
  MODIFY `fddv_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_purchase_summary`
--
ALTER TABLE `food_purchase_summary`
  MODIFY `fdps_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_purchase_value`
--
ALTER TABLE `food_purchase_value`
  MODIFY `fdpv_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_summary`
--
ALTER TABLE `food_summary`
  MODIFY `fds_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_value`
--
ALTER TABLE `food_value`
  MODIFY `fdv_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_wasted`
--
ALTER TABLE `food_wasted`
  MODIFY `fdw_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `livestock`
--
ALTER TABLE `livestock`
  MODIFY `ls_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livestock_death_quantity`
--
ALTER TABLE `livestock_death_quantity`
  MODIFY `ld_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livestock_purchase_summary`
--
ALTER TABLE `livestock_purchase_summary`
  MODIFY `purs_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livestock_purchase_value`
--
ALTER TABLE `livestock_purchase_value`
  MODIFY `purv_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livestock_reproduction`
--
ALTER TABLE `livestock_reproduction`
  MODIFY `lrp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livestock_sale_summary`
--
ALTER TABLE `livestock_sale_summary`
  MODIFY `lsss_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livestock_sale_value`
--
ALTER TABLE `livestock_sale_value`
  MODIFY `lssv_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livestock_transfer_quantity`
--
ALTER TABLE `livestock_transfer_quantity`
  MODIFY `ltr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livestock_type`
--
ALTER TABLE `livestock_type`
  MODIFY `lst_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `live_assigned_shed`
--
ALTER TABLE `live_assigned_shed`
  MODIFY `lsh_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `live_assigned_shed_summary`
--
ALTER TABLE `live_assigned_shed_summary`
  MODIFY `lshs_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_assign`
--
ALTER TABLE `product_assign`
  MODIFY `pra_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
  MODIFY `prc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_sale_summary`
--
ALTER TABLE `product_sale_summary`
  MODIFY `prss_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_sale_value`
--
ALTER TABLE `product_sale_value`
  MODIFY `prsv_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_stock`
--
ALTER TABLE `product_stock`
  MODIFY `prs_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_wasted`
--
ALTER TABLE `product_wasted`
  MODIFY `prw_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale`
--
ALTER TABLE `sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shed`
--
ALTER TABLE `shed`
  MODIFY `sh_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `sf_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_payment`
--
ALTER TABLE `staff_payment`
  MODIFY `sfp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_type`
--
ALTER TABLE `staff_type`
  MODIFY `sft_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_payment`
--
ALTER TABLE `supplier_payment`
  MODIFY `sp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_type`
--
ALTER TABLE `supplier_type`
  MODIFY `st_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `un_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=259;

--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- AUTO_INCREMENT for table `vaccination`
--
ALTER TABLE `vaccination`
  MODIFY `vccn_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vaccine`
--
ALTER TABLE `vaccine`
  MODIFY `vcc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vaccine_dose_assigned_quantity`
--
ALTER TABLE `vaccine_dose_assigned_quantity`
  MODIFY `vdq_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vaccine_dose_status`
--
ALTER TABLE `vaccine_dose_status`
  MODIFY `vds_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vaccine_purchase_summary`
--
ALTER TABLE `vaccine_purchase_summary`
  MODIFY `vps_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vaccine_purchase_value`
--
ALTER TABLE `vaccine_purchase_value`
  MODIFY `vpv_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vaccine_route`
--
ALTER TABLE `vaccine_route`
  MODIFY `vccr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vaccine_used`
--
ALTER TABLE `vaccine_used`
  MODIFY `vccu_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vaccine_wasted`
--
ALTER TABLE `vaccine_wasted`
  MODIFY `vccw_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
