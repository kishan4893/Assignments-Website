(function() {
    var customblock = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var SelectControl = wp.components.SelectControl;
    var TextControl = wp.components.TextControl;
    var ToggleControl = wp.components.ToggleControl;
    var withSelect = wp.data.withSelect;
wp.blocks.registerBlockType('post-slider-block/post-slider', {
    title: 'REST API Post Slider',
    category: 'common',
    attributes: {
        apiEndpoint: {
            type: 'text',
            default: 'URL',
        },
        sliderSpeed: {
            type: 'number',
            default: 5000,
        },
        showArrows: {
            type: 'boolean',
            default: true,
        },
		showMeta: {
            type: 'boolean',
            default: true,
        },
        autoSlide: {
            type: 'boolean',
            default: true,
        },
        displayPosts: {
            type: 'number',
            default: 5,
        },
    },
    edit: function(props) {
        //const { attributes, setAttributes } = props;
                setAttributes = props.setAttributes,
                attributes = props.attributes;

            var apiEndpoint = attributes.apiEndpoint,
                sliderSpeed = attributes.sliderSpeed,
                showArrows = attributes.showArrows,
				showMeta = attributes.showMeta,
                autoSlide = attributes.autoSlide,
                displayPosts = attributes.displayPosts;

                var onApiEndpointChange = function(newApiEndpoint) {
                    setAttributes({
                        apiEndpoint: newApiEndpoint,
                    });
                };
    
                var onSliderSpeedChange = function(newSliderSpeed) {
                    setAttributes({
                        sliderSpeed: newSliderSpeed,
                    });
                };
    
                var onShowArrowsChange = function(newShowArrows) {
                    setAttributes({
                        showArrows: newShowArrows,
                    });
                };
				
				var onShowMetaChange = function(newShowMeta) {
                    setAttributes({
                        showMeta: newShowMeta,
                    });
                };
    
                var onAutoSlideChange = function(newAutoSlide) {
                    setAttributes({
                        autoSlide: newAutoSlide,
                    });
                };
    
                var onDisplayPostsChange = function(newDisplayPosts) {
                    setAttributes({
                        displayPosts: newDisplayPosts,
                    });
                };

                return customblock(
                    'div', {
                        className: 'post-api-slider-block latestpost-data',
                    },
                    customblock(TextControl, {
                        label: 'Website URL',
                        value: apiEndpoint,
                        onChange: onApiEndpointChange,
                    }),
					customblock(TextControl, {
                        label: 'Display Posts',
                        type: 'number',
                        value: displayPosts,
                        onChange: onDisplayPostsChange,
                    }),
					customblock(ToggleControl, {
                        label: 'Show Meta POST(Like Date,Author)',
                        checked: showMeta,
                        onChange: onShowMetaChange,
                    }),
                    customblock(ToggleControl, {
                        label: 'Show Slide Arrows',
                        checked: showArrows,
                        onChange: onShowArrowsChange,
                    }),
                    customblock(ToggleControl, {
                        label: 'Auto Slide',
                        checked: autoSlide,
                        onChange: onAutoSlideChange,
                    }),
                    customblock(TextControl, {
                        label: 'Slider Speed (ms)',
                        type: 'number',
                        value: sliderSpeed,
                        onChange: onSliderSpeedChange,
                    })
                );
    },
    save: function() {
        return null;
    },
});
})();