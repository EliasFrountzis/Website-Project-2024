$(document).ready(function() {
    // Function to load requests
    function loadRequests() {
        $.ajax({
            url: 'requests.php', // Create this file to fetch requests
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    let requestsHtml = '';
                    data.requests.forEach(function(request, index) {
                        requestsHtml += `
                            <div class="show_request-box">
                                <h3> Request: ${index + 1} </h3>
                                <div style="display: flex; align-items: center;">
                                    <p style="margin: 0;">
                                        <h4 style="padding: 10px;"> Student Name: </h4> ${request.st_name} ${request.st_surname} | 
                                        <h4 style="padding: 10px;"> Request Date: </h4> ${request.req_request_date}
                                    </p>
                                    <div class="accept-btn" data-req-id="${request.req_id}" style="margin-left: 10px; cursor: pointer;">
                                        <img class="check" src="images/check.png" alt="Accept" style="width: 24px; height: 24px;"> <!-- Adjust the size as needed -->
                                    </div>
                                    <div class="decline-btn" data-req-id="${request.req_id}" style="margin-left: 10px; cursor: pointer;">
                                        <img class="check" src="images/x.png" alt="Decline" style="width: 24px; height: 24px;"> <!-- Adjust the size as needed -->
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    $('#request-container').html(requestsHtml);
                } else {
                    $('#request-container').html('<p>No requests found.</p>');
                }
            },
            error: function() {
                $('#request-container').html('<p>Error loading requests.</p>');
            }
        });
    }

    // Load requests on page load
    loadRequests();

    // Handle accept and decline actions
    $(document).on('click', '.accept-btn, .decline-btn', function() {
        const reqId = $(this).data('req-id');
        const action = $(this).hasClass('accept-btn') ? 'accept' : 'decline';

        $.ajax({
            url: 'requests.php',
            method: 'POST',
            data: { req_id: reqId, action: action },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                loadRequests(); // Reload requests after action
            },
            error: function() {
                alert('Error processing request.');
            }
        });
    });
});