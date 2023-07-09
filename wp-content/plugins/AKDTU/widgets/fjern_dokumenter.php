<?php

function fjern_dokument_dashboard_widget() {
	include WP_PLUGIN_DIR . '/AKDTU/functions/bestyrelsesdokumenter.php';
?>
	<table id="fjern_dokument_widget_table" class="widefat" width="100%">
		<colgroup>
			<col span="1" style="width: 70%" />
			<col span="1" style="width: 30%" />
		</colgroup>
		<thead>
			<tr>
				<td><label>Dokument type:</label></td>
				<td><select name="document_type" onchange="update_remove_document_display(this.value)">
						<?php
						foreach ($bestyrelsesdocuments_document_types as $type) {
							echo '<option value="' . $type['short'] . '">' . $type['name'] . '</option>';
						}
						?>
					</select></td>
			</tr>
		</thead>
		<tbody>
			<?php $is_not_first = false;
			foreach ($bestyrelsesdocuments_document_types as $type => $typearray) :
				$files = array_filter(scandir($typearray['folder'], 1), function($item) {
					return !is_dir($typearray['folder'] . $item);
				});
				$odd_entry = true;
				foreach ($files as $file) : ?>
				<tr class="dokument_remove_options dokument_remove_options_<?php echo $type; if ($odd_entry) {echo ' alternate';}; ?>" <?php if ($is_not_first) { echo 'style="visibility:collapse"';}; ?>>
					<form action="" method="post" enctype="multipart/form-data">
						<input type="hidden" name="action" value="remove_dokument" />
						<input type="hidden" name="document_type" value="<?php echo $typearray['short']; ?>" />
						<input type="hidden" name="document_name" value="<?php echo $file; ?>" />
					
						<td style="vertical-align:middle;"><?php echo $file; ?></td>
						<td><input type="submit" style="vertical-align:middle;" class="button-secondary" value="Slet"></td>
					</form>
				</tr>
			<?php $odd_entry = !$odd_entry;
			endforeach;
			$is_not_first = true;
			endforeach; ?>
		</tbody>
	</table>
	<script>
		function update_remove_document_display(type) {
			let all_documents = document.getElementsByClassName("dokument_remove_options");
			for (let document of all_documents) {
				document.style.visibility = "collapse";
			}

			let display_documents = document.getElementsByClassName("dokument_remove_options_" + type);
			for (let document of display_documents) {
				document.style.visibility = "visible";
			}
		}
	</script>
<?php
}

?>