-- 01_create_database.sql
-- Run in SSMS as an administrator (Windows auth or sa).
IF DB_ID('TodoDB') IS NULL
    CREATE DATABASE TodoDB;
GO
