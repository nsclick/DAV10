<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @encoding This file must be saved as UTF-8 - No BOM
 */



if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}



/* ######################### fill system tables ##################### */


// bots
// mic: more under: http://www.useragentstring.com/pages/useragentstring.php
// last update: 2008.10.15 mic
//in version 2.2.3 (versions 2.3.0.0 to 2.3.0.84 contain only some of them (not all))
//  - added positions 421-430
$quer[] = "TRUNCATE TABLE #__jstats_bots";

$quer[] = "INSERT IGNORE INTO #__jstats_bots (bot_id, bot_string, bot_fullname) VALUES
(1, 'acme.spider', 'Acme Spider'),
(2, 'ahoythehomepagefinder', 'Ahoy! The Homepage Finder'),
(3, 'alkaline', 'Alkaline'),
(4, 'appie', 'Walhello appie'),
(5, 'arachnophilia', 'Arachnophilia'),
(6, 'architext', 'ArchitextSpider'),
(7, 'aretha', 'Aretha'),
(8, 'ariadne', 'ARIADNE'),
(9, 'arks', 'arks'),
(10, 'aspider', 'ASpider (Associative Spider)'),
(11, 'atn.txt', 'ATN Worldwide'),
(12, 'atomz', 'Atomz.com Search Robot'),
(13, 'auresys', 'AURESYS'),
(14, 'backrub', 'BackRub'),
(15, 'biUKrother', 'Big Brother'),
(16, 'bjaaland', 'Bjaaland'),
(17, 'blackwidow', 'BlackWidow'),
(18, 'blindekuh', 'Die Blinde Kuh'),
(19, 'bloodhound', 'Bloodhound'),
(20, 'brightnet', 'bright.net caching robot'),
(21, 'bspider', 'BSpider'),
(22, 'cactvschemistryspider', 'CACTVS Chemistry Spider'),
(23, 'calif[^r]', 'Calif'),
(24, 'cassandra', 'Cassandra'),
(25, 'cgireader', 'Digimarc Marcspider/CGI'),
(26, 'checkbot', 'Checkbot'),
(27, 'churl', 'churl'),
(28, 'cmc', 'CMC/0.01'),
(29, 'collective', 'Collective'),
(30, 'combine', 'Combine System'),
(31, 'conceptbot', 'Conceptbot'),
(32, 'coolbot', 'CoolBot'),
(33, 'core', 'Web Core / Roots'),
(34, 'cosmos', 'XYLEME Robot'),
(35, 'cruiser', 'Internet Cruiser Robot'),
(36, 'cusco', 'Cusco'),
(37, 'cyberspyder', 'CyberSpyder Link Test'),
(38, 'deweb', 'DeWeb(c) Katalog/Index'),
(39, 'dienstspider', 'DienstSpider'),
(40, 'digger', 'Digger'),
(41, 'diibot', 'Digital Integrity Robot'),
(42, 'directhit', 'Direct Hit Grabber'),
(43, 'dnabot', 'DNAbot'),
(44, 'download_express', 'DownLoad Express'),
(45, 'dragonbot', 'DragonBot'),
(46, 'dwcp', 'DWCP (Dridus Web Cataloging Project)'),
(47, 'e-collector', 'e-collector'),
(48, 'ebiness', 'EbiNess'),
(49, 'eit', 'EIT Link Verifier Robot'),
(50, 'elfinbot', 'ELFINBOT'),
(51, 'emacs', 'Emacs-w3 Search Engine'),
(52, 'emcspider', 'ananzi'),
(53, 'esther', 'Esther'),
(54, 'evliyacelebi', 'Evliya Celebi'),
(55, 'nzexplorer', 'nzexplorer'),
(56, 'fdse', 'Fluid Dynamics Search Engine robot'),
(57, 'felix', 'Felix IDE'),
(58, 'ferret', 'Wild Ferret Web Hopper #1, #2, #3'),
(59, 'fetchrover', 'FetchRover'),
(60, 'fido', 'fido'),
(61, 'finnish', 'Hmhkki'),
(62, 'fireball', 'KIT-Fireball'),
(63, '[^a]fish', 'Fish search'),
(64, 'fouineur', 'Fouineur'),
(65, 'francoroute', 'Robot Francoroute'),
(66, 'freecrawl', 'Freecrawl'),
(67, 'funnelweb', 'FunnelWeb'),
(68, 'gama', 'gammaSpider, FocusedCrawler'),
(69, 'gazz', 'gazz'),
(70, 'gcreep', 'GCreep'),
(71, 'getbot', 'GetBot'),
(72, 'geturl', 'GetURL'),
(73, 'golem', 'Golem'),
(74, 'googlebot', 'Googlebot (Google)'),
(75, 'grapnel', 'Grapnel/0.01 Experiment'),
(76, 'griffon', 'Griffon'),
(77, 'gromit', 'Gromit'),
(78, 'gulliver', 'Northern Light Gulliver'),
(79, 'hambot', 'HamBot'),
(80, 'harvest', 'Harvest'),
(81, 'havindex', 'havIndex'),
(82, 'hometown', 'Hometown Spider Pro'),
(83, 'htdig', 'ht://Dig'),
(84, 'htmlgobble', 'HTMLgobble'),
(85, 'hyperdecontextualizer', 'Hyper-Decontextualizer'),
(86, 'iajabot', 'iajaBot'),
(87, 'ibm', 'IBM_Planetwide'),
(88, 'iconoclast', 'Popular Iconoclast'),
(89, 'ilse', 'Ingrid'),
(90, 'imagelock', 'Imagelock'),
(91, 'incywincy', 'IncyWincy'),
(92, 'informant', 'Informant'),
(93, 'infoseek', 'InfoSeek Robot 1.0'),
(94, 'infoseeksidewinder', 'Infoseek Sidewinder'),
(95, 'infospider', 'InfoSpiders'),
(96, 'inspectorwww', 'Inspector Web'),
(97, 'intelliagent', 'IntelliAgent'),
(98, 'irobot', 'I, Robot'),
(99, 'iron33', 'Iron33'),
(100, 'israelisearch', 'Israeli-search'),
(101, 'javabee', 'JavaBee'),
(102, 'jbot', 'JBot Java Web Robot'),
(103, 'jcrawler', 'JCrawler'),
(104, 'jeeves', 'Jeeves'),
(105, 'jobo', 'JoBo Java Web Robot'),
(106, 'jobot', 'Jobot'),
(107, 'joebot', 'JoeBot'),
(108, 'jubii', 'The Jubii Indexing Robot'),
(109, 'jumpstation', 'JumpStation'),
(110, 'katipo', 'Katipo'),
(111, 'kdd', 'KDD-Explorer'),
(112, 'kilroy', 'Kilroy'),
(113, 'ko_yappo_robot', 'KO_Yappo_Robot'),
(114, 'labelgrabber.txt', 'LabelGrabber'),
(115, 'larbin', 'larbin'),
(116, 'legs', 'legs'),
(117, 'linkidator', 'Link Validator'),
(118, 'linkscan', 'LinkScan'),
(119, 'linkwalker', 'LinkWalker'),
(120, 'lockon', 'Lockon'),
(121, 'logo_gif', 'logo.gif Crawler'),
(122, 'lycos', 'Lycos'),
(123, 'macworm', 'Mac WWWWorm'),
(124, 'magpie', 'Magpie'),
(125, 'marvin', 'marvin/infoseek'),
(126, 'mattie', 'Mattie'),
(127, 'mediafox', 'MediaFox'),
(128, 'merzscope', 'MerzScope'),
(129, 'meshexplorer', 'NEC-MeshExplorer'),
(130, 'mindcrawler', 'MindCrawler'),
(131, 'moget', 'moget'),
(132, 'momspider', 'MOMspider'),
(133, 'monster', 'Monster'),
(134, 'motor', 'Motor'),
(135, 'muscatferret', 'Muscat Ferret'),
(136, 'mwdsearch', 'Mwd.Search'),
(137, 'myweb', 'Internet Shinchakubin'),
(138, 'netcarta', 'NetCarta WebMap Engine'),
(139, 'netcraft', 'Netcraft Web Server Survey'),
(140, 'netmechanic', 'NetMechanic'),
(141, 'netscoop', 'NetScoop'),
(142, 'newscan-online', 'newscan-online'),
(143, 'nhse', 'NHSE Web Forager'),
(144, 'nomad', 'Nomad'),
(145, 'northstar', 'The NorthStar Robot'),
(146, 'occam', 'Occam'),
(147, 'octopus', 'HKU WWW Octopus'),
(148, 'openfind', 'Openfind data gatherer'),
(149, 'orb_search', 'Orb Search'),
(150, 'packrat', 'Pack Rat'),
(151, 'pageboy', 'PageBoy'),
(152, 'parasite', 'ParaSite'),
(153, 'patric', 'Patric'),
(154, 'pegasus', 'pegasus'),
(155, 'perignator', 'The Peregrinator'),
(156, 'perlcrawler', 'PerlCrawler 1.0'),
(157, 'phantom', 'Phantom'),
(158, 'piltdownman', 'PiltdownMan'),
(159, 'pimptrain', 'Pimptrain.com\'s robot'),
(160, 'pioneer', 'Pioneer'),
(161, 'pitkow', 'html_analyzer'),
(162, 'pjspider', 'Portal Juice Spider'),
(163, 'pka', 'PGP Key Agent'),
(164, 'plumtreewebaccessor', 'PlumtreeWebAccessor'),
(165, 'poppi', 'Poppi'),
(166, 'portalb', 'PortalB Spider'),
(167, 'puu', 'GetterroboPlus Puu'),
(168, 'python', 'The Python Robot'),
(169, 'raven', 'Raven Search'),
(170, 'rbse', 'RBSE Spider'),
(171, 'resumerobot', 'Resume Robot'),
(172, 'rhcs', 'RoadHouse Crawling System'),
(173, 'roadrunner', 'Road Runner: The ImageScape Robot'),
(174, 'robbie', 'Robbie the Robot'),
(175, 'robi', 'ComputingSite Robi/1.0'),
(176, 'robofox', 'RoboFox'),
(177, 'robozilla', 'Robozilla'),
(178, 'roverbot', 'Roverbot'),
(179, 'rules', 'RuLeS'),
(180, 'safetynetrobot', 'SafetyNet Robot'),
(181, 'scooter', 'Scooter (AltaVista)'),
(182, 'search_au', 'Search.Aus-AU.COM'),
(183, 'searchprocess', 'SearchProcess'),
(184, 'senrigan', 'Senrigan'),
(185, 'sgscout', 'SG-Scout'),
(186, 'shaggy', 'ShagSeeker'),
(187, 'shaihulud', 'Shai\'Hulud'),
(188, 'sift', 'Sift'),
(189, 'simbot', 'Simmany Robot Ver1.0'),
(190, 'site-valet', 'Site Valet'),
(191, 'sitegrabber', 'Open Text Index Robot'),
(192, 'sitetech', 'SiteTech-Rover'),
(193, 'slcrawler', 'SLCrawler'),
(194, 'slurp', 'Inktomi Slurp'),
(195, 'smartspider', 'Smart Spider'),
(196, 'snooper', 'Snooper'),
(197, 'solbot', 'Solbot'),
(198, 'spanner', 'Spanner'),
(199, 'speedy', 'Speedy Spider'),
(200, 'spider_monkey', 'spider_monkey'),
(201, 'spiderbot', 'SpiderBot'),
(202, 'spiderline', 'Spiderline Crawler'),
(203, 'spiderman', 'SpiderMan'),
(204, 'spiderview', 'SpiderView(tm)'),
(205, 'spry', 'Spry Wizard Robot'),
(206, 'ssearcher', 'Site Searcher'),
(207, 'suke', 'Suke'),
(208, 'suntek', 'suntek search engine'),
(209, 'sven', 'Sven'),
(210, 'tach_bw', 'TACH Black Widow'),
(211, 'tarantula', 'Tarantula'),
(212, 'tarspider', 'tarspider'),
(213, 'techbot', 'TechBOT'),
(214, 'templeton', 'Templeton'),
(215, 'teoma_agent1', 'TeomaTechnologies'),
(216, 'titin', 'TitIn'),
(217, 'titan', 'TITAN'),
(218, 'tkwww', 'The TkWWW Robot'),
(219, 'tlspider', 'TLSpider'),
(220, 'ucsd', 'UCSD Crawl'),
(221, 'udmsearch', 'UdmSearch'),
(222, 'urlck', 'URL Check'),
(223, 'valkyrie', 'Valkyrie'),
(224, 'victoria', 'Victoria'),
(225, 'visionsearch', 'vision-search'),
(226, 'voyager', 'Voyager'),
(227, 'vwbot', 'VWbot'),
(228, 'w3index', 'The NWI Robot'),
(229, 'w3m2', 'W3M2'),
(230, 'wallpaper', 'WallPaper'),
(231, 'wanderer', 'the World Wide Web Wanderer'),
(232, 'wapspider', 'w@pSpider by wap4.com'),
(233, 'webbandit', 'WebBandit Web Spider'),
(234, 'webcatcher', 'WebCatcher'),
(235, 'webcopy', 'WebCopy'),
(236, 'webfetcher', 'Webfetcher'),
(237, 'webfoot', 'The Webfoot Robot'),
(238, 'weblayers', 'Weblayers'),
(239, 'weblinker', 'WebLinker'),
(240, 'webmirror', 'WebMirror'),
(241, 'webmoose', 'The Web Moose'),
(242, 'webquest', 'WebQuest'),
(243, 'webreader', 'Digimarc MarcSpider'),
(244, 'webreaper', 'WebReaper'),
(245, 'websnarf', 'Websnarf'),
(246, 'webspider', 'WebSpider'),
(247, 'webvac', 'WebVac'),
(248, 'webwalk', 'webwalk'),
(249, 'webwalker', 'WebWalker'),
(250, 'webwatch', 'WebWatch'),
(251, 'wget', 'Wget'),
(252, 'whatuseek', 'whatUseek Winona'),
(253, 'whowhere', 'WhoWhere Robot'),
(254, 'wired-digital', 'Wired Digital'),
(255, 'wmir', 'w3mir'),
(256, 'wolp', 'WebStolperer'),
(257, 'wombat', 'The Web Wombat'),
(258, 'worm', 'The World Wide Web Worm'),
(259, 'wwwc', 'WWWC Ver 0.2.5'),
(260, 'wz101', 'WebZinger'),
(261, 'xget', 'XGET'),
(262, 'nederland.zoek', 'Nederland.zoek'),
(263, 'antibot', 'Antibot'),
(264, 'awbot', 'AWBot'),
(265, 'baiduspider', 'BaiDuSpider'),
(266, 'bobby', 'Bobby'),
(267, 'boris', 'Boris'),
(268, 'bumblebee', 'Bumblebee (relevare.com)'),
(269, 'cscrawler', 'CsCrawler'),
(270, 'daviesbot', 'DaviesBot'),
(271, 'digout4u', 'Digout4u'),
(272, 'echo', 'EchO!'),
(273, 'exactseek', 'ExactSeek Crawler'),
(274, 'ezresult', 'Ezresult'),
(275, 'fast-webcrawler', 'Fast-Webcrawler (AllTheWeb)'),
(276, 'gigabot', 'GigaBot'),
(277, 'gnodspider', 'GNOD Spider'),
(278, 'ia_archiver', 'Alexa (IA Archiver)'),
(279, 'internetseer', 'InternetSeer'),
(280, 'jennybot', 'JennyBot'),
(281, 'justview', 'JustView'),
(282, 'linkbot', 'LinkBot'),
(283, 'linkchecker', 'LinkChecker'),
(284, 'mercator', 'Mercator'),
(285, 'msiecrawler', 'MSIECrawler'),
(286, 'perman', 'Perman surfer'),
(287, 'petersnews', 'Petersnews'),
(288, 'pompos', 'Pompos'),
(289, 'psbot', 'psBot'),
(290, 'redalert', 'Red Alert'),
(291, 'shoutcast', 'Shoutcast Directory Service'),
(292, 'slysearch', 'SlySearch'),
(293, 'turnitinbot', 'Turn It In'),
(294, 'ultraseek', 'Ultraseek'),
(295, 'unlost_web_crawler', 'Unlost Web Crawler'),
(296, 'voila', 'Voila'),
(297, 'webbase', 'WebBase'),
(298, 'webcompass', 'webcompass'),
(299, 'wisenutbot', 'WISENutbot (Looksmart)'),
(300, 'yandex', 'Yandex bot'),
(301, 'zyborg', 'Zyborg (Looksmart)'),
(308, 'mixcat', 'morris - mixcat crawler'),
(305, 'netresearchserver', 'Net Research Server'),
(306, 'vagabondo', 'vagabondo (test version WiseGuys webagent)'),
(307, 'szukacz', 'Szukacz crawler'),
(309, 'grub-client', 'Grub\'s distributed crawler'),
(310, 'fluffy', 'fluffy (searchhippo)'),
(311, 'webtrends link analyzer', 'webtrends link analyzer'),
(312, 'naverrobot', 'naver'),
(313, 'steeler', 'steeler'),
(314, 'bordermanager', 'bordermanager'),
(315, 'nutch', 'Nutch'),
(316, 'teradex', 'Teradex'),
(317, 'deepindex', 'DeepIndex'),
(318, 'npbot', 'NPBot'),
(319, 'webcraftboot', 'Webcraftboot'),
(320, 'franklin locator', 'Franklin locator'),
(321, 'internet ninja', 'Internet ninja'),
(322, 'space bison', 'Space bison'),
(323, 'gornker', 'gornker crawler'),
(324, 'gaisbot', 'Gaisbot'),
(325, 'cj spider', 'CJ spider'),
(326, 'semanticdiscovery', 'Semantic Discovery'),
(327, 'zao', 'Zao'),
(328, 'web downloader', 'Web Downloader'),
(329, 'webstripper', 'Webstripper'),
(330, 'zeus', 'Zeus'),
(331, 'webrace', 'Webrace'),
(332, 'christcrawler', 'ChristCENTRAL'),
(333, 'webfilter', 'Webfilter'),
(334, 'webgather', 'Webgather'),
(335, 'surveybot', 'Surveybot'),
(336, 'nitle blog spider', 'Nitle Blog Spider'),
(337, 'galaxybot', 'Galaxybot'),
(338, 'fangcrawl', 'FangCrawl'),
(339, 'searchspider', 'SearchSpider'),
(340, 'msnbot', 'msnbot'),
(341, 'computer_and_automation_research_institute_crawler', 'computer and automation research institute crawler'),
(342, 'overture-webcrawler', 'overture-webcrawler'),
(343, 'exalead ng', 'exalead ng'),
(344, 'denmex websearch', 'denmex websearch'),
(345, 'linkfilter.net url verifier', 'linkfilter.net url verifier'),
(346, 'mac finder', 'mac finder'),
(347, 'polybot', 'polybot'),
(348, 'quepasacreep', 'quepasacreep'),
(349, 'xenu link sleuth', 'xenu link sleuth'),
(350, 'hatena antenna', 'hatena antenna'),
(351, 'timbobot', 'timbobot'),
(352, 'waypath scout', 'waypath scout'),
(353, 'technoratibot', 'technoratibot'),
(354, 'frontier', 'frontier'),
(355, 'blogosphere', 'blogosphere'),
(356, 'my little bot', 'my little bot'),
(357, 'illinois state tech labs', 'illinois state tech labs'),
(358, 'splatsearch.com', 'splatsearch'),
(359, 'blogshares bot', 'blogshares bot'),
(360, 'fastbuzz.com', 'fastbuzz'),
(361, 'obidos-bot', 'obidos'),
(362, 'blogwise.com-metachecker', 'blogwise.com metachecker'),
(363, 'bravobrian bstop', 'bravobrian bstop'),
(364, 'feedster crawler', 'feedster'),
(365, 'isspider', 'blogpulse'),
(366, 'syndic8', 'syndic8'),
(367, 'blogvisioneye', 'blogvisioneye'),
(368, 'downes/referrers', 'downes/referrers'),
(369, 'naverbot', 'naverbot'),
(370, 'soziopath', 'soziopath'),
(371, 'nextopiabot', 'nextopiabot'),
(372, 'ingrid', 'ingrid'),
(373, 'vspider', 'vspider'),
(374, 'yahoo', 'Yahoo'),
(375, 'sherlock-spider', 'Sherlock Spider'),
(376, 'mercubot', 'Mercubot'),
(377, 'mediapartners-google', 'Mediapartners Google'),
(378, 'jetbot', 'JetBot'),
(379, 'faxobot', 'FaxoBot'),
(380, 'cosmixcrawler', 'cosmix crawler'),
(381, 'exabot', 'exabot'),
(382, 'sitespider', 'sitespider'),
(383, 'pipeliner', 'pipeliner'),
(384, 'ccgcrawl', 'ccgcrawl'),
(385, 'cydralspider', 'cydralspider'),
(386, 'crawlconvera', 'crawlconvera'),
(387, 'blogwatcher', 'blogwatcher'),
(388, 'mozdex', 'mozdex'),
(389, 'aleksika spider', 'aleksika spider'),
(390, 'e-societyrobot', 'e-societyrobot'),
(391, 'enterprise_search', 'enterprise search'),
(392, 'seekbot', 'seekbot'),
(393, 'emeraldshield', 'emeraldshield'),
(394, 'mj12bot', 'mj12bot'),
(395, 'aipbot', 'aipbot'),
(396, 'omniexplorer_bot', 'omniexplorer_bot'),
(397, 'shim-crawler', 'shim-crawler'),
(398, 'nimblecrawler', 'nimblecrawler'),
(399, 'msrbot', 'msrbot'),
(400, 'scirus', 'scirus'),
(401, 'geniebot', 'geniebot'),
(402, 'nextgensearchbot', 'nextgensearchbot'),
(403, 'ichiro', 'ichiro'),
(404, 'peerfactor 404 crawler','peerfactor 404 crawler'),
(405, 'ebay relevance ad crawler', 'Ebay relevance ad crawler'),
(406, 'yodaobot', 'yodaobot/1.0'),
(407, 'vmbot', 'vmbot/0.9'),
(408, 'Blaiz-Bee', 'Blaiz-Bee/2.00.*'),
(409, 'sensis', 'Sensis Web Crawler'),
(410, 'ABACHOBot', 'ABACHOBot'),
(411, 'AbiLogicBot', 'AbiLogicBot http://www.abilogic.com/bot.html'),
(412, 'Googlebot-Image', 'Googlebot-Image'),
(413, 'EmailSiphon', 'EmailSiphon (Sonic) - Email Collector'),
(414, 'W3C-checklink', 'W3C Linkchecker'),
(419, 'W3C_Validator', 'W3C XHTML/HTML Validator'),
(420, 'depspid', 'DepSpid http://about.depspid.net'),
(421, 'panscient.com', 'Panscient web crawler http://panscient.com'),
(422, 'Bloglines/3.1','Web based Feed reader for Ask Jeeves Bloglines (http://www.bloglines.com)'),
(423, 'everyfeed-spider/2.0','http://www.everyfeed.com'),
(424, 'FeedFetcher-Google','Google\'s feedfetcher (http://www.google.com/feedfetcher.html)'),
(425, 'Gregarius/0.5.2','http://devlog.gregarius.net/docs/ua'),
(426, 'CSE HTML Validator Lite Online','Free online HTML Editor and Syntax Checker (http://online.htmlvalidator.com/php/onlinevallite.php)'),
(427, 'Cynthia 1.0','Validator for HiSoftware Cynthia Says portal (http://www.contentquality.com/)'),
(428, 'HTMLParser/1.6','HTML Parser a Java library used to parse HTML (http://htmlparser.sourceforge.net/)'),
(429, 'P3P Validator','Platform for Privacy Preferences Project (P3P) by W3C (http://www.w3.org/)'),
(430, 'Jigsaw/2.2.5 W3C_CSS_Validator_JFouffa/2.0','W3C CSS Validator, CSS validator for Cascading Style Sheets, level 2 (http://www.w3.org/)');
";





