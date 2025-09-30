jQuery(document).ready(function($) {
    // Admin page functionality
    
    // Toggle API key visibility
    $('#openai_api_key').after('<button type="button" id="toggle-api-key" class="button button-small">Show</button>');
    
    $('#toggle-api-key').click(function() {
        var input = $('#openai_api_key');
        var button = $(this);
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            button.text('Hide');
        } else {
            input.attr('type', 'password');
            button.text('Show');
        }
    });
    
    // Form validation
    $('form').submit(function(e) {
        var apiKey = $('#openai_api_key').val();
        var dailyLimit = $('#daily_limit').val();
        
        // No validation - accept any API key
        
        if (dailyLimit < 1 || dailyLimit > 100) {
            alert('Daily limit should be between 1 and 100');
            e.preventDefault();
            return false;
        }
    });
    
    // Test API key functionality
    if ($('#test-api-key').length) {
        $('#test-api-key').click(function() {
            var apiKey = $('#openai_api_key').val();
            
            if (!apiKey) {
                alert('Please enter an API key first');
                return;
            }
            
            var button = $(this);
            var originalText = button.text();
            button.text('Testing...').prop('disabled', true);
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'test_openai_api_key',
                    api_key: apiKey,
                    nonce: adsCreativeGenerator.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert('API key is valid!');
                    } else {
                        alert('API key test failed: ' + response.data);
                    }
                },
                error: function() {
                    alert('Error testing API key');
                },
                complete: function() {
                    button.text(originalText).prop('disabled', false);
                }
            });
        });
    }
    
    // View analysis details
    $('.view-analysis').click(function() {
        var analysisId = $(this).data('analysis-id');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'get_analysis_details',
                analysis_id: analysisId,
                nonce: adsCreativeGenerator.nonce
            },
            success: function(response) {
                if (response.success) {
                    showAnalysisModal(response.data);
                } else {
                    alert('Error loading analysis details');
                }
            },
            error: function() {
                alert('Error loading analysis details');
            }
        });
    });
    
    function showAnalysisModal(content) {
        var modal = $('<div id="analysis-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">' +
            '<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; max-width: 80%; max-height: 80%; overflow-y: auto;">' +
            '<h3>Analysis Details</h3>' +
            '<div id="analysis-content">' + content + '</div>' +
            '<button class="button" onclick="$(this).closest(\'#analysis-modal\').remove()">Close</button>' +
            '</div></div>');
        
        $('body').append(modal);
    }
    
    // Close modal when clicking outside
    $(document).on('click', '#analysis-modal', function(e) {
        if (e.target === this) {
            $(this).remove();
        }
    });
});
