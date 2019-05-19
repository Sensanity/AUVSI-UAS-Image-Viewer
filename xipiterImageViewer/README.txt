Couple things to note:

1) Make sure to download XAMPP (64 or 32 bit).
2) project folders for XAMPP are stored in C:\xampp\htdocs\name-of-your-project-folder
3) Hit start on Apache to start the localhost. Hit start on MySQL to start the database server.
4) To run a file in the project folder, go onto a browser and begin the url
    with http://localhost/name-of-your-project-folder/name-of-your-file(php,js,html, etc.)
5) This project uses a database:
    * Open phpMyAdmin by hitting the Admin button next to Start/Stop in MySQL
    * RUN the db_connect.php (http://localhost/xipiterImageViewer/db_connect.php) file to
      setup the database for this project in phpMyAdmin.
    * The database is called "xipiter".
    * The table is called "selected_images". All of the images and corresponding text will be stored is this table.
    * There will be messages if table/database is created successfully.
    * Make sure in phpMyAdmin.

6) Insert all the image files into folder named "collections".
7) run main.php (http://localhost/xipiterImageViewer/main.php)
