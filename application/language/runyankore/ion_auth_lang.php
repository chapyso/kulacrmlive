<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Name: Ion Auth Lang - Runyankore
 * Translation: ChatGPT
 *
 * Description: Runyankore language file for Ion Auth messages and errors
 */

// Account Creation
$lang['account_creation_successful']         = 'Akaunti ekahangwa gye.';
$lang['account_creation_unsuccessful']       = 'Tikyashobokire kuhanga akaunti.';
$lang['account_creation_duplicate_email']    = 'Email egi ekaba eriho nari teri ntuufu.';
$lang['account_creation_duplicate_username'] = 'Eiziina ry\'omukozesa rikaba ririho nari tiri ntuufu.';

$lang['account_creation_missing_default_group'] = 'Ekibiina ky\'okubanza tikiteirweho.';
$lang['account_creation_invalid_default_group'] = 'Eiziina ry\'ekibiina ky\'okubanza tiri ntuufu.';

// Password
$lang['password_change_successful']      = 'Ekigambo ky\'okushereka kikahindurwa gye.';
$lang['password_change_unsuccessful']    = 'Tikyashobokire kuhindura ekigambo ky\'okushereka.';
$lang['forgot_password_successful']      = 'Email y\'okugarura ekigambo ky\'okushereka esindikire.';
$lang['forgot_password_unsuccessful']    = 'Tikyashobokire kugarura ekigambo ky\'okushereka.';

// Activation
$lang['activate_successful']             = 'Akaunti ekakora gye.';
$lang['activate_unsuccessful']           = 'Tikyashobokire kukoresa akaunti.';
$lang['deactivate_successful']           = 'Akaunti ekazibwaho.';
$lang['deactivate_unsuccessful']         = 'Tikyashobokire kuzibaho akaunti.';
$lang['activation_email_successful']     = 'Email y\'okwemeza esindikire gye.';
$lang['activation_email_unsuccessful']   = 'Tikyashobokire kusindika email y\'okwemeza.';

// Login / Logout
$lang['login_successful']                = 'Otaahire gye.';
$lang['login_unsuccessful']              = 'Tikyashobokire kutaaha.';
$lang['login_unsuccessful_not_active']   = 'Akaunti egi terikukora.';
$lang['login_timeout']                   = 'Akaunti yaawe ekazibwaho akanya kakye. Garuka ogezeho bwanyima.';
$lang['logout_successful']               = 'Ohurukire gye.';

// Account Changes
$lang['update_successful']               = 'Amakuru ga akaunti gahindwirwe gye.';
$lang['update_unsuccessful']             = 'Tikyashobokire kuhindura amakuru ga akaunti.';
$lang['delete_successful']               = 'Omukozesa yasazibwaho gye.';
$lang['delete_unsuccessful']             = 'Tikyashobokire kusazaaho omukozesa.';

// Groups
$lang['group_creation_successful']       = 'Ekibiina kihangirwe gye.';
$lang['group_already_exists']            = 'Ekibiina eki kiriho kare.';
$lang['group_update_successful']         = 'Amakuru g\'ekibiina gahindwirwe gye.';
$lang['group_delete_successful']         = 'Ekibiina kisazibwaho gye.';
$lang['group_delete_unsuccessful']       = 'Tikyashobokire kusazaaho ekibiina.';
$lang['group_delete_notallowed']         = 'Tikikikirizibwa kusazaaho ekibiina ky\'Abareeberezi.';
$lang['group_name_required']             = 'Eiziina ry\'ekibiina niryetagisa.';
$lang['group_name_admin_not_alter']      = 'Eiziina ry\'ekibiina ky\'Abareeberezi tirihindurwa.';

// Activation Email
$lang['email_activation_subject']        = 'Kwemeza Akaunti';
$lang['email_activate_heading']          = 'Yemeza akaunti ya %s';
$lang['email_activate_subheading']       = 'Nyiga aha linki egi kugira ngu %s.';
$lang['email_activate_link']             = 'Yemeza Akaunti Yawe';

// Forgot Password Email
$lang['email_forgotten_password_subject'] = 'Garura Ekigambo ky\'Okushereka';
$lang['email_forgot_password_heading']    = 'Garura ekigambo ky\'okushereka kya %s';
$lang['email_forgot_password_subheading'] = 'Nyiga aha linki egi kugira ngu %s.';
$lang['email_forgot_password_link']       = 'Garura Ekigambo Ky\'Okushereka';

// New Password Email
$lang['email_new_password_subject']      = 'Ekigambo Ky\'Okushereka Kihyaka';
$lang['email_new_password_heading']      = 'Ekigambo ky\'okushereka kihyaka kya %s';
$lang['email_new_password_subheading']   = 'Ekigambo kyawe ky\'okushereka kihindwirwe kuba: %s';