// ###################
//
//      BROWSERS
//
// ###################
//
// last update: 2008.10.15 mic
//in version 2.2.3 (versions 2.3.0.0 to 2.3.0.84 contain only some of them (not all))
//  - added positions 87, 88
//
// NOTICE:
//    a) Positions 0 and 1 are correct (both unknown)! See documentation!
//    b) Key for position 1 could be changed (but must be differ from all other keys, particulary from empty (''))
//    a) deprecated entries should be removed (all popular and used at II 2009 contain images!)
//    b) browser_type should be verified and fixed
//    c) in JS there are also images for groups
//         full list is in _JS_DB_TABLE__BROWSERSTYPE define
//    d) line (82, 'avant browser', 'avant browser', 5, 'avant'),
//         should be changed to (82, 'avant browser', 'Avant Browser', 5, 'avant'),
//    e) browser_fullname should be fixed! - First letter upper case, human freindly
//    f) OmniWeb - is browser for Mac
//    g) Chrome browser - it is a safari browser! so testing is not so easy!
//          (87, 'chrome', 'Google Chrome', 5, 'chrome')
//    h) 73, 74, 75 should be moved to bots

$quer[] = "TRUNCATE TABLE #__jstats_browsers";

$quer[] = "INSERT IGNORE INTO #__jstats_browsers (browser_id, browser_type, browser_string, browser_fullname, browser_img) VALUES
(0, 0, '', 'Unknown', 'unknown'),
(1, 0, 'unknown internet browser', 'Unknown Internet Browser', 'unknown'),
(2, 1, 'msie', 'Internet Explorer', 'explorer'),
(3, 2, 'firefox', 'FireFox', 'firefox'),
(4, 5, 'chrome', 'Google Chrome', 'chrome'),
(5, 3, 'opera', 'Opera', 'opera'),
(6, 5, 'netscape', 'Netscape', 'netscape'),
(7, 5, 'icab', 'iCab', 'noimage'),
(8, 5, 'konqueror', 'Konqueror', 'konqueror'),
(9, 5, 'links', 'Links', 'noimage'),
(10, 5, 'lynx', 'Lynx', 'noimage'),
(11, 5, 'omniweb', 'OmniWeb', 'omniweb'),
(12, 5, 'safari', 'Safari', 'safari'),
(13, 5, 'wget', 'Wget', 'noimage'),
(14, 5, 'aol-iweng', 'AOL-Iweng', 'noimage'),
(15, 5, 'amaya', 'Amaya', 'noimage'),
(16, 5, 'amigavoyager', 'AmigaVoyager', 'noimage'),
(17, 5, 'aweb', 'AWeb', 'noimage'),
(18, 5, 'bpftp', 'BPFTP', 'noimage'),
(19, 5, 'cyberdog', 'Cyberdog', 'noimage'),
(20, 5, 'dreamcast', 'Dreamcast', 'noimage'),
(21, 5, 'downloadagent', 'DownloadAgent', 'noimage'),
(22, 5, 'ecatch', 'eCatch', 'noimage'),
(23, 5, 'emailsiphon', 'EmailSiphon', 'noimage'),
(24, 5, 'encompass', 'Encompass', 'noimage'),
(25, 5, 'friendlyspider', 'FriendlySpider', 'noimage'),
(26, 5, 'fresco', 'ANT Fresco', 'noimage'),
(27, 5, 'galeon', 'Galeon', 'noimage'),
(28, 5, 'getright', 'GetRight', 'noimage'),
(29, 5, 'headdump', 'HeadDump', 'noimage'),
(30, 5, 'hotjava', 'Sun HotJava', 'noimage'),
(31, 5, 'ibrowse', 'IBrowse', 'noimage'),
(32, 5, 'intergo', 'InterGO', 'noimage'),
(33, 5, 'linemodebrowser', 'W3C Line Mode Browser', 'noimage'),
(34, 5, 'lotus-notes', 'Lotus Notes web client', 'noimage'),
(35, 5, 'macweb', 'MacWeb', 'noimage'),
(36, 5, 'ncsa_mosaic', 'NCSA Mosaic', 'noimage'),
(37, 5, 'netpositive', 'NetPositive', 'noimage'),
(38, 5, 'nutscrape', 'Nutscrape', 'noimage'),
(39, 5, 'msfrontpageexpress', 'MS FrontPage Express', 'noimage'),
(40, 5, 'phoenix', 'Phoenix', 'noimage'),
(41, 5, 'tzgeturl', 'TzGetURL', 'noimage'),
(42, 5, 'viking', 'Viking', 'noimage'),
(43, 5, 'webfetcher', 'WebFetcher', 'noimage'),
(44, 5, 'webexplorer', 'IBM-WebExplorer', 'noimage'),
(45, 5, 'webmirror', 'WebMirror', 'noimage'),
(46, 5, 'webvcr', 'WebVCR', 'noimage'),
(47, 5, 'teleport', 'TelePort Pro', 'noimage'),
(48, 5, 'webcapture', 'Acrobat', 'noimage'),
(49, 5, 'webcopier', 'WebCopier', 'noimage'),
(50, 5, 'real', 'RealAudio or compatible (media player)', 'noimage'),
(51, 5, 'winamp', 'WinAmp (media player)', 'noimage'),
(52, 5, 'windows-media-player', 'Windows Media Player (media player)', 'noimage'),
(53, 5, 'audion', 'Audion (media player)', 'noimage'),
(54, 5, 'freeamp', 'FreeAmp (media player)', 'noimage'),
(55, 5, 'itunes', 'Apple iTunes (media player)', 'noimage'),
(56, 5, 'jetaudio', 'JetAudio (media player)', 'noimage'),
(57, 5, 'mint_audio', 'Mint Audio (media player)', 'noimage'),
(58, 5, 'mpg123', 'mpg123 (media player)', 'noimage'),
(59, 5, 'nsplayer', 'NetShow Player (media player)', 'noimage'),
(60, 5, 'sonique', 'Sonique (media player)', 'noimage'),
(61, 5, 'uplayer', 'Ultra Player (media player)', 'noimage'),
(62, 5, 'xmms', 'XMMS (media player)', 'noimage'),
(63, 5, 'xaudio', 'Some XAudio Engine based MPEG player (media player', 'noimage'),
(64, 4, 'mmef', 'Microsoft Mobile Explorer (PDA/Phone browser)', 'noimage'),
(65, 4, 'mspie', 'MS Pocket Internet Explorer (PDA/Phone browser)', 'noimage'),
(66, 4, 'wapalizer', 'WAPalizer (PDA/Phone browser)', 'noimage'),
(67, 4, 'wapsilon', 'WAPsilon (PDA/Phone browser)', 'noimage'),
(68, 4, 'webcollage', 'WebCollage (PDA/Phone browser)', 'noimage'),
(69, 4, 'alcatel', 'Alcatel Browser (PDA/Phone browser)', 'noimage'),
(70, 4, 'nokia', 'Nokia Browser (PDA/Phone browser)', 'noimage'),
(71, 5, 'webtv', 'WebTV browser', 'noimage'),
(72, 5, 'w3m', 'w3m', 'noimage'),
(73, 5, 'webzip', 'WebZIP', 'noimage'),
(74, 5, 'staroffice', 'StarOffice', 'noimage'),
(75, 5, 'libwww', 'LibWWW', 'noimage'),
(76, 5, 'httrack', 'HTTrack (offline browser utility)', 'noimage'),
(77, 5, 'webstripper', 'webstripper (offline browser)', 'noimage'),
(78, 5, 'avant browser', 'Avant Browser', 'avant'),
(79, 5, 'firebird', 'firebird', 'firebird'),
(80, 5, 'avantgo', 'avantgo', 'noimage'),
(81, 5, 'navio_aoltv', 'navio aoltv', 'noimage'),
(82, 5, 'go!zilla', 'Go!Zilla', 'noimage'),
(83, 5, '22acidownload', '22AciDownload', 'noimage'),
(84, 5, 'gecko', 'Mozilla', 'gecko')
";




