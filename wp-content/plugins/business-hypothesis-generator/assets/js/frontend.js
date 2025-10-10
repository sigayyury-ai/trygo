/**
 * TRYGO BusinessHypothesis Generator - Frontend JavaScript
 */

(function($) {
    'use strict';

    // Инициализация плагина
    function initBusinessHypothesisGenerator() {
        bindEvents();
        validateUrl();
        // Демо режим отключен - показываем только поле ввода
        showInputScreen();
    }
    
    // Показать экран ввода
    function showInputScreen() {
        $('.bhg-input-screen').show();
        $('.bhg-results-screen').hide();
        $('.bhg-loading-screen').hide();
    }
    
    // Загрузка демо данных при инициализации (отключено)
    function loadDemoData() {
        const demoData = generateDemoHypotheses();
        
        const hypothesesHtml = generateHypothesesHtml(demoData);
        $('.bhg-hypotheses-list').html(hypothesesHtml);
        
        // Привязываем события к кнопкам копирования
        $('.bhg-copy-btn').on('click', copyHypothesis);
        
        // Показываем экран результатов с демо данными
        $('.bhg-input-screen').show();
        $('.bhg-results-screen').show();
    }

    // Привязка событий
    function bindEvents() {
        console.log('Binding events');
        console.log('Found analyze button:', $('.bhg-analyze-btn').length);
        
        // Валидация URL при вводе
        $('#websiteUrl').on('input', validateUrl);
        
        // Обработка отправки формы
        $('.bhg-analyze-btn').on('click', function(e) {
            console.log('Analyze button clicked!');
            analyzeWebsite(e);
        });
        
        // Копирование гипотез
        $('.bhg-copy-btn').on('click', copyHypothesis);
        
        // Кнопка нового анализа удалена
        console.log('Events bound successfully');
    }

    // Валидация URL
    function validateUrl() {
        const url = $('#websiteUrl').val();
        const button = $('.bhg-analyze-btn');
        
        if (url && isValidWebsite(url)) {
            button.prop('disabled', false);
            button.css('opacity', '1');
        } else {
            button.prop('disabled', true);
            button.css('opacity', '0.6');
        }
    }

    // Проверка валидности названия сайта
    function isValidWebsite(string) {
        // Убираем пробелы
        string = string.trim();
        
        // Если пустая строка
        if (!string) {
            return false;
        }
        
        // Если это полный URL с протоколом
        if (string.startsWith('http://') || string.startsWith('https://')) {
            try {
                new URL(string);
                return true;
            } catch (_) {
                return false;
            }
        }
        
        // Если это название сайта без протокола
        // Проверяем, что содержит точку и не содержит пробелов
        if (string.includes('.') && !string.includes(' ')) {
            // Проверяем базовую структуру домена
            const domainPattern = /^[a-zA-Z0-9]([a-zA-Z0-9-]*[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9-]*[a-zA-Z0-9])?)*$/;
            return domainPattern.test(string);
        }
        
        return false;
    }

    // Анализ веб-сайта
    function analyzeWebsite(e) {
        console.log('analyzeWebsite called');
        e.preventDefault();
        
        const url = $('#websiteUrl').val().trim();
        console.log('URL:', url);
        
        if (!url || !isValidWebsite(url)) {
            console.log('URL validation failed');
            showError('Please enter a valid website URL');
            return;
        }

        console.log('URL validation passed, showing loading screen');
        // Показываем экран загрузки
        showLoadingScreen();
        
        console.log('Starting AJAX request to:', businessHypothesisAjax.ajax_url);
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
                console.log('AJAX request started');
                $('.bhg-analyze-btn').prop('disabled', true);
            },
            success: function(response) {
                console.log('AJAX success response:', response);
                if (response.success) {
                    displayResults(response.data, url);
                } else {
                    showError(response.data || 'Error occurred during website analysis');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX error:', status, error, xhr.responseText);
                showError('Connection error with server: ' + error);
            },
            complete: function() {
                console.log('AJAX request completed');
                hideLoadingScreen();
                $('.bhg-analyze-btn').prop('disabled', false);
            }
        });
    }

    // Показать экран загрузки
    function showLoadingScreen() {
        console.log('Showing loading screen');
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
                        <div class="bhg-hypothesis-title">${index + 1}. ${hypothesis.title || 'Hypothesis'}</div>
                        <div class="bhg-hypothesis-description">
                            ${hypothesis.description || 'Hypothesis description'}
                        </div>
                        <div class="bhg-hypothesis-details">
                            <div class="bhg-detail-item">
                                <div class="bhg-detail-label">Test Method</div>
                                <div class="bhg-detail-value">${hypothesis.test_method || 'Not specified'}</div>
                            </div>
                            <div class="bhg-detail-item">
                                <div class="bhg-detail-label">Implementation Complexity</div>
                                <div class="bhg-detail-value">${hypothesis.implementation_complexity || 'Not specified'}</div>
                            </div>
                            <div class="bhg-detail-item">
                                <div class="bhg-detail-label">Success Metrics</div>
                                <div class="bhg-detail-value">${hypothesis.success_metrics || 'Not specified'}</div>
                            </div>
                        </div>
                        <button class="bhg-copy-btn" data-hypothesis-index="${index}">
                            Copy
                        </button>
                    </div>
                `;
            });
        } else {
            // Fallback - показываем демо данные
            const demoData = generateDemoHypotheses();
            html = generateHypothesesHtml(demoData);
        }
        
        return html;
    }

    // Demo data (fallback)
    function generateDemoHypotheses() {
        const demoHypotheses = [
            {
                title: "Customer Problem with Solution Discovery",
                description: "Customers spend significant time searching for suitable tools and solutions for their business tasks, leading to time and resource waste.",
                test_method: "Interview 20 potential customers",
                implementation_complexity: "Low - Requires only customer research and interview setup",
                success_metrics: "70%+ confirm the problem"
            },
            {
                title: "Value of Personalized Recommendations",
                description: "Customers are willing to pay for personalized tool and solution recommendations that save time and improve efficiency.",
                test_method: "A/B test with paid recommendations",
                implementation_complexity: "Medium - Requires algorithm development and payment integration",
                success_metrics: "15%+ conversion to purchase"
            },
            {
                title: "Need for Expert Support",
                description: "Small and medium businesses need expert support when selecting and implementing new technological solutions.",
                test_method: "Survey 100 companies from target audience",
                implementation_complexity: "Low - Requires hiring consultants and setting up support system",
                success_metrics: "60%+ express need for consultations"
            },
            {
                title: "Market Opportunity in AI Tools Niche",
                description: "The growing AI tools market creates opportunities for a platform that helps businesses navigate this space.",
                test_method: "Competitor analysis and search query research",
                implementation_complexity: "Medium - Requires market research and content strategy development",
                success_metrics: "100%+ growth in AI tools search queries"
            },
            {
                title: "Willingness to Pay for Time Savings",
                description: "Entrepreneurs and managers are ready to pay for solutions that save them 2+ hours per week on tool discovery and evaluation.",
                test_method: "Price sensitivity test with different pricing",
                implementation_complexity: "Low - Requires pricing strategy and payment system setup",
                success_metrics: "40%+ willing to pay $50+/month"
            }
        ];
        
        return {
            hypotheses: demoHypotheses,
            analysis: "Demo analysis: Analyzed website content about business tools and solutions platform. Main topics include technology recommendations, AI tools, business automation, and expert consulting services.",
            website_url: "https://trygo.com"
        };
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
        
        const textToCopy = `${title}\n\n${description}\n\nTest Method: ${testMethod}\n\nSuccess Metrics: ${metrics}`;
        
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

    // Show successful copying
    function showCopySuccess(button) {
        const originalText = button.text();
        button.text('Copied!');
        button.addClass('copied');
        
        setTimeout(() => {
            button.text(originalText);
            button.removeClass('copied');
        }, 2000);
    }

    // Функция показа экрана ввода удалена

    // Показать ошибку
    function showError(message) {
        // Простое уведомление об ошибке
        alert(message);
    }

    // Инициализация при загрузке документа
    $(document).ready(function() {
        console.log('Document ready');
        console.log('Found business hypothesis generator:', $('.business-hypothesis-generator').length);
        if ($('.business-hypothesis-generator').length) {
            console.log('Initializing business hypothesis generator');
            initBusinessHypothesisGenerator();
        }
    });

})(jQuery);
