
--
-- データベース: `rss`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(3) NOT NULL,
  `category` varchar(512) NOT NULL,
  `touroku_date` varchar(8) NOT NULL,
  `touroku_time` varchar(6) NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `login_his`
--

CREATE TABLE IF NOT EXISTS `login_his` (
  `NO` int(9) NOT NULL AUTO_INCREMENT,
  `id` int(3) NOT NULL,
  `touroku_date` varchar(8) NOT NULL,
  `touroku_time` varchar(6) NOT NULL,
  `memo` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `kyaria_cd` int(1) NOT NULL,
  `agent` varchar(1000) NOT NULL,
  PRIMARY KEY (`NO`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=531 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `login_user`
--

CREATE TABLE IF NOT EXISTS `login_user` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  `mb_key` varchar(255) DEFAULT NULL,
  `touroku_date` varchar(8) NOT NULL,
  `touroku_time` varchar(6) NOT NULL DEFAULT '',
  `e-mail` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=206 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `rss_data`
--

CREATE TABLE IF NOT EXISTS `rss_data` (
  `no` int(9) NOT NULL AUTO_INCREMENT,
  `id` int(3) NOT NULL,
  `rss_url` varchar(2000) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `cnv_keitai` int(1) NOT NULL,
  `view_cnt` int(3) NOT NULL,
  `category_cd` int(2) NOT NULL,
  `hidden_chk` int(1) NOT NULL,
  `touroku_date` varchar(8) NOT NULL,
  `touroku_time` varchar(6) NOT NULL,
  `haisin_flg` int(1) NOT NULL DEFAULT '0',
  `keyword` varchar(10000) NOT NULL,
  `ng_ad_flg` int(1) NOT NULL,
  `no_repert_flg` int(1) NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `wk_send_rss`
--

CREATE TABLE IF NOT EXISTS `wk_send_rss` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(3) NOT NULL,
  `rss_id` int(9) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `touroku_date` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8043 ;
