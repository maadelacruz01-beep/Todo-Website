-- 03_sample_data.sql
USE TodoDB;
GO

INSERT INTO dbo.Tasks (TaskName, Description, Deadline, Status) VALUES
    (N'Install IIS role on Windows Server', N'Add the Web Server role and the CGI feature.',        '2026-10-23', 'Completed'),
    (N'Install PHP and SQL Server drivers', N'PHP 8.5 NTS, ODBC Driver 18 and pdo_sqlsrv.',         '2026-10-24', 'Completed'),
    (N'Deploy the To-Do website',           N'Copy the site to C:\inetpub\todoapp and test CRUD.',  '2026-10-28', 'In Progress'),
    (N'Configure Windows Firewall rules',   N'Allow port 80. Open 1433 only if the DB is remote.',  '2026-10-30', 'Not Started'),
    (N'Take a VM snapshot',                 NULL,                                                   NULL,         'Not Started');
GO

SELECT * FROM dbo.Tasks;
GO