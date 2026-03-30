                    Updated Work.md
                                    👤 1️⃣ ANUTHTHARA – Authentication & Users + Legal

📂 Controllers
hello
app/Controllers/AuthController.php - ✅

app/Controllers/UserController.php - ✅

📂 Models

app/Models/User.php - ✅

📂 Views – Auth

resources/views/auth/login.php - ✅

resources/views/auth/register.php - ✅

resources/views/auth/forgot.php - ✅

resources/views/auth/reset.php - ✅

📂 Views – Pages

resources/views/pages/contact.php - ✅

📂 Legal

legal/privacy.php - ✅

legal/terms.php - ✅

📂 CSS

public/assets/css/login.css - ✅

public/assets/css/register.css - ✅

public/assets/css/auth/ - ✅

📂 JS

public/assets/js/auth.js - ✅

C:\xampp\ht docs\Lost & Found\Lost-and-Found\app\Services\NotificationService.php - ✅
C:\xampp\htdocs\Lost & Found\Lost-and-Found\public\assets\css\Profile.css - ✅
C:\xampp\htdocs\Lost & Found\Lost-and-Found\public\assets\css\success.css - ✅
C:\xampp\htdocs\Lost & Found\Lost-and-Found\resources\views\items\index.php - ✅
C:\xampp\htdocs\Lost & Found\Lost-and-Found\resources\views\admin\login.php - ✅
C:\xampp\htdocs\Lost & Found\Lost-and-Found\public\assets\css\admin\users.css - ✅
C:\xampp\htdocs\Lost & Found\Lost-and-Found\app\Controllers\MapController.php - ✅
C:\xampp\htdocs\Lost & Found\Lost-and-Found\public\.htaccess - ✅


new -- resources/views/layouts/header.php


                                        👤 2️⃣ PAWAN – Item System + Search + Map + Home

main view home - index.php

📂 Controllers

app/Controllers/ItemController.php

📂 Models

app/Models/Item.php

📂 Views – Items

resources/views/items/create.php

resources/views/items/show.php

📂 Views – Search & Map

resources/views/search.php

resources/views/map.php

📂 Views – Main

resources/views/home.php

📂 CSS

public/assets/css/item-form.css

public/assets/css/item-detail.css

public/assets/css/search.css

public/assets/css/map.css

public/assets/css/home.css

📂 JS

public/assets/js/map.js

pawans files explaination ------------------------------------------------------------------------------------
📂 Controllers
app/Controllers/ItemController.php

👉 The brain for items.

Handles things like:

adding a lost/found item

showing an item

searching items

sending item data to views

Example actions inside it:

createItem()

showItem()

searchItems()

Basically:
User clicks something → Controller decides what happens.

📂 Models
app/Models/Item.php

👉 Talks to the database.

Handles:

saving item data

getting items from DB

searching items

Example methods:

saveItem()

getItemById()

searchItems()

Basically:
Controller asks → Model fetches/saves data.

📂 Views – Items
resources/views/items/create.php

👉 UI page to add an item

User fills:

item name

description

location

image upload

Form sends data → ItemController.

resources/views/items/show.php

👉 Item details page

Shows:

item photo

description

location

contact owner button

Like a product page on Daraz/Amazon but for lost items.

📂 Views – Search & Map
resources/views/search.php

👉 Search results page

Shows:

list of items matching search

filters

item cards

Example:

Lost iPhone
Lost Wallet
Found Keys
resources/views/map.php

👉 Map showing item locations

Pins show where items were lost/found.

Example:

📍 Wallet lost here
📍 Phone found here

Usually uses Google Maps or Leaflet.

📂 Views – Main
resources/views/home.php

👉 Homepage of the website

Shows things like:

latest lost items

latest found items

search bar

quick navigation

Basically the landing page.

📂 CSS (Styling)
public/assets/css/item-form.css

👉 Styles the add item page

Controls:

form layout

buttons

spacing

public/assets/css/item-detail.css

👉 Styles the item details page

Controls:

image size

description layout

contact button

public/assets/css/search.css

👉 Styles the search results page

Controls:

item cards

grid layout

filters

public/assets/css/map.css

👉 Styles the map page

Controls:

map container

markers

responsive layout

public/assets/css/home.css

👉 Styles the homepage

Controls:

hero section

featured items

layout

🧠 Simple way to remember
Type Role
Controller Logic / actions
Model Database
View UI pages
CSS Styling

Or even simpler:

User → Controller → Model → Database
↓
View → User



                                            👤 3️⃣ PABASARA – Messaging + Informational Pages

📂 Controllers

app/Controllers/MessageController.php✅

📂 Models

app/Models/Message.php✅

📂 Views – Messages

resources/views/messages/index.php✅

resources/views/messages/chat.php✅

📂 Views – Pages

resources/views/pages/about.php✅

resources/views/pages/faq.php✅

📂 CSS

public/assets/css/chat.css✅

public/assets/css/messages/✅

📂 Controllers

C:\xampp\htdocs\Lost & Found\Lost-and-Found\resources\views\admin\login.php✅
C:\xampp\htdocs\Lost & Found\Lost-and-Found\resources\views\success_stories.php✅
C:\xampp\htdocs\Lost & Found\Lost-and-Found\public\assets\css\admin\announcements.css✅
C:\xampp\htdocs\Lost & Found\Lost-and-Found\public\assets\css\admin\reports.css✅

app/Core/App.php-pabasara

app/Core/Controller.php-pabasara

app/Core/Database.php -pabasara
app/Core/session.php -pabasara

📂 Config

includes/helpers.php-pabasara

public/index.php - pabasara

new--resources/views/layouts/footer.php

👤 4️⃣ THEJANU – Admin + System Core + Shared Infrastructure

(Owner of architecture. No one edits core without approval.)

📂 Controllers

app/Controllers/AdminController.php

app/Controllers/HomeController.php

📂 Models

app/Models/Admin.php
C:\xampp\htdocs\Lost & Found\Lost-and-Found\app\Models\Announcement.php

📂 Admin Views

resources/views/admin/dashboard.php

resources/views/admin/items.php

resources/views/admin/reports.php

resources/views/admin/users.php

📂 Dashboard

resources/views/dashboard.php

📂 CSS

public/assets/css/admin-main.css

public/assets/css/admin-users.css

public/assets/css/admin/

public/assets/css/dashboard.css
C:\xampp\htdocs\Lost & Found\Lost-and-Found\public\assets\css\admin\admin_dashboard.css
public/assets/css/style.css

📂 JS

public/assets/js/main.js

                                                 ⚙️ CORE SYSTEM (SHARED INFRASTRUCTURE)


📂 DOCUMENTATION & OTHER FILES

👤 tHEJANU

docs/README.md
