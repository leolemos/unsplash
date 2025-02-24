<?php
/**
 * @copyright Copyright (c) 2018, Felix Nüsse
 *
 * @author Felix Nüsse <felix.nuesse@t-online.de>
 *
 * @license GPL-v3.0
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Unsplash\ProviderHandler;

use OCA\Unsplash\ProviderHandler\Provider;
use OCA\Unsplash\Provider\NextcloudImage;
use OCA\Unsplash\Provider\Unsplash;
use OCA\Unsplash\Provider\UnsplashHD;
use OCA\Unsplash\Provider\WallhavenCC;
use OCA\Unsplash\Provider\WikimediaCommons;
use OCP\IConfig;

class ProviderDefinitions{

	/**
	 * @var SettingsService
	 */
	protected $settings;

	/**
	 * @var IConfig
	 */
	protected $config;

	/**
	 * @var string
	 */
	protected $appName;

	/**
	 * @var definitions This variable contains all available provider
	 */
	protected $definitions = [];


	/**
	 * ProviderDefinitions constructor.
	 *
	 * @param SettingsService $settings
	 */
	function __construct($appName, IConfig $config) {

		$this->appName = $appName;
		$this->config = $config;

		$tmp=[];
		//add all provider to this array. The logic takes care of the rest.
		$tmp[] = new Unsplash($this->appName, $this->config, "Unsplash");
		$tmp[] = new NextcloudImage($this->appName, $this->config, "Nextcloud Image");
		$tmp[] = new WikimediaCommons($this->appName, $this->config, "WikimediaCommons");
		$tmp[] = new WallhavenCC($this->appName, $this->config, "WallhavenCC");

		foreach ($tmp as &$value) {
			//$this->definitions = array_merge($this->definitions, array($value->getName()=>$value->getName()));
			$this->definitions[$value->getName()] = $value;
		}
	}

	/**
	 * This returns the selected Provider
	 *
	 * @return Name of the Provider
	 */
	function getProviderByName($name): Provider {

        $provider = $this->definitions[$name];
        if($provider == null) {
            return new Unsplash($this->appName, $this->config, "Unsplash");
        }
		return $this->definitions[$name];
	}

	/**
	 * This returns all defined Provider
	 *
	 * @return Array with Names of Provider
	 */
	function getAllProviderNames() {
		$i=0;
		$tmp=[];
		foreach ($this->definitions as &$value) {
			//array_push($tmp,$value->getName());
			$tmp[] = $value->getName();
			$i++;
		}
		return $tmp;
	}

}
