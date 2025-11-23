$(document).ready(function() {
    function fetchTheses() {
        $.ajax({
            url: 'fetch_thesis3.php',
            type: 'GET',
            success: function(data) {
                try {
                    var results = JSON.parse(data);
                    $('#thesisResults').empty(); // Clear previous results

                    results.forEach(function(item) {
                        var buttonsHtml = '';

                        // Check correct status values
                        if (item.thesis_status === 'ongoing') {
                            buttonsHtml = `
                                <button class="add-protocol-number-btn">Add Protocol Number</button>
                                <form class="protocol-number-form" style="display: none;">
                                    <input type="text" class="protocol-number" name="protocolNumber" placeholder="Protocol Number">
                                    <button type="submit">Submit</button>
                                </form>
                                <button class="cancel-thesis-btn">Cancel Thesis</button>
                                <form class="cancel-thesis-form" style="display: none;">
                                    <input type="text" class="GSNumber" name="GSNumber" placeholder="GS Number">
                                    <input type="text" class="GSYear" name="GSYear" placeholder="GS Year">
                                    <textarea class="cancelText" name="cancelText" placeholder="Cancellation Reason"></textarea>
                                    <button type="submit">Submit Cancellation</button>
                                </form>
                            `;
                        } else if (item.thesis_status === 'under review') {
                            if (item.thesis_url_final) {
                                buttonsHtml = `<button class="finish-thesis-btn">Finish Thesis</button>`;
                            }
                        }

                        $('#thesisResults').append(
                            `<div class="show_subjects-box" data-thesis-id="${item.thesis_id}">
                                <h3>${item.thesis_title}</h3>
                                <p>Status: ${item.thesis_status}</p>
                                <div class="thesis-details">
                                    <p id="thesisDetails">Details for Thesis ID: ${item.thesis_id}</p>
                                    ${buttonsHtml}
                                </div>
                            </div>`
                        );
                    });

                    // Re-attach event handlers
                    attachEventHandlers();
                } catch (e) {
                    $('#thesisResults').append("<div>Error parsing data.</div>");
                }
            },
            error: function() {
                $('#thesisResults').append("<div>Error fetching data.</div>");
            }
        });
    }

    function attachEventHandlers() {
        $('.add-protocol-number-btn').on('click', function() {
            var form = $(this).siblings('.protocol-number-form');
            form.slideToggle();
        });

        $('.cancel-thesis-btn').on('click', function() {
            var form = $(this).siblings('.cancel-thesis-form');
            form.slideToggle();
        });

        $('.protocol-number-form').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting normally
            var protocolNumber = $(this).find('.protocol-number').val();
            var thesisId = $(this).closest('.show_subjects-box').data('thesis-id');

            // AJAX request to submit the protocol number
            $.ajax({
                url: 'submit_protocol_number.php', // Ensure this PHP file handles the submission
                type: 'POST',
                data: {
                    thesis_id: thesisId,
                    protocol_number: protocolNumber
                },
                success: function(response) {
                    alert(`Protocol Number ${protocolNumber} submitted for Thesis ID ${thesisId}.`);
                    fetchTheses(); // Refresh the list
                },
                error: function() {
                    alert('Error submitting the protocol number.');
                }
            });
        });

        $('.cancel-thesis-form').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting normally
        
            var GSNumber = $(this).find('.GSNumber').val();
            var GSYear = $(this).find('.GSYear').val();
            var cancelText = $(this).find('.cancelText').val();
            var thesisId = $(this).closest('.show_subjects-box').data('thesis-id');
        
            // Log the thesis ID for debugging
            console.log("Thesis ID for cancellation: ", thesisId);
        
            // AJAX request to insert into admin_documents and update the status
            $.ajax({
                url: 'insert_admin_documents.php', // New PHP file for inserting data
                type: 'POST',
                data: {
                    thesis_id: thesisId,
                    GSNumber: GSNumber,
                    GSYear: GSYear,
                    cancelText: cancelText
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        alert(`Thesis ID ${thesisId} cancelled and data inserted.`);
                    } else {
                        alert(result.error); // Show error if insertion fails
                    }
                    fetchTheses(); // Refresh the list
                },
                error: function() {
                    alert('Error cancelling the thesis and inserting data.');
                }
            });
        });

        $('.finish-thesis-btn').on('click', function() {
            var thesisId = $(this).closest('.show_subjects-box').data('thesis-id');

            // AJAX request to finish the thesis
            $.ajax({
                url: 'check_and_finish_thesis.php', // Ensure this file handles the finishing logic
                type: 'POST',
                data: {
                    thesis_id: thesisId
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        alert(`Thesis ID ${thesisId} marked as finished.`);
                    } else {
                        alert(result.error); // Show error if the thesis cannot be finished
                    }
                    fetchTheses(); // Refresh the list
                },
                error: function() {
                    alert('Error finishing the thesis.');
                }
            });
        });
    }

    // Fetch all theses on page load
    fetchTheses();
});
