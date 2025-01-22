### Класс JsonResponse ### 
*Controller/JsonResponse.php*

- Некорректное расположение в папке с контроллерами. 
  - Перенести в отдельную папку `Utils/Http/JsonResponse.php`
- Класс не реализует логику для работы с `json`.
  - Добавить логику для работы с `json`, включая кодирование данных и установку заголовка `Content-Type: application/json`. 
