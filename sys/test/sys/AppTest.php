<?php

require_once dirname(__FILE__) . '/../../config.php';
require_once dirname(__FILE__) . '/../../functions.php';
require_once dirname(__FILE__) . '/../../app.class.php';

/**
 * Test class for App.
 * Generated by PHPUnit on 2012-01-11 at 12:46:29.
 */
class AppTest extends PHPUnit_Framework_TestCase {

    /**
     * @var App
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->app = new App;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        $this->app = null;
    }

    /**
     * @todo Implement testRun().
     */
    public function testRun() {
        //$this->markTestIncomplete("The App::run() method is untestable for the moment.");
        
        $_GET['url'] = 'controller/action/page:1/2/3';
        
        /*
         * App::run() requires class Pew, which in turn requires sys/config, 
         * which in turn requires app/config, which in turn...
         */
        //$this->app->run();
    }

    public function testGet_segments_GET()
    {
        $_SERVER['REQUEST_METHOD'] = 'get';
        
        $_GET = $data = array(
            'dog_name' => 'Truffles',
            'street_address' => 'Main st.',
            'owner_id' => '1'
        );
        
        $segments = $this->app->get_segments('');
        $this->assertEquals(DEFAULT_CONTROLLER, $segments['controller']);
        $this->assertEquals(DEFAULT_ACTION, $segments['action']);
        
        
        $segments = $this->app->get_segments('kittens/feed/page:1/2/catnip');
        $this->assertEquals('kittens/feed/page:1/2/catnip', $segments['uri']);
        $this->assertEquals('kittens', $segments['controller']);
        $this->assertEquals('feed', $segments['action']);
        $this->assertEquals('1', $segments['named']['page']);
        $this->assertEquals('page:1', $segments['segments'][2]);
        $this->assertEquals('2', $segments['passed'][1]);
        $this->assertEquals('catnip', $segments['passed'][2]);
        $this->assertEquals(2, $segments['id']);
    }
    
    public function testGet_segments_POST()
    {
        $_SERVER['REQUEST_METHOD'] = 'post';
        
        $_POST = $data = array(
            'dog_name' => 'Truffles',
            'street_address' => 'Main st.',
            'owner_id' => '1'
        );
        
        $segments = $this->app->get_segments('goggies/walk/1');
        $this->assertEquals($data, $segments['form']);
        $this->assertEquals($data['street_address'], $segments['form']['street_address']);
    }
    
    public function testCfg()
    {
        $this->assertEquals('config is set!', cfg('is_config_set?', 'config is set!'));
        $this->assertEquals('config is set!', cfg('is_config_set?'));
        $this->assertEquals(array('is_config_set?' => 'config is set!'), cfg(true));
        $this->assertNull(cfg('this does not exist'));
        $this->assertNull(cfg(false));
        $this->assertNull(cfg(12.0));
    }

    public function testPr()
    {
        $array = array(1, 2, 3);
        $integer = '1234';
        $string = 'output string';
        
        ob_start();
        pr($array, $title = null);
        $result = ob_get_contents();
        ob_end_clean();
        
        $this->assertEquals("Array\n(\n    [0] => 1\n    [1] => 2\n    [2] => 3\n)\n", $result);
        
        ob_start();
        pr(12, $title = 'Twelve');
        $result = ob_get_contents();
        ob_end_clean();
        
        $this->assertEquals("Twelve: 12", $result);
    }

    public function testPew_exit()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    public function testGet_execution_time()
    {
        $this->assertEquals(0, get_execution_time());
        $this->assertNotEquals(0, get_execution_time());
        $this->assertNotEquals(0, get_execution_time(true));
    }

    public function testSanitize()
    {
        $str = '; DELETE FROM \"users\"';
        $this->assertEquals('; DELETE FROM \\\\\"users\\\\\\"', sanitize($str));
        
        $str = '12';
        $this->assertEquals('12', sanitize($str));
        
        $str = 12;
        $this->assertEquals(12, sanitize($str));
    }

    public function testClean_array_data()
    {
        $array_data = array('', array('\\"; DELETE * from users'), '000234');
        $result = array('', array('"; DELETE * from users'), '000234');
        
        $this->assertEquals($result, clean_array_data($array_data));
    }

    public function testPew_clean_string()
    {
        $str = "\"; DELETE * FROM 'users'";
        
        $this->assertEquals('\&quot;; DELETE * FROM \&#039;users\&#039;', pew_clean_string($str));
    }

    public function testDeref()
    {
        function returns_array()
        {
            return array(1, 2, 3, 4, 5, 6);
        }
        
        $this->assertEquals(3, deref(returns_array(), 2));
        $this->assertNull(deref(returns_array(), 10));
        
        // The following test case must throw a E_USER_WARNING error
        ////////////////////////////////////////////////////////////
        ob_start();
        $return = deref(returns_array(), 'as', true);
        $result = ob_get_contents();
        ob_end_clean();
        
        $this->assertNull($return);
        $this->assertEquals("", $result);
    }

