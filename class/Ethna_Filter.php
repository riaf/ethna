<?php
// vim: foldmethod=marker
/**
 *	Ethna_Filter.php
 *
 *	@author		Masaki Fujimoto <fujimoto@php.net>
 *	@license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 *	@package	Ethna
 *	@version	$Id$
 */

// {{{ Ethna_Filter
/**
 *	�ե졼�����Υե��륿���쥯�饹
 *
 *	Mojavi�ο����Ǥ��ʤ��äѤ�ˡ����������¹����˳Ƽ������Ԥ����Ȥ�
 *	����ޤ���
 *
 *	@author		Masaki Fujimoto <fujimoto@php.net>
 *	@access		public
 *	@package	Ethna
 */
class Ethna_Filter
{
	/**#@+
	 *	@access	private
	 */

	/**
	 *	@var	object	Ethna_Controller	controller���֥�������
	 */
	var	$controller;

	/**
	 *	@var	object	Ethna_Controller	controller���֥�������($controller�ξ�ά��)
	 */
	var	$ctl;

	/**
	 *	@var	object	Ethna_Config		���ꥪ�֥�������
	 */
	var	$config;

	/**
	 *	@var	object	Ethna_Logger		�������֥�������
	 */
	var $logger;

	/**#@-*/


	/**
	 *	Ethna_Filter�Υ��󥹥ȥ饯��
	 *
	 *	@access	public
	 *	@param	object	Ethna_Controller	&$controller	����ȥ����饪�֥�������
	 */
	function Ethna_Filter(&$controller)
	{
		// ���֥������Ȥ�����
		$this->controller =& $controller;
		$this->ctl =& $this->controller;

		$this->config =& $controller->getConfig();
		$this->logger =& $this->controller->getLogger();
	}

	/**
	 *	�¹����ե��륿
	 *
	 *	@access	public
	 */
	function prefilter()
	{
	}

	/**
	 *	�¹Ը�ե��륿
	 *
	 *	@access	public
	 */
	function postfilter()
	{
	}
}
// }}}
?>