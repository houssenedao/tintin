<?php

use Tintin\Tintin;
use Tintin\Loader\Filesystem;

class CompileCustomDirectiveTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Tintin
     */
    private $tintin;

    /**
     * @var Filesystem;
     */
    private $loader;

    /**
     * On setup
     */
    public function setUp()
    {
        $this->tintin = new Tintin;

        $this->loader = new Filesystem([
            'path' => __DIR__.'/view',
            'extension' => 'tintin.php',
            'cache' => __DIR__.'/cache'
        ]);
    }

    /**
     * @throws \Tintin\Exception\DirectiveNotAllowException
     */
    public function testHelloDirective()
    {
        $this->tintin->directive('hello', function (array $attributes = []) {
            return 'Hello ' . implode(" ", $attributes);
        });

        $r = $this->tintin->render('#hello("Tintin", "Bow")');

        $this->assertEquals($r, "Hello Tintin Bow");
    }

    /**
     * @throws \Tintin\Exception\DirectiveNotAllowException
     */
    public function testSimpleDirective()
    {
        $this->tintin->directive('now', function (array $attributes = []) {
            return time();
        });

        $r = $this->tintin->render('#now');

        $this->assertTrue(is_numeric($r));
    }

    /**
     * @throws \Tintin\Exception\DirectiveNotAllowException
     */
    public function testComplexDirective()
    {
        $this->tintin->directive('input', function (array $attributes = []) {
            $attribute = $attributes[0];

            return '<input type="'.$attribute['type'].'" name="'.$attribute['name'].'" value="'.$attribute['value'].'" />';
        });

        $r = $this->tintin->render('#input(["type" => "text", "value" => null, "name" => "name"])');

        $this->assertEquals($r, '<input type="text" name="name" value="" />');
    }

    /**
     * @throws \Tintin\Exception\DirectiveNotAllowException
     */
    public function testCompileCustomDirectiveDefineAsBrockenClause()
    {
        $tintin = new Tintin($this->loader);

        $tintin->directive('admin', function ($expression) {
            return '<?php if (true): ?>';
        }, true);

        $tintin->directive('endadmin', function ($expression) {
            return '<?php endif; ?>';
        }, true);

        $r = $tintin->render('custom', ['name' => 'Tintin access allowed']);

        $this->assertEquals(trim($r), 'Tintin access allowed');
    }
}
