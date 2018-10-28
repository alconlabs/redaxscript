<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Module;

/**
 * children class to create the reset form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class ResetForm extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('resetFormStart');

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-title-content'
			])
			->text($this->_language->get('password_reset'));
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-js-validate rs-form-default rs-form-reset'
			],
			'button' =>
			[
				'submit' =>
				[
					'name' => self::class
				]
			]
		],
		[
			'captcha' => 1
		]);

		/* create the form */

		$formElement
			->legend()
			->append('<ul><li>')
			->captcha('task')
			->append('</li></ul>')
			->hidden(
			[
				'name' => 'password',
				'value' => $this->_registry->get('thirdParameter')
			])
			->hidden(
			[
				'name' => 'id',
				'value' => $this->_registry->get('thirdSubParameter')
			])
			->captcha('solution')
			->token()
			->submit();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('resetFormEnd');
		return $output;
	}
}
