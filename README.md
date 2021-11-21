# pasteque-clicker
A clicker game based on watermelons. Heavily inspired by Cookie Clicker, although much less advanced.

Instructions :
==============

    Create the database by importing the "./database.sql" file through phpmyadmin

    Set the username and password for the connection to local mysql server in: 
        -> "./resources/includes/header.php", lines 3 and 4
        -> "./resources/server/save.php", lines 5 and 6

    The website's main point of entry is the "./index.php" file. 
    Create an account (or log in to an existing one) through the buttons in the page header.
    There are two already existing accounts: the main admin and the user.

    Info for the main admin account:
        -> username: admin
        -> password: admin (very secure, I know)
    
    Info for the user account:
        -> username: user
        -> password: user (easier to remember)
    
    
Project architecture :
======================

root folder:
|   admin: ================================ Everything related to site administration
|   |   - deletedUsers.php -------------------- The list of all deleted users
|   |   - globalSettings.php ------------------ The page where admins are able to change global game settings
|   |   - portal.php -------------------------- The main admin portal
|   |   - userList.php ------------------------ The list of all active users
|   |   - userView.php ------------------------ The page where admins can edit a specific user's info
|
|   game: ================================= Everything related to the game
|   |   - game.php ---------------------------- The game page, where users can play pasteque clicker
|
|   profile: ============================== Everything related to the user's profile
|   |   - edit.php ---------------------------- Where a user can edit their username, reset their progress, or delete their profile
|   |   - login.php --------------------------- Where a user can login
|   |   - logout.php -------------------------- Where a user can logout
|   |   - profile.php ------------------------- Where a user can see their profile
|   |   - register.php ------------------------ Where a user can create an account
|   |   - settings.php ------------------------ Where a user can edit their in-game settings
|
|   resources: ============================ Everything that isn't a page by itself and is used by other pages
|   |   img: ================================== All images used in the site
|   |   |   - pasteque.png ------------------------ The image used for the pasteque you click on in-game
|   |
|   |   includes: ============================= All .php files included in other .php files
|   |   |   - header.php -------------------------- The site's header
|   |
|   |   scripts: ============================== All the JavaScript used in the site
|   |   |   - game.js ----------------------------- The game's client-side logic, used by the game.php file
|   |
|   |   server: =============================== All the .php files related to AJAX requests
|   |   |   - save.php ---------------------------- The php code that runs everytime the game is saved client-side
|   |
|   |   styles: =============================== All the CSS used in the site
|   |   |   - global.css -------------------------- The css that applies to the whole site
|   
|   - .gitignore -------------------------- Git configuration file
|   - favicon.ico ------------------------- The website's favicon
|   - index.php --------------------------- The website's main entry point
|   - README.md --------------------------- The website's README. You're reading it!


What you can do :
=================

    As a user:
        -> Create and log in and out of your profile
        -> Play the game (which right now only means clicking on a button to make a number go up, or buying as many cursors as you can to do it for you)
        -> Set your personal settings for the frequency at which the game will:
            - automatically save your game in the database
            - update values on the screen when playing (supposedly for performance reasons, but right now only a proof of concept, as it won't noticeably affect performance)
        -> Change your username (if it's not already taken)
        -> Reset your progress (permanent)
        -> Delete your profile (permanent)
        
    As an admin:
        -> Everything the user can do
        -> Delete standard user profiles (not permanent)
        -> Change default game settings such as:
            - save frequency (if the user has set a personal setting, this is overriden)
            - update frequency (can be overriden too)
            - Default price for cursors
            - The "price increase factor" (explained later in this document)

    As the main admin:
        -> Everything admins can do (except rename, delete or reset their profile)
        -> Promote and demote admins (this allows the main admin to delete admin profiles, simply by demoting them before deletion)
        -> View the deleted users, who deleted them and when, and delete them permanently or restore their profile


How the game works :
====================

    The game has a set base price for cursors, as well as a "price increase factor". This is used to calculate the price for the next purchase of a cursor through this equation:

        base_cursor_price + price_increase_factor ^ ( number_of_cursors_owned / 10 )

    This allows for a gradual increase of the price (and therefore an increase in difficulty) as the game progresses
    There is probably a better way to regulate this increase in price as the user progresses, but this is the best I have found so far


Reflections on my progress :
============================

    What I would have liked to add :
        -> A simple way to add more upgrades as an admin (for now, this requires adding lines in various places of the code and changing the database directly, which is very long and tedious to do, not to mention extremely unoptimized)
        -> A way for users to view other users and possibly a friend system
        -> Two-factor authentication (via email)
        -> Confirmations when editing user info or deleting a profile
        -> Make it look better (it's really ugly)
    
    Most of these would require me to rewrite a major portion of the code and change how a lot of systems work. I believe my mistake was that I jumped straight into coding, without laying out my ideas and planning beforehand (this wouldn't have allowed me to do everything, as I would still have new ideas afterwards, but it would still help). I lost quite a lot of time trying out things and realising that what I wanted to do next wasn't possible with the way I had done things, forcing me to rewrite it multiple times, with different project architectures and configurations. 
    For example, at first I wanted to use the MVC architecture, as I had used it beforehand with the Ac'Lab's website and quite enjoyed it. However I quickly realised I did not know how to do AJAX with this architecture (it's probably really simple, but I couldn't figure it out), and I got quite frustrated. I then decided to completely rewrite the website without the MVC architecture, which made things a lot simpler, but also a lot messier.

    I am definitely going to rewrite this more cleanly after this assignment is done, just so I have a version with which I am (somewhat) happy. 
    Nevertheless, this project was extremely fun and I enjoyed every single bit of it :)