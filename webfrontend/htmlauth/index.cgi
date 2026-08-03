#!/usr/bin/perl
use strict;
use warnings;
use utf8;
use open qw(:std :encoding(UTF-8));
use CGI;
use FindBin qw($Bin);
use HTML::Template;
use LoxBerry::Web;

my $cgi = CGI->new;

sub plugin_folder {
    my ($folder) = $Bin =~ m{/plugins/([^/]+)$};
    return $folder || 'PLUGINNAME';
}

sub plugin_template_path {
    my ($name) = @_;
    my $folder = plugin_folder();
    my @candidates = (
        "/opt/loxberry/templates/plugins/$folder/$name",
        "$Bin/$name",
        $name,
    );
    for my $path (@candidates) {
        return $path if -r $path;
    }
    die "Template file not found: $name. Checked: " . join(', ', @candidates) . "\n";
}

sub plugin_trim {
    my ($v) = @_;
    $v = '' if !defined $v;
    $v =~ s/^\s+//;
    $v =~ s/\s+$//;
    return $v;
}

sub plugin_ensure_logfile {
    my ($name) = @_;
    my $folder = plugin_folder();
    $name ||= $folder;
    $name = $folder if $name eq 'plugin';
    $name =~ s/[^A-Za-z0-9_.-]//g;
    $name = $folder if $name eq '';
    my $logdir = "/opt/loxberry/log/plugins/$folder";
    my $logfile = "$logdir/$name.log";
    mkdir $logdir, 0775 if !-d $logdir;
    if (!-e $logfile) { open my $fh, '>>', $logfile; close $fh if $fh; }
    chmod 0664, $logfile if -e $logfile;
    return "plugins/$folder/$name.log";
}

if ($cgi->param('showlog')) {
    my $viewer_path = plugin_ensure_logfile($cgi->param('showlog'));
    print $cgi->redirect('/admin/system/tools/logfile.cgi?logfile=' . $viewer_path . '&header=html&format=template');
    exit;
}

my %L = (
    APP_TITLE       => 'PLUGINNAME',
    APP_SUBTITLE    => 'v3/v4-kompatibles Plugin-Frontend mit LoxBerry-Menü, PrimeIcons und Fallback-CSS.',
    NAV_MAIN        => 'Übersicht',
    NAV_SETTINGS    => 'Einstellungen',
    NAV_LOG         => 'Log',
    CARD_SETTINGS   => 'Demo-Einstellungen',
    FORM_SETTING1   => 'Einstellung 1',
    FORM_SETTING2   => 'Einstellung 2',
    HELP_TEXT       => 'Kurzer Hilfetext für ein Textfeld.',
    HELP_PASSWORD   => 'Beispiel für ein Passwortfeld.',
    BUTTON_SAVE     => 'Speichern',
    BUTTON_OPEN_LOG => 'Log öffnen',
    MAIN_TEXT       => 'Dies ist die Startseite der Demo. Das horizontale LoxBerry-Menü wird über die LoxBerry Navbar-Funktion erzeugt.',
    LOG_TEXT        => 'Die Logdatei wird vor dem Öffnen sichergestellt und anschließend im LoxBerry Log Viewer geöffnet.',
);

my $form = plugin_trim($cgi->param('form')) || 'main';
$form = 'main' if $form !~ /^(main|settings|log)$/;

my $notice = '';
if ($cgi->param('save')) {
    my $setting1 = plugin_trim($cgi->param('setting1'));
    my $setting2 = plugin_trim($cgi->param('setting2'));
    $notice = "Gespeichert: $setting1 / $setting2";
}

my $folder = plugin_folder();
our @navbar = (
    { Name => $L{NAV_MAIN},     URL => 'index.cgi?form=main',     active => ($form eq 'main' ? 1 : 0) },
    { Name => $L{NAV_SETTINGS}, URL => 'index.cgi?form=settings', active => ($form eq 'settings' ? 1 : 0) },
    { Name => $L{NAV_LOG},      URL => 'index.cgi?form=log',      active => ($form eq 'log' ? 1 : 0) },
);

our $htmlhead = qq{<link rel="stylesheet" href="/plugins/$folder/css/plugin.css?v=100">\n};
LoxBerry::Web::lbheader($L{APP_TITLE}, 'https://wiki.loxberry.de', 'help.html');

my $template = HTML::Template->new(
    filename          => plugin_template_path('index.html'),
    global_vars       => 1,
    die_on_bad_params => 0,
    utf8              => 1,
);
$template->param(%L);
$template->param(
    FORM          => $form,
    FORM_MAIN     => ($form eq 'main' ? 1 : 0),
    FORM_SETTINGS => ($form eq 'settings' ? 1 : 0),
    FORM_LOG      => ($form eq 'log' ? 1 : 0),
    setting1      => 'demo1',
    setting2      => 'demo2',
    notice        => $notice,
);
print $template->output();
LoxBerry::Web::lbfooter();
