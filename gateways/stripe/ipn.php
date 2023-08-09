<?php
    /**
     * Stripe IPN
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: ipn.php, v1.00 2022-08-08 10:12:05 gewa Exp $
     */
    
    use Stripe\Customer;
    use Stripe\Exception\CardException;
    use Stripe\Stripe;
    
    const _WOJO = true;
    require_once("../../init.php");
    
    if (!App::Auth()->is_User())
        exit;
    
    ini_set('log_errors', true);
    ini_set('error_log', dirname(__file__) . '/ipn_errors.log');
    
    if (isset($_POST['processStripePayment'])) {
        $rules = array('payment_method' => array('required|string', "Invalid Payment Method"));
        
        $validate = Validator::instance();
        $safe = $validate->doValidate($_POST, $rules);
        
        if (!$cart = Content::getCart()) {
            Message::$msgs['cart'] = Lang::$word->FRONT_ERROR02;
        }
        
        if (empty(Message::$msgs)) {
            require_once BASEPATH . "/gateways/stripe/vendor/autoload.php";
            
            $key = Db::Go()->select(Admin::gTable, array("extra", "extra2"))->where("name", "stripe", "=")->first()->run();
            
            Stripe::setApiKey($key->extra);
            try {
                //Create a client
                $client = Customer::create(array(
                    "name" => App::Auth()->name,
                    "payment_method" => $safe->payment_method,
                    'address' => [
                        'line1' => Auth::$userdata->address,
                        'postal_code' => Auth::$userdata->zip,
                        'city' => Auth::$userdata->city,
                        'state' => Auth::$userdata->state,
                        'country' => Auth::$userdata->country
                    ]
                ));
                
                // insert payment record
                $row = Db::Go()->select(Content::msTable)->where("id", $cart->membership_id, "=")->first()->run();
                $data = array(
                    'txn_id' => time(),
                    'membership_id' => $row->id,
                    'user_id' => App::Auth()->uid,
                    'amount' => $cart->total,
                    'coupon' => $cart->coupon,
                    'total' => $cart->totalprice,
                    'tax' => $cart->totaltax,
                    'currency' => $key->extra2,
                    'ip' => Url::getIP(),
                    'pp' => "Stripe",
                    'status' => 1,
                );
                
                $last_id = Db::Go()->insert(Content::txTable, $data)->run();
                
                //insert user membership
                $udata = array(
                    'transaction_id' => $last_id,
                    'user_id' => App::Auth()->uid,
                    'membership_id' => $row->id,
                    'expire' => Date::calculateDays($row->id),
                    'recurring' => 0,
                    'active' => 1,
                );
                
                //update user record
                $xdata = array(
                    //'stripe_pm' => $safe->payment_method,
                    //'stripe_cus' => $client['id'],
                    'membership_id' => $row->id,
                    'membership_expire' => $udata['expire'],
                );
                
                Db::Go()->insert(Content::mhTable, $udata)->run();
                Db::Go()->update(Users::mTable, $xdata)->where("id", App::Auth()->uid, "=")->run();
                
                Db::Go()->delete(Content::xTable)->where("user_id", App::Auth()->uid, "=")->run();
                
                //update membership status
                App::Auth()->membership_id = App::Session()->set('membership_id', $row->id);
                App::Auth()->mem_expire = App::Session()->set('mem_expire', $xdata['membership_expire']);
                
                $jn['type'] = 'success';
                $jn['title'] = Lang::$word->SUCCESS;
                $jn['message'] = Lang::$word->HOME_POK1;
                print json_encode($jn);
                
                /* == Notify Administrator == */
                $mailer = Mailer::sendMail();
                $core = App::Core();
                $subject = Lang::$word->HOME_POK1;
                $html_message = Utility::getSnippets(BASEPATH . 'mailer/' . $core->lang . '/Payment_Completed_Admin.tpl.php');
                
                $body = str_replace(array(
                    '[LOGO]',
                    '[CEMAIL]',
                    '[COMPANY]',
                    '[DATE]',
                    '[SITEURL]',
                    '[NAME]',
                    '[PACKAGE]',
                    '[PRICE]',
                    '[PP]',
                    '[IP]',
                    '[FB]',
                    '[TW]'), array(
                    Utility::getLogo(),
                    $core->site_email,
                    $core->company,
                    date('Y'),
                    SITEURL,
                    App::Auth()->name,
                    $row->title,
                    $data['total'],
                    "Stripe",
                    Url::getIP(),
                    $core->social->facebook,
                    $core->social->twitter), $html_message);
                
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->addAddress($core->site_email, $core->company);
                
                $mailer->isHTML();
                $mailer->Subject = $subject;
                $mailer->Body = $body;
                
                $mailer->send();
                
                /* == Notify User == */
                $umailer = Mailer::sendMail();
                $uhtml_message = Utility::getSnippets(BASEPATH . 'mailer/' . $core->lang . '/Payment_Completed_User.tpl.php');
                
                $ubody = str_replace(array(
                    '[LOGO]',
                    '[CEMAIL]',
                    '[COMPANY]',
                    '[DATE]',
                    '[SITEURL]',
                    '[NAME]',
                    '[PACKAGE]',
                    '[PRICE]',
                    '[PP]',
                    '[FB]',
                    '[TW]'), array(
                    Utility::getLogo(),
                    $core->site_email,
                    $core->company,
                    date('Y'),
                    SITEURL,
                    App::Auth()->name,
                    $row->title,
                    $data['total'],
                    "Stripe",
                    Url::getIP(),
                    $core->social->facebook,
                    $core->social->twitter), $uhtml_message);
                
                $umailer->setFrom($core->site_email, $core->company);
                $umailer->addAddress(App::Auth()->email, App::Auth()->name);
                
                $umailer->isHTML();
                $umailer->Subject = $subject;
                $umailer->Body = $ubody;
                
                $umailer->send();
            } catch (CardException $e) {
                $body = $e->getJsonBody();
                $err = $body['error'];
                $json['type'] = 'error';
                Message::$msgs['msg'] = 'Message is: ' . $err['message'] . "\n";
                Message::msgSingleStatus();
            } catch (\PHPMailer\PHPMailer\Exception $e) {
            }
        } else {
            Message::msgSingleStatus();
        }
    }