# Lost & Found Final Viva Question–Answer Bank (120 Questions)

Use this as a direct practice script. Answers are short and viva-friendly.

## A) Project Overview & Architecture

**Q1. What is this project about?**  
**A:** It is a Lost & Found web platform where users post lost/found reports, search matches, and communicate to recover items.

**Q2. Which architecture pattern is used?**  
**A:** A custom PHP MVC pattern with Controllers, Models, Core classes, and Views.

**Q3. Where is the app entry point?**  
**A:** `public/index.php`.

**Q4. How are routes resolved?**  
**A:** `app/Core/App.php` parses `?url=` and maps first segment to controller, second to method, rest to params.

**Q5. Which class handles routing?**  
**A:** `App\Core\App`.

**Q6. What is the default controller and method?**  
**A:** `HomeController` and `index`.

**Q7. How are classes loaded?**  
**A:** PSR-4 style autoloading in `public/index.php` for namespace `App\`.

**Q8. How is URL rewriting configured?**  
**A:** In `public/.htaccess`, all non-file/non-directory requests go to `index.php?url=...`.

**Q9. Which DB layer is used?**  
**A:** PDO through a singleton in `app/Core/Database.php`.

**Q10. Why singleton for DB?**  
**A:** To reuse one connection object and avoid repeated connection creation.

**Q11. Where are global helpers defined?**  
**A:** `includes/helpers.php`.

**Q12. How are views loaded from controllers?**  
**A:** Via base controller method `$this->view('path', $data)` in `app/Core/Controller.php`.

**Q13. Where are templates stored?**  
**A:** `resources/views/`.

**Q14. Where are static assets stored?**  
**A:** `public/assets/`.

**Q15. What server stack is expected?**  
**A:** Apache + PHP + MySQL (XAMPP setup).

**Q16. Which database is configured by default?**  
**A:** `lost_and_found`.

**Q17. How is BASE_URL set?**  
**A:** Auto-detected in `config/config.php` using scheme, host, and script directory.

**Q18. Where are environment-level constants defined?**  
**A:** `config/config.php`.

**Q19. What is a key architectural advantage here?**  
**A:** Clear separation: controllers handle flow, models handle DB, views handle rendering.

**Q20. What is one architectural limitation?**  
**A:** Some business logic and SQL are still inside controllers/views instead of being fully model/service-driven.

## B) Controllers, Routes, and Features

**Q21. Which controller handles login/register/logout?**  
**A:** `AuthController`.

**Q22. What route handles login form submission?**  
**A:** `POST /auth/login` → `AuthController::login()`.

**Q23. What route handles registration?**  
**A:** `POST /auth/register` → `AuthController::register()`.

**Q24. What route logs out the user?**  
**A:** `/auth/logout` → `AuthController::logout()`.

**Q25. Which controller handles item browsing/search?**  
**A:** `ItemController`.

**Q26. Which method serves advanced filtering?**  
**A:** `ItemController::search()`.

**Q27. What route opens item detail?**  
**A:** `/item/show/{id}`.

**Q28. Which method creates reports?**  
**A:** `ItemController::create()`.

**Q29. How is report resolution handled for users?**  
**A:** `POST /item/resolve/{id}` calls `ItemController::resolve($id)`.

**Q30. Which controller provides map page + markers API?**  
**A:** `MapController`.

**Q31. Route for map marker JSON?**  
**A:** `/map/api_markers`.

**Q32. Which controller manages user dashboard/profile?**  
**A:** `UserController`.

**Q33. Which route shows personal dashboard?**  
**A:** `/user/dashboard`.

**Q34. Which route updates profile data?**  
**A:** `POST /user/updateProfile`.

**Q35. Which controller handles inbox/chat?**  
**A:** `MessageController`.

**Q36. Route for inbox list?**  
**A:** `/message/index`.

**Q37. Route for report-based chat room?**  
**A:** `/message/chat/{report_id}`.

**Q38. Route for AJAX message send?**  
**A:** `POST /message/apiSendMessage`.

**Q39. Route for fetching live messages?**  
**A:** `GET /message/apiGetMessages/{report_id}`.

**Q40. Route for typing indicator updates?**  
**A:** `POST /message/apiSetTyping`.

**Q41. Which controller manages static pages?**  
**A:** `PageController`.

**Q42. How is language switched?**  
**A:** `/page/set_language/{lang}` updates `$_SESSION['lang']`.

**Q43. Which controller handles admin features?**  
**A:** `AdminController`.

**Q44. Admin auth check is done where?**  
**A:** Private method `requireAdminLogin()` in `AdminController`.

**Q45. Route for admin report moderation page?**  
**A:** `/admin/reports`.

**Q46. Route to export CSV?**  
**A:** `/admin/export_data`.

**Q47. Route for backup download?**  
**A:** `/admin/backup_download`.

**Q48. Route for restore from SQL backup?**  
**A:** `POST /admin/restore_backup`.

**Q49. Route for announcement management?**  
**A:** `/admin/announcements`.

**Q50. Route for contact requests review?**  
**A:** `/admin/contact_requests`.

## C) Models and Database

**Q51. Which model handles report data?**  
**A:** `app/Models/Item.php`.

**Q52. Which method gets recent open reports for homepage?**  
**A:** `Item::getRecentReports($limit)`.

**Q53. Which method gets resolved reports for success stories?**  
**A:** `Item::getResolvedReports($limit)`.

**Q54. Which method powers advanced search filters?**  
**A:** `Item::searchItems($keyword, $type, $category_id, $location, $date)`.

**Q55. Which method inserts new report records?**  
**A:** `Item::addReport($data)`.

**Q56. Which model handles user auth/profile/admin-user operations?**  
**A:** `app/Models/User.php`.

**Q57. Which method verifies password during login?**  
**A:** `User::login($email, $password)` using `password_verify`.

**Q58. Which method updates user profile fields?**  
**A:** `User::updateProfile($userId, $data)`.

**Q59. Which method toggles ban status?**  
**A:** `User::toggleBan($userId, $isBanned)`.

**Q60. Which model handles chat and conversation queries?**  
**A:** `app/Models/Message.php`.

**Q61. Which method loads all messages for one report?**  
**A:** `Message::getCommentsByReport($report_id)`.

**Q62. Which method loads conversations for inbox?**  
**A:** `Message::getConversationsForUser($user_id)`.

**Q63. Which method supports attachment-aware insert with schema fallback?**  
**A:** `Message::addCommentWithAttachment(...)`.

**Q64. Which method tracks user activity for online status?**  
**A:** `Message::updateUserActivity($user_id)`.

**Q65. How is typing status persisted?**  
**A:** `Message::setTyping()` writes into `chat_status` with upsert logic.

**Q66. Which method reads typing users within last 5 seconds?**  
**A:** `Message::getTypingStatus($report_id, $exclude_user_id)`.

**Q67. Which table stores users?**  
**A:** `users`.

**Q68. Which table stores lost/found reports?**  
**A:** `reports`.

**Q69. Which table stores categories?**  
**A:** `categories`.

**Q70. Which table stores chat messages?**  
**A:** `comments`.

**Q71. Which table stores announcements?**  
**A:** `announcements`.

**Q72. Which table stores typing state?**  
**A:** `chat_status`.

**Q73. What report status values are used?**  
**A:** Primarily `open`, `resolved`, and in admin flow `removed`.

**Q74. Why are prepared statements important here?**  
**A:** They prevent SQL injection and safely bind user input.

**Q75. How are report-to-user relationships enforced?**  
**A:** Foreign key `reports.user_id -> users.user_id`.

## D) Authentication, Session, and Security

**Q76. Where is session configured?**  
**A:** `config/config.php`.

**Q77. What is the session lifetime?**  
**A:** 7200 seconds (2 hours).

**Q78. Session cookie security flags used?**  
**A:** `httponly` and `samesite=Lax`.

**Q79. How is login requirement enforced?**  
**A:** `requireLogin()` helper checks session and redirects to `/auth/login`.

**Q80. Extra check inside requireLogin besides session exists?**  
**A:** It confirms the user still exists in DB.

**Q81. How are passwords stored?**  
**A:** Hashed with `password_hash()`; verified by `password_verify()`.

**Q82. How is output escaped in views?**  
**A:** With helper `escape()` (htmlspecialchars).

**Q83. How are POST inputs sanitized in registration?**  
**A:** `filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS)`.

**Q84. How is account ban enforced?**  
**A:** On login, `AuthController` blocks users with `is_banned`.

**Q85. Which helper is used for safe redirect?**  
**A:** `redirect($url)` in `helpers.php`.

**Q86. How are flash messages implemented?**  
**A:** Session keys (`flash_success`, `flash_error`) rendered by `displayFlashMessages()`.

**Q87. What is a known security concern in current code?**  
**A:** Hardcoded admin login (`admin@gmail.com` / `1234567890`) in `AuthController::login`.

**Q88. Another security concern in header?**  
**A:** Some inline styles/scripts and dynamic announcement rendering should be tightly controlled.

**Q89. What is the main upload safety layer for profile images?**  
**A:** Extension whitelist and controlled upload path in `UserController::updateProfile`.

**Q90. What is main upload safety layer for report images?**  
**A:** Extension filtering + generated unique filenames in `ItemController::create`.

## E) UI/UX and Function-Level Viva Questions

**Q91. Where is the global navbar and footer loaded?**  
**A:** `resources/views/layouts/header.php` and `footer.php`.

**Q92. How is multilingual text shown in UI?**  
**A:** Via helper `t('key')` and session language.

**Q93. Where does language dropdown action go?**  
**A:** `/page/set_language/{lang}` from header select onchange.

**Q94. Where is the advanced search UI?**  
**A:** `resources/views/items/index.php`.

**Q95. Which UI function auto-submits filters after typing?**  
**A:** Debounced `scheduleSubmit()` in `items/index.php` inline script.

**Q96. Where is map marker rendering logic for map page?**  
**A:** Inline JS inside `resources/views/map.php`.

**Q97. Which function validates lat/lng before marker render?**  
**A:** `validLatLng(lat, lng)` in `map.php`.

**Q98. Where does live map refresh happen?**  
**A:** `refreshMarkers()` in `map.php`, runs with `setInterval(..., 10000)`.

**Q99. Where is item-create location pin selector logic?**  
**A:** Inline Leaflet JS in `resources/views/items/create.php`.

**Q100. Function that shows custom category field when “Other” is selected?**  
**A:** `toggleCustomCategory()` in `items/create.php`.

**Q101. Function that creates template description in create page?**  
**A:** `generateDescription()` in `items/create.php`.

**Q102. Function that auto-tags category from text keywords?**  
**A:** `autoTag()` inside create page DOMContentLoaded script.

**Q103. Where is the item detail gallery and lightbox logic?**  
**A:** `resources/views/items/show.php`.

**Q104. Function to switch main thumbnail image?**  
**A:** `changeMainImage(index)`.

**Q105. Function to open full-screen lightbox?**  
**A:** `openLightbox(index)`.

**Q106. Function to close lightbox?**  
**A:** `closeLightbox(e)`.

**Q107. Function for previous image in lightbox?**  
**A:** `prevLightboxImage(e)`.

**Q108. Function for next image in lightbox?**  
**A:** `nextLightboxImage(e)`.

**Q109. Function that updates lightbox UI state?**  
**A:** `refreshLightbox()`.

**Q110. Function that enables threaded reply mode in item comments?**  
**A:** `setReply(parentId)`.

**Q111. Function that cancels reply mode?**  
**A:** `cancelReply()`.

**Q112. Function that opens printable QR flyer?**  
**A:** `printFlyer()`.

**Q113. Where is WhatsApp contact button built?**  
**A:** In `items/show.php` using sanitized number and prefilled message.

**Q114. Where is direct chat button conditionally shown?**  
**A:** In `items/show.php`, only when messaging is enabled and viewer isn’t owner.

**Q115. Where is inbox list UI?**  
**A:** `resources/views/messages/index.php`.

**Q116. Where is real-time chat UI + polling logic?**  
**A:** `resources/views/messages/chat.php`.

**Q117. Function that fetches chat messages repeatedly?**  
**A:** `fetchMessages()` with 3-second interval.

**Q118. Function that sends chat message via AJAX?**  
**A:** `handleChatSubmit(e)`.

**Q119. Which page has password toggle button logic in inline JS?**  
**A:** `resources/views/auth/login.php`.

**Q120. Which shared JS file contains password strength, email validation, and username check functions?**  
**A:** `public/assets/js/auth.js` with functions like `getPasswordScore`, `validateEmailField`, `checkUsername`, and `showToast`.

## F) Admin, Monitoring, Backup, and Practical Viva

**Q121. How does admin dashboard compute active vs resolved counts?**  
**A:** `AdminController::dashboard()` iterates reports and counts by `status`.

**Q122. How does admin user trust badge update work?**  
**A:** `POST /admin/assign_badge/{user_id}` with allowed badge list validation.

**Q123. How does admin ban/unban a user?**  
**A:** `POST /admin/toggle_ban/{user_id}` updates `is_banned` flag.

**Q124. How does admin filter reports?**  
**A:** `AdminController::reports()` passes filters to `Item::getAdminReports()`.

**Q125. How is report editing done by admin?**  
**A:** `/admin/edit_report/{id}` form then `/admin/update_report/{id}` persists changes.

**Q126. How is monitor stats API implemented?**  
**A:** `/admin/monitor_stats` returns JSON counts from SQL queries.

**Q127. How does backup export work?**  
**A:** `backup_download()` outputs SQL with `SHOW CREATE TABLE` + `INSERT` rows.

**Q128. How does restore work?**  
**A:** Uploaded SQL is split into statements and executed inside DB transaction.

**Q129. How are announcements shown globally to users?**  
**A:** Header loads active announcements from `Announcement` model and renders banner.

**Q130. What is your top improvement proposal for production readiness?**  
**A:** Replace hardcoded admin auth with DB-driven admin table + secure reset flow, then tighten upload and role checks across all endpoints.

## Quick “Where is this function?” Cheat Lines

- `toggleCustomCategory`, `generateDescription`, `autoTag` → `resources/views/items/create.php`  
- `fetchMessages`, `handleChatSubmit`, `renderMessages` → `resources/views/messages/chat.php`  
- `openLightbox`, `printFlyer`, `setReply` → `resources/views/items/show.php`  
- `requireLogin`, `redirect`, `escape`, `t` → `includes/helpers.php`  
- `searchItems`, `addReport` → `app/Models/Item.php`  
- `apiGetMessages`, `apiSendMessage` → `app/Controllers/MessageController.php`  
- `monitor_stats`, `backup_download`, `restore_backup` → `app/Controllers/AdminController.php`

