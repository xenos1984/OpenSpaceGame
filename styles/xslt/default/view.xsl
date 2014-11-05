<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" omit-xml-declaration="yes" doctype-system="about:legacy-compat" encoding="UTF-8" indent="yes"/>

	<xsl:template match="view">
		<xsl:variable name="curpos" select="positions/position[@galaxy = /view/positions/@curg and @solsys = /view/positions/@curs and @orbit = /view/positions/@curo and @celb = /view/positions/@curc]"/>
		<html>
			<head>
				<title>OpenSpaceGame</title>
				<link rel="stylesheet" type="text/css" href="{concat(@css, 'view.css')}"/>
			</head>
			<body>
				<div id="menu">
				</div>
				<div id="poslist">
					<table class="poslist">
						<tr>
							<td rowspan="3" class="posmed {$curpos/@tid}" title="{$curpos/@name}"/>
							<td class="poslist">
								<input class="poslist" type="button" value="&lt;&lt;" accesskey="-">
									<xsl:attribute name="onclick">
										<xsl:choose>
											<xsl:when test="$curpos/preceding-sibling::position">
												<xsl:call-template name="jumplink">
													<xsl:with-param name="pos" select="$curpos/preceding-sibling::position[position() = 1]"/>
												</xsl:call-template>
											</xsl:when>
											<xsl:otherwise>
												<xsl:call-template name="jumplink">
													<xsl:with-param name="pos" select="positions/position[last()]"/>
												</xsl:call-template>
											</xsl:otherwise>
										</xsl:choose>
									</xsl:attribute>
								</input>
							</td>
						</tr>
						<tr>
							<td class="poslist">
								<select size="1" class="poslist" onchange="location.href = location.href + '&amp;pos=' + this.options[this.selectedIndex].value">
									<xsl:for-each select="positions/position">
										<option class="posopt" value="{@galaxy}:{@solsys}:{@orbit}:{@celb}">
											<xsl:if test="generate-id(.) = generate-id($curpos)">
												<xsl:attribute name="selected">selected</xsl:attribute>
											</xsl:if>
											<xsl:value-of select="@name"/><xsl:text> (</xsl:text><xsl:value-of select="@tname"/><xsl:text>) </xsl:text><xsl:call-template name="coords"><xsl:with-param name="pos" select="."/></xsl:call-template>
										</option>
									</xsl:for-each>
								</select>
							</td>
						</tr>
						<tr>
							<td class="poslist">
								<input class="poslist" type="button" value="&gt;&gt;" accesskey="+">
									<xsl:attribute name="onclick">
										<xsl:choose>
											<xsl:when test="$curpos/following-sibling::position">
												<xsl:call-template name="jumplink">
													<xsl:with-param name="pos" select="$curpos/following-sibling::position[position() = 1]"/>
												</xsl:call-template>
											</xsl:when>
											<xsl:otherwise>
												<xsl:call-template name="jumplink">
													<xsl:with-param name="pos" select="positions/position[1]"/>
												</xsl:call-template>
											</xsl:otherwise>
										</xsl:choose>
									</xsl:attribute>
								</input>
							</td>
						</tr>
					</table>
				</div>
				<div id="postab">
					<table class="postab">
						<xsl:for-each select="positions/position">
						<tr>
							<td>
								<input type="button" class="posmed {@tid}" title="{@name}">
									<xsl:if test="position() &lt;= 10">
										<xsl:attribute name="accesskey">
											<xsl:value-of select="position() mod 10"/>
										</xsl:attribute>
									</xsl:if>
									<xsl:attribute name="value">
										<xsl:call-template name="coords">
											<xsl:with-param name="pos" select="."/>
										</xsl:call-template>
									</xsl:attribute>
									<xsl:attribute name="onclick">
										<xsl:call-template name="jumplink">
											<xsl:with-param name="pos" select="."/>
										</xsl:call-template>
									</xsl:attribute>
								</input>
							</td>
						</tr>
						</xsl:for-each>
					</table>
				</div>
				<div id="restab">
					<table class="res">
						<tr>
							<xsl:for-each select="resources/resource">
								<td class="respic {@id}"/>
							</xsl:for-each>
						</tr>
						<tr>
							<xsl:for-each select="resources/resource">
								<td class="resname">
									<xsl:value-of select="@name"/>
								</td>
							</xsl:for-each>
						</tr>
					</table>
				</div>
				<div id="logo"/>
				<div id="content">
				</div>
			</body>
		</html>
	</xsl:template>

	<xsl:template name="coords">
		<xsl:param name="pos"/>
		<xsl:text>[</xsl:text><xsl:value-of select="$pos/@galaxy"/><xsl:text>:</xsl:text><xsl:value-of select="$pos/@solsys"/><xsl:text>:</xsl:text><xsl:value-of select="$pos/@orbit"/><xsl:text>:</xsl:text><xsl:value-of select="$pos/@celb"/><xsl:text>]</xsl:text>
	</xsl:template>

	<xsl:template name="jumplink">
		<xsl:param name="pos"/>
		<xsl:text>location.href = location.href + '&amp;pos=</xsl:text><xsl:value-of select="$pos/@galaxy"/><xsl:text>:</xsl:text><xsl:value-of select="$pos/@solsys"/><xsl:text>:</xsl:text><xsl:value-of select="$pos/@orbit"/><xsl:text>:</xsl:text><xsl:value-of select="$pos/@celb"/><xsl:text>'</xsl:text>
	</xsl:template>

</xsl:stylesheet>
