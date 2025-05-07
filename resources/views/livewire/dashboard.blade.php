<div>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-6">
        <div class="p-6 bg-white border-b border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-col space-y-2">
                <span class="text-gray-600">Total Students</span>
                <span class="text-4xl font-bold text-gray-800">{{ $totalStudents }}</span>
            </div>
        </div>
        <div class="p-6 bg-white border-b border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-col space-y-2">
                <span class="text-gray-600">Total Books</span>
                <span class="text-4xl font-bold text-gray-800">{{ $totalBooks }}</span>
            </div>
        </div>
        <div class="p-6 bg-white border-b border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-col space-y-2">
                <span class="text-gray-600">Total Books Borrowed</span>
                <span class="text-4xl font-bold text-gray-800">{{ $totalBooksBorrowed }}</span>
            </div>
        </div>
        <div class="p-6 bg-white border-b border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-col space-y-2">
                <span class="text-gray-600">Total Books Returned</span>
                <span class="text-4xl font-bold text-gray-800">{{ $totalBooksReturned }}</span>
            </div>
        </div>
        <div class="p-6 bg-white border-b border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-col space-y-2">
                <span class="text-gray-600">Overdue Books</span>
                <span class="text-4xl font-bold text-red-600">{{ $overdueBooks }}</span>
            </div>
        </div>
        <div class="p-6 bg-white border-b border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-col space-y-2">
                <span class="text-gray-600">Due Soon (3 days)</span>
                <span class="text-4xl font-bold text-yellow-600">{{ $dueSoonBooks }}</span>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Borrowing Trend -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Borrowing Trend</h3>
            <canvas id="monthlyBorrowingChart" height="300"></canvas>
        </div>

        <!-- Department-wise Distribution -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Department-wise Distribution</h3>
            <canvas id="departmentChart" height="300"></canvas>
        </div>

        <!-- Top Publishers -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Publishers</h3>
            <canvas id="publisherChart" height="300"></canvas>
        </div>

        <!-- Book Status Distribution -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Book Status Distribution</h3>
            <canvas id="statusChart" height="300"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('livewire:initialized', function () {
    // Monthly Borrowing Chart
    new Chart(document.getElementById('monthlyBorrowingChart'), {
        type: 'line',
        data: {
            labels: @json($monthlyBorrowingData['labels']),
            datasets: [{
                label: 'Books Borrowed',
                data: @json($monthlyBorrowingData['data']),
                borderColor: '#2f348f',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Department-wise Chart
    const departmentData = @json($departmentWiseData);
    new Chart(document.getElementById('departmentChart'), {
        type: 'doughnut',
        data: {
            labels: departmentData.map(item => item.name),
            datasets: [{
                data: departmentData.map(item => item.count),
                backgroundColor: [
                    '#2f348f',
                    '#4CAF50',
                    '#FFC107',
                    '#E91E63',
                    '#2196F3',
                    '#9C27B0'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });

    // Publisher Chart
    const publisherData = @json($bookCategoryData);
    new Chart(document.getElementById('publisherChart'), {
        type: 'bar',
        data: {
            labels: publisherData.map(item => item.publisher),
            datasets: [{
                label: 'Number of Books',
                data: publisherData.map(item => item.count),
                backgroundColor: '#2f348f'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Status Distribution Chart
    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: ['Borrowed', 'Returned'],
            datasets: [{
                data: [{{ $totalBooksBorrowed }}, {{ $totalBooksReturned }}],
                backgroundColor: ['#2f348f', '#4CAF50']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
});
</script>
@endpush
