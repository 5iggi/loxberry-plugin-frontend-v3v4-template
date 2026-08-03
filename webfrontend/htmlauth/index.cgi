#!/usr/bin/perl
use strict;
use warnings;
use utf8;
use open qw(:std :encoding(UTF-8));
use CGI;
use HTML::Template;
use LoxBerry::Web;

my $cgi = CGI->new;

sub plugin_trim {
    my ($v) = @_;
    $v = '' if !defined $v;
    $v =~ s/^\s+//;
    $v =~ s/\s+$//;
    return $v;
}

my $template = HTML::Template->new(
    filename          => 'index.html',
    global_vars       => 1,
    die_on_bad_params => 0,
    utf8              => 1,
);

my $notice = '';
if ($cgi->param('save')) {
    my $setting1 = plugin_trim($cgi->param('setting1'));
    my $setting2 = plugin_trim($cgi->param('setting2'));
    # TODO: save configuration
    $notice = "Saved: $setting1 / $setting2";
}

$template->param(
    setting1 => 'demo1',
    setting2 => 'demo2',
    notice   => $notice,
);

my $plugintitle  = 'PLUGINNAME';
my $helplink     = 'https://wiki.loxberry.de';
my $helptemplate = 'help.html';

our $htmlhead = qq{<link rel="stylesheet" href="/plugins/PLUGINNAME/css/plugin.css?v=100">\n};
LoxBerry::Web::lbheader($plugintitle, $helplink, $helptemplate);
print $template->output();
LoxBerry::Web::lbfooter();
