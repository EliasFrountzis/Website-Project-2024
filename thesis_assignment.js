$(document).ready(function() {
    function fetchThesisDetails(thesisId) {
        $.ajax({
            url: 'thesis_assignmentreq.php',
            type: 'GET',
            data: { id: thesisId },
            success: function(data) {
                try {
                    var result = JSON.parse(data);
                    $('#thesisDetail').empty(); // Clear previous details

                    // Append thesis details
                    $('#thesisDetail').append(
                        `<h3>Thesis ID: ${result.thesis_id}</h3>
                         <h4>Thesis Title: ${result.thesis_title}</h4>`
                    );

                    if (result.requests.length > 0) {
                        // Group requests by student
                        const students = {};
                        result.requests.forEach(function(request) {
                            const studentName = `${request.student_name} ${request.student_surname}`;
                            if (!students[studentName]) {
                                students[studentName] = [];
                            }
                            students[studentName].push(request);
                        });

                        // Display each student and their requests
                        for (const [studentName, requests] of Object.entries(students)) {
                            $('#thesisDetail').append(
                                `<h4>Student: ${studentName}</h4>`
                            );
                            requests.forEach(function(request) {
                                $('#thesisDetail').append(
                                    `<p>Request Professor ID: ${request.req_prof_id}</p>
                                     <p>Request Status: ${request.req_status}</p>
                                     <hr>`
                                );
                            });
                        }
                    } else {
                        $('#thesisDetail').append('<p>No requests found.</p>');
                    }

                    // Check if the logged-in professor is the supervising professor
                    if (result.is_supervising_prof) {
                        $('#thesisDetail').append(
                            `<button id="cancelAssignmentBtn">Cancel Assignment</button>`
                        );

                        $('#cancelAssignmentBtn').on('click', function() {
                            cancelAssignment(result.thesis_id);
                        });
                    }
                } catch (e) {
                    $('#thesisDetail').append("<div>Error parsing data.</div>");
                }
            },
            error: function() {
                $('#thesisDetail').append("<div>Error fetching data.</div>");
            }
        });
    }

    function cancelAssignment(thesisId) {
        $.ajax({
            url: 'cancel_assignment.php',
            type: 'POST',
            data: { id: thesisId },
            success: function(data) {
                alert('Assignment canceled and status updated to free.');
                fetchThesisDetails(thesisId); // Refresh details
            },
            error: function() {
                alert('Error canceling assignment.');
            }
        });
    }

    // Assuming thesisId is obtained from the URL
    var urlParams = new URLSearchParams(window.location.search);
    var thesisId = urlParams.get('id');
    fetchThesisDetails(thesisId);
});
