<?php
/**
 * Plugin Name: TRYGO BusinessHypothesis Generator
 * Plugin URI: https://trygo.com/
 * Description: Генератор бизнес-гипотез на основе анализа веб-сайтов с использованием OpenAI API. Создает 5 уникальных гипотез для поиска новых возможностей заработка.
 * Version: 1.0.0
 * Author: TRYGO Team
 * Author URI: https://trygo.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: business-hypothesis-generator
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 * 
 * @package BusinessHypothesisGenerator
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Предотвращение прямого доступа
if (!defined('ABSPATH')) {
    exit;
}

// Константы плагина
define('BUSINESS_HYPOTHESIS_VERSION', '1.0.0');
define('BUSINESS_HYPOTHESIS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('BUSINESS_HYPOTHESIS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('BUSINESS_HYPOTHESIS_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Хук активации плагина
register_activation_hook(__FILE__, 'business_hypothesis_activation');

/**
 * Функция активации плагина
 */
function business_hypothesis_activation() {
    // Проверка версии WordPress
    if (version_compare(get_bloginfo('version'), '5.0', '<')) {
        wp_die(
            __('TRYGO BusinessHypothesis Generator требует WordPress версии 5.0 или выше.', 'business-hypothesis-generator'),
            __('Ошибка активации плагина', 'business-hypothesis-generator'),
            array('back_link' => true)
        );
    }
    
    // Проверка версии PHP
    if (version_compare(PHP_VERSION, '7.4', '<')) {
        wp_die(
            __('TRYGO BusinessHypothesis Generator требует PHP версии 7.4 или выше.', 'business-hypothesis-generator'),
            __('Ошибка активации плагина', 'business-hypothesis-generator'),
            array('back_link' => true)
        );
    }
    
    // Создание таблиц БД
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
    
    // Установка опций по умолчанию
    if (!get_option('business_hypothesis_openai_api_key')) {
        update_option('business_hypothesis_openai_api_key', '');
    }
    
    if (!get_option('business_hypothesis_prompt')) {
        $default_prompt = 'Ты кофаундер проекта которому нужны идеи для дополнительного заработка. Нужно придумать 5 гипотез на основе анализа сайта {website_url}.

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
        
        update_option('business_hypothesis_prompt', $default_prompt);
    }
    
    // Флаг активации
    update_option('business_hypothesis_activated', true);
}

// Хук деактивации плагина
register_deactivation_hook(__FILE__, 'business_hypothesis_deactivation');

/**
 * Функция деактивации плагина
 */
function business_hypothesis_deactivation() {
    // Очистка кэша
    wp_cache_flush();
    
    // Удаление флага активации
    delete_option('business_hypothesis_activated');
}

// Подключение основного класса плагина
require_once BUSINESS_HYPOTHESIS_PLUGIN_PATH . 'business-hypothesis-generator.php';

// Инициализация плагина
function business_hypothesis_init() {
    new BusinessHypothesisGenerator();
}
add_action('plugins_loaded', 'business_hypothesis_init');

// Подключение админ класса
if (is_admin()) {
    require_once BUSINESS_HYPOTHESIS_PLUGIN_PATH . 'admin/admin-page.php';
    new BusinessHypothesisAdmin();
}




