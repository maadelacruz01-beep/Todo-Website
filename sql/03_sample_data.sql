-- 03_sample_data.sql
USE TodoDB;
GO

INSERT INTO dbo.Tasks (TaskName, Status) VALUES
    (N'Install IIS role on Windows Server', 'Completed'),
    (N'Install PHP and SQL Server drivers',  'Completed'),
    (N'Deploy the To-Do website',            'Pending'),
    (N'Configure Windows Firewall rules',    'Pending'),
    (N'Take a VM snapshot',                  'Pending');
GO

SELECT * FROM dbo.Tasks;
GO
