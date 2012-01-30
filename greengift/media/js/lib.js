function requestData() {
    $.ajax({
        url: 'controls/delivery/news_content.php',
        data: { id: "blah"},
        success: function(data) {
        	var script = document.createElement( 'script' );
        	script.type = 'text/javascript';
        	script.text = data;
        	$("#payload").append( script );
        },
        cache: false
    });
}

$(document).ready(function() {
	$("#loader").remove();
	requestData();
});
