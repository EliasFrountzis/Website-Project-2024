$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const thesisId = urlParams.get('id');
    // Fetch the examination status
    $.ajax({
        url: 'teacher_thesis_management_under_review_2_get_presentation_notice.php?id=' + thesisId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                if (response.co_the_examination === 'Active') {
                    $('#textInputContainer').show(); // Show the text input container
                } else {
                    $('#examinationStatus').text('The student has not filled in the Thesis Examination Information.'); // Display the status directly
                }
            } else {
                alert(response.message); // Show error message
            }
        },
        error: function() {
            alert('Error fetching examination status.');
        }
    });

    // Handle the submit button click
    $('#submitButton').on('click', function() {
        var text = $('#thesisText').val(); // Get the text from the textarea
        if (text) {
            // Create a link and append it to the Announcements page
            var announcementLink = '<a href="Display_Announcement.html?text=' + encodeURIComponent(text) + '">' + text + '</a>';
            // Store the link in local storage or directly append it to the Announcements page
            $.ajax({
                url: 'teacher_thesis_management_under_review_2_update_presentation_notice.php',
                method: 'POST',
                data: {
                    thesis_id: thesisId, // Pass the thesis ID
                    presentation_notice: text // Pass the announcement link
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Announcement created and stored in the database! Check the Announcements page.');
                    } else {
                        alert(response.message); // Show error message
                    }
                },
                error: function() {
                    alert('Error storing the announcement in the database.');
                }
            });
            localStorage.setItem('announcementLink', announcementLink);
        } else {
            alert('Please enter some text.');
        }
    });
});
