<?xml version="1.0" encoding="UTF-8"?>
<!--
/**
 * OrangeHRM Enterprise is a closed sourced comprehensive Human Resource Management (HRM)
 * System that captures all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM Inc is the owner of the patent, copyright, trade secrets, trademarks and any
 * other intellectual property rights which subsist in the Licensed Materials. OrangeHRM Inc
 * is the owner of the media / downloaded OrangeHRM Enterprise software files on which the
 * Licensed Materials are received. Title to the Licensed Materials and media shall remain
 * vested in OrangeHRM Inc. For the avoidance of doubt title and all intellectual property
 * rights to any design, new software, new protocol, new interface, enhancement, update,
 * derivative works, revised screen text or any other items that OrangeHRM Inc creates for
 * Customer shall remain vested in OrangeHRM Inc. Any rights not expressly granted herein are
 * reserved to OrangeHRM Inc.
 *
 * You should have received a copy of the OrangeHRM Enterprise  proprietary license file along
 * with this program; if not, write to the OrangeHRM Inc. 538 Teal Plaza, Secaucus , NJ 0709
 * to get the file.
 *
 */

-->
<!DOCTYPE xsl:stylesheet  [
	<!ENTITY nbsp   "&#160;">
	<!ENTITY copy   "&#169;">
	<!ENTITY reg    "&#174;">
	<!ENTITY trade  "&#8482;">
	<!ENTITY mdash  "&#8212;">
	<!ENTITY ldquo  "&#8220;">
	<!ENTITY rdquo  "&#8221;">
	<!ENTITY pound  "&#163;">
	<!ENTITY yen    "&#165;">
	<!ENTITY euro   "&#8364;">
]>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="UTF-8" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"/>
<xsl:template match="/report">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="{Wroot}/themes/{stylesheet}/icons/exceptions/{type}.png" rel="icon" type="image/png"/>
<title>System was brought to a halt</title>
</head>
<style type="text/css">
	@import url(<xsl:value-of select="Wroot"/>/themes/<xsl:value-of select="stylesheet"/>/css/error.css);

	h2 {
		background: url(<xsl:value-of select="Wroot"/>/themes/beyondT/icons/exceptions/<xsl:value-of select="type"/>.png) no-repeat left center;
	}
</style>
<script language="javascript">
	parent.scrollTo(0, 0);
</script>
<body>
	<div id="error_body">
		<h2>
		<xsl:value-of select="heading"/>
		</h2>

		<p class="diagnosis">
			<xsl:value-of select="message"/>
		</p>

		<p class="diagnosis">
	  	<h3>Technical Details</h3>
			<xsl:for-each select="cause">
				<xsl:value-of select="message"/>&nbsp;
			</xsl:for-each>
		</p>
		<p class="environment">
	  		<h3>System Environment</h3>
	  		<ul>
			<xsl:for-each select="environment/version">
				<li id="{@type}" ><xsl:value-of select="@description" /> : <xsl:value-of select="."/></li>
			</xsl:for-each>
			<xsl:for-each select="environment/info">
				<li id="{@type}" ><xsl:value-of select="@description" /> : <xsl:value-of select="."/></li>
			</xsl:for-each>
			</ul>
		</p>
		<cite>
		<h5>Please note</h5>
		The error was logged in the OrangeHRM log located in <span class="code"><xsl:value-of select="logPath"/>logDB.txt</span></cite>
		<p>If you are unable to resolve the problem please post the problem in <a href="http://www.orangehrm.com/forum/">OrangeHRM Forum</a></p>
	</div>
</body>
</html>
</xsl:template>
</xsl:stylesheet>
