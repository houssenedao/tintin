<?php

use Tintin\Compiler;

class CompilerEchoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Compiler
     */
    private $compiler;

    public function setUp()
    {
        $this->compiler = new Compiler;
    }

    /**
     * Reflection maker
     *
     * @param string $method
     */
    public function makeReflectionFor($method)
    {
        $reflection = new \ReflectionMethod('\Tintin\Compiler', $method);
    
        $reflection->setAccessible(true);

        return $reflection;
    }

    /**
     * Test echo
     */
    public function testCompileEcho()
    {
        $compileEcho = $this->makeReflectionFor('compileEcho');
        
        $render = $compileEcho->invoke(new Compiler, '{{ "hello world" }}');

        $this->assertEquals($render, '<?php echo htmlspecialchars("hello world", ENT_QUOTES); ?>');
    }

    /**
     * Test row echo
     */
    public function testCompileRawEcho()
    {
        $compileRawEcho = $this->makeReflectionFor('compileRawEcho');
        
        $render = $compileRawEcho->invoke(new Compiler, '{{{ "hello world" }}}');

        $this->assertEquals($render, '<?php echo "hello world"; ?>');
    }
}
