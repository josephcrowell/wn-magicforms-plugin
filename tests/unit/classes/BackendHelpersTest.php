<?php
namespace JosephCrowell\MagicForms\Tests\Classes;

use Backend\Facades\Backend;
use Backend\Facades\BackendAuth;
use Backend\Models\User;
use JosephCrowell\MagicForms\Classes\BackendHelpers;
use System\Classes\PluginManager;
use System\Tests\Bootstrap\PluginTestCase;

class BackendHelpersTest extends PluginTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        PluginManager::instance()->bootAll(true);
    }

    /**
     * @testdox Get backend URL
     */
    public function testGetBackendUrl()
    {
        $this->_loginUser();
        $expect = Backend::url("josephcrowell/magicforms/records");
        $this->assertEquals($expect, BackendHelpers::getBackendURL([
            'josephcrowell.magicforms.access_records' => 'josephcrowell/magicforms/records',
            'josephcrowell.magicforms.access_exports' => 'josephcrowell/magicforms/exports',
        ], 'josephcrowell.magicforms.access_records'));
    }

    /**
     * @testdox Convert PHP array to HTML list
     */
    public function testArray2Ul()
    {
        $list = [
            'item1' => 'Item 1',
            'item2' => ['item21' => 'Item 2.1', 'item22' => 'Item 2.2', 'item23' => 'Item 2.3'],
            'item3' => 'Item 3',
        ];
        $expected = '<li>Item 1</li><li>item2<ul><li>Item 2.1</li><li>Item 2.2</li><li>Item 2.3</li></ul></li><li>Item 3</li>';
        $this->assertEquals($expected, BackendHelpers::array2ul($list));
    }

    /**
     * @testdox Anonymize IPv4 address
     */
    public function testAnonymizeIPv4()
    {
        $this->assertEquals('8.8.8.0', BackendHelpers::anonymizeIPv4('8.8.8.8'));
    }

    /**
     * @testdox Replace string containing curly braces
     */
    public function testReplaceTokenValid()
    {
        $this->assertEquals('includes 50 string', BackendHelpers::replaceToken('record.id', '50', 'includes {{ record.id }} string'));
    }

    /**
     * @testdox Replace string not containing curly braces
     */
    public function testReplaceTokenNoBraces()
    {
        $this->assertEquals('includes record.id string', BackendHelpers::replaceToken('record.id', '50', 'includes record.id string'));
    }

    /**
     * Login backend user
     *
     * @return void
     */
    private static function _loginUser()
    {
        $user = User::create([
            'email' => 'testuser@testcompany.com',
            'login' => 'testuser',
            'password' => 'superpassword',
            'password_confirmation' => 'superpassword',
        ]);
        BackendAuth::login($user);
    }
}
