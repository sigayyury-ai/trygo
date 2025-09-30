jQuery(document).ready(function($) {
    'use strict';
    
    // Initialize the plugin
    initAdsCreativeGenerator();
    
    function initAdsCreativeGenerator() {
        // Load saved URL if exists
        loadSavedUrl();
        
        // Bind events
        bindEvents();
    }
    
    function loadSavedUrl() {
        var savedUrl = localStorage.getItem('lastAnalyzedUrl');
        if (savedUrl) {
            $('#website-url').val(savedUrl);
        }
    }
    
    function bindEvents() {
        // Analyze button click
        $(document).on('click', '.ads-creative-generator .btn:not(.back-btn)', function(e) {
            e.preventDefault();
            analyzeWebsite();
        });
        
        // Back button click
        $(document).on('click', '.ads-creative-generator .back-btn', function(e) {
            e.preventDefault();
            goBack();
        });
        
        // Copy button clicks
        $(document).on('click', '.ads-creative-generator .mini-copy-btn', function(e) {
            e.preventDefault();
            var section = $(this).data('section');
            var creativeNumber = $(this).data('creative-number');
            copyMiniCreative(section, creativeNumber);
        });
        
        // Enter key on input
        $(document).on('keypress', '.ads-creative-generator #website-url', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                analyzeWebsite();
            }
        });
    }
    
    function analyzeWebsite() {
        console.log('analyzeWebsite function called');
        var url = $('#website-url').val().trim();
        console.log('URL entered:', url);
        
        if (!url) {
            showMessage('Please enter a website URL', 'error');
            return;
        }
        
        // Validate URL format first (before adding protocol)
        if (!isValidUrl(url)) {
            showMessage('Please enter a valid website address (e.g., example.com)', 'error');
            return;
        }
        
        // Add protocol if missing
        if (!url.match(/^https?:\/\//)) {
            url = 'https://' + url;
        }
        
        // Save URL
        localStorage.setItem('lastAnalyzedUrl', url);
        
        // Show loading
        $('#analyze-btn').prop('disabled', true);
        $('#loading1').show();
        
        // Go to second screen
        setTimeout(function() {
            $('#screen1').hide();
            $('#screen2').show();
            
            // Show analysis loading
            $('#loading2').show();
            $('#analysis-result').hide();
            
            // Perform actual analysis
            performAnalysis(url);
        }, 1500);
    }
    
    function performAnalysis(url) {
        console.log('performAnalysis called with URL:', url);
        console.log('User Agent:', navigator.userAgent);
        
        // Check if adsCreativeGenerator object exists
        if (typeof adsCreativeGenerator === 'undefined') {
            console.error('adsCreativeGenerator object is undefined!');
            showMessage('Plugin configuration error. Please refresh the page.', 'error');
            goBack();
            return;
        }
        
        console.log('AJAX URL:', adsCreativeGenerator.ajaxUrl);
        console.log('Nonce:', adsCreativeGenerator.nonce);
        
        // Check if jQuery is available
        if (typeof $ === 'undefined') {
            console.error('jQuery is not available!');
            showMessage('jQuery error. Please refresh the page.', 'error');
            goBack();
            return;
        }
        
        $.ajax({
            url: adsCreativeGenerator.ajaxUrl,
            type: 'POST',
            data: {
                action: 'analyze_website',
                website_url: url,
                nonce: adsCreativeGenerator.nonce
            },
            success: function(response) {
                if (response.success) {
                    displayAnalysisResults(response.data);
                } else {
                    // Log error details for debugging
                    console.log('Analysis failed. Response:', response);
                    console.log('User Agent:', navigator.userAgent);
                    console.log('URL:', url);
                    
                    showMessage(response.data || 'Analysis failed. Please try again.', 'error');
                    goBack();
                }
            },
            error: function(xhr, status, error) {
                console.error('Analysis error:', error);
                console.error('Status:', status);
                console.error('XHR:', xhr);
                console.error('Response Text:', xhr.responseText);
                showMessage('Network error. Please check your connection and try again.', 'error');
                goBack();
            },
            complete: function() {
                $('#loading2').hide();
                $('#analyze-btn').prop('disabled', false);
            }
        });
    }
    
    function displayAnalysisResults(data) {
        // Display analysis results
        $('#uvp-text').text(data.uvp || 'No data available');
        $('#icp-text').text(data.icp || 'No data available');
        $('#pain-text').text(data.pain_points || 'No data available');
        $('#triggers-text').text(data.triggers || 'No data available');
        $('#goals-text').text(data.goals || 'No data available');
        $('#benefits-text').text(data.benefits || 'No data available');
        
        // Generate creatives
        if (data.creatives) {
            generateSectionCreatives('uvp', data.creatives.uvp || []);
            generateSectionCreatives('icp', data.creatives.icp || []);
            generateSectionCreatives('pain', data.creatives.pain_points || []);
            generateSectionCreatives('triggers', data.creatives.triggers || []);
            generateSectionCreatives('goals', data.creatives.goals || []);
            generateSectionCreatives('benefits', data.creatives.benefits || []);
        }
        
        // Show results
        $('#analysis-result').show();
    }
    
    function generateSectionCreatives(section, creatives) {
        if (!creatives || creatives.length === 0) {
            return;
        }
        
        creatives.forEach(function(creative, index) {
            var num = index + 1;
            $('#' + section + '-image-' + num).text(creative.image || 'No image idea');
            $('#' + section + '-cta-' + num).text(creative.cta || 'No CTA');
            $('#' + section + '-headline-' + num).text(creative.headline || 'No headline');
            $('#' + section + '-text-' + num).text(creative.text || 'No ad text');
        });
        
        // Show the section creatives
        $('#' + section + '-creatives').show();
    }
    
    function copyMiniCreative(section, creativeNumber) {
        var image = $('#' + section + '-image-' + creativeNumber).text();
        var cta = $('#' + section + '-cta-' + creativeNumber).text();
        var headline = $('#' + section + '-headline-' + creativeNumber).text();
        var text = $('#' + section + '-text-' + creativeNumber).text();
        
        var fullCreative = section.toUpperCase() + '-BASED CREATIVE #' + creativeNumber + '\n\n' +
                          'IMAGE IDEA:\n' + image + '\n\n' +
                          'CTA FOR IMAGE:\n' + cta + '\n\n' +
                          'HEADLINE:\n' + headline + '\n\n' +
                          'AD TEXT:\n' + text;
        
        // Copy to clipboard
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(fullCreative).then(function() {
                showCopySuccess(event.target);
            }).catch(function() {
                fallbackCopyTextToClipboard(fullCreative, event.target);
            });
        } else {
            fallbackCopyTextToClipboard(fullCreative, event.target);
        }
    }
    
    function fallbackCopyTextToClipboard(text, button) {
        var textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.top = '0';
        textArea.style.left = '0';
        textArea.style.position = 'fixed';
        
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            var successful = document.execCommand('copy');
            if (successful) {
                showCopySuccess(button);
            } else {
                showMessage('Failed to copy to clipboard', 'error');
            }
        } catch (err) {
            showMessage('Failed to copy to clipboard', 'error');
        }
        
        document.body.removeChild(textArea);
    }
    
    function showCopySuccess(button) {
        var $btn = $(button);
        var originalText = $btn.text();
        
        $btn.text('Copied!')
            .css({
                'background': 'rgba(34, 197, 94, 0.1)',
                'color': '#15803d',
                'border-color': 'rgba(34, 197, 94, 0.2)'
            });
        
        setTimeout(function() {
            $btn.text(originalText)
                .css({
                    'background': 'rgba(99, 102, 241, 0.1)',
                    'color': '#6366f1',
                    'border-color': 'rgba(99, 102, 241, 0.2)'
                });
        }, 2000);
    }
    
    function goBack() {
        $('#screen2').hide();
        $('#screen1').show();
        $('#analyze-btn').prop('disabled', false);
        $('#loading1').hide();
        $('#loading2').hide();
        $('#analysis-result').hide();
        
        // Hide all creative sections
        $('.section-creatives').hide();
    }
    
    function showMessage(message, type) {
        var messageClass = 'message ' + (type || 'info');
        var $message = $('<div class="' + messageClass + '">' + message + '</div>');
        
        $('.ads-creative-generator .screen').first().prepend($message);
        
        setTimeout(function() {
            $message.fadeOut(function() {
                $message.remove();
            });
        }, 5000);
    }
    
    function isValidUrl(string) {
        // Simple validation for website addresses
        if (!string || string.trim().length === 0) {
            return false;
        }
        
        // Remove protocol if present for validation
        var cleanString = string.replace(/^https?:\/\//, '').trim();
        
        // Basic domain validation - must contain at least one dot and valid characters
        var domainRegex = /^[a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.[a-zA-Z]{2,}$/;
        
        // Also accept simple domains like "localhost" for testing
        var simpleDomainRegex = /^[a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*$/;
        
        return (domainRegex.test(cleanString) || simpleDomainRegex.test(cleanString)) && cleanString.length > 0;
    }
    
    // Handle page refresh - restore state
    $(window).on('beforeunload', function() {
        var currentScreen = $('.ads-creative-generator .screen:visible').attr('id');
        if (currentScreen) {
            localStorage.setItem('adsCreativeGeneratorCurrentScreen', currentScreen);
        }
    });
    
    $(window).on('load', function() {
        var savedScreen = localStorage.getItem('adsCreativeGeneratorCurrentScreen');
        if (savedScreen === 'screen2') {
            // Restore to screen 2 if we were there
            $('#screen1').hide();
            $('#screen2').show();
        }
    });
    
    // Function to show demo data for testing layout (commented for future use)
    /*
    function showDemoData() {
        setTimeout(function() {
            $('#loading2').hide();
            
            // Demo analysis data
            var demoData = {
                uvp: "AI-powered business automation platform that saves 80% of your time",
                icp: "Business leaders, entrepreneurs, and department managers aged 25-45 who value efficiency and growth",
                pain_points: "Manual data processing, time management issues, growing responsibilities, human errors",
                triggers: "Workload growth, tight deadlines, competitor pressure, process inefficiency",
                goals: "Improving efficiency, reducing costs, improving work quality, profit growth, routine automation",
                benefits: "Time savings, error reduction, team productivity increase, fast ROI, competitive advantage",
                creatives: {
                    uvp: [
                        {
                            image: "Split-screen: cluttered workspace vs. clean automated dashboard with AI symbols",
                            cta: "SAVE 80% TIME",
                            headline: "The Only AI Platform That Saves 80% of Your Time",
                            text: "Stop wasting hours on manual tasks. Our unique AI automation platform transforms your business processes instantly. Join thousands who've already saved 80% of their time. Try free today!"
                        },
                        {
                            image: "Professional pointing at efficiency charts showing dramatic time savings",
                            cta: "AUTOMATE NOW",
                            headline: "Why Choose Us Over Competitors?",
                            text: "While others promise automation, we deliver 80% time savings from day one. Our unique approach combines AI with proven business processes. See the difference yourself - start your free trial!"
                        },
                        {
                            image: "Before/after comparison: stressed manager vs. confident leader with automated systems",
                            cta: "GET STARTED",
                            headline: "The Game-Changing Difference",
                            text: "What makes us different? We don't just automate - we transform your entire workflow. Experience the unique advantage that's helped 10,000+ businesses save 80% of their time. Book a demo!"
                        }
                    ],
                    icp: [
                        {
                            image: "Diverse group of business professionals (25-45 years) in modern office setting",
                            cta: "FOR BUSINESS LEADERS",
                            headline: "Built for Ambitious Business Leaders Like You",
                            text: "You're a driven professional who values efficiency and growth. Our platform is designed specifically for business leaders who want to scale without the stress. Join your peers who've already transformed their operations."
                        },
                        {
                            image: "Entrepreneur working on laptop with success metrics and growth charts visible",
                            cta: "SCALE YOUR BUSINESS",
                            headline: "Perfect for Growing Entrepreneurs",
                            text: "As an entrepreneur, you need solutions that grow with you. Our AI platform adapts to your business size and industry, whether you're in e-commerce, services, or consulting. Start scaling smarter today."
                        },
                        {
                            image: "Department managers in meeting room with automation dashboard on screen",
                            cta: "LEAD YOUR TEAM",
                            headline: "Department Leaders Choose Us",
                            text: "You manage teams and need tools that make everyone more productive. Our platform empowers your department to achieve more with less effort. See why department leaders across industries trust us."
                        }
                    ],
                    pain_points: [
                        {
                            image: "Frustrated business person drowning in paperwork and manual processes",
                            cta: "END THE CHAOS",
                            headline: "Tired of Manual Data Processing?",
                            text: "Manual data entry is killing your productivity and causing costly errors. Our AI platform eliminates manual processing, reducing errors by 95% and saving hours daily. Stop the madness - automate today!"
                        },
                        {
                            image: "Clock showing time being wasted on administrative tasks",
                            cta: "RECLAIM YOUR TIME",
                            headline: "Stop Losing Time on Admin Tasks",
                            text: "Every hour spent on admin work is an hour not growing your business. Our automation platform handles the routine tasks so you can focus on what matters. Reclaim your time and your business potential."
                        },
                        {
                            image: "Business person making costly mistakes due to human error in processes",
                            cta: "ELIMINATE ERRORS",
                            headline: "Human Errors Costing You Money?",
                            text: "One mistake can cost thousands. Our AI platform eliminates human errors in your business processes, ensuring accuracy and consistency. Protect your business from costly mistakes - automate with confidence."
                        }
                    ],
                    triggers: [
                        {
                            image: "Overwhelmed business person with growing workload and tight deadlines",
                            cta: "HANDLE THE LOAD",
                            headline: "Workload Growing Faster Than You?",
                            text: "When your workload explodes, you need solutions that scale instantly. Our AI platform handles increased demand without breaking a sweat. Don't let growth overwhelm you - automate and scale smart."
                        },
                        {
                            image: "Stressed professional facing urgent deadlines with calendar showing red alerts",
                            cta: "BEAT DEADLINES",
                            headline: "Deadlines Stressing You Out?",
                            text: "Tight deadlines don't have to mean sleepless nights. Our automation platform speeds up your processes, helping you meet deadlines with confidence. Turn pressure into productivity - automate your workflow."
                        },
                        {
                            image: "Business person looking at competitors' success while struggling to keep up",
                            cta: "STAY COMPETITIVE",
                            headline: "Competitors Moving Faster Than You?",
                            text: "In today's fast-paced market, speed is everything. Our AI platform gives you the competitive edge by automating processes your competitors do manually. Stay ahead of the game - automate your advantage."
                        }
                    ],
                    goals: [
                        {
                            image: "Business dashboard showing efficiency metrics and productivity gains",
                            cta: "BOOST EFFICIENCY",
                            headline: "Want to Maximize Your Efficiency?",
                            text: "Efficiency isn't just about working harder - it's about working smarter. Our AI platform optimizes your processes to achieve maximum efficiency with minimum effort. Reach your efficiency goals faster."
                        },
                        {
                            image: "Money growing in charts and graphs showing cost reduction",
                            cta: "CUT COSTS",
                            headline: "Ready to Slash Your Operating Costs?",
                            text: "Every dollar saved on operations is a dollar earned in profit. Our automation platform reduces operational costs by eliminating waste and inefficiency. Start cutting costs today and boost your bottom line."
                        },
                        {
                            image: "Team celebrating quality improvements and customer satisfaction",
                            cta: "IMPROVE QUALITY",
                            headline: "Quality Matters to Your Customers",
                            text: "Consistent quality builds customer trust and loyalty. Our AI platform ensures every process meets your high standards, improving quality across your entire operation. Deliver excellence every time."
                        }
                    ],
                    benefits: [
                        {
                            image: "Clock showing time being saved with money symbols around it",
                            cta: "SAVE TIME & MONEY",
                            headline: "Time is Money - Save Both",
                            text: "Every minute saved is money earned. Our platform saves you hours daily while reducing operational costs. The time and money you save pays for the platform in weeks. Start saving today!"
                        },
                        {
                            image: "Team working smoothly with zero errors and high productivity",
                            cta: "ZERO ERRORS",
                            headline: "Eliminate Costly Mistakes",
                            text: "One error can cost thousands. Our AI platform eliminates human errors, ensuring accuracy and consistency. Protect your business from costly mistakes while boosting team confidence and productivity."
                        },
                        {
                            image: "Business growing with success metrics and team celebrating",
                            cta: "SCALE SMART",
                            headline: "Scale Without the Growing Pains",
                            text: "Growth shouldn't mean chaos. Our platform scales with your business, handling increased demand without breaking a sweat. Scale smart, not hard - let automation handle the heavy lifting."
                        }
                    ]
                }
            };
            
            displayAnalysisResults(demoData);
            $('#analyze-btn').prop('disabled', false);
        }, 2000);
    }
    */
    
});
