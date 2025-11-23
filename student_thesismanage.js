$(document).ready(function() {
    let supervisingProfessorId = null;

    function fetchThesisInfo() {
        $.ajax({
            url: 'fetch_thesis_info.php',  // Fetch thesis info
            type: 'GET',
            success: function(data) {
                try {
                    var result = JSON.parse(data);
                    $('#thesisName').text(`Thesis Title: ${result.thesis_title}`);
                    $('#thesisId').text(`Thesis ID: ${result.thesis_id}`);
                    $('#thesisStatus').text(`Thesis Status: ${result.thesis_status}`);
                    $('#supervisingProfessor').text(`Supervising Professor: ${result.supervising_prof_name} ${result.supervising_prof_surname}`);
                    supervisingProfessorId = result.supervising_prof_id; // Store the supervising professor ID

                    if (result.thesis_status === 'under assignment') {
                        $('#showSearchBarBtn').show();
                        $('#searchSection').show();
                    } else if (result.thesis_status === 'under review') {
                        $('#reviewSection').show();
                        fetchUploadedFiles();
                        fetchAddedUrls();
                    } else if (result.thesis_status === 'finished') {
                        $('#finishedSection').show();
                        $('#supervisingProfessorInfo').text(`Supervising Professor: ${result.supervising_prof_name} ${result.supervising_prof_surname}`);
                        $('#thesisTitle').text(`Thesis Title: ${result.thesis_title}`);
                        $('#dateOfCompletion').text(`Date of Completion: ${result.the_date_completion}`);
                        $('#presentationNotice').text(`Presentation Notice: ${result.the_presentation_notice}`);
                        $('#thesisGrade').text(`Grade: ${result.the_grade}`);
                    }
                } catch (e) {
                    $('#generalInfo').append("<div>Error parsing data.</div>");
                }
            },
            error: function() {
                $('#generalInfo').append("<div>Error fetching data.</div>");
            }
        });
    }

    function fetchProfessors(searchTerm = '') {
        $.ajax({
            url: 'student_thesismanagefetch.php',
            type: 'GET',
            data: { searchprofessor: searchTerm },
            success: function(data) {
                try {
                    var results = JSON.parse(data);
                    $('#professorResults').empty(); // Clear previous results

                    if (results.length > 0) {
                        results.forEach(function(item) {
                            if (item.prof_id !== supervisingProfessorId) { // Exclude supervising professor
                                $('#professorResults').append(
                                    `<div class="show_subjects-box" data-prof-id="${item.prof_id}">
                                        <h3>${item.prof_name} ${item.prof_surname}</h3>
                                        <p>Professor ID: ${item.prof_id}</p>
                                        <p>Email: ${item.prof_email}</p>
                                        <button class="request-supervision-btn" data-id="${item.prof_id}">Request Supervision</button>
                                    </div>`
                                );
                            }
                        });

                        $('.request-supervision-btn').on('click', function() {
                            var professorId = $(this).data('id');
                            sendSupervisionRequest(professorId);
                        });
                    } else {
                        $('#professorResults').append("<div>No professors found.</div>");
                    }
                } catch (e) {
                    $('#professorResults').append("<div>Error parsing data.</div>");
                }
            },
            error: function() {
                $('#professorResults').append("<div>Error fetching data.</div>");
            }
        });
    }

    function sendSupervisionRequest(professorId) {
        $.ajax({
            url: 'request_supervision.php',
            type: 'POST',
            data: { professor_id: professorId },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.success) {
                    alert('Supervision request sent.');
                    // Remove the professor's box from the results
                    $(`div[data-prof-id="${professorId}"]`).remove();
                } else {
                    alert('Error sending request: ' + response.error);
                }
            },
            error: function() {
                alert('Error sending request.');
            }
        });
    }

    $('#professorSearchForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting normally
        var searchTerm = $('#searchprofessor').val();
        fetchProfessors(searchTerm);
    });

    $('#showSearchBarBtn').on('click', function() {
        $('#searchSection').slideToggle();
    });

    // Handle thesis file upload
    $('#thesisUploadForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

        var formData = new FormData(this);
        $.ajax({
            url: 'upload_thesis_file.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                alert('Thesis file uploaded successfully.');
                fetchUploadedFiles(); // Refresh the list of uploaded files
            },
            error: function() {
                alert('Error uploading thesis file.');
            }
        });
    });

    // Handle adding supporting materials URL
    $('#addUrlButton').on('click', function() {
        var url = $('#supportingMaterialUrl').val();
        if (url) {
            $.ajax({
                url: 'add_supporting_material_url.php',
                type: 'POST',
                data: { url: url },
                success: function(data) {
                    alert('URL added successfully.');
                    fetchAddedUrls(); // Refresh the list of added URLs
                },
                error: function() {
                    alert('Error adding URL.');
                }
            });
        } else {
            alert('Please enter a URL.');
        }
    });

    // Handle examination form submission
    $('#examinationForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

        var formData = {
            dateTime: $('#examinationDateTime').val(),
            mode: $('input[name="examinationMode"]:checked').val(),
            venue: $('#examinationVenue').val()
        };

        $.ajax({
            url: 'submit_examination_details.php',
            type: 'POST',
            data: formData,
            success: function(data) {
                alert('Examination details submitted successfully.');
            },
            error: function() {
                alert('Error submitting examination details.');
            }
        });
    });

    function fetchUploadedFiles() {
        $.ajax({
            url: 'fetch_uploaded_files.php',
            type: 'GET',
            success: function(data) {
                try {
                    var results = JSON.parse(data);
                    $('#uploadedFiles').empty(); // Clear previous results
                    if (results.length > 0) {
                        results.forEach(function(item) {
                            $('#uploadedFiles').append(`<div><a href="${item.file_path}" target="_blank">${item.file_name}</a></div>`);
                        });
                    } else {
                        $('#uploadedFiles').append("<div>No uploaded files found.</div>");
                    }
                } catch (e) {
                    $('#uploadedFiles').append("<div>Error parsing data.</div>");
                }
            },
            error: function() {
                $('#uploadedFiles').append("<div>Error fetching data.</div>");
            }
        });
    }

    function fetchAddedUrls() {
        $.ajax({
            url: 'fetch_added_urls.php',
            type: 'GET',
            success: function(data) {
                try {
                    var results = JSON.parse(data);
                    $('#addedUrls').empty(); // Clear previous results
                    if (results.length > 0) {
                        results.forEach(function(item) {
                            $('#addedUrls').append(`<div><a href="${item.url}" target="_blank">${item.url}</a></div>`);
                        });
                    } else {
                        $('#addedUrls').append("<div>No added URLs found.</div>");
                    }
                } catch (e) {
                    $('#addedUrls').append("<div>Error parsing data.</div>");
                }
            },
            error: function() {
                $('#addedUrls').append("<div>Error fetching data.</div>");
            }
        });
    }

    // Fetch thesis info on page load
    fetchThesisInfo();
});
