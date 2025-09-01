# Project Map — Admin & Client Panels (Laravel + Filament)

Кратко: два отдельных интерфейса (Admin `/admin` и Client `/client`) на Filament Panels. Клиент видит только `/client/verification`, пока его статус `pending`; полный функционал доступен, когда пользователь `approved` ИЛИ у него есть хотя бы один счёт со статусом `Active`. Реализовано: создание/редактирование аккаунтов, транзакций (по ТЗ), вывод средств (карта), загрузка документов с миниатюрами, автогенерация номера счёта, типы аккаунтов, и полноценные админ-вкладки у пользователя.

## Быстрый старт

- Требования: PHP 8.2+, Composer, Node.js (для Vite), SQLite (по умолчанию).
- Установка зависимостей: `composer install` и при необходимости `npm install`.
- Настройка `.env` (уже задано для SQLite):
  - `APP_URL=http://127.0.0.1:8001`
  - `DB_CONNECTION=sqlite`
  - `DB_DATABASE=database/database.sqlite`
  - `FILESYSTEM_DISK=public`
- Первичный запуск:
  - Создать файл БД: `touch database/database.sqlite`
  - Ключ приложения: `php artisan key:generate`
  - Миграции: `php artisan migrate`
  - Сиды: `php artisan db:seed`
  - Ссылка на storage: `php artisan storage:link`
  - Старт: `php artisan serve --host=127.0.0.1 --port=8001`

Дефолтные доступы (смените после входа):

- Root (admin): `root@system.com` / `password` — создаётся сидером
  - `database/seeders/RootUserSeeder.php:1`
- Admin (admin): `admin@system.com` / `password`
  - `database/seeders/DatabaseSeeder.php:1`
- Demo Client (client): `client@demo.com` / `password` (статус pending)
  - `database/seeders/DatabaseSeeder.php:1`

## Панели и навигация

- Client Panel `/client` — авторизация и регистрация включены, доступ для не-админов.
  - Провайдер: `app/Providers/Filament/ClientPanelProvider.php:1`
  - Ограничение по статусу: middleware `app/Http/Middleware/CheckVerificationStatus.php:1` пропускает только `/client/verification`, логин/логаут и auth-роуты при `pending`. Полный доступ даётся, если пользователь `approved` или есть хотя бы один счёт со статусом `Active`.
  - Дашборд: `app/Filament/Client/Pages/Dashboard.php:1` (виджеты ниже).
- Admin Panel `/admin` — доступ только для админов.

## Данные и связи (модели)

- User: `app/Models/User.php:1`
  - Связи: `accounts()`, `transactions()`, `withdrawals()`, `documents()`.
  - Поля: `is_admin`, `verification_status` (`pending|approved`), `main_balance`, `currency`.
- Account: `app/Models/Account.php:1`
  - Поля: `number` (вместо name), `type`, `organization`, `beneficiary`, `investment_control`, `balance`, `currency`, `term` (expiration), `status`, `is_default`.
  - Касты: `term:date`, `balance:decimal:2`, `is_default:boolean`.
- Transaction: `app/Models/Transaction.php:1`
  - Поля (по ТЗ): `created_at`, `from`, `to`, `type (classic|fast|conversion|hold)`, `amount`, `currency`, `status (pending|approved|blocked|hold)`.
- Withdrawal: `app/Models/Withdrawal.php:1`
  - Поля: `amount`, `method (card|bank|crypto)`, `from_account_id`, `requisites (JSON)`, `status`, `applied`, `applied_at`.
  - Бизнес-логика: при `approved` списывает средства один раз (из счёта или `main_balance`).
- Document: `app/Models/Document.php:1` — путь к файлу, тип, статус.

## Конфигурация

- Валюты: `config/currencies.php:1` (`default='EUR'`, `allowed=[USD, EUR, ...]`).
- Типы счётов: `config/accounts.php:1`
  - `Classic, ECN, PAMM, Gold, Silver, Platinum, VIP, Credit, Personal`.
- Демо-маршруты темы: `config/integration.php:1` → `ENABLE_THEME_ROUTES=false` по умолчанию (если включить — используется `routes/web.php:1`).

## Аккаунты (Accounts)

