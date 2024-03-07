<?php

#####################################
### TAG DEFINITION
###
add_action( 'wpcf7_init', 'wpcf7_add_form_tag_datetime' );
###
function wpcf7_add_form_tag_datetime() {
  wpcf7_add_form_tag(
    array('datetime', 'datetime*'),
    'wpcf7_datetime_handler',
    array( 'name-attr' => true )
  );
}
###
function wpcf7_datetime_handler( $tag ) {
	$validation_error = wpcf7_get_validation_error( $tag->name );

	$class = wpcf7_form_controls_class( $tag->type );

	$class .= ' wpcf7-validates-as-date';

	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
	}

	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_id_option();
	$atts['step'] = $tag->get_option( 'step', 'int', true );

	$atts['min'] = $tag->get_option( 'min' , '' , true );
	if ($atts['min']) {
		$atts['min'] = new DateTime($atts['min'], new DateTimeZone('Europe/Copenhagen'));
		$atts['min'] = $atts['min']->format("Y-m-d") . "T" . $atts['min']->format('H:i');
	}
	$atts['max'] = $tag->get_option( 'max' , '' , true );
	if ($atts['max']) {
		$atts['max'] = new DateTime($atts['max'], new DateTimeZone('Europe/Copenhagen'));
		$atts['max'] = $atts['max']->format("Y-m-d") . "T" . $atts['max']->format('H:i');
	}

	if ( $tag->is_required() ) {
		$atts['aria-required'] = 'true';
	}

	if ( $validation_error ) {
		$atts['aria-invalid'] = 'true';
		$atts['aria-describedby'] = wpcf7_get_validation_error_reference(
			$tag->name
		);
	} else {
		$atts['aria-invalid'] = 'false';
	}

	$value = (string) reset( $tag->values );

	$value = $tag->get_default_option( $value );

	if ( $value ) {
		$datetime_obj = new DateTime($value, new DateTimeZone('Europe/Copenhagen'));

		if ( $datetime_obj ) {
			$value = $datetime_obj->format( 'Y-m-d H:i' );
		}
	}

	$value = wpcf7_get_hangover( $tag->name, $value );

	$atts['value'] = $value;
	$atts['type'] = 'datetime-local';
	$atts['name'] = $tag->name;

	$html = sprintf(
		'<span class="wpcf7-form-control-wrap" data-name="%1$s"><input %2$s />%3$s</span>',
		esc_attr( $tag->name ),
		wpcf7_format_atts( $atts ),
		$validation_error
	);

	return $html;
}
###
#####################################


#####################################
### REPLACEMENT IN EMAIL
###
add_filter( 'wpcf7_mail_tag_replaced_datetime', 'email_replace_datetime', 10, 4 );
add_filter( 'wpcf7_mail_tag_replaced_datetime*', 'email_replace_datetime', 10, 4 );
##
function email_replace_datetime( $replaced, $submitted, $html, $mail_tag ) {
	if ($mail_tag->values()[0]) {
		$replaced = wp_date($mail_tag->values()[0], (new DateTime($submitted, new DateTimeZone('Europe/Copenhagen')))->getTimestamp() );
	} else {
		$replaced = wp_date("d-m-Y H:i", (new DateTime($submitted, new DateTimeZone('Europe/Copenhagen')))->getTimestamp() );
	}

    return $replaced;
}
###
#####################################





#####################################
### DATA VALIDATION
###
add_filter( 'wpcf7_validate_datetime', 'check_min_datetime_confirmation_validation_filter', 20, 2 );
add_filter( 'wpcf7_validate_datetime*', 'check_min_datetime_confirmation_validation_filter', 20, 2 );
###
add_filter( 'wpcf7_validate_datetime', 'check_max_datetime_confirmation_validation_filter', 20, 2 );
add_filter( 'wpcf7_validate_datetime*', 'check_max_datetime_confirmation_validation_filter', 20, 2 );
###
function check_min_datetime_confirmation_validation_filter( $result, $tag ) {
	if ($tag->get_option('min', '', true)) {
		$min = new DateTime($tag->get_option('min', '', true), new DateTimeZone('Europe/Copenhagen'));
		$submitted = new DateTime($_POST[$tag->name], new DateTimeZone('Europe/Copenhagen'));

		if ($submitted->getTimestamp() < $min->getTimestamp()) {
			$result->invalidate( $tag, sprintf(pll_translate_string('The date should be after %1$s-%2$s-%3$s %4$s:%5$s:%6$s but is %7$s-%8$s-%9$s %10$s:%11$s:%12$s', $_POST['_wpcf7_locale']), $min->format("d"), $min->format("m"), $min->format("Y"), $min->format("H"), $min->format("i"), $min->format("s"), $submitted->format("d"), $submitted->format("m"), $submitted->format("Y"), $submitted->format("H"), $submitted->format("i"), $submitted->format("s") ) );
		}
	}
  
  return $result;
}
###
function check_max_datetime_confirmation_validation_filter( $result, $tag ) {
	if ($tag->get_option('max', '', true)) {
		$max = new DateTime($tag->get_option('max', '', true), new DateTimeZone('Europe/Copenhagen'));
		$submitted = new DateTime($_POST[$tag->name], new DateTimeZone('Europe/Copenhagen'));

		if ($submitted->getTimestamp() > $max->getTimestamp()) {
			$result->invalidate( $tag, sprintf(pll_translate_string('The date should be before %1$s-%2$s-%3$s %4$s:%5$s:%6$s but is %7$s-%8$s-%9$s %10$s:%11$s:%12$s', $_POST['_wpcf7_locale']), $max->format("d"), $max->format("m"), $max->format("Y"), $max->format("H"), $max->format("i"), $max->format("s"), $submitted->format("d"), $submitted->format("m"), $submitted->format("Y"), $submitted->format("H"), $submitted->format("i"), $submitted->format("s") ) );
		}
	}
  
  return $result;
}
###
#####################################





#####################################
### TAG GENERATOR - FORM EDITOR
###
add_action( 'wpcf7_admin_init', 'wpcf7_add_tag_generator_datetime', 19, 0 );

function wpcf7_add_tag_generator_datetime() {
	$tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->add( 'datetime', "datetime",
		'wpcf7_tag_generator_datetime' );
}
###
function wpcf7_tag_generator_datetime( $contact_form, $args = '' ) {
	$args = wp_parse_args( $args, array() );
	$type = 'datetime';

	$description = __( "Generate a form-tag for a date input field. For more details, see %s.", 'contact-form-7' );

	$desc_link = wpcf7_link( __( 'https://contactform7.com/date-field/', 'contact-form-7' ), __( 'Date field', 'contact-form-7' ) );

?>
<div class="control-box">
<fieldset>
<legend><?php echo sprintf( esc_html( $description ), $desc_link ); ?></legend>

<table class="form-table">
<tbody>
	<tr>
	<th scope="row"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></th>
	<td>
		<fieldset>
		<legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></legend>
		<label><input type="checkbox" name="required" /> <?php echo esc_html( __( 'Required field', 'contact-form-7' ) ); ?></label>
		</fieldset>
	</td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
	</tr>
</tbody>
</table>
</fieldset>
</div>

<div class="insert-box">
	<input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

	<div class="submitbox">
	<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
	</div>

	<br class="clear" />

	<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
</div>

<?php

}
###
#####################################

?>