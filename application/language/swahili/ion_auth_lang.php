<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Name: Ion Auth Lang - Kiswahili
 * Translation: ChatGPT
 *
 * Description: Kiswahili language file for Ion Auth messages and errors
 */

// Account Creation
$lang['account_creation_successful']         = 'Akaunti imeundwa kwa mafanikio.';
$lang['account_creation_unsuccessful']       = 'Imeshindikana kuunda akaunti.';
$lang['account_creation_duplicate_email']    = 'Anwani ya barua pepe tayari imetumika au si sahihi.';
$lang['account_creation_duplicate_username'] = 'Jina la mtumiaji tayari limetumika au si sahihi.';

$lang['account_creation_missing_default_group'] = 'Kikundi cha msingi hakijawekwa.';
$lang['account_creation_invalid_default_group'] = 'Jina la kikundi cha msingi si sahihi.';

// Password
$lang['password_change_successful']      = 'Nenosiri limebadilishwa kwa mafanikio.';
$lang['password_change_unsuccessful']    = 'Imeshindikana kubadilisha nenosiri.';
$lang['forgot_password_successful']      = 'Barua pepe ya kurejesha nenosiri imetumwa.';
$lang['forgot_password_unsuccessful']    = 'Imeshindikana kurejesha nenosiri.';

// Activation
$lang['activate_successful']             = 'Akaunti imewezeshwa kwa mafanikio.';
$lang['activate_unsuccessful']           = 'Imeshindikana kuwezesha akaunti.';
$lang['deactivate_successful']           = 'Akaunti imezimwa kwa mafanikio.';
$lang['deactivate_unsuccessful']         = 'Imeshindikana kuzima akaunti.';
$lang['activation_email_successful']     = 'Barua pepe ya uthibitisho imetumwa kwa mafanikio.';
$lang['activation_email_unsuccessful']   = 'Imeshindikana kutuma barua pepe ya uthibitisho.';

// Login / Logout
$lang['login_successful']                = 'Umeingia kwa mafanikio.';
$lang['login_unsuccessful']              = 'Imeshindikana kuingia.';
$lang['login_unsuccessful_not_active']   = 'Akaunti hii haijawezeshwa.';
$lang['login_timeout']                   = 'Akaunti imefungwa kwa muda. Tafadhali jaribu tena baadaye.';
$lang['logout_successful']               = 'Umetoka kwa mafanikio.';

// Account Changes
$lang['update_successful']               = 'Taarifa za akaunti zimesasishwa kwa mafanikio.';
$lang['update_unsuccessful']             = 'Imeshindikana kusasisha taarifa za akaunti.';
$lang['delete_successful']               = 'Mtumiaji amefutwa kwa mafanikio.';
$lang['delete_unsuccessful']             = 'Imeshindikana kufuta mtumiaji.';

// Groups
$lang['group_creation_successful']       = 'Kikundi kimeundwa kwa mafanikio.';
$lang['group_already_exists']            = 'Kikundi hiki tayari kipo.';
$lang['group_update_successful']         = 'Taarifa za kikundi zimesasishwa kwa mafanikio.';
$lang['group_delete_successful']         = 'Kikundi kimefutwa kwa mafanikio.';
$lang['group_delete_unsuccessful']       = 'Imeshindikana kufuta kikundi.';
$lang['group_delete_notallowed']         = 'Hairuhusiwi kufuta kikundi cha Wasimamizi.';
$lang['group_name_required']             = 'Jina la kikundi linahitajika.';
$lang['group_name_admin_not_alter']      = 'Jina la kikundi cha Wasimamizi haliwezi kubadilishwa.';

// Activation Email
$lang['email_activation_subject']        = 'Uthibitisho wa Akaunti';
$lang['email_activate_heading']          = 'Thibitisha akaunti ya %s';
$lang['email_activate_subheading']       = 'Tafadhali bofya kiungo hiki ili %s.';
$lang['email_activate_link']             = 'Thibitisha Akaunti Yako';

// Forgot Password Email
$lang['email_forgotten_password_subject'] = 'Kurejesha Nenosiri';
$lang['email_forgot_password_heading']    = 'Weka upya nenosiri la %s';
$lang['email_forgot_password_subheading'] = 'Tafadhali bofya kiungo hiki ili %s.';
$lang['email_forgot_password_link']       = 'Weka Upya Nenosiri Lako';

// New Password Email
$lang['email_new_password_subject']      = 'Nenosiri Jipya';
$lang['email_new_password_heading']      = 'Nenosiri jipya la %s';
$lang['email_new_password_subheading']   = 'Nenosiri lako limewekwa upya kuwa: %s';
