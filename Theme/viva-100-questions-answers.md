# Lost & Found - Viva Practice (100 Questions and Answers)

Use these for final viva practice. Answers are in simple English and based on this project’s UI + code flow.

---

## A. Project Basics and Architecture

1. **Q: What is this project?**  
   **A:** It is a Lost & Found web platform where users report lost/found items and connect with each other.

2. **Q: Which architecture pattern is used?**  
   **A:** Custom PHP MVC (Model-View-Controller).

3. **Q: What is the main entry file?**  
   **A:** `public/index.php`.

4. **Q: Why do we use `public/.htaccess`?**  
   **A:** To rewrite clean URLs to `index.php?url=...`.

5. **Q: What does the router class do?**  
   **A:** `app/Core/App.php` maps URL parts to controller, method, and parameters.

6. **Q: What does `Controller.php` do?**  
   **A:** It provides helper methods to load models and views.

7. **Q: What does `Database.php` do?**  
   **A:** It creates one shared PDO DB connection using singleton pattern.

8. **Q: Where are app constants defined?**  
   **A:** In `config/config.php`.

9. **Q: Where are common helper functions?**  
   **A:** In `includes/helpers.php`.

10. **Q: Give full request flow in one line.**  
    **A:** Browser -> `.htaccess` -> `index.php` -> Router -> Controller -> Model -> DB -> View -> Browser.

11. **Q: Why is MVC useful here?**  
    **A:** It separates UI, logic, and DB access, so code is cleaner and easier to maintain.

12. **Q: Where are controllers located?**  
    **A:** `app/Controllers`.

13. **Q: Where are models located?**  
    **A:** `app/Models`.

14. **Q: Where are views located?**  
    **A:** `resources/views`.

15. **Q: Where are CSS and JS files located?**  
    **A:** `public/assets/css` and `public/assets/js`.

---

## B. Routing and URL Handling

16. **Q: How does `/item/show/12` get processed?**  
    **A:** Router calls `ItemController->show(12)`.

17. **Q: What happens if controller is not given in URL?**  
    **A:** It defaults to `HomeController`.

18. **Q: What happens if method is not given?**  
    **A:** It defaults to `index`.

19. **Q: Where is URL parsing done?**  
    **A:** In `App::parseUrl()`.

20. **Q: What is `BASE_URL` used for?**  
    **A:** Building absolute links and assets paths in views/helpers.

21. **Q: Why are redirects centralized in helper?**  
    **A:** So all redirects use the same logic and base URL.

22. **Q: How are route parameters passed?**  
    **A:** As an array to controller method via `call_user_func_array`.

23. **Q: Which file rewrites every request to front controller?**  
    **A:** `public/.htaccess`.

24. **Q: Why keep `public` as web root?**  
    **A:** To avoid direct exposure of core/config files.

25. **Q: What is one limitation of this router?**  
    **A:** No named routes or route middleware stack like modern frameworks.

---

## C. Authentication and Session

26. **Q: Which controller handles login/register?**  
    **A:** `AuthController`.

27. **Q: Which model checks user credentials?**  
    **A:** `User` model.

28. **Q: How are passwords stored?**  
    **A:** Hashed using `password_hash`.

29. **Q: How are passwords verified?**  
    **A:** Using `password_verify`.

30. **Q: Which helper checks login before protected pages?**  
    **A:** `requireLogin()`.

31. **Q: Where is session started?**  
    **A:** In `config/config.php`.

32. **Q: What session values are set after login?**  
    **A:** `user_id`, `username`, `user_email`, and `is_admin`.

33. **Q: How does logout work?**  
    **A:** Session values are unset and session is destroyed.

34. **Q: Where is hardcoded admin login implemented?**  
    **A:** In `AuthController@login()`.

35. **Q: What is flash message concept in this project?**  
    **A:** One-time success/error messages stored in session and shown in layout.

---

## D. User Features

36. **Q: Which page shows user’s own reports?**  
    **A:** `user/dashboard`.

37. **Q: Which controller method renders dashboard?**  
    **A:** `UserController@dashboard`.

38. **Q: Which file handles profile UI?**  
    **A:** `resources/views/user/profile.php`.

39. **Q: Which method updates profile details?**  
    **A:** `UserController@updateProfile()`.

40. **Q: How is profile image upload handled?**  
    **A:** Validates extension, stores in `public/uploads/avatars/`, updates DB.

41. **Q: What happens to old avatar when new one is uploaded?**  
    **A:** Old file is deleted if it exists.

42. **Q: How are trust badges shown in UI?**  
    **A:** Badge value from DB is styled in profile/dashboard views.

43. **Q: Can admin users open normal user dashboard?**  
    **A:** No, they are redirected to admin dashboard.

44. **Q: Which model method gets user by ID?**  
    **A:** `User::findById()`.

45. **Q: Which model method updates profile?**  
    **A:** `User::updateProfile()`.

---

## E. Item Module (Core Business)

46. **Q: Which controller handles item create/search/show?**  
    **A:** `ItemController`.

47. **Q: Which model stores item records?**  
    **A:** `Item` model.

48. **Q: Which view is item listing page?**  
    **A:** `resources/views/items/index.php`.

49. **Q: Which view is item detail page?**  
    **A:** `resources/views/items/show.php`.

50. **Q: Which view is item create form?**  
    **A:** `resources/views/items/create.php`.

51. **Q: What fields are captured in create item flow?**  
    **A:** Title, type, category, description, location, contact, optional reward, coordinates, images.

52. **Q: How are multiple images handled?**  
    **A:** Uploaded in loop, first image as primary, all saved to `report_images`.

