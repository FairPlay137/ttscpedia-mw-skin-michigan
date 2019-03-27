<?php
/**
 * Michigan - The TTSCpedia Theme
 *
 * Based off of:
 * Metrolook - Metro look for website
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Skins
 */

/**
 * SkinTemplate class for Michigan skin
 * @ingroup Skins
 */
class SkinMichigan extends SkinTemplate {
	public $skinname = 'michigan';
	public $stylename = 'Michigan';
	public $template = 'MichiganTemplate';
	/**
	 * @var Config
	 */
	private $michiganConfig;

	public function __construct() {
		$this->michiganConfig = \MediaWiki\MediaWikiServices::getInstance()->getConfigFactory()
			->makeConfig( 'michigan' );
	}

	/** @inheritDoc */
	public function getPageClasses( $title ) {
		$className = parent::getPageClasses( $title );
		if ( $this->michiganConfig->get( 'MichiganExperimentalPrintStyles' ) ) {
			$className .= ' michigan-experimental-print-styles';
		}
		$className .= ' michigan-nav-directionality';
		return $className;
	}

	/**
	 * Initializes output page and sets up skin-specific parameters
	 * @param OutputPage $out Object to initialize
	 */
	public function initPage( OutputPage $out ) {
		parent::initPage( $out );

		if ( $this->michiganConfig->get( 'MichiganMobile' ) ) {
			$out->addMeta( 'viewport', 'width=device-width, initial-scale=1' );
		}

		// Print styles are feature flagged.
		// This flag can be removed when T169732 is resolved.
		if ( $this->michiganConfig->get( 'MichiganExperimentalPrintStyles' ) ) {
			// Note, when deploying (T169732) we'll want to fold the stylesheet into
			// skins.michigan.styles and remove this module altogether.
			$out->addModuleStyles( 'skins.michigan.styles.experimental.print' );
		}

		$out->addModules( [ 'skins.michigan.js' ] );
	}

	/**
	 * Loads skin and user CSS files.
	 * @param OutputPage $out
	 */
	public function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );
		if ( $this->michiganConfig->get( 'MichiganMobile' ) &&
			!$this->michiganConfig->get( 'MichiganSearchBar' )
		) {
			$styles = [
				'mediawiki.skinning.interface',
				'skins.michigan.styles.custom',
				'skins.michigan.styles.mobile.custom',
				'skins.michigan.styles.theme.custom',
			];
		} elseif ( $this->michiganConfig->get( 'MichiganMobile' ) &&
			$this->michiganConfig->get( 'MichiganSearchBar' )
		) {
			$styles = [
				'mediawiki.skinning.interface',
				'skins.michigan.styles',
				'skins.michigan.styles.mobile',
				'skins.michigan.styles.theme.custom',
			];
		} elseif ( !$this->michiganConfig->get( 'MichiganMobile' ) &&
			$this->michiganConfig->get( 'MichiganSearchBar' )
		) {
			$styles = [
				'mediawiki.skinning.interface',
				'skins.michigan.styles',
				'skins.michigan.styles.theme.custom',
			];
		} elseif ( !$this->michiganConfig->get( 'MichiganMobile' ) &&
			!$this->michiganConfig->get( 'MichiganSearchBar' )
		) {
			$styles = [
				'mediawiki.skinning.interface',
				'skins.michigan.styles.custom',
				'skins.michigan.styles.theme.custom',
			];
		}
		Hooks::run( 'SkinMichiganStyleModules', [ $this, &$styles ] );
		$out->addModuleStyles( $styles );
	}

	/**
	 * Override to pass our Config instance to it
	 * @inheritDoc
	 */
	public function setupTemplate( $classname, $repository = false, $cache_dir = false ) {
		return new $classname( $this->michiganConfig );
	}

	/**
	 * Whether the logo should be preloaded with an HTTP link header or not
	 * @since 1.29
	 * @return bool
	 */
	public function shouldPreloadLogo() {
		return true;
	}
}
