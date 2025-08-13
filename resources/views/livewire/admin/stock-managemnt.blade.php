<div>
    <!-- Stock Management Module -->
    <h2 class="text-[#0d151c] text-2xl font-bold leading-tight tracking-[-0.015em] px-3 py-2">Stock Management</h2>
    <!-- Stock Summary Card -->
    <div class="flex flex-wrap gap-4 p-2">
        <div class="card dashboard-card flex-1 min-w-[180px] p-6">
            <p class="text-[#0d151c] text-base font-medium leading-normal">Total Magi Stock (kg)</p>
            <p class="text-[#0d151c] text-2xl font-bold leading-tight mt-2">1,250.50</p>
        </div>
        <div class="card dashboard-card flex-1 min-w-[180px] p-6">
            <p class="text-[#0d151c] text-base font-medium leading-normal">Total Papadam Stock (kg)</p>
            <p class="text-[#0d151c] text-2xl font-bold leading-tight mt-2">875.25</p>
        </div>
        <div class="card dashboard-card flex-1 min-w-[180px] p-6">
            <p class="text-[#0d151c] text-base font-medium leading-normal">Total Stock Value (LKR)</p>
            <p class="text-[#0d151c] text-2xl font-bold leading-tight mt-2">75,000.00</p>
        </div>
        <div class="card dashboard-card flex-1 min-w-[180px] p-6">
            <p class="text-[#0d151c] text-base font-medium leading-normal">Last Updated</p>
            <p class="text-[#0d151c] text-2xl font-bold leading-tight mt-2">2025-08-06</p>
        </div>
    </div>
    <div class="tabs-container flex px-4 border-b border-gray-200 mb-4">
        <div class="tab px-4 py-2 cursor-pointer font-medium text-gray-600 hover:text-gray-800 border-b-2 border-blue-500 text-blue-600">
            Magi Stock
        </div>
        <div class="tab px-4 py-2 cursor-pointer font-medium text-gray-600 hover:text-gray-800 border-b-2 border-transparent">
            Papadam Stock
        </div>
    </div>

    <div class="p-2">
        <div class="card p-6 mb-6 bg-white rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Add Stock Entry</h3>
            <form id="stock-form">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">Item Name</label>
                        <select class="form-control w-full border border-gray-300 rounded-md p-2">
                            <option value="">Select Item</option>
                            <option value="1">Magi Item</option>
                            <option value="2">Papadam Item</option>
                        </select>
                        
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Transaction Type</label>
                        <select class="form-control w-full border border-gray-300 rounded-md p-2">
                            <option value="">Select Type</option>
                            <option value="in">Stock In</option>
                            <option value="out">Stock Out</option>
                        </select>
                        
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Date</label>
                        <input type="date" class="form-control w-full border border-gray-300 rounded-md p-2" value="2025-08-06">
                        
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Quantity (kg)</label>
                        <input type="number" class="form-control w-full border border-gray-300 rounded-md p-2" placeholder="Enter quantity" value="100">
                        
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Unit Price (LKR)</label>
                        <input type="number" class="form-control w-full border border-gray-300 rounded-md p-2" placeholder="Enter unit price" value="50">
                        
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Total Value (LKR)</label>
                        <input type="text" class="form-control w-full bg-gray-100 border border-gray-300 rounded-md p-2" value="5,000.00" readonly>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea class="form-control w-full border border-gray-300 rounded-md p-2" rows="3" placeholder="Enter stock details">Received new batch of Magi stock</textarea>
                        
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Save Stock Entry</button>
                </div>
            </form>
        </div>

        <div class="card p-6 bg-white rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Stock Records</h3>
            <div class="table-container overflow-hidden rounded-xl">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="text-left px-4 py-3">Date</th>
                            <th class="text-left px-4 py-3">Item</th>
                            <th class="text-left px-4 py-3">Transaction Type</th>
                            <th class="text-left px-4 py-3">Quantity (kg)</th>
                            <th class="text-left px-4 py-3">Unit Price (LKR)</th>
                            <th class="text-left px-4 py-3">Total Value (LKR)</th>
                            <th class="text-left px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-3">2025-08-06</td>
                            <td class="px-4 py-3">Magi Item</td>
                            <td class="px-4 py-3">Stock In</td>
                            <td class="px-4 py-3">100</td>
                            <td class="px-4 py-3">50.00</td>
                            <td class="px-4 py-3">5,000.00</td>
                            <td class="px-4 py-3 flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">2025-08-05</td>
                            <td class="px-4 py-3">Papadam Item</td>
                            <td class="px-4 py-3">Stock Out</td>
                            <td class="px-4 py-3">50</td>
                            <td class="px-4 py-3">60.00</td>
                            <td class="px-4 py-3">3,000.00</td>
                            <td class="px-4 py-3 flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Empty state -->
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                                No records found for Magi.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- <!-- Edit Modal -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg w-full max-w-4xl mx-4 overflow-auto max-h-[90vh]">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Edit Stock Entry</h3>
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-1">Item Name</label>
                                <select class="form-control w-full border border-gray-300 rounded-md p-2">
                                    <option value="">Select Item</option>
                                    <option value="1" selected>Magi Item</option>
                                    <option value="2">Papadam Item</option>
                                </select>
                                <span class="text-red-600 text-sm">Item is required</span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Transaction Type</label>
                                <select class="form-control w-full border border-gray-300 rounded-md p-2">
                                    <option value="">Select Type</option>
                                    <option value="in" selected>Stock In</option>
                                    <option value="out">Stock Out</option>
                                </select>
                                <span class="text-red-600 text-sm">Transaction type is required</span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Date</label>
                                <input type="date" class="form-control w-full border border-gray-300 rounded-md p-2" value="2025-08-06">
                                <span class="text-red-600 text-sm">Date is required</span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Quantity (kg)</label>
                                <input type="number" class="form-control w-full border border-gray-300 rounded-md p-2" placeholder="Enter quantity" value="100">
                                <span class="text-red-600 text-sm">Quantity is required</span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Unit Price (LKR)</label>
                                <input type="number" class="form-control w-full border border-gray-300 rounded-md p-2" placeholder="Enter unit price" value="50">
                                <span class="text-red-600 text-sm">Unit price is required</span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Total Value (LKR)</label>
                                <input type="text" class="form-control w-full bg-gray-100 border border-gray-300 rounded-md p-2" value="5,000.00" readonly>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Description</label>
                                <textarea class="form-control w-full border border-gray-300 rounded-md p-2" rows="3" placeholder="Enter stock details">Received new batch of Magi stock</textarea>
                                <span class="text-red-600 text-sm">Description is required</span>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded mr-2">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                                Update Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div> 

        <!-- Delete Confirmation -->
        {{-- <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-semibold mb-4">Confirm Deletion</h3>
                <p class="mb-6">Are you sure you want to delete this stock record? This action cannot be undone.</p>
                <div class="flex justify-end">
                    <button class="px-4 py-2 bg-gray-500 text-white rounded mr-2">
                        Cancel
                    </button>
                    <button class="px-4 py-2 bg-red-600 text-white rounded">
                        Delete
                    </button>
                </div>
            </div>
        </div>  --}}
    </div>
</div>