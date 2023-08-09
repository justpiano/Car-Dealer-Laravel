<?php
    
    class Db extends PDO
    {
        
        protected static ?object $oPDO = null;
        protected static int $count = 0;
        
        private string $query;
        private string $raw;
        private array|object|string $queryResult = array();
        private string $action;
        private string $which;
        private string $table;
        private array $values = [];
        private array $where = [];
        private array $whereValues = [];
        private array $bindValues = [];
        private string $groupBy;
        private string $having;
        private array $orderBy = [];
        private string $limit;
        private int $offset;
        private int $iLastId = 0;
        private array $iAllLastId = array();
        private int $rowCount = 0;
        private string $server_info;
        private string $_dbName;
        public array $db_info = array();
        protected string $error = "";
        
        /**
         * Db::__construct()
         *
         */
        public function __construct()
        {
            try {
                if (DB_DATABASE != '') {
                    parent::__construct(DB_DRIVER . ':host=' . DB_SERVER . ';dbname=' . DB_DATABASE, DB_USER, DB_PASS,
                        array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET CHARACTER SET utf8mb4, NAMES utf8mb4, SESSION sql_mode = "", SESSION sql_mode = ""'));
                    
                    $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                    
                    $this->db_info = array(
                        "AUTOCOMMIT",
                        "ERRMODE",
                        "CASE",
                        "CLIENT_VERSION",
                        "CONNECTION_STATUS",
                        "ORACLE_NULLS",
                        "PERSISTENT",
                        "PREFETCH",
                        "SERVER_INFO",
                        "SERVER_VERSION",
                        "TIMEOUT"
                    );
                    $this->_dbName = DB_DATABASE;
                    $this->server_info = parent::getAttribute(PDO::ATTR_SERVER_INFO);
                }
            } catch (PDOException $e) {
                header('HTTP/1.1 503 Service Temporarily Unavailable');
                header('Status: 503 Service Temporarily Unavailable');
                $output = self::_fatalErrorPageContent();
                if (DEBUG) {
                    $output = str_ireplace('{DESCRIPTION}', '<p>This application is currently experiencing some database difficulties</p>', $output);
                    $output = str_ireplace('{CODE}', '<b>Description:</b> ' . $e->getMessage() . '<br>
					<b>File:</b> ' . str_replace(BASEPATH, "", $e->getFile()) . '<br>
					<b>Line:</b> ' . $e->getLine(), $output);
                } else {
                    $output = str_ireplace('{DESCRIPTION}', '<p>This application is currently experiencing some database difficulties. Please check back again later</p>', $output);
                    $output = str_ireplace('{CODE}', 'For more information turn on debug mode in your application', $output);
                }
                echo $output;
                exit(1);
            }
        }
        
        /**
         * Db::Go()
         *
         * @return object|Db|null
         */
        public static function Go()
        {
            if (self::$oPDO == null) {
                try {
                    self::$oPDO = new self();
                } catch (Exception) {
                }
            }
            return self::$oPDO;
        }
        
        /**
         * Db::select()
         *
         * @param $table
         * @param $columns
         * @return $this
         */
        public function select($table = null, $columns = '*')
        {
            $this->reset();
            if (is_array($columns) && count($columns) > 0) {
                $columns = implode(', ', $columns);
            } else {
                $columns = '*';
            }
            $this->table = $table;
            $this->action = 'select';
            $this->query = 'SELECT ' . $columns . ' FROM `' . $table . '`';
            return $this;
        }
        
        /**
         * Db::count()
         *
         * @param null $table
         * @param null $condition
         * @param string $name
         * @return $this
         */
        public function count($table = null, $condition = null, $name = "items")
        {
            $this->reset();
            $this->table = $table;
            $this->action = 'count';
            $this->query = 'SELECT COUNT(*) AS ' . $name . ' FROM `' . $table . '` ' . $condition;
            
            return $this;
        }
        
        /**
         * Db::insert()
         *
         * @param $table
         * @param $data
         * @return $this
         */
        public function insert($table = null, $data = [])
        {
            $this->reset();
            
            if (!is_array($data) || count($data) <= 0) {
                $this->_errorLog('insert [db.class.php, ln.:' . __line__ . ']', 'Insert clause must contain an array data.');
            }
            
            $keys = array();
            $alias = array();
            foreach ($data as $key => $value) {
                $keys[] = '`' . $key . '`';
                $alias[] = '?';
                if ($value == '' && $value <> '0') {
                    $value = null;
                }
                $this->values[] = $value;
            }
            $strKeys = implode(',', $keys);
            $strAlias = implode(',', $alias);
            $this->table = $table;
            $this->action = 'insert';
            
            $this->query = 'INSERT INTO `' . $table . '` (' . $strKeys . ') VALUES (' . $strAlias . ')';
            
            return $this;
        }
        
        /**
         * Db::update()
         *
         * @param $table
         * @param $data
         * @return $this
         */
        public function update($table = null, $data = [])
        {
            $this->reset();
            
            if (!is_array($data) || count($data) <= 0) {
                $this->_errorLog('update [db.class.php, ln.:' . __line__ . ']', 'Update clause must contain an array data.');
            }
            $keys = array();
            foreach ($data as $key => $value) {
                $keys[] = '`' . $key . '`=?';
                if (empty($value)) {
                    $value = '';
                }
                $this->values[] = $value;
            }
            $keys = implode(',', $keys);
            $this->table = $table;
            $this->action = 'update';
            $this->query = 'UPDATE `' . $table . '` set ' . $keys;
            return $this;
        }
        
        /**
         * Db::delete()
         *
         * @param $table
         * @return $this
         */
        public function delete($table = null)
        {
            $this->reset();
            
            $this->table = $table;
            $this->action = 'delete';
            $this->query = 'DELETE FROM `' . $table . '`';
            return $this;
        }
        
        /**
         * Db::batch()
         *
         * @param $table
         * @param $data
         * @return $this
         */
        public function batch($table = null, $data = [])
        {
            $this->reset();
            if (!is_array($data) || count($data) <= 0) {
                $this->_errorLog('batch [db.class.php, ln.:' . __line__ . ']', 'Batch clause must contain an array data.');
            }
            
            foreach ($data[0] as $f => $v) {
                $tmp[] = ":s_$f";
            }
            unset($tmp);
            $sFields = implode(', ', array_keys($data[0]));
            
            $this->query = "INSERT INTO `$table` ($sFields) VALUES ";
            foreach ($data as $value) {
                $this->query .= '(' . "'" . implode("', '", array_values($value)) . "'" . '), ';
            }
            $this->query = rtrim($this->query, ', ');
            
            $this->table = $table;
            $this->action = 'batch';
            return $this;
        }
        
        /**
         * Db::truncate()
         *
         * @param $table
         * @return $this
         */
        public function truncate($table = null)
        {
            $this->reset();
            
            $this->table = $table;
            $this->action = 'truncate';
            $this->query = "TRUNCATE TABLE `$table`";
            return $this;
        }
        
        /**
         * Db::describe()
         *
         * @param $table
         * @return $this
         */
        public function describe($table = null)
        {
            $this->reset();
            
            $this->table = $table;
            $this->action = 'describe';
            $this->query = "DESCRIBE $table";
            return $this;
        }
        
        /**
         * Db::where()
         *
         * @param $column
         * @param $value
         * @param $operator
         * @param $condition
         * @return $this
         */
        public function where($column = null, $value = null, $operator = null, $condition = 'and')
        {
            if (empty($value) && empty($operator)) {
                $this->where[] = $condition . ' (' . $column . ') ';
            } else {
                if (empty($column) || empty($operator)) {
                    $this->_errorLog('where [db.class.php, ln.:' . __line__ . ']', 'Exception Where clause must contain a value and operator => ' . $this->query);
                }
                
                if ($operator == 'BETWEEN' || $operator == 'NOT BETWEEN') {
                    if (!empty($value[0]) && !empty($value[1])) {
                        $this->whereValues[] = $value[0];
                        $this->whereValues[] = $value[1];
                        $this->where[] = $condition . ' (`' . $column . '` ' . $operator . ' ? AND ?)';
                    }
                } else {
                    if ($operator == 'IN' || $operator == 'NOT IN') {
                        $values = array();
                        if (is_array($value) && count($value) > 0) {
                            foreach ($value as $val) {
                                $values[] = '?';
                                $this->whereValues[] = $val;
                            }
                            $this->where[] = $condition . ' (`' . $column . '` ' . $operator . ' (' . implode(',', $values) . '))';
                        }
                    } else {
                        $this->where[] = $condition . ' (`' . $column . '` ' . $operator . ' ?) ';
                        if (empty($value)) {
                            $value = '';
                        }
                        $this->whereValues[] = $value;
                    }
                }
            }
            return $this;
        }
        
        /**
         * Db::orWhere()
         *
         * @param $column
         * @param $value
         * @param $operator
         * @return $this
         */
        public function orWhere($column = null, $value = null, $operator = null)
        {
            return $this->where($column, $value, $operator, 'or');
        }
        
        /**
         * Db::groupBy()
         *
         * @param $column
         * @param $function
         * @return $this
         */
        public function groupBy($column = null, $function = null)
        {
            if (empty($column)) {
                $this->_errorLog('groupBy [db.class.php, ln.:' . __line__ . ']', 'Group By clause must contain a column name.');
            }
            
            if (!empty($function)) {
                $this->groupBy = $function . '(`' . $column . '`)';
            } else {
                $this->groupBy = '`' . $column . '`';
            }
            return $this;
        }
        
        /**
         * Db::having()
         *
         * @param $value
         * @return $this
         */
        public function having($value = null)
        {
            if (empty($value)) {
                $this->_errorLog('having [db.class.php, ln.:' . __line__ . ']', 'Having clause must contain a value.');
            }
            $this->having = $value;
            return $this;
        }
        
        /**
         * Db::orderBy()
         *
         * @param $column
         * @param $order
         * @return $this
         */
        public function orderBy($column = null, $order = null)
        {
            
            if (str_contains(strtoupper($column), 'RAND') && empty($order)) {
                $this->orderBy[] = $column;
            } else {
                if (empty($column) || !in_array(strtoupper($order ?? ""), ['ASC', 'DESC'], true)) {
                    $this->_errorLog('orderBy [db.class.php, ln.:' . __line__ . ']', 'Order By clause must contain a column name and order value.');
                }
                
                $this->orderBy[] = '`' . $column . '` ' . $order;
            }
            return $this;
        }
        
        /**
         * Db::limit()
         *
         * @param $start
         * @param $page
         * @return $this
         */
        public function limit($start = 0, $page = null)
        {
            
            if (!is_int($start)) {
                $this->_errorLog('limit [db.class.php, ln.:' . __line__ . ']', 'Limit clause must be 0 or above');
            }
            if (empty($page) || !is_int($page)) {
                $page = $start;
                $start = 0;
            }
            $this->limit = $start . ',' . $page;
            return $this;
        }
        
        /**
         * Db::offset()
         *
         * @param $offset
         * @return $this
         */
        public function offset($offset)
        {
            $this->offset = $offset;
            
            return $this;
        }
        
        /**
         * Db::offset()
         *
         * @param $perPage
         * @param $page
         * @return $this
         */
        public function pagination($perPage, $page)
        {
            $this->limit = $perPage;
            $this->offset = (($page > 0 ? $page : 1) - 1) * $perPage;
            
            return $this;
        }
        
        /**
         * Db::rawQuery()
         *
         * @param $query
         * @param $values
         * @return $this
         */
        public function rawQuery($query = null, $values = [])
        {
            $this->reset();
            if (is_array($values) && count($values) > 0) {
                foreach ($values as $value) {
                    if (empty($value)) {
                        $value = '';
                    }
                    $this->whereValues[] = $value;
                }
            }
            $this->action = 'query';
            $this->query = $query;
            return $this;
        }
        
        /**
         * Db::first()
         *
         * return first record
         *
         * @return $this
         */
        public function first()
        {
            $this->which = 'first';
            return $this;
        }
        
        /**
         * Db::one()
         *
         * return one record
         *
         * @return $this
         */
        public function one()
        {
            $this->which = 'one';
            return $this;
        }
        
        /**
         * Db::last()
         *
         * return the last record
         *
         * @return $this
         */
        public function last()
        {
            $this->which = 'last';
            return $this;
        }
        
        /**
         * Db::random()
         *
         * return random record
         *
         * @return $this
         */
        public function random()
        {
            $this->which = 'random';
            return $this;
        }
        
        /**
         * Db::run()
         *
         * @param $type
         * @return array|false|mixed
         */
        public function run($type = '')
        {
            $fetch = ($type == "array") ? PDO::FETCH_ASSOC : PDO::FETCH_OBJ;
            
            if (!empty($this->where)) { // add Where condition
                $count = 0;
                $clnWhere = array();
                foreach ($this->where as $value) { // remove first And/OR part
                    $count++;
                    if ($count == 1) {
                        $clnWhere[] = ltrim(ltrim($value, 'or'), 'and');
                    } else {
                        $clnWhere[] = $value;
                    }
                }
                $this->query .= ' WHERE ' . implode('', $clnWhere);
                
            }
            if (!empty($this->groupBy)) { // add Group By condition
                $this->query .= ' GROUP BY ' . $this->groupBy;
            }
            if (!empty($this->groupBy) && !empty($this->having)) { // add Having condition
                $this->query .= ' HAVING ' . $this->having;
            }
            if (!empty($this->orderBy)) { // add Order By condition
                $this->query .= ' ORDER BY ' . implode(',', $this->orderBy);
            }
            if (!empty($this->limit)) { // add Limit condition
                $this->query .= ' LIMIT ' . $this->limit;
            }
            if (!empty($this->offset)) { // add offset condition
                $this->query .= ' OFFSET ' . $this->offset;
            }
            if ($this->query) {
                $this->query = $this->cleanQuery();
            }
            
            $this->bindValues = array_merge($this->values, $this->whereValues);
            $this->raw = str_replace("?", "~|~", $this->query);
            $this->raw = $this->toSql();
            
            switch ($this->action) {
                case 'select': // run Select query and return the result (array|object)
                    if ($_oSTH = $this->prepareQuery()) {
                        $_oSTH->setFetchMode($fetch);
                        $_oSTH->execute($this->whereValues);
                        
                        $this->queryResult = ($this->which == 'first' or $this->which == 'one') ? $_oSTH->fetch() : $_oSTH->fetchAll();
                        $this->rowCount = $_oSTH->rowCount(); // selected row count
                        $_oSTH->closeCursor();
                        unset($_oSTH);
                    }
                    switch ($this->which) {
                        case "one";
                            $data = array_values((array) $this->queryResult)[0]; // return only field value
                            break;
                        
                        case "last";
                            $data = end($this->queryResult); // return only last record
                            break;
                        
                        case "random";
                            $index = rand(0, $this->rowCount - 1);
                            $data = $this->queryResult[$index]; // return a random record
                            break;
                        
                        default;
                            $data = $this->queryResult; // return all records
                            break;
                        
                    }
                    Debug::AddMessage("queries", ++self::$count . '. ' . $this->action . ' | <i>total: ' . ($this->rowCount) . '</i>', $this->toSql(), "session");
                    return ($type == "json") ? json_encode($data) : $data;
                    break;
                
                case 'count':
                    if ($_oSTH = $this->prepareQuery()) {
                        $_oSTH->execute($this->whereValues);
                        $this->queryResult = $_oSTH->fetch(PDO::FETCH_OBJ);
                        $_oSTH->closeCursor();
                        unset($_oSTH);
                        Debug::AddMessage("queries", ++self::$count . '. ' . $this->action . ' | <i>total: ' . ($this->queryResult->items) . '</i>', $this->raw, "session");
                        return $this->queryResult->items;
                    }
                    break;
                
                case 'insert':
                    if ($_oSTH = $this->prepareQuery()) {
                        $_oSTH->execute($this->values);
                        $this->iLastId = $this->lastInsertId();
                        $this->rowCount = $_oSTH->rowCount();
                        $_oSTH->closeCursor();
                        
                        unset($_oSTH);
                        Debug::addMessage('queries', ++self::$count . '. ' . $this->action . ' | <i>total: ' . ($this->rowCount) . '</i>', $this->raw, 'session');
                        return $this->iLastId;
                    }
                    break;
                
                case 'update':
                    if ($_oSTH = $this->prepareQuery()) {
                        $_oSTH->execute(array_merge($this->values, $this->whereValues));
                        $this->rowCount = $_oSTH->rowCount();
                        $_oSTH->closeCursor();
                        
                        unset($_oSTH);
                        Debug::addMessage('queries', ++self::$count . '. ' . $this->action . ' | <i>total: ' . ($this->rowCount) . '</i>', $this->raw, 'session');
                        return $this->rowCount;
                    }
                    break;
                
                case 'delete':
                    if ($_oSTH = $this->prepareQuery()) {
                        $_oSTH->execute($this->whereValues);
                        $this->rowCount = $_oSTH->rowCount();
                        $_oSTH->closeCursor();
                        
                        unset($_oSTH);
                        Debug::addMessage('queries', ++self::$count . '. ' . $this->action . ' | <i>total: ' . ($this->rowCount) . '</i>', $this->raw, 'session');
                        return $this->rowCount;
                    }
                    break;
                
                case 'query':
                    $operation = explode(' ', trim($this->query));
                    $operation[0] = strtoupper($operation[0]);
                    if ($_oSTH = $this->prepareQuery()) {
                        $_oSTH->setFetchMode($fetch);
                        $_oSTH->execute($this->whereValues);
                        switch ($operation[0]):
                            case 'SELECT':
                                $this->rowCount = $_oSTH->rowCount();
                                $this->queryResult = ($this->which == 'first' or $this->which == 'one') ? $_oSTH->fetch() : $_oSTH->fetchAll();
                                Debug::addMessage('queries', ++self::$count . '. ' . $operation[0] . ' | <i>total: ' . ($this->rowCount) . '</i>', $this->toSql(), 'session');
                                return ($type == 'json') ? json_encode($this->queryResult) : $this->queryResult;
                                break;
                            case 'INSERT':
                                Debug::addMessage('queries', ++self::$count . '. ' . $operation[0] . ' | <i>total: ' . ($this->rowCount) . '</i>', $this->toSql(), 'session');
                                return $this->iLastId = $this->lastInsertId();
                                break;
                            case 'DELETE':
                            case 'CREATE':
                            case 'ALTER':
                            case 'DROP':
                            case 'UPDATE':
                            case 'REPAIR':
                            case 'OPTIMIZE':
                                $this->rowCount = $_oSTH->rowCount();
                                $_oSTH->closeCursor();
                                unset($_oSTH);
                                
                                Debug::addMessage('queries', ++self::$count . '. ' . $operation[0] . ' | <i>total: ' . ($this->rowCount) . '</i>', $this->toSql(), 'session');
                                return $this->rowCount;
                                break;
                            default:
                                $this->_errorLog('query [db.class.php, ln.:' . __line__ . ']', 'Invalid Operation "' . $operation[0] . '" detected');
                                break;
                        endswitch;
                    } else {
                        return $_oSTH;
                    }
                    break;
                
                case 'batch':
                    $this->start();
                    $_oSTH = $this->prepare($this->query);
                    try {
                        if ($_oSTH->execute()) {
                            $this->iAllLastId[] = $this->lastInsertId();
                            $this->rowCount = $_oSTH->rowCount();
                            Debug::addMessage('queries', ++self::$count . ' batch | <i>total: ' . ($this->rowCount) . '</i>', $this->toSql(), 'session');
                        } else {
                            $this->_errorLog('insertBatch [db.class.php, ln.:' . __line__ . ']', $_oSTH->errorInfo());
                        }
                    } catch (PDOException $e) {
                        $this->_errorLog('insertBatch [db.class.php, ln.:' . __line__ . ']', $e->getMessage());
                    }
                    $this->end();
                    $_oSTH->closeCursor();
                    unset($_oSTH);
                    return $this->iAllLastId;
                    break;
                
                case 'truncate':
                    $_oSTH = $this->prepare($this->query);
                    try {
                        if ($_oSTH->execute()) {
                            $_oSTH->closeCursor();
                            Debug::addMessage('queries', ++self::$count . '. ' . $this->action . ' | <i>total: ' . ($this->rowCount) . '</i>', $this->toSql(), 'session');
                        } else {
                            $this->_errorLog('truncate [pdo.class.php, ln.:' . __line__ . ']', $_oSTH->errorInfo());
                        }
                    } catch (PDOException $e) {
                        $this->_errorLog('truncate [pdo.class.php, ln.:' . __line__ . ']', $e->getMessage());
                    }
                    return true;
                    break;
                
                case 'exist':
                    $_oSTH = $this->prepare($this->query);
                    try {
                        if ($_oSTH->execute()) {
                            $result = $_oSTH->fetchColumn();
                            $_oSTH->closeCursor();
                            Debug::addMessage('queries', ++self::$count . '. ' . $this->action . ' | <i>total: ' . ($result) . '</i>', $this->toSql(), 'session');
                            return $result;
                        } else {
                            $this->_errorLog('truncate [pdo.class.php, ln.:' . __line__ . ']', $_oSTH->errorInfo());
                        }
                    } catch (PDOException $e) {
                        $this->_errorLog('truncate [pdo.class.php, ln.:' . __line__ . ']', $e->getMessage());
                    }
                    break;
                
                case 'describe':
                    $_oSTH = $this->prepare($this->query);
                    try {
                        if ($_oSTH->execute()) {
                            $aColList = $_oSTH->fetchAll();
                            $aField = array();
                            $aType = array();
                            foreach ($aColList as $key) {
                                $aField[] = $key['Field'];
                                $aType[] = $key['Type'];
                            }
                            $_oSTH->closeCursor();
                            Debug::addMessage('queries', ++self::$count . ' describe | <i>total: ' . ($this->rowCount) . '</i>', $this->toSql(), 'session');
                            return array_combine($aField, $aType);
                        } else {
                            $this->_errorLog('describe [pdo.class.php, ln.:' . __line__ . ']', $_oSTH->errorInfo());
                        }
                    } catch (PDOException $e) {
                        $this->_errorLog('describe [pdo.class.php, ln.:' . __line__ . ']', $e->getMessage());
                    }
                    break;
                
                default:
                    $this->_errorLog('run [db.class.php, ln.:' . __line__ . ']', 'Command "' . $this->action . '" is not allowed.');
                    break;
                
            }
        }
        
        /**
         * Db::affected()
         *
         * @return int
         */
        public function affected()
        {
            return $this->rowCount;
        }
        
        /**
         * Db::getLastInsertId()
         *
         * @return int
         */
        public function getLastInsertId()
        {
            return $this->iLastId;
        }
        
        /**
         * Db::getAllLastInsertId()
         *
         * @return array
         */
        public function getAllLastInsertId()
        {
            return $this->iAllLastId;
        }
        
        /**
         * Db::func()
         *
         * $data = [
         * 'column1' => 'Value 1',
         * 'column2' => true, // or false
         * 'column3' => 'Value 3',
         * 'column4' => $db->func('sha1', 'stringText'),
         * Supported functions date, sha1, md5, base64, ceil, floor, round, etc.
         * 'columnX' => $db->func('date', 'now'),
         * 'columnX' => $db->func('date', 'Y-m-d'),
         * 'columnX' => $db->func('date', 'H:i:s'),
         * supported intervals [s]econd, [m]inute, [h]hour, [d]day, [M]onth, [Y]ear
         * ];
         *
         * @param $func
         * @param $param
         * @return mixed
         */
        public function func($func = null, $param = null)
        {
            if (empty($func) || empty($param)) {
                $this->_errorLog('func [db.class.php, ln.:' . __line__ . ']', 'Missing parameters for "' . $func . '" function.');
            }
            return $func($param);
        }
        
        /**
         * Db::toDate()
         *
         * @param mixed $date
         * @param bool $hastime
         * @return string
         */
        public static function toDate($date = null, $hastime = true)
        {
            
            if (is_int($date)) {
                return date('Y-m-d H:i:s', $date);
            } else {
                if (is_string($date)) {
                    if ($hastime) {
                        return date('Y-m-d H:i:s', strtotime($date));
                    } else {
                        return date('Y-m-d', strtotime($date));
                    }
                } else {
                    if ($hastime) {
                        return date('Y-m-d H:i:s');
                    } else {
                        return date('Y-m-d');
                    }
                }
            }
        }
        
        private function cleanQuery()
        {
            return preg_replace('/\s+/', ' ', $this->query) . ";";
        }
        
        /**
         * Db::toSql()
         *
         * @return array|string|string[]|null
         */
        private function toSql()
        {
            $q = preg_replace('/\s+/', ' ', $this->raw);
            return array_reduce($this->bindValues, function ($sql, $binding) {
                return preg_replace('/~(.*?)~/', is_numeric($binding) ? $binding : '"' . $binding . '"', $sql, 1);
            }, $q);
        }
        
        /**
         * Db::prepareQuery()
         *
         * @return false
         */
        private function prepareQuery()
        {
            try {
                $_oSTH = $this->prepare($this->query);
                if (!empty($this->bindValues)) {
                    foreach ($this->bindValues as $key => $value) {
                        $_oSTH->bindValue(is_int($key) ? $key + 1 : ':' . $key, $value, $this->bindTypes($value));
                    }
                }
                return $_oSTH;
            } catch (PDOException $e) {
                $this->_errorLog('prepareQuery [pdo.class.php, ln.:' . __line__ . ']', $e->getMessage() . ' => ' . $this->raw);
            }
            //return false;
            
        }
        
        /**
         * Db::bindTypes()
         *
         * @param $item
         * @return int
         */
        private function bindTypes($item)
        {
            return match (gettype($item)) {
                'NULL' => PDO::PARAM_NULL,
                'boolean' => PDO::PARAM_BOOL,
                'integer' => PDO::PARAM_INT,
                default => PDO::PARAM_STR,
            };
        }
        
        /**
         * Db::start()
         *
         * @return void
         */
        public function start()
        {
            $this->beginTransaction();
        }
        
        /**
         * Db::end()
         *
         * @return void
         */
        public function end()
        {
            $this->commit();
        }
        
        /**
         * Db::dbServer()
         *
         * @return mixed|string
         */
        public function dbServer()
        {
            
            return $this->server_info;
        }
        
        /**
         * Db::dbName()
         *
         * @return string
         */
        public function dbName()
        {
            
            return $this->_dbName;
        }
        
        /**
         * Db::reset()
         *
         * @return void
         */
        private function reset()
        {
            $this->query = "";
            $this->raw = "";
            $this->action = "";
            $this->which = "";
            $this->table = "";
            $this->values = [];
            $this->where = [];
            $this->whereValues = [];
            $this->bindValues = [];
            $this->groupBy = "";
            $this->having = "";
            $this->orderBy = [];
            $this->limit = "";
            $this->offset = 0;
            $this->iLastId = 0;
            $this->iAllLastId = [];
            $this->rowCount = 0;
        }
        
        /**
         * Db::_fatalErrorPageContent()
         *
         * @return string
         */
        private static function _fatalErrorPageContent()
        {
            return '<!DOCTYPE html>
            <html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en">
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Database Fatal Error</title>
            <style>
                html{background:#f9f9f9}
                body{background:#fff; color:#333; font-family:sans-serif; margin:2em auto; padding:1em 2em 2em; -webkit-border-radius:3px; border-radius:3px; border:1px solid #dfdfdf; max-width:750px; text-align:left;}
                #error-page{margin-top:50px}
                #error-page h2{border-bottom:1px dotted #ccc;}
                #error-page p{font-size:16px; line-height:1.5; margin:2px 0 15px}
                #error-page .code-wrapper{color:#400; background-color:#f1f2f3; padding:5px; border:1px dashed #ddd}
                #error-page code{font-size:15px; font-family:Consolas,Monaco,monospace;}
                a{color:#21759B; text-decoration:none}
                a:hover{color:#D54E21}
                #footer{font-size:14px; margin-top:50px; color:#555;}
            </style>
            </head>
            <body id="error-page">
                <h2>Database connection error!</h2>
                {DESCRIPTION}
                <div class="code-wrapper">
                <code>{CODE}</code>
                </div>
            </body>
            </html>';
        }
        
        /**
         * Db::_errorLog()
         *
         * @param $debugMessage
         * @param $errorMessage
         * @return void
         */
        private function _errorLog($debugMessage, $errorMessage)
        {
            $this->error = $errorMessage;
            Debug::AddMessage('errors', $debugMessage, $errorMessage, "session");
        }
    }