<?php

require_once 'img.class.php';

$cwd = getcwd();

/**
 * Test class for Img.
 * Generated by PHPUnit on 2012-02-21 at 15:50:20.
 */
class ImgTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Img
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->img = new Img;
    }

    public static function setUpBeforeClass()
    {
        # create the www folder
        if (!file_exists(TESTS_PATH . '/www')) {
            mkdir();
        }

        # create a BMP file
        if (!file_exists(TESTS_PATH . '/www/bmp_image.bmp')) {
            $bmp_img = imagecreatetruecolor(100, 100);
            imagewbmp($bmp_img , TESTS_PATH . '/www/bmp_image.bmp');
            imagedestroy($bmp_img );
        }

        # create a GIF file
        if (!file_exists(TESTS_PATH . '/www/gif_image.gif')) {
            $gif_img = imagecreatetruecolor(100, 100);
            imagegif($gif_img , TESTS_PATH . '/www/gif_image.gif');
            imagedestroy($gif_img );
        }

        # create a JPG file
        if (!file_exists(TESTS_PATH . '/www/jpg_image.jpg')) {
            $jpg_img = imagecreatetruecolor(100, 100);
            imagejpeg($jpg_img , TESTS_PATH . '/www/jpg_image.jpg');
            imagedestroy($jpg_img );
        }

        # create a PNG file
        if (!file_exists(TESTS_PATH . '/www/png_image.png')) {
            $png_img = imagecreatetruecolor(100, 100);
            imagepng($png_img , TESTS_PATH . '/www/png_image.png');
            imagedestroy($png_img );
        }
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    public static function tearDownAfterClass()
    {
        # delete the created files
        unlink(TESTS_PATH . '/www/bmp_image.bmp');
        unlink(TESTS_PATH . '/www/gif_image.gif');
        unlink(TESTS_PATH . '/www/jpg_image.jpg');
        unlink(TESTS_PATH . '/www/png_image.png');
    }

    public function testLoad()
    {
        $this->assertFalse($this->img->load(TESTS_PATH . '\www\does_not_exist.jpg'));
        $this->assertEquals('Image file could not be found: ' . TESTS_PATH . '\www\does_not_exist.jpg', $this->img->error);
        
        $this->img = new Img(TESTS_PATH . '/www/bmp_image.bmp');
        $this->assertTrue(is_object($this->img));
        $this->assertEquals('For the moment, only JPG, GIF and PNG image files are supported [bmp]', $this->img->error);
        
        $this->img = new Img(TESTS_PATH . '/www/png_image.png');
        $this->assertEquals('', $this->img->error);
        
        $this->img = new Img(TESTS_PATH . '/www/jpg_image.jpg');
        $this->assertEquals('', $this->img->error);
        
        $this->img = new Img(TESTS_PATH . '/www/gif_image.gif');
        $this->assertEquals('', $this->img->error);
    }

    public function testSaveTo()
    {
        $this->img = new Img('false');
        $this->assertfalse($this->img->save_to('temp'));
        $this->assertEquals('No image was loaded', $this->img->error);
        
        $this->img = new Img(TESTS_PATH . '/www/png_image.png');
        $this->img->save_to(TESTS_PATH . '/temp');
        $this->assertFileExists(TESTS_PATH . '/temp/png_image.png');
        @unlink(TESTS_PATH . '/temp/png_image.png');
        
        $this->img = new Img(TESTS_PATH . '/www/png_image.png');
        $this->img->save_to('');
        $this->assertFileExists('png_image.png');
        @unlink('png_image.png');
        
        $this->img = new Img(TESTS_PATH . '/www/jpg_image.jpg');
        $this->img->save_to(TESTS_PATH . '/temp');
        $this->assertFileExists(TESTS_PATH . '/temp/jpg_image.jpg');
        @unlink(TESTS_PATH . '/temp/jpg_image.jpg');
        
        $this->img = new Img(TESTS_PATH . '/www/gif_image.gif');
        $this->img->save_to(TESTS_PATH . '/temp');
        $this->assertFileExists(TESTS_PATH . '/temp/gif_image.gif');
        @unlink(TESTS_PATH . '/temp/gif_image.gif');
    }
    
    public function testSetDestinationName()
    {
        $this->assertFalse($this->img->set_destination_name(1212));
        
        $this->img = new Img(TESTS_PATH . '/www/png_image.png');
        $this->img->set_destination_name('temp_png_image');
        $this->img->save_to(TESTS_PATH . '/temp');
        $this->assertFileExists(TESTS_PATH . '/temp/temp_png_image.png');
        @unlink(TESTS_PATH . '/temp/temp_png_image.png');
        
        $this->assertFalse($this->img->set_destination_name(1212));
    }

    public function testGetDestinationName()
    {
        $this->img = new Img(TESTS_PATH . '/www/png_image.png');
        $this->assertEquals(getcwd() . '/png_image.png', $this->img->get_destination_name());
    }

    public function testSetThumbSize()
    {
        $this->img = new Img('---');
        $this->assertFalse($this->img->set_thumb_size(120));
        
        $this->img = new Img(TESTS_PATH . '/www/png_image.png');
        $result1 = $this->img->set_thumb_size(400);
        $result2 = $this->img->set_thumb_size(400, 150);
        
        $this->assertTrue(is_object($result1));
        $this->assertTrue(is_object($result2));
        
        $result3 = $this->img->set_thumb_size('non-numeric value');
        $this->assertFalse($result3);
        $this->assertEquals('Specified thumbnail width is invalid', $this->img->error);
    }
    
    public function testGetThumbSize()
    {
        $this->assertTrue(is_array($sz = $this->img->get_thumb_size()));
        $this->assertTrue(is_numeric($sz[1]));
    }
    
    public function testResetThumbSize()
    {
        $this->img->reset_thumb_size();
    }

    public function testSaveThumbnailTo()
    {
        $this->img->load(TESTS_PATH . '/www/jpg_image.jpg');
        $this->img->set_destination_name('thumb_1');
        
        $this->img->set_thumb_size(90, 150);
        $this->img->save_thumb_to(TESTS_PATH . '/temp');
        
        $this->assertFileExists(TESTS_PATH . '/temp/thumb_1.jpg');
        $imgsize = getimagesize(TESTS_PATH . '/temp/thumb_1.jpg');
        file_put_contents(TESTS_PATH . '/log.txt', print_r($imgsize, true));
        $this->assertEquals(90, $imgsize[0]);
        $this->assertEquals(150, $imgsize[1]);

        $this->img->set_thumb_size(100, 400);
        $this->img->save_thumb_to(TESTS_PATH . '/temp');
        $imgsize = getimagesize(TESTS_PATH . '/temp/thumb_1.jpg');
        $this->assertEquals(100, $imgsize[0]);
        $this->assertEquals(400, $imgsize[1]);
        
        $this->img->set_thumb_size(400, 100);
        $this->img->save_thumb_to(TESTS_PATH . '/temp');
        $imgsize = getimagesize(TESTS_PATH . '/temp/thumb_1.jpg');
        $this->assertEquals(400, $imgsize[0]);
        $this->assertEquals(100, $imgsize[1]);
        
        $this->img->set_thumb_size(150, 100);
        $this->img->save_thumb_to(TESTS_PATH . '/temp');
        $imgsize = getimagesize(TESTS_PATH . '/temp/thumb_1.jpg');
        $this->assertEquals(150, $imgsize[0]);
        $this->assertEquals(100, $imgsize[1]);

        unlink(TESTS_PATH . '/temp/thumb_1.jpg');
    }
}
