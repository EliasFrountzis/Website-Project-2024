$(document).ready(function() {
    function fetchTheses(searchTerm = '') {
        $.ajax({
            url: 'secretary_fetch_ongoing_theses.php',
            type: 'GET',
            data: { searchthesis: searchTerm },
            success: function(data) {
                try {
                    var results = JSON.parse(data);
                    $('#thesisResults').empty(); // καθαρισμός προηγούμενων αποτελεσμάτων

                    if (results.length > 0) {
                        results.forEach(function(item) {
                            console.log('Thesis Status:', item.the_status);

                            
                            let link; //ορισμός μεταβλητής για το κάθε λινκ
                            if (item.the_status === 'ongoing') {
                                link = `secretary_ongoing_thesis.php?id=${item.the_id}`;
                            } else if (item.the_status === 'under review') {
                                link = `secretary_underreview_thesis.php?id=${item.the_id}`;
                            } 

                            $('#thesisResults').append(
                                `<a class="create_subjects-box" href="${link}">
                                    <h3>${item.the_title}</h3>
                                    <p>Thesis ID: ${item.the_id}</p>
                                    <p>Status: ${item.the_status}</p>
                                </a>`
                            );
                        });
                    } else {
                        $('#thesisResults').append(`<div>NO "${searchTerm}" thesis found</div>`);
                    }
                } catch (e) {
                    $('#thesisResults').append("<div>Error parsing data.</div>");
                }
            },
            error: function() {
                $('#thesisResults').append("<div>Error fetching data.</div>");
            }
        });
    }

    $('#thesisSearchForm').on('submit', function(e) { 
        e.preventDefault(); 
        var searchTerm = $('#searchthesis').val();
        fetchTheses(searchTerm);
    });

    // φόρτωση διπλβμ.
    fetchTheses();
});
