<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#syntaxhl_dlg.title}</title>

	<style type="text/css" media="screen">
		input {
			vertical-align: middle;
		}
		label {
			vertical-align: middle;
		}
		fieldset {
			margin-bottom: 10px;
		}
	</style>
<?php
  $path = $_GET["path"];
  print " <script type=\"text/javascript\" src=\"$path/tiny_mce_popup.js\"></script>";
?>
	<script type="text/javascript" src="js/dialog.js"></script>
  
</head>
<body>
<form name="syntaxhl" onsubmit="SyntaxHLDialog.insert(); return false;" action="#">
	<fieldset id="syntaxhl_options">
		<legend>{#syntaxhl_dlg.highlight_options}</legend>
		<input type="checkbox" name="syntaxhl_nogutter" id="syntaxhl_nogutter" value="1" /><label for="syntaxhl_nogutter" >{#syntaxhl_dlg.nogutter}</label>	
		<input type="checkbox" name="syntaxhl_light" id="syntaxhl_light" value="1" /><label for="syntaxhl_light">{#syntaxhl_dlg.light}</label>
		<input type="checkbox" name="syntaxhl_collapse" id="syntaxhl_collapse" value="1" /><label for="syntaxhl_collapse">{#syntaxhl_dlg.collapse}</label>
		<label for="syntaxhl_highlight">{#syntaxhl_dlg.highlight}</label><input type="text" name="syntaxhl_highlight" id="syntaxhl_highlight" style="width:50px;" /><br />
		<label for="syntaxhl_language">{#syntaxhl_dlg.choose_lang}:</label>
		<select name="syntaxhl_language" id="syntaxhl_language">
		</select>
		<label for="syntaxhl_firstline" style="margin-left: 15px;">{#syntaxhl_dlg.first_line}:</label><input type="textfield" name="syntaxhl_firstline" id="syntaxhl_firstline" value="1" style="width:20px;" />
		<label for="syntaxhl_fontsize">{#syntaxhl_dlg.fontsize}</label><input type="text" name="syntaxhl_fontsize" id="syntaxhl_fontsize" value="100" style="width:25px;" />%
	</fieldset>
	<fieldset>
		<legend>{#syntaxhl_dlg.paste}</legend>
	<textarea name="syntaxhl_code" id="syntaxhl_code" rows="15" cols="100" style="width: 100%; height: 100%; font-family: 'Courier New',Courier,mono; font-size: 12px;" class="mceFocus"></textarea>
	</fieldset>
	<div class="mceActionPanel">
		<div style="float: left">
			<input type="submit" id="insert" name="insert" value="{#insert}" />
		</div>
		<div style="float: right">
			<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
		</div>
	</div>
</form>
</body>
</html>
