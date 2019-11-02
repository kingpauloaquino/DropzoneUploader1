<html>
<head></head>
<body>
<script src="http://www.ipass-select.com/js/jquery-2.0.3.min.js"></script>
<script>
    $(document).ready(function() {


//        $.get('http://www.ipass-select.com/admin/income-report', function(data) {
//
//            var json = jQuery.parseJSON(data);
//
//            console.log(json);
//
//
//        })

        $.ajax({
            url: 'http://www.ipass-select.com/admin/income-report',
            dataType: "text",
            beforeSend: function () {
                var html = "<ul>";
                html += "<li>***</li>";
                html += "</ul>";

                $('#report').empty().prepend(html);
            },
            success: function(results) {
                var json = $.parseJSON(results);
                console.log(json);

                var html = "<ul>";
                html += "<li>"+json.TOTAL_AFUND+"</li>";
                html += "</ul>";

                $('#report').empty().prepend(html);
            }
        });

    })

</script>

<div id="report"></div>

</body>

</html>