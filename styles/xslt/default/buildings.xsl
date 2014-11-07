<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:include href="view.xsl"/>

	<xsl:template match="buildings">
		<table class="tablefix">
			<tr>
				<th class="tabimage"></th>
				<th class="tabtext"><xsl:value-of select="$trans[@key = 'name']"/></th>
				<th class="tabcur"><xsl:value-of select="$trans[@key = 'level']"/></th>
				<xsl:if test="building[@planlevel &gt; @curlevel]">
					<th class="tabplan"><xsl:value-of select="$trans[@key = 'planned']"/></th>
				</xsl:if>
				<th class="tabcost" colspan="2"><xsl:value-of select="$trans[@key = 'costs']"/></th>
				<th class="tabremain" colspan="2"><xsl:value-of select="$trans[@key = 'req']"/></th>
				<th class="tabtime"><xsl:value-of select="$trans[@key = 'dur']"/></th>
				<th class="tabaction"></th>
			</tr>
		</table>
	</xsl:template>

</xsl:stylesheet>
