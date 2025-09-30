# Ads Creative Generator Plugin - Architecture Documentation

## Общая архитектура

### Архитектурный паттерн
Плагин использует **MVC-подобную архитектуру** с разделением на:
- **Model** - работа с данными (БД, API)
- **View** - пользовательский интерфейс (HTML, CSS, JS)
- **Controller** - бизнес-логика (PHP обработчики)

### Технологический стек
- **Backend**: PHP 7.4+, WordPress 5.0+
- **Frontend**: HTML5, CSS3, JavaScript (jQuery)
- **API**: OpenAI ChatGPT API
- **Database**: MySQL (WordPress tables)
- **Build Tools**: Нативные инструменты WordPress

## Детальная архитектура

### 1. Основной файл плагина (`ads-creative-generator.php`)

```php
class AdsCreativeGenerator {
    // Инициализация плагина
    public function __construct()
    
    // Активация/деактивация
    public function activate()
    public function deactivate()
    
    // AJAX обработчики
    public function analyze_website()
    public function get_analysis_details()
    public function test_openai_api_key()
    
    // API интеграция
    private function call_openai_api()
    private function get_website_content()
    
    // Утилиты
    private function create_tables()
    private function save_analysis()
}
```

**Ответственности:**
- Регистрация WordPress хуков
- Обработка AJAX запросов
- Интеграция с OpenAI API
- Управление базой данных
- Безопасность и валидация

### 2. Фронтенд архитектура (`frontend.js`)

```javascript
// Основные модули
- initAdsCreativeGenerator()     // Инициализация
- bindEvents()                   // Обработка событий
- analyzeWebsite()              // Анализ сайта
- performAnalysis()             // AJAX запрос
- displayAnalysisResults()      // Отображение результатов
- generateSectionCreatives()    // Генерация креативов
- copyMiniCreative()            // Копирование
- showDemoData()               // Демо режим (закомментирован)
```

**Поток данных:**
1. Пользователь вводит URL
2. Валидация URL на клиенте
3. AJAX запрос к серверу
4. Обработка ответа
5. Отображение результатов
6. Интерактивность (копирование)

### 3. Админ панель

#### Структура админки
```
admin/
├── admin-page.php      # Главная страница
└── settings-page.php   # Настройки API
```

**Функциональность:**
- Управление API ключами
- Инструкции по использованию
- Отключенная статистика
- Тестирование API подключения

### 4. База данных

#### Таблица `wpie_ads_creative_analyses`
```sql
CREATE TABLE wpie_ads_creative_analyses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    website_url VARCHAR(500) NOT NULL,
    analysis_data LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45)
);
```

**Использование:**
- Хранение результатов анализа
- История анализов (отключено)
- Отслеживание использования (отключено)

### 5. API интеграция

#### OpenAI ChatGPT Integration
```php
private function call_openai_api($website_content) {
    // 1. Подготовка промпта
    // 2. Отправка запроса к OpenAI
    // 3. Обработка ответа
    // 4. Парсинг JSON
    // 5. Возврат структурированных данных
}
```

**Промпт структура:**
- Анализ контента сайта
- Генерация 6 категорий анализа
- Создание 18 креативов
- JSON формат ответа

### 6. Безопасность

#### Уровни защиты
1. **WordPress Security**
   - Nonce проверки
   - Capability проверки
   - Санитизация данных

2. **API Security**
   - Безопасное хранение ключей
   - Валидация входных данных
   - Обработка ошибок

3. **Frontend Security**
   - XSS защита
   - CSRF токены
   - Валидация на клиенте

### 7. Производительность

#### Оптимизации
- **Кэширование**: localStorage для URL
- **Минификация**: Компактные CSS/JS
- **Асинхронность**: AJAX без блокировки UI
- **Ленивая загрузка**: Результаты по требованию

#### Мониторинг
- Логирование ошибок
- Отслеживание производительности API
- Мониторинг использования ресурсов

## Диаграмма архитектуры

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   WordPress     │    │   OpenAI API    │
│   (Browser)     │    │   Backend       │    │   (External)    │
├─────────────────┤    ├─────────────────┤    ├─────────────────┤
│ • HTML/CSS/JS   │    │ • PHP Plugin    │    │ • ChatGPT API   │
│ • jQuery        │◄──►│ • AJAX Handlers │◄──►│ • Content       │
│ • User Interface│    │ • Database      │    │   Analysis      │
│ • Validation    │    │ • Security      │    │ • Creative      │
└─────────────────┘    └─────────────────┘    │   Generation    │
                                              └─────────────────┘
```

## Поток выполнения

### 1. Инициализация
```
WordPress Load → Plugin Activation → Hook Registration → Asset Loading
```

### 2. Анализ сайта
```
User Input → URL Validation → AJAX Request → Server Processing → 
OpenAI API Call → Response Processing → Frontend Display
```

### 3. Генерация креативов
```
Analysis Data → Creative Generation → Section Display → 
User Interaction → Copy to Clipboard
```

## Расширяемость

### Точки расширения
1. **Новые категории креативов**
   - Добавление в промпт OpenAI
   - Обновление фронтенда
   - Расширение базы данных

2. **Дополнительные AI модели**
   - Абстракция API слоя
   - Конфигурация моделей
   - Fallback механизмы

3. **Экспорт данных**
   - PDF генерация
   - Excel экспорт
   - API endpoints

### Модульность
- Независимые компоненты
- Слабая связанность
- Высокая когезия
- Переиспользуемый код

## Мониторинг и отладка

### Логирование
```php
error_log('Analysis started for URL: ' . $url);
error_log('OpenAI API response: ' . json_encode($response));
error_log('Analysis completed successfully');
```

### Отладка
- Консольные логи в JavaScript
- WordPress debug.log
- AJAX response мониторинг
- API response валидация

## Развертывание

### Требования к серверу
- PHP 7.4+ с cURL
- MySQL 5.7+
- WordPress 5.0+
- SSL сертификат (для API)

### Конфигурация
- OpenAI API ключ
- WordPress настройки
- Плагин активация
- Шорткод размещение

### Мониторинг после развертывания
- Проверка API подключения
- Тестирование функциональности
- Мониторинг производительности
- Отслеживание ошибок
