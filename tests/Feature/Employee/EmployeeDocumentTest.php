<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->employee = Employee::factory()->create();
    $this->user = User::factory()->create(['user_type' => 'company', 'employee_id' => $this->employee->id]);
    $this->actingAs($this->user);
});

test('can upload and store multiple employee documents', function () {
    $employee = $this->employee;

    $response = $this->post(route('employee.profile.documents.store', $employee->id), [
        'documents' => [
            [
                'title' => 'Passport',
                'file' => UploadedFile::fake()->create('passport.pdf', 100, 'application/pdf')
            ],
            [
                'title' => 'ID Card',
                'file' => UploadedFile::fake()->image('id_card.jpg')
            ]
        ]
    ]);

    $response->assertSessionHas('alert-type', 'success');

    expect(EmployeeDocument::count())->toBe(2);
    
    $passport = EmployeeDocument::where('title', 'Passport')->first();
    expect($passport->file_type)->toBe('pdf');
    expect($passport->employee_id)->toBe($employee->id);
});

test('can delete an employee document', function () {
    $employee = $this->employee;
    $document = EmployeeDocument::create([
        'employee_id' => $employee->id,
        'title' => 'Test Document',
        'file_path' => 'test/path.pdf',
        'file_type' => 'pdf'
    ]);

    $response = $this->delete(route('employee.profile.documents.delete', ['id' => $employee->id, 'document_id' => $document->id]));

    $response->assertSessionHas('alert-type', 'success');
    expect(EmployeeDocument::count())->toBe(0);
});