// search engines
//in version 2.2.3 (versions 2.3.0.0 to 2.3.0.84 contain only some of them (not all))
//  - added positions 82-88
//  - modified positions: 9, 12
$quer[] = "TRUNCATE TABLE #__jstats_search_engines";

$quer[] = "INSERT IGNORE INTO #__jstats_search_engines (searchid, description, search, searchvar) VALUES
(1, 'Google', 'google.', 'p=|q='),
(2, 'Alexa', 'alexa.com', 'q='),
(3, 'Alltheweb', 'alltheweb.com', 'query=|q='),
(4, 'Altavista', 'altavista.', 'q='),
(5, 'DMOZ', 'dmoz.org', 'search='),
(6, 'Google Images', 'images.google.', 'p=|q='),
(7, 'Lycos', 'lycos.', 'query='),
(8, 'Msn', 'msn.', 'q='),
(9, 'Netscape', 'netscape.', 'search=|q=|query='),
(10, 'Search AOL', 'search.aol.com', 'query='),
(11, 'Search Terra', 'search.terra.', 'query='),
(12, 'Voila', 'voila.', 'kw=|rdata='),
(13, 'Search.Com', 'www.search.com', 'q='),
(14, 'Yahoo', 'yahoo.', 'p='),
(15, 'Go Com', '.go.com', 'qt='),
(16, 'Ask Com', '.ask.com', 'ask='),
(17, 'Atomz', 'atomz.', 'sp-q='),
(18, 'EuroSeek', 'euroseek.', 'query='),
(19, 'Excite', 'excite.', 'search='),
(20, 'FindArticles', 'findarticles.com', 'key='),
(21, 'Go2Net', 'go2net.com', 'general='),
(22, 'HotBot', 'hotbot.', 'mt='),
(23, 'InfoSpace', 'infospace.com', 'qkw='),
(24, 'Kvasir', 'kvasir.', 'q='),
(25, 'LookSmart', 'looksmart.', 'key='),
(26, 'Mamma', 'mamma.', 'query='),
(27, 'MetaCrawler', 'metacrawler.', 'general='),
(28, 'Nbci.Com', 'nbci.com/search', 'keyword='),
(29, 'Northernlight', 'northernlight.', 'qr='),
(30, 'Overture', 'overture.com', 'keywords='),
(31, 'Dogpile', 'dogpile.com', 'qkw='),
(32, 'Dogpile', 'search.dogpile.com', 'q='),
(33, 'Spray', 'spray.', 'string='),
(34, 'Teoma', 'teoma.', 'q='),
(35, 'Virgilio', 'virgilio.it', 'qs='),
(36, 'Webcrawler', 'webcrawler', 'searchText='),
(37, 'Wisenut', 'wisenut.com', 'query='),
(38, 'ix quick', 'ixquick.com', 'query='),
(39, 'Earthlink', 'search.earthlink.net', 'q='),
(40, 'Sympatico', 'search.sli.sympatico.ca', 'query='),
(41, 'I-une', 'i-une.com', 'keywords=|q='),
(42, 'Miner.Bol.Com', 'miner.bol.com.br', 'q='),
(43, 'Baidu', 'baidu.com', 'word='),
(44, 'Sina', 'search.sina.com', 'word='),
(45, 'Sohu', 'search.sohu.com', 'word='),
(46, 'Atlas cz', 'atlas.cz', 'searchtext='),
(47, 'Seznam cz', 'seznam.cz', 'w='),
(48, 'Ftxt Quick cz', 'ftxt.quick.cz', 'query='),
(49, 'Centrum cz', 'centrum.cz', 'q='),
(50, 'Opasia dk', 'opasia.dk', 'q='),
(51, 'Danielsen', 'danielsen.com', 'q='),
(52, 'Sol dk', 'sol.dk', 'q='),
(53, 'Jubii dk', 'jubii.dk', 'soegeord='),
(54, 'Find dk', 'find.dk', 'words='),
(55, 'Edderkoppen dk', 'edderkoppen.dk', 'query='),
(56, 'Orbis dk', 'orbis.dk', 'search_field='),
(57, '1klik dk', '1klik.dk', 'query='),
(58, 'Ofir dk', 'ofir.dk', 'querytext='),
(59, 'Ilse nl', 'ilse.', 'search_for='),
(60, 'Vindex nl', 'vindex.', 'in='),
(61, 'Ask uk', 'ask.co.uk', 'ask='),
(62, 'BBC uk', 'bbc.co.uk/cgi-bin/search', 'q='),
(63, 'ifind uk', 'ifind.freeserve', 'q='),
(64, 'Looksmart uk', 'looksmart.co.uk', 'key='),
(65, 'mirago uk', 'mirago.', 'txtsearch='),
(66, 'Splut uk', 'splut.', 'pattern='),
(67, 'Spotjockey uk', 'spotjockey.', 'Search_Keyword='),
(68, 'Ukindex uk', 'ukindex.co.uk', 'stext='),
(69, 'Ukdirectory uk', 'ukdirectory.', 'k='),
(70, 'Ukplus uk', 'ukplus.', 'search='),
(71, 'Searchy uk', 'searchy.co.uk', 'search_term='),
(73, 'Haku fi', 'haku.www.fi', 'w='),
(74, 'Nomade fr', 'nomade.fr', 's='),
(75, 'Francite fr', 'francite.', 'name='),
(76, 'Club internet fr', 'recherche.club-internet.fr', 'q='),
(77, 'yandex', 'yandex.ru', 'text='),
(78, 'nigma', 'nigma.ru', 'q='),
(79, 'rambler', 'rambler.ru', 'words='),
(80, 'aport', 'aport.ru', 'r='),
(81, 'mail', 'mail.ru', 'q='),
(82, 'Live Search', 'search.live.com', 'q='),
(83, 'AOL.fr','aol.fr','query=|q='),
(84, 'Conduit.com','conduit.com','q='),
(85, 'live.com','search.live.com','q='),
(86, 'AliceADSL','aliceadsl.fr','qs='),
(87, 'bluewin.ch','bluewin.ch','query='),
(88, 'T-online','t-online.de','q='),
(89, 'ICQ.com','search.icq.com','q=|query=')";

