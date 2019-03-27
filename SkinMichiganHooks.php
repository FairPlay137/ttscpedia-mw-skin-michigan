<?php

/**
 * Hooks for Michigan skin
 *
 * @file
 * @ingroup Skins
 */

class SkinMichiganHooks {

	/* Protected Static Members */

	protected static $features = [
		'collapsiblenav' => [
			'preferences' => [
				'skinmichigan-collapsiblenav' => [
					'type' => 'toggle',
					'label-message' => 'skinmichigan-collapsiblenav-preference',
					'section' => 'rendering/advancedrendering',
				],
			],
			'requirements' => [
				'skinmichigan-collapsiblenav' => true,
			],
			'modules' => [ 'skins.michigan.collapsibleNav' ],
		]
	];

	/* Static Methods */

	/**
	 * Checks if a certain option is enabled
	 *
	 * This method is public to allow other extensions that use CollapsibleVector to use the
	 * same configuration as CollapsibleVector itself
	 *
	 * @param $name string Name of the feature, should be a key of $name
	 * @return bool
	 */
	public static function isEnabled( $name ) {
		global $wgMichiganFeatures, $wgUser;

		// Features with global set to true are always enabled
		if ( !isset( $wgMichiganFeatures[$name] ) || $wgMichiganFeatures[$name]['global'] ) {
			return true;
		}
		// Features with user preference control can have any number of preferences
		// to be specific values to be enabled
		if ( $wgMichiganFeatures[$name]['user'] ) {
			if ( isset( self::$features[$name]['requirements'] ) ) {
				foreach ( self::$features[$name]['requirements'] as $requirement => $value ) {
					// Important! We really do want fuzzy evaluation here
					if ( $wgUser->getOption( $requirement ) != $value ) {
						return false;
					}
				}
			}
			return true;
		}
		// Features controlled by $wgMichiganFeatures with both global and user
		// set to false are awlways disabled
		return false;
	}

	/* Static Methods */

	/**
	 * BeforePageDisplay hook
	 *
	 * Adds the modules to the page
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 * @return true
	 */
	public static function beforePageDisplay( $out, $skin ) {
		if ( $skin instanceof SkinMichigan ) {
			// Add modules for enabled features
			foreach ( self::$features as $name => $feature ) {
				if ( isset( $feature['modules'] ) && self::isEnabled( $name ) ) {
					$out->addModules( $feature['modules'] );
				}
			}
		}
		return true;
	}

	/**
	 * GetPreferences hook
	 *
	 * Adds Vector-releated items to the preferences
	 *
	 * @param User current user $user
	 * @param array list of default user preference controls &$defaultPreferences
	 * @return true
	 */
	public static function getPreferences( $user, &$defaultPreferences ) {
		global $wgMichiganFeatures;

		foreach ( self::$features as $name => $feature ) {
			if (
				isset( $feature['preferences'] ) &&
				( !isset( $wgMichiganFeatures[$name] ) || $wgMichiganFeatures[$name]['user'] )
			) {
				foreach ( $feature['preferences'] as $key => $options ) {
					$defaultPreferences[$key] = $options;
				}
			}
		}
		return true;
	}

	/**
	 * ResourceLoaderGetConfigVars hook
	 *
	 * Adds enabled/disabled switches for Vector modules
	 * @param array &$vars
	 * @return true
	 */
	public static function resourceLoaderGetConfigVars( &$vars ) {
		global $wgMichiganFeatures, $wgMichiganSearchBar;

		$configurations = [];
		foreach ( self::$features as $name => $feature ) {
			if (
				isset( $feature['configurations'] ) &&
				( !isset( $wgMichiganFeatures[$name] ) || self::isEnabled( $name ) )
			) {
				foreach ( $feature['configurations'] as $configuration ) {
					global $$wgConfiguration;
					$configurations[$configuration] = $$wgConfiguration;
				}
			}
		}

		$vars['wgMichiganSearch'] = $wgMichiganSearchBar;

		if ( count( $configurations ) ) {
			$vars = array_merge( $vars, $configurations );
		}

		return true;
	}

	/**
	 * @param array &$vars
	 * @return bool
	 */
	public static function makeGlobalVariablesScript( &$vars ) {
		// Build and export old-style wgMichiganEnabledModules object for back compat
		$enabledModules = [];
		foreach ( self::$features as $name => $feature ) {
			$enabledModules[$name] = self::isEnabled( $name );
		}

		$vars['wgMichiganEnabledModules'] = $enabledModules;
		return true;
	}
}
