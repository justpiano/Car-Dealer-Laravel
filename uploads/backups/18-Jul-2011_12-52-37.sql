-- --------------------------------------------------------------------------------
-- 
-- @version: dmp.sql Jul 18, 2011 12:52 gewa
-- @package CMS Pro
-- @author wojoscripts.com.
-- @copyright 2010
-- 
-- --------------------------------------------------------------------------------
-- Host: localhost
-- Database: dmp
-- Time: Jul 18, 2011-12:52
-- MySQL version: 5.1.36-community-log
-- PHP version: 5.3.0
-- --------------------------------------------------------------------------------

#
# Database: `dmp`
#


-- --------------------------------------------------
# -- Table structure for table `categories`
-- --------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `parent_id` int(6) NOT NULL DEFAULT '0',
  `slug` varchar(100) NOT NULL,
  `description` text,
  `position` int(6) NOT NULL DEFAULT '0',
  `metakeys` text NOT NULL,
  `metadesc` text NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `categories`
-- --------------------------------------------------

INSERT INTO `categories` (`id`, `name`, `parent_id`, `slug`, `description`, `position`, `metakeys`, `metadesc`, `active`) VALUES ('1', 'ASP NET', '0', 'ASP-NET', 'ASP.NET lets you create dynamic web applications. This popular Microsoft technology creates pages that work in all browsers. Additionally, when programming in ASP.NET, you can build web pages using far less code than with classic ASP. In this section you\'ll find a variety of ASP.NET scripts ready for you to use on your website.', '1', '', '', '1');
INSERT INTO `categories` (`id`, `name`, `parent_id`, `slug`, `description`, `position`, `metakeys`, `metadesc`, `active`) VALUES ('2', 'ASP', '0', 'ASP', 'Microsoft Active Server Pages (ASP) let you combine HTML pages, script commands, and COM components to create interactive web pages or web-based applications that are easy to modify. This Microsoft technology was designed for creating dynamic, interactive web server applications. From ad management programs to web traffic scripts, you\'ll find all sorts of ASP scripts right here.', '2', '', '', '1');
INSERT INTO `categories` (`id`, `name`, `parent_id`, `slug`, `description`, `position`, `metakeys`, `metadesc`, `active`) VALUES ('3', 'C and C++', '0', 'C-and-C', 'C and C++ are powerful programming languages that are usually used for creating large scale applications. C++ is an enhancement of C that provides object-oriented programming features. Put the power of these languages to work for you without having to spend forever mastering them by checking out the scripts in this section!', '3', '', '', '1');
INSERT INTO `categories` (`id`, `name`, `parent_id`, `slug`, `description`, `position`, `metakeys`, `metadesc`, `active`) VALUES ('4', 'Coldfusion', '1', 'Coldfusion', 'Coldfusion is a tag-based programming language that supports dynamic web page creation. It is popular with programmers for writing web-based applications and building e-commerce websites. You could write your own Coldfusion applications, or you could save yourself a lot of time and check out the assortment of scripts available for your use in this section.', '4', '', '', '1');
INSERT INTO `categories` (`id`, `name`, `parent_id`, `slug`, `description`, `position`, `metakeys`, `metadesc`, `active`) VALUES ('5', 'Java', '0', 'Java', 'The ever-versatile Java programming language lets developers write software on one platform and run it on another. If you ever played a game that ran within a web browser, chances are it was running via the JVM (Java Virtual Machine). Other popular uses for Java online include forums, stores, and HTML forms. Check out the great Java scripts in this section for use on your website!', '5', '', '', '1');
INSERT INTO `categories` (`id`, `name`, `parent_id`, `slug`, `description`, `position`, `metakeys`, `metadesc`, `active`) VALUES ('6', 'JavaScript', '0', 'JavaScript', 'The ever-versatile Java programming language lets developers write software on one platform and run it on another. If you ever played a game that ran within a web browser, chances are it was running via the JVM (Java Virtual Machine). Other popular uses for Java online include forums, stores, and HTML forms. Check out the great Java scripts in this section for use on your website!', '6', '', '', '1');
INSERT INTO `categories` (`id`, `name`, `parent_id`, `slug`, `description`, `position`, `metakeys`, `metadesc`, `active`) VALUES ('7', 'Perl', '0', 'Perl', 'The ever-versatile Java programming language lets developers write software on one platform and run it on another. If you ever played a game that ran within a web browser, chances are it was running via the JVM (Java Virtual Machine). Other popular uses for Java online include forums, stores, and HTML forms. Check out the great Java scripts in this section for use on your website!', '7', '', '', '1');
INSERT INTO `categories` (`id`, `name`, `parent_id`, `slug`, `description`, `position`, `metakeys`, `metadesc`, `active`) VALUES ('8', 'PHP', '0', 'PHP', 'Formerly referred to as "Personal Home Page Tools," PHP Hypertext Preprocessor is an open source server side scripting language. It is very popular for developing web based software applications because programmers can use it easily to create web pages with dynamic content that interact with databases. If you\'d like to put this capability to use on your website we have the PHP scripts here to suit your needs.', '8', '', '', '1');


