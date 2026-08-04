<?php
require_once "loxberry_system.php";
require_once "loxberry_web.php";

$L = LBSystem::readlanguage("language.ini");

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function plugin_folder() {
    return defined('LBPPLUGINDIR') ? LBPPLUGINDIR : basename(__DIR__);
}

function plugin_template($name) {
    $folder = plugin_folder();
    $paths = [];
    if (defined('LBPTEMPLATEDIR')) {
        $paths[] = rtrim(LBPTEMPLATEDIR, '/') . '/' . $name;
    }
    $paths[] = '/opt/loxberry/templates/plugins/' . $folder . '/' . $name;
    $paths[] = __DIR__ . '/' . $name;

    foreach ($paths as $path) {
        if (is_readable($path)) {
            return file_get_contents($path);
        }
    }
    return '<div class="plugin-page"><div class="plugin-card">Template not found</div></div>';
}

function render_template($template, $vars) {
    foreach ($vars as $key => $value) {
        $template = str_replace('{{' . $key . '}}', (string)$value, $template);
    }
    return $template;
}

function service_pid() {
    $pid = trim((string)@shell_exec('pgrep -f PLUGINNAME 2>/dev/null | head -n1'));
    return $pid !== '' ? $pid : '-';
}

function loglist_html_template() {
    if (class_exists('LBWeb') && method_exists('LBWeb', 'loglist_html')) {
        $html = LBWeb::loglist_html();
        if ($html) {
            return $html;
        }
    }
    return '<p class="plugin-muted">No LoxBerry session logs available yet.</p>';
}

// -----------------------------------------------------------------------------
// Actions
// -----------------------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'createdemolog') {
    @shell_exec('/opt/loxberry/bin/plugins/' . plugin_folder() . '/create_demo_log.sh >/dev/null 2>&1');
}

// -----------------------------------------------------------------------------
// Routing and navbar
// -----------------------------------------------------------------------------
$form = $_GET['form'] ?? 'main';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $form = 'settings';
}
if (!preg_match('/^(main|settings|log)$/', $form)) {
    $form = 'main';
}

$folder = plugin_folder();
$htmlhead = '<link rel="stylesheet" href="/plugins/' . rawurlencode($folder) . '/css/plugin.css?v=102">' . "
";

$navbar[1]['Name'] = $L['NAV.MAIN'] ?? 'Overview';
$navbar[1]['URL'] = 'index.php?form=main';
$navbar[1]['active'] = ($form === 'main');
$navbar[2]['Name'] = $L['NAV.SETTINGS'] ?? 'Settings';
$navbar[2]['URL'] = 'index.php?form=settings';
$navbar[2]['active'] = ($form === 'settings');
$navbar[3]['Name'] = $L['NAV.LOG'] ?? 'Log';
$navbar[3]['URL'] = 'index.php?form=log';
$navbar[3]['active'] = ($form === 'log');

$title = $L['APP.TITLE'] ?? 'PLUGINNAME';
LBWeb::lbheader($title, 'https://wiki.loxberry.de', 'help.html');

// -----------------------------------------------------------------------------
// Shared service bar
// -----------------------------------------------------------------------------
$pid = service_pid();
$status = ($pid !== '-') ? ($L['SERVICE.RUNNING'] ?? 'running') : ($L['SERVICE.STOPPED'] ?? 'stopped');
$serviceBar = '<section class="lb-card plugin-card plugin-servicebar">'
    . '<div class="plugin-service-actions">'
    . '<a class="lb-btn plugin-button" data-role="none" href="index.php?action=restart&form=' . h($form) . '"><i class="pi pi-refresh"></i><span>' . h($L['SERVICE.RESTART'] ?? 'Restart') . '</span></a>'
    . '<a class="lb-btn plugin-button plugin-button-danger" data-role="none" href="index.php?action=stop&form=' . h($form) . '"><i class="pi pi-stop-circle"></i><span>' . h($L['SERVICE.STOP'] ?? 'Stop') . '</span></a>'
    . '</div>'
    . '<div class="plugin-service-status">'
    . '<span class="plugin-muted">' . h($L['SERVICE.STATUS'] ?? 'Status') . ': <strong>' . h($status) . '</strong></span>'
    . '<span class="plugin-muted">' . h($L['SERVICE.PID'] ?? 'PID') . ': <strong>' . h($pid) . '</strong></span>'
    . '</div></section>';

