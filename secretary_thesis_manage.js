$(document).ready(function() {
    let action = '';

    const urlParams = new URLSearchParams(window.location.search); //μέσω του url παίρνουμε το ιδ 
    const thesisId = urlParams.get('id');
    console.log('Thesis ID:', thesisId); 


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

    
    $(document).on('click', '.insertNumberBtn', function() {// ψειρισμός κουμπίων 
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

        console.log('Submitting number and text:', formData); 

        $.ajax({
            url: 'admin_insert_number.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response); 
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

    //για ακύρωση διπλωματικής
    $('#cancelThesisForm').submit(function(event) {
        event.preventDefault();

        var gsNumber = $('#gs-number').val();
        var year = $('#year').val();
        var reason = $('#reason').val();
        var thesisTitle = $('#thesis-title').val();

        console.log('Submitting cancellation:', {
            gs_number: gsNumber,
            year: year,
            reason: reason,
            thesis_title: thesisTitle
        }); // debug

        $.ajax({
            url: 'admincancel_thesis.php',
            method: 'POST',
            data: {
                gs_number: gsNumber,
                year: year,
                reason: reason,
                thesis_title: thesisTitle
            },
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
                console.error('Error submitting cancellation:', error);
                alert('There was an error submitting the cancellation.');
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
