/**
 * Dashboard Charts Initialization
 * Handles all chart visualizations for the dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts if they exist on the page
    initRevenueChart();
    initCategoryChart();
    
    // Add event listeners for chart period toggles
    setupChartToggles();
});

/**
 * Initialize Revenue Chart
 */
function initRevenueChart() {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;
    
    // Sample data for the chart
    const monthlyData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [{
            label: 'Revenue',
            data: [12000, 19000, 15000, 25000, 22000, 30000, 28000],
            backgroundColor: 'rgba(56, 189, 248, 0.1)',
            borderColor: '#38bdf8',
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    };
    
    const weeklyData = {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [{
            label: 'Revenue',
            data: [12000, 19000, 15000, 22000],
            backgroundColor: 'rgba(56, 189, 248, 0.1)',
            borderColor: '#38bdf8',
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    };
    
    const yearlyData = {
        labels: ['Q1', 'Q2', 'Q3', 'Q4'],
        datasets: [{
            label: 'Revenue',
            data: [45000, 52000, 48000, 60000],
            backgroundColor: 'rgba(56, 189, 248, 0.1)',
            borderColor: '#38bdf8',
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    };
    
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: '#1f2937',
                titleColor: '#f9fafb',
                bodyColor: '#f9fafb',
                borderColor: '#374151',
                borderWidth: 1,
                padding: 12,
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        return '$' + context.raw.toLocaleString();
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(229, 231, 235, 0.2)',
                    drawBorder: false
                },
                ticks: {
                    color: '#9ca3af',
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            },
            x: {
                grid: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    color: '#9ca3af'
                }
            }
        }
    };
    
    // Create chart instance
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: monthlyData,
        options: chartOptions
    });
    
    // Store chart instance for updates
    window.dashboardCharts = window.dashboardCharts || {};
    window.dashboardCharts.revenue = revenueChart;
    window.dashboardCharts.revenueData = {
        monthly: monthlyData,
        weekly: weeklyData,
        yearly: yearlyData
    };
}

/**
 * Initialize Category Chart
 */
function initCategoryChart() {
    const ctx = document.getElementById('categoryChart');
    if (!ctx) return;
    
    const categoryData = {
        labels: ['Electronics', 'Fashion', 'Home & Garden', 'Books', 'Beauty'],
        datasets: [{
            data: [35, 25, 15, 15, 10],
            backgroundColor: [
                '#38bdf8',
                '#818cf8',
                '#f472b6',
                '#f59e0b',
                '#10b981'
            ],
            borderWidth: 0,
            borderRadius: 4
        }]
    };
    
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    color: '#9ca3af',
                    padding: 20,
                    boxWidth: 12,
                    font: {
                        size: 12
                    },
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                backgroundColor: '#1f2937',
                titleColor: '#f9fafb',
                bodyColor: '#f9fafb',
                borderColor: '#374151',
                borderWidth: 1,
                padding: 12,
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = Math.round((value / total) * 100);
                        return `${label}: ${percentage}% (${value})`;
                    }
                }
            }
        }
    };
    
    // Create chart instance
    const categoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: categoryData,
        options: chartOptions
    });
    
    // Store chart instance for updates
    window.dashboardCharts = window.dashboardCharts || {};
    window.dashboardCharts.category = categoryChart;
    
    // Update charts when theme changes
    const observer = new MutationObserver(() => {
        categoryChart.update();
    });
    
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
}

/**
 * Setup chart period toggles
 */
function setupChartToggles() {
    const periodToggles = document.querySelectorAll('[data-chart-period]');
    
    periodToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const period = this.getAttribute('data-chart-period');
            
            // Update active state
            periodToggles.forEach(t => {
                if (t === this) {
                    t.classList.remove('text-gray-700', 'bg-gray-100', 'dark:bg-gray-700', 'dark:text-gray-300');
                    t.classList.add('text-white', 'bg-primary-600', 'dark:bg-primary-700', 'dark:hover:bg-primary-800');
                } else {
                    t.classList.remove('text-white', 'bg-primary-600', 'dark:bg-primary-700', 'dark:hover:bg-primary-800');
                    t.classList.add('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200', 'dark:bg-gray-700', 'dark:text-gray-300', 'dark:hover:bg-gray-600');
                }
            });
            
            // Update chart data if it's the revenue chart
            if (window.dashboardCharts && window.dashboardCharts.revenue && window.dashboardCharts.revenueData) {
                const chart = window.dashboardCharts.revenue;
                const data = window.dashboardCharts.revenueData[period];
                
                if (data) {
                    chart.data.labels = data.labels;
                    chart.data.datasets[0].data = data.datasets[0].data;
                    chart.update();
                }
            }
        });
    });
}

// Make functions available globally
window.updateChartPeriod = function(period) {
    if (window.dashboardCharts && window.dashboardCharts.revenue && window.dashboardCharts.revenueData) {
        const chart = window.dashboardCharts.revenue;
        const data = window.dashboardCharts.revenueData[period];
        
        if (data) {
            chart.data.labels = data.labels;
            chart.data.datasets[0].data = data.datasets[0].data;
            chart.update();
        }
    }
};
