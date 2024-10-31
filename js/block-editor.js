wp.domReady(function () {
    // Disable the richtext formats
    if (safeEditorSettings.formatsBlacklist) {
        for (const [format, value] of Object.entries(safeEditorSettings.formatsBlacklist)) {
            if (false == value) {
                wp.richText.unregisterFormatType(format);
            }
        }
    }
    
    // Disable styles
    if(safeEditorSettings.stylesBlackList){
        for (const [block, styles] of Object.entries(safeEditorSettings.stylesBlackList)) {
            styles.forEach(style => {
                wp.blocks.unregisterBlockStyle( block, style );
            });
        };
    }
});

// Disable block-specific options
wp.hooks.addFilter('blocks.registerBlockType', 'safe-block-editor', function (blockSettings, blockName) {

    let supports = blockSettings.supports;

    // Override supports with our own
    if (safeEditorSettings.supports) {
        supports = Object.assign({}, supports, safeEditorSettings.supports);
    }

    // Maybe remove block from inserter
    if (-1 < safeEditorSettings.blocksBlacklist.indexOf(blockName)) {
        supports = Object.assign({}, supports, { inserter: false });
    }

    // Override block settings
    blockSettings = Object.assign({}, blockSettings, { supports });  

    return blockSettings;
});
