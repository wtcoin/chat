<?php
/**
 * ownCloud - News
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 * https://raw.githubusercontent.com/owncloud/news/master/utility/controllertestutility.php
 * @author Alessandro Cosentino <cosenal@gmail.com>
 * @author Bernhard Posselt <dev@bernhard-posselt.com>
 * @copyright Alessandro Cosentino 2012
 * @copyright Bernhard Posselt 2012, 2014
 */

namespace OCA\Chat\Utility;

use OCP\IRequest;
use OCP\AppFramework\Http\Response;


/**
 * Simple utility class for testing controllers
 */
abstract class ControllerTestUtility extends \PHPUnit_Framework_TestCase {


	/**
	 * Checks if a controllermethod has the expected annotations
	 * @param Controller/string $controller name or instance of the controller
	 * @param array $expected an array containing the expected annotations
	 * @param array $valid if you define your own annotations, pass them here
	 */
	protected function assertAnnotations($controller, $method, array $expected,
										 array $valid=array()){
		$standard = array(
			'PublicPage',
			'NoAdminRequired',
			'NoCSRFRequired',
			'API'
		);

		$possible = array_merge($standard, $valid);

		// check if expected annotations are valid
		foreach($expected as $annotation){
			$this->assertTrue(in_array($annotation, $possible));
		}

		$reader = new MethodAnnotationReader($controller, $method);
		foreach($expected as $annotation){
			$this->assertTrue($reader->hasAnnotation($annotation));
		}
	}


	/**
	 * Shortcut for testing expected headers of a response
	 * @param array $expected an array with the expected headers
	 * @param Response $response the response which we want to test for headers
	 */
	protected function assertHeaders(array $expected=array(), Response $response){
		$headers = $response->getHeaders();
		foreach($expected as $header){
			$this->assertTrue(in_array($header, $headers));
		}
	}


	/**
	 * Instead of using positional parameters this function instantiates
	 * a request by using a hashmap so its easier to only set specific params
	 * @param array $params a hashmap with the parameters for request
	 * @return Request a request instance
	 */
	protected function getRequest(array $params=array()) {
		$mock = $this->getMockBuilder('\OCP\IRequest')
			->getMock();

		$merged = array();

		foreach ($params as $key => $value) {
			$merged = array_merge($value, $merged);
		}

		$mock->expects($this->any())
			->method('getParam')
			->will($this->returnCallback(function($index, $default) use ($merged) {
				if (array_key_exists($index, $merged)) {
					return $merged[$index];
				} else {
					return $default;
				}
			}));

		// attribute access
		if(array_key_exists('server', $params)) {
			$mock->server = $params['server'];
		}

		return $mock;
	}

}
