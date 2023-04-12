<?php
/*
 * Dokuwiki's Main Configuration File - Local Settings
 * Auto-generated by config plugin
 * Run for user: victor
 * Date: Thu, 29 Dec 2022 18:16:53 +0100
 */

$conf['title'] = 'AKDTU bestyrelseswiki';
$conf['lang'] = 'da';
$conf['template'] = 'readthedokus';
$conf['tagline'] = 'Andelskollegiet ved DTU - Akademivej';
$conf['license'] = '0';
$conf['showuseras'] = 'username';
$conf['useheading'] = '1';
$conf['useacl'] = 1;
$conf['authtype'] = 'authwordpress';
$conf['defaultgroup'] = '';
$conf['superuser'] = '@admin,@administrator';
$conf['manager'] = '@editor';
$conf['disableactions'] = 'recent,revisions,register,resendpwd,profile_delete,rss,source,export_raw';
$conf['htmlok'] = 1;
$conf['target']['extern'] = '_blank';
$conf['userewrite'] = '1';
$conf['plugin']['authldap']['attributes'] = array();
$conf['plugin']['authwordpress']['hostname'] = 'mysql78.unoeuro.com';
$conf['plugin']['authwordpress']['port'] = 3306;
$conf['plugin']['authwordpress']['database'] = 'akdtu_dk_db';
$conf['plugin']['authwordpress']['username'] = 'akdtu_dk';
$conf['plugin']['authwordpress']['password'] = 'te62n9pd';
$conf['plugin']['captcha']['loginprotect'] = '0';
$conf['plugin']['dw2pdf']['template'] = 'AKDTU';
$conf['plugin']['dw2pdf']['usecache'] = 0;
$conf['tpl']['readthedokus']['dokuwikibreadcrumbs_enable'] = 1;
$conf['tpl']['readthedokus']['startpage'] = '/wiki/';
$conf['tpl']['readthedokus']['fontawesome_enable'] = 1;
$conf['tpl']['readthedokus']['fontawesome_tag'] = '<link href=\'/fontawesome-free-6.2.1-web/css/all.css\' rel=\'stylesheet\'>';
