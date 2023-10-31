<?php

define('WORKING_DIR', ABSPATH); # Working directory på serveren
define('BESTYRELSE_FOLDER', 'public_files/bestyrelsesmøder/'); # Hvor referater af bestyrelsesmøder skal gemmes
define('GF_FOLDER', 'public_files/generalforsamlinger/'); # Hvor referater fra generalforsamlinger skal gemmes
define('ÅRSRAPPORT_FOLDER', 'public_files/årsrapporter/'); # Hvor årsrapporter skal gemmes
define('BUDGET_FOLDER', 'public_files/budgetter/'); # Hvor budgetter skal gemmes

### Funktion der definerer alle variable der skal bruges i forbindelse med afsendelse af mails
function AKDTU_DEFINE($CONSTANT_ROOT, $additionals = array()) {
	## Definer standard variable
	define($CONSTANT_ROOT . '_TO', get_option('AKDTU_' . $CONSTANT_ROOT . '_TO')); # Hvem mailen skal sendes til
	define($CONSTANT_ROOT . '_FROM', get_option('AKDTU_' . $CONSTANT_ROOT . '_FROM')); # Hvem mailen står som værende sendt fra
	define($CONSTANT_ROOT . '_REPLYTO', get_option('AKDTU_' . $CONSTANT_ROOT . '_REPLYTO')); # Hvem der skal stå som svar-adresse. Sæt til '' for ingen svar-adresse
	define($CONSTANT_ROOT . '_CC', get_option('AKDTU_' . $CONSTANT_ROOT . '_CC')); # Hvem der skal sættes cc. Sæt til '' for ingen cc.
	define($CONSTANT_ROOT . '_SUBJECT', get_option('AKDTU_' . $CONSTANT_ROOT . '_SUBJECT')); # Emne på mail
	define($CONSTANT_ROOT . '_MAILCONTENT', get_option('AKDTU_' . $CONSTANT_ROOT . '_MAILCONTENT')); # Indhold i mail
	define($CONSTANT_ROOT . '_ATTACHMENTS', get_option('AKDTU_' . $CONSTANT_ROOT . '_ATTACHMENTS')); # Vedhæftede filer i mail

	## Definer eventuelle ekstra variable
	foreach ($additionals as $additional) {
		define($CONSTANT_ROOT . $additional, get_option('AKDTU_' . $CONSTANT_ROOT . $additional));
	}
}

# Mail om opkrævning for leje af fælleshus
AKDTU_DEFINE(
	'FÆLLESHUS',
	array(
		'_FORMAT' # Format af opkrævning
	)
);

# Mail om ændring af VLAN status i fælleshuset
AKDTU_DEFINE(
	'FÆLLESHUS_VLAN'
);

# Mail med info om fjernet brugeradgang
AKDTU_DEFINE(
	'FJERNBRUGERADGANG',
	array(
		'_FORMAT_RENTALS', # Format for reservationer af fælleshuset
		'_FORMAT_PREVIOUS_GARDENDAYS', # Format for tilmeldinger til tidligere havedage
		'_FORMAT_FUTURE_GARDENDAYS' # Format for tilmeldinger til fremtidige havedage
	)
);

# Mail med info om fjernet lejeradgang
AKDTU_DEFINE(
	'FJERNLEJERADGANG',
	array(
		'_FORMAT_RENTALS', # Format for reservationer af fælleshuset
		'_FORMAT_PREVIOUS_GARDENDAYS', # Format for tilmeldinger til tidligere havedage
		'_FORMAT_FUTURE_GARDENDAYS' # Format for tilmeldinger til fremtidige havedage
	)
);

# Mail med info om ny bruger
define('NYBRUGER_BRUGER_TOGGLE', get_option('AKDTU_NYBRUGER_BRUGER_TOGGLE'));
AKDTU_DEFINE(
	'NYBRUGER_BRUGER_DA'
);
AKDTU_DEFINE(
	'NYBRUGER_BRUGER_EN'
);
AKDTU_DEFINE(
	'NYBRUGER_BESTYRELSE'
);

# Mail med opkrævning for havedage
define('HAVEDAG_DAYS', get_option('AKDTU_HAVEDAG_DAYS'));
AKDTU_DEFINE(
	'HAVEDAG',
	array(
		'_FORMAT', # Format af opkrævning
		'_BOARDMEMBER' # Format til bestyrelsesmedlemmer
	)
);
define('HAVEDAG_WARNING_DAYS', get_option('AKDTU_HAVEDAG_WARNING_DAYS'));
AKDTU_DEFINE(
	'HAVEDAG_WARNING',
	array(
		'_FORMAT', # Format af opkrævning
		'_BOARDMEMBER' # Format til bestyrelsesmedlemmer
	)
);