// -----------------------------------------------------------------------------
// Content
// -----------------------------------------------------------------------------
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $notice = sprintf($L['STATUS.SAVED'] ?? 'Saved: %s / %s', h($_POST['setting1'] ?? ''), h($_POST['setting2'] ?? ''));
}

if ($form === 'main') {
    $content = '<section class="lb-card plugin-card"><h3><i class="pi pi-home"></i> ' . h($L['NAV.MAIN'] ?? 'Overview') . '</h3>'
        . '<p class="plugin-muted">' . h($L['TEXT.MAIN'] ?? '') . '</p>'
        . '<div class="plugin-actions">'
        . '<a class="lb-btn plugin-button" data-role="none" href="index.php?form=settings"><i class="pi pi-cog"></i><span>' . h($L['NAV.SETTINGS'] ?? 'Settings') . '</span></a>'
        . '<a class="lb-btn plugin-button" data-role="none" href="index.php?form=log"><i class="pi pi-file"></i><span>' . h($L['NAV.LOG'] ?? 'Log') . '</span></a>'
        . '</div></section>';
} elseif ($form === 'settings') {
    $content = '<section class="lb-card plugin-card"><h3><i class="pi pi-cog"></i> ' . h($L['CARD.SETTINGS'] ?? 'Settings') . '</h3>'
        . '<form method="post" action="index.php?form=settings" data-role="none"><input type="hidden" name="form" value="settings">'
        . '<div class="plugin-grid-2">'
        . '<div class="plugin-field lb-form-row"><label class="lb-form-label" for="setting1">' . h($L['FORM.SETTING1'] ?? 'Setting 1') . '</label><div class="lb-form-field"><input class="lb-input" data-role="none" type="text" name="setting1" id="setting1" value="demo1"><div class="plugin-help">' . h($L['HELP.TEXT'] ?? '') . '</div></div></div>'
        . '<div class="plugin-field lb-form-row"><label class="lb-form-label" for="setting2">' . h($L['FORM.SETTING2'] ?? 'Setting 2') . '</label><div class="lb-form-field"><input class="lb-input" data-role="none" type="password" name="setting2" id="setting2" value="demo2"><div class="plugin-help">' . h($L['HELP.PASSWORD'] ?? '') . '</div></div></div>'
        . '</div><div class="plugin-actions"><button type="submit" name="save" value="1" class="lb-btn lb-btn-primary plugin-button plugin-button-primary" data-role="none"><i class="pi pi-check"></i><span>' . h($L['BUTTON.SAVE'] ?? 'Save') . '</span></button></div></form>'
        . '<div class="plugin-status plugin-muted">' . $notice . '</div></section>';
} else {
    $content = '<section class="lb-card plugin-card"><h3><i class="pi pi-file"></i> ' . h($L['NAV.LOG'] ?? 'Log') . '</h3>'
        . '<p class="plugin-muted">' . h($L['TEXT.LOG'] ?? '') . '</p>'
        . '<div class="plugin-actions"><a class="lb-btn plugin-button" data-role="none" href="index.php?action=createdemolog&form=log"><i class="pi pi-plus-circle"></i><span>' . h($L['BUTTON.CREATE_DEMO_LOG'] ?? 'Create demo log') . '</span></a></div>'
        . '<div class="plugin-loglist">' . loglist_html_template() . '</div></section>';
}

echo render_template(plugin_template('index_php.html'), [
    'FORM' => h($form),
    'APP.TITLE' => h($title),
    'APP.SUBTITLE' => h($L['APP.SUBTITLE'] ?? ''),
    'SERVICE_BAR' => $serviceBar,
    'CONTENT' => $content,
]);

LBWeb::lbfooter();
