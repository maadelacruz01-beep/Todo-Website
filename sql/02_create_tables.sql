-- 02_create_tables.sql
USE TodoDB;
GO

IF OBJECT_ID('dbo.Tasks', 'U') IS NULL
CREATE TABLE dbo.Tasks (
    TaskID    INT IDENTITY(1,1) PRIMARY KEY,                 -- auto-numbered primary key
    TaskName  NVARCHAR(255) NOT NULL,                        -- task description
    Status    NVARCHAR(20)  NOT NULL DEFAULT 'Pending'       -- Pending or Completed
              CONSTRAINT CK_Tasks_Status CHECK (Status IN ('Pending', 'Completed')),
    CreatedAt DATETIME2(0)  NOT NULL DEFAULT SYSDATETIME()   -- creation timestamp
);
GO
