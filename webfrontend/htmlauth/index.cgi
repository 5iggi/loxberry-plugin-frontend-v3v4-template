#!/usr/bin/perl
use strict;
use warnings;
use utf8;
use CGI;
use FindBin qw($Bin);
use HTML::Template;
use LoxBerry::Web;

binmode STDOUT, ':encoding(UTF-8)';
my $cgi = CGI->new;

# -----------------------------------------------------------------------------
# Helpers
# -----------------------------------------------------------------------------
sub plugin_folder {
    my ($folder) = $Bin =~ m{/plugins/([^/]+)$};
    return $folder || 'PLUGINNAME';
}

sub plugin_template_path {
    my ($name) = @_;
    my $folder = plugin_folder();
    for my $path ("/opt/loxberry/templates/plugins/$folder/$name", "$Bin/$name", $name) {
        return $path if -r $path;
    }
    die "Template not found: $name
";
}

sub trim {
    my ($value) = @_;
    $value = '' if !defined $value;
    $value =~ s/^\s+//;
    $value =~ s/\s+$//;
    return $value;
}

sub service_pid {
    my $pid = `pgrep -f PLUGINNAME 2>/dev/null | head -n1`;
    chomp $pid;
    return $pid || '-';
}

sub fix_mojibake {
    my ($text) = @_;
    return '' if !defined $text;
    $text =~ s/Ã/Ö/g;
    $text =~ s/Ã/Ä/g;
    $text =~ s/Ã/Ü/g;
    $text =~ s/Ã¶/ö/g;
    $text =~ s/Ã¤/ä/g;
    $text =~ s/Ã¼/ü/g;
    $text =~ s/ÃŸ/ß/g;
    return $text;
}

sub loxberry_loglist_html {
    my $html = '';
    eval { $html = loglist_html(); };
    if (!$html) {
        eval { $html = LoxBerry::Web::loglist_html(); };
    }
    if (!$html) {
        no strict 'refs';
        if (defined &{"LoxBerryWebloglist_html"}) {
            eval { $html = &{"LoxBerryWebloglist_html"}(); };
        }
    }
    return fix_mojibake($html) || '<p class="plugin-muted">No LoxBerry session logs available yet.</p>';
}

# -----------------------------------------------------------------------------
# Actions
# -----------------------------------------------------------------------------
if ($cgi->param('action')) {
    my $action = trim($cgi->param('action'));
    if ($action eq 'createdemolog') {
        my $script = '/opt/loxberry/bin/plugins/' . plugin_folder() . '/create_demo_log.sh';
        system($script . ' >/dev/null 2>&1') if -x $script;
    }
}

# -----------------------------------------------------------------------------
# Demo text values
# -----------------------------------------------------------------------------
my %L = (
    APP_TITLE              => 'PLUGINNAME',
    APP_SUBTITLE           => 'v3/v4-kompatibles Plugin-Frontend mit LoxBerry-Menü, PrimeIcons und Fallback-CSS.',
    NAV_MAIN               => 'Übersicht',
    NAV_SETTINGS           => 'Einstellungen',
    NAV_LOG                => 'Log',
    CARD_SETTINGS          => 'Demo-Einstellungen',
    FORM_SETTING1          => 'Einstellung 1',
    FORM_SETTING2          => 'Einstellung 2',
    HELP_TEXT              => 'Kurzer Hilfetext für ein Textfeld.',
    HELP_PASSWORD          => 'Beispiel für ein Passwortfeld.',
    BUTTON_SAVE            => 'Speichern',
    BUTTON_CREATE_DEMO_LOG => 'Demo-Log erzeugen',
    BUTTON_RESTART         => 'Neustart',
    BUTTON_STOP            => 'Stop',
    LABEL_STATUS           => 'Status',
    LABEL_PID              => 'PID',
    MAIN_TEXT              => 'Dies ist die Startseite der Demo. Das horizontale LoxBerry-Menü wird über die LoxBerry Navbar-Funktion erzeugt.',
    LOG_TEXT               => 'Die Logseite bindet die LoxBerry-Logliste ein. Logs werden über das LoxBerry Logging SDK erzeugt.',
);

# -----------------------------------------------------------------------------
# Routing and navbar
# -----------------------------------------------------------------------------
my $form = trim($cgi->param('form')) || 'main';
$form = 'settings' if $cgi->param('save');
$form = 'main' if $form !~ /^(main|settings|log)$/;

our %navbar;
$navbar{10}{Name}   = $L{NAV_MAIN};
$navbar{10}{URL}    = 'index.cgi?form=main';
$navbar{10}{active} = ($form eq 'main') ? 1 : 0;

$navbar{20}{Name}   = $L{NAV_SETTINGS};
$navbar{20}{URL}    = 'index.cgi?form=settings';
$navbar{20}{active} = ($form eq 'settings') ? 1 : 0;

$navbar{30}{Name}   = $L{NAV_LOG};
$navbar{30}{URL}    = 'index.cgi?form=log';
$navbar{30}{active} = ($form eq 'log') ? 1 : 0;

# -----------------------------------------------------------------------------
# Form handling
# -----------------------------------------------------------------------------
my $notice = '';
if ($cgi->param('save')) {
    $notice = 'Gespeichert: ' . trim($cgi->param('setting1')) . ' / ' . trim($cgi->param('setting2'));
}

# -----------------------------------------------------------------------------
# Rendering
# -----------------------------------------------------------------------------
my $folder = plugin_folder();
our $htmlhead = qq{<link rel="stylesheet" href="/plugins/$folder/css/plugin.css?v=102">
};
LoxBerry::Web::lbheader($L{APP_TITLE}, 'https://wiki.loxberry.de', 'help.html');

my $template = HTML::Template->new(
    filename          => plugin_template_path('index.html'),
    global_vars       => 1,
    die_on_bad_params => 0,
    utf8              => 1,
);

$template->param(%L);
my $pid = service_pid();
$template->param(
    FORM           => $form,
    FORM_MAIN      => ($form eq 'main') ? 1 : 0,
    FORM_SETTINGS  => ($form eq 'settings') ? 1 : 0,
    FORM_LOG       => ($form eq 'log') ? 1 : 0,
    setting1       => 'demo1',
    setting2       => 'demo2',
    notice         => $notice,
    SERVICE_PID    => $pid,
    SERVICE_STATUS => ($pid ne '-') ? 'läuft' : 'gestoppt',
    LOGLIST        => loxberry_loglist_html(),
);

print $template->output();
LoxBerry::Web::lbfooter();