-- --------------------------------------------------
# -- Table structure for table `countries`
-- --------------------------------------------------
DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `flag` varchar(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=261 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `countries`
-- --------------------------------------------------

INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('1', 'Albania', 'al');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('2', 'Algeria', 'dz');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('3', 'American Samoa', 'as');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('4', 'Andorra', 'ad');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('5', 'Angola', 'ao');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('6', 'Anguilla', 'al');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('7', 'Antigua', 'ag');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('8', 'Antilles', 'an');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('9', 'Argentina', 'ar');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('10', 'Armenia', 'am');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('11', 'Aruba', 'aw');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('12', 'Australia', 'au');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('13', 'Austria', 'at');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('14', 'Azerbaijan', 'az');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('15', 'Azores', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('16', 'Bahamas', 'bs');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('17', 'Bahrain', 'bh');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('18', 'Bangladesh', 'bd');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('19', 'Barbados', 'bb');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('20', 'Barbuda', 'ag');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('21', 'Belgium', 'be');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('22', 'Belize', 'bz');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('23', 'Belarus', 'by');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('24', 'Benin', 'bj');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('25', 'Bermuda', 'bm');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('26', 'Bhutan', 'bt');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('27', 'Bolivia', 'bo');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('28', 'Bonaire', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('29', 'Bosnia-Hercegovina', 'ba');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('30', 'Botswana', 'bw');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('31', 'Br. Virgin Islands', 'vg');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('32', 'Brazil', 'br');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('33', 'Brunei', 'bn');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('34', 'Bulgaria', 'bg');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('35', 'Burkina Faso', 'bf');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('36', 'Burundi', 'bi');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('37', 'Cocos Island', 'cc');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('38', 'Cameroon', 'cm');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('39', 'Canada', 'ca');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('40', 'Canary Islands', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('41', 'Cape Verde', 'cv');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('42', 'Cayman Islands', 'ky');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('43', 'Central African Republic', 'cf');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('44', 'Chad', 'td');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('45', 'Channel Islands', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('46', 'Chile', 'cl');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('47', 'China', 'cn');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('48', 'Colombia', 'co');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('50', 'Congo', 'cg');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('51', 'Cook Islands', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('52', 'Cooper Island', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('53', 'Costa Rica', 'cr');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('54', 'Cote D\'Ivoire', 'ci');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('55', 'Croatia', 'hr');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('56', 'Curacao', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('57', 'Cyprus', 'cy');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('58', 'Czech Republic', 'cz');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('59', 'Denmark', 'dk');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('60', 'Djibouti', 'dj');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('61', 'Dominica', 'dm');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('62', 'Dominican Republic', 'do');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('63', 'Ecuador', 'ec');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('64', 'Egypt', 'eg');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('65', 'El Salvador', 'sv');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('66', 'England', 'uk');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('67', 'Equatorial Guinea', 'gq');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('68', 'Estonia', 'ee');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('69', 'Ethiopia', 'et');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('70', 'Fiji', 'fj');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('71', 'Finland', 'fi');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('72', 'France', 'fr');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('73', 'French Guiana', 'gf');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('74', 'French Polynesia', 'pf');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('75', 'Futuna Island', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('76', 'Gabon', 'ga');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('77', 'Gambia', 'gm');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('78', 'Georgia', 'ge');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('79', 'Germany', 'de');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('80', 'Ghana', 'gh');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('81', 'Gibraltar', 'gi');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('82', 'Greece', 'gr');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('83', 'Grenada', 'gd');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('84', 'Grenland', 'gl');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('85', 'Guadeloupe', 'gp');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('86', 'Guam', 'gu');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('87', 'Guatemala', 'gt');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('88', 'Guinea', 'gn');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('89', 'Guinea-Bissau', 'gw');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('90', 'Guyana', 'gy');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('91', 'Haiti', 'ht');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('92', 'Holland', 'nl');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('93', 'Honduras', 'hn');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('94', 'Hong Kong', 'hk');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('95', 'Hungary', 'hu');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('96', 'Iceland', 'is');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('97', 'India', 'in');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('98', 'Indonesia', 'id');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('99', 'Iran', 'ir');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('100', 'Iraq', 'iq');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('101', 'Ireland, Northern', 'ie');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('102', 'Ireland, Republic of', 'ie');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('103', 'Isle of Man', 'im');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('104', 'Israel', 'il');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('105', 'Italy', 'it');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('107', 'Jamaica', 'jm');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('108', 'Japan', 'jp');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('109', 'Jordan', 'jo');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('110', 'Jost Van Dyke Island', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('111', 'Kampuchea', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('112', 'Kazakhstan', 'kz');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('113', 'Kenya', 'ke');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('114', 'Kiribati', 'ki');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('115', 'Korea', 'kp');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('116', 'Korea, South', 'kr');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('117', 'Kosrae', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('118', 'Kuwait', 'kw');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('119', 'Kyrgyzstan', 'kg');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('120', 'Laos', 'la');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('121', 'Latvia', 'lv');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('122', 'Lebanon', 'lb');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('123', 'Lesotho', 'ls');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('124', 'Liberia', 'lr');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('125', 'Liechtenstein', 'li');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('126', 'Lithuania', 'lt');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('127', 'Luxembourg', 'lu');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('128', 'Macau', 'mo');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('129', 'Macedonia', 'fm');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('130', 'Madagascar', 'mg');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('131', 'Madeira Islands', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('132', 'Malagasy', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('133', 'Malawi', 'mw');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('134', 'Malaysia', 'my');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('135', 'Maldives', 'mv');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('136', 'Mali', 'ml');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('137', 'Malta', 'mt');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('138', 'Marshall Islands', 'mh');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('139', 'Martinique', 'mq');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('140', 'Mauritania', 'mr');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('141', 'Mauritius', 'mu');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('142', 'Mexico', 'mx');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('143', 'Micronesia', 'fm');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('144', 'Moldova', 'md');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('145', 'Monaco', 'mc');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('146', 'Mongolia', 'mn');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('147', 'Montenegro', 'me');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('148', 'Montserrat', 'ms');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('149', 'Morocco', 'ma');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('150', 'Mozambique', 'mz');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('151', 'Myanmar', 'mm');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('152', 'Namibia', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('153', 'Nauru', 'nr');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('154', 'Nepal', 'np');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('155', 'Netherlands', 'nl');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('156', 'Nevis', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('157', 'Nevis (St. Kitts)', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('158', 'New Caledonia', 'nc');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('159', 'New Zealand', 'nz');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('160', 'Nicaragua', 'ni');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('161', 'Niger', 'ne');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('162', 'Nigeria', 'ng');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('163', 'Niue', 'nu');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('164', 'Norfolk Island', 'nf');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('165', 'Norman Island', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('166', 'Northern Mariana Island', 'mp');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('167', 'Norway', 'no');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('168', 'Oman', 'om');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('169', 'Pakistan', 'pk');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('170', 'Palau', 'pw');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('171', 'Panama', 'pa');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('172', 'Papua New Guinea', 'pg');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('173', 'Paraguay', 'py');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('174', 'Peru', 'pe');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('175', 'Philippines', 'ph');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('176', 'Poland', 'pl');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('177', 'Ponape', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('178', 'Portugal', 'pt');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('179', 'Qatar', 'qa');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('180', 'Reunion', 're');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('181', 'Romania', 'ro');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('182', 'Rota', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('183', 'Russia', 'ru');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('184', 'Rwanda', 'rw');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('185', 'Saba', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('186', 'Spain', 'es');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('187', 'San Marino', 'sm');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('188', 'Sao Tome', 'st');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('189', 'Saudi Arabia', 'sa');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('190', 'Scotland', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('191', 'Senegal', 'sn');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('192', 'Serbia', 'rs');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('193', 'Seychelles', 'sc');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('194', 'Sierra Leone', 'sl');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('195', 'Singapore', 'sg');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('196', 'Slovakia', 'sk');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('197', 'Slovenia', 'si');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('198', 'Solomon Islands', 'sb');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('199', 'Somalia', 'so');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('200', 'South Africa', 'za');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('201', 'Spain', 'es');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('202', 'Sri Lanka', 'lk');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('203', 'St. Barthelemy', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('204', 'St. Christopher', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('205', 'St. Croix', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('206', 'St. Eustatius', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('207', 'St. John', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('208', 'St. Kitts', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('209', 'St. Lucia', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('210', 'St. Maarten', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('211', 'St. Martin', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('212', 'St. Thomas', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('213', 'St. Vincent', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('214', 'Sudan', 'sd');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('215', 'Suriname', 'sr');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('216', 'Swaziland', 'sz');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('217', 'Sweden', 'se');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('218', 'Switzerland', 'ch');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('219', 'Syria', 'sy');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('220', 'Tahiti', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('221', 'Taiwan', 'tw');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('222', 'Tajikistan', 'tj');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('223', 'Tanzania', 'tz');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('224', 'Thailand', 'th');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('225', 'Tinian', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('226', 'Togo', 'tg');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('227', 'Tonaga', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('228', 'Tonga', 'to');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('229', 'Tortola', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('230', 'Trinidad and Tobago', 'tt');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('232', 'Tunisia', 'tn');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('233', 'Turkey', 'tr');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('234', 'Turkmenistan', 'tm');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('235', 'Turks and Caicos Island', 'tt');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('236', 'Tuvalu', 'tv');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('238', 'Uganda', 'ug');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('239', 'Ukraine', 'ua');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('240', 'Union Island', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('241', 'United Arab Emirates', 'ae');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('242', 'United Kingdom', 'en');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('243', 'Uruguay', 'uy');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('244', 'Usa', 'us');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('245', 'Uzbekistan', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('246', 'Vanuatu', 'vu');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('247', 'Vatican City', 'va');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('248', 'Venezuela', 've');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('249', 'Vietnam', 'vn');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('250', 'Virgin Islands (Brit', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('251', 'Virgin Islands (U.S.', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('252', 'Wake Island', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('253', 'Wales', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('254', 'Wallis Island', '');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('255', 'Western Sahara', 'eh');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('257', 'Yemen', 'ye');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('258', 'Zaire', 'zr');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('259', 'Zambia', 'zm');
INSERT INTO `countries` (`id`, `name`, `flag`) VALUES ('260', 'Zimbabwe', 'zw');


-- --------------------------------------------------
# -- Table structure for table `custom_fields`
-- --------------------------------------------------
DROP TABLE IF EXISTS `custom_fields`;
CREATE TABLE `custom_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `label` varchar(100) DEFAULT NULL,
  `int_value` text,
  `tooltip` text,
  `msg` text,
  `position` int(11) NOT NULL DEFAULT '0',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_name` (`name`),
  KEY `idx_catid` (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `custom_fields`
-- --------------------------------------------------

INSERT INTO `custom_fields` (`id`, `cat_id`, `type`, `name`, `label`, `int_value`, `tooltip`, `msg`, `position`, `required`) VALUES ('1', '8', 'multiple', 'mpm_php', 'PHP Version', 'N/A|PHP4|PHP5|PHP6', 'Please select PHP Version', 'Please Select Multiple Options From Select List', '1', '1');
INSERT INTO `custom_fields` (`id`, `cat_id`, `type`, `name`, `label`, `int_value`, `tooltip`, `msg`, `position`, `required`) VALUES ('2', '8', 'select', 'mpm_mysql', 'MySql Version', 'N/A|MySql 4|MySql 5|MySql 6', '', '', '2', '0');
INSERT INTO `custom_fields` (`id`, `cat_id`, `type`, `name`, `label`, `int_value`, `tooltip`, `msg`, `position`, `required`) VALUES ('3', '8', 'text', 'mpm_text', 'Select Value', 'yes', '', '', '3', '0');
INSERT INTO `custom_fields` (`id`, `cat_id`, `type`, `name`, `label`, `int_value`, `tooltip`, `msg`, `position`, `required`) VALUES ('4', '8', 'radio', 'mpm_url', 'Radio Select', 'Yes|No|Maybe', '', '', '4', '0');
INSERT INTO `custom_fields` (`id`, `cat_id`, `type`, `name`, `label`, `int_value`, `tooltip`, `msg`, `position`, `required`) VALUES ('5', '8', 'checkbox', 'mpm_check', 'Checkbox Select', 'N/A|Yes|No|Maybe|OK', 'Please select one of the checkboxes', 'Field Message:', '5', '0');


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
  `info` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `gateways`
-- --------------------------------------------------

INSERT INTO `gateways` (`id`, `name`, `displayname`, `dir`, `demo`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `info`, `active`) VALUES ('1', 'paypal', 'PayPal', 'paypal', '0', 'Paypal Email Address', 'Currency Code', 'Not in Use', 'paypal@address.com', 'CAD', '', '<p><a href="http://www.paypal.com/" title="PayPal" rel="nofollow" target="_blank">Click here to setup an account with Paypal</a> </p>\r\n\t\t\t<p><strong>Gateway Name</strong> - Enter the name of the payment provider here.</p>\r\n\t\t\t<p><strong>Active</strong> - Select Yes to make this payment provider active. <br />\r\n\t\t\tIf this box is not checked, the payment provider will not show up in the payment provider list during checkout.</p>\r\n\t\t\t<p><strong>Set Live Mode</strong> - If you would like to test the payment provider settings, please select No. </p>\r\n\t\t\t<p><strong>Paypal email address</strong> - Enter your PayPal Business email address here. </p>\r\n\t\t\t<p><strong>Currency Code</strong> - Enter your currency code here. Valid choices are: </p>\r\n\t\t\t\t<ul>\r\n\t\t\t\t\t<li> USD (U.S. Dollar)</li>\r\n\t\t\t\t\t<li> EUR (Euro) </li>\r\n\t\t\t\t\t<li> GBP (Pound Sterling) </li>\r\n\t\t\t\t\t<li> CAD (Canadian Dollar) </li>\r\n\t\t\t\t\t<li> JPY (Yen). </li>\r\n\t\t\t\t\t<li> If omitted, all monetary fields will use default system setting Currency Code. </li>\r\n\t\t\t\t</ul>\r\n\t\t\t<p><strong>Not in Use</strong> - This field it\\\'s not in use. Leave it empty. </p>\r\n\t        <p><strong>IPN Url</strong> - If using recurring payment method, you need to set up and activate the IPN Url in your account: </p>', '1');
INSERT INTO `gateways` (`id`, `name`, `displayname`, `dir`, `demo`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `info`, `active`) VALUES ('2', 'moneybookers', 'MoneyBookers', 'moneybookers', '1', 'MoneyBookers Email Address', 'Currency Code', 'Secret Passphrase', 'moneybookers@address.com', 'EUR', 'mypassphrase', '<p><a href="http://www.moneybookers.com/" title="http://www.moneybookers.net/" rel="nofollow">Click here to setup an account with MoneyBookers</a></p>\r\n\t\t\t<p><strong>Gateway Name</strong> - Enter the name of the payment provider here.</p>\r\n\t\t\t<p><strong>Active</strong> - Select Yes to make this payment provider active. <br />\r\n\t\t\tIf this box is not checked, the payment provider will not show up in the payment provider list during checkout.</p>\r\n\t\t\t<p><strong>Set Live Mode</strong> - If you would like to test the payment provider settings, please select No. </p>\r\n\t\t\t<p><strong>MoneyBookers email address</strong> - Enter your MoneyBookers email address here. </p>\r\n\t\t\t<p><strong>Secret Passphrase</strong> - This field must be set at Moneybookers.com.</p>\r\n\t        <p><strong>IPN Url</strong> - If using recurring payment method, you need to set up and activate the IPN Url in your account: </p>', '1');


-- --------------------------------------------------
# -- Table structure for table `sessions`
-- --------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `ses_id` varchar(32) NOT NULL DEFAULT '',
  `ses_time` int(11) NOT NULL DEFAULT '0',
  `ses_start` int(11) NOT NULL DEFAULT '0',
  `ses_value` text NOT NULL,
  PRIMARY KEY (`ses_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `sessions`
-- --------------------------------------------------



-- --------------------------------------------------
# -- Table structure for table `settings`
-- --------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `site_name` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `site_url` varchar(150) NOT NULL,
  `site_email` varchar(50) NOT NULL,
  `seo` tinyint(1) NOT NULL DEFAULT '0',
  `perpage` tinyint(4) NOT NULL DEFAULT '10',
  `backup` varchar(64) NOT NULL,
  `thumb_w` varchar(5) NOT NULL,
  `thumb_h` varchar(5) NOT NULL,
  `img_w` varchar(5) NOT NULL,
  `img_h` varchar(5) NOT NULL,
  `file_dir` varchar(200) DEFAULT NULL,
  `short_date` varchar(50) NOT NULL,
  `long_date` varchar(50) NOT NULL,
  `dtz` varchar(120) DEFAULT NULL,
  `lang` varchar(2) NOT NULL DEFAULT 'en',
  `show_lang` tinyint(1) NOT NULL DEFAULT '0',
  `offline` tinyint(1) NOT NULL DEFAULT '0',
  `logo` varchar(100) DEFAULT NULL,
  `currency` varchar(4) DEFAULT NULL,
  `cur_symbol` varchar(2) DEFAULT NULL,
  `reg_verify` tinyint(1) NOT NULL DEFAULT '1',
  `auto_verify` tinyint(1) NOT NULL DEFAULT '1',
  `reg_allowed` tinyint(1) NOT NULL DEFAULT '1',
  `user_limit` int(6) NOT NULL DEFAULT '0',
  `notify_admin` tinyint(1) NOT NULL DEFAULT '0',
  `metakeys` text,
  `metadesc` text,
  `analytics` text,
  `mailer` enum('PHP','SMTP') DEFAULT NULL,
  `smtp_host` varchar(150) DEFAULT NULL,
  `smtp_user` varchar(50) DEFAULT NULL,
  `smtp_pass` varchar(50) DEFAULT NULL,
  `smtp_port` tinyint(3) DEFAULT NULL,
  `version` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `settings`
-- --------------------------------------------------

INSERT INTO `settings` (`site_name`, `company`, `site_url`, `site_email`, `seo`, `perpage`, `backup`, `thumb_w`, `thumb_h`, `img_w`, `img_h`, `file_dir`, `short_date`, `long_date`, `dtz`, `lang`, `show_lang`, `offline`, `logo`, `currency`, `cur_symbol`, `reg_verify`, `auto_verify`, `reg_allowed`, `user_limit`, `notify_admin`, `metakeys`, `metadesc`, `analytics`, `mailer`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_port`, `version`) VALUES ('Digital Downloads Pro', 'wojoscripts.com', 'http://agda-graph/dmp', 'gewa@rogers.com', '1', '10', '27-May-2011_21-50-51.sql', '150', '150', '800', '800', 'W:/public_html/dmp/uploads/files/', '%d %b %Y', '%a %d, %M %Y', 'America/Toronto', 'en', '1', '0', 'logo.png', 'CAD', '$', '1', '1', '1', '0', '1', 'metakeys, separated,by coma', 'Your website description goes here', '', 'PHP', 'mail.hostname.com', 'yourusername', 'yourpass', '25', '2.00');


-- --------------------------------------------------
# -- Table structure for table `users`
-- --------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(80) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `newsletter` int(1) NOT NULL DEFAULT '0',
  `cookie_id` varchar(120) NOT NULL DEFAULT '0',
  `token` varchar(100) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `lastlogin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastip` varchar(16) DEFAULT NULL,
  `userlevel` int(1) NOT NULL DEFAULT '1',
  `balance` decimal(10,2) DEFAULT '0.00',
  `avatar` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(150) NOT NULL DEFAULT '',
  `country` varchar(4) NOT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `featured_info` text,
  `active` enum('y','n','t','b') NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `users`
-- --------------------------------------------------

INSERT INTO `users` (`id`, `username`, `password`, `fname`, `lname`, `email`, `newsletter`, `cookie_id`, `token`, `created`, `lastlogin`, `lastip`, `userlevel`, `balance`, `avatar`, `city`, `country`, `featured`, `featured_info`, `active`) VALUES ('1', 'admin', '42b7b504b2753b71f41780d5e86f1139a2ab5647', 'Web', 'Master', 'gewa@rogers.com', '1', '8d599da3016ce8f0cc5fd11f35a759b957929551', '0', '2010-03-27 19:10:44', '2011-07-18 12:15:43', '127.0.0.1', '9', '65.05', '', 'Toronto', '39', '0', '', 'y');
INSERT INTO `users` (`id`, `username`, `password`, `fname`, `lname`, `email`, `newsletter`, `cookie_id`, `token`, `created`, `lastlogin`, `lastip`, `userlevel`, `balance`, `avatar`, `city`, `country`, `featured`, `featured_info`, `active`) VALUES ('2', 'peter', '42b7b504b2753b71f41780d5e86f1139a2ab5647', 'Peter', 'Peterson', 'gewa@rogers.com', '1', '0', '0', '2010-04-28 08:30:05', '2010-06-01 14:11:52', '127.0.0.1', '1', '0.00', '', 'London', '242', '0', '', 'y');
INSERT INTO `users` (`id`, `username`, `password`, `fname`, `lname`, `email`, `newsletter`, `cookie_id`, `token`, `created`, `lastlogin`, `lastip`, `userlevel`, `balance`, `avatar`, `city`, `country`, `featured`, `featured_info`, `active`) VALUES ('3', 'mike', '42b7b504b2753b71f41780d5e86f1139a2ab5647', 'Mike', 'Nelson', 'gewa@rogers.com', '0', '0', '0', '2010-05-30 17:28:17', '2010-08-07 12:35:54', '127.0.0.1', '2', '120.00', '', 'Paris', '72', '1', 'Cras augue augue, convallis vel consectetur sed, scelerisque nec lacus. Etiam vitae nisi diam, et interdum velit. Duis eget dui volutpat mi molestie consectetur vel eget metus. Curabitur nec commodo mi. Nulla facilisi. ', 'y');
INSERT INTO `users` (`id`, `username`, `password`, `fname`, `lname`, `email`, `newsletter`, `cookie_id`, `token`, `created`, `lastlogin`, `lastip`, `userlevel`, `balance`, `avatar`, `city`, `country`, `featured`, `featured_info`, `active`) VALUES ('4', 'john', '42b7b504b2753b71f41780d5e86f1139a2ab5647', 'John', 'Stevens', 'gewa@rogers.com', '0', '0', '0', '2010-06-15 00:58:31', '2010-06-18 13:34:47', '127.0.0.1', '5', '0.00', '', 'Madrid', '186', '0', '', 'y');


