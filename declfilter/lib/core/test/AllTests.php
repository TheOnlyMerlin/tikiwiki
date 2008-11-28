<?php
ini_set( 'display_errors', 'on' );
error_reporting( E_ALL );

ini_set( 'include_path', ini_get('include_path') . ':.:../lib' );

function __autoload( $name ) {
	$path = str_replace( '_', '/', $name );
	require_once( $path . '.php' );
}

class AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('CoreSuite');

        $suite->addTest(JitFilter_AllTests::suite());

        return $suite;
    }
}

?>
