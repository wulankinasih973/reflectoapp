# Web-Based Self-Reflection Application with AES-256 Encryption and RBAC

This application is a **web-based self-reflection system** developed as part of an undergraduate thesis research, focusing on improving user data security through the implementation of **field-level encryption using the AES-256 algorithm** and **Role-Based Access Control (RBAC)**.

## Development Objectives
The main objectives of developing this application are:
- To protect sensitive personal reflection data of users
- To restrict user access rights based on defined roles
- To apply the principle of *selective encryption* to journal data
- To enhance data confidentiality and authorization aspects in web-based applications

## Key Security Features
- **Field-Level Encryption (AES-256)**  
  Encryption is applied only to the `isi_jurnal` field, which contains users’ personal reflections. Mini assessment score fields (mood, stress, and anxiety) are not encrypted because they are used for data analysis and visualization purposes.

- **Role-Based Access Control (RBAC)**  
  The system defines two main roles:
  - **User**: Create and manage personal reflection journals
  - **Admin**: Access aggregated statistical data without the ability to view users’ journal contents

- **Selective Encryption**  
  Encryption is selectively applied only to sensitive data to maintain a balance between data security and system performance.

## Application Features
- User login and registration
- User dashboard
- Self-reflection journal entry
- Mini assessments (mood, anxiety, stress)
- User profile management
- Account deletion request form (contact admin)
- Admin dashboard and statistical data export

## Technologies Used
- **Framework**: Laravel
- **Programming Language**: PHP
- **Database**: MySQL
- **Security**:
  - AES-256-CBC (Laravel Encryption)
  - Role-Based Access Control (RBAC) Middleware

## Testing Methods
- **Functional Testing**  
  Ensures that all application features operate according to user requirements.
- **Security Testing**  
  Verifies the effectiveness of data encryption and role-based access restrictions.

## Research Context
This application was developed as an implementation of an undergraduate thesis entitled:

> **“Implementation of Field-Level Encryption Using AES-256 and Role-Based Access Control (RBAC) to Enhance Data Security in a Web-Based Self-Reflection Application”**