<?php

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @license http://www.gnu.org/licenses/ GNU General Public License
 * @author  Sven Strittmatter <ich@weltraumschaf.de>
 * @package tests
 */

namespace de\weltraumschaf\ebnf;

/**
 * @see Position
 */
require_once 'Renderer.php';
/**
 * @see Parser
 */
require_once 'Parser.php';
/**
 * @see Scanner
 */
require_once 'Scanner.php';

/**
 * Testcase for class {@link Renderer}.
 *
 * @package tests
 * @version @@version@@
 * @group   slow
 */
class RendererTest extends \PHPUnit_Framework_TestCase {
    private $fixtureDir;
    /**
     * @var TestDirHelper
     */
    private static $testDir;
    private static $isGdInstalled;

    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->fixtureDir = EBNF_TESTS_FIXTURS . DIRECTORY_SEPARATOR . "Renderer";
    }

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        self::$isGdInstalled = function_exists("imagedestroy");

        if (self::$isGdInstalled) {
            self::$testDir = new TestDirHelper();
            self::$testDir->create();
        }
    }

    public static function tearDownAfterClass() {
        if (self::$isGdInstalled) {
            self::$testDir->remove();
        }

        parent::tearDownAfterClass();
    }

    /**
     * @return DOMDocument
     */
    private function createAst() {
        $input    = file_get_contents($this->fixtureDir . DIRECTORY_SEPARATOR . "test_grammar.ebnf");
        $scanner  = new Scanner($input);
        $parser   = new Parser($scanner);
        return $parser->parse();
    }

    /**
     * @large
     */
    public function testRenderGif() {
        if ( ! self::$isGdInstalled) {
            $this->markTestSkipped("No GD lib installed!");
        }

        $fixture  = "{$this->fixtureDir}/test_grammar.gif";
        $fileName = self::$testDir->get() . "/out.gif";
        $renderer = new Renderer(Renderer::FORMAT_GIF, $fileName, $this->createAst());
        $renderer->save();
        $this->assertTrue(
            file_get_contents($fixture) === file_get_contents($fileName),
            "Failed rendering gif $fileName}!'",
            "Reference file {$fixture}"
        );
    }

    /**
     * @large
     */
    public function testRenderJpg() {
        if ( ! self::$isGdInstalled) {
            $this->markTestSkipped("No GD lib installed!");
        }

        $fixture = $this->fixtureDir . "/test_grammar";

        if (EBNF_TESTS_HOST_OS === EBNF_TESTS_HOST_OS_LINUX) {
            $fixture .= "_linux";
        }

        $fixture .= ".jpg";

        $fileName = self::$testDir->get() . "/out.jpg";
        $renderer = new Renderer(Renderer::FORMAT_JPG, $fileName, $this->createAst());
        $renderer->save();
        $this->assertTrue(
            file_get_contents($fixture) === file_get_contents($fileName),
            "Failed rendering jpg '{$fileName}!'",
            "Reference file {$fixture}"
        );
    }

    /**
     * @large
     */
    public function testRenderPng() {
        if ( ! self::$isGdInstalled) {
            $this->markTestSkipped("No GD lib installed!");
        }

        $fixture  = "{$this->fixtureDir}/test_grammar.png";
        $fileName = self::$testDir->get() . "/out.png";
        $renderer = new Renderer(Renderer::FORMAT_PNG, $fileName, $this->createAst());
        $renderer->save();
        $this->assertTrue(
            file_get_contents($fixture) === file_get_contents($fileName),
            "Failed rendering png '{$fileName}!'",
            "Reference file {$fixture}"
        );
    }

    /**
     * @large
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Unsupported format: 'foo'!
     */
    public function testThrowExceptionOnInvalidFormat() {
        $renderer = new Renderer("foo", self::$testDir->get() . "/out.foo", $this->createAst());
        $renderer->save();
    }
}
