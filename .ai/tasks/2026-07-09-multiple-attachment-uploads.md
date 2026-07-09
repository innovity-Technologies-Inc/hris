# Task: Multiple Attachment Uploads for Career Movements

## Overview
Implement multiple attachment upload capabilities for Transfers, Promotions, Demotions, Increments, and Decrements, including backend polymorphic database integration, service layer handling, and frontend view updates.

## Tasks Completed
1. [x] **Database Migration**: Create `movement_attachments` polymorphic table migration and run it.
2. [x] **Polymorphic Model**: Create `MovementAttachment` model with `attachable()` morphTo relation.
3. [x] **Model Relations**: Add `attachments()` morphMany relation to `Transfer`, `Promotion`, `Demotion`, `Increment`, and `Decrement`.
4. [x] **Service Layer Implementation**:
   - Centralize storage in `TransferServices` inside `storeTransfer()`.
   - Add `handleAttachments()` helper in `PayrollServices` and integrate it into store/update methods.
5. [x] **Form Request Validation**: Update `StoreTransferRequest` to validate files.
6. [x] **Frontend Form Updates**:
   - Add file inputs with `multiple` selection to Transfer, Increment, Decrement, Promotion, and Demotion forms.
   - Set `enctype="multipart/form-data"` on forms.
   - Refactor Axios scripts to compile and post inputs as `FormData`.
7. [x] **Detail View Updates**: Display downloadable attachment list/badges in view templates.
8. [x] **Automated Tests**: Write `MovementAttachmentTest` and verify all tests pass successfully.
9. [x] **Project Documentation**: Document the upload system architecture and update the project doc.
