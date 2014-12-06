<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" omit-xml-declaration="yes" doctype-system="about:legacy-compat" encoding="UTF-8" indent="yes"/>

	<xsl:variable name="lang" select="/view/@lang"/>
	<xsl:variable name="i18n" select="document('i18n.xml')/i18n"/>
	<xsl:variable name="trans" select="$i18n/trans[position() = 1 or @lang = $lang][last()]/phrase"/>
	<xsl:variable name="curpos" select="/view/positions/position[@galaxy = /view/positions/@curg and @sun = /view/positions/@curs and @orbit = /view/positions/@curo and @celb = /view/positions/@curc]"/>

	<xsl:template match="view">
		<html>
			<head>
				<title>OpenSpaceGame</title>
				<link rel="stylesheet" type="text/css" href="{concat(@css, 'view.css')}"/>
				<script type="text/javascript" src="js/utilities.js"/>
				<script type="text/javascript">
					<xsl:text>servertime = </xsl:text>
					<xsl:value-of select="@servertime"/>
					<xsl:text>;</xsl:text>
				</script>
			</head>
			<body>
				<div id="menu">
					<table class="menutab">
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">o</xsl:with-param>
							<xsl:with-param name="vname">overview</xsl:with-param>
							<xsl:with-param name="lkey">menu_ov</xsl:with-param>
						</xsl:call-template>
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">e</xsl:with-param>
							<xsl:with-param name="vname">empire</xsl:with-param>
							<xsl:with-param name="lkey">menu_em</xsl:with-param>
						</xsl:call-template>
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">b</xsl:with-param>
							<xsl:with-param name="vname">buildings</xsl:with-param>
							<xsl:with-param name="lkey">menu_bld</xsl:with-param>
						</xsl:call-template>
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">s</xsl:with-param>
							<xsl:with-param name="vname">ships</xsl:with-param>
							<xsl:with-param name="lkey">menu_sh</xsl:with-param>
						</xsl:call-template>
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">f</xsl:with-param>
							<xsl:with-param name="vname">fleets</xsl:with-param>
							<xsl:with-param name="lkey">menu_fl</xsl:with-param>
						</xsl:call-template>
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">d</xsl:with-param>
							<xsl:with-param name="vname">defense</xsl:with-param>
							<xsl:with-param name="lkey">menu_def</xsl:with-param>
						</xsl:call-template>
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">i</xsl:with-param>
							<xsl:with-param name="vname">missiles</xsl:with-param>
							<xsl:with-param name="lkey">menu_mi</xsl:with-param>
						</xsl:call-template>
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">c</xsl:with-param>
							<xsl:with-param name="vname">resources</xsl:with-param>
							<xsl:with-param name="lkey">menu_ro</xsl:with-param>
						</xsl:call-template>
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">r</xsl:with-param>
							<xsl:with-param name="vname">research</xsl:with-param>
							<xsl:with-param name="lkey">menu_rs</xsl:with-param>
						</xsl:call-template>
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">t</xsl:with-param>
							<xsl:with-param name="vname">techs</xsl:with-param>
							<xsl:with-param name="lkey">menu_te</xsl:with-param>
						</xsl:call-template>
						<tr><td class="menuspace"></td></tr>
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">m</xsl:with-param>
							<xsl:with-param name="vname">messages</xsl:with-param>
							<xsl:with-param name="lkey">menu_me</xsl:with-param>
						</xsl:call-template>
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">n</xsl:with-param>
							<xsl:with-param name="vname">notes</xsl:with-param>
							<xsl:with-param name="lkey">menu_no</xsl:with-param>
						</xsl:call-template>
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">a</xsl:with-param>
							<xsl:with-param name="vname">alliance</xsl:with-param>
							<xsl:with-param name="lkey">menu_al</xsl:with-param>
						</xsl:call-template>
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">y</xsl:with-param>
							<xsl:with-param name="vname">diplomacy</xsl:with-param>
							<xsl:with-param name="lkey">menu_di</xsl:with-param>
						</xsl:call-template>
						<xsl:call-template name="menuitem">
							<xsl:with-param name="akey">k</xsl:with-param>
							<xsl:with-param name="vname">market</xsl:with-param>
							<xsl:with-param name="lkey">menu_ma</xsl:with-param>
						</xsl:call-template>
						<tr><td class="menuspace"></td></tr>
						<tr>
							<td class="menu">
								<a class="menulink logout" accesskey="q" href="logout.php">
									<xsl:value-of select="$trans[@key = 'menu_lo']"/>
								</a>
							</td>
						</tr>
					</table>
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
								<select size="1" class="poslist" onchange="location.href = location.href.replace(/celb=[0-9]+:[0-9]+:[0-9]+:[0-9]+/g, 'celb=' + this.options[this.selectedIndex].value)">
									<xsl:for-each select="positions/position">
										<option class="posopt" value="{@galaxy}:{@sun}:{@orbit}:{@celb}">
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
						<tr>
							<xsl:for-each select="resources/resource">
								<td>
									<xsl:attribute name="class">
										<xsl:choose>
											<xsl:when test="@present &gt;= @storage">resbad</xsl:when>
											<xsl:otherwise>resgood</xsl:otherwise>
										</xsl:choose>
									</xsl:attribute>
									<xsl:value-of select="@present"/>
								</td>
							</xsl:for-each>
						</tr>
					</table>
				</div>
				<div id="logo"/>
				<div id="content">
					<xsl:apply-templates select="buildings"/>
				</div>
				<script type="text/javascript">countdown();</script>
			</body>
		</html>
	</xsl:template>

	<xsl:template name="coords">
		<xsl:param name="pos"/>
		<xsl:text>[</xsl:text>
		<xsl:value-of select="$pos/@galaxy"/>
		<xsl:text>:</xsl:text>
		<xsl:value-of select="$pos/@sun"/>
		<xsl:text>:</xsl:text>
		<xsl:value-of select="$pos/@orbit"/>
		<xsl:text>:</xsl:text>
		<xsl:value-of select="$pos/@celb"/>
		<xsl:text>]</xsl:text>
	</xsl:template>

	<xsl:template name="jumplink">
		<xsl:param name="pos"/>
		<xsl:text>location.href = location.href.replace(/celb=[0-9]+:[0-9]+:[0-9]+:[0-9]+/g, 'celb=</xsl:text>
		<xsl:value-of select="$pos/@galaxy"/>
		<xsl:text>:</xsl:text>
		<xsl:value-of select="$pos/@sun"/>
		<xsl:text>:</xsl:text>
		<xsl:value-of select="$pos/@orbit"/>
		<xsl:text>:</xsl:text>
		<xsl:value-of select="$pos/@celb"/>
		<xsl:text>')</xsl:text>
	</xsl:template>

	<xsl:template name="menuitem">
		<xsl:param name="akey"/>
		<xsl:param name="vname"/>
		<xsl:param name="lkey"/>
		<tr>
			<td class="menu">
				<a class="menulink {$vname}" accesskey="{$akey}" href="view.php?view={$vname}&amp;celb={/view/positions/@curg}:{/view/positions/@curs}:{/view/positions/@curo}:{/view/positions/@curc}">
					<xsl:value-of select="$trans[@key = $lkey]"/>
				</a>
			</td>
		</tr>
	</xsl:template>

	<xsl:template name="time">
		<xsl:param name="value"/>
		<xsl:if test="$value &gt;= 86400">
			<xsl:value-of select="($value - $value mod 86400) div 86400"/><xsl:text>d</xsl:text>
			<xsl:if test="($value mod 86400) &gt; 0"><xsl:text> </xsl:text></xsl:if>
		</xsl:if>
		<xsl:if test="($value mod 86400) &gt;= 3600">
			<xsl:value-of select="($value mod 86400 - $value mod 3600) div 3600"/><xsl:text>h</xsl:text>
			<xsl:if test="($value mod 3600) &gt; 0"><xsl:text> </xsl:text></xsl:if>
		</xsl:if>
		<xsl:if test="($value mod 3600) &gt;= 60">
			<xsl:value-of select="($value mod 3600 - $value mod 60) div 60"/><xsl:text>m</xsl:text>
			<xsl:if test="($value mod 60) &gt; 0"><xsl:text> </xsl:text></xsl:if>
		</xsl:if>
		<xsl:if test="($value mod 60) &gt; 0">
			<xsl:value-of select="$value mod 60"/><xsl:text>s</xsl:text>
		</xsl:if>
	</xsl:template>

	<xsl:template name="countdown">
		<xsl:param name="id"/>
		<xsl:param name="value"/>
		<span id="{$id}" title="{$value}"></span>
		<script type="text/javascript">cdarray.push(&quot;<xsl:value-of select="$id"/>&quot;);</script>
	</xsl:template>

</xsl:stylesheet>
