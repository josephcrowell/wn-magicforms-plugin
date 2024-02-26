<?php
namespace JosephCrowell\MagicForms\Tests\Classes;

use Backend\Facades\Backend;
use Backend\Facades\BackendAuth;
use Backend\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JosephCrowell\MagicForms\Classes\BackendHelpers;
use System\Classes\PluginManager;
use System\Tests\Bootstrap\PluginTestCase;

class BackendHelpersTest extends PluginTestCase
{

    use RefreshDatabase;

    public function setUp()
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
        $bh     = new BackendHelpers();
        $this->assertEquals($expect, $bh->getBackendURL([
            'josephcrowell.magicforms.access_records' => 'josephcrowell/magicforms/records',
            'josephcrowell.magicforms.access_exports' => 'josephcrowell/magicforms/exports',
        ], 'josephcrowell.magicforms.access_records'));
    }

    /**
     * @testdox Convert PHP array to HTML list
     */
    public function testArray2Ul()
    {
        $list     = [
            'item1' => 'Item 1',
            'item2' => ['item21' => 'Item 2.1', 'item22' => 'Item 2.2', 'item23' => 'Item 2.3'],
            'item3' => 'Item 3',
        ];
        $expected = '<li>Item 1</li><li>item2<ul><li>Item 2.1</li><li>Item 2.2</li><li>Item 2.3</li></ul></li><li>Item 3</li>';
        $bh       = new BackendHelpers();
        $this->assertEquals($expected, $bh->array2ul($list));
    }

    /**
     * @testdox Anonymize IPv4 address
     */
    public function testAnonymizeIPv4()
    {
        $bh = new BackendHelpers();
        $this->assertEquals('8.8.8.0', $bh->anonymizeIPv4('8.8.8.8'));

    }

    /**
     * @testdox Replace string containing curly braces
     */
    public function testReplaceTokenValid()
    {
        $bh = new BackendHelpers();
        $this->assertEquals('includes 50 string', $bh->replaceToken('record.id', '50', 'includes {{ record.id }} string'));
    }

    /**
     * @testdox Replace string not containing curly braces
     */
    public function testReplaceTokenNoBraces()
    {
        $bh = new BackendHelpers();
        $this->assertEquals('includes record.id string', $bh->replaceToken('record.id', '50', 'includes record.id string'));
    }


    /**
     * Login backend user
     *
     * @return void
     */
    private static function _loginUser()
    {
        $user = User::create([
            'email'                 => 'testuser@testcompany.com',
            'login'                 => 'testuser',
            'password'              => 'superpassword',
            'password_confirmation' => 'superpassword',
        ]);
        BackendAuth::login($user);
    }

}
