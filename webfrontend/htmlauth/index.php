<?php
/*
 * LoxBerry Plugin Frontend v3/v4 PHP Template
 *
 * Replace PLUGINNAME placeholders and adapt paths/actions for your plugin.
 */

require_once "loxberry_system.php";
require_once "loxberry_web.php";

// Load language files from templates/lang/language_de.ini, language_en.ini, ...
$L = LBSystem::readlanguage("language.ini");

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function plugin_read_template($name) {
    $path = LBPHTMLAUTHDIR . "/../../templates/" . $name;
    // In most LoxBerry setups, LBPTEMPLATEDIR points to /opt/loxberry/templates/plugins/<folder>
    if (defined('LBPTEMPLATEDIR')) {
        $candidate = LBPTEMPLATEDIR . "/" . $name;
        if (is_readable($candidate)) {
            $path = $candidate;
        }
    }
    if (!is_readable($path)) {
        return "<div class=\"plugin-page\"><div class=\"plugin-card\">Template not found: " . h($name) . "</div></div>";
    }
    return file_get_contents($path);
}

function plugin_render_template($template, array $vars) {
    foreach ($vars as $key => $value) {
        $template = str_replace('{{' . $key . '}}', (string)$value, $template);
    }
    return $template;
}

// Handle a simple demo POST without page reload complexity.
// For real plugins, prefer an AJAX endpoint that returns JSON.
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $setting1 = trim($_POST['setting1'] ?? '');
    $setting2 = trim($_POST['setting2'] ?? '');
    $notice = sprintf($L['STATUS.SAVED'] ?? 'Saved: %s / %s', h($setting1), h($setting2));
}

$cssVersion = '100';
$cssHref = '/plugins/' . rawurlencode(LBPPLUGINDIR) . '/css/plugin.css?v=' . rawurlencode($cssVersion);

// $htmlhead must be prepared before lbheader().
// Keep using the plugin CSS even on LoxBerry v4; it acts as fallback and adapter.
$htmlhead = '<link rel="stylesheet" href="' . h($cssHref) . '">' . "\n";

$title = $L['APP.TITLE'] ?? 'PLUGINNAME';
$helpUrl = 'https://wiki.loxberry.de';
$helpTemplate = 'help.html';

// Compatibility mode:
// - false/omitted keeps jQuery Mobile for LoxBerry v3 compatibility.
// - true enables nojqm mode, recommended for pure LoxBerry v4 Design System pages.
// For this v3/v4 template, keep default mode and use data-role="none" on custom controls.
LBWeb::lbheader($title, $helpUrl, $helpTemplate);

$template = plugin_read_template('index_php.html');

echo plugin_render_template($template, [
    'APP.TITLE' => h($title),
    'APP.SUBTITLE' => h($L['APP.SUBTITLE'] ?? 'v3/v4 compatible plugin frontend'),
    'FORM.SETTING1' => h($L['FORM.SETTING1'] ?? 'Setting 1'),
    'FORM.SETTING2' => h($L['FORM.SETTING2'] ?? 'Setting 2'),
    'BUTTON.SAVE' => h($L['BUTTON.SAVE'] ?? 'Save'),
    'BUTTON.RESTART' => h($L['BUTTON.RESTART'] ?? 'Restart'),
    'BUTTON.STOP' => h($L['BUTTON.STOP'] ?? 'Stop'),
    'BUTTON.LOG' => h($L['BUTTON.LOG'] ?? 'Show log'),
    'NOTICE' => $notice,
    'LOG_URL' => '/admin/system/tools/logfile.cgi?logfile=plugins/' . rawurlencode(LBPPLUGINDIR) . '/PLUGINNAME.log&header=html&format=template',
]);

LBWeb::lbfooter();