- Генерация номера счёта: `app/Support/AccountNumber.php:1`
  - Берёт `MAX(CAST(number AS INTEGER))` и подбирает следующий свободный.
- Админ: `app/Filament/Resources/AccountResource.php:1`
  - Поле `Account number` (уникальность на уровне валидации), кнопка Generate, тип из `config/accounts.php`, баланс с суффиксом валюты, `is_default`.
- Клиент: `app/Filament/Client/Resources/AccountResource.php:1`
  - Только свои записи; `Account number` с автогенерацией, тип, баланс с суффиксом валюты, `organization`, `client_initials`, `broker_initials`.
- Вкладка у пользователя (админ): `app/Filament/Resources/UserResource/RelationManagers/AccountsRelationManager.php:1`
  - Полный CRUD по аккаунтам прямо в карточке пользователя; заголовок — номер счёта.
- Виджеты клиента:
  - Текущий (основной) счёт: `app/Filament/Client/Widgets/BrokerageAccountWidget.php:1`, вью `resources/views/filament/client/widgets/brokerage-account-widget.blade.php:1`.
  - Все счета карточками/таблицей: `app/Filament/Client/Widgets/AllAccountsWidget.php:1`, `resources/views/filament/client/widgets/all-accounts-widget.blade.php:1`.
  - Табличный вид (Transit): `app/Filament/Client/Widgets/TransitAccountWidget.php:1`, `resources/views/filament/client/widgets/transit-account-widget.blade.php:1`.

Примечание: во всём проекте заменено `Invoice → Account` (runtime-ошибки от старых переменных устранены).

## Транзакции (Transactions)

- Миграция: `database/migrations/2025_08_31_212308_create_transactions_table.php:1`
  - Колонки по ТЗ, индексы на `type`, `status`, `currency`.
- Глобальный ресурс (админ): `app/Filament/Resources/TransactionResource.php:1`
  - Поля: `Date & Time`, `User`, `Account (фильтр по User)`, `From`, `To`, `Type`, `Amount`, `Currency`, `Status`.
  - Таблица с фильтрами по статусу/типу/валюте/пользователю.
- Вкладка у пользователя (админ): `app/Filament/Resources/UserResource/RelationManagers/TransactionsRelationManager.php:1`
  - Создание/редактирование транзакций для выбранного клиента.
- Валидация при создании/редактировании:
  - Страница Create/Edit: `app/Filament/Resources/TransactionResource/Pages/CreateTransaction.php:1`, `.../EditTransaction.php:1`
  - Проверяется, что `account_id` принадлежит выбранному `user_id`, нормализуется `amount`, подставляется валюта пользователя при пустом `currency`.

## Вывод средств (Withdrawals)

- Клиент (карта): `app/Filament/Client/Resources/WithdrawalResource.php:1`, страница `.../Pages/CreateWithdrawal.php:1`
  - Поля: `Withdrawal Amount`, `Card Number` (маска 4-4-4-4), `Expiration (MM/YY)` (авто-слэш), `CVC`.
  - В БД сохраняются только маска карты (`masked`, `last4`, `exp_month`, `exp_year`, `cvc_provided`) в `requisites` — полных данных карты нет.
- Админ: `app/Filament/Resources/WithdrawalResource.php:1`
  - Видит `Method=Card` и рид-онли поля маски/срока; для `Method=Bank` включаются банковские реквизиты.
  - Сумма отображается с валютой пользователя. Кнопка Create возвращена в список клиента: `app/Filament/Client/Resources/WithdrawalResource/Pages/ListWithdrawals.php:1`.
- Бизнес-логика списания: `app/Models/Withdrawal.php:1` (в `booted`) — единоразово при `approved`.

## Документы (Documents)

- Клиент: загрузка и предпросмотр до/после сохранения — `resources/views/livewire/client/upload-document.blade.php:1`, Livewire-компонент `app/Livewire/Client/DocumentUploadForm.php`.
- Админ: ресурс `app/Filament/Resources/DocumentResource.php:1` с превью `resources/views/filament/admin/document-preview.blade.php:1` и загрузкой только `image/*, application/pdf`.
- Хранилище: ссылки относительные (`/storage/...`) для стабильной работы независимо от `APP_URL`.
- Симлинк: `php artisan storage:link` обязателен.

