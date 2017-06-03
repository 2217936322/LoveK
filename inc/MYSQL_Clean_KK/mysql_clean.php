<?php
function load_mysql_clean_lang(){
	$currentLocale = get_locale();
	if(!empty($currentLocale)){
		$moFile = dirname(__FILE__) . "/lang/KK-" . $currentLocale . ".mo";
		if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('MYSQL_Clean_KK',$moFile);
	}
}
add_filter('init','load_mysql_clean_lang');

if(is_admin()){require_once('mysql_clean_admin.php');}
function kk_admin_bar_render() {
	global $wp_admin_bar;
	$wp_admin_bar->add_menu( array(
				'parent' => '',
				'id' => '数据库优化',
				'title' => __( '数据库优化', 'MYSQL_Clean_KK' ),
				'meta' => array( 'title' => __( '冗余数据清理', 'MYSQL_Clean_KK' ) ),
				'href' => wp_nonce_url( admin_url( 'options-general.php?page=mysql_clean_admin.php' ), '数据库优化' )
				) );
}
add_action( 'wp_before_admin_bar_render', 'kk_admin_bar_render' );
?>