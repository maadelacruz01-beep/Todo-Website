-- 02_create_tables.sql
USE TodoDB;
GO

IF OBJECT_ID('dbo.Tasks', 'U') IS NULL
CREATE TABLE dbo.Tasks (
    TaskID      INT IDENTITY(1,1) PRIMARY KEY,                   -- auto-numbered primary key
    TaskName    NVARCHAR(255)  NOT NULL,                         -- short task title
    Description NVARCHAR(1000) NULL,                             -- optional details
    Deadline    DATE           NULL,                             -- optional due date
    Status      NVARCHAR(20)   NOT NULL
                CONSTRAINT DF_Tasks_Status DEFAULT 'Not Started' -- new tasks start here
                CONSTRAINT CK_Tasks_Status
                    CHECK (Status IN ('Not Started', 'In Progress', 'Completed')),
    CreatedAt   DATETIME2(0)   NOT NULL DEFAULT SYSDATETIME()    -- creation timestamp
);
GO