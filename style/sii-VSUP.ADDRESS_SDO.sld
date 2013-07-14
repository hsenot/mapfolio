<?xml version="1.0" encoding="UTF-8"?>
<!-- Styled by Herve (http://www.carbongis.com.au) -->
<StyledLayerDescriptor version="1.0.0" xmlns="http://www.opengis.net/sld" xmlns:sld="http://www.opengis.net/sld" xmlns:ogc="http://www.opengis.net/ogc" xmlns:gml="http://www.opengis.net/gml">
    <NamedLayer>
        <Name>sii:VSUP.ADDRESS_SDO</Name>
        <sld:UserStyle>
            <sld:Name>Default Styler</sld:Name>
            <sld:Title/>
            <sld:FeatureTypeStyle>
                <sld:Name>name</sld:Name>
                <sld:Rule>
                    <sld:MaxScaleDenominator>10000.0</sld:MaxScaleDenominator>
                    <sld:PointSymbolizer>
                        <Graphic>
                            <Mark>
                                <WellKnownName>circle</WellKnownName>
                                <Fill>
                                    <CssParameter name="fill">#FFFF00</CssParameter>
                                </Fill>
                            </Mark>
                            <Size>1</Size>
                        </Graphic>
                    </sld:PointSymbolizer>
                    <TextSymbolizer>
                        <Label>
                            <ogc:Function name="strSubstring">
                                <ogc:PropertyName>EZI_ADDRESS</ogc:PropertyName>
                                <ogc:Literal>0</ogc:Literal>
                                <ogc:Function name="strIndexOf">
                                        <ogc:PropertyName>EZI_ADDRESS</ogc:PropertyName>
                                        <!-- Work-around to be able to detect a space character -->
                                        <ogc:Function name="strSubstring">
                                                <ogc:Literal>A C</ogc:Literal>
                                                <ogc:Literal>1</ogc:Literal>
                                                <ogc:Literal>2</ogc:Literal>
                                        </ogc:Function>
                                </ogc:Function>
                            </ogc:Function>
                        </Label>
                        <Font>
                            <CssParameter name="font-size">9</CssParameter>
                            <CssParameter name="font-weight">bold</CssParameter>
                        </Font>
                        <LabelPlacement>
                            <PointPlacement>
                                <AnchorPoint>
                                    <AnchorPointX>0.5</AnchorPointX>
                                    <AnchorPointY>0</AnchorPointY>
                                </AnchorPoint>
                            </PointPlacement>
                        </LabelPlacement>
                        <Halo>
                            <Radius>2</Radius>
                            <Fill>
                                <CssParameter name="fill">#FFFFFF</CssParameter>
                                <CssParameter name="opacity">0.5</CssParameter>
                            </Fill>
                        </Halo>
                        <Fill>
                            <CssParameter name="fill">#000000</CssParameter>
                        </Fill>
                    </TextSymbolizer>
                </sld:Rule>
            </sld:FeatureTypeStyle>
        </sld:UserStyle>
    </NamedLayer>
</StyledLayerDescriptor>
