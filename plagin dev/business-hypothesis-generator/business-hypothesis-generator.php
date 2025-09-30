<?php
/**
 * Plugin Name: TRYGO - BusinessHypothesis Generator
 * Plugin URI: https://trygo.com
 * Description: Генератор бизнес-гипотез на основе анализа рынка и целевой аудитории с использованием OpenAI API
 * Version: 1.0.0
 * Author: TRYGO Team
 * Author URI: https://trygo.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: business-hypothesis-generator
 * Domain Path: /languages
 */

// Предотвращение прямого доступа
if (!defined('ABSPATH')) {
    exit;
}

// Константы плагина
define('BUSINESS_HYPOTHESIS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('BUSINESS_HYPOTHESIS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('BUSINESS_HYPOTHESIS_VERSION', '1.0.0');

/**
 * Основной класс плагина TRYGO BusinessHypothesis Generator
 */
class BusinessHypothesisGenerator {
    
    /**
     * Конструктор
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_ajax_generate_business_hypothesis', array($this, 'generate_business_hypothesis'));
        add_action('wp_ajax_nopriv_generate_business_hypothesis', array($this, 'generate_business_hypothesis'));
        add_action('wp_ajax_test_openai_api_key', array($this, 'test_openai_api_key'));
        add_action('wp_ajax_nopriv_test_openai_api_key', array($this, 'test_openai_api_key'));
        
        // Активация и деактивация
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Инициализация плагина
     */
    public function init() {
        // Загрузка текстового домена
        load_plugin_textdomain('business-hypothesis-generator', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Регистрация шорткода
        add_shortcode('business_hypothesis_generator', array($this, 'shortcode_handler'));
        
        // Создание таблиц БД
        $this->create_tables();
        
        // Инициализация админ панели
        if (is_admin()) {
            require_once BUSINESS_HYPOTHESIS_PLUGIN_PATH . 'admin/admin-page.php';
            require_once BUSINESS_HYPOTHESIS_PLUGIN_PATH . 'admin/settings-page.php';
            
            new BusinessHypothesisAdmin();
            new BusinessHypothesisSettings();
        }
    }
    
    /**
     * Подключение скриптов для фронтенда
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'business-hypothesis-frontend-css',
            BUSINESS_HYPOTHESIS_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            BUSINESS_HYPOTHESIS_VERSION
        );
        
        wp_enqueue_script(
            'business-hypothesis-frontend-js',
            BUSINESS_HYPOTHESIS_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            BUSINESS_HYPOTHESIS_VERSION,
            true
        );
        
        // Локализация для AJAX
        wp_localize_script('business-hypothesis-frontend-js', 'businessHypothesisAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('business_hypothesis_nonce'),
            'loading_text' => __('Генерация гипотез...', 'business-hypothesis-generator'),
            'error_text' => __('Произошла ошибка. Попробуйте еще раз.', 'business-hypothesis-generator')
        ));
    }
    
    /**
     * Подключение скриптов для админки
     */
    public function admin_enqueue_scripts($hook) {
        if (strpos($hook, 'business-hypothesis') !== false) {
            wp_enqueue_style(
                'business-hypothesis-admin-css',
                BUSINESS_HYPOTHESIS_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                BUSINESS_HYPOTHESIS_VERSION
            );
            
            wp_enqueue_script(
                'business-hypothesis-admin-js',
                BUSINESS_HYPOTHESIS_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery'),
                BUSINESS_HYPOTHESIS_VERSION,
                true
            );
            
            wp_localize_script('business-hypothesis-admin-js', 'businessHypothesisAdmin', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('business_hypothesis_admin_nonce'),
                'test_success' => __('API ключ работает корректно!', 'business-hypothesis-generator'),
                'test_error' => __('Ошибка подключения к API', 'business-hypothesis-generator')
            ));
        }
    }
    
    /**
     * Обработчик шорткода
     */
    public function shortcode_handler($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Генератор бизнес-гипотез', 'business-hypothesis-generator')
        ), $atts);
        
        ob_start();
        include BUSINESS_HYPOTHESIS_PLUGIN_PATH . 'includes/shortcode.php';
        return ob_get_clean();
    }
    
    /**
     * AJAX обработчик генерации бизнес-гипотез
     */
    public function generate_business_hypothesis() {
        // Проверка nonce
        if (!wp_verify_nonce($_POST['nonce'], 'business_hypothesis_nonce')) {
            wp_die(__('Ошибка безопасности', 'business-hypothesis-generator'));
        }
        
        $website_url = sanitize_url($_POST['website_url']);
        
        if (empty($website_url)) {
            wp_send_json_error(__('URL сайта обязателен для заполнения', 'business-hypothesis-generator'));
        }
        
        // Получение API ключа
        $api_key = get_option('business_hypothesis_openai_api_key');
        if (empty($api_key)) {
            wp_send_json_error(__('API ключ не настроен. Обратитесь к администратору.', 'business-hypothesis-generator'));
        }
        
        // Генерация гипотез через OpenAI
        $hypotheses = $this->generate_hypotheses_via_openai($website_url, $api_key);
        
        if ($hypotheses === false) {
            wp_send_json_error(__('Ошибка генерации гипотез. Проверьте API ключ.', 'business-hypothesis-generator'));
        }
        
        // Сохранение в БД
        $this->save_hypothesis_analysis($website_url, $hypotheses);
        
        wp_send_json_success($hypotheses);
    }
    
    /**
     * AJAX обработчик тестирования API ключа
     */
    public function test_openai_api_key() {
        if (!wp_verify_nonce($_POST['nonce'], 'business_hypothesis_admin_nonce')) {
            wp_die(__('Ошибка безопасности', 'business-hypothesis-generator'));
        }
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Недостаточно прав', 'business-hypothesis-generator'));
        }
        
        $api_key = sanitize_text_field($_POST['api_key']);
        
        if (empty($api_key)) {
            wp_send_json_error(__('API ключ не может быть пустым', 'business-hypothesis-generator'));
        }
        
        // Тест API ключа
        $test_result = $this->test_openai_connection($api_key);
        
        if ($test_result) {
            wp_send_json_success(__('API ключ работает корректно!', 'business-hypothesis-generator'));
        } else {
            wp_send_json_error(__('Ошибка подключения к OpenAI API', 'business-hypothesis-generator'));
        }
    }
    
    /**
     * Генерация гипотез через OpenAI API
     */
    private function generate_hypotheses_via_openai($website_url, $api_key) {
        // Получаем промпт из настроек
        $prompt_template = get_option('business_hypothesis_prompt', $this->get_default_prompt());
        
        // Получаем контент сайта
        $website_content = $this->get_website_content($website_url);
        
        // Заменяем переменные в промпте
        $prompt = str_replace('{website_url}', $website_url, $prompt_template);
        $prompt = str_replace('{website_content}', $website_content, $prompt);
        
        $data = array(
            'model' => 'gpt-3.5-turbo',
            'messages' => array(
                array(
                    'role' => 'system',
                    'content' => 'Вы - эксперт по бизнес-анализу и генерации гипотез. Создавайте структурированные бизнес-гипотезы на основе предоставленной информации.'
                ),
                array(
                    'role' => 'user',
                    'content' => $prompt
                )
            ),
            'max_tokens' => 2000,
            'temperature' => 0.7
        );
        
        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($data),
            'timeout' => 60
        ));
        
        if (is_wp_error($response)) {
            error_log('Business Hypothesis Generator - OpenAI API Error: ' . $response->get_error_message());
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (!isset($data['choices'][0]['message']['content'])) {
            error_log('Business Hypothesis Generator - Invalid API response: ' . $body);
            return false;
        }
        
        $content = $data['choices'][0]['message']['content'];
        
        // Попытка парсинга JSON ответа
        $hypotheses = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Если не JSON, создаем структуру из текста
            $hypotheses = $this->parse_text_to_hypotheses($content);
        }
        
        return $hypotheses;
    }
    
    /**
     * Получение контента веб-сайта
     */
    private function get_website_content($url) {
        $response = wp_remote_get($url, array(
            'timeout' => 30,
            'user-agent' => 'TRYGO BusinessHypothesis Generator/1.0'
        ));
        
        if (is_wp_error($response)) {
            return 'Не удалось получить контент сайта';
        }
        
        $body = wp_remote_retrieve_body($response);
        
        // Извлекаем только текстовый контент
        $body = strip_tags($body);
        $body = preg_replace('/\s+/', ' ', $body);
        $body = trim($body);
        
        // Ограничиваем длину контента
        if (strlen($body) > 3000) {
            $body = substr($body, 0, 3000) . '...';
        }
        
        return $body;
    }
    
    /**
     * Парсинг текстового ответа в структуру гипотез
     */
    private function parse_text_to_hypotheses($content) {
        // Fallback структура если JSON парсинг не удался
        return array(
            'problem_hypotheses' => array(
                array(
                    'title' => __('Основная проблема клиентов', 'business-hypothesis-generator'),
                    'description' => $content,
                    'test_method' => __('Интервью с клиентами', 'business-hypothesis-generator'),
                    'success_metrics' => __('70% клиентов подтверждают проблему', 'business-hypothesis-generator')
                )
            ),
            'solution_hypotheses' => array(),
            'value_hypotheses' => array(),
            'market_hypotheses' => array()
        );
    }
    
    /**
     * Тестирование подключения к OpenAI API
     */
    private function test_openai_connection($api_key) {
        $data = array(
            'model' => 'gpt-3.5-turbo',
            'messages' => array(
                array(
                    'role' => 'user',
                    'content' => 'Тест подключения'
                )
            ),
            'max_tokens' => 5
        );
        
        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($data),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $code = wp_remote_retrieve_response_code($response);
        return $code === 200;
    }
    
    /**
     * Сохранение анализа гипотез в БД
     */
    private function save_hypothesis_analysis($website_url, $hypotheses) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'business_hypothesis_analyses';
        
        $wpdb->insert(
            $table_name,
            array(
                'website_url' => $website_url,
                'hypotheses_data' => json_encode($hypotheses),
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%s')
        );
    }
    
    /**
     * Промпт по умолчанию для поиска новых возможностей заработка
     */
    private function get_default_prompt() {
        return 'Ты кофаундер проекта которому нужны идеи для дополнительного заработка. Нужно придумать 5 гипотез на основе анализа сайта {website_url}.

Контент сайта для анализа:
{website_content}

Гипотезы должны быть направлены на поиск новых возможностей. Гипотезы должны улучшить текущий продукт или найти новую аудиторию с небольшими модификациями продукта или новые рынки. Одна из идей может быть из маркетинга в плане попробовать новые каналы.

Каждая гипотеза должна содержать:
- Название гипотезы
- Описание проблемы или возможности
- Метод тестирования гипотезы
- Метрики успеха

Формат ответа (строго JSON):
{
  "hypotheses": [
    {
      "title": "Название гипотезы",
      "description": "Подробное описание проблемы или возможности",
      "test_method": "Конкретный метод тестирования",
      "success_metrics": "Четкие метрики успеха"
    }
  ]
}

Сгенерируй 5 уникальных гипотез для поиска новых возможностей заработка.';
    }
    
    /**
     * Создание таблиц БД
     */
    private function create_tables() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'business_hypothesis_analyses';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            website_url varchar(500) NOT NULL,
            hypotheses_data longtext NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Активация плагина
     */
    public function activate() {
        $this->create_tables();
        
        // Создание опций по умолчанию
        if (!get_option('business_hypothesis_openai_api_key')) {
            add_option('business_hypothesis_openai_api_key', '');
        }
        
        flush_rewrite_rules();
    }
    
    /**
     * Деактивация плагина
     */
    public function deactivate() {
        flush_rewrite_rules();
    }
}

// Инициализация плагина
new BusinessHypothesisGenerator();

