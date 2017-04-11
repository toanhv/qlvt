<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"  xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php='remove'>
	<xsl:output method="xml" indent="yes" encoding="utf-8" omit-xml-declaration="yes"/> 
	<xsl:variable name="shift-width" select="/treeview/custom-parameters/param[@name='shift-width']/@value"/>
	<xsl:variable name="opening_node_img_name" select="/treeview/custom-parameters/param[@name='opening_node_img_name']/@value"/>
	<xsl:variable name="closing_node_img_name" select="/treeview/custom-parameters/param[@name='closing_node_img_name']/@value"/>
	<xsl:variable name="leaf_node_img_name" select="/treeview/custom-parameters/param[@name='leaf_node_img_name']/@value"/>
	<xsl:variable name="modal_dialog_mode" select="/treeview/custom-parameters/param[@name='modal_dialog_mode']/@value"/>
	<xsl:variable name="show_control" select="/treeview/custom-parameters/param[@name='show_control']/@value"/>
	<xsl:variable name="select_parent" select="/treeview/custom-parameters/param[@name='select_parent']/@value"/>
	<xsl:template match="/treeview">	
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="left" class="normal_label" colspan="3"> 
				<xsl:apply-templates select="folder">
				<xsl:with-param name="depth" select="1"/>
				</xsl:apply-templates>
			</td>
		</tr>
	</table>
	</xsl:template>
	<xsl:template match="folder">
		<xsl:param name="depth"/>
		<xsl:param name="type" select="@type"/> <!-- Khai bao tham so, Khi do $type la gia tri cua tham so-->
		<xsl:param name="parent_id" select="@parent_id"/>
		<table border="0" cellspacing="0" cellpadding="0">
			<tr id="str_obj" style="display:block;">
				<xsl:attribute name="item_id"><xsl:value-of select="@id"/></xsl:attribute>
				<xsl:attribute name="parent_id"><xsl:value-of select="@parent_id"/></xsl:attribute>
				<xsl:attribute name="type"><xsl:value-of select="@type"/></xsl:attribute>
				<xsl:attribute name="level"><xsl:value-of select="@level"/></xsl:attribute>
				<xsl:if test="$depth>1">
					<td width="{$shift-width}"></td>					
				</xsl:if>	 
				<td valign="top" align="left" colspan="3">				
				<!--Neu cho hien cac nut-->	
					<xsl:if test="$show_control='true'">
						<xsl:if test="$type='1'"> <!--Neu la leaf object-->
							<xsl:element name="input">
								<xsl:attribute name="name">chk_item_id</xsl:attribute>
								<xsl:attribute name="type">checkbox</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="@id"/></xsl:attribute>
								<xsl:attribute name="parent_id"><xsl:value-of select="@parent_id"/></xsl:attribute>
								<xsl:attribute name="onclick">
									<xsl:text>select_parent_radio(document.all.rad_item_id,document.all.chk_item_id,this.parent_id)</xsl:text>
								</xsl:attribute>
							</xsl:element>
						</xsl:if>						

						<xsl:if test="$type='0'"> <!--Neu la contener object-->
							<xsl:element name="input">
								<xsl:attribute name="name">rad_item_id</xsl:attribute>
								<xsl:attribute name="type">radio</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="@id"/></xsl:attribute>
								<xsl:attribute name="parent_id"><xsl:value-of select="@parent_id"/></xsl:attribute>
								<!--Them thuoc tinh level de phan biet duoc cap cua radio tuong ung (voi cay co do sau vo cung) "dung trong ham xoa node"-->
								<xsl:attribute name="level"><xsl:value-of select="@level"/></xsl:attribute>
								<xsl:attribute name="onclick">
									<xsl:text>unselect_checbox(this,document.all.chk_item_id)</xsl:text>
								</xsl:attribute>
							</xsl:element>
						</xsl:if>
					</xsl:if>
					</td>
					
					<td valign="top" align="left" colspan="3">
					<a onclick="node_image_onclick(this,'{$show_control}','{$opening_node_img_name}','{$closing_node_img_name}')" class="folder" >
						<xsl:attribute name="id">
							<xsl:value-of select="@id"/>
						</xsl:attribute>
						<xsl:attribute name="type">
							<xsl:value-of select="@type"/>
						</xsl:attribute>										
						<xsl:if test="$type='1'">												
							<img src="{$leaf_node_img_name}"/>
						</xsl:if>
						<xsl:if test="$type='0'">
							<img id="img" src="{$closing_node_img_name}"/>
						</xsl:if>
					</a>
					<a style=".font-size:8pt;cursor:hand;" onmouseover="this.style.color='red'" onmouseout="this.style.color='#005fa9'" onclick="phone_node_name_onclick(this,'false')" target="master">
						<xsl:attribute name="id">
							<xsl:value-of select="@id"/>
						</xsl:attribute>
						<xsl:attribute name="value">
							<xsl:value-of select="@value"/>
						</xsl:attribute>
						<xsl:attribute name="type">
							<xsl:value-of select="@type"/>
						</xsl:attribute>
						<xsl:if test="$type='1'">
							<xsl:attribute name="class">normal_user</xsl:attribute>
						</xsl:if>									
						<xsl:if test="$type='0'">
							<xsl:attribute name="class">normal_label</xsl:attribute>
						</xsl:if>
						<xsl:attribute name="parent_id">
							<xsl:value-of select="@parent_id"/>
						</xsl:attribute>
						<xsl:attribute name="level">
							<xsl:value-of select="@level"/>
						</xsl:attribute>
						<xsl:value-of select="@title"/>
					</a>            
					<div id="div_obj"  style="display:none;">
						<xsl:attribute name="parent_id">
							<xsl:value-of select="@parent_id"/>
						</xsl:attribute>							
						<xsl:attribute name="item_id">
							<xsl:value-of select="@id"/>
						</xsl:attribute>							
						<xsl:apply-templates select="folder">
						<xsl:with-param name="depth" select="$depth+1"/>						
						</xsl:apply-templates>						
					</div>
				</td>
			</tr>
		</table>
	</xsl:template>  
</xsl:stylesheet>  