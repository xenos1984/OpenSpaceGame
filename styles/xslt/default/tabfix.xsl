<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template name="tablefix">
		<xsl:param name="entry"/>
		<tr id="{$entry/@id}">
			<td class="tabimage {$entry/@id} level{$entry/@curlevel}" title="{$entry/@name}" rowspan="{count($entry/cost)}"></td>
			<td class="tabtext" rowspan="{count($entry/cost)}">
				<span class="tabname">
					<xsl:value-of select="$entry/@name"/>
				</span><!--
				<br/>
				<span class="tabdesc">
					<xsl:value-of select="$entry/description"/>
				</span>-->
			</td>
			<td class="tabcur" rowspan="{count($entry/cost)}">
				<xsl:value-of select="$entry/@curlevel"/>
			</td>
			<xsl:if test="/view/buildings/building[@planlevel &gt; @curlevel]">
				<td class="tabplan" rowspan="{count($entry/cost)}">
					<xsl:if test="$entry/@planlevel &gt; $entry/@curlevel">
						<xsl:value-of select="$entry/@planlevel"/>
					</xsl:if>
				</td>
			</xsl:if>
			<xsl:apply-templates select="$entry/cost[1]"/>
			<td class="tabtime" rowspan="{count($entry/cost)}">
				<xsl:call-template name="time"><xsl:with-param name="value" select="$entry/@time"/></xsl:call-template>
			</td>
			<td class="tabaction" rowspan="{count($entry/cost)}">
				<xsl:choose>
					<xsl:when test="/view/buildings/building[@planlevel &gt; @curlevel]">
						<xsl:value-of select="$trans[@key = 'level']"/>
						<xsl:text> </xsl:text>
						<xsl:value-of select="$entry/@planlevel + 1"/>
					</xsl:when>
					<xsl:otherwise>
						<xsl:variable name="costok">
							<xsl:for-each select="$entry/cost">
								<xsl:variable name="id" select="@id"/>
								<xsl:variable name="res" select="/view/resources/resource[@id = $id]"/>
								<xsl:if test="@value &gt; $res/@present">1</xsl:if>
							</xsl:for-each>
						</xsl:variable>
						<xsl:choose>
							<xsl:when test="$costok = ''">
								<a href="#" class="costgood">
									<xsl:value-of select="$trans[@key = 'level']"/>
									<xsl:text> </xsl:text>
									<xsl:value-of select="$entry/@curlevel + 1"/>
								</a>
							</xsl:when>
							<xsl:otherwise>
								<span class="costbad"><xsl:value-of select="$trans[@key = 'notpos']"/></span>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
		<xsl:for-each select="$entry/cost[position() > 1]">
			<tr>
				<xsl:apply-templates select="."/>
			</tr>
		</xsl:for-each>
	</xsl:template>

	<xsl:template match="cost">
		<xsl:variable name="id" select="@id"/>
		<xsl:variable name="res" select="/view/resources/resource[@id = $id]"/>
		<td class="costname"><xsl:value-of select="$res/@name"/>:</td>
		<xsl:choose>
			<xsl:when test="@value &gt; $res/@present">
				<td class="costbad">
					<xsl:value-of select="@value"/>
				</td>
				<td class="costmiss">
					<xsl:value-of select="@value - $res/@present"/>
				</td>
				<td>
					<xsl:choose>
						<xsl:when test="$res/@produced = 0">
							<xsl:attribute name="class">costbad</xsl:attribute>
							<xsl:value-of select="$trans[@key = 'noprod']"/>
						</xsl:when>
						<xsl:otherwise>
							<xsl:attribute name="class">
								<xsl:choose>
									<xsl:when test="@value &gt; $res/@storage">costbad</xsl:when>
									<xsl:otherwise>costmiss</xsl:otherwise>
								</xsl:choose>
							</xsl:attribute><!--
							<xsl:call-template name="countdown">
								<xsl:with-param name="id" select="concat('prod', generate-id(.))"/>
								<xsl:with-param name="value" select="/*/@servertime + floor(3600 * (@value - $res/@present) div $res/@produced)"/>
							</xsl:call-template>-->
						</xsl:otherwise>
					</xsl:choose>
				</td>
			</xsl:when>
			<xsl:otherwise>
				<td class="costgood">
					<xsl:value-of select="@value"/>
				</td>
				<td class="costmiss"></td>
				<td class="costremain"></td>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

</xsl:stylesheet>
