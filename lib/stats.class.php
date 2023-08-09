<?php
    /**
     * Stats Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: stats.class.php, v1.00 2022-04-20 18:20:24 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    class Stats
    {
        
        const sTable = "stats";
        
        
        /**
         * Stats::__construct()
         *
         */
        public function __construct()
        {
        
        }
        
        /**
         * Stats::indexStats()
         *
         * @return array
         */
        public static function indexStats()
        {
            
            $users = Db::Go()->count(Users::mTable)->run();
            $active = Db::Go()->count(Items::lTable)->run();
            $expire = Db::Go()->count(Items::lTable, "WHERE DATE(expire) < DATE(NOW())")->run();
            $pending = Db::Go()->count(Items::lTable)->where("status", 1, "<")->run();
            $mems = Db::Go()->count(Users::mTable)->where("membership_id", 1, ">=")->where("type", "member", "=")->run();
            
            return [$users, $active, $expire, $pending, $mems];
            
        }
        
        /**
         * Stats::indexSalesStats()
         *
         * @return array|int
         */
        public static function indexSalesStats()
        {
            
            $data = array();
            $data['label'] = array();
            $data['color'] = array();
            $data['legend'] = array();
            $data['preUnits'] = Utility::currencySymbol();
            
            $color = array(
                "#03a9f4",
                "#33BFC1"
            );
            
            $labels = array(
                Lang::$word->TRX_SALES,
                Lang::$word->TRX_AMOUNT
            );
            
            for ($i = 1; $i <= 12; $i++) {
                $data['data'][$i]['m'] = Date::doDate("MMM", date("F", mktime(0, 0, 0, $i, 10)));
                $reg_data[$i] = array(
                    'month' => date('M', mktime(0, 0, 0, $i)),
                    'sales' => 0,
                    'amount' => 0,
                );
            }
            
            $sql = "
              SELECT
                COUNT(id) AS sales,
                SUM(total) AS amount,
                MONTH(created) as created
              FROM
                `" . Content::txTable . "`
              WHERE status = ?
              GROUP BY MONTH(created)";
            
            $query = Db::Go()->rawQuery($sql, array(1));
            foreach ($query->run() as $result) {
                $reg_data[$result->created] = array(
                    'month' => Date::doDate("MMM", date("F", mktime(0, 0, 0, $result->created, 10))),
                    'sales' => $result->sales,
                    'amount' => $result->amount);
            }
            
            $totalsum = 0;
            $totalsales = 0;
            
            
            foreach ($reg_data as $key => $value) {
                $data['sales'][] = array($key, $value['sales']);
                $data['amount'][] = array($key, $value['amount']);
                $data['data'][$key][Lang::$word->TRX_SALES] = $value['sales'];
                $data['data'][$key][Lang::$word->TRX_AMOUNT] = $value['amount'];
                $totalsum += $value['amount'];
                $totalsales += $value['sales'];
            }
            
            $data['totalsum'] = $totalsum;
            $data['totalsales'] = $totalsales;
            $data['sales_str'] = implode(",", array_column($data["sales"], 1));
            $data['amount_str'] = implode(",", array_column($data["amount"], 1));
            
            foreach ($labels as $k => $label) {
                $data['label'][] = $label;
                $data['color'][] = $color[$k];
                $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
            }
            $data['data'] = array_values($data['data']);
            return ($data) ?: 0;
            
        }
        
        /**
         * Stats::getMainStats()
         *
         * @return array
         * @throws Exception
         */
        public static function getMainStats()
        {
            $data = array();
            $data['label'] = array();
            $data['color'] = array();
            $data['legend'] = array();
            $data['preUnits'] = Utility::currencySymbol();
            
            $color = array(
                "#f44336",
                "#2196f3",
                "#e91e63",
                "#4caf50",
                "#ff9800",
                "#ff5722",
                "#795548",
                "#607d8b",
                "#00bcd4",
                "#9c27b0");
            
            $begin = new DateTime(date('Y') . '-01');
            $ends = new DateTime(date('Y') . '-12');
            $end = $ends->modify('+1 month');
            
            $interval = new DateInterval('P1M');
            $daterange = new DatePeriod($begin, $interval, $end);
            
            $sql = "
              SELECT
                DATE_FORMAT(p.created, '%Y-%m') as cdate,
                m.title,
                p.membership_id,
                p.total
              FROM
                `" . Content::txTable . "` AS p
                LEFT JOIN `" . Content::msTable . "` AS m
                  ON m.id = p.membership_id
              WHERE status = ?;";
            $query = Db::Go()->rawQuery($sql, array(1))->run();
            $memberships = Utility::groupToLoop($query, "title");
            
            foreach ($daterange as $k => $date) {
                $data['data'][$k]['m'] = Date::dodate("MMM", $date->format("Y-m"));
                if ($memberships) {
                    foreach ($memberships as $title => $rows) {
                        $sum = 0;
                        foreach ($rows as $row) {
                            $data['data'][$k][$row->title] = $sum;
                            if ($row->cdate == $date->format("Y-m")) {
                                $sum += $row->total;
                                $data['data'][$k][$title] = $sum;
                            }
                        }
                        
                    }
                    
                } else {
                    $data['data'][$k]['-/-'] = 0;
                }
            }
            
            if ($memberships) {
                $k = 0;
                foreach ($memberships as $label => $vals) {
                    $k++;
                    $data['label'][] = $label;
                    $data['color'][] = $color[$k];
                    $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
                }
            } else {
                $data['label'][] = '-/-';
                $data['color'][] = $color[0];
                $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[0] . '"> </span> -/-</div>';
            }
            
            return $data;
            
        }
        
        /**
         * Stats::getAllStats()
         *
         * @return array|int
         */
        public static function getAllStats()
        {
            
            $enddate = (Validator::post('enddate') && $_POST['enddate'] <> "") ? Validator::sanitize(Db::toDate($_POST['enddate'], false)) : date("Y-m-d");
            $fromdate = Validator::post('fromdate') ? Validator::sanitize(Db::toDate($_POST['fromdate'], false)) : null;
            
            if (Validator::post('fromdate') && $_POST['fromdate'] <> "") {
                $counter = Db::Go()->count(Content::txTable, "WHERE `created` BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND status = 1")->run();
                $where = "WHERE p.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND p.status = 1";
                
            } else {
                $counter = Db::Go()->count(Content::txTable)->run();
                $where = null;
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $counter;
            $pager->default_ipp = App::Core()->perpage;
            $pager->path = Url::url(Router::$path, "?");
            $pager->paginate();
            
            $sql = "
              SELECT
                p.*,
                m.title,
                CONCAT(u.fname,' ',u.lname) as name
              FROM `" . Content::txTable . "` as p
                LEFT JOIN " . Users::mTable . " AS u
                  ON p.user_id = u.id
                LEFT JOIN " . Content::msTable . " AS m
                  ON p.membership_id = m.id
              $where
              ORDER BY p.created DESC " . $pager->limit;
            
            $row = Db::Go()->rawQuery($sql)->run();
            
            return ($row) ? [$row, $pager] : 0;
            
        }
        
        /**
         * Stats::exportAllTransactions()
         *
         * @return int|mixed
         */
        public static function exportAllTransactions()
        {
            $sql = "
              SELECT
                p.txn_id,
                m.title,
                CONCAT(u.fname,' ',u.lname) as name,
                p.amount,
                p.tax,
                p.coupon,
                p.total,
                p.currency,
                p.pp,
                p.created
              FROM
                `" . Content::txTable . "` AS p
                LEFT JOIN `" . Users::mTable . "` AS u
                  ON u.id = p.user_id
                LEFT JOIN `" . Content::msTable . "` AS m
                  ON m.id = p.membership_id
              ORDER BY p.created DESC";
            
            $rows = Db::Go()->rawQuery($sql)->run();
            $array = json_decode(json_encode($rows), true);
            
            return $array ?: 0;
            
        }
        
        /**
         * Stats::getAllSalesStats()
         *
         * @return array
         */
        public static function getAllSalesStats()
        {
            $range = (isset($_GET['timerange'])) ? Validator::sanitize($_GET['timerange'], "string", 6) : 'all';
            
            $data = array();
            $data['label'] = array();
            $data['color'] = array();
            $data['legend'] = array();
            $data['preUnits'] = Utility::currencySymbol();
            
            $color = array(
                "#03a9f4",
                "#33BFC1",
                "#ff9800",
                "#e91e63",
            );
            
            $labels = array(
                Lang::$word->TRX_SALES,
                Lang::$word->TRX_AMOUNT,
                Lang::$word->TRX_TAX,
                Lang::$word->TRX_COUPON,
            );
            
            $reg_data = array();
            
            switch ($range) {
                case 'day':
                    for ($i = 0; $i < 24; $i++) {
                        $data['data'][$i]['m'] = $i;
                        $reg_data[$i] = array(
                            'hour' => $i,
                            'sales' => 0,
                            'amount' => 0,
                            'tax' => 0,
                            'coupon' => 0,
                        );
                    }
                    
                    $sql = ("
                      SELECT
                        COUNT(id) AS sales,
                        SUM(amount) AS amount,
                        SUM(tax) AS tax,
                        SUM(coupon) AS coupon,
                        HOUR(created) as hour
                      FROM
                        `" . Content::txTable . "`
                        WHERE DATE(created) = DATE(NOW())
                        AND status = ?
                      GROUP BY HOUR(created)
                      ORDER BY hour
				    ");
                    $query = Db::Go()->rawQuery($sql, array(1));
                    
                    foreach ($query->run() as $result) {
                        $reg_data[$result->hour] = array(
                            'hour' => $result->hour,
                            'sales' => $result->sales,
                            'amount' => $result->amount,
                            'tax' => $result->tax,
                            'coupon' => $result->coupon
                        );
                    }
                    break;
                
                case 'week':
                    $date_start = strtotime('-' . date('w') . ' days');
                    for ($i = 0; $i < 7; $i++) {
                        $date = date('Y-m-d', $date_start + ($i * 86400));
                        $data['data'][$i]['m'] = Date::doDate("EE", date('D', strtotime($date)));
                        $reg_data[date('w', strtotime($date))] = array(
                            'day' => date('D', strtotime($date)),
                            'sales' => 0,
                            'amount' => 0,
                            'tax' => 0,
                            'coupon' => 0,
                        );
                    }
                    
                    $sql = ("
                      SELECT
                        COUNT(id) AS sales,
                        SUM(amount) AS amount,
                        SUM(tax) AS tax,
                        SUM(coupon) AS coupon,
                        DAYNAME(created) as created
                      FROM
                        `" . Content::txTable . "`
                        WHERE DATE(created) >= DATE('" . Validator::sanitize(date('Y-m-d', $date_start), "string", 10) . "')
                        AND status = ?
                      GROUP BY DAYNAME(created)
				    ");
                    $query = Db::Go()->rawQuery($sql, array(1));
                    
                    foreach ($query->run() as $result) {
                        $reg_data[$result->created] = array(
                            'day' => $result->created,
                            'sales' => $result->sales,
                            'amount' => $result->amount,
                            'tax' => $result->tax,
                            'coupon' => $result->coupon
                        );
                    }
                    break;
                
                case 'month':
                    for ($i = 1; $i <= date('t'); $i++) {
                        $date = date('Y') . '-' . date('m') . '-' . $i;
                        $data['data'][$i]['m'] = date('d', strtotime($date));
                        $reg_data[date('j', strtotime($date))] = array(
                            'day' => date('d', strtotime($date)),
                            'sales' => 0,
                            'amount' => 0,
                            'tax' => 0,
                            'coupon' => 0,
                        );
                    }
                    
                    $sql = ("
                      SELECT
                        COUNT(id) AS sales,
                        SUM(amount) AS amount,
                        SUM(tax) AS tax,
                        SUM(coupon) AS coupon,
                        DAY(created) as created
                      FROM
                        `" . Content::txTable . "`
                        WHERE MONTH(created) = MONTH(CURDATE())
                        AND YEAR(created) = YEAR(CURDATE())
                        AND status = ?
                      GROUP BY DAY(created)
				    ");
                    $query = Db::Go()->rawQuery($sql, array(1));
                    
                    foreach ($query->run() as $result) {
                        $reg_data[$result->created] = array(
                            'month' => $result->created,
                            'sales' => $result->sales,
                            'amount' => $result->amount,
                            'tax' => $result->tax,
                            'coupon' => $result->coupon
                        );
                    }
                    break;
                
                case 'year':
                    for ($i = 1; $i <= 12; $i++) {
                        $data['data'][$i]['m'] = Date::doDate("MMM", date("F", mktime(0, 0, 0, $i, 10)));
                        $reg_data[$i] = array(
                            'month' => date('M', mktime(0, 0, 0, $i)),
                            'sales' => 0,
                            'amount' => 0,
                            'tax' => 0,
                            'coupon' => 0,
                        );
                    }
                    
                    $sql = ("
                      SELECT
                        COUNT(id) AS sales,
                        SUM(amount) AS amount,
                        SUM(tax) AS tax,
                        SUM(coupon) AS coupon,
                        MONTH(created) as created
                      FROM
                        `" . Content::txTable . "`
                        WHERE YEAR(created) = YEAR(NOW())
                        AND status = ?
                      GROUP BY MONTH(created)
				    ");
                    $query = Db::Go()->rawQuery($sql, array(1));
                    
                    foreach ($query->run() as $result) {
                        $reg_data[$result->created] = array(
                            'month' => Date::doDate("MMM", date("F", mktime(0, 0, 0, $result->created, 10))),
                            'sales' => $result->sales,
                            'amount' => $result->amount,
                            'tax' => $result->tax,
                            'coupon' => $result->coupon
                        );
                    }
                    break;
                
                case 'all':
                    for ($i = 1; $i <= 12; $i++) {
                        $data['data'][$i]['m'] = Date::doDate("MMM", date("F", mktime(0, 0, 0, $i, 10)));
                        $reg_data[$i] = array(
                            'month' => date('M', mktime(0, 0, 0, $i)),
                            'sales' => 0,
                            'amount' => 0,
                            'tax' => 0,
                            'coupon' => 0,
                        );
                    }
                    
                    $sql = ("
                      SELECT
                        COUNT(id) AS sales,
                        SUM(amount) AS amount,
                        SUM(tax) AS tax,
                        SUM(coupon) AS coupon,
                        MONTH(created) as created
                      FROM
                        `" . Content::txTable . "`
                        WHERE status = ?
                      GROUP BY MONTH(created)
				    ");
                    $query = Db::Go()->rawQuery($sql, array(1));
                    
                    foreach ($query->run() as $result) {
                        $reg_data[$result->created] = array(
                            'month' => Date::doDate("MMM", date("F", mktime(0, 0, 0, $result->created, 10))),
                            'sales' => $result->sales,
                            'amount' => $result->amount,
                            'tax' => $result->tax,
                            'coupon' => $result->coupon
                        );
                    }
                    break;
                
            }
            
            foreach ($reg_data as $key => $value) {
                $data['data'][$key][Lang::$word->TRX_SALES] = $value['sales'];
                $data['data'][$key][Lang::$word->TRX_AMOUNT] = $value['amount'];
                $data['data'][$key][Lang::$word->TRX_TAX] = $value['tax'];
                $data['data'][$key][Lang::$word->TRX_COUPON] = $value['coupon'];
            }
            
            foreach ($labels as $k => $label) {
                $data['label'][] = $label;
                $data['color'][] = $color[$k];
                $data['legend'][] = '<div class="item"><span class="wojo ring label right spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
            }
            $data['data'] = array_values($data['data']);
            return $data;
        }
        
        /**
         * Stats::listingStats()
         *
         * @param $id
         * @return array
         */
        public static function listingStats($id)
        {
            
            $data = array();
            $data['label'] = array();
            $data['color'] = array();
            $data['legend'] = array();
            $data['preUnits'] = "";
            
            $color = array("#478ba2");
            $labels = array(Lang::$word->VISITS);
            
            for ($i = 1; $i <= 12; $i++) {
                $data['data'][$i]['m'] = Date::dodate("MMM", date("F", mktime(0, 0, 0, $i, 10)));
                $reg_data[$i] = array(
                    'month' => date('M', mktime(0, 0, 0, $i)),
                    'visits' => 0
                );
            }
            
            $sql = ("
                SELECT
                  SUM(visits) AS visits,
                  MONTH(created) as created
                FROM
                  `" . self::sTable . "`
                WHERE  listing_id = ?
                AND YEAR(created) = YEAR(NOW())
                GROUP BY MONTH(created)
            ");
            
            $query = Db::Go()->rawQuery($sql, array($id));
            
            foreach ($query->run() as $result) {
                $reg_data[$result->created] = array(
                    'month' => Date::dodate("MMM", date("F", mktime(0, 0, 0, $result->created, 10))),
                    'visits' => $result->visits
                );
            }
            
            foreach ($reg_data as $key => $value) {
                $data['data'][$key][Lang::$word->VISITS] = $value['visits'];
            }
            
            foreach ($labels as $k => $label) {
                $data['label'][] = $label;
                $data['color'][] = $color[$k];
                $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
            }
            
            $data['data'] = array_values($data['data']);
            
            return $data;
        }
        
        /**
         * Stats::getPackagePaymentsChart()
         *
         * @param $id
         * @return array
         */
        public static function getPackagePaymentsChart($id)
        {
            
            $data = array();
            $data['label'] = array();
            $data['color'] = array();
            $data['legend'] = array();
            $data['preUnits'] = Utility::currencySymbol();
            
            $color = array(
                "#03a9f4",
                "#33BFC1",
                "#ff9800",
                "#e91e63",
            );
            
            $labels = array(
                Lang::$word->TRX_SALES,
                Lang::$word->TRX_AMOUNT,
                Lang::$word->TRX_TAX,
                Lang::$word->TRX_COUPON,
            );
            
            for ($i = 1; $i <= 12; $i++) {
                $data['data'][$i]['m'] = Date::doDate("MMM", date("F", mktime(0, 0, 0, $i, 10)));
                $reg_data[$i] = array(
                    'month' => date('M', mktime(0, 0, 0, $i)),
                    'sales' => 0,
                    'amount' => 0,
                    'tax' => 0,
                    'coupon' => 0);
            }
            
            $sql = ("
                SELECT
                  COUNT(id) AS sales,
                  SUM(amount) AS amount,
                  SUM(tax) AS tax,
                  SUM(coupon) AS coupon,
                  MONTH(created) as created
                FROM
                  `" . Content::txTable . "`
                  WHERE membership_id = ?
                  AND status = ?
                GROUP BY MONTH(created);
		    ");
            $query = Db::Go()->rawQuery($sql, array($id, 1));
            
            foreach ($query->run() as $result) {
                $reg_data[$result->created] = array(
                    'month' => Date::doDate("MMM", date("F", mktime(0, 0, 0, $result->created, 10))),
                    'sales' => $result->sales,
                    'amount' => $result->amount,
                    'tax' => $result->tax,
                    'coupon' => $result->coupon
                );
            }
            
            
            foreach ($reg_data as $key => $value) {
                $data['data'][$key][Lang::$word->TRX_SALES] = $value['sales'];
                $data['data'][$key][Lang::$word->TRX_AMOUNT] = $value['amount'];
                $data['data'][$key][Lang::$word->TRX_TAX] = $value['tax'];
                $data['data'][$key][Lang::$word->TRX_COUPON] = $value['coupon'];
            }
            
            foreach ($labels as $k => $label) {
                $data['label'][] = $label;
                $data['color'][] = $color[$k];
                $data['legend'][] = '<div class="item"><span class="wojo ring label right spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
            }
            $data['data'] = array_values($data['data']);
            return $data;
        }
        
        /**
         * Stats::exportPackagePayments()
         *
         * @param $id
         * @return int|mixed
         */
        public static function exportPackagePayments($id)
        {
            $sql = "
              SELECT
                p.txn_id,
                CONCAT(u.fname,' ',u.lname) as name,
                p.amount,
                p.tax,
                p.coupon,
                p.total,
                p.currency,
                p.pp,
                p.created
              FROM
                `" . Content::txTable . "` AS p
                LEFT JOIN `" . Users::mTable . "` AS u
                  ON u.id = p.user_id
              WHERE p.membership_id = ?
              AND p.status = ?
              ORDER BY p.created DESC";
            
            $rows = Db::GO()->rawQuery($sql, array($id, 1))->run();
            $array = json_decode(json_encode($rows), true);
            
            return $array ?: 0;
            
        }
        
        /**
         * Stats::userPaymentsChart()
         *
         * @param $id
         * @return array
         */
        public static function userPaymentsChart($id)
        {
            
            $data = array();
            $data['label'] = array();
            $data['color'] = array();
            $data['legend'] = array();
            $data['preUnits'] = Utility::currencySymbol();
            
            $color = array(
                "#03a9f4",
                "#33BFC1",
                "#ff9800",
                "#e91e63",
            );
            
            $labels = array(
                Lang::$word->TRX_SALES,
                Lang::$word->TRX_AMOUNT,
                Lang::$word->TRX_TAX,
                Lang::$word->TRX_COUPON,
            );
            
            for ($i = 1; $i <= 12; $i++) {
                $data['data'][$i]['m'] = Date::dodate("MMM", date("F", mktime(0, 0, 0, $i, 10)));
                $reg_data[$i] = array(
                    'month' => date('M', mktime(0, 0, 0, $i)),
                    'sales' => 0,
                    'amount' => 0,
                    'tax' => 0,
                    'coupon' => 0);
            }
            
            $sql = ("
                SELECT
                  COUNT(id) AS sales,
                  SUM(amount) AS amount,
                  SUM(tax) AS tax,
                  SUM(coupon) AS coupon,
                  MONTH(created) as created
                FROM
                  `" . Content::txTable . "`
                  WHERE user_id = ?
                  AND status = ?
                GROUP BY MONTH(created)
		    ");
            $query = Db::Go()->rawQuery($sql, array($id, 1));
            
            foreach ($query->run() as $result) {
                $reg_data[$result->created] = array(
                    'month' => Date::dodate("MMM", date("F", mktime(0, 0, 0, $result->created, 10))),
                    'sales' => $result->sales,
                    'amount' => $result->amount,
                    'tax' => $result->tax,
                    'coupon' => $result->coupon
                );
            }
            
            
            foreach ($reg_data as $key => $value) {
                $data['data'][$key][Lang::$word->TRX_SALES] = $value['sales'];
                $data['data'][$key][Lang::$word->TRX_AMOUNT] = $value['amount'];
                $data['data'][$key][Lang::$word->TRX_TAX] = $value['tax'];
                $data['data'][$key][Lang::$word->TRX_COUPON] = $value['coupon'];
            }
            
            foreach ($labels as $k => $label) {
                $data['label'][] = $label;
                $data['color'][] = $color[$k];
                $data['legend'][] = '<div class="item align middle"><span class="wojo ring right label spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
            }
            $data['data'] = array_values($data['data']);
            return $data;
        }
        
        /**
         * Stats::listingsExpireMonth()
         *
         * @return array|int|string
         */
        public static function listingsExpireMonth()
        {
            $sql = ("
            SELECT COUNT(id) AS total, DATE_FORMAT(expire,'%Y-%m-%d') as expires
              FROM `" . Items::lTable . "`
              WHERE MONTH(expire) = MONTH(NOW())
              AND YEAR(expire) = YEAR(NOW())
            GROUP BY expires
            ");
            
            $row = Db::Go()->rawQuery($sql)->run();
            
            return $row ?: 0;
        }
        
        /**
         * Stats::membershipsExpireMonth()
         *
         * @return array|int|string
         */
        public static function membershipsExpireMonth()
        {
            $sql = ("
            SELECT COUNT(id) AS total, DATE_FORMAT(membership_expire,'%Y-%m-%d') as expires
              FROM `" . Users::mTable . "`
              WHERE MONTH(membership_expire) = MONTH(NOW())
              AND YEAR(membership_expire) = YEAR(NOW())
              AND membership_id > 0
            GROUP BY expires
		    ");
            
            $row = Db::Go()->rawQuery($sql)->run();
            
            return $row ?: 0;
        }
        
        /**
         * Stats::userPayments()
         *
         * @param int $id
         * @return array|int|string
         */
        public static function userPayments($id)
        {
            $sql = "
              SELECT
                p.txn_id,
                m.title,
                p.amount,
                p.tax,
                p.coupon,
                p.total,
                p.pp,
                p.currency,
                p.created,
                p.ip
              FROM
                `" . Content::txTable . "` AS p
                LEFT JOIN " . Users::msTable . " AS m
                  ON m.id = p.membership_id
              WHERE p.user_id = ?
              ORDER BY p.created DESC";
            
            $row = Db::GO()->rawQuery($sql, array($id))->run();
            
            return $row ?: 0;
        }
        
        /**
         * Stats::userHistory()
         *
         * @param $id
         * @param string $order
         * @return array|int|string
         */
        public static function userHistory($id, $order = 'activated')
        {
            $sql = "
              SELECT 
                um.activated,
                um.membership_id,
                um.transaction_id,
                um.expire,
                m.title,
                m.price
              FROM
                `" . Content::mhTable . "` AS um 
                LEFT JOIN " . Content::msTable . " AS m 
                  ON m.id = um.membership_id 
              WHERE um.user_id = ?
              ORDER BY um.$order DESC";
            
            $row = Db::Go()->rawQuery($sql, array($id))->run();
            
            return $row ?: 0;
            
        }
        
        /**
         * Stats::updateHits($id)
         *
         * @param bool $id
         * @return void
         */
        public static function updateHits($id)
        {
            $date = date('Y-m-d');
            if ($row = Db::Go()->rawQuery("SELECT * FROM `" . self::sTable . "` WHERE DATE(created) = ? AND listing_id = ? LIMIT 1", array($date, $id))->first()->run()) {
                Db::Go()->rawQuery("UPDATE `" . self::sTable . "` SET visits = visits+1 WHERE listing_id = ? AND DATE(created) = ?", array($row->listing_id, $date))->run();
            } else {
                $data = array(
                    'created' => Db::toDate(),
                    'visits' => 1,
                    'listing_id' => $id,
                );
                Db::Go()->insert(self::sTable, $data)->run();
            }
            Db::GO()->rawQuery("UPDATE " . Items::lTable . " SET hits = hits+1 WHERE id = ?", array($id))->run();
            
        }
        
        /**
         * Stats::userTotals()
         *
         * @return int
         */
        public static function userTotals()
        {
            $sql = "
              SELECT
                SUM(total) as total
              FROM
                `" . Content::txTable . "`
              WHERE user_id = ?
              GROUP BY user_id";
            
            $row = Db::Go()->rawQuery($sql, array(App::Auth()->uid))->first()->run();
            
            return $row ? $row->total : 0;
            
        }
        
        /**
         * Stats::emptyCart()
         *
         * @return void
         */
        public static function emptyCart()
        {
            
            Db::GO()->rawQuery("DELETE FROM `" . Items::xTable . "` WHERE DATE(created) < DATE_SUB(CURDATE(), INTERVAL 1 DAY);")->run();
            
            $total = Db::Go()->affected();
            
            Message::msgReply($total, 'success', Message::formatSuccessMessage($total, Lang::$word->UTL_DELCRT_OK));
        }
        
        /**
         * Stats::doArraySum($array, $key)
         *
         * @param $array
         * @param $key
         * @return int|string
         */
        public static function doArraySum($array, $key)
        {
            if (is_array($array)) {
                return (number_format(array_sum(array_map(function ($item) use ($key) {
                    return $item->$key;
                }
                    , $array)), 2));
            }
            
            return 0;
            
        }
    }