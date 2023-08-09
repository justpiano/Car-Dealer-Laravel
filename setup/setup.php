<?php
    /**
     * Setup
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version $Id: setup.php, v1.00 2023-01-05 10:12:05 gewa Exp $
     */
    const _WOJO = true;
    const DEBUG = false;

    ini_set('display_errors', 1);
	error_reporting(E_ALL);
    
    if (strlen(session_id()) < 1)
        session_start();
    
    require_once ("functions.php");
    
    $_SESSION['err'] = null;
    $_SESSION['msg'] = null;
    const CMS_DS = DIRECTORY_SEPARATOR;
    define("BASE", dirname(__file__));
    define("DDPBASE", str_replace('setup', '', BASE));
    
    $script_path = str_replace('/setup', '', dirname($_SERVER['SCRIPT_NAME']));
    $_SERVER['REQUEST_TIME'] = time();
    $step = !isset($_GET['step']) ? 0 : (int)$_GET['step'];
    
    if (isset($_POST['db_action'])) {
        if (empty($_POST['dbhost']))
            $_SESSION['err'][] = 1;
        
        if (empty($_POST['dbuser']))
            $_SESSION['err'][] = 2;
        
        if (empty($_POST['dbname']))
            $_SESSION['err'][] = 3;
        
        if (empty($_POST['site_email']))
            $_SESSION['err'][] = 4;
        
        if (empty($_POST['admin_username']))
            $_SESSION['err'][] = 5;
        
        if (empty($_SESSION['err'])) {
            
            try {
                $db = new PDO("mysql:host=" . $_POST['dbhost'] . ";dbname=" . $_POST['dbname'], $_POST['dbuser'], $_POST['dbpwd'],
				    array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET CHARACTER SET utf8mb4, NAMES utf8mb4, SESSION sql_mode = "", SESSION sql_mode = ""'));
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                
                $error = false;
                $success = true;
                
                /** Writing to database **/
                $sqldata = parse("sql/structure.sql");
                foreach($sqldata as $sql) {
                    $db->exec($sql);
                }
                
                $param = array("host" => $_POST['dbhost'], "user" => $_POST['dbuser'], "pass" => $_POST['dbpwd'], "name" => $_POST['dbname'], "key" => sessionKey());
                writeConfigFile($param);
                
                if ($script_path == "/")
                    $script_path = "";
                
                if ($content = @file_get_contents("../.htaccess")) {
                    if (!stristr($content, "RewriteBase " . $script_path . "/")) {
                        $content = str_replace("RewriteBase /", "RewriteBase " . $script_path . "/", $content);
                        $content = str_replace("ErrorDocument 404 /404.php", "ErrorDocument 404 " . $script_path . "/404.php", $content);
                        if (is_writable("../.htaccess")) {
                            $continue = true;
                        } else {
                            if (@chmod("../.htaccess", 0755)) {
                                $continue = true;
                            } else {
                                $continue = false;
                            }
                        }
                        if ($continue) {
                            if ($handle = @fopen("../.htaccess", "w")) {
                                @fwrite($handle, $content);
                                @fclose($handle);
                            }
                            @chmod("../.htaccess", 0644);
                        }
                    }
                }
                
                $user = (isset($_POST['admin_username'])) ? $_POST['admin_username'] : "";
                $sdir = (isset($_POST['site_dir'])) ? $_POST['site_dir'] : "";
                $company = (isset($_POST['company'])) ? $_POST['company'] : "";
                $site_email = (isset($_POST['site_email'])) ? $_POST['site_email'] : "";
                
                $db->exec("
				INSERT INTO `admins` (
				  username,
				  email,
				  fname,
				  lname,
				  usertype,
				  hash,
				  userlevel,
				  active
				  ) 
				VALUES
				  (
					'" . sanitize($user) . "',
					'" . sanitize($site_email) . "',
					'Web',
					'Master',
					'owner',
					'" . '$2y$10$QCl3.tuOlxnq4zyR2EDPp.gGzynvJW9V5sc3SjgFbo5VneSckUoOS' . "',
					9,
					'y'
				  );");
                
                $db->exec("
                    UPDATE
                      `settings`
                    SET
                      company = '" . sanitize($company) . "',
                      site_dir = '" . sanitize($sdir) . "',
                      site_email = '" . sanitize($site_email) . "'
                      WHERE id=1"
                );
                
                $db = null;
                
                if (!file_exists("../lib/config.inc.php")) {
                    cmsHeader();
                    include ("templates/finish.tpl.php");
                    cmsFooter();
                    exit;
                }
            }
            catch (PDOException $e) {
                $error = true;
                $_SESSION['msg'] = 'Could not connect to MySQL server<br> ' . $e->getMessage();
            }
        }
    }
?>
<?php cmsHeader(); ?>
<?php
    if (!$step):
        clearstatcache();
        
        include ("templates/pre_install.tpl.php");
    elseif ($step == 1):
        include ("templates/license.tpl.php");
    elseif ($step == '2'):
        include ("templates/configuration.tpl.php");
    elseif ($step == '3'):
        include ("templates/finish.tpl.php");
    else:
        echo 'Incorrect step. Please follow installation instructions.';
    endif;
?>
<?php cmsFooter(); ?>