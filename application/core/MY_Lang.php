<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Language loader that never fatals on a missing or mis-cased language.
 *
 * - Matches the language folder case-insensitively (Linux is case-sensitive, Windows is not).
 * - If the language has no such file (e.g. german has no system_syntax_lang.php), falls back to English.
 * - For any non-English language, English is loaded first so untranslated keys still show text.
 */
class MY_Lang extends CI_Lang {

    protected $lang_dirs = null;

    /**
     * Folder name under application/language matching $idiom regardless of case, or null.
     */
    protected function match_language_dir($idiom) {
        if ($this->lang_dirs === null) {
            $this->lang_dirs = array();
            foreach ((array)@scandir(APPPATH . 'language') as $entry) {
                if ($entry !== '.' && $entry !== '..' && is_dir(APPPATH . 'language/' . $entry)) {
                    $this->lang_dirs[strtolower($entry)] = $entry;
                }
            }
        }
        return isset($this->lang_dirs[strtolower($idiom)]) ? $this->lang_dirs[strtolower($idiom)] : null;
    }

    public function load($langfile, $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '') {
        if (is_array($langfile) || $alt_path !== '' || $return === TRUE) {
            return parent::load($langfile, $idiom, $return, $add_suffix, $alt_path);
        }

        if (empty($idiom) || !preg_match('/^[a-z_-]+$/i', $idiom)) {
            $config =& get_config();
            $idiom = empty($config['language']) ? 'english' : $config['language'];
        }

        $file = str_replace('.php', '', $langfile);
        if ($add_suffix === TRUE) {
            $file = preg_replace('/_lang$/', '', $file) . '_lang';
        }
        $file .= '.php';

        $dir = $this->match_language_dir($idiom);
        if ($dir === null || !file_exists(APPPATH . 'language/' . $dir . '/' . $file)) {
            $english = $this->match_language_dir('english');
            if ($english !== null && file_exists(APPPATH . 'language/' . $english . '/' . $file)) {
                log_message('error', "Language file language/{$idiom}/{$file} not found; using English.");
                $dir = $english;
            } else {
                // Not an app language file (e.g. a system/library file): let CodeIgniter handle it as usual
                return parent::load($langfile, $idiom, $return, $add_suffix, $alt_path);
            }
        }

        // English first as the base so missing translations fall back to English text
        if (strtolower($dir) !== 'english') {
            $english = $this->match_language_dir('english');
            if ($english !== null && file_exists(APPPATH . 'language/' . $english . '/' . $file)) {
                parent::load($langfile, $english, FALSE, $add_suffix, $alt_path);
            }
        }

        return parent::load($langfile, $dir, $return, $add_suffix, $alt_path);
    }
}
