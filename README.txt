LAUTECH CS Department Website
==============================
Complete project — Days 1 through 5 + all revisit additions

SETUP (3 steps only)
---------------------

STEP 1 — Extract
  Extract this zip into: C:\wamp\www\
  Result:                C:\wamp\www\lautech_csd\

STEP 2 — Create & Import Database
  a. Open http://localhost/phpmyadmin/
  b. Click "New" in the left sidebar
  c. Database name: lautech_csd
  d. Collation:     utf8mb4_general_ci
  e. Click "Create"
  f. Click the lautech_csd database (left sidebar)
  g. Click the "SQL" tab at the top
  h. Open the file database.sql from this folder
  i. Copy the entire contents and paste into the SQL box
  j. Click "Go"

STEP 3 — Open the site
  http://localhost/lautech_csd/

That's it. Everything else is already done.


IF YOUR MYSQL HAS A PASSWORD
-----------------------------
Open config/db.php and change:
  define('DB_PASS', '');
to:
  define('DB_PASS', 'your-password-here');


ADDING YOUR LOGO
-----------------
Drop your LAUTECH logo file into:
  assets/images/lautech-logo.png
It will appear in the header and footer automatically.


ADDING STAFF PHOTOS
--------------------
Drop staff photo files into:
  assets/images/staff/
Filename must match the 'photo' column in the staff database table.
Default fallback shows a placeholder if no photo is found.


FOLDER STRUCTURE
-----------------
lautech_csd/
├── config/
│   └── db.php              Database connection
├── includes/
│   ├── header.php           <head> + CSS links
│   ├── navbar.php           Announcement bar + navigation
│   └── footer.php           Footer + Bootstrap JS
├── assets/
│   ├── css/style.css        All custom styles
│   ├── images/              Logo and general images
│   │   └── staff/           Staff photos
│   └── js/                  Custom JS (Day 6+)
├── admin/                   Admin panel (Day 6)
├── uploads/                 Student file uploads (auto-created)
│   └── .htaccess            Security: blocks script execution
├── index.php                Homepage
├── about.php                About the department
├── courses.php              Course listings (DB-driven)
├── staff.php                Staff directory (DB-driven)
├── calendar.php             Academic calendar (DB-driven)
├── regulations.php          Regulations (DB-driven)
├── profile.php              Student file submission form
├── submit_handler.php       Form processor (never visit directly)
├── verify.php               Submission confirmation
├── news.php                 News (stub — Day 7)
├── research.php             Research (stub — Day 7)
├── resources.php            Student resources (stub — Day 7)
├── contact.php              Contact us (stub — Day 7)
├── database.sql             Full database setup script
└── README.txt               This file


LAUTECH COLOURS
----------------
Green: #006400   Gold: #FFD700
To change, edit the :root block in assets/css/style.css


DAYS REMAINING
--------------
Day 6 — Admin Panel (login, dashboard, manage all content)
Day 7 — Remaining pages, polish, hosting
