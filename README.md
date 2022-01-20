# Live Demo Reset

[SEE LIVE DEMO HERE](http://live-demo-reset.phploaded.com/ "SEE LIVE DEMO HERE")

## Introduction

**Live Demo Reset** is a PHP, MYSQL and jQuery based script that **automatically resets a live website**. This plugin is best suitable for developers who want to showcase their website, theme, plugin or other web work that have a possibility where other people can mess up the demo.

*For example: consider that you have a gallery script that you want to showcase, with admin panel where other users can also edit or delete the images. Now there are people who will do funny things to your demo. Like uploading nude or violent photos, writing bad words, etc. Hence you want an automatic and easy solution that helps you with this. This script will reset your demo completely and automatically, giving you peach of mind.* 

## Features
- Timer is displayed for user to know that demo will reset soon. Only admin can set, at what interval demo files will reset. Time expiration is counted by server itself and not it does not depend on the JavaScript timer, for better security.
- Reset files : All files in the demo folder are deleted and restored from a backup folder, that can be renamed to whatever the admin likes so that it is not predictable and hence safe.
- Reset mySQL database : Whole mySQL database is made empty by deleting all tables. This data is restored from a phpMyAdmin SQL dump file, that is also kept on server. Admin can change its name to prevent being misused or download by other people.
- Kill Cookies and PHP Sessions : This is done to ensure user privacy, data safety and any conflicts that may occur due to previous user sessions or cookies.

## Installation

Your live demo must be setup first and should be in completely working condition. If not, please do it first before proceeding.
- Take a backup of your files and keep it for future use, if anything goes wrong.
- Download the zip file and extract the contents.
- Upload the contents of 'script_files' folder to your demo's root folder so that reset-includes folder exists directly inside the demo.
- Create a phpMyAdmin dump or export of your live demo's database to SQL file(".sql"). Now rename this file to something that other people can't predict and upload it inside the reset-includes folder on your server.
- Create a folder with any name inside reset-includes folder so that it is unpredictable to other people. This folder serves as backup of your files. Now copy all your files from root of demo to inside the folder you just created except reset-includes folder.
- Open 'reset-includes/reset-demo.js' in a text editor and change the value of demo_reset_domain in line 2 so that it reads something like this

`demo_reset_domain = 'http://your-demo-url-here.com/';`  please note that URL must end with slash at the end.
- Open 'functions.php' in a text editor and change the values as needed. Everything is explained in php comments in that file. Save the changes and close the file once you are done.
- On all of your demo pages, please include this javascript and css in head tag after including latest jQuery. If your demo does not uses jQuery, it will be included automatically by the script.Please note that you have to make these changes to the backup folder files and not the files kept in your demo's root folder.
```html
<link rel="stylesheet" href="http://your-demo-url-here.com/reset-includes/style.css" type="text/css" />
<script type="text/javascript" src="http://your-demo-url-here.com/reset-includes/reset-demo.js"></script>
```

- Installation is complete.



## Usage Instructions
There is no need to do anything additional. If your integration was successful, you should see a timer at right bottom of the screen, when it hits zero, demo will reset.


## Points to Remember
- This script uses jQuery, without jQuery this plugin will not work. jQuery is automatically included if not found.
- If you want to make some changes in your demo, never edit the files in demo's root as they will be overwritten by backup folder files automatically. You must make all changes inside backup folder files only, so that when demo resets itself, you will be able to see the changes.
- It is advisable to not to visit the demo page in browser until integration is fully completed.
