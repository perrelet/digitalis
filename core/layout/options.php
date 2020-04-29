<?php
	$setting_group = DIGITALIS_OPTION_GROUP . "test_group";
	$setting = DIGITALIS_OPTION . "test_setting";
	$setting_title = "Test Setting";
?>
<h1>Digitalis Options</h1>
<form method="post" action="options.php">
	<?php settings_fields($setting_group); ?>
	<?php do_settings_sections($setting_group); ?>
	<table>
		<tr valign="top">
			<th scope="row"><label for="<?php echo $setting; ?>"><?php echo $setting_title; ?></label></th>
			<td><input type="checkbox" id="<?php echo $setting; ?>" name="<?php echo $setting; ?>" value="true" <?php echo checked(get_option($setting), "true"); ?>></td>
		</tr>
	</table>
	<?php submit_button(); ?>
</form>