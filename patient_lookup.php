<?php
	// $Id$
	// $Author$

$page_name = "patient_lookup.php";
include_once("lib/freemed.php");

//----- Open database, authenticate, etc
freemed_open_db ();
$this_user = CreateObject('FreeMED.User');

//----- Check for process
if ($action==_("Search")) {
	$display_buffer .= "<BODY onLoad=\"process(); return true;\">\n";
}

//----- Form header
$display_buffer .= "<CENTER><FORM NAME=\"lookup\" ACTION=\"".$page_name."\" ".
	"METHOD=\"POST\">\n";

//----- Master action switch
switch ($action) {
	case _("Search"):
	// Perform query

//	if ( empty($last_name) and empty($first_name) and
//		empty(html_form::combo_assemble("city")) ) break;

	unset ( $wheres );
	if (!empty($last_name))
		$wheres[] = "LCASE(ptlname) LIKE '".addslashes(
			strtolower($last_name))."%'";
	if (!empty($first_name))
		$wheres[] = "LCASE(ptfname) LIKE '".addslashes(
			strtolower($first_name))."%'";
	if (!empty($city))
		$wheres[] = "ptcity = '".addslashes(
			html_form::combo_assemble("city"))."'";

	$query = "SELECT * FROM patient WHERE ".implode(", ", $wheres).
		"ORDER BY ptlname, ptfname, ptcity";
	$result = $sql->query($query);

	// If no results, die right here
	if (!$sql->results($result)) {
		$display_buffer .= _("No patients found with that criteria!");
		break;
	}

	// Handle immediate passing and closing
	if ($sql->num_rows($result)==1) {
		$r = $sql->fetch_array($result);
		$display_buffer .= "
		<SCRIPT LANGUAGE=\"Javascript\">
		function process () {
			var our_value = '".prepare($r[id])."'

			// Pass the variable
			opener.document.".prepare($formname).".".
			prepare($varname).".value = our_value
			
			// Show an alert with the patients' name
			//var x = alert ('The patient should be '+".
				"'".$r[ptfname].' '.
				$r[ptlname]."');

			// Submit name to null
			opener.document.".prepare($formname).".".prepare($submitname).
			".value = ''
			// Submit the form
			opener.document.forms.".prepare($formname).".submit();
			
			// Close the window
			window.self.close()
		}
		</SCRIPT>
		We should be '".$r[ptfname].' '.$r[ptlname]."'.
		";
		
		// Add to pick list
		$pick_list[(stripslashes($r[ptlname].", ".
			$r[ptfname]." (".
			$r[ptcity].", ".
			$r[ptstate].")"))] = $r[id];
	} else { // end handling only one result
		unset($pick_list);

		// Display pick list of results
		while ($r = $sql->fetch_array($result)) {
			$pick_list[(stripslashes($r[ptlname].", ".
				$r[ptfname]." (".
				$r[ptcity].", ".
				$r[ptstate].")"))] = $r[id];
		} // end looping through results
	}

	$display_buffer .= "
		<SCRIPT LANGUAGE=\"Javascript\">
		function my_process () {
			// Pass the variable
			opener.document.".prepare($formname).".".prepare($varname).
			".value = document.lookup.list.value

			// Submit name to null
			opener.document.".prepare($formname).".".prepare($submitname).
			".value = ''

			// Submit the form
			opener.document.".prepare($formname).".submit()
			
			// Close the window
			window.self.close()
		}
		</SCRIPT>
		<div ALIGN=\"CENTER\" CLASS=\"infobox\">
		".html_form::select_widget(
			"list",	$pick_list
		)."
		<input TYPE=\"BUTTON\" NAME=\"select\" ".
		"VALUE=\"Select\" onClick=\"my_process(); return true;\">
		</div>
	";
	break;

	default:
	$display_buffer .= "
		<INPUT TYPE=\"HIDDEN\" NAME=\"varname\" VALUE=\"".prepare($varname)."\">
		<INPUT TYPE=\"HIDDEN\" NAME=\"formname\" VALUE=\"".prepare($formname)."\">
		<INPUT TYPE=\"HIDDEN\" NAME=\"submitname\" VALUE=\"".prepare($submitname)."\">
		<input TYPE=\"HIDDEN\" NAME=\"action\" VALUE=\""._("Search")."\">
		<div ALIGN=\"CENTER\" CLASS=\"infobox\">
		".html_form::form_table(array(
			"Last Name" =>
			html_form::text_widget(
				"last_name", 20
			),
			
			"First Name" =>
			html_form::text_widget(
				"first_name", 20
			),

			"City" =>
			html_form::combo_widget(
				"city",
				$sql->distinct_values(
					"patient", "ptcity"
				)
			)
		))."
		<input TYPE=\"SUBMIT\" VALUE=\""._("Search")."\">
		</div>
	";
	break;
} // end switch

//----- End of form
$display_buffer .= "</FORM>\n";

//----- Display template
$no_template_display = true;
template_display();

?>
