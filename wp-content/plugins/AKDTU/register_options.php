<?php

/**
 * @var $AKDTU_OPTIONS Structure containing information about all option pages to load.
 * 
 * Structure:
 * 
 * - "optionfilename" => array
 * > - "page-title" => string
 * > - "menu-title" => string
 * > - "menu-slug" => string
 * > - "function" => string
 * 
 * Keys: 
 * - "page-title": String. Title of the options page. Shown at the top of the page.
 * - "menu-title": String. Title of the menu object. Shown in the menu at the left of the control panel.
 * - "menu-slug": String. Slug for the menu page. Part of the page URL.
 * - "function": String. Name of the function to call to get the content of the menu page.
 * - "optionfilename": String. Filename of the file with the option function.
 */
$AKDTU_OPTIONS = array(
	"fælleshus.php" => array(
		"page-title" => "Mail: Fælleshus opkrævning",
		"menu-title" => "Mail: Fælleshus opkrævning",
		"menu-slug" => "akdtu-plugin-fælleshus-opkrævning",
		"function" => "AKDTU_fælleshus_mail_settings",
		"default-tab" => "settings",
		"h1" => "Mailindstillinger",
		"h2" => "Mail der sendes med opkrævninger for leje af fælleshuset",
		"tabs" => array(
			"settings" => array(
				"save-success-message" => "Indstillingerne blev gemt",
				"tab-title" => "Indstillinger",
				"tab-type" => "settings",
				"save-action" => "AKDTU_save_fælleshus_mail_settings",
				"save-button-text" => "Gem",
				"settings" => array(
					"group1" => array(
						"h3" => "",
						"content" => array(
							array(
								"headline" => "Modtager adresse",
								"name" => "AKDTU_FÆLLESHUS_TO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Afsender adresse",
								"name" => "AKDTU_FÆLLESHUS_FROM",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code>",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Cc adresse",
								"name" => "AKDTU_FÆLLESHUS_CC",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen cc",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Svaradresse",
								"name" => "AKDTU_FÆLLESHUS_REPLYTO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen svaradresse",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Emne",
								"name" => "AKDTU_FÆLLESHUS_SUBJECT",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"<code>#APT</code> erstattes med lejlighedsnummeret",
								),
							),
							array(
								"headline" => "Vedhæftede filer",
								"name" => "AKDTU_FÆLLESHUS_ATTACHMENTS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Skal være relativt til <code>" . website_root_folder() . "</code>. Skal starte med <code>/</code>",
									"Flere vedhæftede filer adskilles med <code>,</code>",
								),
							),
							array(
								"headline" => "Mail indhold",
								"name" => "AKDTU_FÆLLESHUS_MAILCONTENT",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"<code>#PAYMENT_INFO</code> erstattes med info omkring hvem der skal betale for leje. Formatet for dette kan rettes nedenunder.",
									"<code>#MONTH</code> erstattes med måned.",
									"<code>#YEAR</code> erstattes med år.",
								),
							),
							array(
								"headline" => "Format for info om betaling",
								"name" => "AKDTU_FÆLLESHUS_FORMAT",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Dette gentages for hver lejlighed, der har lejet fælleshuset den foregående måned, med linjeskift mellem.",
									"<code>#APT</code> erstattes med lejlighedsnummer. Hvis der nyligt er flyttet en ny beboer ind står dette også herefter som <code>(Ny beboer)</code> eller <code>(Tidligere beboer)</code>",
									"<code>#PRICE</code> erstattes med pris.",
								),
							),
						),
					),
				),
			),
			"test" => array(
				"tab-title" => "Afprøv",
				"tab-type" => "test",
				"includes" => array(
					
				),
				"function-calls" => array(
					"send_opkrævning_fælleshus(true);",
				)
			),
		),
	),
	"fælleshus-internet.php" => array(
		"page-title" => "Mail: Fælleshus internet",
		"menu-title" => "Mail: Fælleshus internet",
		"menu-slug" => "akdtu-plugin-mail-fælleshus-internet",
		"function" => "AKDTU_fælleshus_internet_mail_settings",
		"default-tab" => "settings",
		"h1" => "Mailindstillinger",
		"h2" => "Mail der sendes når adgangskoden til internettet i fælleshuset ændres",
		"tabs" => array(
			"settings" => array(
				"save-success-message" => "Indstillingerne blev gemt",
				"tab-title" => "Indstillinger",
				"tab-type" => "settings",
				"save-action" => "AKDTU_save_fælleshus_internet_mail_settings",
				"save-button-text" => "Gem (Alt)",
				"settings" => array(
					"group1" => array(
						"h3" => "Mail til bestyrelsen",
						"content" => array(
							array(
								"headline" => "Modtager adresse",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_TO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Afsender adresse",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_FROM",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code>",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Cc adresse",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_CC",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen cc",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Svaradresse",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_REPLYTO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen svaradresse",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Emne",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_SUBJECT",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"<code>#APT</code> erstattes med lejlighedsnummeret",
								),
							),
							array(
								"headline" => "Vedhæftede filer",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_ATTACHMENTS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Skal være relativt til <code>" . website_root_folder() . "</code>. Skal starte med <code>/</code>",
									"Flere vedhæftede filer adskilles med <code>,</code>",
								),
							),
							array(
								"headline" => "Mail indhold",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_MAILCONTENT",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"<code>#RENTER</code> erstattes med info om hvem fælleshuset er lejet af.",
									"<code>#SSID</code> erstattes med navnet på internetforbindelsen.",
									"<code>#NEWPASS</code> erstattes med adgangskoden til internetforbindelsen.",
									"<code>#UPDATETIME</code> erstattes med tidspunktet, hvor opdateringen fandt sted.",
								),
							),
						),
					),
					"group2" => array(
						"h3" => "Mail til lejer, dansk",
						"content" => array(
							array(
								"headline" => "Send mail",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_TOGGLE",
								"tag" => "input",
								"type" => "checkbox",
								"style" => "",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Afsender adresse",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_FROM",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code>",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Cc adresse",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_CC",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen cc",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Svaradresse",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_REPLYTO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen svaradresse",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Emne",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_SUBJECT",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"<code>#APT</code> erstattes med lejlighedsnummeret",
								),
							),
							array(
								"headline" => "Vedhæftede filer",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_ATTACHMENTS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Skal være relativt til <code>" . website_root_folder() . "</code>. Skal starte med <code>/</code>",
									"Flere vedhæftede filer adskilles med <code>,</code>",
								),
							),
							array(
								"headline" => "Mail indhold",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_BRUGER_DA_MAILCONTENT",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"<code>#RENTALSTARTDATETIME</code> erstattes med start-dato på lejeperioden.",
									"<code>#RENTALENDDATETIME</code> erstattes med slut-dato på lejeperioden.",
									"<code>#RENTALSTARTDATETIME</code> erstattes med start-tidpunkt på lejeperioden.",
									"<code>#RENTALENDDATETIME</code> erstattes med slut-tidpunkt på lejeperioden.",
									"<code>#SSID</code> erstattes med navnet på internetforbindelsen.",
									"<code>#NEWPASS</code> erstattes med adgangskoden til internetforbindelsen.",
									"<code>#FIRSTNAME</code> erstattes med lejerens fornavn.",
									"<code>#LASTNAME</code> erstattes med lejerens efternavn.",
								),
							),
						),
					),
					"group3" => array(
						"h3" => "Mail til lejer, engelsk",
						"content" => array(
							array(
								"headline" => "Send mail",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_TOGGLE",
								"tag" => "input",
								"type" => "checkbox",
								"style" => "",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Afsender adresse",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_FROM",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code>",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Cc adresse",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_CC",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen cc",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Svaradresse",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_REPLYTO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen svaradresse",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Emne",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_SUBJECT",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"<code>#APT</code> erstattes med lejlighedsnummeret",
								),
							),
							array(
								"headline" => "Vedhæftede filer",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_ATTACHMENTS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Skal være relativt til <code>" . website_root_folder() . "</code>. Skal starte med <code>/</code>",
									"Flere vedhæftede filer adskilles med <code>,</code>",
								),
							),
							array(
								"headline" => "Mail indhold",
								"name" => "AKDTU_FÆLLESHUS_INTERNET_BRUGER_EN_MAILCONTENT",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"<code>#RENTALSTARTDATETIME</code> erstattes med start-dato på lejeperioden.",
									"<code>#RENTALENDDATETIME</code> erstattes med slut-dato på lejeperioden.",
									"<code>#RENTALSTARTDATETIME</code> erstattes med start-tidpunkt på lejeperioden.",
									"<code>#RENTALENDDATETIME</code> erstattes med slut-tidpunkt på lejeperioden.",
									"<code>#SSID</code> erstattes med navnet på internetforbindelsen.",
									"<code>#NEWPASS</code> erstattes med adgangskoden til internetforbindelsen.",
									"<code>#FIRSTNAME</code> erstattes med lejerens fornavn.",
									"<code>#LASTNAME</code> erstattes med lejerens efternavn.",
								),
							),
						),
					),
				),
			),
			"test" => array(
				"tab-title" => "Afprøv",
				"tab-type" => "test",
				"includes" => array(
					
				),
				"function-calls" => array(
					"send_opdater_fælleshus_internet(true);",
				)
			),
		),
	),
	"brugeradgang.php" => array(
		"page-title" => "Mail: Bruger adgang",
		"menu-title" => "Mail: Bruger adgang",
		"menu-slug" => "akdtu-plugin-mail-bruger-adgang",
		"function" => "AKDTU_brugeradgang_mail_settings",
		"default-tab" => "settings",
		"h1" => "Mailindstillinger",
		"h2" => "Mail der sendes når adgang fjernes til en beboer, der er flyttet",
		"tabs" => array(
			"settings" => array(
				"save-success-message" => "Indstillingerne blev gemt",
				"tab-title" => "Indstillinger",
				"tab-type" => "settings",
				"save-action" => "AKDTU_save_brugeradgang_mail_settings",
				"save-button-text" => "Gem",
				"settings" => array(
					"group1" => array(
						"h3" => "",
						"content" => array(
							array(
								"headline" => "Modtager adresse",
								"name" => "AKDTU_FJERNBRUGERADGANG_TO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Afsender adresse",
								"name" => "AKDTU_FJERNBRUGERADGANG_FROM",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code>",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Cc adresse",
								"name" => "AKDTU_FJERNBRUGERADGANG_CC",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen cc",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Svaradresse",
								"name" => "AKDTU_FJERNBRUGERADGANG_REPLYTO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen svaradresse",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Emne",
								"name" => "AKDTU_FJERNBRUGERADGANG_SUBJECT",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"<code>#APT</code> erstattes med lejlighedsnummeret",
								),
							),
							array(
								"headline" => "Vedhæftede filer",
								"name" => "AKDTU_FJERNBRUGERADGANG_ATTACHMENTS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Skal være relativt til <code>" . website_root_folder() . "</code>. Skal starte med <code>/</code>",
									"Flere vedhæftede filer adskilles med <code>,</code>",
								),
							),
							array(
								"headline" => "Mail indhold",
								"name" => "AKDTU_FJERNBRUGERADGANG_MAILCONTENT",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"<code>#APT</code> erstattes med lejlighedsnummeret.",
									"<code>#NEWMAIL</code> erstattes med brugerens nye emailadresse.",
									"<code>#NEWFIRSTNAME</code> erstattes med brugerens nye fornavn.",
									"<code>#NEWLASTNAME</code> erstattes med brugerens nye efternavn.",
									"<code>#OLDMAIL</code> erstattes med brugerens gamle emailadresse.",
									"<code>#OLDFIRSTNAME</code> erstattes med brugerens gamle fornavn.",
									"<code>#OLDLASTNAME</code> erstattes med brugerens gamle efternavn.",
									"<code>#RENTALS</code> erstattes med info omkring udlejninger af fælleshus.",
									"<code>#PREVIOUS_GARDENDAYS</code> erstattes med info om tidligere havedage.",
									"<code>#FUTURE_GARDENDAYS</code> erstattes med info om fremtidige havedage.",
								),
							),
							array(
								"headline" => "Format for info omkring udlejninger af fælleshus",
								"name" => "AKDTU_FJERNBRUGERADGANG_FORMAT_RENTALS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Dette gentages for hver leje af fælleshuset, brugeren har foretaget, med linjeskift mellem.",
									"<code>#NAME</code> erstattes med navnet på begivenheden.",
									"<code>#START_DATE_SECOND</code> erstattes med sekundet for starten på begivenheden.",
									"<code>#START_DATE_MINUTE</code> erstattes med minuttet for starten på begivenheden.",
									"<code>#START_DATE_HOUR</code> erstattes med timetallet for starten på begivenheden.",
									"<code>#START_DATE_DAY</code> erstattes med dagen for starten på begivenheden.",
									"<code>#START_DATE_MONTH</code> erstattes med måneden for starten på begivenheden.",
									"<code>#START_DATE_YEAR</code> erstattes med året for starten på begivenheden.",
									"<code>#END_DATE_SECOND</code> erstattes med sekundet for slutningen på begivenheden.",
									"<code>#END_DATE_MINUTE</code> erstattes med minuttet for slutningen på begivenheden.",
									"<code>#END_DATE_HOUR</code> erstattes med timetallet for slutningen på begivenheden.",
									"<code>#END_DATE_DAY</code> erstattes med dagen for slutningen på begivenheden.",
									"<code>#END_DATE_MONTH</code> erstattes med måneden for slutningen på begivenheden.",
									"<code>#END_DATE_YEAR</code> erstattes med året for slutningen på begivenheden.",
								),
							),
							array(
								"headline" => "Format for info omkring tidligere havedage",
								"name" => "AKDTU_FJERNBRUGERADGANG_FORMAT_PREVIOUS_GARDENDAYS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Dette gentages for hver fremtidig havedag, brugeren er tilmeldt, med linjeskift mellem.",
									"<code>#NAME</code> erstattes med navnet på havedagen.",
									"<code>#DATE_SECOND</code> erstattes med sekundet for havedagen.",
									"<code>#DATE_MINUTE</code> erstattes med minuttet for havedagen.",
									"<code>#DATE_HOUR</code> erstattes med timen for havedagen.",
									"<code>#DATE_DAY</code> erstattes med dagen for havedagen.",
									"<code>#DATE_MONTH</code> erstattes med måneden for havedagen.",
									"<code>#DATE_YEAR</code> erstattes med året for havedagen.",
								),
							),
							array(
								"headline" => "Format for info omkring fremtidige havedage",
								"name" => "AKDTU_FJERNBRUGERADGANG_FORMAT_FUTURE_GARDENDAYS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Dette gentages for hver fremtidig havedag, brugeren er tilmeldt, med linjeskift mellem.",
									"<code>#NAME</code> erstattes med navnet på havedagen.",
									"<code>#DATE_SECOND</code> erstattes med sekundet for havedagen.",
									"<code>#DATE_MINUTE</code> erstattes med minuttet for havedagen.",
									"<code>#DATE_HOUR</code> erstattes med timen for havedagen.",
									"<code>#DATE_DAY</code> erstattes med dagen for havedagen.",
									"<code>#DATE_MONTH</code> erstattes med måneden for havedagen.",
									"<code>#DATE_YEAR</code> erstattes med året for havedagen.",
								),
							),
						),
					),
				),
			),
			"test" => array(
				"tab-title" => "Afprøv",
				"tab-type" => "test",
				"includes" => array(
					
				),
				"function-calls" => array(
					"send_fjern_brugeradgang(true);",
				)
			)
		),
	),
	"lejeradgang.php" => array(
		"page-title" => "Mail: Lejer adgang",
		"menu-title" => "Mail: Lejer adgang",
		"menu-slug" => "akdtu-plugin-mail-lejer-adgang",
		"function" => "AKDTU_lejeradgang_mail_settings",
		"default-tab" => "settings",
		"h1" => "Mailindstillinger",
		"h2" => "Mail der sendes når adgang fjernes til en lejer, der er flyttet",
		"tabs" => array(
			"settings" => array(
				"save-success-message" => "Indstillingerne blev gemt",
				"tab-title" => "Indstillinger",
				"tab-type" => "settings",
				"save-action" => "AKDTU_save_lejeradgang_mail_settings",
				"save-button-text" => "Gem",
				"settings" => array(
					"group1" => array(
						"h3" => "",
						"content" => array(
							array(
								"headline" => "Modtager adresse",
								"name" => "AKDTU_FJERNLEJERADGANG_TO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Afsender adresse",
								"name" => "AKDTU_FJERNLEJERADGANG_FROM",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code>",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Cc adresse",
								"name" => "AKDTU_FJERNLEJERADGANG_CC",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen cc",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Svaradresse",
								"name" => "AKDTU_FJERNLEJERADGANG_REPLYTO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen svaradresse",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Emne",
								"name" => "AKDTU_FJERNLEJERADGANG_SUBJECT",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"<code>#APT</code> erstattes med lejlighedsnummeret",
								),
							),
							array(
								"headline" => "Vedhæftede filer",
								"name" => "AKDTU_FJERNLEJERADGANG_ATTACHMENTS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Skal være relativt til <code>" . website_root_folder() . "</code>. Skal starte med <code>/</code>",
									"Flere vedhæftede filer adskilles med <code>,</code>",
								),
							),
							array(
								"headline" => "Mail indhold",
								"name" => "AKDTU_FJERNLEJERADGANG_MAILCONTENT",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"<code>#APT</code> erstattes med lejlighedsnummeret.",
									"<code>#NEWMAIL</code> erstattes med lejerens nye emailadresse.",
									"<code>#NEWFIRSTNAME</code> erstattes med lejerens nye fornavn.",
									"<code>#NEWLASTNAME</code> erstattes med lejerens nye efternavn.",
									"<code>#OLDMAIL</code> erstattes med lejerens gamle emailadresse.",
									"<code>#OLDFIRSTNAME</code> erstattes med lejerens gamle fornavn.",
									"<code>#OLDLASTNAME</code> erstattes med lejerens gamle efternavn.",
									"<code>#RENTALS</code> erstattes med info omkring udlejninger af fælleshus.",
									"<code>#PREVIOUS_GARDENDAYS</code> erstattes med info om tidligere havedage.",
									"<code>#FUTURE_GARDENDAYS</code> erstattes med info om fremtidige havedage.",
								),
							),
							array(
								"headline" => "Format for info omkring udlejninger af fælleshus",
								"name" => "AKDTU_FJERNLEJERADGANG_FORMAT_RENTALS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Dette gentages for hver leje af fælleshuset, lejeren har foretaget, med linjeskift mellem.",
									"<code>#NAME</code> erstattes med navnet på begivenheden.",
									"<code>#START_DATE_SECOND</code> erstattes med sekundet for starten på begivenheden.",
									"<code>#START_DATE_MINUTE</code> erstattes med minuttet for starten på begivenheden.",
									"<code>#START_DATE_HOUR</code> erstattes med timetallet for starten på begivenheden.",
									"<code>#START_DATE_DAY</code> erstattes med dagen for starten på begivenheden.",
									"<code>#START_DATE_MONTH</code> erstattes med måneden for starten på begivenheden.",
									"<code>#START_DATE_YEAR</code> erstattes med året for starten på begivenheden.",
									"<code>#END_DATE_SECOND</code> erstattes med sekundet for slutningen på begivenheden.",
									"<code>#END_DATE_MINUTE</code> erstattes med minuttet for slutningen på begivenheden.",
									"<code>#END_DATE_HOUR</code> erstattes med timetallet for slutningen på begivenheden.",
									"<code>#END_DATE_DAY</code> erstattes med dagen for slutningen på begivenheden.",
									"<code>#END_DATE_MONTH</code> erstattes med måneden for slutningen på begivenheden.",
									"<code>#END_DATE_YEAR</code> erstattes med året for slutningen på begivenheden.",
								),
							),
							array(
								"headline" => "Format for info omkring tidligere havedage",
								"name" => "AKDTU_FJERNLEJERADGANG_FORMAT_PREVIOUS_GARDENDAYS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Dette gentages for hver tidligere havedag, lejeren er tilmeldt, med linjeskift mellem.",
									"<code>#NAME</code> erstattes med navnet på havedagen.",
									"<code>#DATE_SECOND</code> erstattes med sekundet for havedagen.",
									"<code>#DATE_MINUTE</code> erstattes med minuttet for havedagen.",
									"<code>#DATE_HOUR</code> erstattes med timen for havedagen.",
									"<code>#DATE_DAY</code> erstattes med dagen for havedagen.",
									"<code>#DATE_MONTH</code> erstattes med måneden for havedagen.",
									"<code>#DATE_YEAR</code> erstattes med året for havedagen.",
								),
							),
							array(
								"headline" => "Format for info omkring fremtidige havedage",
								"name" => "AKDTU_FJERNLEJERADGANG_FORMAT_FUTURE_GARDENDAYS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Dette gentages for hver fremtidig havedag, lejeren er tilmeldt, med linjeskift mellem.",
									"<code>#NAME</code> erstattes med navnet på havedagen.",
									"<code>#DATE_SECOND</code> erstattes med sekundet for havedagen.",
									"<code>#DATE_MINUTE</code> erstattes med minuttet for havedagen.",
									"<code>#DATE_HOUR</code> erstattes med timen for havedagen.",
									"<code>#DATE_DAY</code> erstattes med dagen for havedagen.",
									"<code>#DATE_MONTH</code> erstattes med måneden for havedagen.",
									"<code>#DATE_YEAR</code> erstattes med året for havedagen.",
								),
							),
						),
					),
				),
			),
			"test" => array(
				"tab-title" => "Afprøv",
				"tab-type" => "test",
				"includes" => array(
					
				),
				"function-calls" => array(
					"send_fjern_lejeradgang(true);",
				)
			),
		),
	),
	"ny-bruger.php" => array(
		"page-title" => "Mail: Ny bruger",
		"menu-title" => "Mail: Ny bruger",
		"menu-slug" => "akdtu-plugin-mail-ny-bruger",
		"function" => "AKDTU_ny_bruger_mail_settings",
		"default-tab" => "settings",
		"h1" => "Mailindstillinger",
		"h2" => "Mail der sendes når der bliver oprettet en ny profil på hjemmesiden",
		"tabs" => array(
			"settings" => array(
				"save-success-message" => "Indstillingerne blev gemt",
				"tab-title" => "Indstillinger",
				"tab-type" => "settings",
				"save-action" => "AKDTU_save_ny_bruger_mail_settings",
				"save-button-text" => "Gem (Alt)",
				"settings" => array(
					"group1" => array(
						"h3" => "Mail til bestyrelse",
						"content" => array(
							array(
								"headline" => "Modtager adresse",
								"name" => "AKDTU_NYBRUGER_BESTYRELSE_TO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Afsender adresse",
								"name" => "AKDTU_NYBRUGER_BESTYRELSE_FROM",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code>",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Cc adresse",
								"name" => "AKDTU_NYBRUGER_BESTYRELSE_CC",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen cc",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Svaradresse",
								"name" => "AKDTU_NYBRUGER_BESTYRELSE_REPLYTO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen svaradresse",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Emne",
								"name" => "AKDTU_NYBRUGER_BESTYRELSE_SUBJECT",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"<code>#APT</code> erstattes med lejlighedsnummeret.",
									"<code>#NEWLOGIN</code> erstattes med brugernavnet på profilen.",
									"<code>#NEWEMAIL</code> erstattes med brugerens nye emailadresse.",
									"<code>#NEWFIRSTNAME</code> erstattes med brugerens nye fornavn.",
									"<code>#NEWLASTNAME</code> erstattes med brugerens nye efternavn.",
								),
							),
							array(
								"headline" => "Vedhæftede filer",
								"name" => "AKDTU_NYBRUGER_BESTYRELSE_ATTACHMENTS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Skal være relativt til <code>" . website_root_folder() . "</code>. Skal starte med <code>/</code>",
									"Flere vedhæftede filer adskilles med <code>,</code>",
								),
							),
							array(
								"headline" => "Mail indhold",
								"name" => "AKDTU_NYBRUGER_BESTYRELSE_MAILCONTENT",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"<code>#APT</code> erstattes med lejlighedsnummeret.",
									"<code>#NEWLOGIN</code> erstattes med brugernavnet på profilen.",
									"<code>#NEWEMAIL</code> erstattes med brugerens nye emailadresse.",
									"<code>#NEWFIRSTNAME</code> erstattes med brugerens nye fornavn.",
									"<code>#NEWLASTNAME</code> erstattes med brugerens nye efternavn.",
								),
							),
						),
					),
					"group2" => array(
						"h3" => "Mail til ny beboer",
						"content" => array(
							array(
								"headline" => "Send email?",
								"name" => "AKDTU_NYBRUGER_BRUGER_TOGGLE",
								"tag" => "input",
								"type" => "checkbox",
								"style" => "",
								"comments" => array(
									"Afgør om der sendes bekræftelsesmail til den nyoprettede bruger.",
								),
							),
							array(
								"headline" => "Afsender adresse",
								"name" => "AKDTU_NYBRUGER_BRUGER_FROM",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code>",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Cc adresse",
								"name" => "AKDTU_NYBRUGER_BRUGER_CC",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen cc",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Svaradresse",
								"name" => "AKDTU_NYBRUGER_BRUGER_REPLYTO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen svaradresse",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Emne (Dansk)",
								"name" => "AKDTU_NYBRUGER_BRUGER_DA_SUBJECT",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"<code>#APT</code> erstattes med lejlighedsnummeret.",
									"<code>#NEWLOGIN</code> erstattes med brugernavnet på profilen.",
									"<code>#NEWEMAIL</code> erstattes med brugerens nye emailadresse.",
									"<code>#NEWFIRSTNAME</code> erstattes med brugerens nye fornavn.",
									"<code>#NEWLASTNAME</code> erstattes med brugerens nye efternavn.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Dansk)",
								"name" => "AKDTU_NYBRUGER_BRUGER_DA_ATTACHMENTS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Skal være relativt til <code>" . website_root_folder() . "</code>. Skal starte med <code>/</code>",
									"Flere vedhæftede filer adskilles med <code>,</code>",
								),
							),
							array(
								"headline" => "Mail indhold (Dansk)",
								"name" => "AKDTU_NYBRUGER_BRUGER_DA_MAILCONTENT",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"<code>#APT</code> erstattes med lejlighedsnummeret.",
									"<code>#NEWLOGIN</code> erstattes med brugernavnet på profilen.",
									"<code>#NEWEMAIL</code> erstattes med brugerens nye emailadresse.",
									"<code>#NEWFIRSTNAME</code> erstattes med brugerens nye fornavn.",
									"<code>#NEWLASTNAME</code> erstattes med brugerens nye efternavn.",
								),
							),
							array(
								"headline" => "Emne (Engelsk)",
								"name" => "AKDTU_NYBRUGER_BRUGER_EN_SUBJECT",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"<code>#APT</code> erstattes med lejlighedsnummeret.",
									"<code>#NEWLOGIN</code> erstattes med brugernavnet på profilen.",
									"<code>#NEWEMAIL</code> erstattes med brugerens nye emailadresse.",
									"<code>#NEWFIRSTNAME</code> erstattes med brugerens nye fornavn.",
									"<code>#NEWLASTNAME</code> erstattes med brugerens nye efternavn.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Engelsk)",
								"name" => "AKDTU_NYBRUGER_BRUGER_EN_ATTACHMENTS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Skal være relativt til <code>" . website_root_folder() . "</code>. Skal starte med <code>/</code>",
									"Flere vedhæftede filer adskilles med <code>,</code>",
								),
							),
							array(
								"headline" => "Mail indhold (Engelsk)",
								"name" => "AKDTU_NYBRUGER_BRUGER_EN_MAILCONTENT",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"<code>#APT</code> erstattes med lejlighedsnummeret.",
									"<code>#NEWLOGIN</code> erstattes med brugernavnet på profilen.",
									"<code>#NEWEMAIL</code> erstattes med brugerens nye emailadresse.",
									"<code>#NEWFIRSTNAME</code> erstattes med brugerens nye fornavn.",
									"<code>#NEWLASTNAME</code> erstattes med brugerens nye efternavn.",
								),
							),
						),
					),
				),
			),
			"test" => array(
				"tab-title" => "Afprøv",
				"tab-type" => "test",
				"includes" => array(
					
				),
				"function-calls" => array(
					"test_ny_bruger_mail(true);",
				)
			),
		),
	),
	"havedag-tilmelding-beboer.php" => array(
		"page-title" => "Mail: Havedag tilmelding - beboer",
		"menu-title" => "Mail: Havedag tilmelding - beboer",
		"menu-slug" => "akdtu-plugin-havedag-tilmelding-beboer-mail-settings",
		"function" => "AKDTU_havedag_tilmelding_beboer_mail_settings",
		"default-tab" => "settings",
		"h1" => "Mailindstillinger",
		"h2" => "Mail der sendes til beboeren når de tilmelder eller afmelder sig en havedag",
		"tabs" => array(
			"settings" => array(
				"save-success-message" => "Indstillingerne blev gemt",
				"tab-title" => "Indstillinger",
				"tab-type" => "settings",
				"save-action" => "AKDTU_save_havedag_tilmelding_beboer_mail_settings",
				"save-button-text" => "Gem (Alt)",
				"settings" => array(
					"group1" => array(
						"h3" => "Tilmelding",
						"content" => array(
							array(
								"headline" => "Emne (Dansk)",
								"name" => "dbem_bookings_email_confirmed_subject_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Dansk)",
								"name" => "dbem_bookings_email_confirmed_body_da",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 300px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
									"<code>#_BOOKINGNAME</code> erstattes med navnet på havedagen.",
									"<code>#_BOOKINGTICKETNAME</code> erstattes med datoen på havedagen.",
									"<code>#_BOOKINGCOMMENT</code> erstattes med en eventuel kommentar til tilmeldingen.",
								),
							),
							array(
								"headline" => "Emne (Engelsk)",
								"name" => "dbem_bookings_email_confirmed_subject_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Engelsk)",
								"name" => "dbem_bookings_email_confirmed_body_en",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 300px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
									"<code>#_BOOKINGNAME</code> erstattes med navnet på havedagen.",
									"<code>#_BOOKINGTICKETNAME</code> erstattes med datoen på havedagen.",
									"<code>#_BOOKINGCOMMENT</code> erstattes med en eventuel kommentar til tilmeldingen.",
								),
							),
						),
					),
					"group2" => array(
						"h3" => "Afmelding",
						"content" => array(
							array(
								"headline" => "Emne (Dansk)",
								"name" => "dbem_bookings_email_cancelled_subject_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Dansk)",
								"name" => "dbem_bookings_email_cancelled_body_da",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 300px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
									"<code>#_BOOKINGNAME</code> erstattes med navnet på havedagen.",
									"<code>#_BOOKINGTICKETNAME</code> erstattes med datoen på havedagen.",
									"<code>#_BOOKINGCOMMENT</code> erstattes med en eventuel kommentar til tilmeldingen.",
								),
							),
							array(
								"headline" => "Emne (Engelsk)",
								"name" => "dbem_bookings_email_cancelled_subject_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Engelsk)",
								"name" => "dbem_bookings_email_cancelled_body_en",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 300px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
									"<code>#_BOOKINGNAME</code> erstattes med navnet på havedagen.",
									"<code>#_BOOKINGTICKETNAME</code> erstattes med datoen på havedagen.",
									"<code>#_BOOKINGCOMMENT</code> erstattes med en eventuel kommentar til tilmeldingen.",
								),
							),
						),
					),
				),
			),
			"test" => array(
				"tab-title" => "Afprøv",
				"tab-type" => "test",
				"includes" => array(
					
				),
				"function-calls" => array(
					"test_havedag_tilmelding_beboer_mail();",
				)
			),
		),
	),
	"havedag-tilmelding-bestyrelse.php" => array(
		"page-title" => "Mail: Havedag tilmelding - bestyrelse",
		"menu-title" => "Mail: Havedag tilmelding - bestyrelse",
		"menu-slug" => "akdtu-plugin-havedag-tilmelding-bestyrelse-mail-settings",
		"function" => "AKDTU_havedag_tilmelding_bestyrelse_mail_settings",
		"default-tab" => "settings",
		"h1" => "Mailindstillinger",
		"h2" => "Mail der sendes til bestyrelsen når en beboer tilmelder eller afmelder sig en havedag",
		"tabs" => array(
			"settings" => array(
				"save-success-message" => "Indstillingerne blev gemt",
				"tab-title" => "Indstillinger",
				"tab-type" => "settings",
				"save-action" => "AKDTU_save_havedag_tilmelding_bestyrelse_mail_settings",
				"save-button-text" => "Gem (Alt)",
				"settings" => array(
					"group1" => array(
						"h3" => "",
						"content" => array(
							array(
								"headline" => "Email-adresse",
								"name" => "dbem_bookings_notify_admin",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
									"Kan KUN skrives ind som <code>bestyrelsen@akdtu.dk</code>",
								),
							),
						),
					),
					"group2" => array(
						"h3" => "Tilmelding",
						"content" => array(
							array(
								"headline" => "Emne",
								"name" => "dbem_bookings_contact_email_confirmed_subject",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold",
								"name" => "dbem_bookings_contact_email_confirmed_body",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
									"<code>#_BOOKINGNAME</code> erstattes med navnet på havedagen.",
									"<code>#_BOOKINGTICKETNAME</code> erstattes med datoen på havedagen.",
									"<code>#_BOOKINGCOMMENT</code> erstattes med en eventuel kommentar til tilmeldingen.",
									"<code>#_BOOKEDSPACES</code> erstattes med det samlede antal af tilmeldinger.",
									"<code>#_AVAILABLESPACES</code> erstattes med det samlede antal ledige pladser.",
									"<code>#_EVENTINFO</code> erstattes med et overblik over antal tilmeldinger og antal mulige tilmeldinger til alle de planlagte havedage.",
								),
							),
						),
					),
					"group3" => array(
						"h3" => "Afmelding",
						"content" => array(
							array(
								"headline" => "Emne",
								"name" => "dbem_bookings_contact_email_cancelled_subject",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold",
								"name" => "dbem_bookings_contact_email_cancelled_body",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
									"<code>#_BOOKINGNAME</code> erstattes med navnet på havedagen.",
									"<code>#_BOOKINGTICKETNAME</code> erstattes med datoen på havedagen.",
									"<code>#_BOOKINGCOMMENT</code> erstattes med en eventuel kommentar til tilmeldingen.",
									"<code>#_BOOKEDSPACES</code> erstattes med det samlede antal af tilmeldinger.",
									"<code>#_AVAILABLESPACES</code> erstattes med det samlede antal ledige pladser.",
									"<code>#_EVENTINFO</code> erstattes med et overblik over antal tilmeldinger og antal mulige tilmeldinger til alle de planlagte havedage.",
								),
							),
						),
					),
				),
			),
			"test" => array(
				"tab-title" => "Afprøv",
				"tab-type" => "test",
				"includes" => array(
					
				),
				"function-calls" => array(
					"test_havedag_tilmelding_bestyrelse_mail();",
				)
			),
		),
	),
	"leje-af-fælleshus-beboer.php" => array(
		"page-title" => "Mail: Leje af fælleshus - beboer",
		"menu-title" => "Mail: Leje af fælleshus - beboer",
		"menu-slug" => "akdtu-plugin-leje-af-fælleshus-beboer-mail-settings",
		"function" => "AKDTU_leje_af_fælleshus_beboer_mail_settings",
		"default-tab" => "settings",
		"h1" => "Mailindstillinger",
		"h2" => "Mail der sendes til beboeren når ansøgninger om leje af fælleshus besvares",
		"tabs" => array(
			"settings" => array(
				"save-success-message" => "Indstillingerne blev gemt",
				"tab-title" => "Indstillinger",
				"tab-type" => "settings",
				"save-action" => "AKDTU_save_leje_af_fælleshus_beboer_mail_settings",
				"save-button-text" => "Gem (Alt)",
				"settings" => array(
					"group1" => array(
						"h3" => "Mail ved godkendt leje",
						"content" => array(
							array(
								"headline" => "Emne (Dansk)",
								"name" => "dbem_event_approved_email_subject_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Dansk)",
								"name" => "dbem_event_approved_email_body_da",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Dansk)",
								"name" => "dbem_event_approved_email_attachments_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
							array(
								"headline" => "Emne (Engelsk)",
								"name" => "dbem_event_approved_email_subject_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Engelsk)",
								"name" => "dbem_event_approved_email_body_en",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Engelsk)",
								"name" => "dbem_event_approved_email_attachments_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
						),
					),
					"group2" => array(
						"h3" => "Mail ved godkendt ændring af leje",
						"content" => array(
							array(
								"headline" => "Emne (Dansk)",
								"name" => "dbem_event_reapproved_email_subject_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Dansk)",
								"name" => "dbem_event_reapproved_email_body_da",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Dansk)",
								"name" => "dbem_event_reapproved_email_attachments_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
							array(
								"headline" => "Emne (Engelsk)",
								"name" => "dbem_event_reapproved_email_subject_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Engelsk)",
								"name" => "dbem_event_reapproved_email_body_en",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Engelsk)",
								"name" => "dbem_event_reapproved_email_attachments_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
						),
					),
					"group3" => array(
						"h3" => "Mail ved afvist leje",
						"content" => array(
							array(
								"headline" => "Emne (Dansk)",
								"name" => "dbem_event_rejected_email_subject_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Dansk)",
								"name" => "dbem_event_rejected_email_body_da",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Dansk)",
								"name" => "dbem_event_rejected_email_attachments_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
							array(
								"headline" => "Emne (Engelsk)",
								"name" => "dbem_event_rejected_email_subject_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Engelsk)",
								"name" => "dbem_event_rejected_email_body_en",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Engelsk)",
								"name" => "dbem_event_rejected_email_attachments_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
						),
					),
					"group4" => array(
						"h3" => "Mail ved leje slettet af beboer",
						"content" => array(
							array(
								"headline" => "Emne (Dansk)",
								"name" => "dbem_event_deleted_email_subject_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Dansk)",
								"name" => "dbem_event_deleted_email_body_da",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Dansk)",
								"name" => "dbem_event_deleted_email_attachments_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
							array(
								"headline" => "Emne (Engelsk)",
								"name" => "dbem_event_deleted_email_subject_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Engelsk)",
								"name" => "dbem_event_deleted_email_body_en",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Engelsk)",
								"name" => "dbem_event_deleted_email_attachments_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
						),
					),
				),
			),
			"test" => array(
				"tab-title" => "Afprøv",
				"tab-type" => "test",
				"includes" => array(
					
				),
				"function-calls" => array(
					"test_leje_af_fælleshus_beboer_mail();",
				)
			),
		),
	),
	"leje-af-fælleshus-bestyrelse-modtaget.php" => array(
		"page-title" => "Mail: Leje af fælleshus - bestyrelse, modtaget",
		"menu-title" => "Mail: Leje af fælleshus - bestyrelse, modtaget",
		"menu-slug" => "akdtu-plugin-leje-af-fælleshus-bestyrelse-modtaget-mail-settings",
		"function" => "AKDTU_leje_af_fælleshus_bestyrelse_modtaget_mail_settings",
		"default-tab" => "settings",
		"h1" => "Mailindstillinger",
		"h2" => "Mail der sendes til beboeren når ansøgninger om leje af fælleshus besvares",
		"tabs" => array(
			"settings" => array(
				"save-success-message" => "Indstillingerne blev gemt",
				"tab-title" => "Indstillinger",
				"tab-type" => "settings",
				"save-action" => "AKDTU_save_leje_af_fælleshus_bestyrelse_modtaget_mail_settings",
				"save-button-text" => "Gem (Alt)",
				"settings" => array(
					"group1" => array(
						"h3" => "Mail ved godkendt leje",
						"content" => array(
							array(
								"headline" => "Emne",
								"name" => "dbem_event_submitted_email_subject",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold",
								"name" => "dbem_event_submitted_email_body",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer",
								"name" => "dbem_event_submitted_email_attachments",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
						),
					),
					"group2" => array(
						"h3" => "Mail ved ændret ansøgning",
						"content" => array(
							array(
								"headline" => "Emne",
								"name" => "dbem_event_resubmitted_email_subject",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold",
								"name" => "dbem_event_resubmitted_email_body",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer",
								"name" => "dbem_event_resubmitted_email_attachments",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
						),
					),
					"group3" => array(
						"h3" => "Mail ved afvist leje",
						"content" => array(
							array(
								"headline" => "Emne",
								"name" => "dbem_event_deleted_email_subject",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold",
								"name" => "dbem_event_deleted_email_body",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer",
								"name" => "dbem_event_deleted_email_attachments",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
						),
					),
				),
			),
			"test" => array(
				"tab-title" => "Afprøv",
				"tab-type" => "test",
				"includes" => array(
					
				),
				"function-calls" => array(
					"test_leje_af_fælleshus_bestyrelse_modtaget_mail();",
				)
			),
		),
	),
	"leje-af-fælleshus-bestyrelse-besluttet.php" => array(
		"page-title" => "Mail: Leje af fælleshus - bestyrelse, besluttet",
		"menu-title" => "Mail: Leje af fælleshus - bestyrelse, besluttet",
		"menu-slug" => "akdtu-plugin-leje-af-fælleshus-bestyrelse-besluttet-mail-settings",
		"function" => "AKDTU_leje_af_fælleshus_bestyrelse_besluttet_mail_settings",
		"default-tab" => "settings",
		"h1" => "Mailindstillinger",
		"h2" => "Mail der sendes til bestyrelsen når ansøgninger om leje af fælleshus besvares",
		"tabs" => array(
			"settings" => array(
				"save-success-message" => "Indstillingerne blev gemt",
				"tab-title" => "Indstillinger",
				"tab-type" => "settings",
				"save-action" => "AKDTU_save_leje_af_fælleshus_bestyrelse_besluttet_mail_settings",
				"save-button-text" => "Gem (Alt)",
				"settings" => array(
					"group1" => array(
						"h3" => "Mail ved godkendt leje",
						"content" => array(
							array(
								"headline" => "Emne (Dansk)",
								"name" => "dbem_event_approved_confirmation_email_subject_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Dansk)",
								"name" => "dbem_event_approved_confirmation_email_body_da",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Dansk)",
								"name" => "dbem_event_approved_confirmation_email_attachments_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
							array(
								"headline" => "Emne (Engelsk)",
								"name" => "dbem_event_approved_confirmation_email_subject_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Engelsk)",
								"name" => "dbem_event_approved_confirmation_email_body_en",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Engelsk)",
								"name" => "dbem_event_approved_confirmation_email_attachments_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
						),
					),
					"group2" => array(
						"h3" => "Mail ved godkendt ændring af leje",
						"content" => array(
							array(
								"headline" => "Emne (Dansk)",
								"name" => "dbem_event_reapproved_confirmation_email_subject_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Dansk)",
								"name" => "dbem_event_reapproved_confirmation_email_body_da",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Dansk)",
								"name" => "dbem_event_reapproved_confirmation_email_attachments_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
							array(
								"headline" => "Emne (Engelsk)",
								"name" => "dbem_event_reapproved_confirmation_email_subject_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Engelsk)",
								"name" => "dbem_event_reapproved_confirmation_email_body_en",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Engelsk)",
								"name" => "dbem_event_reapproved_confirmation_email_attachments_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
						),
					),
					"group3" => array(
						"h3" => "Mail ved afvist leje",
						"content" => array(
							array(
								"headline" => "Emne (Dansk)",
								"name" => "dbem_event_rejected_confirmation_email_subject_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Dansk)",
								"name" => "dbem_event_rejected_confirmation_email_body_da",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Dansk)",
								"name" => "dbem_event_rejected_confirmation_email_attachments_da",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
							array(
								"headline" => "Emne (Engelsk)",
								"name" => "dbem_event_rejected_confirmation_email_subject_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Indhold (Engelsk)",
								"name" => "dbem_event_rejected_confirmation_email_body_en",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => "auto",
								"style" => "width: 600px",
								"comments" => array(
									"Understøtter <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#booking-placeholders' target='_blank'>Tilmeldingsrelaterede pladsholdere</a>, <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#event-placeholders' target='_blank'>Event relateret pladsholdere</a> og <a href='/wp-admin/edit.php?post_type=event&page=events-manager-help#location-placeholders' target='_blank'>Lokationsrelateret pladsholdere</a>.",
								),
							),
							array(
								"headline" => "Vedhæftede filer (Engelsk)",
								"name" => "dbem_event_rejected_confirmation_email_attachments_en",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at vedhæfte noget.",
									"Skal være relativt til <code>" . website_root_folder() . "</code>",
									"Flere vedhæftede filer splittes med <code>,</code> uden mellemrum.",
								),
							),
						),
					),
				),
			),
			"test" => array(
				"tab-title" => "Afprøv",
				"tab-type" => "test",
				"includes" => array(
					
				),
				"function-calls" => array(
					"test_leje_af_fælleshus_bestyrelse_besluttet_mail();",
				)
			),
		),
	),
	"havedag.php" => array(
		"page-title" => "Mail: Havedag opkrævning",
		"menu-title" => "Mail: Havedag opkrævning",
		"menu-slug" => "akdtu-plugin-havedag-opkrævning-settings",
		"function" => "AKDTU_havedag_opkrævning_mail_settings",
		"default-tab" => "settings",
		"h1" => "Mailindstillinger",
		"h2" => "Mail der sendes med opkrævninger for manglende deltagelse på havedage",
		"tabs" => array(
			"settings" => array(
				"save-success-message" => "Indstillingerne blev gemt",
				"tab-title" => "Indstillinger",
				"tab-type" => "settings",
				"save-action" => "AKDTU_save_havedag_mail_settings",
				"save-button-text" => "Gem (Alt)",
				"settings" => array(
					"group1" => array(
						"h3" => "Opkrævningsmail",
						"content" => array(
							array(
								"headline" => "Afsendelsestidspunkt",
								"name" => "AKDTU_HAVEDAG_DAYS",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Antallet af dage efter sidste havedag, hvor der skal sendes en opkrævningsmail. Minimum er <code>1</code>. Skriv <code>-1</code> for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Modtager adresse",
								"name" => "AKDTU_HAVEDAG_TO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code>",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Afsender adresse",
								"name" => "AKDTU_HAVEDAG_FROM",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code>",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Cc adresse",
								"name" => "AKDTU_HAVEDAG_CC",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen cc",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Svaradresse",
								"name" => "AKDTU_HAVEDAG_REPLYTO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen svaradresse",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Emne",
								"name" => "AKDTU_HAVEDAG_SUBJECT",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"<code>#SEASON</code> erstattes med <code>forår</code> eller <code>efterår</code>.",
									"<code>#YEAR</code> erstattes med året.",
								),
							),
							array(
								"headline" => "Vedhæftede filer",
								"name" => "AKDTU_HAVEDAG_ATTACHMENTS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Skal være relativt til <code>" . website_root_folder() . "</code>. Skal starte med <code>/</code>",
									"Flere vedhæftede filer adskilles med <code>,</code>",
								),
							),
							array(
								"headline" => "Mail indhold",
								"name" => "AKDTU_HAVEDAG_MAILCONTENT",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"<code>#PAYMENT_INFO</code> erstattes med info omkring hvem der skal betale for ikke at have mødt op til havedagene. Formatet for dette kan rettes nedenunder.",
									"<code>#SEASON</code> erstattes med <code>forår</code> eller <code>efterår</code>.",
									"<code>#YEAR</code> erstattes med år.",
								),
							),
							array(
								"headline" => "Format for info om betaling",
								"name" => "AKDTU_HAVEDAG_FORMAT",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Dette gentages for hver lejlighed, der skal opkræves for manglende deltagelse i den seneste havedag, med linjeskift mellem.",
									"<code>#APT</code> erstattes med lejlighedsnummer. Hvis der efter deadline for tilmelding til havedagen er flyttet en ny beboer ind står dette også herefter som <code>(Tidligere beboer)</code>.",
									"<code>#PRICE</code> erstattes med pris.",
									"<code>#BOARDMEMBER</code> erstattes med hvad der er skrevet nedenunder, hvis lejligheden tilhører et bestyrelsesmedlem.",
								),
							),
							array(
								"headline" => "Format for bestyrelsesmedlemmer",
								"name" => "AKDTU_HAVEDAG_BOARDMEMBER",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Dette er hvad <code>#BOARDMEMBER</code> erstattes med i ovenstående format for info om betaling."
								),
							),
						),
					),
					"group2" => array(
						"h3" => "Varselsmail",
						"content" => array(
							array(
								"headline" => "Afsendelsestidspunkt",
								"name" => "AKDTU_HAVEDAG_WARNING_DAYS",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Antallet af dage efter sidste havedag, hvor der skal sendes en opkrævningsmail. Minimum er <code>1</code>. Skriv <code>-1</code> for ikke at sende nogen mail",
								),
							),
							array(
								"headline" => "Modtager adresse",
								"name" => "AKDTU_HAVEDAG_WARNING_TO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code>",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Afsender adresse",
								"name" => "AKDTU_HAVEDAG_WARNING_FROM",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for <code>AKDTU &#60;hjemmeside@akdtu.dk&#62;</code>",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Cc adresse",
								"name" => "AKDTU_HAVEDAG_WARNING_CC",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen cc",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Svaradresse",
								"name" => "AKDTU_HAVEDAG_WARNING_REPLYTO",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"Efterlad tom for ikke at sætte nogen svaradresse",
									"Kan skrives ind som <code>Bestyrelsen &#60;bestyrelsen@akdtu.dk&#62;</code> eller <code>bestyrelsen@akdtu.dk</code>",
								),
							),
							array(
								"headline" => "Emne",
								"name" => "AKDTU_HAVEDAG_WARNING_SUBJECT",
								"tag" => "input",
								"type" => "text",
								"style" => "width: 300px",
								"comments" => array(
									"<code>#SEASON</code> erstattes med <code>forår</code> eller <code>efterår</code>.",
									"<code>#YEAR</code> erstattes med året.",
								),
							),
							array(
								"headline" => "Vedhæftede filer",
								"name" => "AKDTU_HAVEDAG_WARNING_ATTACHMENTS",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Skal være relativt til <code>" . website_root_folder() . "</code>. Skal starte med <code>/</code>",
									"Flere vedhæftede filer adskilles med <code>,</code>",
								),
							),
							array(
								"headline" => "Mail indhold",
								"name" => "AKDTU_HAVEDAG_WARNING_MAILCONTENT",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"<code>#PAYMENT_INFO</code> erstattes med info omkring hvem der skal betale for ikke at have mødt op til havedagene. Formatet for dette kan rettes nedenunder.",
									"<code>#SEASON</code> erstattes med <code>forår</code> eller <code>efterår</code>.",
									"<code>#YEAR</code> erstattes med år.",
								),
							),
							array(
								"headline" => "Format for info om betaling",
								"name" => "AKDTU_HAVEDAG_WARNING_FORMAT",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Dette gentages for hver lejlighed, der skal opkræves for manglende deltagelse i den seneste havedag, med linjeskift mellem.",
									"<code>#APT</code> erstattes med lejlighedsnummer. Hvis der efter deadline for tilmelding til havedagen er flyttet en ny beboer ind står dette også herefter som <code>(Tidligere beboer)</code>.",
									"<code>#PRICE</code> erstattes med pris.",
									"<code>#BOARDMEMBER</code> erstattes med hvad der er skrevet nedenunder, hvis lejligheden tilhører et bestyrelsesmedlem.",
								),
							),
							array(
								"headline" => "Format for bestyrelsesmedlemmer",
								"name" => "AKDTU_HAVEDAG_BOARDMEMBER",
								"tag" => "textarea",
								"type" => "text",
								"rows" => 5,
								"cols" => 50,
								"style" => "",
								"comments" => array(
									"Dette er hvad <code>#BOARDMEMBER</code> erstattes med i ovenstående format for info om betaling."
								),
							),
						),
					),
				),
			),
			"test" => array(
				"tab-title" => "Afprøv",
				"tab-type" => "test",
				"includes" => array(
					
				),
				"function-calls" => array(
					"send_opkrævning_havedag(true);",
				)
			),
		),
	),
);

add_action('admin_menu', 'AKDTU_menu');

function AKDTU_menu() {
	global $AKDTU_OPTIONS;

	add_menu_page('AKDTU', 'AKDTU', 'add_users', 'akdtu-plugin-fælleshus-opkrævning', '', 'dashicons-admin-tools', 65);

	foreach ($AKDTU_OPTIONS as $option_file => $option_spec) {
		include_once "options/" . $option_file;

		add_submenu_page('akdtu-plugin-fælleshus-opkrævning', $option_spec["page-title"], $option_spec["menu-title"], 'add_users', $option_spec["menu-slug"], $option_spec["function"], 'dashicons-admin-tools', 65);
	}
}

?>