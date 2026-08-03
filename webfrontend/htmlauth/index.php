<?php
/*
 * LoxBerry Plugin Frontend v3/v4 PHP Template
 * Replace PLUGINNAME placeholders and adapt paths/actions for your plugin.
 */

require_once "loxberry_system.php";
require_once "loxberry_web.php";

$L = LBSystem::readlanguage("language.ini");

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function plugin_php_folder() {
    if (defined('LBPPLUGINDIR')) {
        return LBPPLUGINDIR;
    }
    return basename(__DIR__);
}

function plugin_read_template($name) {
    $folder = plugin_php_folder();
    $candidates = [];
    if (defined('LBPTEMPLATEDIR')) {
        $candidates[] = rtrim(LBPTEMPLATEDIR, '/') . '/' . $name;
    }
    $candidates[] = '/opt/loxberry/templates/plugins/' . $folder . '/' . $name;
    $candidates[] = __DIR__ . '/' . $name;

    foreach ($candidates as $path) {
        if (is_readable($path)) {
            return file_get_contents($path);
        }
    }
    return '<div class="plugin-page"><div class="plugin-card">Template not found: ' . h($name) . '</div></div>';
}

function plugin_render_template($template, array $vars) {
    foreach ($vars as $key => $value) {
        $template = str_replace('{{' . $key . '}}', (string)$value, $template);
    }
    return $template;
}

$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $setting1 = trim($_POST['setting1'] ?? '');
    $setting2 = trim($_POST['setting2'] ?? '');
    $notice = sprintf($L['STATUS.SAVED'] ?? 'Saved: %s / %s', h($setting1), h($setting2));
}

$folder = plugin_php_folder();
$cssVersion = '100';
$cssHref = '/plugins/' . rawurlencode($folder) . '/css/plugin.css?v=' . rawurlencode($cssVersion);
$htmlhead = '<link rel="stylesheet" href="' . h($cssHref) . '">' . "\n";

$title = $L['APP.TITLE'] ?? 'PLUGINNAME';
$helpUrl = 'https://wiki.loxberry.de';
$helpTemplate = 'help.html';

// For a mixed LoxBerry v3/v4 template, keep jQuery Mobile available and use data-role="none" on custom controls.
// For a pure LoxBerry v4 Design System page, use: LBWeb::lbheader($title, $helpUrl, $helpTemplate, true);
LBWeb::lbheader($title, $helpUrl, $helpTemplate);

$template = plugin_read_template('index_php.html');

echo plugin_render_template($template, [
    'APP.TITLE' => h($title),
    'APP.SUBTITLE' => h($L['APP.SUBTITLE'] ?? 'v3/v4 compatible plugin frontend'),
    'CARD.SETTINGS' => h($L['CARD.SETTINGS'] ?? 'Demo settings'),
    'FORM.SETTING1' => h($L['FORM.SETTING1'] ?? 'Setting 1'),
    'FORM.SETTING2' => h($L['FORM.SETTING2'] ?? 'Setting 2'),
    'FORM.SELECT1' => h($L['FORM.SELECT1'] ?? 'Selection'),
    'FORM.NUMBER1' => h($L['FORM.NUMBER1'] ?? 'Number'),
    'HELP.TEXT' => h($L['HELP.TEXT'] ?? 'Short help text for a text field.'),
    'HELP.PASSWORD' => h($L['HELP.PASSWORD'] ?? 'Example for a password field.'),
    'BUTTON.SAVE' => h($L['BUTTON.SAVE'] ?? 'Save'),
    'BUTTON.RESTART' => h($L['BUTTON.RESTART'] ?? 'Restart'),
    'BUTTON.STOP' => h($L['BUTTON.STOP'] ?? 'Stop'),
    'BUTTON.LOG' => h($L['BUTTON.LOG'] ?? 'Show log'),
    'NOTICE' => $notice,
    'LOG_URL' => '/admin/system/tools/logfile.cgi?logfile=plugins/' . rawurlencode($folder) . '/PLUGINNAME.log&header=html&format=template',
]);

LBWeb::lbfooter();
