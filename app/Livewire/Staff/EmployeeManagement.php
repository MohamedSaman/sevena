<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

#[Title("Staff Dashboard")]
#[Layout("components.layouts.staff")]
class EmployeeManagement extends Component
{
    use WithFileUploads;

    public $empCode, $fingerprint_id, $photo, $fname, $lname, $gender, $dob, $nic, $email, $phone, $address, 
           $department, $designation, $salary_type, $basic_salary, $joining_date, $status = 'active';
    
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showViewModal = false;
    public $employeeId;
    public $existingPhoto;

    protected $rules = [
        'empCode' => 'required|string|unique:employees,empCode',
        'fname' => 'required|string|max:255',
        'lname' => 'required|string|max:255',
        'gender' => 'required|string|in:male,female,other',
        'dob' => 'required|date',
        'nic' => 'required|string|max:20',
        'email' => 'required|email|unique:employees,email',
        'phone' => 'required|string|max:20',
        'address' => 'required|string',
        'department' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'salary_type' => 'required|in:daily,monthly',
        'basic_salary' => 'required|numeric|min:0',
        'joining_date' => 'required|date',
        'status' => 'required|in:active,inactive',
        'fingerprint_id' => 'nullable|string|max:255',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ];

    public function addEmployee()
    {
        $this->validate();

        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('employees', 'public');
        }

        Employee::create([
            'empCode' => $this->empCode,
            'fingerprint_id' => $this->fingerprint_id,
            'photo' => $photoPath,
            'fname' => $this->fname,
            'lname' => $this->lname,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'nic' => $this->nic,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'department' => $this->department,
            'designation' => $this->designation,
            'salary_type' => $this->salary_type,
            'basic_salary' => $this->basic_salary,
            'joining_date' => $this->joining_date,
            'status' => $this->status,
        ]);

        $this->reset();
        $this->dispatch('closeModal');
        session()->flash('message', 'Employee added successfully.');
    }

    public function openEditModal($emp_id)
    {
        $employee = Employee::findOrFail($emp_id);
        $this->employeeId = $emp_id;
        $this->empCode = $employee->empCode;
        $this->fingerprint_id = $employee->fingerprint_id;
        $this->existingPhoto = $employee->photo;
        $this->photo = null;
        $this->fname = $employee->fname;
        $this->lname = $employee->lname;
        $this->gender = $employee->gender;
        $this->dob = $employee->dob ? Carbon::parse($employee->dob)->format('Y-m-d') : null;
        $this->nic = $employee->nic;
        $this->email = $employee->email;
        $this->phone = $employee->phone;
        $this->address = $employee->address;
        $this->department = $employee->department;
        $this->designation = $employee->designation;
        $this->salary_type = $employee->salary_type;
        $this->basic_salary = $employee->basic_salary;
        $this->joining_date = $employee->joining_date ? Carbon::parse($employee->joining_date)->format('Y-m-d') : null;
        $this->status = $employee->status;
        $this->showEditModal = true;
        $this->showDeleteModal = false;
        $this->showViewModal = false;
        $this->dispatch('openEditModal');
    }

    public function openViewModal($emp_id)
    {
        $employee = Employee::findOrFail($emp_id);
        $this->employeeId = $emp_id;
        $this->empCode = $employee->empCode;
        $this->fingerprint_id = $employee->fingerprint_id;
        $this->existingPhoto = $employee->photo;
        $this->fname = $employee->fname;
        $this->lname = $employee->lname;
        $this->gender = $employee->gender;
        $this->dob = $employee->dob ? Carbon::parse($employee->dob)->format('Y-m-d') : 'N/A';
        $this->nic = $employee->nic;
        $this->email = $employee->email;
        $this->phone = $employee->phone;
        $this->address = $employee->address;
        $this->department = $employee->department;
        $this->designation = $employee->designation;
        $this->salary_type = $employee->salary_type;
        $this->basic_salary = $employee->basic_salary;
        $this->joining_date = $employee->joining_date ? Carbon::parse($employee->joining_date)->format('Y-m-d') : 'N/A';
        $this->status = $employee->status;
        $this->showViewModal = true;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->dispatch('openViewModal');
    }

    public function updateEmployee()
    {
        $this->validate(array_merge($this->rules, [
            'empCode' => 'required|string|unique:employees,empCode,' . $this->employeeId . ',emp_id',
            'email' => 'required|email|unique:employees,email,' . $this->employeeId . ',emp_id',
        ]));

        $employee = Employee::findOrFail($this->employeeId);
        $photoPath = $employee->photo;

        if ($this->photo) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $this->photo->store('employees', 'public');
        }

        $employee->update([
            'empCode' => $this->empCode,
            'fingerprint_id' => $this->fingerprint_id,
            'photo' => $photoPath,
            'fname' => $this->fname,
            'lname' => $this->lname,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'nic' => $this->nic,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'department' => $this->department,
            'designation' => $this->designation,
            'salary_type' => $this->salary_type,
            'basic_salary' => $this->basic_salary,
            'joining_date' => $this->joining_date,
            'status' => $this->status,
        ]);

        $this->reset();
        $this->showEditModal = false;
        $this->dispatch('closeModal');
        session()->flash('message', 'Employee updated successfully.');
    }

    public function confirmDelete($emp_id)
    {
        $this->employeeId = $emp_id;
        $this->showDeleteModal = true;
        $this->showEditModal = false;
        $this->showViewModal = false;
        $this->dispatch('openDeleteModal');
    }

    public function deleteEmployee()
    {
        $employee = Employee::findOrFail($this->employeeId);
        if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
            Storage::disk('public')->delete($employee->photo);
        }
        $employee->delete();
        $this->showDeleteModal = false;
        $this->reset();
        $this->dispatch('closeModal');
        session()->flash('message', 'Employee deleted successfully.');
    }

    public function closeModal()
    {
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->showViewModal = false;
        $this->reset();
        $this->dispatch('closeModal');
    }

    public function render()
    {
        $employees = Employee::all();
        return view('livewire.staff.employee-management', [
            'employees' => $employees
        ]);
    }
}