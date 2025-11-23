$(document).ready(function() {
    // Get the thesis ID from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const thesisId = urlParams.get('id');

    if (thesisId) {
        // Fetch the student ID associated with the thesis
        $.ajax({
            url: 'teacher_thesis_management_under_review_1_get_thesis_text.php?id=' + thesisId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    fetchPDF(response.st_id); // Fetch the PDF for the student ID
                } else {
                    $('#thesis-content').text(response.message);
                }
            },
            error: function() {
                $('#thesis-content').text('An error occurred while fetching the student ID.');
            }
        });
    } else {
        $('#thesis-content').text('No Thesis ID provided.');
    }
});

function fetchPDF(st_id) {
    $.ajax({
        url: 'fetch_pdf.php?id=' + st_id, // Call the PHP script to fetch the PDF
        type: 'GET',
        xhrFields: {
            responseType: 'blob' // Set the response type to blob
        },
        success: function(data) {
            // Create a Blob from the PDF Stream
            var url = URL.createObjectURL(data);
            // Create a new iframe for the PDF
            var newIframe = $('<iframe>', {
                src: url,
                style: 'width: 100%; height: 600px;'
            });
            $('#pdfContainer').append(newIframe); // Append the new iframe to the container
        },
        error: function() {
            $('#thesis-content').text('An error occurred while fetching the PDF.');
        }
    });
}