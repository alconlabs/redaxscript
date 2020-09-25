<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Controller;
use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CommentTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 *
 * @covers Redaxscript\Controller\Comment
 * @covers Redaxscript\Controller\ControllerAbstract
 *
 * @requires OS Linux
 */

class CommentTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one'

			])
			->save();
		$setting = $this->settingFactory();
		$setting->set('captcha', 1);
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param array $settingArray
	 * @param string $method
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testProcess(array $postArray = [], array $settingArray = [], string $method = null, string $expect = null) : void
	{
		/* setup */

		$this->_request->set('post', $postArray);
		$setting = $this->settingFactory();
		$setting->set('notification', $settingArray['notification']);
		$setting->set('moderation', $settingArray['moderation']);
		if ($method)
		{
			$commentController = $this
				->getMockBuilder('Redaxscript\Controller\Comment')
				->setConstructorArgs(
				[
					$this->_registry,
					$this->_request,
					$this->_language,
					$this->_config
				])
				->setMethods(
				[
					$method
				])
				->getMock();

			/* override */

			$commentController
				->expects($this->any())
				->method($method);
		}
		else
		{
			$commentController = new Controller\Comment($this->_registry, $this->_request, $this->_language, $this->_config);
		}

		/* actual */

		$actual = $commentController->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
