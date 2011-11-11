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
 * @author Sven Strittmatter <ich@weltraumschaf.de>
 */

namespace de\weltraumschaf\ebnf\visitor;

require_once 'visitor/Validator.php';
require_once 'ast/Syntax.php';
require_once 'ast/Rule.php';

use de\weltraumschaf\ebnf\ast\Syntax as Syntax;
use de\weltraumschaf\ebnf\ast\Rule as Rule;

/**
 * Tests for {Validator}.
 * 
 * @package ebnf
 * @subpackage tests
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase {
    
    public function testIsSyntaxDefined() {
        $syntax = new Syntax();
        $syntax->meta  = "foo";
        $syntax->title = "bar";
        
        $validator = new Validator();
        $this->assertFalse($validator->isSyntaxDefined());
        $syntax->accept($validator);
        $this->assertTrue($validator->isSyntaxDefined());
        
        try {
            $syntax->accept($validator);
            $this->fail("Exceptd exception not thrown!");
        } catch(ValidaorException $e) {
            $this->assertEquals(ValidaorException::SYNTAXT_REDECLARATION, $e->getCode());
            $this->assertEquals("You can specify a syntax only once!", $e->getMessage());
        }
    }
    
    public function testValidateSyntax() {
        $syntax = new Syntax();
        $syntax->meta  = "foo meta";
        $syntax->title = "bar title";
        
        $tester = new Validator();
        $syntax->accept($tester);
        $this->assertEquals(array(
            "syntax" => array(
                "meta"  => "foo meta",
                "title" => "bar title",
                "rule" => array()
            )
        ), $tester->getRepresentative());
    }
    
    /**
     * @expectedException        de\weltraumschaf\ebnf\visitor\ValidaorException
     * @expectedExceptionMessage You must specify a syntax at very first!
     * @expectedExceptionCode    2
     */
    public function testThrowExcpetionValidateRuleBeforeSyntax() {
        $rule = new Rule();
        $rule->accept(new Validator());
    }
    
    /**
     * @expectedException        de\weltraumschaf\ebnf\visitor\ValidaorException
     * @expectedExceptionMessage Rule with name 'foobar' already declared!
     * @expectedExceptionCode    3
     */
    public function testThrowExcpetionOnRuleRedecalartion() {
        $syntax  = new Syntax();
        $ruleFoo = new Rule();
        $ruleFoo->name = "foobar";
        $syntax->addChild($ruleFoo);
        $syntax->addChild($ruleFoo);
        $syntax->accept(new Validator);
    }
    
    public function testValidateRule() {      
        $syntax = new Syntax();
        $syntax->meta  = "foo meta";
        $syntax->title = "bar title";
        $ruleFoo = new Rule();
        $ruleFoo->name = "foo";
        $syntax->addChild($ruleFoo);
        $ruleBar = new Rule();
        $ruleBar->name = "bar";
        $syntax->addChild($ruleBar);
        $tester = new Validator();
        $syntax->accept($tester);
        $this->assertEquals(array(
            "syntax" => array(
                "meta"  => "foo meta",
                "title" => "bar title",
                "rule" => array(
                    "foo" => array(),
                    "bar" => array()
                )
            )
        ), $tester->getRepresentative());
    }
    
    public function testValidateRuleWithSubnodes() {
        $this->markTestIncomplete();
    }
    
    public function testAssertSyntax() {
        $this->markTestIncomplete();
    }
    
}