-- --------------------------------------------------------------------------------
-- 
-- @version: msm.sql Jul 16, 2011 22:45 gewa
-- @package CMS Pro
-- @author wojoscripts.com.
-- @copyright 2010
-- 
-- --------------------------------------------------------------------------------
-- Host: localhost
-- Database: msm
-- Time: Jul 16, 2011-22:45
-- MySQL version: 5.1.36-community-log
-- PHP version: 5.3.0
-- --------------------------------------------------------------------------------

#
# Database: `msm`
#


-- --------------------------------------------------
# -- Table structure for table `email_templates`
-- --------------------------------------------------
DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE `email_templates` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `help` text,
  `body` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `email_templates`
-- --------------------------------------------------

INSERT INTO `email_templates` (`id`, `name`, `subject`, `help`, `body`) VALUES ('1', 'Registration Email', 'Please verify your email', 'This template is used to send Registration Verification Email, when Configuration->Registration Verification is set to YES', '&lt;div align=&quot;center&quot;&gt;\n&lt;table cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; width=&quot;600&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Welcome [NAME]! Thanks for registering.&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;Hello,&lt;br /&gt;\n            &lt;br /&gt;\n            You&#039;re now a member of [SITE_NAME].&lt;br /&gt;\n            &lt;br /&gt;\n            Here are your login details. Please keep them in a safe place:&lt;br /&gt;\n            &lt;br /&gt;\n            Username: &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            Password: &lt;strong&gt;[PASSWORD]&lt;/strong&gt;         &lt;hr /&gt;\n            The administrator of this site has requested all new accounts&lt;br /&gt;\n            to be activated by the users who created them thus your account&lt;br /&gt;\n            is currently inactive. To activate your account,&lt;br /&gt;\n            please visit the link below and enter the following:&lt;hr /&gt;\n            Token: &lt;strong&gt;[TOKEN]&lt;/strong&gt;&lt;br /&gt;\n            Email: &lt;strong&gt;[EMAIL]&lt;/strong&gt;         &lt;hr /&gt;\n            &lt;a href=&quot;[LINK]&quot;&gt;Click here to activate tour account&lt;/a&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name`, `subject`, `help`, `body`) VALUES ('2', 'Forgot Password Email', 'Password Reset', 'This template is used for retrieving lost user password', '&lt;div align=&quot;center&quot;&gt;\n&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;New password reset from [SITE_NAME]!&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;Hello, &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            &lt;br /&gt;\n            It seems that you or someone requested a new password for you.&lt;br /&gt;\n            We have generated a new password, as requested:&lt;br /&gt;\n            &lt;br /&gt;\n            Your new password: &lt;strong&gt;[PASSWORD]&lt;/strong&gt;&lt;br /&gt;\n            &lt;br /&gt;\n            To use the new password you need to activate it. To do this click the link provided below and login with your new password.&lt;br /&gt;\n            &lt;a href=&quot;[LINK]&quot;&gt;[LINK]&lt;/a&gt;&lt;br /&gt;\n            &lt;br /&gt;\n            You can change your password after you sign in.&lt;hr /&gt;\n            Password requested from IP: [IP]&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name`, `subject`, `help`, `body`) VALUES ('3', 'Welcome Mail From Admin', 'You have been registered', 'This template is used to send welcome email, when user is added by administrator', '&lt;div align=&quot;center&quot;&gt;\n&lt;table cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; width=&quot;600&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Welcome [NAME]! You have been Registered.&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;Hello,&lt;br /&gt;\n            &lt;br /&gt;\n            You&#039;re now a member of [SITE_NAME].&lt;br /&gt;\n            &lt;br /&gt;\n            Here are your login details. Please keep them in a safe place:&lt;br /&gt;\n            &lt;br /&gt;\n            Username: &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            Password: &lt;strong&gt;[PASSWORD]&lt;/strong&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name`, `subject`, `help`, `body`) VALUES ('4', 'Default Newsletter', 'Newsletter', 'This is a default newsletter template', '&lt;div align=&quot;center&quot;&gt;\n&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Hello [NAME]!&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;You are receiving this email as a part of your newsletter subscription.         &lt;hr /&gt;\n            Here goes your newsletter content         &lt;hr /&gt;\n            &lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;         &lt;hr /&gt;\n            &lt;span style=&quot;font-size: 11px;&quot;&gt;&lt;em&gt;To stop receiving future newsletters please login into your account         and uncheck newsletter subscription box.&lt;/em&gt;&lt;/span&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name`, `subject`, `help`, `body`) VALUES ('5', 'Transaction Completed', 'Payment Completed', 'This template is used to notify administrator on successful payment transaction', '&lt;div align=&quot;center&quot;&gt;\n&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Hello, Admin&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;You have received new payment following:&lt;br /&gt;\n            &lt;br /&gt;\n            Username: &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            Membership: &lt;strong&gt;[ITEMNAME]&lt;/strong&gt;&lt;br /&gt;\n            Price: &lt;strong&gt;[PRICE]&lt;/strong&gt;&lt;br /&gt;\n            Status: &lt;strong&gt;[STATUS] &lt;/strong&gt;&lt;br /&gt;\r\n            Processor: &lt;strong&gt;[PP] &lt;/strong&gt;&lt;br /&gt;\n            IP: &lt;strong&gt;[IP] &lt;/strong&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;&lt;em&gt;You can view this transaction from your admin panel&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name`, `subject`, `help`, `body`) VALUES ('6', 'Transaction Suspicious', 'Suspicious Transaction', 'This template is used to notify administrator on failed/suspicious payment transaction', '&lt;div align=&quot;center&quot;&gt;\n&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color:#ccc&quot;&gt;Hello, Admin&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align:left&quot;&gt;The following transaction has been disabled due to suspicious activity:&lt;br /&gt;\n            &lt;br /&gt;\n            Buyer: &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            Item: &lt;strong&gt;[ITEM]&lt;/strong&gt;&lt;br /&gt;\n            Price: &lt;strong&gt;[PRICE]&lt;/strong&gt;&lt;br /&gt;\n            Status: &lt;strong&gt;[STATUS]&lt;/strong&gt;&lt;/td&gt;\r\n            Processor: &lt;strong&gt;[PP] &lt;/strong&gt;&lt;br /&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align:left&quot;&gt;&lt;em&gt;Please verify this transaction is correct. If it is, please activate it in the transaction section of your site&#039;s &lt;br /&gt;\n            administration control panel. If not, it appears that someone tried to fraudulently obtain products from your site.&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name`, `subject`, `help`, `body`) VALUES ('7', 'Welcome Email', 'Welcome', 'This template is used to welcome newly registered user when Configuration->Registration Verification and Configuration->Auto Registration are both set to YES', '&lt;div align=&quot;center&quot;&gt;\n&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Welcome [NAME]! Thanks for registering.&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;Hello,&lt;br /&gt;\n            &lt;br /&gt;\n            You&#039;re now a member of [SITE_NAME].&lt;br /&gt;\n            &lt;br /&gt;\n            Here are your login details. Please keep them in a safe place:&lt;br /&gt;\n            &lt;br /&gt;\n            Username: &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            Password: &lt;strong&gt;[PASSWORD]&lt;/strong&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name`, `subject`, `help`, `body`) VALUES ('8', 'Membership Expire 7 days', 'Your membership will expire in 7 days', 'This template is used to remind user that membership will expire in 7 days', '&lt;div align=&quot;center&quot;&gt;\n&lt;table cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; width=&quot;600&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Hello, [NAME]&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;\n            &lt;h2 style=&quot;color: rgb(255, 0, 0);&quot;&gt;Your current membership will expire in 7 days&lt;/h2&gt;\n            Please login to your user panel to extend or upgrade your membership.&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name`, `subject`, `help`, `body`) VALUES ('9', 'Membership Expired Today', 'Your membership has expired', 'This template is used to remind user that membership had expired', '&lt;div align=&quot;center&quot;&gt;\n&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Hello, [NAME]&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;\n            &lt;h2 style=&quot;color: rgb(255, 0, 0);&quot;&gt;Your current membership has expired!&lt;/h2&gt;\n            Please login to your user panel to extend or upgrade your membership.&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name`, `subject`, `help`, `body`) VALUES ('10', 'Contact Request', 'Contact Inquiry', 'This template is used to send default Contact Request Form', '&lt;div align=&quot;center&quot;&gt;\n&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Hello Admin&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;You have a new contact request:         &lt;hr /&gt;\n            [MESSAGE]         &lt;hr /&gt;\n            From: &lt;strong&gt;[SENDER] - [NAME]&lt;/strong&gt;&lt;br /&gt;\n            Subject: &lt;strong&gt;[MAILSUBJECT]&lt;/strong&gt;&lt;br /&gt;\n            Senders IP: &lt;strong&gt;[IP]&lt;/strong&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name`, `subject`, `help`, `body`) VALUES ('12', 'Single Email', 'Single User Email', 'This template is used to email single user', '&lt;div align=&quot;center&quot;&gt;\n  &lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n      &lt;tr&gt;\n        &lt;th style=&quot;background-color:#ccc&quot;&gt;Hello [NAME]&lt;/th&gt;\n      &lt;/tr&gt;\n      &lt;tr&gt;\n        &lt;td valign=&quot;top&quot; style=&quot;text-align:left&quot;&gt;Your message goes here...&lt;/td&gt;\n      &lt;/tr&gt;\n      &lt;tr&gt;\n        &lt;td style=&quot;text-align:left&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n          [SITE_NAME] Team&lt;br /&gt;\n          &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n      &lt;/tr&gt;\n    &lt;/tbody&gt;\n  &lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name`, `subject`, `help`, `body`) VALUES ('13', 'Notify Admin', 'New User Registration', 'This template is used to notify admin of new registration when Configuration->Registration Notification is set to YES', '&lt;div align=&quot;center&quot;&gt;\n&lt;table cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; width=&quot;600&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Hello Admin&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;You have a new user registration. You can login into your admin panel to view details:&lt;hr /&gt;\n            Username: &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            Name: &lt;strong&gt;[NAME]&lt;/strong&gt;&lt;br /&gt;\n            IP: &lt;strong&gt;[IP]&lt;/strong&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name`, `subject`, `help`, `body`) VALUES ('14', 'Registration Pending', 'Registration Verification Pending', 'This template is used to send Registration Verification Email, when Configuration->Auto Registration is set to NO', '&lt;div align=&quot;center&quot;&gt;\n&lt;table cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; width=&quot;600&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Welcome [NAME]! Thanks for registering.&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;Hello,&lt;br /&gt;\n            &lt;br /&gt;\n            You&#039;re now a member of [SITE_NAME].&lt;br /&gt;\n            &lt;br /&gt;\n            Here are your login details. Please keep them in a safe place:&lt;br /&gt;\n            &lt;br /&gt;\n            Username: &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            Password: &lt;strong&gt;[PASSWORD]&lt;/strong&gt;         &lt;hr /&gt;\n            The administrator of this site has requested all new accounts&lt;br /&gt;\n            to be activated by the users who created them thus your account&lt;br /&gt;\n            is currently pending verification process.&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');


-- --------------------------------------------------
# -- Table structure for table `gateways`
-- --------------------------------------------------
DROP TABLE IF EXISTS `gateways`;
CREATE TABLE `gateways` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `displayname` varchar(255) NOT NULL,
  `dir` varchar(255) NOT NULL,
  `demo` tinyint(1) NOT NULL DEFAULT '1',
  `extra_txt` varchar(255) NOT NULL,
  `extra_txt2` varchar(255) NOT NULL,
  `extra_txt3` varchar(255) DEFAULT NULL,
  `extra` varchar(255) NOT NULL,
  `extra2` varchar(255) NOT NULL,
  `extra3` varchar(255) DEFAULT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `gateways`
-- --------------------------------------------------

INSERT INTO `gateways` (`id`, `name`, `displayname`, `dir`, `demo`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `is_recurring`, `active`) VALUES ('1', 'paypal', 'PayPal', 'paypal', '0', 'Paypal Email Address', 'Currency Code', 'Not in Use', 'paypal@address.com', 'CAD', '', '1', '1');
INSERT INTO `gateways` (`id`, `name`, `displayname`, `dir`, `demo`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `is_recurring`, `active`) VALUES ('2', 'moneybookers', 'MoneyBookers', 'moneybookers', '1', 'MoneyBookers Email Address', 'Currency Code', 'Secret Passphrase', 'moneybookers@address.com', 'EUR', 'mypassphrase', '1', '1');


-- --------------------------------------------------
# -- Table structure for table `memberships`
-- --------------------------------------------------
DROP TABLE IF EXISTS `memberships`;
CREATE TABLE `memberships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` float(10,2) NOT NULL DEFAULT '0.00',
  `days` int(5) NOT NULL DEFAULT '0',
  `period` varchar(1) NOT NULL DEFAULT 'D',
  `trial` tinyint(1) NOT NULL DEFAULT '0',
  `recurring` tinyint(1) NOT NULL DEFAULT '0',
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `memberships`
-- --------------------------------------------------

INSERT INTO `memberships` (`id`, `title`, `description`, `price`, `days`, `period`, `trial`, `recurring`, `private`, `active`) VALUES ('1', 'Trial 7', 'This is 7 days trial membership', '0.00', '7', 'D', '1', '0', '0', '1');
INSERT INTO `memberships` (`id`, `title`, `description`, `price`, `days`, `period`, `trial`, `recurring`, `private`, `active`) VALUES ('2', 'Basic 30', 'This is 30 days basic membership', '2.99', '1', 'M', '0', '0', '0', '1');
INSERT INTO `memberships` (`id`, `title`, `description`, `price`, `days`, `period`, `trial`, `recurring`, `private`, `active`) VALUES ('3', 'Basic 90', 'This is 90 days basic membership', '6.99', '90', 'D', '0', '0', '0', '1');
INSERT INTO `memberships` (`id`, `title`, `description`, `price`, `days`, `period`, `trial`, `recurring`, `private`, `active`) VALUES ('4', 'Platinum', 'Platinum Yearly Subscription.', '49.99', '1', 'Y', '0', '1', '0', '1');
INSERT INTO `memberships` (`id`, `title`, `description`, `price`, `days`, `period`, `trial`, `recurring`, `private`, `active`) VALUES ('5', 'Weekly Access', 'This is 7 days basic membership', '1.99', '1', 'W', '0', '0', '0', '1');


-- --------------------------------------------------
# -- Table structure for table `news`
-- --------------------------------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(55) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `body` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `author` varchar(55) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `created` date NOT NULL DEFAULT '0000-00-00',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `news`
-- --------------------------------------------------

INSERT INTO `news` (`id`, `title`, `body`, `author`, `created`, `active`) VALUES ('1', 'Welcome to our Client Area!', '&lt;p&gt;We are pleased to announce the new release of Membership Subscription Manager v 2.0&lt;br/&gt;&lt;/p&gt;', 'Administrator', '2011-07-10', '1');


-- --------------------------------------------------
# -- Table structure for table `payments`
-- --------------------------------------------------
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(100) DEFAULT NULL,
  `membership_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rate_amount` varchar(255) NOT NULL,
  `currency` varchar(4) DEFAULT NULL,
  `date` datetime NOT NULL,
  `pp` enum('PayPal','MoneyBookers') DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `payments`
-- --------------------------------------------------

INSERT INTO `payments` (`id`, `txn_id`, `membership_id`, `user_id`, `rate_amount`, `currency`, `date`, `pp`, `ip`, `status`) VALUES ('1', '', '2', '1', '5.00', 'CAD', '2011-04-05 14:12:32', 'PayPal', '', '1');
INSERT INTO `payments` (`id`, `txn_id`, `membership_id`, `user_id`, `rate_amount`, `currency`, `date`, `pp`, `ip`, `status`) VALUES ('2', '', '2', '2', '5.00', 'CAD', '2011-03-12 14:12:32', 'PayPal', '', '0');
INSERT INTO `payments` (`id`, `txn_id`, `membership_id`, `user_id`, `rate_amount`, `currency`, `date`, `pp`, `ip`, `status`) VALUES ('3', '', '3', '3', '10.00', 'CAD', '2011-03-05 16:47:36', 'MoneyBookers', '', '1');


-- --------------------------------------------------
# -- Table structure for table `settings`
-- --------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `site_name` varchar(50) DEFAULT NULL,
  `site_email` varchar(40) DEFAULT NULL,
  `site_url` varchar(200) DEFAULT NULL,
  `reg_allowed` tinyint(1) NOT NULL DEFAULT '1',
  `user_limit` tinyint(1) NOT NULL DEFAULT '0',
  `reg_verify` tinyint(1) NOT NULL DEFAULT '0',
  `notify_admin` tinyint(1) NOT NULL DEFAULT '0',
  `auto_verify` tinyint(1) NOT NULL DEFAULT '0',
  `user_perpage` varchar(4) NOT NULL DEFAULT '10',
  `thumb_w` varchar(4) NOT NULL,
  `thumb_h` varchar(4) NOT NULL,
  `backup` varchar(60) DEFAULT NULL,
  `currency` varchar(4) DEFAULT NULL,
  `cur_symbol` varchar(3) DEFAULT NULL,
  `mailer` enum('PHP','SMTP') NOT NULL DEFAULT 'PHP',
  `smtp_host` varchar(100) DEFAULT NULL,
  `smtp_user` varchar(50) DEFAULT NULL,
  `smtp_pass` varchar(50) DEFAULT NULL,
  `smtp_port` varchar(6) DEFAULT NULL,
  `version` varchar(5) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `settings`
-- --------------------------------------------------

INSERT INTO `settings` (`site_name`, `site_email`, `site_url`, `reg_allowed`, `user_limit`, `reg_verify`, `notify_admin`, `auto_verify`, `user_perpage`, `thumb_w`, `thumb_h`, `backup`, `currency`, `cur_symbol`, `mailer`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_port`, `version`) VALUES ('Your Site Name', 'gewa@rogers.com', 'http://agda-graph/msm', '1', '0', '1', '0', '1', '10', '80', '100', '14-Jul-2011_23-37-21.sql', 'CAD', '$', 'PHP', '', '', '', '0', '2.0');


-- --------------------------------------------------
# -- Table structure for table `users`
-- --------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `membership_id` tinyint(3) NOT NULL DEFAULT '0',
  `mem_expire` datetime DEFAULT '0000-00-00 00:00:00',
  `trial_used` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(32) NOT NULL,
  `fname` varchar(32) NOT NULL,
  `lname` varchar(32) NOT NULL,
  `token` varchar(40) NOT NULL DEFAULT '0',
  `newsletter` tinyint(1) NOT NULL DEFAULT '0',
  `userlevel` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `lastlogin` datetime DEFAULT '0000-00-00 00:00:00',
  `lastip` varchar(16) DEFAULT '0',
  `avatar` varchar(150) DEFAULT NULL,
  `active` enum('y','n','t','b') NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `users`
-- --------------------------------------------------

INSERT INTO `users` (`id`, `username`, `password`, `membership_id`, `mem_expire`, `trial_used`, `email`, `fname`, `lname`, `token`, `newsletter`, `userlevel`, `created`, `lastlogin`, `lastip`, `avatar`, `active`) VALUES ('1', 'admin', '42b7b504b2753b71f41780d5e86f1139a2ab5647', '4', '2012-07-15 22:45:16', '0', 'alex.kuzmanovic@gmail.com', 'Web', 'Master', '0', '0', '9', '2011-04-10 14:16:22', '2011-07-16 22:01:26', '127.0.0.1', '', 'y');
INSERT INTO `users` (`id`, `username`, `password`, `membership_id`, `mem_expire`, `trial_used`, `email`, `fname`, `lname`, `token`, `newsletter`, `userlevel`, `created`, `lastlogin`, `lastip`, `avatar`, `active`) VALUES ('2', 'john', '42b7b504b2753b71f41780d5e86f1139a2ab5647', '3', '0000-00-00 00:00:00', '0', 'john@mail.com', 'John', 'Johnson', '0', '0', '1', '2011-05-01 18:10:14', '0000-00-00 00:00:00', '127.0.0.1', '', 'n');
INSERT INTO `users` (`id`, `username`, `password`, `membership_id`, `mem_expire`, `trial_used`, `email`, `fname`, `lname`, `token`, `newsletter`, `userlevel`, `created`, `lastlogin`, `lastip`, `avatar`, `active`) VALUES ('3', 'mike', '42b7b504b2753b71f41780d5e86f1139a2ab5647', '2', '0000-00-00 00:00:00', '0', 'mike@mail.com', 'Mike', 'Manson', '0', '0', '1', '2011-05-02 18:10:14', '0000-00-00 00:00:00', '127.0.0.1', '', 't');
INSERT INTO `users` (`id`, `username`, `password`, `membership_id`, `mem_expire`, `trial_used`, `email`, `fname`, `lname`, `token`, `newsletter`, `userlevel`, `created`, `lastlogin`, `lastip`, `avatar`, `active`) VALUES ('4', 'steve', '42b7b504b2753b71f41780d5e86f1139a2ab5647', '0', '0000-00-00 00:00:00', '0', 'steve@mail.com', 'Steven', 'Swanson', '0', '0', '1', '2011-05-03 18:10:14', '0000-00-00 00:00:00', '127.0.0.1', '', 'b');
INSERT INTO `users` (`id`, `username`, `password`, `membership_id`, `mem_expire`, `trial_used`, `email`, `fname`, `lname`, `token`, `newsletter`, `userlevel`, `created`, `lastlogin`, `lastip`, `avatar`, `active`) VALUES ('5', 'peter', '42b7b504b2753b71f41780d5e86f1139a2ab5647', '4', '2012-07-14 15:12:59', '0', 'peter@mail.com', 'Peter', 'Peterson', '0', '0', '1', '2011-06-28 22:11:04', '2011-06-27 11:43:36', '127.0.0.1', '', 'y');