    public function testArray_reap()
    {
        $array = array(
            array(1, 2, 3, 4, 5),
            array('string1', 'string2', 'string3', 'str4' => 'string4'),
            array('uno' => 'one', 'dos' => 'two', 'tres' => 'three'),
            'PEW' => true
        );
        
        $result1 = array(1 => array('str4' => 'string4'), 2 => array('uno' => 'one', 'dos' => 'two', 'tres' => 'three'));
        $result2 = array(2 => array('uno' => 'one'));
        $result3 = array(array(1, 2, 3, 4, 5), array('string1', 'string2', 'string3'));
        $result4 = array('PEW' => true);
        $result5 = array(1 => array(2 => 'string3'));
        $this->assertEquals($result1, array_reap($array, '#:$'));
        $this->assertEquals($result2, array_reap($array, '#:uno'));
        $this->assertEquals($result3, array_reap($array, '#:#'));
        $this->assertEquals($result4, array_reap($array, '$'));
        $this->assertEquals($result5, array_reap($array, '1:2'));
        $this->assertNull(array_reap($array, true));
        $this->assertEquals(array(), array_reap((object) $array, '1:2'));
        
        $obj = new stdClass();
        $obj->prop = 1;
        
        $array2 = array(
            'obj' => $obj
        );
        
        $result6 = array('obj' => $obj);
        $this->assertEquals($result6, array_reap($array2, '$'), "Objects as array values are not converted");
    }

    public function testArray_flatten()
    {
        $array = array(array(1, 2, 3), 4, array ('five' => 5, 'six' => 6));
        $this->assertEquals(array(1, 2, 3, 4, 5, 6), array_flatten($array));
    }

    public function testArray_to_xml()
    {
        $array = array(
            'one' => array('test'),
            1 => array(1, 2, 3)
        );
        $xml = 'root';
        
        $this->assertEquals('', array_to_xml($array, $xml));
    }

    public function testFile_name_to_class_name()
    {
        $this->assertEquals('PewClassName', file_name_to_class_name('pew_class_name'));
        $this->assertEquals('PewclassName', file_name_to_class_name('pewclass_name'));
        $this->assertEquals('PEWClassName', file_name_to_class_name('p_e_w_class_name'));
        $this->assertEquals('Pewclassname', file_name_to_class_name('pewclassname'));
        $this->assertEquals('PewClassName', file_name_to_class_name('pew_class_name'));
        $this->assertEquals('Pewclassname', file_name_to_class_name('pewclassname'));
    }

    public function testClass_name_to_file_name()
    {
        $this->assertEquals('pew_class_name', class_name_to_file_name('PewClassName'));
        $this->assertEquals('pewclass_name', class_name_to_file_name('PewclassName'));
        $this->assertEquals('p_e_w_class_name', class_name_to_file_name('PEWClassName'));
        $this->assertEquals('pewclassname', class_name_to_file_name('Pewclassname'));
        $this->assertEquals('pew_class_name', class_name_to_file_name('pewClassName'));
        $this->assertEquals('pewclassname', class_name_to_file_name('pewclassname'));
    }

    public function testRedirect()
    {
        $this->markTestSkipped('The http redirecion is tested at functional stage.');
    }

    public function testCheck_dirs()
    {
        if (is_dir('testFunc_check_dir')) {
            rmdir('testFunc_check_dir');
        }
        $this->assertFalse(is_dir('testFunc_check_dir'));
        $this->assertTrue(check_dirs('testFunc_check_dir'));
        $this->assertTrue(is_dir('testFunc_check_dir'));
        
        $this->assertFalse(check_dirs(''));
    }

    public function testSlugify()
    {
        $str = 'This is a slug';
        $this->assertEquals('this-is-a-slug', slugify($str));
        
        $str = 'This has strange characters: áprÑi\n.´t-çth$i#s';
        $this->assertEquals('this-has-strange-characters-print-this', slugify($str));
        
        $str = 'This is a slug';
        $this->assertEquals('this-is-a-slug', slugify($str));
        
        $str = 'This is a slug';
        $this->assertEquals('this-is-a-slug', slugify($str));
    }

    public function testTo_underscores()
    {
        $this->assertEquals('______', to_underscores('- _-- '));
        $this->assertEquals('My_Class_Name', to_underscores('My Class Name'));
        $this->assertEquals('my_class_name', to_underscores('my class name'));
        $this->assertEquals('My_Class\Name', to_underscores('My-Class\\Name'));
    }

    public function testRoot()
    {
        $this->assertEquals(getcwd() . DIRECTORY_SEPARATOR, root('', false));
        $this->assertEquals(getcwd() . DIRECTORY_SEPARATOR . 'subdir', root('subdir', false));
    }

    public function testUrl()
    {
        if (defined('STDIN')) {
            $this->markTestSkipped();
        } else {
            $this->assertEquals(basename(__DIR__), url('', false));
            $this->assertEquals(basename(__DIR__) . '/example2', url('example2', false));
        }
    }

    public function testWww()
    {
        if (defined('STDIN')) {
            $this->markTestSkipped();
        } else {
            $this->assertEquals(basename(__DIR__) . '/www', url('', false));
            $this->assertEquals(basename(__DIR__) . 'www//example2', url('example2', false));
        }
    }

    public function testPrint_config()
    {
        ob_start();
        print_config();
        $result = ob_get_contents();
        ob_end_clean();
        
        $this->assertTrue(is_string($result));
        
        $lines = explode(PHP_EOL, trim($result));
        $this->assertEquals(4, count($lines));
    }
    
    public function testUser()
    {
        if (!USESESSION || !USEAUTH or defined(STDIN)) {
            $this->markTestIncomplete();
        } else {
            $user = user();
            $this->assertFalse($user);
        }
    }
}
