<?php
    /**
     * Functions
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version $Id: functions.php, v1.00 2023-01-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    /**
     * sanitize()
     *
     * @param mixed $string
     * @param bool $trim
     * @return array|string|string[]
     */
    function sanitize($string, $trim = false)
    {
        $string = trim($string);
        $string = stripslashes($string);
        $string = strip_tags($string);
        $string = str_replace(array('‘', '’', '“', '”'), array("'", "'", '"', '"'), $string);
        if ($trim)
            $string = substr($string, 0, $trim);
        
        return $string;
    }
    
    /**
     * getIniSettings()
     *
     * @param mixed $aSetting
     * @return string
     */
    function getIniSettings($aSetting)
    {
        return (ini_get($aSetting) == '1' ? 'ON' : 'OFF');
    }
    
    /**
     * sessionKey()
     *
     * @return string
     */
    function sessionKey()
    {
        return substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 16)), 0, 16);
    }
    
    /**
     * getWritableCell()
     *
     * @param mixed $aDir
     * @return void
     */
    function getWritableCell($aDir)
    {
        echo '<tr>';
        echo '<td>' . $aDir . CMS_DS . '</td>';
        echo '<td>';
        echo is_writable(DDPBASE . $aDir) ? '<span class="yes">Writeable</span>' : '<span class="no">Unwriteable</span>';
        echo '</td>';
        echo '</tr>';
    }
    
    /**
     * removeComments()
     *
     * @param mixed $query
     * @return string
     */
    function removeComments($query)
    {
        /*
         * Commented version
         * $sqlComments = '@
         * (([\'"]).*?[^\\\]\2) # $1 : Skip single & double quoted expressions
         * |( # $3 : Match comments
         * (?:\#|--).*?$ # - Single line comments
         * | # - Multi line (nested) comments
         * /\* # . comment open marker
         * (?: [^/*] # . non comment-marker characters
         * |/(?!\*) # . ! not a comment open
         * |\*(?!/) # . ! not a comment close
         * |(?R) # . recursive case
         * )* # . repeat eventually
         * \*\/ # . comment close marker
         * )\s* # Trim after comments
         * |(?<=;)\s+ # Trim after semi-colon
         * @msx';
         */
        $sqlComments = '@(([\'"]).*?[^\\\]\2)|((?:\#|--).*?$|/\*(?:[^/*]|/(?!\*)|\*(?!/)|(?R))*\*\/)\s*|(?<=;)\s+@ms';
        
        $query = trim(preg_replace($sqlComments, '$1', $query));
        
        //Eventually remove the last ;
        if (strrpos($query, ";") === strlen($query) - 1) {
            $query = substr($query, 0, strlen($query) - 1);
        }
        
        return $query;
    }
    
    /**
     * parse()
     *
     * @param mixed $content
     * @return array
     */
    function parse($content)
    {
        
	  $sqlList = array();
	  $lines = explode("\n", file_get_contents($content));
	  $query = "";

	  foreach ($lines as $sql_line) {
		  $sql_line = trim($sql_line);
		  if ($sql_line === "") {
			  continue;
		  } else {
            if (str_starts_with($sql_line, '--') || str_starts_with($sql_line, '#') || $sql_line == '') {
                continue;
            }

			  $query .= $sql_line;
			  if (/*preg_match("/(.*);/", $sql_line)*/str_ends_with(trim($sql_line), ';')) {
				  $query = trim($query);
				  $query = substr($query, 0, strlen($query) - 1);
				  $sqlList[] = $query . ';';
				  $query = "";
			  }
		  }
	  }
		return $sqlList;
    }
    
    /**
     * writeConfigFile()
     *
     * @param mixed $param
     * @param bool $safe
     * @return bool|string
     */
    function writeConfigFile($param, $safe = false)
    {
        
        $content = "<?php \n"
            . "\t/** \n"
            . "\t* Configuration\n"
            . "\n"
            . "\t* @package Wojo Framework\n"
            . "\t* @author wojoscripts.com\n"
            . "\t* @copyright " . date('Y') . "\n"
            . "\t* @version Id: config.ini.php, v1.00 " . date('Y-m-d h:i:s') . " gewa Exp $\n"
            . "\t*/\n"
            
            . " \n"
            . "\t if (!defined(\"_WOJO\")) \n"
            . "     die('Direct access to this location is not allowed.');\n"
            
            . " \n"
            . "\t/** \n"
            . "\t* Database Constants - these constants refer to \n"
            . "\t* the database configuration settings. \n"
            . "\t*/\n"
            . "\t define('DB_SERVER', '" . $param['host'] . "'); \n"
            . "\t define('DB_USER', '" . $param['user'] . "'); \n"
            . "\t define('DB_PASS', '" . $param['pass'] . "'); \n"
            . "\t define('DB_DATABASE', '" . $param['name'] . "');\n"
            . "\t define('DB_DRIVER', 'mysql');\n"
            
            . " \n"
            . "\t define('INSTALL_KEY', '" . $param['key'] . "'); \n"
            
            . " \n"
            . "\t/** \n"
            . "\t* Show Debugger Console. \n"
            . "\t* Display errors in console view. Not recommended for live site. true/false \n"
            . "\t*/\n"
            . "\t define('DEBUG', false);\n"
            . "?>";
        
        if ($safe) {
            return $content;
        } else {
            $config = '../lib/config.ini.php';
            if (is_writable('../lib/')) {
                $handle = fopen($config, 'w');
                fwrite($handle, $content);
                fclose($handle);
                return true;
            } else {
                return false;
            }
        }
    }
    
    /**
     * cmsHeader()
     *
     * @return void
     */
    function cmsHeader()
    {
        
        echo '<!doctype html>' . "\n";
        echo '<html lang="en">' . "\n";
        echo '<head>' . "\n";
        echo '<meta charset="utf-8">' . "\n";
        echo '<title>Wojoscripts - Web Installer</title>' . "\n";
        echo '<link rel="stylesheet" type="text/css" href="style.css">' . "\n";
        echo '</head>' . "\n";
        echo '<body>' . "\n";
        echo '<div id="wrap">' . "\n";
        echo '<header><div><img src="images/logo.svg" alt="W"></div><h4>Welcome to Car Dealer Pro Install Wizard</h4></header>' . "\n";
        echo '<div class="line"></div>' . "\n";
        echo '<div id="content">' . "\n";
    }
    
    /**
     * cmsFooter()
     *
     * @return void
     */
    function cmsFooter()
    {
        
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        echo '<div id="copyright">Wojoscripts<br />' . "\n";
        echo 'Copyright &copy; ' . date("Y") . ' Wojoscripts.com';
        echo '</div>' . "\n";
        echo '<script type="text/javascript">' . "\n";
        
        if (isset($_SESSION['err'])) {
            foreach ($_SESSION['err'] as $i) {
                if ($i > 0) {
                    echo "document.getElementById('err$i').style.display = 'block';\n";
                    echo "document.getElementById('t$i').style.background = '#bf360c';\n";
                }
            }
            echo "document.getElementById('t{$_SESSION['err'][0]}').focus();\n";
        }
        
        echo '</script>' . "\n";
        echo '</body>' . "\n";
        echo '</html>' . "\n";
    }