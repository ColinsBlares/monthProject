# Тема месячного проекта - "Сайт для комуникации для ТСЖ (Жильцы и администраторы)"

# Что есть на сайте 
- [x] Авторизация
- [x] Система администрирования
- [ ] Система уведомлений
- [x] Система чата

# Стек проекта
### **Frontend**
   **HTML** – Основная разметка страницы
   **Bootstrap** - для стилизации и создания адаптивного дизайна (подключается через CDN).
   **Custom CSS** - для темной темы, подключается локально
### JavaScript
  **jQuery** - для удобного управления DOM и обработки событий (подключается через CDN).
**EmojioneArea** - плагин для добавления эмодзи в текстовое поле (подключается через CDN).
  Встроенный JavaScript для управления темной темой
### Backend
   **PHP** - серверный язык программирования для обработки логики, работы с сессиями, аутентификации пользователя, обработки формы и взаимодействия с базой данных.



# База данных
База данных курсовой работы была улучшена и были добавлены 2 таблицы:
1. Таблица для сообщений ```messgages```
   ```sql
   CREATE TABLE `messages` (
     `id` int NOT NULL AUTO_INCREMENT,
     `sender_id` int DEFAULT NULL,
     `receiver_id` int DEFAULT NULL,
     `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
     `is_read` tinyint(1) DEFAULT '0',
     `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY (`id`),
     KEY `sender_id` (`sender_id`),
     KEY `receiver_id` (`receiver_id`),
     CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `residents` (`id`),
     CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `residents` (`id`)
   ) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3;
   ```
2. Таблица для объявлений ```announcements```
   ```sql
      CREATE TABLE `announcements` (
     `id` int NOT NULL AUTO_INCREMENT,
     `title` varchar(255) NOT NULL,
     `content` text NOT NULL,
     `start_date` date NOT NULL,
     `end_date` date NOT NULL,
     `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
     `importance` enum('low','medium','high') NOT NULL DEFAULT 'low',
     PRIMARY KEY (`id`)
   ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;
   ```
