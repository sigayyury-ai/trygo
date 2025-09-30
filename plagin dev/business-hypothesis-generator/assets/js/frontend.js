/**
 * TRYGO BusinessHypothesis Generator - Frontend JavaScript
 */

(function($) {
    'use strict';

    // Инициализация плагина
    function initBusinessHypothesisGenerator() {
        bindEvents();
        validateUrl();
    }

    // Привязка событий
    function bindEvents() {
        // Валидация URL при вводе
        $('#websiteUrl').on('input', validateUrl);
        
        // Обработка отправки формы
        $('.bhg-analyze-btn').on('click', analyzeWebsite);
        
        // Копирование гипотез
        $('.bhg-copy-btn').on('click', copyHypothesis);
        
        // Кнопка нового анализа
        $('.bhg-new-analysis-btn').on('click', showInputScreen);
    }

    // Валидация URL
    function validateUrl() {
        const url = $('#websiteUrl').val();
        const button = $('.bhg-analyze-btn');
        
        if (url && isValidUrl(url)) {
            button.prop('disabled', false);
            button.css('opacity', '1');
        } else {
            button.prop('disabled', true);
            button.css('opacity', '0.6');
        }
    }

    // Проверка валидности URL
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }

    // Анализ веб-сайта
    function analyzeWebsite(e) {
        e.preventDefault();
        
        const url = $('#websiteUrl').val().trim();
        
        if (!url || !isValidUrl(url)) {
            showError('Пожалуйста, введите корректный URL сайта');
            return;
        }

        // Показываем экран загрузки
        showLoadingScreen();
        
        // AJAX запрос к серверу
        $.ajax({
            url: businessHypothesisAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'generate_business_hypothesis',
                nonce: businessHypothesisAjax.nonce,
                website_url: url
            },
            beforeSend: function() {
                $('.bhg-analyze-btn').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    displayResults(response.data, url);
                } else {
                    showError(response.data || 'Произошла ошибка при анализе сайта');
                }
            },
            error: function() {
                showError('Ошибка соединения с сервером');
            },
            complete: function() {
                hideLoadingScreen();
                $('.bhg-analyze-btn').prop('disabled', false);
            }
        });
    }

    // Показать экран загрузки
    function showLoadingScreen() {
        $('.bhg-input-screen').hide();
        $('.bhg-results-screen').hide();
        
        if (!$('.bhg-loading-screen').length) {
            const loadingHtml = `
                <div class="bhg-loading-screen">
                    <div class="bhg-spinner"></div>
                    <div class="bhg-loading-text">${businessHypothesisAjax.loading_text}</div>
                </div>
            `;
            $('.business-hypothesis-generator').append(loadingHtml);
        }
        
        $('.bhg-loading-screen').show();
    }

    // Скрыть экран загрузки
    function hideLoadingScreen() {
        $('.bhg-loading-screen').hide();
    }

    // Отображение результатов
    function displayResults(hypotheses, url) {
        // Обновляем URL в заголовке
        $('#analyzedUrl').text(url);
        
        // Генерируем HTML для гипотез
        const hypothesesHtml = generateHypothesesHtml(hypotheses);
        $('.bhg-hypotheses-list').html(hypothesesHtml);
        
        // Показываем результаты
        $('.bhg-loading-screen').hide();
        $('.bhg-results-screen').show();
        
        // Привязываем события к новым кнопкам копирования
        $('.bhg-copy-btn').on('click', copyHypothesis);
    }

    // Генерация HTML для гипотез
    function generateHypothesesHtml(hypotheses) {
        let html = '';
        
        if (hypotheses && hypotheses.hypotheses && Array.isArray(hypotheses.hypotheses)) {
            hypotheses.hypotheses.forEach((hypothesis, index) => {
                html += `
                    <div class="bhg-hypothesis-card">
                        <div class="bhg-hypothesis-title">${index + 1}. ${hypothesis.title || 'Гипотеза'}</div>
                        <div class="bhg-hypothesis-description">
                            ${hypothesis.description || 'Описание гипотезы'}
                        </div>
                        <div class="bhg-hypothesis-details">
                            <div class="bhg-detail-item">
                                <div class="bhg-detail-label">Метод тестирования</div>
                                <div class="bhg-detail-value">${hypothesis.test_method || 'Не указано'}</div>
                            </div>
                            <div class="bhg-detail-item">
                                <div class="bhg-detail-label">Метрики успеха</div>
                                <div class="bhg-detail-value">${hypothesis.success_metrics || 'Не указано'}</div>
                            </div>
                        </div>
                        <button class="bhg-copy-btn" data-hypothesis-index="${index}">
                            Копировать
                        </button>
                    </div>
                `;
            });
        } else {
            // Fallback - показываем демо данные
            html = generateDemoHypotheses();
        }
        
        return html;
    }

    // Демо данные (fallback)
    function generateDemoHypotheses() {
        const demoHypotheses = [
            {
                title: "Проблема клиентов с поиском решений",
                description: "Клиенты тратят много времени на поиск подходящих инструментов и решений для своих бизнес-задач, что приводит к потере времени и ресурсов.",
                test_method: "Интервью с 20 потенциальными клиентами",
                success_metrics: "70%+ подтверждают проблему"
            },
            {
                title: "Ценность персонализированных рекомендаций",
                description: "Клиенты готовы платить за персонализированные рекомендации инструментов и решений, которые экономят их время и повышают эффективность.",
                test_method: "A/B тест с платными рекомендациями",
                success_metrics: "15%+ конверсия в покупку"
            },
            {
                title: "Потребность в экспертной поддержке",
                description: "Малый и средний бизнес нуждается в экспертной поддержке при выборе и внедрении новых технологических решений.",
                test_method: "Опрос 100 компаний из целевой аудитории",
                success_metrics: "60%+ выражают потребность в консультациях"
            },
            {
                title: "Рыночная возможность в нише AI-инструментов",
                description: "Растущий рынок AI-инструментов создает возможности для платформы, которая помогает бизнесу ориентироваться в этом пространстве.",
                test_method: "Анализ конкурентов и поисковых запросов",
                success_metrics: "100%+ рост поисковых запросов по AI-инструментам"
            },
            {
                title: "Готовность платить за экономию времени",
                description: "Предприниматели и менеджеры готовы платить за решения, которые экономят им 2+ часа в неделю на поиске и оценке инструментов.",
                test_method: "Тест готовности платить с разными ценами",
                success_metrics: "40%+ готовы платить $50+/месяц"
            }
        ];
        
        return generateHypothesesHtml({ hypotheses: demoHypotheses });
    }

    // Копирование гипотезы
    function copyHypothesis(e) {
        e.preventDefault();
        
        const button = $(this);
        const card = button.closest('.bhg-hypothesis-card');
        const title = card.find('.bhg-hypothesis-title').text();
        const description = card.find('.bhg-hypothesis-description').text();
        const testMethod = card.find('.bhg-detail-item:first-child .bhg-detail-value').text();
        const metrics = card.find('.bhg-detail-item:last-child .bhg-detail-value').text();
        
        const textToCopy = `${title}\n\n${description}\n\nМетод тестирования: ${testMethod}\n\nМетрики успеха: ${metrics}`;
        
        // Копирование в буфер обмена
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(textToCopy).then(() => {
                showCopySuccess(button);
            }).catch(() => {
                fallbackCopyText(textToCopy, button);
            });
        } else {
            fallbackCopyText(textToCopy, button);
        }
    }

    // Fallback копирование для старых браузеров
    function fallbackCopyText(text, button) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            showCopySuccess(button);
        } catch (err) {
            showError('Не удалось скопировать текст');
        }
        
        document.body.removeChild(textArea);
    }

    // Показать успешное копирование
    function showCopySuccess(button) {
        const originalText = button.text();
        button.text('Скопировано!');
        button.addClass('copied');
        
        setTimeout(() => {
            button.text(originalText);
            button.removeClass('copied');
        }, 2000);
    }

    // Показать экран ввода
    function showInputScreen() {
        $('.bhg-results-screen').hide();
        $('.bhg-input-screen').show();
        
        // Очищаем поле ввода
        $('#websiteUrl').val('');
        validateUrl();
    }

    // Показать ошибку
    function showError(message) {
        // Простое уведомление об ошибке
        alert(message);
    }

    // Инициализация при загрузке документа
    $(document).ready(function() {
        if ($('.business-hypothesis-generator').length) {
            initBusinessHypothesisGenerator();
        }
    });

})(jQuery);




