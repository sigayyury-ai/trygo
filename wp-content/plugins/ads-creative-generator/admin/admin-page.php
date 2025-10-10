<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$api_key = get_option('ads_creative_generator_openai_api_key', '');
?>

<div class="wrap">
    <h1>Ads Creative Generator</h1>
    
    <?php if (empty($api_key)): ?>
        <div class="notice notice-warning">
            <p><strong>Warning:</strong> OpenAI API key is not configured. Please go to <a href="<?php echo admin_url('admin.php?page=ads-creative-generator-settings'); ?>">Settings</a> to configure your API key.</p>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <h2>Quick Start</h2>
        <p>This plugin analyzes business websites and generates AI-powered ad creative ideas. Here's how to get started:</p>
        
        <ol>
            <li><strong>Configure API Key:</strong> Go to <a href="<?php echo admin_url('admin.php?page=ads-creative-generator-settings'); ?>">Settings</a> and enter your OpenAI API key</li>
            <li><strong>Add to Pages:</strong> Use the shortcode <code>[ads_creative_generator]</code> on any page or post</li>
            <li><strong>Test the Plugin:</strong> Visit a page with the shortcode and try analyzing a website</li>
        </ol>
    </div>
    
    <div class="card">
        <h2>Plugin Status</h2>
        <table class="widefat">
            <tr>
                <td><strong>API Key Status:</strong></td>
                <td>
                    <?php if (!empty($api_key)): ?>
                        <span style="color: green;">✓ Configured</span>
                    <?php else: ?>
                        <span style="color: red;">✗ Not configured</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>Usage Tracking:</strong></td>
                <td>Disabled</td>
            </tr>
            <tr>
                <td><strong>Plugin Version:</strong></td>
                <td><?php echo ADS_CREATIVE_GENERATOR_VERSION; ?></td>
            </tr>
        </table>
    </div>
    
    <div class="card">
        <h2>Usage Tracking Disabled</h2>
        <p>Usage tracking has been disabled to improve performance and avoid database issues.</p>
    </div>
    
    <div class="card">
        <h2>Statistics Disabled</h2>
        <p>Usage statistics have been disabled to improve performance.</p>
    </div>
    
    <div class="card">
        <h2>Quick Actions</h2>
        <p>
            <a href="<?php echo admin_url('admin.php?page=ads-creative-generator-settings'); ?>" class="button button-primary">
                Configure Settings
            </a>
            <a href="<?php echo home_url(); ?>" class="button" target="_blank">
                View Website
            </a>
        </p>
    </div>
</div>

<!-- Modal for viewing analysis -->
<div id="analysis-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; max-width: 80%; max-height: 80%; overflow-y: auto;">
        <h3>Analysis Details</h3>
        <div id="analysis-content"></div>
        <button class="button" onclick="document.getElementById('analysis-modal').style.display='none'">Close</button>
    </div>
</div>

<style>
.card {
    background: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.card h2 {
    margin-top: 0;
    color: #23282d;
}

.widefat {
    width: 100%;
    border-collapse: collapse;
}

.widefat th,
.widefat td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.widefat th {
    background: #f9f9f9;
    font-weight: 600;
}

.widefat tr:last-child td {
    border-bottom: none;
}

code {
    background: #f1f1f1;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: Consolas, Monaco, monospace;
}
</style>

<script>
jQuery(document).ready(function($) {
    $('.view-analysis').click(function() {
        var analysisId = $(this).data('analysis-id');
        
        // Make AJAX request to get analysis details
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'get_analysis_details',
                analysis_id: analysisId,
                nonce: '<?php echo wp_create_nonce('ads_creative_generator_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    $('#analysis-content').html(response.data);
                    $('#analysis-modal').show();
                } else {
                    alert('Error loading analysis details');
                }
            },
            error: function() {
                alert('Error loading analysis details');
            }
        });
    });
});
</script>
