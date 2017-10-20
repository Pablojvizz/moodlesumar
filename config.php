<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'moodle_capsumar';
$CFG->dbuser    = 'root';
$CFG->dbpass    = '$umar';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbsocket' => 0,
);

$CFG->wwwroot   = 'http://127.0.0.1/site';

$CFG->dataroot  = '/home/administrador/Documents/moodledata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

/*
@error_reporting(E_ALL | E_STRICT);
@ini_set('display_errors', '1');
$CFG->debug = (E_ALL | E_STRICT);
$CFG->debugdisplay = 1;
$CFG->debugusers = '10254';
*/
//$CFG->passwordsaltmain = 'v[/ZAM?z!wkTqnUzQ5(lH-l} #R5+';

$CFG->passwordsaltmain = '0z0eCR_zll)xH&>?lGY1Pt<dow';

//e-ABC para que no aparezca el panel de registracion en el login.php
// $CFG->auth_instructions=false;

require_once(dirname(__FILE__) . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
