# 🚀 Настройка автоматического деплоя для trygo

## ✅ Что уже настроено автоматически:
- SSH URL репозитория: `git@github.com:sigayyury-ai/trygo.git`
- Скрипт деплоя: `deploy.sh`
- Webhook endpoint: `webhook-deploy.php`

## 🔧 Что нужно сделать вручную (5 минут):

### Шаг 1: Добавить Deploy Key в GitHub
1. Перейдите: https://github.com/sigayyury-ai/trygo/settings/keys
2. Нажмите **"Add deploy key"**
3. **Title**: `trygo-hosting-deploy`
4. **Key**: Скопируйте и вставьте этот ключ:
```
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDCE1gaD40RFdkQR1e0exkQ7ctT0xgCvRoXIpffaZETAnng/hhHgM5uOUXNUEMXqvSOJBMmpabDDxf+Z7zbOCYFYqRuD2EBmU718VydIa7E0emqXSOge6+2q2B69MNtiNBBxCOtk8ziM2bn5+fbmTCBqFHWRYY5RLcD3XQLd5oRa5qA1cgv8vKGwrPJyFQFIDp9/0lAthMIM8MGNtKMXLaIGRVxQFK2CcusnaR8uV8U3xGLQpGPKFJBN4xKq2atyYAIrNcDpkWj0c0zZp3gfxjWdIoc7tdng7Z2K9BJwQEVfYmnfvprmCo/oyIxTrKiKpc1hbdE1osevx8oRd5sGkAl
```
5. **НЕ ставьте галочку** "Allow write access"
6. Нажмите **"Add key"**

### Шаг 2: Настроить Webhook
1. Перейдите: https://github.com/sigayyury-ai/trygo/settings/hooks
2. Нажмите **"Add webhook"**
3. **Payload URL**: `https://ваш-домен.com/webhook-deploy.php`
   (замените "ваш-домен.com" на ваш реальный домен)
4. **Content type**: `application/json`
5. **Secret**: `trygo-webhook-secret-2024`
6. **Events**: выберите "Just the push event"
7. Нажмите **"Add webhook"**

### Шаг 3: Обновить webhook-deploy.php
Замените строку 8 в файле `webhook-deploy.php`:
```php
$github_secret = 'trygo-webhook-secret-2024';
```

## 🧪 Тестирование:
После настройки:
1. Сделайте любое изменение в коде
2. Выполните: `git add . && git commit -m "test" && git push`
3. Проверьте файл `deploy.log` - там должны появиться записи о деплое

## 📁 Структура файлов:
```
/home/uroclzzw/public_html/trygo/
├── deploy.sh              # Скрипт автоматического деплоя
├── webhook-deploy.php     # Webhook endpoint
├── deploy.log            # Лог деплоев (создается автоматически)
└── SETUP-DEPLOY.md       # Эта инструкция
```

## 🆘 Если что-то не работает:
1. Проверьте права доступа: `chmod +x deploy.sh`
2. Проверьте логи: `tail -f deploy.log`
3. Проверьте webhook: `curl -X POST https://ваш-домен.com/webhook-deploy.php`

---
**Время настройки: ~5 минут** ⏱️
