# Requirements for Multiple Attachment Uploads in Career Movements

## 1. Background
Career movements (Transfers, Promotions, Demotions, Increments, Decrements) require physical documentation (such as approval letters, certificates, and recommendation notes) to be uploaded and preserved for audit trails. A polymorphic multiple attachment upload system is needed to support uploading and viewing these files dynamically.

## 2. Functional Requirements
- **Multiple File Upload**: Support uploading multiple files (PDFs, images, documents) simultaneously during the creation or edit phase of:
  - Transfers
  - Promotions
  - Demotions
  - Increments
  - Decrements
- **Attachment Viewer**: Display downloadable file links in the detail views of each career movement module.
- **Validation**: Restrict attachment uploads to valid files with a maximum size of 10MB per file.

## 3. Technical Requirements
- **Polymorphic Schema**: Implement a single `movement_attachments` database table mapping to models polymorphically (`attachable_id`, `attachable_type`).
- **Polymorphic Morph Map**: Define custom morph mappings in [AppServiceProvider](file:///P:/Project/Web/hrms/app/Providers/AppServiceProvider.php) so that the database stores clean type labels (such as `'transfer'`, `'increment'`) in the `attachable_type` column instead of absolute class namespace paths.
- **Axios FormData**: Upgrade AJAX/Axios handlers in views to compile and post inputs via `FormData` objects to enable multipart file transport.
- **Service Layer Processing**: Centralize file storage and association logic inside the service layers (`PayrollServices` and `TransferServices`) rather than in controllers.
- **Pest Verification**: Implement feature tests to verify that files upload, store, and associate correctly.
