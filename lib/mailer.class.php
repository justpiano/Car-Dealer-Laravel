<?php
    /**
     * Mailer Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: mailer.class.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    class Mailer
    {
        
        private static $instance;
        
        /**
         * Mailer::__construct()
         *
         */
        private function __construct()
        {
        }
        
        /**
         * Mailer::instance()
         *
         * @return Mailer
         */
        public static function instance()
        {
            if (!self::$instance) {
                self::$instance = new Mailer();
            }
            
            return self::$instance;
        }
        
        /**
         * Mailer::sendMail()
         *
         * @return PHPMailer
         */
        public static function sendMail()
        {
            require_once BASEPATH . 'lib/PHPMailer/vendor/autoload.php';
            
            $core = App::Core();
            $mail = new PHPMailer(true);
            
            if ($core->mailer == "SMTP") {
                $mail->isSMTP();
            } else {
                $mail->isSendmail($core->sendmail);
            }
            //$mail->SMTPDebug = 4;//Enable verbose debug output
            $mail->Host = $core->smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $core->smtp_user;
            $mail->Password = $core->smtp_pass;
            $mail->SMTPSecure = $core->is_ssl ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $core->smtp_port;
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            
            return $mail;
        }
    }