携帯RSSリーダー
=================  
いわゆるガラケーでRSSを読むためのWebアプリです。  
世界最小フレームワークちいたんを使用しています。  
特徴としては、  
RSSの登録、カテゴライズ、キーワード設定はPCで行う。  
(PCでのRSSリーダは沢山あるので、PC側でのリーダー機能はありません）  
指定したRSSに指定したキーワードが合致した場合、携帯へメールでお知らせ。  
ガラケー側では、RSSを携帯用に変換して表示。  
広告RSSの除外機能付  

動作環境は、  

PHP:５．２．６以上  

GDライブラリ:5.2.6以上  

MySQL:５．０．４５以上  

フレームワーク：ちいたん  

DBの文字コードはUTF-８で作成しています。  

設置方法  

１：/cron/cron.phpを開き、  
  
require_once( “<サーバの絶対パス>/rss/component/rss_fetch.inc”);  
define(‘MAGPIE_OUTPUT_ENCODING’, ‘utf-8′);  
define(‘MAGPIE_CACHE_DIR’,'<サーバの絶対パス>/rss/component/cache’);  
//ちいたんはあとに読み込ますこと  
require_once( “<サーバの絶対パス>/rss/config/config.php” );  
require_once( “<サーバの絶対パス>/cheetan/cheetan.php” );  
  
の箇所を絶対パスに書き直します。  

最後のほうにある  
$mailfrom=”From:info@exsamile.com”;  
の箇所を適切なメールアドレスに変更します。  
$wk_body2 .= "\n\n携帯RSSリーダ：http://exsample.com/rss/" . "\n";  
の箇所を適切なアドレスに変更します。  

２：cron.phpを非公開のエリアに設置してください。  
３：cron.phpをcronに登録してください。  
以下は私の設定です。  
  
0,30 * * * * /usr/bin/php /xxx/rss/cron.php  
  
４：config.phpのDB接続設定を変更します。  

５：付属のrss.sqlをMySQLに流してテーブルを作成  

６：動作チェック  

以上  

参考にしたサイト、使用したコンポーネントなど  
[ページネーションクラス](http://www4.osk.3web.ne.jp/~nisitatu/pagination.html "ページネーションクラス")  
[QRcode Perl CGI & PHP scripts ver. 0.50](http://www.swetake.com/qr/qr_cgi.html "QRcode Perl CGI & PHP scripts ver. 0.50")  
[MagpieRSS](http://sourceforge.net/projects/magpierss/files/ "MagpieRSS")  
[簡単ログインクラス](http://turi2.net/blog/709.html "簡単ログインクラス")  
[配列データを一気にクロスサイトスクリプティング対策する関数(php)](http://soft.fpso.jp/develop/php/entry_1891.html "配列データを一気にクロスサイトスクリプティング対策する関数(php)")  
[cookieが使えない場合のセッションについて](http://php.cheetan.net/community/forum/categories/2/topics/373/1.html "cookieが使えない場合のセッションについて")  
[PHP でセッション変数、Cookie を使用する際のセキュリティ対策について](http://www.asahi-net.or.jp/~wv7y-kmr/memo/php_security.html#PHP_Session "PHP でセッション変数、Cookie を使用する際のセキュリティ対策について")  