## Аутентификация и UI

- Регистрация в клиентской панели включена: `app/Providers/Filament/ClientPanelProvider.php:1` (`->registration()`).
- Экран логина (текст «User registration» и увеличенный размер ссылки на регистрацию):
  - Оверрайд: `resources/views/vendor/filament-panels/pages/auth/login.blade.php:1`.
- Исправление Blade-компонента макета (ошибка `app-layout`): `resources/views/components/app-layout.blade.php:1`.

## Команды разработчика

- Очистить кэши: `php artisan optimize:clear`
- Очистить кэш представлений: `php artisan view:clear`
- Прогреть кэш (опционально): `php artisan optimize`
- Проверка статуса миграций: `php artisan migrate:status`
- Полная пересборка БД (разрушительно): `php artisan migrate:fresh --seed`

## Частые проблемы и решения

- 500 и `no such table: transactions` → выполнить миграции (`php artisan migrate`), для SQLite убедиться, что существует `database/database.sqlite`.
- «Unable to locate component [app-layout]» → компонент добавлен (`resources/views/components/app-layout.blade.php:1`), после изменения кэши: `php artisan view:clear`.
- Миниатюры документов не отображаются / «вопросительный знак» → убедиться в наличии симлинка `public/storage` и что ссылки относительные (`/storage/...`). Выполнить: `php artisan storage:link`.
- TypeError в Withdrawals при редактировании (тип `Get`) → в админ-ресурсе исправлено на `Filament\Forms\Get` (`app/Filament/Resources/WithdrawalResource.php:1`).

## Безопасность и приватность

- Дефолтные пароли (root/admin) — смените после установки.
- Данные карт не сохраняются (PAN/CVC) — в БД остаётся только маска и служебные признаки.

## Индекс ключевых файлов

- Панель клиента: `app/Providers/Filament/ClientPanelProvider.php:1`
- Middleware: `app/Http/Middleware/CheckVerificationStatus.php:1`
- Аккаунты: `app/Filament/Resources/AccountResource.php:1`, `app/Filament/Client/Resources/AccountResource.php:1`, `app/Filament/Resources/UserResource/RelationManagers/AccountsRelationManager.php:1`, `app/Support/AccountNumber.php:1`
- Транзакции: `app/Filament/Resources/TransactionResource.php:1`, `app/Filament/Resources/TransactionResource/Pages/CreateTransaction.php:1`, `.../EditTransaction.php:1`, миграция `database/migrations/2025_08_31_212308_create_transactions_table.php:1`
- Вывод средств: `app/Filament/Client/Resources/WithdrawalResource.php:1`, `.../Pages/CreateWithdrawal.php:1`, `app/Filament/Resources/WithdrawalResource.php:1`
- Документы: `app/Filament/Resources/DocumentResource.php:1`, `resources/views/filament/admin/document-preview.blade.php:1`, `resources/views/livewire/client/upload-document.blade.php:1`
- Виджеты: `app/Filament/Client/Widgets/BrokerageAccountWidget.php:1`, `resources/views/filament/client/widgets/brokerage-account-widget.blade.php:1`, `app/Filament/Client/Widgets/AllAccountsWidget.php:1`, `resources/views/filament/client/widgets/all-accounts-widget.blade.php:1`, `app/Filament/Client/Widgets/TransitAccountWidget.php:1`, `resources/views/filament/client/widgets/transit-account-widget.blade.php:1`
- Конфиг: `config/currencies.php:1`, `config/accounts.php:1`, `config/integration.php:1`
- Сиды: `database/seeders/RootUserSeeder.php:1`, `database/seeders/DatabaseSeeder.php:1`

## Заметки по чистке

- Мусорные файлы в корне (`, [, 0,, success,, warning,, …) удалены.
- `Invoice` → `Account` заменено по коду приложения (вендор и логи не трогаются).

Если нужна интеграция дополнительных проверок (например, уникальный индекс БД на `accounts.number`) — дам команду миграции и применю. Также могу скрыть меню разделов до применения миграций для ещё более мягкого UX.
