-- 04_create_app_user.sql
-- Dedicated least-privilege account for the website (never use sa).
-- CHANGE the password below before running, and never paste it in your report.
USE master;
GO
IF NOT EXISTS (SELECT 1 FROM sys.server_principals WHERE name = 'todo_app')
    CREATE LOGIN todo_app WITH PASSWORD = 'TODO-CIT401IT41S1!',
                               CHECK_POLICY = ON,
                               DEFAULT_DATABASE = TodoDB;
GO

USE TodoDB;
GO
IF NOT EXISTS (SELECT 1 FROM sys.database_principals WHERE name = 'todo_app')
    CREATE USER todo_app FOR LOGIN todo_app;
GO

-- Only the four CRUD permissions, only on the Tasks table.
GRANT SELECT, INSERT, UPDATE, DELETE ON dbo.Tasks TO todo_app;
GO