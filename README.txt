===============================================================================
TYPO3 Extension ReadSpeaker
===============================================================================
Author: Oliver Hader <oliver.hader@typo3.org>
License: GPL v2 or any later version of that license
-------------------------------------------------------------------------------


Installation
------------

The installation of this extension is really simple. Just enable the plugin and
include the static TypoScript "ReadSpeaker" to your TypoScript template in the
TYPO3 backend.

By using the constant editor you need to specify several settings, like e.g.
your ReadSpeaker clientId (see your ReadSpeaker contract for details).


Defining render position by TypoScript
--------------------------------------

Integration into your website can be done by defining the "renderTo" setting,
this causes the extension to lookup an accordant element with that CSS id and
includes the ReadSpeaker widget as first child element inside that element.

	Example:

	<div id="readspeaker-renderTo">
		... my content ...
	</div>

	will then become

	<div id="readspeaker-renderTo">
		<div id="tx-readspeaker-widget-1" class="tx-readspeaker-widget rs_skip">
		... my content ...
	</div>


Defining render position by template marker
-------------------------------------------

Additionally to setting the "renderTo" property, you can please a marker in
your HTML template file that get's replaced by the ReadSpeaker widget.

	Example:

	<div>
		###READSPEAKER_WIDGET###
		... my content ...
	</div>

	will then become

	<div>
		<div id="tx-readspeaker-widget-1" class="tx-readspeaker-widget rs_skip">
		... my content ...
	</div>


Support & Additions
-------------------

If you have further questions to this TYPO3 extension, please use the issue
tracker in the accordant Forge-Project:

	http://forge.typo3.org/projects/extension-readspeaker/issues


Next steps
----------

Add proper documentation and unit-test to this extension.

===============================================================================
