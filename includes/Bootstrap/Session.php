<?php
namespace Redaxscript\Bootstrap;

use function session_regenerate_id;
use function session_start;
use function session_status;

/**
 * children class to boot the session
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Bootstrap
 * @author Henry Ruhs
 */

class Session extends BootstrapAbstract
{
	/**
	 * automate run
	 *
	 * @since 3.1.0
	 */

	public function autorun()
	{
		session_start();
		$this->_request->refreshSession();

		/* prevent hijacking */

		if (!$this->_request->getSession('regenerateId'))
		{
			$this->_request->setSession('regenerateId', session_regenerate_id());
		}
		$this->_registry->set('sessionStatus', session_status());
	}
}
