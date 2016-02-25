<?php
use Redaxscript\Language;

/**
 * router
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Center
 * @author Henry Ruhs
 */

function router()
{
	Redaxscript\Hook::trigger('routerStart');
	if (Redaxscript\Registry::get('routerBreak') == 1)
	{
		return;
	}

	/* notification */

	$messenger = new \Redaxscript\Messenger();
	/* check token */

	if ($_POST && $_POST['token'] != TOKEN)
	{
		echo $messenger->setAction(Language::get('home'), ROOT)->error(Language::get('token_incorrect'), Language::get('error_occurred'));
		return;
	}

	/* call default post */

	$post_list = array(
		'Redaxscript\View\LoginForm' => 'login_post',
		'Redaxscript\View\RegisterForm' => 'registration_post',
		'Redaxscript\View\ResetForm' => 'password_reset_post',
		'Redaxscript\View\RecoverForm' => 'reminder_post',
		'comment',
		'search'
	);
	foreach ($post_list as $key => $value)
	{
		if ($_POST[$value . '_post'] && function_exists($value . '_post'))
		{
			call_user_func($value . '_post');
			return;
		}
		if ($_POST[$key] && function_exists($value))
		{
			call_user_func($value);
			return;
		}
	}

	/* general routing */

	switch (FIRST_PARAMETER)
	{
		case 'admin':
			if (LOGGED_IN == TOKEN)
			{
				admin_router();
			}
			else
			{
				echo $messenger->setAction(Language::get('login'), 'login')->error(Language::get('access_no'), Language::get('error_occurred'));
			}
			return;
		case 'login':
			switch (SECOND_PARAMETER)
			{
			case 'recover':
				if (s('recovery') == 1)
				{
					$recoverForm = new Redaxscript\View\RecoverForm();
					echo $recoverForm;
					return;
				}
			case 'reset':
				if (s('recovery') == 1 && THIRD_PARAMETER && THIRD_PARAMETER_SUB)
				{
					$resetForm = new Redaxscript\View\ResetForm();
					echo $resetForm;
					return;
				}

				/* show error */

				echo $messenger->setAction(Language::get('login'), 'login')->error(Language::get('access_no'), Language::get('error_occurred'));
				return;
			default:
				$loginForm = new Redaxscript\View\LoginForm();
				echo $loginForm;
				return;
			}
		case 'logout':
			if (LOGGED_IN == TOKEN)
			{
				logout();
				return;
			}

			/* show error */

			echo $messenger->setAction(Language::get('login'), 'login')->error(Language::get('access_no'), Language::get('error_occurred'));
			return;
		case 'register':
			if (s('registration'))
			{
				$registerForm = new Redaxscript\View\RegisterForm();
				echo $registerForm;
				return;
			}

			/* show error */

			echo $messenger->setAction(Language::get('home'), ROOT)->error(Language::get('access_no'), Language::get('error_occurred'));
			return;
		default:
			contents();
			return;
	}
	Redaxscript\Hook::trigger('routerEnd');
}
