# Тема месячного проекта - "Сайт для комуникации для ТСЖ (Жильцы и администраторы)"

# Что есть на сайте 
- [ ] Авторизация
- [ ] Система администрирования
- [ ] Система уведомлений
- [ ] Система чата

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
