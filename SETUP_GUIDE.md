# LankaIntern Setup Guide

## "Failed to fetch" Error - Common Fixes

### ✅ Step 1: Check How You're Opening the Website

**WRONG WAY** ❌:
- Double-clicking `signup.html` 
- URL looks like: `file:///C:/xampp/htdocs/internship_search/signup.html`

**CORRECT WAY** ✓:
- Open XAMPP Control Panel
- Start **Apache** and **MySQL**
- Open browser and type: `http://localhost/internship_search/signup.html`

### ✅ Step 2: Verify XAMPP is Running

1. Open **XAMPP Control Panel**
2. Make sure **Apache** shows a green "Running" status
3. Make sure **MySQL** shows a green "Running" status
4. If not running, click "Start" for each

### ✅ Step 3: Import the Database

1. Open browser: `http://localhost/phpmyadmin`
2. Click "New" to create database
3. Name it: `internship_db`
4. Click on `internship_db` in left sidebar
5. Click "Import" tab
6. Choose file: `C:\xampp\htdocs\internship_search\sql\database.sql`
7. Click "Go"

### ✅ Step 4: Test Database Connection

Open this link in your browser:
`http://localhost/internship_search/test_connection.php`

If you see "Database connected successfully", you're good to go!

## Quick Access Links

- **Sign Up**: http://localhost/internship_search/signup.html
- **Login**: http://localhost/internship_search/login.html
- **Dashboard**: http://localhost/internship_search/index.html

## Still Having Issues?

Check the browser console:
1. Press F12 in your browser
2. Go to "Console" tab
3. Try signing up again
4. Share any red error messages you see
