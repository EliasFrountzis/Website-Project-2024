$(document).ready(function() {
    $('#search-btn').click(function() {
        var searchTerm = $('#searchstudent').val(); // Get the search term from the input
        var resultsContainer = $('#results-container'); // Select the container for student results
        resultsContainer.empty(); // Clear previous results

        // Make the AJAX call to search for students
        $.ajax({
            url: 'search_students.php?searchTerm=' + encodeURIComponent(searchTerm), // Correctly pass the search term
            method: 'GET',
            dataType: 'json', // Expect JSON response
            success: function(data) {
                console.log(data); // Log the response to check its format
                if (Array.isArray(data) && data.length > 0) {
                    $.each(data, function(index, student) {
                        // Create a new div for each student
                        var studentBox = $('<div class="show_subjects-box"></div>');

                        // Append student details
                        studentBox.append('<h3>' + student.st_surname + '</h3>');
                        studentBox.append('<p>' + student.st_name + '</p>');
                        studentBox.append('<p>' + student.st_id + '</p>');

                        // Create a form for thesis assignment
                        var form = $('<form action="update_thesis.php" method="POST"></form>');
                        form.append('<label for="the_select">Select Thesis:</label>');
                        
                        // Create a dropdown for available theses
                        var select = $('<select name="the_id" id="the_select"></select>');

                        // Fetch available theses for the current student
                        $.ajax({
                            url: 'fetch_thesis.php',
                            method: 'GET',
                            data: { student_id: student.st_id }, // Pass the student ID
                            dataType: 'json', // Expect JSON response
                            success: function(thesisData) {
                                if (Array.isArray(thesisData) && thesisData.length > 0) {
                                    $.each(thesisData, function(index, thesis) {
                                        select.append('<option value="' + thesis.the_id + '">' + thesis.the_title + '</option>');
                                    });
                                } else {
                                    select.append('<option>No theses available</option>');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching theses: ' + status + ' ' + error);
                                select.append('<option>Error fetching theses</option>'); // Inform user of error
                            }
                        });

                        // Append the select dropdown and hidden input for student ID
                        form.append(select);
                        form.append('<input type="hidden" name="student_id" value="' + student.st_id + '">');
                        form.append('<button type="submit">Assign Thesis</button>');

                        // Append the form to the student box
                        studentBox.append(form);

                        // Append the student box to the results container
                        resultsContainer.append(studentBox);
                    });
                } else {
                    resultsContainer.append('<div>No students found.</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + ' ' + error);
                console.log('Response Text: ' + xhr.responseText); // Log the raw response
                resultsContainer.append('<div>Error fetching students. Please try again.</div>'); // Inform user of error
            }
        });
    });
});