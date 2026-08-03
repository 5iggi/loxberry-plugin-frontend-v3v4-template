<?php
require_once "loxberry_system.php";
require_once "loxberry_web.php";

$L = LBSystem::readlanguage("language.ini");

function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function plugin_folder() { return defined('LBPPLUGINDIR') ? LBPPLUGINDIR : basename(__DIR__); }
function plugin_template($name) {
    $folder = plugin_folder();
    $paths = [];
    if (defined('LBPTEMPLATEDIR')) $paths[] = rtrim(LBPTEMPLATEDIR, '/') . '/' . $name;
    $paths[] = '/opt/loxberry/templates/plugins/' . $folder . '/' . $name;
    $paths[] = __DIR__ . '/' . $name;
    foreach ($paths as $p) if (is_readable($p)) return file_get_contents($p);
    return '<div class="plugin-page"><div class="plugin-card">Template not found: '.h($name).'</div></div>';
}
function plugin_render($tpl, $vars) { foreach ($vars as $k=>$v) $tpl = str_replace('{{'.$k.'}}', (string)$v, $tpl); return $tpl; }
function plugin_ensure_logfile($name = '') {
    $folder = plugin_folder();
    if ((string)$name === 'plugin') $name = $folder;
    $name = preg_replace('/[^A-Za-z0-9_.-]/', '', (string)$name);
    if ($name === '') $name = $folder;
    $logdir = '/opt/loxberry/log/plugins/' . $folder;
    $logfile = $logdir . '/' . $name . '.log';
    if (!is_dir($logdir)) @mkdir($logdir, 0775, true);
    if (!file_exists($logfile)) @touch($logfile);
    @chmod($logfile, 0664);
    return 'plugins/' . rawurlencode($folder) . '/' . rawurlencode($name . '.log');
}

if (isset($_GET['showlog'])) {
    $viewerPath = plugin_ensure_logfile($_GET['showlog']);
    header('Location: /admin/system/tools/logfile.cgi?logfile=' . $viewerPath . '&header=html&format=template');
    exit;
}

$form = $_GET['form'] ?? 'main';
if (!preg_match('/^(main|settings|log)$/', $form)) $form = 'main';

$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $notice = sprintf($L['STATUS.SAVED'] ?? 'Saved: %s / %s', h($_POST['setting1'] ?? ''), h($_POST['setting2'] ?? ''));
}

$folder = plugin_folder();
$cssHref = '/plugins/' . rawurlencode($folder) . '/css/plugin.css?v=100';
$htmlhead = '<link rel="stylesheet" href="' . h($cssHref) . '">' . "\n";

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

$content = '';
if ($form === 'main') {
    $content = '<section class="lb-card plugin-card"><h3><i class="pi pi-home"></i> '.h($L['NAV.MAIN'] ?? 'Overview').'</h3><p class="plugin-muted">'.h($L['TEXT.MAIN'] ?? '').'</p><div class="plugin-actions"><a class="lb-btn plugin-button" data-role="none" href="index.php?form=settings"><i class="pi pi-cog"></i><span>'.h($L['NAV.SETTINGS'] ?? 'Settings').'</span></a><a class="lb-btn plugin-button" data-role="none" href="index.php?form=log"><i class="pi pi-file"></i><span>'.h($L['NAV.LOG'] ?? 'Log').'</span></a></div></section>';
} elseif ($form === 'settings') {
    $content = '<section class="lb-card plugin-card"><h3><i class="pi pi-cog"></i> '.h($L['CARD.SETTINGS'] ?? 'Demo settings').'</h3><form method="post" action="index.php?form=settings" data-role="none"><div class="plugin-grid-2"><div class="plugin-field lb-form-row"><label class="lb-form-label" for="setting1">'.h($L['FORM.SETTING1'] ?? 'Setting 1').'</label><div class="lb-form-field"><input class="lb-input" data-role="none" type="text" name="setting1" id="setting1" value="demo1"><div class="plugin-help">'.h($L['HELP.TEXT'] ?? '').'</div></div></div><div class="plugin-field lb-form-row"><label class="lb-form-label" for="setting2">'.h($L['FORM.SETTING2'] ?? 'Setting 2').'</label><div class="lb-form-field"><input class="lb-input" data-role="none" type="password" name="setting2" id="setting2" value="demo2"><div class="plugin-help">'.h($L['HELP.PASSWORD'] ?? '').'</div></div></div></div><div class="plugin-actions"><button type="submit" name="save" value="1" class="lb-btn lb-btn-primary plugin-button plugin-button-primary" data-role="none"><i class="pi pi-check"></i><span>'.h($L['BUTTON.SAVE'] ?? 'Save').'</span></button></div></form><div class="plugin-status plugin-muted">'.$notice.'</div></section>';
} else {
    $content = '<section class="lb-card plugin-card"><h3><i class="pi pi-file"></i> '.h($L['NAV.LOG'] ?? 'Log').'</h3><p class="plugin-muted">'.h($L['TEXT.LOG'] ?? '').'</p><div class="plugin-actions"><a class="lb-btn plugin-button" data-role="none" href="index.php?showlog=plugin" target="plugin_log"><i class="pi pi-external-link"></i><span>'.h($L['BUTTON.OPEN_LOG'] ?? 'Open log').'</span></a></div></section>';
}

echo plugin_render(plugin_template('index_php.html'), [
    'FORM' => h($form),
    'APP.TITLE' => h($title),
    'APP.SUBTITLE' => h($L['APP.SUBTITLE'] ?? ''),
    'CONTENT' => $content,
]);

LBWeb::lbfooter();
