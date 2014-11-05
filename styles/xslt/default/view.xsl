<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" omit-xml-declaration="yes" doctype-system="about:legacy-compat" encoding="UTF-8" indent="yes"/>

	<xsl:template match="view">
		<html>
			<head>
				<title>OpenSpaceGame</title>
			</head>
			<body>
				<div id="menu">
				</div>
				<div id="poslist">
				</div>
				<div id="postab">
					<table class="postab">
						<xsl:for-each select="positions/position">
						<tr>
							<td>
								<input type="button" class="posmed {@tid}" title="{@name}">
									<xsl:if test="position() &lt;= 10">
										<xsl:attribute name="accesskey">
											<xsl:value-of select="position() - 1"/>
										</xsl:attribute>
									</xsl:if>
									<xsl:attribute name="value">
										<xsl:call-template name="coords">
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
		<xsl:text>[</xsl:text><xsl:value-of select="$pos/@galaxy"/>:<xsl:value-of select="$pos/@solsys"/>:<xsl:value-of select="$pos/@orbit"/>:<xsl:value-of select="$pos/@celb"/><xsl:text>]</xsl:text>
	</xsl:template>

</xsl:stylesheet>
