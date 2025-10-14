<?php
/**
 * Asset Management Example
 * This file demonstrates how to use the asset management functions
 */

// Include the config and asset helper
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../core/shared/helpers/asset_helper.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Management Example</title>
    <!-- Using the link_css helper function -->
    <?php echo link_css('main.css'); ?>
    <?php echo link_css('components.css'); ?>
    
    <!-- Using the link_css helper function with custom attributes -->
    <?php echo link_css('style.css', ['media' => 'screen']); ?>
</head>
<body>
    <div class="container mt-5">
        <h1>Asset Management Example</h1>
        
        <div class="row">
            <div class="col-md-6">
                <h2>Image Assets</h2>
                <!-- Using the img_asset helper function -->
                <?php echo img_asset('product-default.jpg', 'Default Product Image', ['class' => 'img-fluid']); ?>
                
                <h2 class="mt-4">CSS Assets</h2>
                <p>CSS assets are loaded using the <code>link_css()</code> function:</p>
                <pre><?php echo htmlspecialchars(link_css('main.css')); ?></pre>
                
                <h2 class="mt-4">JS Assets</h2>
                <p>JS assets are loaded using the <code>script_js()</code> function:</p>
                <pre><?php echo htmlspecialchars(script_js('script.js')); ?></pre>
            </div>
            
            <div class="col-md-6">
                <h2>Asset URLs</h2>
                <p>Asset URLs can be generated using the helper functions:</p>
                <ul>
                    <li>CSS URL: <code><?php echo htmlspecialchars(asset_css('main.css')); ?></code></li>
                    <li>JS URL: <code><?php echo htmlspecialchars(asset_js('script.js')); ?></code></li>
                    <li>Image URL: <code><?php echo htmlspecialchars(asset_img('product-default.jpg')); ?></code></li>
                </ul>
                
                <h2 class="mt-4">Benefits</h2>
                <ul>
                    <li>Fully flexible asset paths that work in any environment</li>
                    <li>Consistent asset loading across the application</li>
                    <li>Easily maintainable and scalable</li>
                    <li>No more broken asset links when moving between environments</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Using the script_js helper function -->
    <?php echo script_js('script.js'); ?>
    
    <!-- Using the script_js helper function with custom attributes -->
    <?php echo script_js('public-script.js', ['defer' => 'defer']); ?>
</body>
</html>