$(document).ready(function() {
    function fetchTheses(searchTerm = '') {
        $.ajax({
            url: 'fetch_thesis2.php',
            type: 'GET',
            data: { searchthesis: searchTerm },
            success: function(data) {
                try {
                    var results = JSON.parse(data);
                    $('#thesisResults').empty(); // Clear previous results

                    if (results.length > 0) {
                        results.forEach(function(item) {
                            console.log('Thesis Status:', item.the_status); // Log the status for debugging

                            // Determine the appropriate link based on the status
                            let link;
                            if (item.the_status === 'ongoing') {
                                link = `thesis_ongoing.php?id=${item.the_id}`;
                            } else if (item.the_status === 'under review') {
                                link = `teacher_thesis_management_under_review.php?id=${item.the_id}`;
                            } else if (item.the_status === 'under assignment') {
                                link = `thesis_assignment.php?id=${item.the_id}`;
                            } else {
                                link = `thesis_detail.php?id=${item.the_id}`;
                            }

                            $('#thesisResults').append(
                                `<a class="create_subjects-box" href="${link}">
                                    <h3>${item.the_title}</h3>
                                    <p>Thesis ID: ${item.the_id}</p>
                                    <p>Status: ${item.the_status}</p>
                                </a>`
                            );
                        });
                    } else {
                        $('#thesisResults').append(`<div>NO "${searchTerm}" thesis found</div>`);
                    }
                } catch (e) {
                    $('#thesisResults').append("<div>Error parsing data.</div>");
                }
            },
            error: function() {
                $('#thesisResults').append("<div>Error fetching data.</div>");
            }
        });
    }

    $('#thesisSearchForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting normally
        var searchTerm = $('#searchthesis').val();
        fetchTheses(searchTerm);
    });

    // Fetch all theses on page load
    fetchTheses();
});
