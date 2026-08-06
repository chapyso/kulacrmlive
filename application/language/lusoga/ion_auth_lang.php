<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Name: Ion Auth Lang - Lusoga
 * Translation: ChatGPT
 *
 * Description: Lusoga language file for Ion Auth messages and errors
 */

// Account Creation
$lang['account_creation_successful']         = 'Akaunti etondibwemu bulungi.';
$lang['account_creation_unsuccessful']       = 'Tekisobose kutonda akaunti.';
$lang['account_creation_duplicate_email']    = 'Endagiriro ya email eyo ekozesebwa dda oba si ntuufu.';
$lang['account_creation_duplicate_username'] = 'Erinnya ly\'omukozesa eryo likozesebwa dda oba si ntuufu.';

$lang['account_creation_missing_default_group'] = 'Ekibiina eky\'okusooka tekitegekeddwa.';
$lang['account_creation_invalid_default_group'] = 'Erinnya ly\'ekibiina eky\'okusooka si ntuufu.';

// Password
$lang['password_change_successful']      = 'Ekigambo ky\'okuyingira kikyusiddwa bulungi.';
$lang['password_change_unsuccessful']    = 'Tekisobose kukyusa ekigambo ky\'okuyingira.';
$lang['forgot_password_successful']      = 'Email ey\'okuzzaawo ekigambo ky\'okuyingira esindikiddwa.';
$lang['forgot_password_unsuccessful']    = 'Tekisobose kuzzaawo ekigambo ky\'okuyingira.';

// Activation
$lang['activate_successful']             = 'Akaunti ekakasiddwa bulungi.';
$lang['activate_unsuccessful']           = 'Tekisobose kukakasa akaunti.';
$lang['deactivate_successful']           = 'Akaunti eggiddwako obusobozi.';
$lang['deactivate_unsuccessful']         = 'Tekisobose kuggyako obusobozi ku akaunti.';
$lang['activation_email_successful']     = 'Email ey\'okukakasa esindikiddwa bulungi.';
$lang['activation_email_unsuccessful']   = 'Tekisobose kusindika email ey\'okukakasa.';

// Login / Logout
$lang['login_successful']                = 'Oyingidde bulungi.';
$lang['login_unsuccessful']              = 'Tekisobose kuyingira.';
$lang['login_unsuccessful_not_active']   = 'Akaunti eno tekakakasiddwa.';
$lang['login_timeout']                   = 'Akaunti esibiddwa okumala akaseera. Gezaako nate oluvannyuma.';
$lang['logout_successful']               = 'Ofulumye bulungi.';

// Account Changes
$lang['update_successful']               = 'Ebikwata ku akaunti bikyusiddwa bulungi.';
$lang['update_unsuccessful']             = 'Tekisobose kukyuusa ebikwata ku akaunti.';
$lang['delete_successful']               = 'Omukozesa asaziddwawo bulungi.';
$lang['delete_unsuccessful']             = 'Tekisobose kusazaawo omukozesa.';

// Groups
$lang['group_creation_successful']       = 'Ekibiina kitondeddwa bulungi.';
$lang['group_already_exists']            = 'Ekibiina kino kyaliwo dda.';
$lang['group_update_successful']         = 'Ebikwata ku kibiina bikyusiddwa bulungi.';
$lang['group_delete_successful']         = 'Ekibiina kisaziddwawo bulungi.';
$lang['group_delete_unsuccessful']       = 'Tekisobose kusazaawo ekibiina.';
$lang['group_delete_notallowed']         = 'Tekikkirizibwa kusazaawo ekibiina kya ba Admin.';
$lang['group_name_required']             = 'Erinnya ly\'ekibiina lyetaagisa.';
$lang['group_name_admin_not_alter']      = 'Erinnya ly\'ekibiina kya ba Admin terikyusibwa.';

// Activation Email
$lang['email_activation_subject']        = 'Okukakasa Akaunti';
$lang['email_activate_heading']          = 'Kakasa akaunti ya %s';
$lang['email_activate_subheading']       = 'Nyiga ku link eno okusobola %s.';
$lang['email_activate_link']             = 'Kakasa Akaunti Yo';

// Forgot Password Email
$lang['email_forgotten_password_subject'] = 'Okuzzaawo Ekigambo ky\'Okuyingira';
$lang['email_forgot_password_heading']    = 'Zzaawo ekigambo ky\'okuyingira kya %s';
$lang['email_forgot_password_subheading'] = 'Nyiga ku link eno okusobola %s.';
$lang['email_forgot_password_link']       = 'Zzaawo Ekigambo Ky\'Okuyingira';

// New Password Email
$lang['email_new_password_subject']      = 'Ekigambo Ky\'Okuyingira Ekipya';
$lang['email_new_password_heading']      = 'Ekigambo ky\'okuyingira ekipya kya %s';
$lang['email_new_password_subheading']   = 'Ekigambo kyo ky\'okuyingira kizziddwawo ne kiba: %s';
