@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Users -->
        <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">2,345</p>
                    <p class="text-sm text-green-500 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i> 12.5% from last month
                    </p>
                </div>
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                    <i class="fas fa-users text-blue-600 dark:text-blue-300 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">$48,234</p>
                    <p class="text-sm text-green-500 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i> 8.2% from last month
                    </p>
                </div>
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <i class="fas fa-dollar-sign text-green-600 dark:text-green-300 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">1,234</p>
                    <p class="text-sm text-red-500 mt-1">
                        <i class="fas fa-arrow-down mr-1"></i> 2.3% from last month
                    </p>
                </div>
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                    <i class="fas fa-shopping-cart text-purple-600 dark:text-purple-300 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Products</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">856</p>
                    <p class="text-sm text-green-500 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i> 5.7% from last month
                    </p>
                </div>
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <i class="fas fa-box text-yellow-600 dark:text-yellow-300 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Revenue Chart -->
        <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Revenue Overview</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Week
                    </button>
                    <button class="px-3 py-1 text-xs font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700">
                        Month
                    </button>
                    <button class="px-3 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Year
                    </button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Overview</h3>
                <div class="relative">
                    <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option selected>Last 7 days</option>
                        <option value="30">Last 30 days</option>
                        <option value="90">Last 90 days</option>
                    </select>
                </div>
            </div>
            <div class="h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h3>
            <a href="#" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Order ID</th>
                        <th scope="col" class="px-6 py-3">Customer</th>
                        <th scope="col" class="px-6 py-3">Product</th>
                        <th scope="col" class="px-6 py-3">Amount</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Order 1 -->
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">#ORD-0001</td>
                        <td class="px-6 py-4">John Doe</td>
                        <td class="px-6 py-4">Premium Plan</td>
                        <td class="px-6 py-4">$99.00</td>
                        <td class="px-6 py-4">
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Completed</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
                        </td>
                    </tr>
                    <!-- Order 2 -->
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">#ORD-0002</td>
                        <td class="px-6 py-4">Jane Smith</td>
                        <td class="px-6 py-4">Basic Plan</td>
                        <td class="px-6 py-4">$49.00</td>
                        <td class="px-6 py-4">
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Pending</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
                        </td>
                    </tr>
                    <!-- Order 3 -->
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">#ORD-0003</td>
                        <td class="px-6 py-4">Robert Johnson</td>
                        <td class="px-6 py-4">Pro Plan</td>
                        <td class="px-6 py-4">$199.00</td>
                        <td class="px-6 py-4">
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Cancelled</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>

@endpush