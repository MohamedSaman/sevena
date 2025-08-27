<div>
    <style>
        [x-cloak] { display: none !important; }
        .employee-item { transition: all 0.3s ease; }
        .employee-item:hover { transform: translateY(-2px); }
        .shadow-soft { box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
    </style>

    <div class="bg-gray-50 min-h-screen"
         x-data="packingModal({
            employees: @js($this->employees),
            products: @js($this->products)
         })"
         x-on:records-saved.window="records = []; selectedEmployees = []; open = false;">

        <div class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-[#0d151c] text-2xl font-bold tracking-tight">Packing Management</h2>

                <!-- Add New Packing Entry Button -->
                <button @click="open = true"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Add New Packing Entry
                </button>
            </div>

            @if (session()->has('packingSaved'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2500)"
                     class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-3">
                    {{ session('packingSaved') }}
                </div>
            @endif

            <!-- Recent Records -->
            <div class="bg-white rounded-xl shadow-soft p-4 mb-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Today Records</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Packed By</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($todayRecords as $row)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $row->id }}</td>
                                    <td class="px-6 py-4">{{ $row->date_packed }}</td>
                                    <td class="px-6 py-4">{{ $row->employee?->fname ?? '—' }}</td>
                                    <td class="px-6 py-4">{{ $row->product?->product_name ?? '—' }}</td>
                                    <td class="px-6 py-4">{{ $row->quantity }}</td>
                                    <td class="px-6 py-4">{{ number_format($row->total_salary, 2) }}</td>
                                    <td class="px-6 py-4 space-x-3">
                                        <!-- Hooks for future edit/delete if needed -->
                                        <button class="text-blue-600 hover:text-blue-800 font-medium disabled:opacity-40" disabled>
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        <button class="text-red-600 hover:text-red-800 font-medium disabled:opacity-40" disabled>
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-6 py-6 text-center text-gray-500">No records for today.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Monthly Summary -->
            <div class="bg-white rounded-xl shadow-soft p-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Total Records This Month</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Month</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php $i = 1; $monthLabel = now()->format('M'); @endphp
                            @forelse ($monthlySummary as $sum)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $i++ }}</td>
                                    <td class="px-6 py-4">{{ $monthLabel }}</td>
                                    <td class="px-6 py-4">{{ $sum->product?->product_name ?? '—' }}</td>
                                    <td class="px-6 py-4">{{ $sum->total_quantity }}</td>
                                    <td class="px-6 py-4">{{ number_format($sum->total_amount, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <button class="text-indigo-600 hover:text-indigo-800 font-medium disabled:opacity-40" disabled>
                                            <i class="fas fa-eye mr-1"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-6 text-center text-gray-500">No data this month.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="open" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-5xl p-6 max-h-[90vh] overflow-y-auto"
                 @keydown.escape.window="open = false">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Packing Management</h2>
                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Employee Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Left Side: Available Employees -->
                    <div class="border border-gray-200 rounded-xl shadow-soft overflow-hidden">
                        <div class="bg-blue-50 p-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-700 flex items-center gap-2">
                                <i class="fas fa-users text-blue-500"></i> Available Employees
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">Select employees to assign packing tasks</p>
                        </div>
                        <div class="h-72 overflow-y-auto p-4 bg-white">
                            <template x-for="(emp, index) in availableEmployees" :key="emp.emp_id">
                                <div class="employee-item flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200 mb-2 shadow-soft hover:shadow-md">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-user text-blue-500"></i>
                                        </div>
                                        <span x-text="emp.fname" class="text-gray-700 font-medium"></span>
                                    </div>
                                    <button @click="selectEmployee(emp)"
                                        class="text-green-600 hover:text-green-800 text-sm font-medium px-3 py-1.5 rounded-lg bg-green-50 hover:bg-green-100 transition flex items-center gap-1">
                                        <i class="fas fa-plus text-xs"></i> Add
                                    </button>
                                </div>
                            </template>
                            <p x-show="availableEmployees.length === 0" class="text-center text-gray-500 py-4">
                                All employees are selected
                            </p>
                        </div>
                    </div>

                    <!-- Right Side: Selected Employees -->
                    <div class="border border-gray-200 rounded-xl shadow-soft overflow-hidden">
                        <div class="bg-green-50 p-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-700 flex items-center gap-2">
                                <i class="fas fa-user-check text-green-500"></i> Selected Employees
                                <span class="ml-auto bg-green-200 text-green-800 px-2 py-1 rounded-full text-xs" x-text="selectedEmployees.length"></span>
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">Employees assigned to packing tasks</p>
                        </div>
                        <div class="h-72 overflow-y-auto p-4 bg-white">
                            <template x-for="(emp, index) in selectedEmployees" :key="emp.emp_id">
                                <div class="employee-item flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200 mb-2 shadow-soft hover:shadow-md">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-user text-green-500"></i>
                                        </div>
                                        <span x-text="emp.fname" class="text-gray-700 font-medium"></span>
                                    </div>
                                    <button @click="removeEmployee(index)"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 transition flex items-center gap-1">
                                        <i class="fas fa-times text-xs"></i> Remove
                                    </button>
                                </div>
                            </template>
                            <p x-show="selectedEmployees.length === 0" class="text-center text-gray-500 py-4">
                                No employees selected yet
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Packing Details Form -->
                <div class="border border-gray-200 rounded-xl shadow-soft p-5">
                    <div class="bg-indigo-50 p-4 -m-5 mb-5 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-700 flex items-center gap-2">
                            <i class="fas fa-boxes text-indigo-500"></i> Packing Details
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="packing-date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input id="packing-date" name="date" type="date" x-model="form.date"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="packing-product" class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                            <select id="packing-product" name="product_id" x-model.number="form.product_id" @change="maybeAutoSalary()"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Product</option>
                                <template x-for="p in products" :key="p.id">
                                    <option :value="p.id" x-text="p.product_name"></option>
                                </template>
                            </select>
                            <p class="text-xs text-gray-500 mt-1" x-show="form.product_id">
                                Rate: <span x-text="formatMoney(productRate(form.product_id))"></span>
                            </p>
                        </div>
                        <div>
                            <label for="packing-quantity" class="block text-sm font-medium text-gray-700 mb-1">Total Quantity</label>
                            <input id="packing-quantity" name="quantity" type="number" min="1" step="1" x-model.number="form.quantity" @input="maybeAutoSalary()"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="packing-session" class="block text-sm font-medium text-gray-700 mb-1">Session</label>
                            <select id="packing-session" name="session" x-model="form.session"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option>Morning</option>
                                <option>Evening</option>
                            </select>
                        </div>
                        <div>
                            <label for="packing-salary" class="block text-sm font-medium text-gray-700 mb-1">Salary Amount</label>
                            <input id="packing-salary" name="salary" type="number" min="0" step="0.01" x-model.number="form.salary"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Auto-fills as (rate × quantity). You can override.</p>
                        </div>
                    </div>
                    <button @click="addRecord()"
                            class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow transition flex items-center gap-2 w-full justify-center">
                        <i class="fas fa-save"></i> Enter Packing Record
                    </button>
                </div>

                <!-- Records Table -->
                <div class="border border-gray-200 rounded-xl shadow-soft p-5 mb-6">
                    <div class="bg-purple-50 p-4 -m-5 mb-5 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-700 flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-purple-500"></i> Employee Records
                        </h3>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Employee</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Qty</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Session</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Salary</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Adjustment</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Final Salary</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(record, index) in records" :key="index">
                                    <tr class="hover:bg-gray-50 border-t border-gray-200">
                                        <td class="px-4 py-3" x-text="record.employee_name"></td>
                                        <td class="px-4 py-3" x-text="record.date"></td>
                                        <td class="px-4 py-3" x-text="record.product_name"></td>
                                        <td class="px-4 py-3" x-text="record.quantity"></td>
                                        <td class="px-4 py-3" x-text="record.session"></td>
                                        <td class="px-4 py-3" x-text="formatMoney(record.salary)"></td>
                                        <td class="px-4 py-3">
                                            <input :id="`adjustment-${index}`" type="number" step="0.01" x-model.number="record.adjustment"
                                                   class="w-24 border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </td>
                                        <td class="px-4 py-3 font-medium" x-text="formatMoney(Number(record.salary) + Number(record.adjustment || 0))"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <p x-show="records.length === 0" class="text-center text-gray-500 py-4">
                            No records added yet
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3">
                    <button @click="open = false"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button @click="saveAllRecords()"
                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg shadow transition flex items-center gap-2">
                        <i class="fas fa-check"></i> Save All Records
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function packingModal({ employees, products }) {
            return {
                open: false,

                // from backend
                employees,            // [{emp_id,fname},...]
                products,             // [{id,product_name,per_rate},...]

                selectedEmployees: [],

                form: { date: "", product_id: "", quantity: "", session: "Morning", salary: "" },
                records: [],

                get availableEmployees() {
                    return this.employees.filter(e => !this.selectedEmployees.some(se => se.emp_id === e.emp_id));
                },

                selectEmployee(emp) { this.selectedEmployees.push(emp); },
                removeEmployee(index) { this.selectedEmployees.splice(index, 1); },

                productById(id) {
                    return this.products.find(p => Number(p.id) === Number(id));
                },
                productRate(id) {
                    const p = this.productById(id);
                    return p ? Number(p.per_rate) : 0;
                },
                formatMoney(v) {
                    const n = Number(v || 0);
                    return n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },

                maybeAutoSalary() {
                    const q = Number(this.form.quantity || 0);
                    const rate = this.productRate(this.form.product_id);
                    if (rate && q) {
                        // Auto fill (can be overridden)
                        this.form.salary = Number(rate * q).toFixed(2);
                    }
                },

                addRecord() {
                    if (!this.form.date || !this.form.product_id || !this.form.quantity || !this.form.salary) {
                        alert("Please fill in all fields before adding a record.");
                        return;
                    }
                    if (this.selectedEmployees.length === 0) {
                        alert("Please select at least one employee.");
                        return;
                    }

                    const product = this.productById(this.form.product_id);
                    const productName = product ? product.product_name : '';

                    // Push a row for each selected employee
                    this.selectedEmployees.forEach(emp => {
                        this.records.push({
                            employee_id: emp.emp_id,
                            employee_name: emp.fname,
                            product_id: this.form.product_id,
                            product_name: productName,
                            date: this.form.date,
                            quantity: Number(this.form.quantity),
                            session: this.form.session,
                            salary: Number(this.form.salary),
                            adjustment: 0
                        });
                    });

                    // Reset form but keep selected employees
                    this.form = { date: "", product_id: "", quantity: "", session: "Morning", salary: "" };
                },

                saveAllRecords() {
                    if (this.records.length === 0) {
                        alert("No records to save.");
                        return;
                    }

                    // Shape payload for backend
                    const payload = this.records.map(r => ({
                        employee_id: r.employee_id,
                        product_id: r.product_id,
                        date: r.date,
                        quantity: Number(r.quantity),
                        session: r.session,
                        salary: Number(r.salary),
                        adjustment: Number(r.adjustment || 0)
                    }));

                    // Call Livewire action
                    this.$wire.savePackingRecords(payload);
                }
            }
        }
    </script>
</div>