//(90, 'In.gr','find.in.gr','data=')"; //request by user, but it is very rare
//(91, 'Charter.net','charter.net','q=')"; //request by user, but it is very rare






// ###################
//
//  OPERATING SYSTEMS
//
// ###################
//
//define('_JS_DB_OSTYP__ID_UNKNOWN',                   0);
//define('_JS_DB_OSTYP__ID_WINDOWS',                   1);
//define('_JS_DB_OSTYP__ID_LINUX_UNIX_MAC',            2);//windows or unix or mac
//define('_JS_DB_OSTYP__ID_PDA_PHONE',                 3);//pda or phone or mobile
//define('_JS_DB_OSTYP__ID_OTHER',                     4);
//
// NOTICE: 
//   a) no changes from version version 2.2.3 to 2.3.0.158
//   b) index 9 is missing from version v2.1.0 (inclusive)
//
//   c) positions that should be removed: 20, 29, 30
//        32 position is correct?
//        36 position is correct?
//   d) Position 36 catch mainly 'windows nt 4.0' - it is OK, because some NT systems are without version - '(36, 'windows nt', 'Windows NT', 1, 'windows2000')'
//      I made tests on jos_jstats_ipaddresses with 15,375 records - above change made that I losse only 2 real visitors
//   e) #__jstats_systems this table will be renamed to #__jstats_os, all column prefixes also will be renamed to os
//   f) position 33 is correct? 'Windows 2003' - Was it something like this?

