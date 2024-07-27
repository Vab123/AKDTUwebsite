<?php

add_action( 'show_user_profile', 'AKDTU_define_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'AKDTU_define_custom_user_profile_fields' );

function AKDTU_define_custom_user_profile_fields( $user ) {
	$saved_apartment_number = get_user_meta( $user->ID, 'apartment_number', true );
	$saved_user_type = get_user_meta( $user->ID, 'user_type', true );
	$saved_knet_type = get_user_meta( $user->ID, 'knet_type', true );
	$saved_is_active = get_user_meta( $user->ID, 'is_active', true ); ?>
  <h3>AKDTU information</h3>

  <table class="form-table">
    <tr>
      <th><label for="aparment_number">Lejlighed</label></th>
      <td>
        <input type="number" name="apartment_number" id="apartment_number" value="<?php echo esc_attr($saved_apartment_number); ?>" />
      </td>
    </tr>
    <tr>
      <th><label for="user_type">Bruger-type</label></th>
      <td>
        <select name="user_type" id="user_type">
			<?php global $AKDTU_USER_TYPES;
			foreach ($AKDTU_USER_TYPES as $key => $definition): ?>
				<option value="<?php echo $definition["id"]; ?>"<?php echo $definition["id"] == $saved_user_type ? ' selected' : ''; ?>><?php echo $definition['name']; ?></option>
			<?php endforeach; ?>
		</select>
      </td>
    </tr>
    <tr>
      <th><label for="knet_type">Netværksgruppe-type</label></th>
      <td>
        <select name="knet_type" id="knet_type">
			<?php global $KNET_USER_TYPES;
			foreach ($KNET_USER_TYPES as $key => $definition): ?>
				<option value="<?php echo $definition["id"]; ?>"<?php echo $definition["id"] == $saved_knet_type ? ' selected' : ''; ?>><?php echo $definition['name']; ?></option>
			<?php endforeach; ?>
		</select>
      </td>
    </tr>
    <tr>
      <th><label for="is_active">Aktiv profil</label></th>
      <td>
        <input type="checkbox" name="is_active" id="is_active"<?php echo $saved_is_active ? ' checked' : ''; ?> />
      </td>
    </tr>
  </table>
<?php }

add_action( 'personal_options_update', 'AKDTU_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'AKDTU_save_custom_user_profile_fields' );

function AKDTU_save_custom_user_profile_fields( $user_id ) {
  if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
    return;
  }

  if ( !current_user_can( 'edit_user', $user_id ) ) {
    return;
  }

  update_user_meta( $user_id, 'user_type', $_POST['user_type'] );
  update_user_meta( $user_id, 'knet_type', $_POST['knet_type'] );
  update_user_meta( $user_id, 'apartment_number', $_POST['apartment_number'] );
  update_user_meta( $user_id, 'is_active', isset($_POST['is_active']) );
}