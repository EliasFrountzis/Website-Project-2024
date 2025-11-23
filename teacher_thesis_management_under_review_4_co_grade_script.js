
$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const thesisId = urlParams.get('id');
    // Fetch the grades using AJAX
    $.ajax({
        url: 'teacher_thesis_management_under_review_4_get_co_grade.php?id=' + thesisId, // The PHP script that fetches the grades
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Clear the grades list
                $('#grades-list').empty();
                // Loop through the grades and display them
                response.grades.forEach(function(grade) {
                    $('#grades-list').append(
                        '<div class="grade-item">' +
                            '<h3 style="display: inline;">Thesis Title: </h3>' + 
                            '<span style="display: inline; font-size: 1.2em; color: white;">' + grade.the_title + '</span>' + 
                            '<br>' + 
                            '<h3 style="display: inline;">Committee ID: </h3>' + 
                            '<span style="display: inline; font-size: 1.2em; color: white;">' + grade.co_id + '</span>' + 
                            '<br>' +
                            '<h3 style="display: inline;">Co Prof 1 Grade: </h3>' + 
                            '<span style="display: inline; font-size: 1.2em; color: white;">' + (grade.co_prof_1_grade !== null ? grade.co_prof_1_grade : 'N/A') + '</span>' + 
                            '<br>' +
                            '<h3 style="display: inline;">Co Prof 2 Grade: </h3>' + 
                            '<span style="display: inline; font-size: 1.2em; color: white;">' + (grade.co_prof_2_grade !== null ? grade.co_prof_2_grade : 'N/A') + '</span>' + 
                            '<br>' +
                            '<h3 style="display: inline;">Supervising Prof Grade: </h3>' + 
                            '<span style="display: inline; font-size: 1.2em; color: white;">' + (grade.co_supervising_prof_grade !== null ? grade.co_supervising_prof_grade : 'N/A') + '</span>' + 
                            '<br>' +
                            '<h3>Enter Your Grade:</h3>' +
                            '<form class="gradeForm" method="POST" action="update_grade.php" onsubmit="setTimeout(function(){ location.reload(); }, 100);">' + 
                            '<label for="grade-' + grade.co_id + '">Grade:</label>' + // Unique ID for the label
                            '<input type="number" id="grade-' + grade.co_id + '" name="grade" required>' + // Unique ID for the input
                            '<input type="hidden" name="co_id" value="' + grade.co_id + '">' + // Hidden input for committee ID
                            '<button type="submit" style="border: none; background: none; padding: 0;">' +
                            '<img class="check" src="images/check.png" alt="submit" style="width: 30px; height: 30px;">' +
                            '</button>' +
                            '</form>' +
                            '<br>' + 
                            '<br>' + 
                            '<div class="grade-message"></div>' + // Message area for feedback
                        '</div>' // Close grade-item
                    );
                });

                // Handle form submission for each grade form
                $('.gradeForm').on('submit', function(e) {
                    e.preventDefault(); // Prevent the default form submission

                    var form = $(this); // Get the current form
                    var co_id = form.find('input[name="co_id"]').val();
                    var grade = form.find('input[name="grade"]').val();

                    console.log({
                        co_id: co_id,
                        grade: grade,
                        gradeField: form.closest('.grade-item').find('h3:contains("Role:")').text().includes('Co Prof 1') ? 'co_prof_1_grade' : 'co_prof_2_grade'
                    });
                    

                    $.ajax({
                        url: 'teacher_thesis_management_under_review_4_get_co_update_grade.php', // The PHP script to update the grade
                        type: 'POST',
                        data: {
                            co_id: co_id,
                            grade: grade,
                            gradeField: (form.closest('.grade-item').find('h3:contains("Role:")').text().includes('Co Prof 1') ? 'co_prof_1_grade' : 'co_prof_2_grade')
                        },
                        success: function(response) {
                            form.siblings('.grade-message').text(response.message); // Show the message in the corresponding area
                        },
                        error: function() {
                            form.siblings('.grade-message').text('An error occurred while updating the grade.');
                        }
                    });
                });
            } else {
                $('#grades-list').text(response.message);
            }
        },
        error: function() {
            $('#grades-list').text('An error occurred while fetching the grades.');
        }
        
    });
});
