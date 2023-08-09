<?php
    
    /**
     * Cron Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: cron.class.php, v1.00 2022-04-20 18:20:24 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    class Cron
    {
        

        /**
         * Cron::__construct()
         *
         */
        public function __construct()
        {
        }
        
        /**
         * Cron::Run
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public static function Run()
        {
            self::expireListings();
        }
        
        /**
         * Cron::expireMemberships()
         *
         * @param integer $days
         * @return array|false|int|string
         */
        public static function expireMemberships($days = 1)
        {
            
            $sql = "
              SELECT
                u.id, CONCAT(u.fname,' ',u.lname) as fullname,
                u.email, u.membership_expire, m.id AS mid, m.title
              FROM
                `" . Users::mTable . "` AS u
                LEFT JOIN `" . Content::msTable . "` AS m
                  ON m.id = u.membership_id
              WHERE u.active = ?
              AND u.membership_id <> 0
              AND u.membership_expire <= DATE_ADD(DATE(NOW()), INTERVAL $days DAY)";
            
            $row = Db::Go()->rawQuery($sql, array("y"))->run();
            
            return ($row) ?: 0;
        }
        
        /**
         * Cron::expireSold()
         *
         * @return array|false|int|string
         */
        public static function expireSold()
        {
            
            $sql = "
              SELECT
                id
              FROM
                `" . Items::lTable . "`
              WHERE sold = ?
              AND soldexpire BETWEEN DATE_SUB(NOW(), INTERVAL " . App::Core()->number_sold . " DAY) AND NOW()
              AND status = ?";
            
            $row = Db::Go()->rawQuery($sql, array(1, 1))->run();
            
            return ($row) ?: 0;
        }
        
        /**
         * Cron::deleteTempImages()
         *
         * @return void
         */
        public static function deleteTempImages()
        {
            
            $prefix = "pics___temp___";
            $dir = UPLOADS . '/listings/';
            $time = 86400; // 24 hours - 24*60*60
            $current = time();
            
            chdir($dir);
            $matches = glob("$prefix*");
            if (is_array($matches) && !empty($matches)) {
                foreach ($matches as $match) {
                    $created = filemtime($match);
                    $diff = $current - $created;
                    if ($diff >= $time) {
                        File::deleteRecursive($match, true);
                    }
                }
            }
        }
        
        /**
         * Cron::expireListings()
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public static function expireListings()
        {
            $solddata = self::expireSold();
            if ($solddata) {
                //Process Sold Items
                $query = "UPDATE `" . Items::lTable . "` SET soldexpire = DEFAULT(soldexpire), status = CASE ";
                $idlist = '';
                foreach ($solddata as $sld) {
                    $query .= " WHEN id = " . $sld->id . " THEN status = 0";
                    $idlist .= $sld->id . ',';
                }
                $idlist = substr($idlist, 0, -1);
                $query .= "
					END
					WHERE id IN (" . $idlist . ") AND status = 1";
                Db::Go()->rawQuery($query)->run();
                unset($query, $idlist, $sld);
            }
            
            $result = self::expireMemberships();
            if ($result) {
                //Process Listings
                $query = "UPDATE `" . Items::lTable . "` SET soldexpire = DEFAULT(soldexpire), expire = DEFAULT(expire), status = CASE ";
                $idlist = '';
                foreach ($result as $usr) {
                    $query .= " WHEN user_id = " . $usr->id . " THEN status = 0";
                    $idlist .= $usr->id . ',';
                }
                $idlist = substr($idlist, 0, -1);
                $query .= "
					END
					WHERE user_id IN (" . $idlist . ") AND status = 1";
                Db::Go()->rawQuery($query)->run();
                
                unset($query, $idlist, $usr);
                
                //Process Users
                $query = "UPDATE `" . Users::mTable . "` SET membership_expire = DEFAULT(membership_expire), membership_id = CASE ";
                $idlist = '';
                foreach ($result as $usr) {
                    $query .= " WHEN id = " . $usr->id . " THEN membership_id = 0";
                    $idlist .= $usr->id . ',';
                }
                $idlist = substr($idlist, 0, -1);
                $query .= "
					END
					WHERE id IN (" . $idlist . ")";
                Db::Go()->rawQuery($query)->run();
                
                unset($query, $idlist, $usr);
                
                //Process Listings Info
                $query = "UPDATE `" . Items::liTable . "` SET lstatus = CASE ";
                $idlist = '';
                foreach ($result as $usr) {
                    $query .= " WHEN user_id = " . $usr->id . " THEN lstatus = 0";
                    $idlist .= $usr->id . ',';
                }
                $idlist = substr($idlist, 0, -1);
                $query .= "
					END
					WHERE user_id IN (" . $idlist . ") AND lstatus = 1";
                Db::Go()->rawQuery($query)->run();
                
                unset($query, $idlist, $usr);
                
            }
            
            //Reset Counters
            if ($result or $solddata) {
                Items::doCalc();
            }
            
            //delete temp images
            self::deleteTempImages();
            
            //Send Emails
            if ($result) {
                $core = App::Core();
                
                $mailer = Mailer::sendMail();
                $mailer->Subject = Lang::$word->EMN_NOTEFROM . ' ' . $core->company;
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->isHTML();
                
                $html_message = Utility::getSnippets(BASEPATH . 'mailer/' . $core->lang . '/Membership_Expire_Message.tpl.php');
                
                foreach ($result as $row) {
                    $html[$row->email] = str_replace(array(
                        '[LOGO]',
                        '[NAME]',
                        '[MNAME]',
                        '[DATE]',
                        '[COMPANY]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[SITEURL]'), array(
                        Utility::getLogo(),
                        $row->fullname,
                        $row->title,
                        date('Y'),
                        $core->company,
                        $core->social->facebook,
                        $core->social->twitter,
                        $core->site_email,
                        SITEURL), $html_message);
                    
                    $mailer->Body = $html;
                    $mailer->addAddress($row->email, $row->fullname);
                    
                    try {
                        $mailer->send();
                    } catch (exception) {
                        //$failedRecipients[] = htmlspecialchars($row->email);
                        $mailer->getSMTPInstance()->reset();
                    }
                    $mailer->clearAddresses();
                    $mailer->clearAttachments();
                }
                unset($row);
            }
        }
    }
