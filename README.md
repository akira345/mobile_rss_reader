�g��RSS���[�_�[
=================  
������K���P�[��RSS��ǂނ��߂�Web�A�v���ł��B  
���E�ŏ��t���[�����[�N����������g�p���Ă��܂��B  
�����Ƃ��ẮA  
RSS�̓o�^�A�J�e�S���C�Y�A�L�[���[�h�ݒ��PC�ōs���B  
(PC�ł�RSS���[�_�͑�R����̂ŁAPC���ł̃��[�_�[�@�\�͂���܂���j  
�w�肵��RSS�Ɏw�肵���L�[���[�h�����v�����ꍇ�A�g�тփ��[���ł��m�点�B  
�K���P�[���ł́ARSS���g�їp�ɕϊ����ĕ\���B  
�L��RSS�̏��O�@�\�t  

������́A  

PHP:�T�D�Q�D�U�ȏ�  

GD���C�u����:5.2.6�ȏ�  

MySQL:�T�D�O�D�S�T�ȏ�  

�t���[�����[�N�F��������  

DB�̕����R�[�h��UTF-�W�ō쐬���Ă��܂��B  

�ݒu���@  

�P�F/cron/cron.php���J���A  
  
require_once( �g<�T�[�o�̐�΃p�X>/rss/component/rss_fetch.inc�h);  
define(�eMAGPIE_OUTPUT_ENCODING�f, �eutf-8��);  
define(�eMAGPIE_CACHE_DIR�f,'<�T�[�o�̐�΃p�X>/rss/component/cache�f);  
//��������͂��Ƃɓǂݍ��܂�����  
require_once( �g<�T�[�o�̐�΃p�X>/rss/config/config.php�h );  
require_once( �g<�T�[�o�̐�΃p�X>/cheetan/cheetan.php�h );  
  
�̉ӏ����΃p�X�ɏ��������܂��B  

�Ō�̂ق��ɂ���  
$mailfrom=�hFrom:info@exsamile.com�h;  
�̉ӏ���K�؂ȃ��[���A�h���X�ɕύX���܂��B  
$wk_body2 .= "\n\n�g��RSS���[�_�Fhttp://exsample.com/rss/" . "\n";  
�̉ӏ���K�؂ȃA�h���X�ɕύX���܂��B  

�Q�Fcron.php�����J�̃G���A�ɐݒu���Ă��������B  
�R�Fcron.php��cron�ɓo�^���Ă��������B  
�ȉ��͎��̐ݒ�ł��B  
  
0,30 * * * * /usr/bin/php /xxx/rss/cron.php  
  
�S�Fconfig.php��DB�ڑ��ݒ��ύX���܂��B  

�T�F�t����rss.sql��MySQL�ɗ����ăe�[�u�����쐬  

�U�F����`�F�b�N  

�ȏ�  

�Q�l�ɂ����T�C�g�A�g�p�����R���|�[�l���g�Ȃ�  
[�y�[�W�l�[�V�����N���X](http://www4.osk.3web.ne.jp/~nisitatu/pagination.html "�y�[�W�l�[�V�����N���X")
[QRcode Perl CGI & PHP scripts ver. 0.50](http://www.swetake.com/qr/qr_cgi.html "QRcode Perl CGI & PHP scripts ver. 0.50")
[MagpieRSS](http://sourceforge.net/projects/magpierss/files/ "MagpieRSS")
[�ȒP���O�C���N���X](http://turi2.net/blog/709.html "�ȒP���O�C���N���X")
[�z��f�[�^����C�ɃN���X�T�C�g�X�N���v�e�B���O�΍􂷂�֐�(php)](http://soft.fpso.jp/develop/php/entry_1891.html "�z��f�[�^����C�ɃN���X�T�C�g�X�N���v�e�B���O�΍􂷂�֐�(php)")
[cookie���g���Ȃ��ꍇ�̃Z�b�V�����ɂ���](http://php.cheetan.net/community/forum/categories/2/topics/373/1.html "cookie���g���Ȃ��ꍇ�̃Z�b�V�����ɂ���")
[PHP �ŃZ�b�V�����ϐ��ACookie ���g�p����ۂ̃Z�L�����e�B�΍�ɂ���](http://www.asahi-net.or.jp/~wv7y-kmr/memo/php_security.html#PHP_Session "PHP �ŃZ�b�V�����ϐ��ACookie ���g�p����ۂ̃Z�L�����e�B�΍�ɂ���")
