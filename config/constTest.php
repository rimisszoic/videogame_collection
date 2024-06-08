<?php

use PHPUnit\Framework\TestCase;

class ConstantsTest extends TestCase
{
    public function testBaseUrlConstant()
    {
        $this->assertEquals('/videogame_collection/index.php', BASE_URL);
    }

    public function testModelsConstant()
    {
        $this->assertEquals('model/', MODELS);
    }

    public function testViewsConstant()
    {
        $this->assertEquals('resources/views/', VIEWS);
    }

    public function testControllersConstant()
    {
        $this->assertEquals('controller/', CONTROLLERS);
    }

    public function testCssConstant()
    {
        $this->assertEquals('resources/css/', CSS);
    }

    public function testJsConstant()
    {
        $this->assertEquals('resources/js/', JS);
    }

    public function testImgConstant()
    {
        $this->assertEquals('resources/images/', IMG);
    }

    public function testBootstrapConstant()
    {
        $this->assertEquals('resources/bootstrap/', BOOTSTRAP);
    }

    public function testConfigConstant()
    {
        $this->assertEquals('config/', CONFIG);
    }

    public function testRootConstant()
    {
        $this->assertEquals($_SERVER['DOCUMENT_ROOT'] . '/videogame_collection/', ROOT);
    }

    public function testLogsConstant()
    {
        $this->assertEquals(ROOT . 'logs/', LOGS);
    }

    public function testResourcesConstant()
    {
        $this->assertEquals(ROOT . 'resources/', RESOURCES);
    }

    public function testRouterConstant()
    {
        $this->assertEquals('/videogame_collection/router.php', ROUTER);
    }

    public function testDbHostConstant()
    {
        $this->assertEquals('localhost', DB_HOST);
    }

    public function testDbUserConstant()
    {
        $this->assertEquals('gam3Coll@bUs3r', DB_USER);
    }

    public function testDbPwdConstant()
    {
        $this->assertEquals('J4nR#9!pQz_23Ld', DB_PWD);
    }

    public function testDbNameConstant()
    {
        $this->assertEquals('videogame_collection', DBNAME);
    }

    public function testAdminEmailConstant()
    {
        $this->assertEquals('rimiss@rimisszoic.live', ADMIN_EMAIL);
    }

    public function testEmailHostConstant()
    {
        $this->assertEquals('rimisszoic.live', EMAIL_HOST);
    }

    public function testEmailUserConstant()
    {
        $this->assertEquals('noreply@noelperez.rimisszoic.live', EMAIL_USER);
    }

    public function testEmailPwdConstant()
    {
        $this->assertEquals('?2&h;@1o$ym??W!?+G5BxxeB', EMAIL_PWD);
    }
}