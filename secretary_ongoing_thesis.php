<!DOCTYPE html>
<html lang="el">

<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="utf-8">
    <title>Theses Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="welcome_nosingin.css">

</head>

<body>
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fa fa-bars"></i>
        </label>
        <img class="image" src="images/logo_oldmoney.png" alt="thesis old money logo">
        <ul>
            <li><a href="secretary_showthesis.php">Thesis </a></li>
            <li><a href="secretary_addjson.php">JSON</a></li>
            <li><a href="secretary_creatingaccounts.php">Creating accounts</a></li>
            <li><a href="admin_thesismanage.html">Thesis management</a></li>
            <li><a href="announcements.html">Announcements</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="seethroughbox2">
        <div id="thesisDetails"></div>

        <!-- φόρμα για την εισαγωγή ΑΠ και του πρακτικού -->
        <div id="insertNumberModal" style="display:none;">
    <h2>Insert Protocol Number and Text</h2>
    <form id="insertNumberForm">
        <div class="user-input-box">
            <label for="number">Number:</label>
            <input type="text"  id="number" name="number" required>
        </div>
        <div class="user-input-box">
            <label for="text">Text:</label>
            <input type="text"  id="text" name="text" required>
        </div>
        <input type="hidden" id="thesis-title" name="thesis-title">
        <div class="form-submit-btn">
        <button type="submit" class="secrbutton">Submit</button>
        </div>
    </form>
</div>


        <!-- φόρμα για την εισαγωγή ΓΣ , έτους , λόγου -->
        <div id="thesisModal" style="display:none;">
            <h2>Cancel Thesis</h2>
            <form id="cancelThesisForm">
            <div class="user-input-box">
                <label for="gs-number">GS Number:</label>
                <input type="text" id="gs-number" name="gs-number" required>
            </div>
                
                <div class="user-input-box">
                <label for="year">Year:</label>
                <input type="text" id="year" name="year" required>
                </div>
                
                <div class="user-input-box">
                <label for="reason">Reason:</label>
                <input type="text" id="reason" name="reason" required>
                </div>
                
                <div class="user-input-box">
                <input type="hidden" id="thesis-title" name="thesis-title">
                <button type="submit" class="secrbutton">Submit</button>
                </div>
            </form>

        </div>
    </div>

<script > $(document).ready(function() {
    let action = '';

    //μέσω το url πέρνουμε το id της διπλωματικής
    const urlParams = new URLSearchParams(window.location.search);
    const thesisId = urlParams.get('id');
    console.log('Thesis ID:', thesisId); // debug


    function fetchThesis(thesisId) {
        $.ajax({
            url: 'secretary_single_fetch.php',
            type: 'GET',
            data: { id: thesisId },
            success: function(data) {
                console.log('AJAX success:', data);
                try {
                    const thesis = JSON.parse(data);
                    console.log('Parsed thesis data:', thesis);
                    if (thesis.error) {
                        $('#thesisDetails').html(`<div>${thesis.error}</div>`);
                    } else {
                        $('#thesisDetails').html(`
                            <div class="thesisItem">
                                <h3>${thesis.the_title}</h3>
                                <button class="insertNumberBtn" data-title="${thesis.the_title}">Insert Number</button>
                                <button class="cancelThesisBtn" data-title="${thesis.the_title}">Cancel Thesis</button>
                            </div>
                        `);
                    }
                } catch (e) {
                    console.error('Parsing error:', e);
                    $('#thesisDetails').html("<div>Error parsing data.</div>");
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                $('#thesisDetails').html(`<div>Error fetching data: ${status} - ${error}</div>`);
            }
        });
    }

    
    $(document).on('click', '.insertNumberBtn', function() { // ψειρισμός κουμπίων 
        action = 'insert';
        $('#thesis-title').val($(this).data('title'));
        $('#insertNumberModal').show();
        $('#thesisModal').hide();
    });

    $(document).on('click', '.cancelThesisBtn', function() {
        action = 'cancel';
        $('#thesis-title').val($(this).data('title'));
        $('#thesisModal').show();
        $('#insertNumberModal').hide();
    });

    // για εισαγωγή AP
    $('#insertNumberForm').submit(function(event) {
        event.preventDefault();

        var formData = {
            number: $('#number').val(),
            text: $('#text').val(),
            thesis_title: $('#thesis-title').val()
        };

        console.log('Submitting number and text:', formData); // debug

        $.ajax({
            url: 'admin_insert_number.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response); // debug
                if (response.status === 'success') {
                    alert('Number and text inserted successfully!');
                    $('#insertNumberModal').hide();
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error submitting number and text:', error);
                alert('There was an error submitting the number and text.');
            }
        });
    });

    $('#cancelThesisForm').submit(function(event) {
    event.preventDefault();

    var formData = {
        gs_number: $('#gs-number').val(),
        year: $('#year').val(),
        reason: $('#reason').val(),
        thesis_title: $('#thesis-title').val()
    };

    console.log('Form Data:', formData);

    $.ajax({
        url: 'admincancel_thesis.php',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            console.log('Response:', response);
            if (response.status === 'success') {
                alert('Thesis cancelled successfully!');
                $('#thesisModal').hide();
            } else {
                alert('Error: ' + response.error);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error submitting cancellation:', xhr.responseText);
            alert('There was an error submitting the cancellation: ' + xhr.responseText);
        }
    });
});


    

    // φόρτωση του θέματοσ
    if (thesisId) {
        fetchThesis(thesisId);
    } else {
        $('#thesisDetails').html("<div>No thesis ID provided.</div>");
    }
});
</script>
</body>

</html>
