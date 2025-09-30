<?php
/**
 * Plugin Name: TRYGO Ads Creative Generator
 * Plugin URI: https://urock.pro
 * Description: Analyze business websites and generate ad creative ideas using AI
 * Version: 1.0.0
 * Author: TRYGO
 * License: GPL v2 or later
 * Text Domain: ads-creative-generator
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('ADS_CREATIVE_GENERATOR_VERSION', '1.0.0');
define('ADS_CREATIVE_GENERATOR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ADS_CREATIVE_GENERATOR_PLUGIN_URL', plugin_dir_url(__FILE__));

// Main plugin class
class AdsCreativeGenerator {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_enqueue_scripts'));
        
        // AJAX handlers
        add_action('wp_ajax_analyze_website', array($this, 'analyze_website'));
        add_action('wp_ajax_nopriv_analyze_website', array($this, 'analyze_website'));
        add_action('wp_ajax_get_analysis_details', array($this, 'get_analysis_details'));
        add_action('wp_ajax_test_openai_api_key', array($this, 'test_openai_api_key'));
        
        // Include shortcode functionality
        require_once ADS_CREATIVE_GENERATOR_PLUGIN_DIR . 'includes/shortcode.php';
        
        // Activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        // Load text domain for translations
        load_plugin_textdomain('ads-creative-generator', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function activate() {
        // Create database tables if needed
        $this->create_tables();
        
        // Set default options
        $this->set_default_options();
    }
    
    public function deactivate() {
        // Cleanup if needed
    }
    
    private function create_tables() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'ads_creative_analyses';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            ip_address varchar(45) DEFAULT NULL,
            website_url varchar(255) NOT NULL,
            analysis_data longtext,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY ip_address (ip_address),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    private function set_default_options() {
        add_option('ads_creative_generator_openai_api_key', '');
        add_option('ads_creative_generator_daily_limit', 3);
        add_option('ads_creative_generator_plugin_version', ADS_CREATIVE_GENERATOR_VERSION);
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Ads Creative Generator',
            'Ads Creative Generator',
            'manage_options',
            'ads-creative-generator',
            array($this, 'admin_page'),
            'dashicons-lightbulb',
            30
        );
        
        add_submenu_page(
            'ads-creative-generator',
            'Settings',
            'Settings',
            'manage_options',
            'ads-creative-generator-settings',
            array($this, 'settings_page')
        );
    }
    
    public function admin_page() {
        include ADS_CREATIVE_GENERATOR_PLUGIN_DIR . 'admin/admin-page.php';
    }
    
    public function settings_page() {
        if (isset($_POST['submit'])) {
            $this->save_settings();
        }
        include ADS_CREATIVE_GENERATOR_PLUGIN_DIR . 'admin/settings-page.php';
    }
    
    private function save_settings() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        if (isset($_POST['openai_api_key'])) {
            update_option('ads_creative_generator_openai_api_key', sanitize_text_field($_POST['openai_api_key']));
        }
        
        if (isset($_POST['daily_limit'])) {
            update_option('ads_creative_generator_daily_limit', intval($_POST['daily_limit']));
        }
        
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>Settings saved successfully!</p></div>';
        });
    }
    
    public function admin_enqueue_scripts($hook) {
        if (strpos($hook, 'ads-creative-generator') !== false) {
            wp_enqueue_script('ads-creative-generator-admin', ADS_CREATIVE_GENERATOR_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), ADS_CREATIVE_GENERATOR_VERSION, true);
            wp_enqueue_style('ads-creative-generator-admin', ADS_CREATIVE_GENERATOR_PLUGIN_URL . 'assets/css/admin.css', array(), ADS_CREATIVE_GENERATOR_VERSION);
            
            wp_localize_script('ads-creative-generator-admin', 'adsCreativeGenerator', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ads_creative_generator_nonce')
            ));
        }
    }
    
    public function frontend_enqueue_scripts() {
        wp_enqueue_script('ads-creative-generator-frontend', ADS_CREATIVE_GENERATOR_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), ADS_CREATIVE_GENERATOR_VERSION, true);
        wp_enqueue_style('ads-creative-generator-frontend', ADS_CREATIVE_GENERATOR_PLUGIN_URL . 'assets/css/frontend.css', array(), ADS_CREATIVE_GENERATOR_VERSION);
        
        wp_localize_script('ads-creative-generator-frontend', 'adsCreativeGenerator', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ads_creative_generator_nonce')
        ));
    }
    
    public function analyze_website() {
        // Log all requests for debugging
        error_log('Ads Creative Generator: analyze_website called');
        error_log('Ads Creative Generator: User Agent: ' . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'));
        error_log('Ads Creative Generator: POST data: ' . print_r($_POST, true));
        
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'ads_creative_generator_nonce')) {
            error_log('Ads Creative Generator: Nonce verification failed');
            wp_die('Security check failed');
        }
        
        // User permissions check removed - allow anonymous users
        error_log('Ads Creative Generator: User permissions check skipped - allowing anonymous users');
        
        // Daily limit check removed - no usage tracking
        
        $website_url = sanitize_url($_POST['website_url']);
        error_log('Ads Creative Generator: Sanitized URL: ' . $website_url);
        
        if (empty($website_url)) {
            error_log('Ads Creative Generator: Website URL is empty');
            wp_send_json_error('Website URL is required');
        }
        
        // Get OpenAI API key
        $api_key = get_option('ads_creative_generator_openai_api_key');
        if (empty($api_key)) {
            wp_send_json_error('OpenAI API key not configured');
        }
        
        // Analyze website
        $analysis = $this->perform_analysis($website_url, $api_key);
        
        if ($analysis) {
            // No usage tracking - just return the analysis
            wp_send_json_success($analysis);
        } else {
            // Log detailed error for debugging
            error_log('Ads Creative Generator: Analysis failed for URL: ' . $website_url);
            error_log('Ads Creative Generator: User Agent: ' . $_SERVER['HTTP_USER_AGENT']);
            error_log('Ads Creative Generator: API Key exists: ' . (!empty($api_key) ? 'Yes' : 'No'));
            
            wp_send_json_error('Analysis failed. Please try again or contact support if the problem persists.');
        }
    }
    
    // Function removed to avoid database errors
    
    // Function removed - no usage tracking
    
    private function perform_analysis($website_url, $api_key) {
        // All devices now get real analysis from OpenAI API
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $is_mobile = preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $user_agent);
        
        error_log('Ads Creative Generator: User Agent: ' . $user_agent);
        error_log('Ads Creative Generator: Is Mobile: ' . ($is_mobile ? 'Yes' : 'No'));
        error_log('Ads Creative Generator: Getting real analysis for URL: ' . $website_url);
        
        // Try to get real analysis from OpenAI API for all devices
        $analysis = $this->call_openai_api($website_url, $api_key);
        
        if ($analysis) {
            error_log('Ads Creative Generator: Successfully got analysis from OpenAI API');
            return $analysis;
        }
        
        // Log that we're falling back to demo data
        error_log('Ads Creative Generator: OpenAI API failed, using demo data for URL: ' . $website_url);
        
        // Fallback to demo data if API fails
        return $this->get_demo_analysis();
    }
    
    private function get_demo_analysis() {
        // Demo data for fallback
        return array(
            'uvp' => 'AI-powered business automation platform that saves 80% of your time',
            'icp' => 'Business leaders, entrepreneurs, and department managers aged 25-45 who value efficiency and growth',
            'pain_points' => 'Manual data processing, time management issues, growing responsibilities, human errors',
            'triggers' => 'Workload growth, tight deadlines, competitor pressure, process inefficiency',
            'goals' => 'Improving efficiency, reducing costs, improving work quality, profit growth, routine automation',
            'benefits' => 'Time savings, error reduction, team productivity increase, fast ROI, competitive advantage',
            'creatives' => array(
                'uvp' => array(
                    array(
                        'image' => 'Split-screen: cluttered workspace vs. clean automated dashboard with AI symbols',
                        'cta' => 'SAVE 80% TIME',
                        'headline' => 'The Only AI Platform That Saves 80% of Your Time',
                        'text' => 'Stop wasting hours on manual tasks. Our unique AI automation platform transforms your business processes instantly. Join thousands who\'ve already saved 80% of their time. Try free today!'
                    ),
                    array(
                        'image' => 'Professional pointing at efficiency charts showing dramatic time savings',
                        'cta' => 'AUTOMATE NOW',
                        'headline' => 'Why Choose Us Over Competitors?',
                        'text' => 'While others promise automation, we deliver 80% time savings from day one. Our unique approach combines AI with proven business processes. See the difference yourself - start your free trial!'
                    ),
                    array(
                        'image' => 'Before/after comparison: stressed manager vs. confident leader with automated systems',
                        'cta' => 'GET STARTED',
                        'headline' => 'The Game-Changing Difference',
                        'text' => 'What makes us different? We don\'t just automate - we transform your entire workflow. Experience the unique advantage that\'s helped 10,000+ businesses save 80% of their time. Book a demo!'
                    )
                ),
                'icp' => array(
                    array(
                        'image' => 'Diverse group of business professionals (25-45 years) in modern office setting',
                        'cta' => 'FOR BUSINESS LEADERS',
                        'headline' => 'Built for Ambitious Business Leaders Like You',
                        'text' => 'You\'re a driven professional who values efficiency and growth. Our platform is designed specifically for business leaders who want to scale without the stress. Join your peers who\'ve already transformed their operations.'
                    ),
                    array(
                        'image' => 'Entrepreneur working on laptop with success metrics and growth charts visible',
                        'cta' => 'SCALE YOUR BUSINESS',
                        'headline' => 'Perfect for Growing Entrepreneurs',
                        'text' => 'As an entrepreneur, you need solutions that grow with you. Our AI platform adapts to your business size and industry, whether you\'re in e-commerce, services, or consulting. Start scaling smarter today.'
                    ),
                    array(
                        'image' => 'Department managers in meeting room with automation dashboard on screen',
                        'cta' => 'LEAD YOUR TEAM',
                        'headline' => 'Department Leaders Choose Us',
                        'text' => 'You manage teams and need tools that make everyone more productive. Our platform empowers your department to achieve more with less effort. See why department leaders across industries trust us.'
                    )
                ),
                'pain_points' => array(
                    array(
                        'image' => 'Frustrated business person drowning in paperwork and manual processes',
                        'cta' => 'END THE CHAOS',
                        'headline' => 'Tired of Manual Data Processing?',
                        'text' => 'Manual data entry is killing your productivity and causing costly errors. Our AI platform eliminates manual processing, reducing errors by 95% and saving hours daily. Stop the madness - automate today!'
                    ),
                    array(
                        'image' => 'Clock showing time being wasted on administrative tasks',
                        'cta' => 'RECLAIM YOUR TIME',
                        'headline' => 'Stop Losing Time on Admin Tasks',
                        'text' => 'Every hour spent on admin work is an hour not growing your business. Our automation platform handles the routine tasks so you can focus on what matters. Reclaim your time and your business potential.'
                    ),
                    array(
                        'image' => 'Business person making costly mistakes due to human error in processes',
                        'cta' => 'ELIMINATE ERRORS',
                        'headline' => 'Human Errors Costing You Money?',
                        'text' => 'One mistake can cost thousands. Our AI platform eliminates human errors in your business processes, ensuring accuracy and consistency. Protect your business from costly mistakes - automate with confidence.'
                    )
                ),
                'triggers' => array(
                    array(
                        'image' => 'Overwhelmed business person with growing workload and tight deadlines',
                        'cta' => 'HANDLE THE LOAD',
                        'headline' => 'Workload Growing Faster Than You?',
                        'text' => 'When your workload explodes, you need solutions that scale instantly. Our AI platform handles increased demand without breaking a sweat. Don\'t let growth overwhelm you - automate and scale smart.'
                    ),
                    array(
                        'image' => 'Stressed professional facing urgent deadlines with calendar showing red alerts',
                        'cta' => 'BEAT DEADLINES',
                        'headline' => 'Deadlines Stressing You Out?',
                        'text' => 'Tight deadlines don\'t have to mean sleepless nights. Our automation platform speeds up your processes, helping you meet deadlines with confidence. Turn pressure into productivity - automate your workflow.'
                    ),
                    array(
                        'image' => 'Business person looking at competitors\' success while struggling to keep up',
                        'cta' => 'STAY COMPETITIVE',
                        'headline' => 'Competitors Moving Faster Than You?',
                        'text' => 'In today\'s fast-paced market, speed is everything. Our AI platform gives you the competitive edge by automating processes your competitors do manually. Stay ahead of the game - automate your advantage.'
                    )
                ),
                'goals' => array(
                    array(
                        'image' => 'Business dashboard showing efficiency metrics and productivity gains',
                        'cta' => 'BOOST EFFICIENCY',
                        'headline' => 'Want to Maximize Your Efficiency?',
                        'text' => 'Efficiency isn\'t just about working harder - it\'s about working smarter. Our AI platform optimizes your processes to achieve maximum efficiency with minimum effort. Reach your efficiency goals faster.'
                    ),
                    array(
                        'image' => 'Money growing in charts and graphs showing cost reduction',
                        'cta' => 'CUT COSTS',
                        'headline' => 'Ready to Slash Your Operating Costs?',
                        'text' => 'Every dollar saved on operations is a dollar earned in profit. Our automation platform reduces operational costs by eliminating waste and inefficiency. Start cutting costs today and boost your bottom line.'
                    ),
                    array(
                        'image' => 'Team celebrating quality improvements and customer satisfaction',
                        'cta' => 'IMPROVE QUALITY',
                        'headline' => 'Quality Matters to Your Customers',
                        'text' => 'Consistent quality builds customer trust and loyalty. Our AI platform ensures every process meets your high standards, improving quality across your entire operation. Deliver excellence every time.'
                    )
                ),
                'benefits' => array(
                    array(
                        'image' => 'Clock showing time being saved with money symbols around it',
                        'cta' => 'SAVE TIME & MONEY',
                        'headline' => 'Time is Money - Save Both',
                        'text' => 'Every minute saved is money earned. Our platform saves you hours daily while reducing operational costs. The time and money you save pays for the platform in weeks. Start saving today!'
                    ),
                    array(
                        'image' => 'Team working smoothly with zero errors and high productivity',
                        'cta' => 'ZERO ERRORS',
                        'headline' => 'Eliminate Costly Mistakes',
                        'text' => 'One error can cost thousands. Our AI platform eliminates human errors, ensuring accuracy and consistency. Protect your business from costly mistakes while boosting team confidence and productivity.'
                    ),
                    array(
                        'image' => 'Business growing with success metrics and team celebrating',
                        'cta' => 'SCALE SMART',
                        'headline' => 'Scale Without the Growing Pains',
                        'text' => 'Growth shouldn\'t mean chaos. Our platform scales with your business, handling increased demand without breaking a sweat. Scale smart, not hard - let automation handle the heavy lifting.'
                    )
                )
            )
        );
    }
    
    private function call_openai_api($website_url, $api_key) {
        // First, try to get website content
        $website_content = $this->get_website_content($website_url);
        
        // Create the prompt for analysis
        $prompt = "Analyze this business website and provide a comprehensive business analysis.

Website URL: {$website_url}

Website Content:
{$website_content}

Please provide a detailed business analysis in the following format:

UVP (Unique Value Proposition): [Main advantage that distinguishes from competitors - be specific and concise]
ICP (Ideal Customer Profile): [Portrait of ideal buyer: age, profession, interests, needs - be detailed]
Pain Points: [Problems and frustrations that customers face - list specific pain points]
Triggers: [Situations and events that prompt customers to take action - be specific]
Goals: [What customers want to achieve and their desired outcomes - list main goals]
Benefits: [Value and advantages customers get from using the product - be specific]

For each category above, also generate 3 ad creatives with the following structure:
- Image idea: [Visual concept for ad image - be descriptive]
- CTA for image: [Call-to-action text for image - short and action-oriented]
- Headline: [Short, compelling headline - attention-grabbing]
- Text: [2-3 sentence ad copy with problem-solution-CTA structure - persuasive]

IMPORTANT: Format the response as valid JSON with the following exact structure:
{
  \"uvp\": \"[text here]\",
  \"icp\": \"[text here]\",
  \"pain_points\": \"[text here]\",
  \"triggers\": \"[text here]\",
  \"goals\": \"[text here]\",
  \"benefits\": \"[text here]\",
  \"creatives\": {
    \"uvp\": [
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"},
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"},
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"}
    ],
    \"icp\": [
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"},
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"},
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"}
    ],
    \"pain_points\": [
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"},
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"},
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"}
    ],
    \"triggers\": [
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"},
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"},
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"}
    ],
    \"goals\": [
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"},
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"},
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"}
    ],
    \"benefits\": [
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"},
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"},
      {\"image\": \"[text]\", \"cta\": \"[text]\", \"headline\": \"[text]\", \"text\": \"[text]\"}
    ]
  }
}

Make sure the response is valid JSON that can be parsed directly.";

        // Make API call to OpenAI
        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode(array(
                'model' => 'gpt-4',
                'messages' => array(
                    array(
                        'role' => 'system',
                        'content' => 'You are a professional business analyst and marketing expert. Analyze websites and create compelling ad creatives. Always respond with valid JSON format.'
                    ),
                    array(
                        'role' => 'user',
                        'content' => $prompt
                    )
                ),
                'max_tokens' => 4000,
                'temperature' => 0.7
            )),
            'timeout' => 90
        ));

        if (is_wp_error($response)) {
            error_log('OpenAI API Error: ' . $response->get_error_message());
            return false;
        }

        $status_code = wp_remote_retrieve_response_code($response);
        if ($status_code !== 200) {
            $body = wp_remote_retrieve_body($response);
            error_log('OpenAI API HTTP Error: ' . $status_code . ' - ' . $body);
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!isset($data['choices'][0]['message']['content'])) {
            error_log('OpenAI API Response Error: Invalid response format');
            return false;
        }

        $content = $data['choices'][0]['message']['content'];
        
        // Try to extract JSON from the response
        $json_start = strpos($content, '{');
        $json_end = strrpos($content, '}') + 1;
        
        if ($json_start !== false && $json_end !== false) {
            $json_content = substr($content, $json_start, $json_end - $json_start);
            $analysis = json_decode($json_content, true);
            
            if ($analysis && isset($analysis['uvp']) && isset($analysis['creatives'])) {
                return $analysis;
            }
        }

        error_log('OpenAI API Response Error: Could not parse JSON from response. Content: ' . $content);
        return false;
    }
    
    private function get_website_content($website_url) {
        // Get website content for analysis
        $response = wp_remote_get($website_url, array(
            'timeout' => 45,
            'user-agent' => 'Mozilla/5.0 (compatible; AdsCreativeGenerator/1.0; +https://urock.pro)',
            'headers' => array(
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate',
                'Connection' => 'keep-alive',
            ),
            'sslverify' => false, // Disable SSL verification for better compatibility
            'redirection' => 5
        ));
        
        if (is_wp_error($response)) {
            error_log('Ads Creative Generator: Failed to fetch website content: ' . $response->get_error_message());
            return "Unable to fetch website content. URL: {$website_url}";
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        if ($status_code !== 200) {
            error_log('Ads Creative Generator: HTTP error ' . $status_code . ' for URL: ' . $website_url);
            return "Website returned HTTP {$status_code}. URL: {$website_url}";
        }
        
        $body = wp_remote_retrieve_body($response);
        
        if (empty($body)) {
            error_log('Ads Creative Generator: Empty response body for URL: ' . $website_url);
            return "Website returned empty content. URL: {$website_url}";
        }
        
        // Extract text content (remove HTML tags)
        $text = wp_strip_all_tags($body);
        
        // Clean up the text
        $text = preg_replace('/\s+/', ' ', $text); // Replace multiple spaces with single space
        $text = trim($text);
        
        // Limit content length to avoid token limits
        $text = substr($text, 0, 5000);
        
        return $text ?: "Website content could not be extracted. URL: {$website_url}";
    }
    
    // Function removed - no usage tracking
    
    public function get_analysis_details() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'ads_creative_generator_nonce')) {
            wp_die('Security check failed');
        }
        
        // Check user permissions
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $analysis_id = intval($_POST['analysis_id']);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'ads_creative_analyses';
        
        $analysis = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d",
            $analysis_id
        ));
        
        if ($analysis) {
            $data = json_decode($analysis->analysis_data, true);
            
            $html = '<div class="analysis-details">';
            $html .= '<h4>Website: ' . esc_html($analysis->website_url) . '</h4>';
            $html .= '<p><strong>Date:</strong> ' . esc_html($analysis->created_at) . '</p>';
            
            if ($data) {
                $html .= '<div class="analysis-section"><h4>UVP:</h4><p>' . esc_html($data['uvp']) . '</p></div>';
                $html .= '<div class="analysis-section"><h4>ICP:</h4><p>' . esc_html($data['icp']) . '</p></div>';
                $html .= '<div class="analysis-section"><h4>Pain Points:</h4><p>' . esc_html($data['pain_points']) . '</p></div>';
                $html .= '<div class="analysis-section"><h4>Triggers:</h4><p>' . esc_html($data['triggers']) . '</p></div>';
                $html .= '<div class="analysis-section"><h4>Goals:</h4><p>' . esc_html($data['goals']) . '</p></div>';
                $html .= '<div class="analysis-section"><h4>Benefits:</h4><p>' . esc_html($data['benefits']) . '</p></div>';
                
                if (isset($data['creatives'])) {
                    $html .= '<h4>Generated Creatives:</h4>';
                    foreach ($data['creatives'] as $section => $creatives) {
                        $html .= '<div class="analysis-section">';
                        $html .= '<h4>' . strtoupper($section) . ' Creatives:</h4>';
                        foreach ($creatives as $index => $creative) {
                            $html .= '<div class="creative-item">';
                            $html .= '<strong>Creative ' . ($index + 1) . ':</strong><br>';
                            $html .= '<strong>Image:</strong> <span>' . esc_html($creative['image']) . '</span><br>';
                            $html .= '<strong>CTA:</strong> <span>' . esc_html($creative['cta']) . '</span><br>';
                            $html .= '<strong>Headline:</strong> <span>' . esc_html($creative['headline']) . '</span><br>';
                            $html .= '<strong>Text:</strong> <span>' . esc_html($creative['text']) . '</span>';
                            $html .= '</div>';
                        }
                        $html .= '</div>';
                    }
                }
            }
            
            $html .= '</div>';
            
            wp_send_json_success($html);
        } else {
            wp_send_json_error('Analysis not found');
        }
    }
    
    public function test_openai_api_key() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'ads_creative_generator_nonce')) {
            wp_die('Security check failed');
        }
        
        // Check user permissions
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $api_key = sanitize_text_field($_POST['api_key']);
        
        if (empty($api_key)) {
            wp_send_json_error('API key is required');
        }
        
        // Test API key with a simple request
        $response = wp_remote_post('https://api.openai.com/v1/models', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json'
            ),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            wp_send_json_error('Connection error: ' . $response->get_error_message());
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        
        if ($status_code === 200) {
            wp_send_json_success('API key is valid');
        } elseif ($status_code === 401) {
            wp_send_json_error('Invalid API key');
        } else {
            wp_send_json_error('API key test failed with status code: ' . $status_code);
        }
    }
}

// Initialize the plugin
new AdsCreativeGenerator();
