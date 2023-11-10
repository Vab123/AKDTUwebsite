<?php

function AKDTU_REGISTER($CONSTANT_ROOT, $additionals = array()) {
	## Definer standard variable
	register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . '_TO', array('type' => 'string', 'default' => '')); # Hvem mailen skal sendes til
	register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . '_FROM', array('type' => 'string', 'default' => '')); # Hvem mailen står som værende sendt fra
	register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . '_REPLYTO', array('type' => 'string', 'default' => '')); # Hvem der skal stå som svar-adresse. Sæt til '' for ingen svar-adresse
	register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . '_CC', array('type' => 'string', 'default' => '')); # Hvem der skal sættes cc. Sæt til '' for ingen cc.
	register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . '_SUBJECT', array('type' => 'string', 'default' => '')); # Emne på mail
	register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . '_MAILCONTENT', array('type' => 'string', 'default' => '')); # Indhold i mail
	register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . '_ATTACHMENTS', array('type' => 'string', 'default' => '')); # Vedhæftede filer i mail

	## Definer eventuelle ekstra variable
	foreach ($additionals as $additional) {
		register_setting('options', 'AKDTU_' . $CONSTANT_ROOT . $additional, array('type' => 'string', 'default' => ''));
	}
}

# Mail om opkrævning for leje af fælleshus
AKDTU_REGISTER(
	'FÆLLESHUS',
	array(
		'_FORMAT' # Format af opkrævning
	)
);

# Mail om ændring af VLAN status i fælleshuset
AKDTU_REGISTER(
	'FÆLLESHUS_INTERNET'
);
AKDTU_REGISTER(
	'FÆLLESHUS_INTERNET_BRUGER_DA',
	array(
		'_TOGGLE'
	)
);
AKDTU_REGISTER(
	'FÆLLESHUS_INTERNET_BRUGER_EN',
	array(
		'_TOGGLE'
	)
);

# Mail med info om fjernet brugeradgang
AKDTU_REGISTER(
	'FJERNBRUGERADGANG',
	array(
		'_FORMAT_RENTALS', # Format for reservationer af fælleshuset
		'_FORMAT_PREVIOUS_GARDENDAYS', # Format for tilmeldinger til tidligere havedage
		'_FORMAT_FUTURE_GARDENDAYS' # Format for tilmeldinger til fremtidige havedage
	)
);

# Mail med info om fjernet lejeradgang
AKDTU_REGISTER(
	'FJERNLEJERADGANG',
	array(
		'_FORMAT_RENTALS', # Format for reservationer af fælleshuset
		'_FORMAT_PREVIOUS_GARDENDAYS', # Format for tilmeldinger til tidligere havedage
		'_FORMAT_FUTURE_GARDENDAYS' # Format for tilmeldinger til fremtidige havedage
	)
);

# Mail med info om ny bruger
register_setting('options', 'NYBRUGER_BRUGER_TOGGLE', array('type' => 'string', 'default' => '')); # Indhold i mail
AKDTU_REGISTER(
	'NYBRUGER_BRUGER_DA'
);
AKDTU_REGISTER(
	'NYBRUGER_BRUGER_EN'
);
AKDTU_REGISTER(
	'NYBRUGER_BESTYRELSE'
);
