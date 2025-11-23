
$(document).ready(function() {
    // Function to load chart data
    function loadChartData() {
        $.ajax({
            url: 'super_thesis_grade_data.php', // This is the PHP file that fetches the data
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    const labels = data.labels;
                    const values = data.values;

                    const ctx = document.getElementById('thesisChart').getContext('2d');
                    const thesisChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Average Thesis Grades',
                                data: values,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Grades',
                                        color: 'rgba(255, 255, 255, 1)' // White text color for Y-axis 
                                    },
                                    ticks: {
                                        color: 'rgba(255, 255, 255, 1)', // White ticks for Y-axis
                                        grid: {
                                            color: 'rgba(255, 255, 255, 0.2)' // Light grid lines for Y-axis
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(255, 255, 255, 0.2)' // Light grid lines for Y-axis
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Thesis Titles',
                                        color: 'rgba(255, 255, 255, 1)' // White text color for X-axis
                                    },
                                    ticks: {
                                        color: 'rgba(255, 255, 255, 1)', // Brighter color for X-axis ticks (e.g., gold)
                                        grid: {
                                            color: 'rgba(255, 255, 255, 0.2)' // Light grid lines for X-axis
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(255, 255, 255, 0.2)' // Light grid lines for X-axis
                                    }
                                }
                            }
                        }
                    });
                } else {
                    alert(data.message);
                }
            },
            error: function() {
                alert('Error loading chart data.');
            }
        });
    }

    // Load chart data on page load
    loadChartData();
});
