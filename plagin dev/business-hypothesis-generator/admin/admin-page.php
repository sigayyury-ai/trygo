<?php
/**
 * Админ страница для TRYGO BusinessHypothesis Generator
 */

// Предотвращение прямого доступа
if (!defined('ABSPATH')) {
    exit;
}

class BusinessHypothesisAdmin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }
    
    /**
     * Добавление меню в админку WordPress
     */
    public function add_admin_menu() {
        add_menu_page(
            'TRYGO BusinessHypothesis Generator',
            'TRYGO Гипотезы',
            'manage_options',
            'business-hypothesis-generator',
            array($this, 'admin_page'),
            'dashicons-lightbulb',
            30
        );
        
        add_submenu_page(
            'business-hypothesis-generator',
            'Настройки',
            'Настройки',
            'manage_options',
            'business-hypothesis-settings',
            array($this, 'settings_page')
        );
    }
    
    /**
     * Главная админ страница
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>🚀 TRYGO - BusinessHypothesis Generator</h1>
            
            <div class="card" style="max-width: 800px;">
                <h2>📋 Инструкции по использованию</h2>
                <ol>
                    <li><strong>Настройте API ключ:</strong> Перейдите в раздел "Настройки" и введите ваш OpenAI API ключ</li>
                    <li><strong>Настройте промпт:</strong> В настройках вы можете изменить промпт для генерации гипотез под ваши нужды</li>
                    <li><strong>Добавьте шорткод:</strong> Используйте шорткод <code>[business_hypothesis_generator]</code> на любой странице или в посте</li>
                    <li><strong>Тестирование:</strong> Если API ключ не настроен, плагин будет работать в демо-режиме</li>
                </ol>
            </div>
            
            <div class="card" style="max-width: 800px; margin-top: 20px;">
                <h2>🎯 О плагине</h2>
                <p><strong>TRYGO BusinessHypothesis Generator</strong> - это инструмент для генерации бизнес-гипотез на основе анализа веб-сайтов с использованием искусственного интеллекта.</p>
                
                <h3>Основные возможности:</h3>
                <ul>
                    <li>✅ Анализ любого веб-сайта по URL</li>
                    <li>✅ Генерация 5 уникальных бизнес-гипотез</li>
                    <li>✅ Настраиваемый промпт для генерации</li>
                    <li>✅ Копирование гипотез в буфер обмена</li>
                    <li>✅ Адаптивный дизайн для всех устройств</li>
                    <li>✅ Демо-режим для тестирования</li>
                </ul>
            </div>
            
            <div class="card" style="max-width: 800px; margin-top: 20px;">
                <h2>🔧 Быстрые действия</h2>
                <p>
                    <a href="<?php echo admin_url('admin.php?page=business-hypothesis-settings'); ?>" class="button button-primary">
                        ⚙️ Настройки API и промпта
                    </a>
                </p>
                
                <h3>Статус плагина:</h3>
                <?php
                $api_key = get_option('business_hypothesis_openai_api_key');
                if (!empty($api_key)) {
                    echo '<p style="color: green;">✅ API ключ настроен</p>';
                } else {
                    echo '<p style="color: orange;">⚠️ API ключ не настроен (работает демо-режим)</p>';
                }
                ?>
            </div>
            
            <div class="card" style="max-width: 800px; margin-top: 20px;">
                <h2>📞 Поддержка</h2>
                <p>Если у вас возникли вопросы или проблемы с плагином, обратитесь к разработчикам TRYGO.</p>
                <p><strong>Версия плагина:</strong> <?php echo BUSINESS_HYPOTHESIS_VERSION; ?></p>
            </div>
        </div>
        
        <style>
        .card {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        .card h2 {
            margin-top: 0;
            color: #23282d;
        }
        .card h3 {
            color: #0073aa;
        }
        .card ul, .card ol {
            margin-left: 20px;
        }
        .card li {
            margin-bottom: 8px;
        }
        </style>
        <?php
    }
    
    /**
     * Страница настроек
     */
    public function settings_page() {
        // Обработка сохранения настроек
        if (isset($_POST['submit']) && wp_verify_nonce($_POST['business_hypothesis_settings_nonce'], 'business_hypothesis_settings')) {
            $api_key = sanitize_text_field($_POST['openai_api_key']);
            $prompt = sanitize_textarea_field($_POST['hypothesis_prompt']);
            
            update_option('business_hypothesis_openai_api_key', $api_key);
            update_option('business_hypothesis_prompt', $prompt);
            
            echo '<div class="notice notice-success"><p>Настройки сохранены!</p></div>';
        }
        
        // Получение текущих настроек
        $api_key = get_option('business_hypothesis_openai_api_key', '');
        $prompt = get_option('business_hypothesis_prompt', $this->get_default_prompt());
        ?>
        
        <div class="wrap">
            <h1>⚙️ Настройки TRYGO BusinessHypothesis Generator</h1>
            
            <form method="post" action="">
                <?php wp_nonce_field('business_hypothesis_settings', 'business_hypothesis_settings_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="openai_api_key">OpenAI API Ключ</label>
                        </th>
                        <td>
                            <input type="password" 
                                   id="openai_api_key" 
                                   name="openai_api_key" 
                                   value="<?php echo esc_attr($api_key); ?>" 
                                   class="regular-text"
                                   placeholder="sk-...">
                            <p class="description">
                                Введите ваш API ключ от OpenAI. 
                                <a href="https://platform.openai.com/api-keys" target="_blank">Получить ключ можно здесь</a>
                            </p>
                            <button type="button" id="test-api-key" class="button button-secondary">
                                🧪 Тестировать API ключ
                            </button>
                            <span id="api-test-result"></span>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="hypothesis_prompt">Промпт для генерации гипотез</label>
                        </th>
                        <td>
                            <textarea id="hypothesis_prompt" 
                                      name="hypothesis_prompt" 
                                      rows="15" 
                                      cols="80" 
                                      class="large-text"><?php echo esc_textarea($prompt); ?></textarea>
                            <p class="description">
                                Настройте промпт для генерации гипотез. Используйте переменные: {website_content} - контент сайта, {website_url} - URL сайта.
                            </p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button('Сохранить настройки'); ?>
            </form>
            
            <div class="card" style="max-width: 100%; margin-top: 30px;">
                <h2>💡 Примеры использования</h2>
                <p><strong>Шорткод:</strong> <code>[business_hypothesis_generator]</code></p>
                <p><strong>Шорткод с заголовком:</strong> <code>[business_hypothesis_generator title="Мой генератор гипотез"]</code></p>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#test-api-key').on('click', function() {
                var button = $(this);
                var result = $('#api-test-result');
                var apiKey = $('#openai_api_key').val();
                
                if (!apiKey) {
                    result.html('<span style="color: red;">❌ Введите API ключ</span>');
                    return;
                }
                
                button.prop('disabled', true).text('Тестирование...');
                result.html('<span style="color: blue;">🔄 Тестирование...</span>');
                
                $.ajax({
                    url: businessHypothesisAdmin.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'test_openai_api_key',
                        nonce: businessHypothesisAdmin.nonce,
                        api_key: apiKey
                    },
                    success: function(response) {
                        if (response.success) {
                            result.html('<span style="color: green;">✅ ' + response.data + '</span>');
                        } else {
                            result.html('<span style="color: red;">❌ ' + response.data + '</span>');
                        }
                    },
                    error: function() {
                        result.html('<span style="color: red;">❌ Ошибка соединения</span>');
                    },
                    complete: function() {
                        button.prop('disabled', false).text('🧪 Тестировать API ключ');
                    }
                });
            });
        });
        </script>
        <?php
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
}