$quer[] = "TRUNCATE TABLE #__jstats_systems";

$quer[] = "INSERT IGNORE INTO #__jstats_systems (sys_id, sys_string, sys_fullname, sys_type, sys_img) VALUES
(0, '', 'Unknown', 0, 'unknown'),
(1, 'win95', 'Windows 95', 1, 'windows2000'),
(2, 'windows 95', 'Windows 95', 1, 'windows2000'),
(3, 'win98', 'Windows 98', 1, 'windows2000'),
(4, 'windows 98', 'Windows 98', 1, 'windows2000'),
(5, 'winme', 'Windows me', 1, 'windows2000'),
(6, 'windows me', 'Windows me', 1, 'windows2000'),
(7, 'windows nt 4.0', 'Windows NT', 1, 'windows2000'),
(8, 'windows nt 5.0', 'Windows 2000', 1, 'windows2000'),
(9, 'winnt 5.0', 'Windows 2000', 1, 'windows2000'),
(10, 'winnt 5.1', 'Windows XP', 1, 'windowsxp'),
(11, 'windows nt 5.1', 'Windows XP', 1, 'windowsxp'),
(12, 'macintosh', 'Mac OS', 2, 'mac'),
(13, 'linux', 'Linux', 2, 'linux'),
(14, 'aix', 'Aix', 3, 'aix'),
(15, 'sunos', 'Sun Solaris', 4, 'solaris'),
(16, 'irix', 'Irix', 4, 'irix'),
(17, 'osf', 'OSF Unix', 2, 'linux'),
(18, 'hp-ux', 'HP Unix', 2, 'hpux'),
(19, 'netbsd', 'NetBSD', 2, 'netbsd'),
(20, 'bsdi', 'BSDi', 2, 'freebsd'),
(21, 'freebsd', 'FreeBSD', 2, 'freebsd'),
(22, 'openbsd', 'OpenBSD', 2, 'openbsd'),
(23, 'unix', 'Unknown Unix system', 2, 'unix'),
(24, 'beos', 'BeOS', 4, 'beos'),
(25, 'os/2', 'Warp OS/2', 4, 'os2'),
(26, 'amigaos', 'AmigaOS', 4, 'amiga'),
(27, 'vms', 'VMS', 4, 'vms'),
(28, 'cp/m', 'CPM', 4, 'noimage'),
(29, 'crayos', 'CrayOS', 4, 'cray'),
(30, 'dreamcast', 'Dreamcast', 4, 'dreamcast'), 
(31, 'riscos', 'RISC OS', 4, 'risc'),
(32, 'webtv', 'WebTV', 4, 'webtv'),
(33, 'windows nt 5.2', 'Windows 2003', 1, 'windows2000'),
(34, 'mac_powerpc', 'Mac PowerPC', 2, 'mac'),
(35, 'mac os x', 'Mac OS X', 2, 'mac'),
(36, 'windows nt', 'Windows NT', 1, 'windows2000'),
(37, 'windows nt 6.0', 'Windows Vista', 1, 'windowsvista'),
(38, 'windows nt 6.1', 'Windows 7', 1, 'windows7')
";





