<!--{* Smarty *}-->
<!--{*
 // $Id$
 //
 // Authors:
 //      Jeff Buchbinder <jeff@freemedsoftware.org>
 //
 // Copyright (C) 1999-2007 FreeMED Software Foundation
 //
 // This program is free software; you can redistribute it and/or modify
 // it under the terms of the GNU General Public License as published by
 // the Free Software Foundation; either version 2 of the License, or
 // (at your option) any later version.
 //
 // This program is distributed in the hope that it will be useful,
 // but WITHOUT ANY WARRANTY; without even the implied warranty of
 // MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 // GNU General Public License for more details.
 //
 // You should have received a copy of the GNU General Public License
 // along with this program; if not, write to the Free Software
 // Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
*}-->
<!--{*

	File:	org.freemedsoftware.widget.letterstemplates

	Reusable Letters Templates widget.

	Parameters:

		$varname - Variable name

		$inject - Name of Dojo widget to inject content into

*}-->
<script language="javascript">
	var <!--{$varname}-->_namespace = {
		use: function() {
			var v;
			try {
				v = document.getElementById('<!--{$varname}-->').value;
			} catch (err) {
				alert("<!--{t}-->No template was chosen!<!--{/t}-->");
				return false;
			}
			if ( parseInt( v ) < 1 ) {
				alert("<!--{t}-->No template was chosen!<!--{/t}-->");
				return false;
			}
			dojo.io.bind({
				method: 'POST',
				content: {
					param0: v
				},
				url: "<!--{$relay}-->/org.freemedsoftware.module.LettersTemplates.GetTemplate",
				load: function ( type, data, evt ) {
					dojo.widget.byId("<!--{$inject}-->").setValue( data );
				},
				mimetype: 'text/json'
			});
		}
	};
	_container_.addOnLoad(function(){
		dojo.event.connect( dojo.widget.byId('inject_<!--{$varname}-->'), 'onClick', <!--{$varname}-->_namespace, 'use' );
	});
	_container_.addOnUnload(function(){
		dojo.event.disconnect( dojo.widget.byId('inject_<!--{$varname}-->'), 'onClick', <!--{$varname}-->_namespace, 'use' );
	});
</script>
<table border="0" cellspacing="0" style="width: auto;">
	<tr>
		<td><!--{include file="org.freemedsoftware.widget.supportpicklist.tpl" varname=$varname module="LettersTemplates"}--></td>
		<td>
			<button dojoType="Button" id="inject_<!--{$varname}-->" widgetId="inject_<!--{$varname}-->">
				<!--{t}-->Use<!--{/t}-->
			</button>
		</td>
		<td>[ADD]</td>
	</tr>
</table>