53. **Q: Where is item search query built?**  
    **A:** `Item::searchItems()`.

54. **Q: Which filters are supported in search?**  
    **A:** Keyword, type, category, location, date range.

55. **Q: How is item resolved by owner?**  
    **A:** `ItemController@resolve` calls `Item::markResolved`.

56. **Q: How are potential matches shown on detail page?**  
    **A:** Opposite type + same category + open reports query in `ItemController@show`.

57. **Q: Why is item detail page important in viva?**  
    **A:** It integrates many features: gallery, map, chat, share links, status timeline.

58. **Q: What does AI generate button do in item create UI?**  
    **A:** It generates template description text using JavaScript.

59. **Q: What does map picker in create page do?**  
    **A:** User clicks map and hidden latitude/longitude fields are filled.

60. **Q: What is the value of category auto-tag logic?**  
    **A:** It improves UX by suggesting category from title/description keywords.

---

## F. Messaging and Real-Time Behavior

61. **Q: Which controller manages chat?**  
    **A:** `MessageController`.

62. **Q: Which model manages message data?**  
    **A:** `Message` model.

63. **Q: Which view shows chat inbox list?**  
    **A:** `resources/views/messages/index.php`.

64. **Q: Which view shows single chat conversation?**  
    **A:** `resources/views/messages/chat.php`.

65. **Q: How are messages loaded in real-time?**  
    **A:** Frontend polls `/message/apiGetMessages/{report_id}`.

66. **Q: How are typing indicators implemented?**  
    **A:** `apiSetTyping` stores status in `chat_status` table.

67. **Q: How is online status checked?**  
    **A:** By comparing `users.last_activity` timestamp to recent time.

68. **Q: What DB table stores comments/messages?**  
    **A:** `comments`.

69. **Q: How are threaded replies supported?**  
    **A:** Through `parent_id` field in comments.

70. **Q: Why are chat APIs returning JSON?**  
    **A:** So JavaScript can update UI without full page reload.

---

## G. Map Feature

71. **Q: Which controller renders map page?**  
    **A:** `MapController@index`.

72. **Q: Which endpoint gives live markers?**  
    **A:** `/map/api_markers`.

73. **Q: Which view contains map UI?**  
    **A:** `resources/views/map.php`.

74. **Q: Which external library is used for map?**  
    **A:** Leaflet.

75. **Q: What happens if an item has no coordinates?**  
    **A:** Fallback coordinates are generated on map page script.

76. **Q: Why map is useful in this system?**  
    **A:** It helps users locate nearby lost/found reports quickly.

77. **Q: What data is shown in map marker popup?**  
    **A:** Title, location, and detail-page link.

78. **Q: How often can map refresh happen?**  
    **A:** Current script refreshes periodically (every few seconds).

79. **Q: Which CSS file styles map page?**  
    **A:** `public/assets/css/map.css`.

80. **Q: Which model mainly supports map data retrieval?**  
    **A:** Item data, through DB queries in controller.

---

## H. Admin Module

81. **Q: Which controller handles admin panel?**  
    **A:** `AdminController`.

82. **Q: How is admin access protected?**  
    **A:** `requireAdminLogin()` checks `$_SESSION['is_admin']`.

83. **Q: Which page shows admin dashboard metrics?**  
    **A:** `resources/views/admin/dashboard.php`.

84. **Q: Which page manages users and badges?**  
    **A:** `resources/views/admin/users.php`.

85. **Q: Which page manages reports?**  
    **A:** `resources/views/admin/reports.php`.

86. **Q: Which page edits a selected report?**  
    **A:** `resources/views/admin/edit_report.php`.

87. **Q: How is user ban/unban done?**  
    **A:** `AdminController@toggle_ban` + `User::toggleBan`.

88. **Q: How is badge assignment done?**  
    **A:** `AdminController@assign_badge` + `User::updateBadge`.

89. **Q: Which module handles site settings?**  
    **A:** `SystemConfig` model + admin settings view.

90. **Q: Which module handles announcements?**  
    **A:** `Announcement` model + `admin/announcements` page.

91. **Q: How are announcements shown globally?**  
    **A:** Header loads active announcements and renders alert bar.

92. **Q: Which admin feature exports CSV?**  
    **A:** `AdminController@export_data`.

93. **Q: Which admin feature handles SQL backup download?**  
    **A:** `AdminController@backup_download`.

94. **Q: Which feature restores SQL backup file?**  
    **A:** `AdminController@restore_backup`.

95. **Q: Which page shows real-time monitor cards?**  
    **A:** `resources/views/admin/monitor.php`.

---

## I. UI Components and "What this component does"

96. **Q: What does layout header component do?**  
    **A:** It provides common page shell: title, global CSS, nav menu, language switch, theme toggle, announcements.

97. **Q: What does layout footer component do?**  
    **A:** It provides common footer links and optional JS inclusion.

98. **Q: What does flash toast component do?**  
    **A:** Shows temporary success/error messages to improve user feedback.

99. **Q: What does the item timeline component on detail page do?**  
    **A:** Visually shows report lifecycle: reported -> activity -> resolved.

100. **Q: What does direct chat button component do?**  
     **A:** It opens report-specific conversation so finder and owner can coordinate safely.

---

## Extra Quick-Fire Practice Prompts (Use in Mock Viva)

- Explain one full feature from UI click to DB write.
- Explain one security measure and where it is implemented.
- Explain one known linking issue and how you would fix it.
- Explain why MVC improved your team development.
- Explain one place where UX was improved using JavaScript.
- Explain one backend validation and one frontend validation.

