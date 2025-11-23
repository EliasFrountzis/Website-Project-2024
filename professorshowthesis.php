<!DOCTYPE html>
<html lang="el">
<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="utf-8">
    <title>Thesis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="welcome_nosingin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
</head>
<body>
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fa fa-bars"></i>
        </label>
        <img class="image" src="images/logo_oldmoney.png" alt="thesis old money logo">
       
        <ul>
            <li><a href="teacher_showthesissubjects.php">Thesis Subjects</a></li>
            <li><a href="teacherassingthesis.php">Assign Thesis to student</a></li>
            <li><a href="teacher_thesisList_3.php">Thesis List</a></li>
            <li><a href="teacher_answers_requests.php">Requests</a></li>
            <li><a href="teacher_statistics.html">Statistics</a></li>
			<li><a href="announcements.html">Announcements</a></li>
			<li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <div>
        <h1 class="form-title">Theses List</h1>
        <table id="dataTable">
            <thead>
                <tr>
                    <th>Topic</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date Assigned</th>
                    <th>Download PDF</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <!-- εδώ θα φορτωθούν τα δεδομένα-->
            </tbody>
        </table>
    </div>

  <div id="pdfContainer" style="width: 100%; height: 600px;"></div>

    <script>
      $(document).ready(function() {
    $.ajax({
        url: 'professorfetchthesis.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log(data); // έλεγχος δεδομένων
            var tableBody = $('#dataTable tbody'); 
            tableBody.empty();
            $.each(data, function(index, thesis) {
                var row = '<tr class="thesisRow">' +
                    '<td data-label="Topic">' + thesis.the_topic + '</td>' +
                    '<td data-label="Title">' + thesis.the_title + '</td>' +
                    '<td data-label="Description">' + thesis.the_description + '</td>' +
                    '<td data-label="Date Assigned">' + thesis.the_date_assignation + '</td>' +
                    '<td data-label="Download PDF"><a href="showpdf.php?id=' + thesis.the_id + '" target="_blank">Download PDF</a></td>' +
                    '<td data-label="Edit"><a href="professoredit_thesis.php?the_id=' + thesis.the_id + '">Edit</a></td>' +
                    '</tr>';
                tableBody.append(row);
            });
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + status + error);
        }
    });
});


        function fetchPDF(the_id) {
    $.ajax({
        url: 'showpdf.php?id=' + the_id,
        type: 'GET',
        xhrFields: {
            responseType: 'blob'
        },
        success: function(data) {
            // δημιουργία url gia τα blob 
            var url = URL.createObjectURL(data);
            var newIframe = $('<iframe>', {
                src: url,
                style: 'width: 100%; height: 600px;'
            });
            $('#pdfContainer').html(newIframe); // πρόσθεση του frame 
        },
        error: function() {
            $('#pdfContainer').text('An error occurred while fetching the PDF.');
        }
    });
}

    </script>
</body>
</html>
