<?php

/**
 ******************************************************************************
 *     P E W - P E W - P E W   D E F A U L T   C O N F I G U R A T I O N      *
 *******************************IT**BEGINS**NAO********************************
 *
 * @package pew
 */

/**
 * Filesystem separator.
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * @var array Configuration array.
 */
$cfg = [];

/**
 * @var string Server string. This goes before URL to assemble a full server URL.
 */
$cfg['host'] = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') 
             . $_SERVER['SERVER_NAME'];

/**
 * @var string Root path (url), '/' in case of root installation.
 */
$cfg['path'] = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

/**
 * @var string Full path (url) to the app, including server name and folder path.
*/
$cfg['app_url'] = $cfg['host'] . $cfg['path'];

/**
 * @var string Full path to the base folder (filesystem).
 */
$cfg['root_folder'] = getcwd() . DS;

/**
 * @var string Full path to the framework folder (filesystem).
 */
$cfg['system_folder'] = dirname(__FILE__) . DS;

/**
 * @var string Full path to the framework default folder (filesystem).
 */
$cfg['default_folder'] = $cfg['system_folder'] . 'default' . DS;

/**
 * @var string Full path to the application folder (filesystem).
 */
$cfg['app_folder'] = getcwd() . DS . 'app' . DS;

/**
 * @var string Full path to the public assets folder (filesystem).
 */
$cfg['www_folder'] = getcwd() . DS . 'www' . DS;

/**
 * @var string Framework version numbers.
 */
$cfg['version_major'] = '0';
$cfg['version_minor'] = '83';
$cfg['version_date'] = '2012-12-06';

/**
 * @var boolean Whether the App is running on the localhost space.
 */
$cfg['localhost'] = in_array($_SERVER['REMOTE_ADDR'], array('localhost', '127.0.0.1', '::1'));

/**
 * @var string Option to use a prefix for action method names in controllers.
 */
$cfg['action_prefix'] = '';

/**
 * @var boolean Define DEBUG if it's not defined in app/config 
 */
$cfg['debug'] = false;

/**
 * @var integer Logging level -- the higher, the more things get logged
 */
$cfg['log_level'] = 0;

/**
 * @var string Default folder names for files.
 *
 * This is used for both the Default folder and the App folder
 */
$cfg['models_folder'] = 'models' . DS;
$cfg['controllers_folder'] = 'controllers' . DS;
$cfg['views_folder'] = 'views' . DS;
$cfg['elements_folder'] = 'views' . DS . 'elements' . DS;
$cfg['layouts_folder'] = 'views' . DS;
$cfg['libraries_folder'] = 'libs' . DS;

/**
 * @var string Default file extensions for files.
 * 
 * The values must include the first period (i.e., '.php')
 */
$cfg['view_ext'] = '.php';
$cfg['element_ext'] = '.php';
$cfg['layout_ext'] = '.layout.php';
$cfg['class_ext'] = '.php';

/**
 * @var string Default controller to use if none is specified (file name)
 */
$cfg['default_controller'] = 'pages';

/**
 * @var string Default action to use if none is specified (no prefix)
 */
$cfg['default_action'] = 'index';

/**
 * @var array Configured routes.
 */
$cfg['routes'] = [];

/**
 * @var boolean Set to true to enable the use of the session functionality.
 */
$cfg['use_session'] = false;

/**
 * @var boolean Set to true to enable the use of database connections.
 */
$cfg['use_db'] = false;

/**
 * @var boolean Set to true to enable the use of the auth library.
 */
$cfg['use_auth'] = false;

/**
 * @var boolean Set to true to use the Twig templating library for views.
 */
$cfg['use_twig'] = false;

/**
 * Base URL of the application (the location of index.php).
 */
define('APP_URL', $cfg['app_url']);

/**
 * Base URL of the application (the location of index.php).
 */
define('PEW_PATH', $cfg['system_folder']);

/**
 * Full path to the base folder (filesystem).
 */
define('BASE_PATH', $cfg['root_folder']);

return $cfg;
