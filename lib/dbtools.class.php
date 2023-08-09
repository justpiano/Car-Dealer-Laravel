<?php
    /**
     * DB Tools Class
     *
     * @package Digital Downloads Pro
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: class_dbtools.php, v1.00 2022-04-10 10:12:05 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    
    class dbTools
    {
        private static $tables = array();
        const suffix = 'd-M-Y_H-i-s';
        const nl = "\r\n";
        
        
        /**
         * dbTools::doBackup()
         *
         * @param bool $gzip
         * @return false|void
         */
        public static function doBackup($gzip = false)
        {
            if (!($sql = self::fetch())) {
                return false;
            } else {
                $fname = UPLOADS . '/backups/';
                $fname .= date(self::suffix);
                $fname .= ($gzip ? '.sql.gz' : '.sql');
                
                self::save($fname, $sql, $gzip);
                
                $data['backup'] = basename($fname);
                Db::Go()->update(Core::sTable, $data)->where("id", 1, "=")->run();
                
                $tpl = App::View(ADMINBASE . '/snippets/');
                $tpl->backup = $data['backup'];
                $tpl->dbdir = UPLOADS . '/backups/';
                $tpl->template = 'loadDatabaseBackup.tpl.php';
                Message::msgModalReply(Db::Go()->affected(), 'success', Lang::$word->DBM_BKP_OK, $tpl->render());
            }
        }
        
        /**
         * dbTools::doRestore()
         *
         * @param string $fname
         * @return void
         */
        public static function doRestore($fname)
        {
            
            $filename = UPLOADS . '/backups/' . trim($fname);
            $templine = '';
            $lines = file($filename);
            foreach ($lines as $line) {
                if (!str_starts_with($line, '--') && $line != '') {
                    $templine .= $line;
                    if (str_ends_with(trim($line), ';')) {
                        if (!Db::Go()->rawQuery($templine)->run()) {
                            Debug::AddMessage("errors", '<i>Exception</i>', 'during the following query ' . $templine, "session");
                        }
                        $templine = '';
                    }
                }
            }
            
            Message::msgModalReply(true, 'success', str_replace("[NAME]", $_POST['title'], Lang::$word->DBM_RES_OK), '');
            
        }
        
        /**
         * dbTools::getTables()
         *
         * @return array|false
         */
        private static function getTables()
        {
            $value = array();
            
            $_oSTH = Db::Go()->prepare('SHOW TABLES');
            $_oSTH->execute();
            $aResults = $_oSTH->fetchAll(PDO::FETCH_NUM);
            
            if (!$aResults) {
                return false;
            }
            foreach ($aResults as $row) {
                if (empty(self::$tables) or in_array($row[0], self::$tables)) {
                    $value[] = $row[0];
                }
            }
            if (!sizeof($value)) {
                Debug::AddMessage("errors", '<i>Exception</i>', 'No tables found in database', "session");
                return false;
            }
            return $value;
        }
        
        
        /**
         * dbTools::dumpTable()
         *
         * @param mixed $table
         * @return false|string
         */
        private static function dumpTable($table)
        {
            
            $output = '-- --------------------------------------------------' . self::nl;
            $output .= '# -- Table structure for table `' . $table . '`' . self::nl;
            $output .= '-- --------------------------------------------------' . self::nl;
            $output .= 'DROP TABLE IF EXISTS `' . $table . '`;' . self::nl;
            
            $_oSTH = Db::Go()->prepare('SHOW CREATE TABLE ' . $table);
            if (!$_oSTH->execute()) {
                return false;
            }
            $row = $_oSTH->fetch();
            $output .= str_replace("\n", self::nl, $row->{'Create Table'}) . ';';
            $output .= self::nl . self::nl;
            $output .= '-- --------------------------------------------------' . self::nl;
            $output .= '# Dumping data for table `' . $table . '`' . self::nl;
            $output .= '-- --------------------------------------------------' . self::nl . self::nl;
            $output .= self::insert($table);
            $output .= self::nl . self::nl;
            
            return $output;
        }
        
        
        /**
         * dbTools::insert()
         *
         * @param mixed $table
         * @return false|string
         */
        public static function insert($table)
        {
            
            $output = '';
            if (!$query = Db::Go()->select($table)->results()) {
                return false;
            }
            foreach ($query as $result) {
                $fields = '';
                
                $array = get_object_vars($result);
                foreach (array_keys($array) as $value) {
                    $fields .= '`' . $value . '`, ';
                }
                $values = '';
                
                foreach ($array as $value) {
                    $value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
                    $value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
                    $value = str_replace('\\', '\\\\', $value);
                    $value = str_replace('\'', '\\\'', $value);
                    $value = str_replace('\\\n', '\n', $value);
                    $value = str_replace('\\\r', '\r', $value);
                    $value = str_replace('\\\t', '\t', $value);
                    
                    $values .= '\'' . $value . '\', ';
                }
                
                $output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
            }
            return $output;
        }
        
        /**
         * dbTools::fetch()
         *
         * @return false|string
         */
        public static function fetch()
        {
            
            $dump = '-- --------------------------------------------------------------------------------' . self::nl;
            $dump .= '-- ' . self::nl;
            $dump .= '-- @version: ' . DB_DATABASE . '.sql ' . date('M j, Y') . ' ' . date('H:i') . ' gewa' . self::nl;
            $dump .= '-- @package ' . App::Core()->wojon . ' v.' . App::Core()->wojov . self::nl;
            $dump .= '-- @author wojoscripts.com.' . self::nl;
            $dump .= '-- @copyright ' . date('Y') . self::nl;
            $dump .= '-- ' . self::nl;
            $dump .= '-- --------------------------------------------------------------------------------' . self::nl;
            $dump .= '-- Host: ' . DB_SERVER . self::nl;
            $dump .= '-- Database: ' . DB_DATABASE . self::nl;
            $dump .= '-- Time: ' . date('M j, Y') . '-' . date('H:i') . self::nl;
            $dump .= '-- MySQL version: ' . Db::Go()->getAttribute(PDO::ATTR_SERVER_VERSION) . self::nl;
            $dump .= '-- PHP version: ' . phpversion() . self::nl;
            $dump .= '-- --------------------------------------------------------------------------------' . self::nl . self::nl;
            
            $dump .= '#' . self::nl;
            $dump .= '# Database: `' . DB_DATABASE . '`' . self::nl;
            $dump .= '#' . self::nl . self::nl . self::nl;
            
            if (!($tables = self::getTables())) {
                return false;
            }
            foreach ($tables as $table) {
                if (!($table_dump = self::dumpTable($table))) {
                    Debug::AddMessage("errors", '<i>Exception</i>', 'mySQL Error', "session");
                    return false;
                }
                $dump .= $table_dump;
            }
            return $dump;
        }
        
        
        /**
         * dbTools::save()
         *
         * @param mixed $fname
         * @param mixed $sql
         * @param mixed $gzip
         * @return void
         */
        private static function save($fname, $sql, $gzip)
        {
            if ($gzip) {
                if (!($zf = gzopen($fname, 'w9'))) {
                    Debug::AddMessage("errors", '<i>Exception</i>', 'Can not write to ' . $fname, "session");
                    return;
                }
                gzwrite($zf, $sql);
                gzclose($zf);
            } else {
                if (!($f = fopen($fname, 'w'))) {
                    Debug::AddMessage("errors", '<i>Exception</i>', 'Can not write to ' . $fname, "session");
                    return;
                }
                fwrite($f, $sql);
                fclose($f);
            }
        }
        
        /**
         * dbTools::optimizeDb()
         *
         * @return string
         */
        public static function optimizeDb()
        {
            $html = '<table class="wojo basic table">';
            $html .= '<thead><tr>';
            $html .= '<th colspan="2">' . Lang::$word->SYS_DBREPAIRING . '... </th>';
            $html .= '<th colspan="2">' . Lang::$word->SYS_DBOPTIMIZING . '... </th>';
            $html .= '</tr></thead><tbody>';
            
            $sql = "SHOW TABLES FROM " . DB_DATABASE;
            $_oSTH = Db::Go()->prepare($sql);
            $_oSTH->execute();
            $result = $_oSTH->fetchAll(PDO::FETCH_COLUMN);
            $tables = "`" . implode("`, `", $result) . "`";
            Db::Go()->rawQuery("REPAIR TABLE " . $tables)->run();
            Db::Go()->rawQuery("OPTIMIZE TABLE " . $tables)->run();
            foreach ($result as $row) {
                $table = $row[0];
                $html .= '<tr>';
                $html .= '<td>' . $row . '</td>';
                $html .= '<td>';
                
                //$sql = "REPAIR TABLE `" . $row . "`";
                //$result = Db::Go()->prepare($sql);
                //if ($result->execute()) {
                    $html .= "<span class=\"wojo right icon text\">" . Lang::$word->SYS_DBSTATUS . ' <i class="positive icon check"></i></span>';
                //}
                $html .= '</td>';
                $html .= '<td>' . $row . '</td>';
                $html .= '<td>';
                
                //$sql = "OPTIMIZE TABLE `" . $table . "`";
                //$result = Db::Go()->prepare($sql);
                //if ($result->execute()) {
                    $html .= "<span class=\"wojo right icon text\">" . Lang::$word->SYS_DBSTATUS . ' <i class="positive icon check"></i></span>';
                //}
                
                $html .= '</td></tr>';
            }
            $html .= '</tbody></table>';
            
            return $html;
        }
    }