// TLD
// NOTICE: max index is 273!!!
//in version 2.2.3 only indexes to 269 (inclusive) exists
//
// NOTICE:
//   a) table should be reindexed eg. 6 should be reserved for 'ah'

$quer[] = "INSERT IGNORE INTO #__jstats_topleveldomains (tld_id, tld, fullname) VALUES
(0, 'unknown', 'Unknown'),
(1, 'ac', 'Ascension Island'),
(2, 'ad', 'Andorra'),
(3, 'ae', 'United Arab Emirates'),
(4, 'af', 'Afghanistan'),
(5, 'ag', 'Antigua and Barbuda'),
(6, 'ai', 'Anguilla'),
(7, 'al', 'Albania'),
(8, 'am', 'Armenia'),
(9, 'an', 'Netherlands Antilles'),
(10, 'ao', 'Angola'),
(11, 'aq', 'Antarctica'),
(12, 'ar', 'Argentina'),
(13, 'as', 'American Samoa'),
(14, 'at', 'Austria'),
(15, 'au', 'Australia'),
(16, 'aw', 'Aruba'),
(17, 'ax', 'Aland Islands'),
(18, 'az', 'Azerbaijan'),
(19, 'ba', 'Bosnia Hercegovina'),
(20, 'bb', 'Barbados'),
(21, 'bd', 'Bangladesh'),
(22, 'be', 'Belgium'),
(23, 'bf', 'Burkina Faso'),
(24, 'bg', 'Bulgaria'),
(25, 'bh', 'Bahrain'),
(26, 'bi', 'Burundi'),
(27, 'bj', 'Benin'),
(28, 'bm', 'Bermuda'),
(29, 'bn', 'Brunei Darussalam'),
(30, 'bo', 'Bolivia'),
(31, 'br', 'Brazil'),
(32, 'bs', 'Bahamas'),
(33, 'bt', 'Bhutan'),
(34, 'bv', 'Bouvet Island'),
(35, 'bw', 'Botswana'),
(36, 'by', 'Belarus (Byelorussia)'),
(37, 'bz', 'Belize'),
(38, 'ca', 'Canada'),
(39, 'cc', 'Cocos Islands (Keeling)'),
(40, 'cd', 'Congo, Democratic Republic of the'),
(41, 'cf', 'Central African Republic'),
(42, 'cg', 'Congo, Republic of'),
(43, 'ch', 'Switzerland'),
(44, 'ci', 'Cote d\'Ivoire (Ivory Coast)'),
(45, 'ck', 'Cook Islands'),
(46, 'cl', 'Chile'),
(47, 'cm', 'Cameroon'),
(48, 'cn', 'China'),
(49, 'co', 'Colombia'),
(50, 'cr', 'Costa Rica'),
(51, 'cs', 'Serbia and Montenegro'),
(52, 'cu', 'Cuba'),
(53, 'cv', 'Cap Verde'),
(54, 'cx', 'Christmas Island'),
(55, 'cy', 'Cyprus'),
(56, 'cz', 'Czech Republic'),
(57, 'de', 'Germany'),
(58, 'dj', 'Djibouti'),
(59, 'dk', 'Denmark'),
(60, 'dm', 'Dominica'),
(61, 'do', 'Dominican Republic'),
(62, 'dz', 'Algeria'),
(63, 'ec', 'Ecuador'),
(64, 'ee', 'Estonia'),
(65, 'eg', 'Egypt'),
(66, 'eh', 'Western Sahara'),
(67, 'er', 'Eritrea'),
(68, 'es', 'Spain'),
(69, 'et', 'Ethiopia'),
(70, 'fi', 'Finland'),
(71, 'fj', 'Fiji'),
(72, 'fk', 'Falkland Islands'),
(73, 'fm', 'Micronesia, Federated States of'),
(74, 'fo', 'Faroe Islands'),
(75, 'fr', 'France'),
(76, 'ga', 'Gabon'),
(77, 'gb', 'United Kingdom'),
(78, 'gd', 'Grenada'),
(79, 'ge', 'Georgia'),
(80, 'gf', 'French Guiana'),
(81, 'gg', 'Guernsey'),
(82, 'gh', 'Ghana'),
(83, 'gi', 'Gibraltar'),
(84, 'gl', 'Greenland'),
(85, 'gm', 'Gambia'),
(86, 'gn', 'Guinea'),
(87, 'gp', 'Guadeloupe'),
(88, 'gq', 'Equatorial Guinea'),
(89, 'gr', 'Greece'),
(90, 'gs', 'South Georgia and the South Sandwich Islands'),
(91, 'gt', 'Guatemala'),
(92, 'gu', 'Guam'),
(93, 'gw', 'Guinea-Bissau'),
(94, 'gy', 'Guyana'),
(95, 'hk', 'Hong Kong'),
(96, 'hm', 'Heard and McDonald Islands'),
(97, 'hn', 'Honduras'),
(98, 'hr', 'Croatia/Hrvatska'),
(99, 'ht', 'Haiti'),
(100, 'hu', 'Hungary'),
(101, 'id', 'Indonesia'),
(102, 'ie', 'Ireland'),
(103, 'il', 'Israel'),
(104, 'im', 'Isle of Man'),
(105, 'in', 'India'),
(106, 'io', 'British Indian Ocean Territory'),
(107, 'iq', 'Iraq'),
(108, 'ir', 'Iran, Islamic Republic of'),
(109, 'is', 'Iceland'),
(110, 'it', 'Italy'),
(111, 'je', 'Jersey'),
(112, 'jm', 'Jamaica'),
(113, 'jo', 'Jordan'),
(114, 'jp', 'Japan'),
(115, 'ke', 'Kenya'),
(116, 'kg', 'Kyrgyzstan'),
(117, 'kh', 'Cambodia'),
(118, 'ki', 'Kiribati'),
(119, 'km', 'Comoros'),
(120, 'kn', 'Saint Kitts and Nevis'),
(121, 'kp', 'Korea, Democratic People\'s Republic'),
(122, 'kr', 'Korea, Republic of'),
(123, 'kw', 'Kuwait'),
(124, 'ky', 'Cayman Islands'),
(125, 'kz', 'Kazakhstan'),
(126, 'la', 'Lao People\'s Democratic Republic'),
(127, 'lb', 'Lebanon'),
(128, 'lc', 'Saint Lucia'),
(129, 'li', 'Liechtenstein'),
(130, 'lk', 'Sri Lanka'),
(131, 'lr', 'Liberia'),
(132, 'ls', 'Lesotho'),
(133, 'lt', 'Lithuania'),
(134, 'lu', 'Luxembourg'),
(135, 'lv', 'Latvia'),
(136, 'ly', 'Libyan Arab Jamahiriya'),
(137, 'ma', 'Morocco'),
(138, 'mc', 'Monaco'),
(139, 'md', 'Moldova, Republic of'),
(271, 'me', 'Montenegro'),
(140, 'mg', 'Madagascar'),
(141, 'mh', 'Marshall Islands'),
(142, 'mk', 'Macedonia, Former Yugoslav Republic'),
(143, 'ml', 'Mali'),
(144, 'mm', 'Myanmar'),
(145, 'mn', 'Mongolia'),
(146, 'mo', 'Macau'),
(147, 'mp', 'Northern Mariana Islands'),
(148, 'mq', 'Martinique'),
(149, 'mr', 'Mauritani'),
(150, 'ms', 'Montserrat'),
(151, 'mt', 'Malta'),
(152, 'mu', 'Mauritius'),
(153, 'mv', 'Maldives'),
(154, 'mw', 'Malawi'),
(155, 'mx', 'Mexico'),
(156, 'my', 'Malaysia'),
(157, 'mz', 'Mozambique'),
(158, 'na', 'Namibia'),
(159, 'nc', 'New Caledonia'),
(160, 'ne', 'Niger'),
(161, 'nf', 'Norfolk Island'),
(162, 'ng', 'Nigeria'),
(163, 'ni', 'Nicaragua'),
(164, 'nl', 'Netherlands'),
(165, 'no', 'Norway'),
(166, 'np', 'Nepal'),
(167, 'nr', 'Nauru'),
(168, 'nt', 'Neutral Zone'),
(169, 'nu', 'Niue'),
(170, 'nz', 'New Zealand'),
(171, 'om', 'Oman'),
(172, 'pa', 'Panama'),
(173, 'pe', 'Peru'),
(174, 'pf', 'French Polynesia'),
(175, 'pg', 'Papua New Guinea'),
(176, 'ph', 'Philippines'),
(177, 'pk', 'Pakistan'),
(178, 'pl', 'Poland'),
(179, 'pm', 'St. Pierre and Miquelon'),
(180, 'pn', 'Pitcairn Island'),
(181, 'pr', 'Puerto Rico'),
(182, 'ps', 'Palestinian Territories'),
(183, 'pt', 'Portugal'),
(184, 'pw', 'Palau'),
(185, 'py', 'Paraguay'),
(186, 'qa', 'Qatar'),
(187, 're', 'Reunion Island'),
(188, 'ro', 'Romania'),
(272, 'rs', 'Serbia'),
(189, 'ru', 'Russian Federation'),
(190, 'rw', 'Rwanda'),
(191, 'sa', 'Saudi Arabia'),
(192, 'sb', 'Solomon Islands'),
(193, 'sc', 'Seychelles'),
(194, 'sd', 'Sudan'),
(195, 'se', 'Sweden'),
(196, 'sg', 'Singapore'),
(197, 'sh', 'St. Helena'),
(198, 'si', 'Slovenia'),
(199, 'sj', 'Svalbard and Jan Mayen Islands'),
(200, 'sk', 'Slovak Republic'),
(201, 'sl', 'Sierra Leone'),
(202, 'sm', 'San Marino'),
(203, 'sn', 'Senegal'),
(204, 'so', 'Somalia'),
(205, 'sr', 'Suriname'),
(206, 'st', 'Sao Tome and Principe'),
(207, 'su', 'Former Soviet Union'),
(208, 'sv', 'El Salvador'),
(209, 'sy', 'Syrian Arab Republic'),
(210, 'sz', 'Swaziland'),
(211, 'tc', 'Turks and Caicos Islands'),
(212, 'td', 'Chad'),
(213, 'tf', 'French Southern Territories'),
(214, 'tg', 'Togo'),
(215, 'th', 'Thailand'),
(216, 'tj', 'Tajikistan'),
(217, 'tk', 'Tokelau'),
(218, 'tl', 'East Timor'),
(219, 'tm', 'Turkmenistan'),
(220, 'tn', 'Tunisia'),
(221, 'to', 'Tonga'),
(222, 'tp', 'East Timor'),
(223, 'tr', 'Turkey'),
(224, 'tt', 'Trinidad and Tobago'),
(225, 'tv', 'Tuvalu'),
(226, 'tw', 'Taiwan'),
(227, 'tz', 'Tanzania'),
(228, 'ua', 'Ukraine'),
(229, 'ug', 'Uganda'),
(230, 'uk', 'United Kingdom'),
(231, 'um', 'US Minor Outlying Islands'),
(232, 'us', 'United States'),
(233, 'uy', 'Uruguay'),
(234, 'uz', 'Uzbekistan'),
(235, 'va', 'Holy See (City Vatican State)'),
(236, 'vc', 'Saint Vincent and the Grenadines'),
(237, 've', 'Venezuela'),
(238, 'vg', 'Virgin Islands (British)'),
(239, 'vi', 'Virgin Islands (USA)'),
(240, 'vn', 'Vietnam'),
(241, 'vu', 'Vanuatu'),
(242, 'wf', 'Wallis and Futuna Islands'),
(243, 'ws', 'Western Samoa'),
(244, 'ye', 'Yemen'),
(245, 'yt', 'Mayotte'),
(246, 'yu', 'Serbia and Montenegro'),
(247, 'za', 'South Africa'),
(248, 'zm', 'Zambia'),
(249, 'zw', 'Zimbabwe'),

(250, 'eu', 'European Union'),
(251, 'cat', 'Catalonia'),
(273, 'asia', 'Asia'),

(252, 'com', 'Commercial'),
(253, 'net', 'Network'),
(254, 'org', 'Organization'),

(255, 'gov', 'US Government'),
(256, 'mil', 'US Military (Dept of Defense)'),

(257, 'int', 'International Organizations'),

(258, 'aero', 'Aviation Industry'),
(259, 'biz', 'Businesses'),
(260, 'coop', 'Cooperatives'),
(261, 'edu', 'Educational Institutions'),
(262, 'info', 'Worldwide unrestricted use'),
(263, 'jobs', 'Job Offering Companies'),
(264, 'mobi', 'Mobile Internet Services'),
(265, 'museum', 'Museums'),
(266, 'name', 'Individuals and Families'),
(267, 'pro', 'Attorneys, Physicians, Engineers, and Accountants'),
(274, 'tel', 'Telephone Service'),
(268, 'travel', 'Travel and Tourism Industry'),
(269, 'arpa', 'Old Style Arpanet')";
