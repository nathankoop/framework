<?php

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\View\Compilers\BladeCompiler;

class BladeLangTest extends TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testStatementThatContainsNonConsecutiveParanthesisAreCompiled()
    {
        $compiler = new BladeCompiler($this->getFiles(), __DIR__);
        $string = "Foo @lang(function_call('foo(blah)')) bar";
        $expected = "Foo <?php echo app('translator')->get(function_call('foo(blah)')); ?> bar";
        $this->assertEquals($expected, $compiler->compileString($string));
    }

    public function testLanguageAndChoicesAreCompiled()
    {
        $compiler = new BladeCompiler($this->getFiles(), __DIR__);
        $this->assertEquals('<?php echo app(\'translator\')->get(\'foo\'); ?>', $compiler->compileString("@lang('foo')"));
        $this->assertEquals('<?php echo app(\'translator\')->choice(\'foo\', 1); ?>', $compiler->compileString("@choice('foo', 1)"));
    }

    protected function getFiles()
    {
        return m::mock('Illuminate\Filesystem\Filesystem');
    }
}
