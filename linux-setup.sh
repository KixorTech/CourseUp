#! /bin/sh

rm -rf ../../../../var/www/html/*
mkdir ../../../../var/www/html/CourseUp
cp -r base/* ../../../../var/www/html
cp base/.htaccess ../../../../var/www/html
cp -r * ../../../../var/www/html/CourseUp