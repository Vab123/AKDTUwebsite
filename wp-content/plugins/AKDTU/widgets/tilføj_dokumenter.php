<?php

/**
 * @file Widget to add board documents to the website
 */

function tilføj_dokument_dashboard_widget() {
	include WP_PLUGIN_DIR . '/AKDTU/functions/bestyrelsesdokumenter.php';
?>
	<table id="tilføj_dokument_widget_table" width="100%">
		<colgroup>
			<col span="1" style="width: 30%" />
			<col span="1" style="width: 70%" />
		</colgroup>
		<thead>
			<tr>
				<td><label>Dokument type:</label></td>
				<td><select name="document_type" onchange="update_upload_document_display(this.value)">
						<?php
						foreach ($bestyrelsesdocuments_document_types as $short => $type_def) {
							echo '<option value="' . $short . '">' . $type_def['name'] . '</option>';
						}
						?>
					</select></td>
			</tr>
		</thead>
		<tbody>
			<?php $is_not_first = false;
			foreach ($bestyrelsesdocuments_document_types as $short => $type_def) : ?>
				<form action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="action" value="upload_dokument" />
					<input type="hidden" name="document_type" value="<?php echo $short; ?>" />
					<tr class="dokument_upload_options" id="document_<?php echo $short; ?>_date" <?php if ($is_not_first) {
																												echo 'style="visibility:collapse"';
																											}; ?>>
						<td><label>Dokument dato:</label></td>
						<td><input type="date" name="document_date" /></td>
					</tr>
					<?php if ($type_def['has_type']) : ?>
						<tr class="dokument_upload_options" id="document_<?php echo $short; ?>_typetype" <?php if ($is_not_first) {
																														echo 'style="visibility:collapse"';
																													}; ?>>
							<td><label>Type:</label></td>
							<td><select name="document_typetype">
									<?php
									foreach ($type_def['type_options'] as $short_type => $long_type) {
										echo '<option value="' . $short_type . '">' . $long_type . '</option>';
									}
									?>
								</select></td>
						</tr>
					<?php endif; ?>
					<tr class="dokument_upload_options" id="document_<?php echo $short; ?>_file" <?php if ($is_not_first) {
																												echo 'style="visibility:collapse"';
																											}; ?>>
						<td><label>Fil:</label></td>
						<td><input type="file" name="file" accept="application/pdf" /></td>
					</tr>
					<tr class="dokument_upload_options" id="document_<?php echo $short; ?>_submit" <?php if ($is_not_first) {
																												echo 'style="visibility:collapse"';
																											}; ?>>
						<td></td>
						<td><input type="submit" class="button-secondary" value="Upload"></td>
					</tr>
				</form>
			<?php $is_not_first = true;
			endforeach; ?>
		</tbody>
	</table>
	<script>
		function update_upload_document_display(type) {
			let options = document.getElementsByClassName("dokument_upload_options");
			for (let option of options) {
				option.style.visibility = "collapse";
			}
			if (!!(document.getElementById("document_" + type + "_date"))) {
				document.getElementById("document_" + type + "_date").style.visibility = "initial";
			}
			if (!!(document.getElementById("document_" + type + "_file"))) {
				document.getElementById("document_" + type + "_file").style.visibility = "initial";
			}
			if (!!(document.getElementById("document_" + type + "_typetype"))) {
				document.getElementById("document_" + type + "_typetype").style.visibility = "initial";
			}
			if (!!(document.getElementById("document_" + type + "_submit"))) {
				document.getElementById("document_" + type + "_submit").style.visibility = "initial";
			}
		}
	</script>
<?php
}

?>