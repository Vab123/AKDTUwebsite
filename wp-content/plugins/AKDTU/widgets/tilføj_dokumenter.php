<?php

function tilføj_dokument_dashboard_widget() {
	$document_types = array(
		array(
			'short' => 'GF_REF',
			'name' => 'Referat af generalforsamling',
			'has_type' => true,
			'type_options' => array('OGF' => 'Ordinær', 'XGF' => 'Ekstraordinær')
		),
		array(
			'short' => 'board',
			'name' => 'Referat af bestyrelsesmøde',
			'has_type' => false,
			'type_options' => NULL
		),
		array(
			'short' => 'aar',
			'name' => 'Årsrapport',
			'has_type' => false,
			'type_options' => NULL
		),
		array(
			'short' => 'budget',
			'name' => 'Vedtaget budget',
			'has_type' => false,
			'type_options' => NULL
		)
	)
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
						foreach ($document_types as $type) {
							echo '<option value="' . $type['short'] . '">' . $type['name'] . '</option>';
						}
						?>
					</select></td>
			</tr>
		</thead>
		<tbody>
			<?php $is_not_first = false;
			foreach ($document_types as $type) : ?>
				<form action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="action" value="upload_dokument" />
					<input type="hidden" name="document_type" value="<?php echo $type['short']; ?>" />
					<tr class="dokument_upload_options" id="document_<?php echo $type['short']; ?>_date" <?php if ($is_not_first) {
																												echo 'style="visibility:collapse"';
																											}; ?>>
						<td><label>Dokument dato:</label></td>
						<td><input type="date" name="document_date" /></td>
					</tr>
					<?php if ($type['has_type']) : ?>
						<tr class="dokument_upload_options" id="document_<?php echo $type['short']; ?>_typetype" <?php if ($is_not_first) {
																														echo 'style="visibility:collapse"';
																													}; ?>>
							<td><label>Type:</label></td>
							<td><select name="document_typetype" onchange="update_upload_document_display(this.value)">
									<?php
									foreach ($type['type_options'] as $short => $long) {
										echo '<option value="' . $short . '">' . $long . '</option>';
									}
									?>
								</select></td>
						</tr>
					<?php endif; ?>
					<tr class="dokument_upload_options" id="document_<?php echo $type['short']; ?>_file" <?php if ($is_not_first) {
																												echo 'style="visibility:collapse"';
																											}; ?>>
						<td><label>Fil:</label></td>
						<td><input type="file" name="file" accept="application/pdf" /></td>
					</tr>
					<tr class="dokument_upload_options" id="document_<?php echo $type['short']; ?>_submit" <?php if ($is_not_first) {
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